<?php
/*
namespace Scratch\Controller;
use Think\Controller;
class WechatController extends Controller {
    public function index () {
    	$appId = 'wxa5f8c116cc13b413';//I('get.appid','Error','htmlspecialchars');
    	$databaseOfApp = M('app');
    	//$appInfo = $databaseOfApp->where("appid='%s'",$appId)->find();
    	import("Org.Util.Wechat");
     	$options = array(
  						'token'=>'3qgwq6HDaq1uLQjcEhJf', //填写你设定的key
  						'encodingaeskey'=>'T9PPLlbLNj24txUKIz9LGQhdzZBzkUKY33C3Oia2R49', //填写加密用的EncodingAESKey
  						'appid'=>'wxa5f8c116cc13b413',//$appId, //填写高级调用功能的app id
  						'appsecret'=>'8325968d99b6bafc108949955e38db2e', //填写高级调用功能的密钥
  					);dump($options);
  	 	$wechat = new \Org\Util\Wechat($options);
     	//$check = $wechat->valid(true);$databaseOfApp->where("appid='%s'",$appId)->setField('username',$check);
     	if (true) {
     		$menu = array(
     					"button"=>
     						array(
     							array('type'=>'click','name'=>'微信刮刮乐','key'=>'MENU_SCRATCH'),
     							array('type'=>'view','name'=>'Menu2','url'=>'http://www.baidu.com'),
     							)
    					);
			$setMenu = $wechat->createMenu($menu);
		}
     	$type = $wechat->getRev()->getRevType();
     	if ($type == \Org\Util\Wechat::MSGTYPE_EVENT) {
     		$event = $wechat->getRev()->getRevEvent();
     		if ($event['key'] == 'MENU_SCRATCH') {
     			$openId = $wechat->getRev()->getRevFrom();
     			$appId = $wechat->getRev()->getRevTo();
     			if (!empty($openId)&&!empty($appId)) {
     				$url = U('Scratch/Index/index','appid='.$appId.'&openid='.$openId.'&status=0','',$domain=true);
	   				$data = array(
	   							"0"   =>array(
	   										'Title'=>'微信刮刮乐——分享得大奖',
	   										'Description'=>'炫目大奖，轻轻一刮拿回家',
	   										'PicUrl'=>'__PUBLIC__/images/index.jpg',
	   										'Url'=>$url
	   									)
	  						);
	   				$wechat->news($data)->reply();
     			} else {
     				$wechat->text('系统错误，请重试')->reply();
     			}
     		} else {
     			return 0;
     		}
     	}
    }
}*/


namespace Scratch\Controller;
use Think\Controller;
class WechatController extends Controller {
    public function index () {
    	$appId = I('get.appid');
    	$databaseOfApp = M('app');
    	$app = $databaseOfApp->where("appid='%s'",$appId)->find();
    	import("Org.Util.Wechat");
     	$options = array(
  						'token'=>$app['token'], //填写你设定的key
  						'encodingaeskey'=>$app['encodingaeskey'], //填写加密用的EncodingAESKey
  						'appid'=>$appId, //填写高级调用功能的app id
  						'appsecret'=>$app['appsecret'], //填写高级调用功能的密钥
  					);
  	 	$wechat = new \Org\Util\Wechat($options);
     	$wechat->valid();
     	$menu = array(
     				"button"=>
     					array(
     						array('type'=>'click','name'=>'微信刮刮乐','key'=>'MENU_SCRATCH'),
     						array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
     						)
    				);
		$setMenu = $wechat->createMenu($menu);
     	$type = $wechat->getRev()->getRevType();
     	if ($type == \Org\Util\Wechat::MSGTYPE_EVENT) {
     		$event = $wechat->getRev()->getRevEvent();
     		if ($event['key'] == 'MENU_SCRATCH') {
     			$openId = $wechat->getRev()->getRevFrom();
     			$appId = $appId;//$wechat->getRev()->getRevTo();
     			if (!empty($openId)&&!empty($appId)) {
     				$url = U('Scratch/Index/index','appid='.$appId.'&openid='.$openId.'&status=1','',$domain=true);
	   				$data = array(
	   							"0"   =>array(
	   										'Title'=>'微信刮刮乐——分享得大奖',
	   										'Description'=>'炫目大奖，轻轻一刮拿回家',
	   										'PicUrl'=>'__PUBLIC__/images/index.jpg',
	   										'Url'=>$url
	   									)
	  						);
	   				$wechat->news($data)->reply();
     			} else {
     				$wechat->text('系统错误，请重试')->reply();
     			}
     		} else {
     			return 0;
     		}
     	}
    }
}