<?php
namespace Home\Controller;
use Home\Logic\UserLogic;

class UserController extends BaseController {
    private $logic;
    public function __construct(){
        $this->logic = new UserLogic();
    }
    public function userList(){
        $page = I("page", 1);
        $pagesize = I("pagesize");

        $list = $this->logic->getUserList($page, $pagesize);
        return $this->jsonReturn($list);
    }
}