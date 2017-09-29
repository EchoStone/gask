<?php
namespace Home\Controller;
use Home\Logic\UserLogic;
use Home\Logic\UserTagLogic;

class UserController extends BaseController {
    private $logic;
    private $tagLogic;
    private $userId;
    public function __construct(){
        $this->userId = session("userID");
        $this->logic = new UserLogic();
        $this->tagLogic = new UserTagLogic();
    }
    public function userList(){
        $list = $this->logic->getUserList();
        $this->jsonReturn($list);
    }

    public function logout(){

    }

}