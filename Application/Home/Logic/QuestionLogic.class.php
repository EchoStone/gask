<?php
namespace Home\Logic;
use  Home\Model\QuestionModel;
use Home\Model\UserModel;

class QuestionLogic{
    private $modelHandel;
    private $userModelHandel;
    public function __construct(){
        $this->modelHandel = new QuestionModel();
        $this->userModelHandel = new UserModel();
    }
    public function getMyQuestionList($userId)
    {
        $map = ["q.user_id" => $userId];
        //($map = [], $field = '', $order = '', $limit = 0, $join = [], $group = '')
        $field = 'q.*,u.brief,u.head_url';
        $join = [
            'tablename' => 'gask_question',
            'brief' => 'q',
            'join' => 'LEFT JOIN gask_user u on q.answer_user_id = u.id'
        ];
        $list =  $this->modelHandel->getAllByCondition($map, $field, "id desc", 0, $join);

        return $list;
    }

}