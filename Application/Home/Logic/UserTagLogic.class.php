<?php
namespace Home\Logic;
use  Home\Model\UserTagModel;

class UserTagLogic{
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

    public function addTag($userId, $tag){
        $data = ["user_id" => $userId, "tag" => $tag];
        return $this->modelHandel->insert($data);
    }

    public function delTag($userId, $id){
        $map = ["user_id" => $userId, "id" => $id];
        return $this->modelHandel->del($map, [], false);
    }


}