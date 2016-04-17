<?php
namespace Control\Controller;
use Common\Auth\Controller;
class IndexController extends AuthController {
    public function index () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $this->success('已登录，即将跳转',U('Admin/Index/center'));
        } else {
            $this->display();
        }
    }

    public function login () {
    	$user['username'] = I('post.username','Error','htmlspecialchars');
    	$password = md5(I('post.password','Error','htmlspecialchars'));
    	$databaseOfAdmin = M('control');
    	$salt = $databaseOfAdmin->where("username='%s'",$username)->getField('salt');
    	$string = $password.$salt;
    	$user['password'] = md5($string);
    	$check = $databaseOfAdmin->where($user)->find();
    	if (!empty($check)) {
    		session('wechat_username',$check['username']);
    		session('wechat_key',$check['key']);
    		session('wechat_appid',$check['appid']);
    		$loginTime = date("Y-m-d H:i:s");
    		$databaseOfAdmin->where($user)->setField('last_login_time',$loginTime);
      		$this->success('登陆成功，即将跳转',U('Admin/Index/center'),2);
    	} else {
    		$this->error('用户名或密码错误，请重试');
    	}
    }

    public function center () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
        	$info = $this->info();
        	$databaseOfApp = M('app');
        	$appId = session('wechat_appid');
        	$data = $databaseOfApp->where("appid='%s'",$appId)->find();
        	$databaseOfUser = M('user');
        	$count = $databaseOfUser->where("appid='%s'",$appId)->count();
        	$this->assign('info',$info);
        	$this->assign('count',$count);
        	$this->assign('data',$data);
            $this->display();
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

	private function info () {
    	$info['seversoftware'] = $_SERVER['SERVER_SOFTWARE'];
    	$info['ip'] = $_SERVER['REMOTE_ADDR'];
    	$info['domain'] = $_SERVER['HTTP_HOST'];
    	$info['time'] = date('Y-m-d,H:i:s');
    	$info['os'] = php_uname('s').php_uname('r').php_uname('v');
    	$info['post'] = ini_get('post_max_size');
    	$info['upload'] = ini_get('upload_max_filesize');
    
    	if (ini_get("safe_mode")==0){
        	$info['safemode'] = 'off';
    	}else{
        	$info['safemode'] = 'on';
    	}

    	return $info;
	}
}

