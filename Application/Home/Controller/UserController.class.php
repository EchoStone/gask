<?php

namespace Home\Controller;

use Home\Logic\UserLogic;
use Home\Logic\UserTagLogic;

class UserController extends BaseController
{
    private $logic;
    private $tagLogic;
    private $userId;

    public function __construct()
    {
        parent::__construct();
        $this->userId = session("userID");
        $this->logic = new UserLogic();
        $this->tagLogic = new UserTagLogic();
    }

    public function userList()
    {
        $list = $this->logic->getUserList();
        $this->assign('list', $list);
        $this->display('userlist');
    }


    public function detail($id)
    {
        $userInfo = D('User')->getOneById($id);
        if(empty($userInfo)){
            p('非法操作！');
        }
        $userInfo['tags'] = D('UserTag')->getTagListByIds([$id]);
        $this->assign('userInfo',$userInfo);
        $userId = session('userID');
        $this->assign('now_user_id',$userId);
        $this->display('detail');
    }

    public function userUpdate()
    {
        $brief = I("brief");
        $askprice = I("askprice");

        if (!$brief || !$askprice) {
            $this->jsonReturn(null, 999, "参数不能为空");
        }

        $upresult = $this->logic->updateUser($this->userId, $brief, $askprice);
        if ($upresult) {
            $this->jsonReturn("");
        } else {
            $this->jsonReturn(null, 998, "更新失败");
        }

    }

    public function addUserFlag()
    {
        $flag = I("flag");
        if ($flag === "") {
            $this->jsonReturn(null, 999, "参数不能为空");
        }
        if ($this->tagLogic->addTag($this->userId, $flag)) {
            $this->jsonReturn("");
        } else {
            $this->jsonReturn(null, 997, "添加失败");
        }
    }

    public function delUserFlag()
    {
        $flagId = I("flagId");
        if (empty($flagId)) {
            $this->jsonReturn(null, 999, "参数不能为空");
        }
        if ($this->tagLogic->delTag($this->userId, $flagId)) {
            $this->jsonReturn("");
        } else {
            $this->jsonReturn(null, 996, "删除失败");
        }
    }

}