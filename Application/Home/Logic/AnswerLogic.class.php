<?php
namespace Home\Logic;
use Home\Model\AnswerModel;

class AnswerLogic{
    private $modelHandel;
    public function __construct(){
        $this->modelHandel = new AnswerModel();
    }

    public function getAnswerNumsByUid($userId){
        return $this->modelHandel->getAnsewerNumsByUid($userId);
    }


}