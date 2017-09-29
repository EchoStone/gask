<?php
/**
 * Created by PhpStorm.
 * User: sansan
 * Date: 2017/9/29
 * Time: 14:39
 */

namespace Home\Model;

class UserModel extends \Home\Model\BaseModel{
    public function getUserList($map, $page, $pagesize, $order){
        return $this->getListByMap($map, $page, $pagesize, $order);
    }
}