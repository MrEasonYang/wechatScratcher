<?php
namespace Common\Auth\Controller;
use Think\Controller;
use Think\Auth;

class AuthController extends Controller {
	protected function _initialize () {
		$auth = new Auth();
        $username = cookie('heu_username');
        $group = cookie('heu_group');
        $cookieKey = cookie('heu_key');
        $session = session('heu_auth');
		$checkCookie = verifyCookie($username,$group,$cookieKey);
		if (!$checkCookie||!empty($session)) {
			return true;
		} else {
			$this->error('您尚未登陆，即将跳转至登陆页面',U('Home/Index/index'),1);
		}

		if (!$auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$session)) {
			$this->error('您无权限访问本页',1);		
		}
	}
}