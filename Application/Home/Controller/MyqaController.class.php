<?php
namespace Home\Controller;
use Home\Logic\QuestionLogic;

class MyqaController extends BaseController{
    private $logic;
    private $userId;
    public function __construct(){
        parent::__construct();
        $this->userId = session("userID");
        $this->logic = new QuestionLogic();
    }
    public function question(){
        $list = $this->logic->getMyQuestionList($this->userId);
        $this->assign('list', $list);
        $this->display();
    }

    public function answer(){
        $list = $this->logic->getMyAnswerList($this->userId);
        $this->assign('list', $list);
        $this->display();
    }
}