# WechatScratcher
一个针对旧版微信JS API的，基于PHP的微信刮刮乐系统（包含前后端）。

## 本系统主要依赖于以下开源项目：

* [WeixinApi](https://github.com/zxlie/WeixinApi)
* [PHPExcel](https://github.com/PHPOffice/PHPExcel)
* [ThinkPHP 3.2](https://github.com/top-think/thinkphp)
* [UEditor](https://github.com/fex-team/ueditor)
* [wScratchPad](https://github.com/websanova/wScratchPad)
* [jQuery](https://github.com/jquery/jquery)
* [Validform](https://github.com/haiercdboy/Validform)

## 说明：

本项目原本为商业项目。由于2015年腾讯微信推出官方JS API，项目依赖的WeixinApi也停止更新，所以目前项目前端已无法保证适配目前的微信浏览器，因此将本项目开源。

使用者需要将WeixinApi部分替换为微信官方的JS API，将sql文件导入5.x版本的MySQL数据库即可（数据库编码为GBK格式）。

本项目包含前后端，前端入口位于Application\Scratch，后端入口位于Application\Admin。

## License

使用LGPL 3.0协议

