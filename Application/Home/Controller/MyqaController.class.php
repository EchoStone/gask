<?php
namespace Home\Controller;
use Home\Logic\QuestionLogic;
use Home\Service\JsSdkService;

class MyqaController extends BaseController{
    private $logic;
    private $userId;
    public function __construct(){
        parent::__construct();
        $this->userId = 2;
        $this->logic = new QuestionLogic();
    }
    public function question(){
        $list = $this->logic->getMyQuestionList($this->userId);
        $this->assign('list', $list);
        $this->display();
    }

    public function answer(){
        $list = $this->logic->getMyAnswerList($this->userId);
        $signPackage = (new JsSdkService())->GetSignPackage();
        $this->assign('list', $list);
        $this->assign('signPackage', $signPackage);
        $this->display();
    }
}