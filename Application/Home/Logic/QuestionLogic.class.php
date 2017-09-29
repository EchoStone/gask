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
        $field = 'q.*,u.username,u.head_url,a.voice_url';
        $join = [
            'tablename' => 'gask_question',
            'brief' => 'q',
            'join' => 'LEFT JOIN gask_user u on q.answer_user_id = u.id LEFT JOIN gask_answer a on q.id = a.q_id',
        ];
        $list =  $this->modelHandel->getAllByCondition($map, $field, "id desc", 0, $join);

        return $list;
    }

    public function getMyAnswerList($userId)
    {
        $map = ["q.answer_user_id" => $userId];
        //($map = [], $field = '', $order = '', $limit = 0, $join = [], $group = '')
        $field = 'q.*,u.username,u.head_url,a.voice_url';
        $join = [
            'tablename' => 'gask_question',
            'brief' => 'q',
            'join' => 'LEFT JOIN gask_user u on q.user_id = u.id LEFT JOIN gask_answer a on q.id = a.q_id',
        ];
        $list =  $this->modelHandel->getAllByCondition($map, $field, "id desc", 0, $join);

        return $list;
    }
    /*
    public function get
    */

}