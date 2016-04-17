<?php
namespace Access\Controller;
use Think\Controller;
class WechatController extends Controller {
    public function index () {
    	$appKey = I('get.appkey');
    	$databaseOfApp = M('app');
    	$app = $databaseOfApp->where("appkey='%s'",$appKey)->find();
    	import("Org.Util.Wechat");
     	$options = array(
  						'token'=>$app['token'], //填写你设定的key
  						'encodingaeskey'=>$app['encodingaeskey'], //填写加密用的EncodingAESKey
  						'appid'=>$app['appid'], //填写高级调用功能的app id
  						'appsecret'=>$app['appsecret'], //填写高级调用功能的密钥
  					);
  	 	$wechat = new \Org\Util\Wechat($options);
     	$wechat->valid();
     	$menu = array(
     				"button"=>
     					array(
     						array('type'=>'click','name'=>'微信刮刮乐','key'=>'MENU_SCRATCH'),
     						array('type'=>'click','name'=>'刮刮乐快乐传','key'=>'MENU_SCRATCH_PRO'),
     						)
    				);
		$setMenu = $wechat->createMenu($menu);
     	$type = $wechat->getRev()->getRevType();
     	if ($type == \Org\Util\Wechat::MSGTYPE_EVENT) {
     		$event = $wechat->getRev()->getRevEvent();
     		if ($event['key'] == 'MENU_SCRATCH') {
     			$openId = $wechat->getRev()->getRevFrom();
     			//$wechat->getRev()->getRevTo();
     			if (!empty($openId)&&!empty($appKey)) {
     				$url = U('Scratch/Index/index','appkey='.$appKey.'&openid='.$openId.'&status=1','',$domain=true);
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
     		} else if ($event['key'] == 'MENU_SCRATCH_PRO') {
                $openId = $wechat->getRev()->getRevFrom();
                //$wechat->getRev()->getRevTo();
                if (!empty($openId)&&!empty($appKey)) {
                    $url = U('ScratchPro/Index/index','appkey='.$appKey.'&openid='.$openId,'',$domain=true);
                    $data = array(
                                "0"   =>array(
                                            'Title'=>'刮刮乐快乐传——传递拿大奖',
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