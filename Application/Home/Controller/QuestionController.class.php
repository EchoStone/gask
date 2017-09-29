<?php

namespace Home\Controller;
class QuestionController extends BaseController
{

    public function addView($uid)
    {
        $answerUserId = $uid;
        $this->assign('answer_user_id', $answerUserId);
        $this->display('addview');
    }

    /**
     * 提问
     */
    public function add()
    {
        $userID = session('userID');
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

        $ye = round($myInfo['wallet'] - $answerUserInfo['ask_price'], 2);
        if ($ye < 0) {
            $this->jsonReturn([], '997', '您的余额不够!');
        }

        $data['content'] = $postData['content'];
        $data['user_id'] = $userID;
        $data['answer_user_id'] = $answerUserId;
        $data['is_reply'] = 0;
        $data['duration'] = 0;
        $data['price'] = $answerUserInfo['ask_price'];
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
        $this->goHome();
    }

    /**
     * 回答
     */
    public function answer()
    {
        $userID = session('userID');
        $postData = I('post.');
        $nowTime = time();
        $qId = $postData['q_id'];
        $questionInfo = D('Question')->getOneById($qId);
        $data = [];
        $data['q_id'] = $qId;
        $data['answer_user_id'] = $userID;
        $data['voice_url'] = $postData['voice_url'];
        $data['created_at'] = $nowTime;
        $data['updated_at'] = $nowTime;
        $data['q_user_id'] = $questionInfo['user_id'];
        $data['duration'] = $postData['duration'];
        $data['num'] = 1;

        $answerID = D('Answer')->insert($data);
        $logData = [];
        $logData['user_id'] = $userID;
        $logData['money'] = $questionInfo['price'];
        $logData['answer_id'] = $answerID;
        $logData['answer_user_id'] = $userID;
        $logData['type'] = 1;
        $logData['q_id'] = $qId;
        $logData['created_at'] = $nowTime;
        $logData['updated_at'] = $nowTime;
        $moneyLogModel = D('MoneyLog');
        $moneyLogModel->insert($logData);

        $logUpdataMap = [];
        $logUpdataMap['user_id'] = $questionInfo['user_id'];
        $logUpdataMap['q_id'] = $qId;
        $moneyLogModel->update($logUpdataMap, ['type' => 3, 'updated_at' => $nowTime, 'answer_id' => $answerID]);

        D('User')->where(['id' => $userID])->setInc('wallet', $questionInfo['price']);

        $listen = [];
        $listen['q_id'] = $qId;
        $listen['answer_id'] = $answerID;
        $listen['user_id'] = $questionInfo['user_id'];
        $listen['created_at'] = $nowTime;
        $listen['updated_at'] = $nowTime;
        D('Listener')->insert($listen);
        $this->jsonReturn();
    }

    /**
     * 偷听
     */
    public function tou()
    {
        $userID = session('userID');
        $userID = 1;
        $postData = I('post.');
        $qId = $postData['q_id'];
        $answerID = $postData['answer_id'];
        $nowTime = time();
        $listen = [];
        $listen['q_id'] = $qId;
        $listen['answer_id'] = $answerID;
        $listen['user_id'] = $userID;
        $listen['created_at'] = $nowTime;
        $listen['updated_at'] = $nowTime;

        D('Listener')->insert($listen);
    }

}