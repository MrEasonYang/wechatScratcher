<?php
namespace Scratch\Controller;
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

    public function index () {
        //通过url获取openid及应用的appkey
    	$info['openid'] = I('get.openid','Error','htmlspecialchars');
    	$info['appkey'] = I('get.appkey','Error','htmlspecialchars');
        $status = I('get.status','Error','htmlspecialchars');
        if ('Error' == $info['openid']||'Error' == $info['appkey']||'Error' == $status) {
            $this->error('访问错误');
            return 0;
        }
        //在数据库中查询openid
    	$databaseOfUser = M('user');
        $databaseOfIntroduction = M('introduction');
        $introduction = $databaseOfIntroduction->where("appkey='%s'",$info['appkey'])->getField('content');
        $databaseOfAward = M('award');
        $award = $databaseOfAward->where("appkey='%s'",$info['appkey'])->select();
        $databaseOfApp = M('app');
        $app = $databaseOfApp->where("appkey='%s'",$info['appkey'])->find();
    	$userInfo = $databaseOfUser->where('openid="%s"',$info['openid'])->find();
        $databaseOfTwice = M('twice');
        $twiceArray = $databaseOfTwice->where($info)->find();

        if (2 == $status) {
            $checkShow = IndexController::SITUATION_2;
            $data['award'] = I('get.award','Error','htmlspecialchars');
            $data['openid'] = $info['openid'];
            $data['appkey'] = $info['appkey'];
            $data['twice'] = I('get.result','Error','htmlspecialchars');
            $data['result'] = I('get.result','Error','htmlspecialchars');

            if (IndexController::UNSENT == $userInfo['send']&&IndexController::WON == $userInfo['result']) {
                import("Org.Util.Wechat");
                $options = array(
                                'token'=>$app['token'], //填写你设定的key
                                'encodingaeskey'=>$app['encodingaeskey'], //填写加密用的EncodingAESKey
                                'appid'=>$app['appid'], //填写高级调用功能的app id
                                'appsecret'=>$app['appsecret'], //填写高级调用功能的密钥
                                );
                $wechat = new \Org\Util\Wechat($options);
                //$wechat->valid();
                $text = '恭喜您，系统已确认您获奖，请点击下面的链接填写信息，我们将根据您所填写的信息邮寄奖品→→→→→→';
                $url = '<a href="'.U('Form/Index/index','appkey='.$info['appkey'].'&openid='.$info['openid'],'',$domain=true).'">点击填写信息</a>';
                $sendContent = array("touser"=>$info['openid'],"msgtype"=>"text","text"=>array("content"=>$text.$url));
                $wechat->sendCustomMessage($sendContent);
                $data['send'] = IndexController::SENT;
                $data['share'] = IndexController::SHARED;
            } else {
                $data['share'] = IndexController::SHARED;
            }

            if (IndexController::ONCE == $twiceArray['confirm']) {
                import("Org.Util.Wechat");
                $options = array(
                                'token'=>$app['token'], //填写你设定的key
                                'encodingaeskey'=>$app['encodingaeskey'], //填写加密用的EncodingAESKey
                                'appid'=>$app['appid'], //填写高级调用功能的app id
                                'appsecret'=>$app['appsecret'], //填写高级调用功能的密钥
                                );
                $wechat = new \Org\Util\Wechat($options);
                //$wechat->valid();
                $text = '您已获得第二次抽奖机会，请点击下面的链接→→→→→→';
                $url = '<a href="'.U('Scratch/Index/index','appkey='.$info['appkey'].'&openid='.$info['openid'].'&status=1','',$domain=true).'">点击刮奖</a>';
                $sendContent = array("touser"=>$info['openid'],"msgtype"=>"text","text"=>array("content"=>$text.$url));
                $wechat->sendCustomMessage($sendContent);
                $databaseOfTwice->where($info)->setField('confirm',IndexController::TWICE);
                $data['share'] = IndexController::SHARED;
                $data['twice'] = IndexController::ONCE;
            } else {
                $data['share'] = IndexController::SHARED;
            }

            if (IndexController::TWICE == $userInfo['twice']&&IndexController::TWICE == $twiceArray['confirm']) {
                $data = $userInfo;
                $data['send'] = IndexController::SENT;
            }

            if (empty($userInfo)) {
                $check = $databaseOfUser->add($data);
            } else {
                $check = $databaseOfUser->where($info)->save($data);
            }
        } else if (1 == $status) {
            if (IndexController::WON == $userInfo['result']) {
                //已中奖
                $data = $userInfo;
                $checkShow = IndexController::SITUATION_3;
            } else if (IndexController::LOST == $userInfo['result']&&IndexController::TWICE == $userInfo['twice']) {
                //未中奖但已二次刮奖
                $data = $userInfo;
                $checkShow = IndexController::SITUATION_3;
            } else if (IndexController::LOST == $userInfo['result']&&IndexController::ONCE == $userInfo['twice']) {
                //二次刮奖
                $checkShow = IndexController::SITUATION_1;
                $result = $this->lottery($info['appkey']);
                if (NULL == $result) {
                    $result['name'] = "未中奖";
                    $result['image'] = 'default.jpg';
                };
                $data['award'] = $result['name'];
                $data['openid'] = $info['openid'];
                $data['appkey'] = $info['appkey'];
                $data['share'] = IndexController::SHARED;
                $data['twice'] = IndexController::TWICE;
                if ("未中奖" == $result['name']) {
                    $data['result'] = IndexController::LOST;
                } else {
                    $data['result'] = IndexController::WON;
                }
                $databaseOfUser->where($info)->save($data);
                $this->assign('twice',$data['twice']);
                $this->assign('result',$result['image']);
            } else if (empty($userInfo)) {
                //新用户，进行第一次抽奖
                $result = $this->lottery($info['appkey']);
                if (NULL == $result) {
                    $result['name'] = "未中奖";
                    $result['image'] = 'default.jpg';
                };
                $data['award'] = $result['name'];
                $data['openid'] = $info['openid'];
                $data['appkey'] = $info['appkey'];
                $data['share'] = IndexController::UNSHARED;
                if ("未中奖" == $data['award']) {
                    $data['result'] = IndexController::LOST;
                    $data['twice'] = IndexController::ONCE;
                } else {
                    $data['result'] = IndexController::WON;
                    $data['twice'] = IndexController::ONCE;
                }
                $this->assign('result',$result['image']);
                $checkShow = IndexController::SITUATION_1;
            } else {
                $data = $userInfo;
                $checkShow = IndexController::SITUATION_3;
            }
        } else {
            $this->error('访问错误');
            return 0;
        }
        $shareUrl = U('Scratch/Index/index','appkey='.$info['appkey'].'&openid='.$info['openid'].'&status=2&twice='.$data['twice'].'&award='.$data['award'].'&result='.$data['result'],'',$domain = true);
        $jumpUrl = U('Scratch/Index/result','appkey='.$info['appkey'].'&openid='.$info['openid'].'&status=1&twice='.$data['twice'].'&result='.$data['result'],'',$domain = true);
        $this->assign('shareurl',$shareUrl);
        $this->assign('jumpurl',$jumpUrl);
        $this->assign('award',$award);
        $this->assign('introduction',$introduction);
        $this->assign('checkshow',$checkShow);
        $this->display();
    }

    public function result () {
        //通过url获取openid及应用的appkey
        $info['openid'] = I('get.openid','Error','htmlspecialchars');
        $info['appkey'] = I('get.appkey','Error','htmlspecialchars');
        $status = I('get.status','Error','htmlspecialchars');
        $databaseOfTwice = M('twice');
        $twice = $databaseOfTwice->where($info)->find();
        $result = I('get.result','Error','htmlspecialchars');
        
        if (1 == $status&&'Error' != $info['openid']) {
            if (empty($twice)&&IndexController::LOST == $result) {
                $twiceData['openid'] = $info['openid'];
                $twiceData['appkey'] = $info['appkey'];
                $twiceData['confirm'] = IndexController::ONCE;
                $databaseOfTwice->add($twiceData);
            }
            $this->assign('checkshow',IndexController::SHOW_RESULT);
            $this->display();
        } else {
            $this->error('访问错误');
        }
    }

    private function lottery ($info['appkey']="") {
    	if (!empty($info['appkey'])) {
    		$databaseOfAward = M('award');
    		$award = $databaseOfAward->where('appkey="%s"',$info['appkey'])->order('probility asc')->select();
            foreach ($award as $key => $value) {
    			$max = 100;
    			$randNumber = mt_rand(1,$max);
    			if ($randNumber <= $value['probility']&&0 != $value['amount']) {
    				$result['image'] = $value['scratchimage'];
                    $result['name'] = $value['name'];
                    $value['amount'] -= 1;
                    $databaseOfAward->where('id="%s"',$value['id'])->setField('amount',$value['amount']);
                    break;
    			} else {
    				$max -= $value['probility'];
    			}
    		}
    		return $result;
    	} else {
    		$this->error('访问错误，请重试');
		}
    }
}