<?php
namespace Control\Controller;
use Common\Auth\Controller;
class SettingController extends AuthController {
    public function index () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $this->display();
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function award () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $appId = session('wechat_appid');
            $this->assign('appid',$appId);
            $databaseOfAward = M('award');
            $data = $databaseOfAward->where("appid='%s'",$appId)->select();
            if (!empty($data)) {
                $this->assign('data',$data);
                $this->assign('show',1);
                $this->display();             
            } else {
                $this->assign('show',0);
                $this->display();
            }
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function addaward () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $this->display();
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function awarddetail () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $appId = session('wechat_appid');
            $this->assign('appid',$appId);
            $id = I('get.id','Error','htmlspecialchars');
            $databaseOfAward = M('award');
            $data = $databaseOfAward->where("id='%d'",$id)->find();
            if (!empty($data)) {
                $this->assign('data',$data);
            }
            $this->display();
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function solveaward () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $appId = session('wechat_appid');
            $time = date("Y-m-d-H-i-s");
            //上传图片1
            $configForShow = array(
                'maxSize'    =>    3145728,
                'rootPath'   =>    './Public/Uploads/images/',
                'saveName'   =>    'show'.$time,
                'exts'       =>    'jpg',
                'autoSub'    =>    false,
                'replace'    =>    true
            );
            $upload = new \Think\Upload($configForShow);
            $info['1'] = $upload->uploadOne($_FILES['image']);
            $data['showimage'] = 'show'.$time.'.jpg';
            //上传图片2
            $configForScratch = array(
                'maxSize'    =>    3145728,
                'rootPath'   =>    './Public/Uploads/images/',
                'saveName'   =>    'scratch'.$time,
                'exts'       =>    'jpg',
                'autoSub'    =>    false,
                'replace'    =>    true
            );
            $upload = new \Think\Upload($configForScratch);
            $info['2'] = $upload->uploadOne($_FILES['image1']);
            $data['scratchimage'] = 'scratch'.$time.'.jpg';

            $data['name'] = I('post.award_name','Error','htmlspecialchars');
            $data['probility'] = I('post.probility','Error','htmlspecialchars');
            $data['appid'] = $appId;
            $data['username'] = $username;
            $data['amount'] = I('post.amount','Error','htmlspecialchars');
            $data['appkey'] = I('post.appkey','Error','htmlspecialchars');
            if (!empty($info)) {
                $databaseOfAward = M('award');
                $check = $databaseOfAward->add($data);
                if (!empty($check)) {
                    $this->success('设置成功，即将跳转');                    
                } else {
                    $this->error('设置失败，请重试');               
                }
            } else {
                $this->error($upload->getError());
            }
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function deleteaward () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $id = I('get.id','Error','htmlspecialchars');
            $databaseOfAward = M('award');
            $check = $databaseOfAward->where("id='%d'",$id)->delete();
            if (!empty($check)) {
                $this->success('删除成功');
            } else {
                $this->error('删除失败，请重试');
            }
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function changeaward () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $appId = session('wechat_appid');
            $time = date("Y-m-d-H-i-s");
            //上传图片1
            $configForShow = array(
                'maxSize'    =>    3145728,
                'rootPath'   =>    './Public/Uploads/images/',
                'saveName'   =>    'show'.$time,
                'exts'       =>    'jpg',
                'autoSub'    =>    false,
                'replace'    =>    true
            );
            $upload = new \Think\Upload($configForShow);
            $info['1'] = $upload->uploadOne($_FILES['image']);
            $data['showimage'] = 'show'.$time.'.jpg';
            //上传图片2
            $configForScratch = array(
                'maxSize'    =>    3145728,
                'rootPath'   =>    './Public/Uploads/images/',
                'saveName'   =>    'scratch'.$time,
                'exts'       =>    'jpg',
                'autoSub'    =>    false,
                'replace'    =>    true
            );
            $upload = new \Think\Upload($configForScratch);
            $info['2'] = $upload->uploadOne($_FILES['image1']);
            $data['scratchimage'] = 'scratch'.$time.'.jpg';
            $data['name'] = I('post.award_name','Error','htmlspecialchars');
            $data['probility'] = I('post.probility','Error','htmlspecialchars');
            $data['appid'] = $appId;
            $data['amount'] = I('post.amount','Error','htmlspecialchars');
            $data['id'] = I('get.id','Error','htmlspecialchars');
            $data['appkey'] = I('post.appkey','Error','htmlspecialchars');
            $data['username'] = $username;
            if (!empty($info)) {
                $databaseOfAward = M('award');
                $check = $databaseOfAward->where("id='%s'",$data['id'])->save($data);
                if (!empty($check)) {
                    $this->success('设置成功，即将跳转');                    
                } else {
                    $this->error('设置失败，请重试');               
                }
            } else {
                $this->error($upload->getError());
            }
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function introduction () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $appId = session('wechat_appid');
            $this->assign('appid',$appId);
            $databaseOfIntroduction = M('introduction');
            $data = $databaseOfIntroduction->where("appid='%s'",$appId)->find();
            $this->assign('data',$data);
            $this->display();            
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function solveintroduction () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $data['content'] = I('post.introduction','Error','htmlspecialchars');
            $data['appid'] = session('wechat_appid');
            $data['username'] = $username;
            $data['appkey'] = I('post.appkey','Error','htmlspecialchars');
            $databaseOfIntroduction = M('introduction');
            $find = $databaseOfIntroduction->where("appid='%s'",$data['appid'])->find();
            $this->assign('data',$find['content']);
            if (!empty($find)) {
                $check = $databaseOfIntroduction->where("appid='%s'",$appId)->save($data);
                if (!empty($check)) {
                    $this->success('设置成功，即将跳转');
                } else {
                    $this->error('设置失败，请重试');
                }
            } else {
                $check = $databaseOfIntroduction->add($data);
                if (!empty($check)) {
                    $this->success('设置成功，即将跳转');
                } else {
                    dump($find);
                    //$this->error('设置失败，请重试');
                }
            }
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function app () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $appId = session('wechat_appid');
            $databaseOfApp = M('app');
            $app = $databaseOfApp->where("appId='%s'",$appId)->find();
            $this->assign('appid',$appId);
            $this->assign('data',$app);
            $this->display();
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function solveapp () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $data['appid'] = session('wechat_appid');
            $data['token'] = I('post.token','Error','htmlspecialchars');
            $data['encodingaeskey'] = I('post.encodingaeskey','Error','htmlspecialchars');
            $data['appsecret'] = I('post.appsecret','Error','htmlspecialchars');
            $data['menu'] = I('post.menu','Error','htmlspecialchars');
            $data['username'] = $username;
            $databaseOfApp = M('app');
            $exist = $databaseOfApp->where("appid='%s'",$data['appid'])->find();
            if (!empty($exist)) {
                $check = $databaseOfApp->where("appid='%s'",$data['appid'])->save($data);
                if (!empty($check)) {
                    $this->success('设置成功',U('Admin/Logout/index'));
                } else {
                    $this->error('设置失败，请重试');
                }
            } else {
                $check = $databaseOfApp->add($data);
                if (!empty($check)) {
                    $this->success('设置成功',U('Admin/Logout/index'));
                } else {
                    $this->error('设置失败，请重试');
                }
            }
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }
}