<?php
namespace Home\Controller;

use Common\Controller\CenterController;
use Common\Model\UserModel;

class AccountSetController extends CenterController
{

    public function myProfile()
    {
        $userModel = new \Common\Model\UserModel();
        $userRow   = $userModel->getUserInfo($this->_userId);
        if (IS_POST) {
            $userModel              = new \Common\Model\UserModel();
            $conditon['first_name'] = I("post.first_name");
            $conditon['last_name']  = I("post.last_name");
            $conditon['gender']     = I("post.gender");
            $phoneArr               = I("post.phone_tel");
            $faxArr                 = I("post.fax");
            $conditon['phone_tel']  = $phoneArr[0] . '-' . $phoneArr[1] . '-' . $phoneArr[2];
            $conditon['fax']        = $faxArr[0] . '-' . $faxArr[1] . '-' . $faxArr[2];
            $result                 = $userModel->modify($conditon, $this->_userId);
            $this->redirect('AccountSet/myProfile');
        }
        $this->assign('userRow', $userRow)
            ->assign('phone', explode('-', $userRow['phone_tel']))
            ->assign('fax', explode('-', $userRow['fax']));
        $this->display('AccountSet/myProfile');
    }

    public function securityCenter()
    {
        $this->display('AccountSet/securityCenter');
    }

    public function emailModify()
    {
        $this->display('AccountSet/emailModify');
    }

    public function resetMailBox()
    {
        if (IS_POST) {
            $condition['id']       = $this->_userId;
            $condition['password'] = I('post.password');
            $newEmail              = I("post.email");
            //验证是否合法
            $userModel = new \Common\Model\UserModel();
            $return    = $userModel->sendResetEmail($condition, $newEmail);
            if (!$return['status']) {
                $this->jump($return['info'], 0, U('AccountSet/emailModify'));
            }
            layout('Layout/email');
            $this->assign('url', $_SERVER['SERVER_NAME'] . U('Home/AccountSet/resetMailBoxAccess', array('str' => $return['data']['str'])));
            $mailContent = $this->fetch('AccountSet/resetMailBoxEmail');
            //发送邮件
            $result = sendMail($newEmail, 'Change of Log in Email confirmation', $mailContent);
            layout('Layout/center');
            if ($result['status']) {
                $this->assign('email', $newEmail);
                $this->display('AccountSet/resetMailBox');
            } else {
                $this->jump($result['info'], 0, U('AccountSet/emailModify'));
            }
        }
    }

    //验证更改邮箱
    public function resetMailBoxAccess()
    {
        if (IS_GET) {
            $userModel = new \Common\Model\UserModel();
            $return    = $userModel->debaseMailBox(I('get.str'));
            if (!$return['status']) {
                $this->jump($result['status'], 0, U('AccountSet/emailModify'));
            }
            $this->display('AccountSet/resetMailBoxAccess');
        }
    }

    //更改邮箱邮件重发
    public function resendResetMailBox()
    {
        if (IS_AJAX) {
            $userModel = new \Common\Model\UserModel();
            $str       = $userModel->baseResendResetEmail($this->_userId, I('post.email'));
            layout('Layout/email');
            $this->assign('url', $_SERVER['SERVER_NAME'] . U('Home/AccountSet/resetMailBoxAccess', array('str' => $str)));
            $mailContent = $this->fetch('AccountSet/resetMailBoxEmail');
            //发送邮件
            $result = sendMail(I('post.email'), 'Change of Log in Email confirmation', $mailContent);
            $this->ajaxReturn($result);
        }
    }

    public function passwordModify()
    {
        if (IS_POST) {
            $userModel = new \Common\Model\UserModel();
            $result    = $userModel->passwordModify($this->_userId, I('post.oldPwd'), I("post.newPwd"));
            if ($result['status']) {
                $this->redirect('AccountSet/pwdChangeSucess');
            } else {
                $this->jump($result['info'], 0, U("AccountSet/passwordModify"));
            }
        }
        $this->display('AccountSet/passwordModify');
    }

    public function pwdChangeSucess()
    {
        $this->display('AccountSet/pwdChangeSucess');
    }

    public function vipClub()
    {
        $logon = $this->_userId ? true : false;

        if ($logon) {
            $vipIsVia = D('User')->vipIsVia($this->_userId);
            $this->assign('vipIsVia', $vipIsVia);
        }

        $this->assign('logon', $logon);

        $this->display('AccountSet/vipClub');
    }
}
