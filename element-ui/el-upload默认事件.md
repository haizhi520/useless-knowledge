# el-upload取消上传的默认事件

## 一、示例代码

```vue
<el-upload
  class="upload-demo"
  ref="upload"
  action="https://jsonplaceholder.typicode.com/posts/"
  :on-preview="handlePreview"
  :on-remove="handleRemove"
  :file-list="fileList"
  :auto-upload="false">
  <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
  <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button>
  <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
</el-upload>
<script>
  export default {
    data() {
      return {
        fileList: [{name: 'food.jpeg', url: 'https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100'}, {name: 'food2.jpeg', url: 'https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100'}]
      };
    },
    methods: {
      submitUpload() {
        this.$refs.upload.submit();
      },
      handleRemove(file, fileList) {
        console.log(file, fileList);
      },
      handlePreview(file) {
        console.log(file);
      }
    }
  }
</script>
```

## 二、问题

​		如果el-upload接口请求上传文件，会默认使用action的地址作为上传的地址。

## 三、取消上传地址

### 1、http-request

​		如果使用`http-request`属性那么就会覆盖默认的上传行为，可以自定义上传的实现。(即使action提供了地址也是无效的，被覆盖掉了)。

```vue
<el-upload
           class="upload-demo"
           action="#"
           :http-request="httpRequest"
           >
    <i class="el-icon-upload"></i>
    <div class="el-upload__text">
        {{ $t("maintain.uploadInfoFirst") || "将文件拖到此处，或"
        }}<em>{{ $t("maintain.clickUpload") || "点击上传" }}</em>
    </div>
    <div class="el-upload__tip" slot="tip">
        <div>
            {{ $t("ipcMgt.Can only be uploaded by") || "只能上传由" }}
        </div>
        <el-button
                   type="text"
                   @click="importTempHandler"
                   icon="el-icon-download"
                   class="download-temp-btn"
                   >{{ $t("ipcMgt.template file") || "模版文件" }}</el-button
            >
        <div>
            {{
            $t("ipcMgt.Fill in the IPC information EXECL") ||
            "填写的IPC信息EXECL"
            }}
        </div>
    </div>
</el-upload>
```

### 2、before-upload

​		使用`before-upload`这个属性，`beforeUpload` 里 `return false` 是阻止默认的 Ajax 上传动作，然后会触发 onChange 以便添加到文件列表中交由用户后续手动上传。

```vue
<el-upload
           class="upload-demo"
           ref="upload"
           action="#"
           :file-list="formData.fileList"
           :before-upload="beforeUpload"
           >
    <i class="el-icon-upload"></i>
    <div class="el-upload__text">
        {{ $t("maintain.uploadInfoFirst") || "将文件拖到此处，或"
        }}<em>{{ $t("maintain.clickUpload") || "点击上传" }}</em>
    </div>
    <div class="el-upload__tip" slot="tip">
        <div>
            {{ $t("ipcMgt.Can only be uploaded by") || "只能上传由" }}
        </div>
        <el-button
                   type="text"
                   @click="importTempHandler"
                   icon="el-icon-download"
                   class="download-temp-btn"
                   >{{ $t("ipcMgt.template file") || "模版文件" }}</el-button
            >
        <div>
            {{
            $t("ipcMgt.Fill in the IPC information EXECL") ||
            "填写的IPC信息EXECL"
            }}
        </div>
    </div>
</el-upload>
<script>
  export default {
    methods: {
        beforeUpload(file) {
          return false;
        },
    }
  }
</script>

```

### 3、auto-upload

是否在选取文件后立即进行上传, 可以设置为`false`

```vue
<el-upload
           class="upload-demo"
           ref="upload"
           action="..."
           :auto-upload="false"
           >
    <i class="el-icon-upload"></i>
    <div class="el-upload__text">
        {{ $t("maintain.uploadInfoFirst") || "将文件拖到此处，或"
        }}<em>{{ $t("maintain.clickUpload") || "点击上传" }}</em>
    </div>
    <div class="el-upload__tip" slot="tip">
        <div>
            {{ $t("ipcMgt.Can only be uploaded by") || "只能上传由" }}
        </div>
        <el-button
                   type="text"
                   @click="importTempHandler"
                   icon="el-icon-download"
                   class="download-temp-btn"
                   >{{ $t("ipcMgt.template file") || "模版文件" }}</el-button
            >
        <div>
            {{
            $t("ipcMgt.Fill in the IPC information EXECL") ||
            "填写的IPC信息EXECL"
            }}
        </div>
    </div>
</el-upload>
<script>

</script>
```



## 四、参考文档

https://blog.csdn.net/weixin_44293690/article/details/121994444

https://blog.csdn.net/qq_37807767/article/details/107319662

https://element.eleme.cn/#/zh-CN/component/upload#methods