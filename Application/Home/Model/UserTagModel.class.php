<?php

namespace Home\Model;

use Home\Model\CommonModel;


/**
 * 用于定义数据相关的自动验证和自动完成和数据存取接口-公用类
 *
 * @author Stone
 */
class UserTagModel extends CommonModel
{
    public function getTagListByIds($ids)
    {
        $map['user_id'] = ["in", $ids];
        $list =  $this->getAllByCondition($map, '', "id desc");
        return $list;
    }
}