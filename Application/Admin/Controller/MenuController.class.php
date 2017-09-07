<?php
namespace Admin\Controller;

use Common\Controller\AdminController;

class MenuController extends AdminController
{

    /**
     * 列表
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:46:17+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $authRuleModel = D('AuthRule');

        $authRuleAll = $authRuleModel->getAuthRuleForPid();
        $this->assign('authRuleAll', $authRuleAll);
        $this->display();
    }

    /**
     * 修改
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:46:42+0800
     * @return   [type]                   [description]
     */
    public function edit()
    {
        $authRuleModel = M('AuthRule');
        $id            = I('get.id');
        $authRuleRow   = $authRuleModel->find($id);
        if ($authRuleRow == '') {
            $this->error('菜单不存在或者已删除！');
        }

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );
            $params = I('post.');
            $isViod = $authRuleModel->where(array('id' => $id))->save(array(
                'pid'    => $params['pid'],
                'name'   => $params['name'],
                'title'  => $params['title'],
                'icon'   => $params['icon'],
                'islink' => ($params['islink'] == '') ? 0 : 1,
                'sort'   => $params['sort'],
            ));
            if ($isViod) {
                $data['status'] = 1;
                $data['info']   = '修改成功';
                $data['url']    = U('/Admin/Menu/');

                addAdminLog("修改菜单：ID[{$id}] 成功！");
            } else {
                addAdminLog("修改菜单：ID[{$id}] 失败！");
            }

            $this->ajaxReturn($data);exit;
        }

        $parentRow = $authRuleModel->where(array(
            'pid' => 0,
        ))->select();
        $this->assign('parentRow', $parentRow);
        $this->assign('authRuleRow', $authRuleRow);
        $this->display('add');
    }

    /**
     * 添加
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:46:06+0800
     */
    public function add()
    {
        $authRuleModel = M('AuthRule');
        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );
            $params = I('post.');
            $isViod = $authRuleModel->data(array(
                'pid'    => $params['pid'],
                'name'   => $params['name'],
                'title'  => $params['title'],
                'icon'   => $params['icon'],
                'islink' => ($params['islink'] == '') ? 0 : 1,
                'sort'   => $params['sort'],
            ))->add();

            if ($isViod) {
                $data['status'] = 1;
                $data['info']   = '添加成功！';
                $data['url']    = U('/Admin/Menu/');

                addAdminLog("添加菜单：[{$isViod}] 成功！");
            }
            $this->ajaxReturn($data);exit;
        }
        $parentRow = $authRuleModel->where(array(
            'pid' => 0,
        ))->select();
        $this->assign('parentRow', $parentRow);
        $this->display();
    }

    /**
     * 验证url重复
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:45:46+0800
     * @return   [type]                   [description]
     */
    public function verifyname()
    {
        $data          = array('status' => 0);
        $authRuleModel = M('AuthRule');

        $name = I('post.name');
        $id   = I('get.id', 0);

        $whereData = array(
            'name' => $name,
        );
        if ($id) {
            $whereData['id'] = array('neq', $id);
        }
        $count = $authRuleModel->where($whereData)->count();

        if ($count > 0) {
            $data['status'] = 1;
            $data['msg']    = '菜单链接已存在！';
        }

        $this->ajaxReturn($data);
    }

    public function sort()
    {
        $data          = array('status' => 0, 'info' => '修改失败');
        $value         = I('post.name');
        $id            = I('get.id');
        $authRuleModel = M('AuthRule');
        $isViod        = $authRuleModel->where(array('id' => $id))->save(array(
            'sort' => $value,
        ));
        if ($isViod === false) {
            $this->ajaxReturn($data);
        }
        $data['status'] = 1;
        $data['info']   = '修改成功';

        addAdminLog("修改菜单排序：[{$id}] 成功！");

        $this->ajaxReturn($data);
    }

    /**
     * 删除菜单
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:45:36+0800
     * @return   [type]                   [description]
     */
    public function destroy()
    {
        $data          = array('status' => 0, 'info' => '删除失败');
        $authRuleModel = M('AuthRule');
        $id            = I('id');

        if (!is_array($id)) {
            $id = array($id);
        }

        $isViod = $authRuleModel->where(array('id' => array('in', $id)))->delete();

        if ($isViod === false) {
            $this->ajaxReturn($data);
        }

        addAdminLog("删除菜单：[" . implode(',', $id) . "] 成功！");

        $data['status'] = 1;
        $data['info']   = '删除成功';
        $this->ajaxReturn($data);
    }
}
