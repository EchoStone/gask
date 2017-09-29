<?php
namespace Home\Logic;
use  Home\Model\UserModel;
use  Home\Model\UserTagModel;
class UserLogic{
    private $modelHandel;
    private $tagmodelHandel;
    public function __construct(){
        $this->modelHandel = new UserModel();
        $this->tagmodelHandel = new UserTagModel();
    }
    public function getUserList()
    {
        $map = [];
        $list =  $this->modelHandel->getAllByCondition($map, '', "id desc");
        $ids = [];
        foreach($list as $userInfo){
            array_push($ids, $userInfo["id"]);
        }
        $newlist = [];
        if($ids){
            $tagList = $this->tagmodelHandel->getTagListByIds($ids);

            foreach($list as $userInfo){
                $newlist[$userInfo['id']] = $userInfo;
                array_push($ids, $userInfo["id"]);
            }

            foreach($tagList as $tag){
                $newlist[$tag["user_id"]]['tag'][] = $tag['tag'];
            }
        }

        return $newlist;

    }

    public function updateUser($userId,$brief, $askprice){
        $map = ["id" => $userId];
        $data = ["brief" => $brief, "ask_price" => $askprice];
        return $this->modelHandel->update($map, $data);
    }
}