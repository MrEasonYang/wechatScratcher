<?php
namespace Control\Controller;
use Common\Auth\Controller;
class LogoutController extends AuthController {
	public function index () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
		  $check['cookie'] = cookie('wechat_username');
		  $check['session'] = session('wechat_username');
            if (!empty($check['cookie'])||!empty($check['session'])) {
                cookie(null,'wechat_');
                session(null,'wechat_');
                session('[destroy]');
                $check['cookie'] = cookie('wechat_username');
                $check['session'] = session('wechat_username');
                if (!empty($check['cookie'])||!empty($check['session'])) {
                    $this->error('退出系统失败，请重新尝试');
                } else {
                    $this->success('您已成功退出，即将跳转至登陆页',U('Admin/Index/index'),1);
                }
            } else {
                $this->error('无访问权限，请重新登陆',U('Admin/Index/index'),1);
            }
	    } else {
            $this->error('您尚未登陆，即将跳转至登陆页面',U('Admin/Index/index'));
        }
    }
}
?>