<?php
/*
 * 获取用户列表
 */

namespace Home\Logic;

use Home\Model\AnswerModel;
use Home\Model\QuestionModel;
use  Home\Model\UserModel;
use  Home\Model\UserTagModel;

class UserLogic
{
    private $modelHandel;
    private $tagmodelHandel;
    private $questionModel;
    private $answerModel;

    public function __construct()
    {
        $this->modelHandel = new UserModel();
        $this->tagmodelHandel = new UserTagModel();
        $this->questionModel = new QuestionModel();
        $this->answerModel = new AnswerModel();
    }

    public function getUserList()
    {
        $map = [];
        $list = $this->modelHandel->getAllByCondition($map, '', "id desc");
        $ids = [];
        foreach ($list as $userInfo) {
            array_push($ids, $userInfo["id"]);
        }
        $newlist = [];
        if ($ids) {
            $tagList = $this->tagmodelHandel->getTagListByIds($ids);
            $answerList = $this->answerModel->getAnsewerNumsByUids();
            $listenList = $this->answerModel->getListenNumsByUids();

            foreach ($list as $userInfo) {
                $newlist[$userInfo['id']] = $userInfo;
                $newlist[$userInfo['id']]['anserNums'] = !empty($answerList[$userInfo['id']]) ? $answerList[$userInfo['id']] : 0;
                $newlist[$userInfo['id']]['listenNums'] = !empty($listenList[$userInfo['id']]) ? $listenList[$userInfo['id']] : 0;
                array_push($ids, $userInfo["id"]);
            }
            foreach ($tagList as $tag) {
                $newlist[$tag["user_id"]]['tag'][] = $tag['tag'];
            }
        }

        return $newlist;

    }

    public function updateUser($userId, $brief, $askprice)
    {
        $map = ["id" => $userId];
        $data = ["brief" => $brief, "ask_price" => $askprice];
        return $this->modelHandel->update($map, $data);
    }

    public function getUserInfoById($userId){
        return $this->modelHandel->getOneById($userId);
    }

}