<?php

namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{
    protected $wxLoginBackUrl = ''; // 微信回调地址

    public function _initialize()
    {
        $this->initInternal();
    }

    private function initInternal()
    {
        if (!$this->isWxPlat()) {
//            p('请在微信客户端打开链接');
        }

        if (strtolower(ACTION_NAME) != strtolower('wxBackUrl')) {
            $this->isLogin();
        }
    }


    /**
     * 微信授权
     */
    protected function wxAuthor()
    {
        if (empty($this->wxLoginBackUrl)) {
            p('未配置回调地址'); // TODO
        }
        $returnUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/Index/wxBackUrl';
        $url = 'http://wxmanage.gaodun.com/api/wxshouquan.php?returnurl=' .$returnUrl;
        $this->redirectToOutLink($url);
    }


    /**
     * 跳转到外部链接
     */
    protected function redirectToOutLink($url)
    {
        header('Location: ' . $url);
        exit();
    }

    protected function isLogin($isAjax = false)
    {
        $isUserID = session('?userID');
        if (empty($isUserID)) {
            if ($isAjax) {
                $json = [];
                $json['status'] = '101';
                $this->jsonReturn($json);
            } else {
                $this->wxLoginBackUrl = curPageURL();
                $this->wxAuthor();
            }
        }
        return true;
    }

    /**
     *返回json格式
     */
    protected function jsonReturn($data = [], $status = 0, $info = '', $callback = '')
    {
        $rData = [];
        $rData['status'] = $status;
        $rData['info'] = $info;
        $rData['data'] = $data;
        $jsonData = json_encode($rData);
        if (!empty($callback)) {
            echo $callback . '(' . $jsonData . ')';
        } else {
            echo $jsonData;
        }
        die();
    }


    /**
     * 是否微信平台
     */
    protected function isWxPlat()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        return strpos($agent, 'micromessenger') ? true : false;
    }
}