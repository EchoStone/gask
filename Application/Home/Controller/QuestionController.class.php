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
        $questionModel = D('Question');
        $questionInfo = $questionModel->getOneById($qId);
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

        $questionModel->update(['id' => $qId], ['is_reply' => 1]);

        $this->jsonReturn();
    }

    /**
     * 偷听
     */
    public function tou()
    {
        $userID = session('userID');
        $postData = I('post.');
        $qId = $postData['q_id'];

        $questionInfo = D('Question')->getOneById($qId);
        if (empty($questionInfo)) {
            $this->jsonReturn([], '997', '无此问题');
        }

        if (empty($questionInfo['is_reply'])) {
            $this->jsonReturn([], '996', '还未有回答');
        }

        if ($questionInfo['user_id'] != $userID) {
            $userModel = D('User');
            $userInfo = $userModel->getOneById($userID);

            $ye = round($userInfo['wallet'] - $questionInfo['price'], 2);
            if ($ye < 0) {
                $this->jsonReturn([], '997', '您的余额不够!');
            }

            $answerInfo = D('Answer')->getOneByCondition(['q_id' => $qId]);

            if ($answerInfo['answer_user_id'] == $userID) {
                $this->jsonReturn();
            }
            $answerID = $answerInfo['id'];
            $answerUserId = $answerInfo['answer_user_id'];
            $nowTime = time();

            $listenerModel = D('Listener');

            $isExist = $listenerModel->getCountByCondition(['q_id' => $qId, 'user_id' => $userID]);
            if ($isExist > 0) {
                $this->jsonReturn();
            }
            // 偷听的人扣全部
            $logData = [];
            $logData[0]['user_id'] = $userID;
            $logData[0]['money'] = $questionInfo['price'];
            $logData[0]['answer_id'] = $answerID;
            $logData[0]['answer_user_id'] = $answerUserId;
            $logData[0]['type'] = 3;
            $logData[0]['q_id'] = $qId;
            $logData[0]['created_at'] = $nowTime;
            $logData[0]['updated_at'] = $nowTime;

            $devPrice = $questionInfo['price'] / 2;
            //回答者的分成
            $logData[1]['user_id'] = $answerUserId;
            $logData[1]['money'] = $devPrice;
            $logData[1]['answer_id'] = $answerID;
            $logData[1]['answer_user_id'] = $answerUserId;
            $logData[1]['type'] = 1;
            $logData[1]['q_id'] = $qId;
            $logData[1]['created_at'] = $nowTime;
            $logData[1]['updated_at'] = $nowTime;

            //问题者的分成
            $logData[2]['user_id'] = $questionInfo['user_id'];
            $logData[2]['money'] = $devPrice;
            $logData[2]['answer_id'] = $answerID;
            $logData[2]['answer_user_id'] = $answerUserId;
            $logData[2]['type'] = 1;
            $logData[2]['q_id'] = $qId;
            $logData[2]['created_at'] = $nowTime;
            $logData[2]['updated_at'] = $nowTime;
            $moneyLogModel = D('MoneyLog');
            $moneyLogModel->insertAll($logData);


            $userModel->where(['id' => $answerUserId])->setInc('wallet', $devPrice);
            $userModel->where(['id' => $questionInfo['user_id']])->setInc('wallet', $devPrice);
            $userModel->where(['id' => $userID])->setDec('wallet', $questionInfo['price']);

            $nowTime = time();
            $listen = [];
            $listen['q_id'] = $qId;
            $listen['answer_id'] = $answerID;
            $listen['user_id'] = $userID;
            $listen['created_at'] = $nowTime;
            $listen['updated_at'] = $nowTime;
            $listenerModel->insert($listen);
            $this->jsonReturn();
        }
        $this->jsonReturn();
    }

}