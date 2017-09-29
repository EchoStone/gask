<?php

namespace Home\Model;

use Think\Model;


/**
 * 用于定义数据相关的自动验证和自动完成和数据存取接口-公用类
 *
 * @author Stone
 */
class CommonModel extends Model
{

    /**
     * 通过ID得到一条数据
     *
     * @param $id 数据的id
     * @param string $field 需要的字段
     * @param array $map 额外的条件
     * @param array $join 连表查询
     * @return bool|mixed ，无数据为false；
     *         @date:2014.09.17
     * @author :Stone.geng
     */
    public function getOneById($id, $field = '', $map = [], $join = [])
    {
        if (! is_array($map) || ! is_array($join) || (int) $id <= 0) {
            return false;
        }
        if (count($join) > 0) {
            $map[$join['brief'] . '.id'] = $id; // 2014-12-16
            $vo = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->field($field)
                ->join($join['join'])
                ->where($map)
                ->find();
        } else {
            if (! isset($map['id'])) { // tp 3.1 对 find($id) 多个主键情况下支持不完善 2014-12-16
                $map['id'] = $id;
            }
            $vo = $this->field($field)
                ->where($map)
                ->find();
        }
        if (empty($vo)) {
            return false;
        } else {
            return $vo;
        }
    }

    /**
     * :通过条件得到一条数据
     *
     * @date:2014.09.17
     *
     * @author :Stone.geng
     */
    public function getOneByCondition($map = [], $field = '', $order = '', $limit = '', $join = [], $group = '')
    {
        if (! is_array($map) || ! is_array($join)) {
            return false;
        }
        if (count($join) > 0) {
            $vo = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->field($field)
                ->join($join['join'])
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->find();
        } else {
            $vo = $this->field($field)
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->find();
        }
        
        if (empty($vo)) {
            return false;
        } else {
            return $vo;
        }
    }

    /**
     * :通过条件得到多条数据
     *
     * @date:2014.09.18
     *
     * @author :Stone.geng
     */
    public function getAllByCondition($map = [], $field = '', $order = '', $limit = 0, $join = [], $group = '')
    {
        if (! is_array($map) || ! is_array($join)) {
            return false;
        }
        
        if (count($join) > 0) {
            $list = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->field($field)
                ->join($join['join'])
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->select();
        } else {
            $list = $this->field($field)
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->select();
        }
        
        return $list;
    }

    /**
     * :通过条件得到数量
     *
     * @date:2014.09.18
     *
     * @author :Stone.geng
     */
    public function getCountByCondition($map = [], $join = [], $group = '')
    {
        if (! is_array($map) || ! is_array($join)) {
            return false;
        }
        
        if (count($join) > 0) {
            $count = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->join($join['join'])
                ->where($map)
                ->count();
        } else {
            $count = $this->where($map)
                ->group($group)
                ->count();
        }
        
        return $count;
    }

    public function getOneByConditionOther($map, $field = '', $order = '', $limit = '', $join = array(), $group = '')
    {
        if (! is_array($map) || ! is_array($join)) {
            return false;
        }
        if (count($join) > 0) {
            $vo = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->field($field)
                ->join($join['join'])
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->find();
        } else {
            $vo = $this->field($field)
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->find();
        }
        return $vo;
    }

    /**
     * 插入
     *
     * @author :Stone.geng
     */
    public function insert($data)
    {
        $this->add($data);
        return $this->getLastInsID();
    }

    /**
     * 批量插入
     *
     * @author :Stone.geng
     */
    public function insertAll($dataList, $options = array(), $replace = false)
    {
        $this->addAll($dataList, $options, $replace);
        return $this->getLastInsID();
    }

    /**
     * 插入-用于create创建
     */
    public function createInsert()
    {
        $this->add();
        return $this->getLastInsID();
    }

