<?php

namespace Home\Model;

use Home\Model\CommonModel;


/**
 * 用于定义数据相关的自动验证和自动完成和数据存取接口-公用类
 *
 * @author Stone
 */
class AnswerModel extends CommonModel
{
    public function getAnsewerNumsByUids()
    {
        $list = $this->query("select count(*) as anserNums, answer_user_id from gask_answer group by answer_user_id");
        $newList = [];
        foreach ($list as $nums) {
            $newList[$nums['answer_user_id']] = $nums['ansernums'];
        }
        return $newList;
    }

    public function getListenNumsByUids()
    {
        $list = $this->getAllByCondition([], $field = 'sum(num) as listenNums, answer_user_id', '', 0, [], 'answer_user_id');
        $newList = [];
        foreach ($list as $nums) {
            $newList[$nums['answer_user_id']] = $nums['listennums'];
        }
        return $newList;
    }

    public function getAnsewerNumsByUid($userId)
    {
        $map['answer_user_id'] = $userId;
        $nums = $this->getCountByCondition($map);
        return $nums;
    }

}