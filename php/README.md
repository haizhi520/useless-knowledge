# PHP入门



## 下载环境

```javascript
https://windows.php.net/download/
```

“Non Thread Safe”和“Thread Safe”通常用于描述在多线程环境中是否可以安全地使用某个库或程序。在 VS16（Visual Studio 2019）环境中构建的 x64（64位）应用中

**Web 服务器**：选择线程安全版本，以确保在并发访问情况下的安全性。

**本地开发或单线程应用**：选择非线程安全版本，因为性能更高且不需要多余的锁机制。

一般有三个让你选：

- [Zip](https://windows.php.net/downloads/releases/php-8.3.13-nts-Win32-vs16-x64.zip) [30.68MB]
  sha256: 1c69f1e93f49fc94fffae90ffadc0a5982a52db7150bdfa1b89b0717c3a94b99
- [Debug Pack](https://windows.php.net/downloads/releases/php-debug-pack-8.3.13-nts-Win32-vs16-x64.zip) [25.73MB]
  sha256: 32712c3042b85861f343e58a34fcf909755e550351f14758449e08a81798cdbe
- [Development package (SDK to develop PHP extensions)](https://windows.php.net/downloads/releases/php-devel-pack-8.3.13-nts-Win32-vs16-x64.zip) [1.26MB]
  sha256: 5daec4a947b49ce2fb4082d24ace13ebd95249ced6534af32f689187e566aa26

选择zip包即可

## 配置环境变量

1、找到自己放的位置，比如：

```javascript
D:\php\php-8.3.13-Win32-vs16-x64
```

2、添加环境变量（控制面板->高级系统设置->环境变量->最上方的系统变量Path->新增两条php路径即可）；

3、打开cmd输入php-v 即可查看添加的php版本信息；



## 配置vscode

1、插件市场下载

PHP Debug 和 PHP Server

2、文件->首选项->设置,搜索php-找到settings.json中编辑-> 新增下方代码

```json
"php.executablePath": "D:/php/php-8.3.13-Win32-vs16-x64/php.exe",
"php.validate.executablePath": "D:/php/php-8.3.13-Win32-vs16-x64/php.exe",
"php.debug.executablePath": "D:/php/php-8.3.13-Win32-vs16-x64/php.exe",
"phpserver.phpConfigPath": "D:/php/php-8.3.13-Win32-vs16-x64/php.ini",
"phpserver.phpPath": "D:/php/php-8.3.13-Win32-vs16-x64/php.exe",
```

3、使用PHP Server

右键点击菜单项，选择serve project