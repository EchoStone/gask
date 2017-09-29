<?php

namespace Home\Controller;
class IndexController extends BaseController
{
    /**
     * 首页
     */
    public function index()
    {
        $data = D('Question')->getAllByCondition([], '', 'id desc');
        if (!empty($data)) {
            $userIdArray = [];
            $qIdArray = [];
            foreach ($data as $v) {
                $userIdArray[] = (int)$v['user_id'];
                $qIdArray[] = $v['id'];
            }
            $userInfoTemp = D('User')->getAllByCondition(['id' => ['in', $userIdArray]]);
            foreach ($userInfoTemp as $item) {
                $userInfo[$item['id']] = $item;
            }
            //回答

            $answerInfoTemp = D('Answer')->getAllByCondition(['q_id' => ['in', $qIdArray]]);
            foreach ($answerInfoTemp as $item){
                $answerInfo[$item['q_id']] = $item;
            }

            foreach ($data as &$v) {
                $v['user_info'] = [];
                if (isset($userInfo[$v['user_id']])) {
                    $v['user_info'] = $userInfo[$v['user_id']];
                }
                $v['answer_info'] = [];
                if (isset($answerInfo[$v['id']])) {
                    $v['answer_info'] = $answerInfo[$v['id']];
                }
            }
        }
//        p($data);
        $this->assign('data', $data);
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