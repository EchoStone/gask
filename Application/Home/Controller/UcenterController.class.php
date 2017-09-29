<?php

namespace Home\Controller;
use Home\Logic\UserLogic;
class UcenterController extends BaseController
{
    /**
     * 用户中心
     */

    private $logic;
    private $userId;
    public function __construct(){
        parent::__construct();
        $this->userId = session("userID");
        $this->logic = new UserLogic();
    }
    public function index()
    {
        $userInfo = $this->logic->getUserInfoById($this->userId);
        $this->assign('userInfo', $userInfo);
        $this->display();
    }

}