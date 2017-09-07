<?php
namespace Admin\Controller;

use Common\Controller\AdminController;

class RoleController extends AdminController
{
    /**
     * 角色列表页面
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T15:07:52+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $roleMode = M('Role');

        $total      = $roleMode->count();
        $pagesize   = C('PAGE_SIZE');
        $pageObject = new \Org\Util\Page($total, $pagesize);
        $pages      = $pageObject->show();

        $page = I('get.p', 0);

        $roleRows = $roleMode->page($page)->limit($pagesize)->select();

        $this->assign('roleRows', $roleRows);
        $this->assign('pages', $pages);
        $this->display();
    }

    /**
     * 添加角色页面
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T15:08:05+0800
     */
    public function add()
    {
        $authRuleModel = D('AuthRule');
        $authRuleAll   = $authRuleModel->getAuthRuleForPid();

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );

            $params    = I();
            $roleModel = D('Role');

            $insertId = $roleModel->relation(true)->data(array(
                "name"   => $params["name"],
                "status" => ($params["status"] == '') ? 0 : 1,
                "remark" => $params["remark"],
                "rules"  => $params['rules'],
            ))->add();

            if ($insertId > 0) {
                $data['status'] = 1;
                $data['info']   = '添加成功';
                $data['url']    = U('/Admin/Role/');

                addAdminLog("添加角色：[" . $insertId . "] 成功！");
            }

            $this->ajaxReturn($data);exit;
        }

        $this->assign('authRuleAll', $authRuleAll);
        $this->display();
    }

    /**
     * 修改角色页面
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T15:08:14+0800
     * @return   [type]                   [description]
     */
    public function edit()
    {
        $id            = I('get.id');
        $authRuleModel = D('AuthRule');
        $roleModel     = D('Role');

        $roleRow = $roleModel->relation(true)->find($id);
        if (empty($roleRow)) {
            $this->display('角色不存在，或者已删除！');
        }
        $roleAuthRuleRows = array_column($roleRow['rules'], 'id');

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );
            $params = I('post.');

            $isViod = $roleModel->where(array('id' => $id))->relation(true)->save(array(
                "name"   => $params["name"],
                "status" => ($params["status"] == '') ? 0 : 1,
                "remark" => $params["remark"],
                "rules"  => $params['rules'],
            ));
            if ($isViod === false) {
                $this->ajaxReturn($data);exit;
            }

            $data['status'] = 1;
            $data['info']   = '修改成功';
            $data['url']    = U('/Admin/Role/');

            addAdminLog("修改角色：[" . $id . "] 成功！");

            $this->ajaxReturn($data);exit;
        }

        $authRuleAll = $authRuleModel->getAuthRuleForPid();

        $this->assign('roleAuthRuleRows', $roleAuthRuleRows);
        $this->assign('roleRow', $roleRow);
        $this->assign('authRuleAll', $authRuleAll);
        $this->display('add');
    }

    /**
     * 删除角色页面
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T15:08:27+0800
     * @return   [type]                   [description]
     */
    public function destroy()
    {
        $data      = array('status' => 0, 'info' => '删除失败');
        $roleModel = D('Role');
        $id        = I('id');

        if (!is_array($id)) {
            $id = array($id);
        }

        $isViod = $roleModel->where(array('id' => array('in', $id)))->relation(true)->delete();

        if ($isViod === false) {
            $this->ajaxReturn($data);
        }

        addAdminLog("删除角色：[" . implode(',', $id) . "] 成功！");
        $data['status'] = 1;
        $data['info']   = '删除成功';
        $this->ajaxReturn($data);
    }
}
