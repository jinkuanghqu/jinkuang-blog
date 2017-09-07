<?php
namespace Home\Behavior;

use \Common\Model\UserModel;
use \Common\Model\UserPaypointsLogModel;

use \Think\Behavior;

class UserPaypointsLogBehavior extends Behavior
{

    public function run(&$logData)
    {
        $userModel = new UserModel();
        $paypointsLogModel = new UserPaypointsLogModel();

        $currentPoints   = $userModel->where(array('id' => $logData['user_id']))->getField('paypoints');

        $logData['balance']  = $currentPoints;
        $logData['add_time'] = time();

        return $paypointsLogModel->add($logData);
    }
}
