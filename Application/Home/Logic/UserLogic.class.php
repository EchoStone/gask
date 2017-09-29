<?php
namespace Home\Logic;
use  Home\Model\UserModel;

class UserLogic{
    private $modelHandel;
    public function __construct(){
        $this->modelHandel = new UserModel();
    }
    public function getUserList()
    {
        $map = [];
        $list =  $this->modelHandel->getAllByCondition($map, '', "id desc");
        return $list;
    }
}