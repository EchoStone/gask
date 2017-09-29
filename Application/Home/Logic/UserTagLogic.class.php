<?php
namespace Home\Logic;
use  Home\Model\UserTagModel;

class UserLogic{
    private $modelHandel;
    public function __construct(){
        $this->modelHandel = new UserTagModel();
    }
    public function getTagList()
    {
        $map = [];
        $list =  $this->modelHandel->getAllByCondition($map, '', "id desc");
        return $list;
    }
}