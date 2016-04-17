<?php
namespace SignUp\Controller;
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
		if ($checkCookie&&!empty($session)) {
    		if (!$auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$session)) {
				$this->error('无访问权限，即将跳转');		
			} else if ($auth->check(TEACHER_NAV_1,$session)) {
				$nav = 1;
				$this->assign('nav',$nav);
				return true;
			} else {
				$nav = 2;
				$this->assign('nav',$nav);
			}
		} else {
			$this->error('您尚未登陆，即将跳转至登陆页面',U('Home/Index/index'),1);
		}
	}
}