    /**
     * :更新表单数据
     *
     * @author :Stone.geng
     */
    public function update($map, $data = [])
    {
        if (empty($map) || empty($data)) {
            return false;
        }
        $result = $this->where($map)->save($data);
        if ($result !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除或者是逻辑删除
     *
     * @author :Stone.geng
     */
    public function del($map = [], $data = [], $isLogic = true)
    {
        if (empty($map) || (empty($data) && $isLogic)) {
            return false;
        }
        if ($isLogic) {
            if ($this->where($map)->save($data) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($this->where($map)->delete($map) !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 去除重复
     */
    public function getDistinctCountByCondition($map = [], $string = '', $join = [])
    {
        if (! is_array($map) || ! is_array($join)) {
            return false;
        }
        
        if (count($join) > 0) {
            $count = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->join($join['join'])
                ->where($map)
                ->count($string);
        } else {
            $count = $this->where($map)->count($string);
        }
        return $count;
    }

    public function getCountWithGroup($map = [], $field = '', $group = '', $order = '')
    {
        $return = $this->where($map)
            ->group($group)
            ->field($field)
            ->order($order)
            ->select();
        
        if ($return) {
            return $return;
        } else {
            return false;
        }
    }

    public function jointInsertSql($data = [], $tablename = '')
    {
        $sql = 'value(';
        $sql1 = "INSERT INTO $tablename(";
        foreach ($data as $k => $v) {
            $sql1 .= "`$k`,";
            $sql .= "$v,";
        }
        $sql = trim($sql, ',');
        $sql1 = trim($sql1, ',');
        $sql .= ')';
        $sql1 .= ')';
        $sql_all = $sql1 . $sql;
        return $sql_all;
    }

    /**
     * 可链表可分页查询
     *
     * @author :dafa
     */
    public function getList($map = '', $order = '', $field = '', $limit = '20', $type = '1', $param = '', $group = '', $join = [])
    {
        if (empty($order)) {
            $order = $this->getPk() . ' desc';
        }
        if ($type == '2') {
            $count = count($this->join($join)
                ->where($map)
                ->group($group)
                ->select());
            $data['list'] = [];
            $data['page'] = [];
            if ($count > 0) {
                import('ORG.Util.Page');
                // 实例化分页类
                $page = new \Think\Page($count, $limit);
                
                if (count($join) > 0) {
                    $list = $this->table($join['tablename'] . ' ' . $join['brief'])
                        ->field($field)
                        ->join($join['join'])
                        ->where($map)
                        ->limit($page->firstRow . ',' . $page->listRows)
                        ->order($order)
                        ->group($group)
                        ->select();
                } else {
                    $list = $this->field($field)
                        ->where($map)
                        ->limit($page->firstRow . ',' . $page->listRows)
                        ->order($order)
                        ->group($group)
                        ->select();
                }
                
                // 分页跳转的时候保证查询条件
                foreach ($param as $key => $val) {
                    if (! is_array($val)) {
                        $page->parameter .= "$key=" . urlencode($val) . '&';
                    }
                }
                $show = $page->show();
                $data['list'] = $list;
                $data['page'] = $show;
                $data['count'] = $count;
            }
            return $data;
        } else {
            $list = $this->field($field)
                ->where($map)
                ->limit($limit)
                ->order($order)
                ->group($group)
                ->select();
            return $list;
        }
    }

    /**
     * 读取字段值
     *
     * @author :dafa
     */
    public function getFieldByCondition($map, $field, $all, $join = [])
    {
        if (count($join) > 0) {
            $return = $this->table($join['tablename'] . ' ' . $join['brief'])
                ->join($join['join'])
                ->where($map)
                ->getField($field, $all);
        } else {
            $return = $this->where($map)->getField($field, $all);
        }
        if (empty($return)) {
            return false;
        } else {
            return $return;
        }
    }

    public function getListJoin($map = '', $order = '', $field = '', $limit = '20', $group = '', $join = [])
    {
        if (empty($order)) {
            $order = $this->getPk() . ' desc';
        }
        $list = $this->table($join['tablename'] . ' ' . $join['brief'])
            ->field($field)
            ->join($join['join'])
            ->where($map)
            ->limit($limit)
            ->order($order)
            ->group($group)
            ->select();
        return $list;
    }

    /**
     * 查询字段
     */
    public function selectField($map = [], $field = '', $limit = 1, $order = '', $group = '')
    {
        $retField = $this->where($map)
            ->group($group)
            ->order($order)
            ->getField($field, $limit);
        return $retField;
    }
}
