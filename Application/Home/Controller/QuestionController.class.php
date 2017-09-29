<?php

namespace Home\Controller;
class QuestionController extends BaseController
{
    /**
     * 提问
     */
    public function add()
    {
        $userID = session('userID');
        $userID = 1;
        $postData = I('post.');
        $nowTime = time();
        $data = [];
        $answerUserId = $postData['answer_user_id'];
        $userModel = D('User');
        $answerUserInfo = $userModel->getOneById($answerUserId);
        if (empty($answerUserInfo)) {
            $this->jsonReturn([], '997', '回答者不存在!');
        }
        $myInfo = $userModel->getOneById($userID);

        $ye = $myInfo['wallet'] - $answerUserInfo['ask_price'];
        if ($ye < 0) {
            $this->jsonReturn([], '997', '您的余额不够!');
        }

        $data['content'] = $postData['content'];
        $data['user_id'] = $userID;
        $data['answer_user_id'] = $answerUserId;
        $data['is_reply'] = 0;
        $data['duration'] = $postData['duration'];
        $data['created_at'] = $nowTime;
        $data['updated_at'] = $nowTime;
        $qId = D('Question')->insert($data);
        $logData = [];
        $logData['user_id'] = $userID;
        $logData['money'] = $answerUserInfo['ask_price'];
        $logData['answer_id'] = 0;
        $logData['answer_user_id'] = $answerUserId;
        $logData['type'] = 2;
        $logData['q_id'] = $qId;
        $logData['created_at'] = $nowTime;
        $logData['updated_at'] = $nowTime;
        $logId = D('MoneyLog')->insert($logData);
        if (!empty($logId)) {
            $userModel->update(['id' => $userID], ['wallet' => $ye]);
        }
        $this->jsonReturn();
    }

    /**
     * 回答
     */
    public function answer()
    {
        $userID = session('userID');
        $userID = 1;
        $postData = I('post.');


    }
}