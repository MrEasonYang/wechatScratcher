<?php
namespace Form\Controller;
use Think\Controller;
class IndexController extends Controller {
    const SENT = 2;
    const UNSENT = 1;
    const SHARED = 2;
    const UNSHARED = 1;
    const ONCE = 1;
    const TWICE = 2;
    const SITUATION_1 = 1;
    const SITUATION_2 = 2;
    const SITUATION_3 = 3;
    const SHOW_RESULT = 1;
    const FOLLOWED = 1;
    const UNFOLLOWED = 0;
    const WAIT_0 = 1;
    const WAIT_1 = 2;
    const WON = 3;
    const LOST = 4;
    const SHOW_FORM = 1;
    const NOT_SHOW_FORM = 2;

    public function index () {
    	$user['openid'] = I('get.openid','Error','htmlspecialchars');
    	$user['appkey'] = I('get.appkey','Error','htmlspecialchars');
    	$databaseOfWinner = M('winner');
    	$check['1'] = $databaseOfWinner->where($user)->find();
      	$databaseOfUser = M('user');
    	$check['2'] = $databaseOfUser->where($user)->find();  	
    	if (empty($check['1'])&&IndexController::WON == $check['2']['result']&&IndexController::SHARED == $check['2']['share']) {
    		$show = IndexController::SHOW_FORM;
    		$url = U('Form/Index/result','openid='.$user['openid'].'&appkey='.$user['appkey'],'',$domain = true);
    		$this->assign('url',$url);
    		$this->assign('show',$show);
    		$this->display();
    	} else if (!empty($check['1'])) {
    		$show = IndexController::NOT_SHOW_FORM;
    		$this->assign('show',$show);
    		$this->display();
    	} else {
    		$this->error('访问错误');
    	}
    }

    public function result () {
    	$user['openid'] = I('get.openid','Error','htmlspecialchars');
    	$user['appkey'] = I('get.appkey','Error','htmlspecialchars');
    	$databaseOfUser = M('user');
    	$check = $databaseOfUser->where($user)->find();
    	if (IndexController::WON == $check['result']&&IndexController::SHARED == $check['share']) {
    		$databaseOfWinner = M('winner');
    		$user['name'] = I('post.name','Error','htmlspecialchars');
    		$user['tel'] = I('post.tel','Error','htmlspecialchars');
    		$user['address'] = I('post.address','Error','htmlspecialchars');
    		$user['zipcode'] = I('post.zipcode','Error','htmlspecialchars');
    		$user['receiver'] = I('post.receiver','Error','htmlspecialchars');
    		$user['award'] = $check['award'];
    		$checkInsert = $databaseOfWinner->add($user);
    		if (!empty($checkInsert)) {
    			$this->success('提交成功');
    		} else {
    			$this->error('提交失败，请重试');
    		}
    	} else {
    		$this->error('访问错误');
    	}
    } 
}