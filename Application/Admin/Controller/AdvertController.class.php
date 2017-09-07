<?php

namespace Admin\Controller;

use Common\Controller\AdminController;

class AdvertController extends AdminController
{
    /**
     * [index description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T09:50:49+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $webCallModel = D('WebCall');
        $total        = $webCallModel->count();
        $pagesize     = C('PAGE_SIZE');
        $pageObject   = new \Org\Util\Page($total, $pagesize);
        $pages        = $pageObject->show();

        $page = I('get.p', 0);

        $webCallRows = $webCallModel->page($page)->limit($pagesize)->select();
        $this->assign('webCallRows', $webCallRows);
        $this->assign('pages', $pages);
        $this->display();
    }

    /**
     * [add description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T10:29:41+0800
     */
    public function add()
    {
        $webCallModel = D('WebCall');

        $fileDirUtilModel = new \Org\Util\FileDirUtil();
        $viewPath         = $webCallModel->getViewPath();
        $viewFiles        = $fileDirUtilModel->dirList($viewPath, C('TMPL_TEMPLATE_SUFFIX'));
        $viewFileRows     = array();
        foreach ($viewFiles as $key => $value) {
            $viewFileRows[] = basename(str_ireplace($viewPath, '', $value), C('TMPL_TEMPLATE_SUFFIX'));
        }

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );

            $params = I('post.');

            $count = $webCallModel->where("code='{$params['code']}'")->count();
            if ($count > 0) {
                $data['info'] = "代码已存在！";
                $this->ajaxReturn($data);
            }

            if (!in_array($params['view'], $viewFileRows)) {
                $data['info'] = "调用类型模板不存在！";
                $this->ajaxReturn($data);
            }

            $isVoid = $webCallModel->data(array(
                'name'           => $params['name'],
                'code'           => $params['code'],
                'view'           => $params['view'],
                'display_number' => $params['display_number'],
            ))->add();

            if ($isVoid === false) {
                $this->ajaxReturn($data);
            }

            $data['status'] = 1;
            $data['info']   = '添加成功';
            $data['url']    = U('/Admin/Advert');

            addAdminLog("添加调用：[" . $isVoid . "] 成功！");

            $this->ajaxReturn($data);

        }

        $this->assign('viewFileRows', $viewFileRows);
        $this->display();
    }

    /**
     * [edit description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T10:35:18+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function edit()
    {
        $webCallModel = D('WebCall');
        $id           = I('get.id');

        $webCallRow = $webCallModel->find($id);
        if (!$webCallRow) {
            $this->error('不存在！');
        }

        $fileDirUtilModel = new \Org\Util\FileDirUtil();
        $viewPath         = $webCallModel->getViewPath();
        $viewFiles        = $fileDirUtilModel->dirList($viewPath, C('TMPL_TEMPLATE_SUFFIX'));
        $viewFileRows     = array();
        foreach ($viewFiles as $key => $value) {
            $viewFileRows[] = basename(str_ireplace($viewPath, '', $value), C('TMPL_TEMPLATE_SUFFIX'));
        }

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '修改失败',
            );

            $webCallModel = D('WebCall');

            $params = I('post.');

            $count = $webCallModel->where("code='{$params['code']}' AND id <> '{$id}'")->count();
            if ($count > 0) {
                $data['info'] = "代码已存在！";
                $this->ajaxReturn($data);
            }

            if (!in_array($params['view'], $viewFileRows)) {
                $data['info'] = "调用类型模板不存在！";
                $this->ajaxReturn($data);
            }

            $isVoid = $webCallModel->where("id='{$id}'")->save(array(
                'name'           => $params['name'],
                'code'           => $params['code'],
                'view'           => $params['view'],
                'display_number' => $params['display_number'],
            ));

            if ($isVoid === false) {
                $this->ajaxReturn($data);
            }

            $data['status'] = 1;
            $data['info']   = '修改成功';
            $data['url']    = U('/Admin/Advert');

            addAdminLog("修改调用：[" . $id . "] 成功！");

            $this->ajaxReturn($data);

        }

        $this->assign('viewFileRows', $viewFileRows);
        $this->assign('webCallRow', $webCallRow);
        $this->display('add');
    }

    /**
     * [verifyCode description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T10:16:56+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function verifyCode()
    {
        $data = array('status' => 0);

        $webCallModel = D('WebCall');
        $code         = I('post.name');
        $id           = I('get.id', false);
        $whereData    = array('code' => $code);
        if ($id) {
            $whereData['id'] = array('neq', $id);
        }
        $count = $webCallModel->where($whereData)->count();

        if ($count == 0) {
            $this->ajaxReturn($data);
        }
        $data['status'] = 1;
        $data['msg']    = '菜单链接已存在！';

        $this->ajaxReturn($data);
    }

    /**
     * 删除调用
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:45:36+0800
     * @return   [type]                   [description]
     */
    public function destroy()
    {
        $data = array('status' => 0, 'info' => '删除失败');

        $webCallModel       = D('WebCall');
        $webCallDetailModel = D('WebCallDetail');

        $id = I('id');
        if (!is_array($id)) {
            $id = array($id);
        }

        $count = $webCallDetailModel->where(array(
            'web_call_id' => array('in', $id),
        ))->count();

        if ($count > 0) {
            $data['info'] = '删除失败,本类型下有内容！';
            $this->ajaxReturn($data);
        }

        $isViod = $webCallModel->where(array('id' => array('in', $id)))->delete();

        if ($isViod === false) {
            $this->ajaxReturn($data);
        }

        addAdminLog("删除调用：[" . implode(',', $id) . "] 成功！");

        $data['status'] = 1;
        $data['info']   = '删除成功';
        $this->ajaxReturn($data);
    }
}
