<?php

namespace Home\Controller;
class IndexController extends BaseController
{
    public function index()
    {
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>', 'utf-8');
    }

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
        $isExist = $userModel->getCountByCondition($map);
        $nowTime = time();
        if ($isExist > 0) {
            //更新
            $update = [];
            $update['login_at'] = $nowTime;
            $userModel->update($map, $update);

        } else {
            //插入
            $data = [];
            $data['username'] = empty($userInfo['nickname']) ? '高小财' : $userInfo['nickname'];
            $data['brief'] = '我很懒的哟~';
            $data['wx_openid'] = $openid;
            $data['head_url'] = empty($userInfo['headimgurl']) ? '' : $userInfo['headimgurl'];
            $data['ask_price'] = 0;
            $data['wallet'] = 0;
            $data['login_at'] = $nowTime;
            $userModel->insert($data);
        }
        $this->goHome();
    }
}