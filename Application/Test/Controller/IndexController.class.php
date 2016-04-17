<?php
namespace Test\Controller;
use Think\Controller;
class WechatController extends Controller {
    public function index () {
    	import("Org.Util.Wechat");
     	$options = array(
  						'token'=>'tokenaccesskey', //填写你设定的key
  						'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey
  						'appid'=>'wxdk1234567890', //填写高级调用功能的app id
  						'appsecret'=>'xxxxxxxxxxxxxxxxxxx', //填写高级调用功能的密钥
  					);
  	 	$wechat = new \Wechat($options);
     	$wechat->valid();
     	$type = $wechat->getRev()->getRevType();
     	//if ($type == Wechat::MSGTYPE_EVENT) {
     	if (!empty($type)) {
     		$event = $wechat->getRev()->getRevEvent();
     		//if ($event['key'] == ) {
     		if (true) {
     			$openid = $wechat->getRev()->getRevFrom();
     			$appid = $wechat->getRev()->getRevTo();
     			if (!empty($openid)&&!empty($appid)) {
     				$url = '__URL__/Index/index/appid/'.$addid.'/openid/'.$openid.'/status/0';
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