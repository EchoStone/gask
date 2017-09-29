<?php

namespace Home\Controller;
use Home\Logic\UserLogic;
use Home\Logic\AnswerLogic;
use Home\Logic\UserTagLogic;
class UcenterController extends BaseController
{
    /**
     * 用户中心
     */

    private $logic;
    private $userId;
    private $answerLogic;
    private $tagLogic;
    public function __construct(){
        parent::__construct();
        $this->userId = session("userID");
        $this->logic = new UserLogic();
        $this->answerLogic = new AnswerLogic();
        $this->tagLogic = new UserTagLogic();
    }
    public function index()
    {
        $userInfo = $this->logic->getUserInfoById($this->userId);
        $userInfo['answerNums'] = $this->answerLogic->getAnswerNumsByUid($this->userId);
        $this->assign('userInfo', $userInfo);
        $this->display();
    }

    public function edit(){
        $userInfo = $this->logic->getUserInfoById($this->userId);
        $userInfo['answerNums'] = $this->answerLogic->getAnswerNumsByUid($this->userId);
        $tags = $this->tagLogic->getTagListByUid($this->userId);
        $this->assign('userInfo', $userInfo);
        $this->assign('tags', $tags);
        $this->display("edit");
    }

    public function upInfo(){
        $brief = I("brief");
        $ask_price = I("ask_price");
        $username = I("username");
        if(!$brief || !$ask_price){
            $this->error("请把信息填写完整再提交");
        }
        $upInfo = $this->logic->updateUser($this->userId, $brief, $ask_price, $username);
        $tags = $_POST["tags"];
        if($tags){
            $this->tagLogic->addTags($this->userId, $tags);
        }
        $this->success("修改成功");

    }

    public function delUserFlag(){
        $flagId = I("flagId");
        if(empty($flagId)){
            $this->jsonReturn(null, 999, "参数不能为空");
        }
        if($this->tagLogic->delTag($this->userId, $flagId)){
            $this->jsonReturn("");
        } else {
            $this->jsonReturn(null, 996, "删除失败");
        }
    }

}