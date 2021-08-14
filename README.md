# wfm
## 原生PHP在线文件管理器，原生PHP在线文件编辑器

#### 解决痛点
在开发中，往往线上项目在查看log日志时，都是远程登录服务器，然后再翻找很深的log文件路径，这样不仅费时费力，而且操作繁琐。本项目实现了以网页的形式直接在线查看log日志，这样不仅仅避免了每次远程登录服务器，再查找log文件的繁琐操作，而且还节约了大量宝贵时间，让你只专注项目业务开发和维护。

#### 主要功能 

- 支持浏览文件目录
- 支持在线浏览文件内容
- 支持在线创建文件，编辑文件，删除文件
- 文件夹和文件排序

#### 实用场景
- 网站在线Log日志，查看和管理
- 在线修改代码
- 单纯文件管理

#### 使用说明
原生php开发，代码量少，无其他依赖，容易集成到自己项目中，比如你项目是ThinkPHP,CI,Yii等框架开发，都可以很轻松的集成到自己项目中，不用时直接删除即可。

composer 导入
```sh
composer require melodyne/wfm
```

使用示例
```PHP
 $viewFile = new \melodyne\wfm\ViewFile([
     'sys_name'=>'在线log日志系统',
     'dir_path'=>'../logs',
     'file_action'=>['create','update','delete'] // 创建，修改，删除
 ]);
```
权限 `file_action` 的值为，不要该权限则不填
* create 创建文件权限
* update 修改编辑文件权限
* delete 删除文件权限


**运行效果**

![效果展示](https://github.com/melodyne/file_manager/blob/master/eg.gif?raw=true)

