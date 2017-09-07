<?php
namespace Admin\Controller;

use Common\Controller\AdminController;
use Common\Model\AdminModel;
use \Org\Util\Auth;

class DefaultController extends AdminController
{
    /**
     * [_initialize description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-01-29T11:52:18+0800
     * @return   [type]                   [description]
     */
    public function _initialize()
    {
        parent::_initialize();
        layout(false);
    }

    /**
     * [login description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-01-29T11:13:12+0800
     * @return   [type]                   [description]
     */
    public function login()
    {
        if (Auth::isLogin()) {
            redirect('/admin/index/index');
        }
        if (IS_POST) {
            $adminModel = new AdminModel();
            $email      = I('post.email');
            $password   = I('post.password');

            if ($adminModel->doLogin($email, $password)) {
                addAdminLog('登录');
                $this->success('登录成功', '/admin/index/index');
            } else {
                $this->error('登录失败');
            }
            exit;

        }
        $this->display();
    }

    /**
     * [logout description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-17T15:05:09+0800
     * @return   [type]                   [description]
     */
    public function logout()
    {
        if (!Auth::isLogin()) {
            redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
        }
        addAdminLog('登出');
        session(null);
        $this->success('登出成功', '/admin/default/login');
    }

    /**
     * 锁定登录
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-17T15:21:12+0800
     * @return   [type]                   [description]
     */
    public function loginlock()
    {
        if (!Auth::isLogin()) {
            redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
        }

        if (IS_POST) {
            $adminModel = new AdminModel();
            $password   = I('post.password');

            if ($adminModel->doLoginLock($password)) {
                $this->success('登录成功', '/admin/index/index');
                exit;
            }
        }
        if (session('admin.overtime') === false) {
            session('admin.overtime', true);
        }
        $this->display();
    }

    /**
     * [noAccess description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T15:03:29+0800
     * @return   [type]                   [description]
     */
    public function noAccess()
    {
        $this->display();
    }

    /**
     * 通过pid获取区域
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-10T17:45:43+0800
     * @return   [type]                   [description]
     */
    public function regionParent()
    {
        $id = I('post.id');

        $data = array('status' => 0, 'data' => []);

        $cityModel = M('City');

        $cityRow = $cityModel->field('city_name,city_id')->cache(true)->where(['country_id' => $id])->select();

        if ($cityRow) {
            $data['data']   = $cityRow;
            $data['status'] = 1;
        }
        $this->ajaxReturn($data);
    }

    public function Port()
    {
        $id = I('post.id');

        $data = array('status' => 0, 'data' => []);

        $portModel = M('Port');

        $portRow = $portModel->cache(true)->where(['city_id' => $id])->select();

        if ($portRow) {
            $data['data']   = $portRow;
            $data['status'] = 1;
        }
        $this->ajaxReturn($data);
    }

    public function Airport()
    {
        $id = I('post.id');

        $data = array('status' => 0, 'data' => []);

        $airportModel = M('Airport');

        $airportRow = $airportModel->cache(true)->where(['city_id' => $id])->select();

        if ($airportRow) {
            $data['data']   = $airportRow;
            $data['status'] = 1;
        }
        $this->ajaxReturn($data);
    }

}
