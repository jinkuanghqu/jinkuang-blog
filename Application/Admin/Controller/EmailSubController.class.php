<?php
namespace Admin\Controller;

use Common\Controller\AdminController;

class EmailSubController extends AdminController
{

    public function sendEmail()
    {

        if (IS_AJAX) {
            $data = array(
                "title"     => $_POST['title'],
                "content"   => $_POST['content'],
                "sender_id"   => $_SESSION['admin']['id'],
                "send_time" => time(),
                "id" => 0,
            );
            $Jobs = new \Admin\Model\JobsModel();
            $ret = $Jobs->addJobs("emailSub", $data);
            if($ret){
                $log = '发送订阅邮件:标题'.I('post.title');
                addAdminLog($log);
                $return_data = array(
                    'status' => 1,
                    'info'   => '发送邮件成功！',
                    );
            } else {
                $return_data = array(
                    'status' => 1,
                    'info'   => '发送邮件失败！',
                    );
            }
            $this->ajaxReturn($return_data);exit;
        }
        $this->display('EmailSub/sendEmail');
    }

    public function test()
    {
        $emailSub = new \Common\Api\EmailSubApi();
        $ret = $emailSub->fetch();
        echo "<pre>";
        print_r($ret);
        echo "</pre>";
    }
}
