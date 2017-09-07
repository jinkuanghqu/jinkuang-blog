<?php
namespace Admin\Controller;

use Common\Controller\AdminController;
use Common\Model\GradeModel;
use Common\Model\UserModel;

class UserController extends AdminController
{
    private $_model;

    protected function _initialize()
    {
        parent::_initialize();
        if (!$this->_model) {
            $this->_model = new UserModel();
        }
    }

    //用户列表
    public function index()
    {
        $total      = $this->_model->count();
        $pagesize   = C('PAGE_SIZE');
        $pageObject = new \Org\Util\Page($total, $pagesize);
        $pages      = $pageObject->show();

        $page = I('get.p', 0);

        $userRows = $this->_model->page($page)->limit($pagesize)->select();
        $sexy = array('未知','男','女');
        foreach ($userRows as $key => $value) {
            $userRows[$key]['sex'] = $sexy[$value['sex']];
        }
        $this->assign('userRows', $userRows);
        $this->assign('pages', $pages);

        $this->display('User/index');
    }

    //用户信息
    public function userInfo()
    {
        $userInfo    = $this->_model->getUserInfo(I('post.id'));
        $userInfoDom = $this->fetch('User/userInfo');
        $this->ajaxReturn($userInfoDom);
    }

    //用户等级
    public function userGrade()
    {
        $gradeModel = new gradeModel();

        $total      = $gradeModel->where('type = 2')->count();
        $pagesize   = C('PAGE_SIZE');
        $pageObject = new \Org\Util\Page($total, $pagesize);
        $pages      = $pageObject->show();

        $page = I('get.p', 0);

        $userGradeRows = $gradeModel->where('type = 2')->page($page)->limit($pagesize)->select();
        $this->assign('userGradeRows', $userGradeRows);
        $this->assign('pages', $pages);

        $this->display('User/userGrade');
    }

    //用户等级添加修改
    public function userGradeAddModify()
    {
        if (IS_AJAX) {
            $gradeModel = new gradeModel();
            $result     = $gradeModel->userGradeAddModify();
            $this->ajaxReturn($result);
            exit;
        }
        if (I("get.id")) {
            $gradeModel      = new gradeModel();
            $condition['id'] = I('get.id');
            $userGradeRow    = $gradeModel->getUserGrade($condition, false);
            $this->assign('dataRow', $userGradeRow);
        }
        $this->display('User/userGradeAddModify');
    }

    public function gradeDestroy()
    {
        if (IS_AJAX) {
            $gradeModel = new gradeModel();
            $result     = $gradeModel->destroy();
            $this->ajaxReturn($result);
        }
        exit;
    }
}
