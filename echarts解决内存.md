# echart 问题定位

## 一、背景

```plain
initScoket() {
      let that = this;
      let token = localStorage.getItem("token");
      if (typeof WebSocket === "undefined") {
        alert("您的浏览器不支持socket");
      } else {
        that.socket = new WebSocket(
          "ws://" + window.location.hostname + ":8000/clu" + "?token=" + token
        );
        that.socket.onopen = that.onopen;
        that.socket.onerror = that.error;
        that.socket.onmessage = that.getMessage;
        that.$nextTick(() => {
          that.drawLine();
          that.drawLineChart2();
        });
      }
    },
getMessage(msg) {
    let obj = JSON.parse(msg.data);   
    // 数据收到后修改
    if(obj.cluio){
        this.changebusinessIo(obj.cluio)
    }else if(obj.svcio){
        this.changebackinessIo(obj.svcio)
    }
},
```

## 二、重复init 导致echarts重复

### 1、问题写法

```plain
drawLine() {
  this.mychart = echarts.init(document.getElementById("myChart1"));
}
```

### 2、解决改法

#### a、使用 echarts init 之前先判断是否存在实例

```plain
if (mychart) {
    const chart = echarts.getInstanceByDom(document.getElementById("myChart1"));
    if (chart) {
        echarts.dispose(chart);
    }
}
mychart = echarts.init(document.getElementById("myChart1"));
```

#### b、如果 ECharts 存在，先 dispose 销毁后，再调用 init

```plain
const chart = echarts.getInstanceByDom(document.getElementById(dom)); 
if (chart === undefined) {  
    chart = echarts.init(document.getElementById(dom));
}
```

## 三、数据量过大，可采用降采样策略或数据分段渲染

### 1、降采样策略

#### a、实现步骤

`sampling` 属性提供了几个可选值，配置不同的值可以有效的优化图表的绘制效率，如下所示：

`sampling` 的可选值有以下几个：

- `lttb`: 采用 `Largest-Triangle-Three-Bucket` 算法，可以最大程度保证采样后线条的趋势，形状和极值。
- `average`: 取过滤点的平均值
- `min`: 取过滤点的最小值
- `max`: 取过滤点的最大值
- `minmax`: 取过滤点绝对值的最大极值 (从 v5.5.0 开始支持)
- `sum`: 取过滤点的和

具体方案是配置 `series` 的 `sampling`，最终表示使用的是 ECharts 的哪一种采样策略，ECharts 内部机制实现优化策略：

```plain
var option = {
  series: {
    type: "line",
    sampling: "lttb", // 最大程度保证采样后线条的趋势，形状和极值。
  },
};
```

以上代码表示使用 'lttb' 降采样策略，实现降低性能消耗的效果。



### 2、数据分段渲染

#### a、背景

为了能让 ECharts 避免一次性渲染的数据量过大，因此可以考虑使用 `dataZoom` 的区域缩放属性实现首次渲染 ECharts 图表时就进行区域渲染，减少整体渲染带来的性能消耗。

#### b、实现步骤

dataZoom 组件提供了几个属性，利用这几个属性可以控制图表渲染时的性能问题，如下所示：

- `start`: 数据窗口范围的起始百分比。范围是：0 ~ 100。表示 0% ~ 100%。
- `end`: 数据窗口范围的结束百分比。范围是：0 ~ 100。
- `minSpan`: 用于限制窗口大小的最小值（百分比值），取值范围是 0 ~ 100。
- `maxSpan`: 用于限制窗口大小的最大值（百分比值），取值范围是 0 ~ 100。

具体方案是使用 `start` 和 `end` 控制 ECharts 图表初次渲染时滑块所处的位置以及数据窗口范围，使用 `minSpan` 和 `maxSpan` 用于限制窗口大小的最小值和最大值，最终限制的图表的可视区域显示范围，如下代码所示：

```javascript
var option = {
  dataZoom: [
    {
      type: "slider",
      xAxisIndex: [0],
      start: 0,
      end: 1,
      minSpan: 0,
      maxSpan: 10,
    },
  ],
};
```

以上代码表示 ECharts 图表初始化时，数据窗口从 x 轴 `0 ~ 1%` 范围内显示，最大的窗口显示范围为 `10%`





## 四、建议少把chart实例赋值在this或者global上，this对象一直存在不会被回收

### 1、可以不再绑定在vue的单文件组件的data中

```plain
<script>
import echarts from "echarts";
import { getSummary , getHost } from "@/api/tcfs";
import { getClusterOsdsWarns } from "@/api/cluster";
import { getServiceInfo } from "@/api/serviceSetting";
import IntroInfo from "@/components/IntroInfo"
import { ForcastUsage } from "./components/index"
let mychart = null, mychart2 = null;
export default {
    // ...
}
</script>
```

### 2、如果依然要绑定的话，请高效使用setOption() 这个方法。

`**dispose()**` **方法**：销毁实例后会释放所有绑定的事件和资源，必须重新初始化。

`**setOption()**` **的高效使用**：使用 `setOption` 更新数据比重新创建实例更轻量。

**检查实例状态**：始终通过 `echarts.getInstanceByDom` 检查实例状态，避免重复初始化。

```plain
if (mychart) {
    const chart = echarts.getInstanceByDom(document.getElementById("myChart1"));
    if (chart) {
        echarts.dispose(chart);
    }
}
```

## 五、参考资料

https://github.com/apache/echarts/issues/7125

https://segmentfault.com/q/1010000020716956

https://juejin.cn/post/6858048873844178951?from=search-suggest

https://juejin.cn/post/7350152569756680227?from=search-suggest