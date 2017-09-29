<?php
/**
 * Created by PhpStorm.
 * User: sansan
 * Date: 2017/9/29
 * Time: 14:33
 */

namespace Home\Model;

class BaseModel extends \Think\Model{
    public function getListByMap($map, $page, $pagesize, $order){
        $rs = $this->where($map)->page($page, $pagesize)->order($order)->select();
        return $rs;
    }

    public function findInfoByMap($map){
        $rs = $this->where($map)->find();
        return $rs;
    }
}