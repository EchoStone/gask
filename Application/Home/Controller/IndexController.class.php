<?php

namespace Home\Controller;
class IndexController extends BaseController
{
    /**
     * 首页
     */
    public function index()
    {
        $this->display();
    }


    /**
     * 微信返回处理
     */
    public function wxBackUrl()
    {
        $userInfo = $_GET['userinfo'];
        $userInfo = urldecode($userInfo);
        $userInfo = json_decode($userInfo, true);
        if (empty($userInfo) && !empty($userInfo['openid'])) {
            p('授权失败！');
        }
        $userModel = D('User');
        $openid = $userInfo['openid'];
        $map = [];
        $map['wx_openid'] = $openid;
        $isExist = $userModel->getOneByCondition($map, 'id');
        $nowTime = time();
        if (!empty($isExist)) {
            //更新
            $update = [];
            $update['login_at'] = $nowTime;
            $id = $isExist['id'];
            $userModel->update($map, ['id' => $id]);

        } else {
            //插入 =
            $data = [];
            $data['username'] = empty($userInfo['nickname']) ? '高小财' : $userInfo['nickname'];
            $data['brief'] = '我很懒的哟~';
            $data['wx_openid'] = $openid;
            $data['head_url'] = empty($userInfo['headimgurl']) ? '' : $userInfo['headimgurl'];
            $data['ask_price'] = 1;
            $data['wallet'] = 0;
            $data['login_at'] = $nowTime;
            $id = $userModel->insert($data);
        }
        session('userID', $id);
        $this->goHome();
    }
}