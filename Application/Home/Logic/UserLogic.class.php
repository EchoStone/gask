<?php
namespace Home\Logic;
use  Home\Model\UserModel;

class UserLogic{
    private $modelHandel;
    public function __construct(){
        $this->modelHandel = new UserModel();
    }
    public function getUserList($page = 1, $pagesize = 0)
    {
        $map = [];
        $pagesize = $pagesize?:C('DEFAULT_PAGE_NUMS');
        $list =  $this->modelHandel->getUserList($map, $page, $pagesize, "id desc");
        /*
        public function getUserList($map, $page, $pagesize, $order){
        return $this->getListByMap($map, $page, $pagesize, $order);
        }
        */


    }
}