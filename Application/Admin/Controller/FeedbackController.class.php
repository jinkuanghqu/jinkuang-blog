<?php

/**
 *  用户反馈类目控制器
 * @author simon 2016-03-01
 *
 */

namespace Admin\Controller;

use Common\Controller\AdminController;

class FeedbackController extends AdminController
{
    private $model;
    private $fields = array(
        'subject'     => '反馈主题',
        'name'        => '反馈人',
        'contact_way' => '联系方式',
        'content'     => '反馈内容',
        'addtime'     => '反馈时间',
        'action'      => '操作',
    );
    private $page_num;

    public function __construct()
    {

        parent::__construct();
        $this->page_num = C('PAGE_SIZE', null, 20);
        $this->model    = D('Feedback');

    }

    public function _initialize()
    {
        parent::_initialize();

    }

    public function index()
    {

        $cur_page  = I('get.p', 1, 'intval');
        $feedbacks = $this->model->getFeedback($cur_page, $this->page_num);

        // 分页
        $total_count = $this->model->count();
        $this->assign('page_note', '<span class="rows">每页' . $this->page_num . '条, 共 ' . $total_count . ' 条记录</span>');
        $Page       = new \Org\Util\Page($total_count, $this->page_num);
        $page_links = $Page->show();
        $this->assign('pages', $page_links);

        $this->assign("fields", $this->fields);
        $this->assign("feedbacks", $feedbacks);
        $this->display();

    }

    public function getContent()
    {

        $id = $_GET['id'];

        $feedbackContent = $this->model->find($id);

        if (!$feedbackContent) {

            die("未找到此内容！");

        }

        $this->assign("feedbackContent", $feedbackContent);
        $this->display("content");

    }

    public function delete()
    {

        $id = $_GET['id'];
        $this->model->where("id=$id")->delete();

        $log_str = '删除反馈内容，ID为' . $id;
        addAdminLog($log_str);

        $this->redirect('index');

    }

}
