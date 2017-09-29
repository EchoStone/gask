<?php
namespace Home\Controller;
use Home\Logic\UserLogic;

class UserController extends BaseController {
    private $logic;
    public function __construct(){
        $this->logic = new UserLogic();
    }
    public function userList(){
        $list = $this->logic->getUserList();
        $this->jsonReturn($list);
    }
}