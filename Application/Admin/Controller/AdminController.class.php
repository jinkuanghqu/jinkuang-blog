<?php
namespace Admin\Controller;

use Common\Controller\AdminController as C_AdminController;

class AdminController extends C_AdminController
{
    /**
     * 角色列表页面
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-26T15:07:52+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $adminModel = D('Admin');

        $total      = $adminModel->count();
        $pagesize   = C('PAGE_SIZE');
        $pageObject = new \Org\Util\Page($total, $pagesize);
        $pages      = $pageObject->show();

        $page = I('get.p', 0);

        $adminRows = $adminModel->page($page)->limit($pagesize)->relation(true)->select();
        $this->assign('adminRows', $adminRows);
        $this->assign('pages', $pages);
        $this->display();
    }

    /**
     * 添加角色页面
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-26T15:08:05+0800
     */
    public function add()
    {
        $roleModel  = D('Role');
        $adminModel = D('Admin');
        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );

            $params = I('post.');
            if ($params['password'] != $params['password_confirm']) {
                $data['info'] = "两次输入的密码不正确！";
                $this->ajaxReturn($data);exit;
            }

            if ($roleModel->where(array('id' => $params['role_id']))->count() == 0) {
                $data['info'] = "用户组不存在！";
                $this->ajaxReturn($data);exit;
            }

            $isViod = $adminModel->data(array(
                'name'        => $params['name'],
                'email'       => $params['email'],
                'role_id'     => $params['role_id'],
                'status'      => ($params['status'] == '') ? 0 : 1,
                'password'    => sha1(md5($params['password'])),
                'add_time'    => NOW_TIME,
                'update_time' => NOW_TIME,
            ))->add();

            if ($isViod) {
                $data['status'] = 1;
                $data['info']   = '添加成功';
                $data['url']    = U('/Admin/Admin');

                addAdminLog("添加用户：[" . $isViod . "] 成功！");
            }
            $this->ajaxReturn($data);exit;
        }

        $roleRows = $roleModel->select();
        $this->assign('roleRows', $roleRows);
        $this->display();
    }

    /**
     * 修改角色页面
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-26T15:08:14+0800
     * @return   [type]                   [description]
     */
    public function edit()
    {
        $roleModel  = D('Role');
        $adminModel = D('Admin');
        $id         = I('get.id');

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '修改失败',
            );
            $params = I('post.');
            if ($params['password'] && $params['password'] != $params['password_confirm']) {
                $data['info'] = "两次输入的密码不正确！";
                $this->ajaxReturn($data);exit;
            }

            if ($roleModel->where(array('id' => $params['role_id']))->count() == 0) {
                $data['info'] = "用户组不存在！";
                $this->ajaxReturn($data);exit;
            }

            $saveData = array(
                'name'        => $params['name'],
                'email'       => $params['email'],
                'role_id'     => $params['role_id'],
                'status'      => ($params['status'] == '') ? 0 : 1,
                'update_time' => NOW_TIME,
            );

            if (!empty($params['password'])) {
                $saveData['password'] = sha1(md5($params['password']));
            }

            $isViod = $adminModel->where(array('id' => $id))->save($saveData);

            if ($isViod) {
                $data['status'] = 1;
                $data['info']   = '修改成功';
                $data['url']    = U('/Admin/Admin/');

                addAdminLog("修改用户：[" . $isViod . "] 成功！");
            }
            $this->ajaxReturn($data);exit;
        }
        $adminRow = $adminModel->find($id);
        $roleRows = $roleModel->select();

        $this->assign('roleRows', $roleRows);
        $this->assign('adminRowForEdit', $adminRow);

        $this->display('add');
    }

    /**
     * [verifyemail description]
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-26T17:20:58+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function verifyemail()
    {
        $data = array('status' => 0);

        $adminModel = M('Admin');
        $email      = I('post.name');
        $id         = I('get.id');
        $whereData  = array(
            'email' => $email,
        );
        if ($id) {
            $whereData['id'] = array('neq', $id);
        }
        $count = $adminModel->where($whereData)->count();

        if ($count > 0) {
            $data['status'] = 1;
            $data['msg']    = '邮箱已存在！';
        }

        $this->ajaxReturn($data);
    }

    /**
     * 删除角色页面
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-26T15:08:27+0800
     * @return   [type]                   [description]
     */
    public function destroy()
    {
        $data       = array('status' => 0, 'info' => '删除失败');
        $adminModel = M('Admin');
        $id         = I('id');

        if (!is_array($id)) {
            $id = array($id);
        }

        $isViod = $adminModel->where(array('id' => array('in', $id)))->delete();

        if ($isViod === false) {
            $this->ajaxReturn($data);
        }
        $data['status'] = 1;
        $data['info']   = '删除成功';

        addAdminLog("删除用户：[" . implode(',', $id) . "] 成功！");

        $this->ajaxReturn($data);
    }
}
