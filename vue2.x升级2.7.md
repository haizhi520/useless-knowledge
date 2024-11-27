# Vue2.x 升级到Vue2.7

环境：NCS-oss-test 

node版本：v16.19.1

1、存在node_modules包，则删除node_modules包

2、下载vue@2.7.xx的版本

```bash
$ npm install vue@2.7.16
```

3、下载其他依赖

```bash
$ npm install
```

4、实际下载完毕后会出现问题，可能会显示   `vue-template-compiler 错误`

```javascript
  "dependencies": {
    "axios": "^0.21.1",
    "clipboard": "^2.0.6",
    "codemirror": "^5.65.16",
    "core-js": "^3.4.3",
    "driver.js": "^0.9.8",
    "echarts": "^4.6.0",
    "el-select-v2": "^1.2.2",
    "element-ui": "^2.15.12",
    "file-saver": "^2.0.2",
    "js-yaml": "^4.1.0",
    "mavon-editor": "^2.10.4",
    "svg-sprite-loader": "^5.2.1",
    "three": "^0.138.3",
    "vue": "^2.7.16",
    "vue-router": "^3.1.3",
    "vuex": "^3.1.2",
    "xlsx": "^0.16.1"
  },
  "devDependencies": {
    "@vue/cli-plugin-babel": "^4.1.0",
    "@vue/cli-plugin-eslint": "^4.1.0",
    "@vue/cli-plugin-unit-jest": "^4.1.0",
    "@vue/cli-service": "^4.1.0",
    "@vue/eslint-config-prettier": "^5.0.0",
    "babel-eslint": "^10.0.3",
    "compression-webpack-plugin": "^6.1.0",
    "eslint": "^5.16.0",
    "eslint-plugin-prettier": "^3.1.1",
    "eslint-plugin-vue": "^5.0.0",
    "less": "^3.0.4",
    "less-loader": "^5.0.0",
    "prettier": "^1.19.1",
    "uglifyjs-webpack-plugin": "^2.2.0",
    "vue-i18n": "^8.7.0",
    // "vue-template-compiler": "^2.7.16"
  }
```

如果你在使用 `@vue/test-utils`，那么 `vue-template-compiler` 需要保留，否则可以将 `vue-template-compiler` 从依赖中移除。

5、并检查网络，实际是否能够下载。

## 踩坑实际问题

```bash
$ npm install -g @vue/cli
$ vue upgrade
```

如果依赖冲突，可以使用 `npm i --legacy-peer-deps` 进行安装

```bash
$ npm cache clean --force
```

```bash
$ npm install
```

```bash
$ npm list vue
```

为了确保没有依赖缓存影响,执行`npm cache clear --force`,删除 package-lock.json 文件和 node_modules 文件夹, 重新执行 `npm install` 下载最新的依赖

确保项目中安装的`Vue`版本和`"vue-template-compiler": "^2.7.7"`兼容。如果不兼容，尝试升级或降级`Vue`版本，或更新`vue-template-compiler`模块的版本。

## 冲突错误1

```bash
 warning  in ./src/views/layout/components/RightMenu.vue?vue&type=template&id=ece90046&scoped=true&lang=html&

Module Warning (from ./node_modules/vue-loader/lib/loaders/templateLoader.js):
(Emitted value instead of an instance of Error) <el-dropdown-item v-for="item in languageOption">: component lists rendered with v-for should have explicit keys. See https://v2.vuejs.org/v2/guide/list.html#key for more info.

 @ ./src/views/layout/components/RightMenu.vue?vue&type=template&id=ece90046&scoped=true&lang=html& 1:0-460 1:0-460
 @ ./src/views/layout/components/RightMenu.vue
 @ ./src/views/layout/components/index.js
 @ ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/layout/Layout.vue?vue&type=script&lang=js&
 @ ./src/views/layout/Layout.vue?vue&type=script&lang=js&
 @ ./src/views/layout/Layout.vue
 @ ./src/router/index.js
 @ ./src/main.js
 @ multi (webpack)-dev-server/client?http://192.168.2.90:80&sockPath=/sockjs-node (webpack)/hot/dev-server.js ./src/main.js
```

在 `vue.config.js` 中，禁用 `eslint` 对模板的警告

```javascript
module.exports = {
  lintOnSave: false // 禁用 eslint 检查
};
```

## 冲突错误2

```bash
 in ./src/views/cluster/oss/bucket/components/browse.vue?vue&type=style&index=0&id=67c16b4d&scoped=true&lang=less&

Module Warning (from ./node_modules/postcss-loader/src/index.js):
Warning

(76:3) start value has mixed support, consider using flex-start instead
```

这些警告是由 `postcss-loader` 抛出的，是关于 CSS 属性 `start` 和 `end` 的兼容性问题。

```javascript
// vue.config.js     补充...如果警告可以忽略，可以通过配置 postcss-loader 的选项禁用

module.exports = {
  loader: 'postcss-loader',
  options: {
    postcssOptions: {
      plugins: [
        require('autoprefixer')({
          overrideBrowserslist: ['> 0.5%', 'last 2 versions', 'not dead'],
          grid: true,
        }),
      ],
    },
  },
};
```

## 冲突错误3

```bash
chunk 8 [mini-css-extract-plugin]
Conflicting order. Following module has been added:
 * css ./node_modules/css-loader/dist/cjs.js??ref--11-oneOf-1-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--11-oneOf-1-2!./node_modules/less-loader/dist/cjs.js??ref--11-oneOf-1-3!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/diskImageNew.vue?vue&type=style&index=1&id=44b9ca12&scoped=true&lang=less&
despite it was not able to fulfill desired ordering with these modules:
 * css ./node_modules/css-loader/dist/cjs.js??ref--7-oneOf-1-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-oneOf-1-2!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/VuePagination.vue?vue&type=style&index=0&lang=css&
   - couldn't fulfill desired order of chunk group(s)
   - while fulfilling desired order of chunk group(s) ,
```

 以上是CSS 模块之间的加载顺序冲突

```javascript
module.exports = {
  css: {
    extract: {
      ignoreOrder: true, // 忽略 CSS 顺序警告
    },
  },
};
```

## 参考信息

https://llmysnow.top/4-vue/26-Vue%E5%8D%87%E7%BA%A7%E6%8C%87%E5%8D%97#vue2-6%E5%8D%87%E7%BA%A7vue2-7

https://v2.cn.vuejs.org/v2/guide/installation.html

https://juejin.cn/post/7170265909595439134#heading-0

https://juejin.cn/post/7328213780311081000?from=search-suggest

https://juejin.cn/post/7190297574694191163#heading-10