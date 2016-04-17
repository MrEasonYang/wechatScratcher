<?php
namespace Control\Controller;
use Common\Auth\Controller;
class IndexController extends AuthController {
	const ACCESS = 2;
	const NOT_ACCESS = 1;
    public function index () {
    	$this->display();
    }

    public function solve () {
    	$data['username'] = I('post.username','Error','htmlspecialchars');
    	$data['appid'] = I('post.appid','Error','htmlspecialchars');
    	$data['appsecret'] = I('post.appsecret','Error','htmlspecialchars');
    	$data['token'] = I('post.token','Error','htmlspecialchars');
    	$data['encodingaeskey'] = I('post.encodingaeskey','Error','htmlspecialchars');
    	$data['password'] = doPassword();
    	$data['salt'] = ;
    	$data['key'] = md5($username);
    	$data['mail'] = session('mail');
    	$databaseOfApp = M('app');
    	$check = $databaseOfApp->where("username'%s'",$username)->find();
    	if (empty($check)) {
    		$databaseOfControl = M('control');
    		$checkInsert = $databaseOfControl->add($data);
    		if (!empty($checkInsert)) {
    			session('wechat_appid',$data['appid']);
    			$this->success('注册成功，即将跳转');
    		} else {
    			$this->ajaxReturn();
    		}
    	} else {
    		$this->ajaxReturn();
    	}
    }

    public function check () {
    	$this->display();
    }

    public function solvecheck () {
    	$appId = session('wechat_appid');
    	$databaseOfControl = M('control');
    	$check = $databaseOfControl->where("appid='%s'",$appId)->getField('access');
    	if (indexController::ACCESS == $check) {
    		$this->success('微信验证成功，即将跳转');
    	} else {
    		$this->ajaxReturn();
    	}
    }
}