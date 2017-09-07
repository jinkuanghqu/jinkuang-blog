<?php
/**
 * SEO控制器
 *
 * @author jinkkuanghqu@gmail.com
 */

namespace Admin\Controller;

use Common\Controller\AdminController;

class SeoController extends AdminController
{
    protected $_model;

    public function __construct()
    {
        parent::__construct();
    }

    public function _initialize()
    {
        parent::_initialize();

        $this->_model = new \Common\Model\SeoModel();
    }

    public function index()
    {
        $curPage = I('get.p', 1, 'intval');
        $pageSize = C('PAGE_SIZE', null, 10);

        $fieldList = 'id,name,page_path,title,keywords,description';
        $queryParams = true;

        list($total, $seoList, $pageLinks) = $this->_model->getByPage($fieldList, $queryParams, $curPage, $pageSize);

        $this->assign('data', $seoList);
        $this->assign('pages', $pageLinks);

        $this->display();
    }

    public function add()
    {
        if (IS_GET) {
            $this->display();
        } else if (IS_AJAX){
            $data = array(
                'name' => I('post.name', ''),
                'page_path' => I('post.path', ''),
                'title' => I('post.title', ''),
                'keywords' => I('post.keywords', ''),
                'description' => I('post.description', ''),
            );

            if (false === ($insertId = $this->_model->addOne($data))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'info' => $this->_model->getError()
                ));
                exit();
            }
            $this->ajaxReturn(array(
                    'status' => 1,
                    'info' => '添加成功',
                    'url' => U('/Admin/Seo/index'),
                ));
            addAdminLog('添加页面SEO['. $insertId . ']成功');
        }
    }
    public function edit()
    {
        $id = I('param.id', 0, 'intval');

        if (IS_GET) {
            $item = $this->_model->find($id);

            $this->assign('item', $item);
            $this->display();
        } else if (IS_AJAX){
            $data = array(
                'name' => I('post.name', ''),
                'page_path' => I('post.path', ''),
                'title' => I('post.title', ''),
                'keywords' => I('post.keywords', ''),
                'description' => I('post.description', ''),
            );

            $entryId = $this->_model->nameExists($data['name']);
            if (!empty($entryId) && $entryId != $id) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'info' => '名字与已存在的重复',
                ));
                exit();
            }
            $entryId = $this->_model->pathExists($data['page_path']);
            if (!empty($entryId) && $entryId != $id) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'info' => '页面路径与已存在的重复',
                ));
                exit();
            }
            if (false === $this->_model->updateOne(array('id' => $id), $data)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'info' => $this->_model->getError()
                ));
                exit();
            }
            $this->ajaxReturn(array(
                    'status' => 1,
                    'info' => '修改成功',
                    'url' => U('/Admin/Seo/index'),
                ));
            addAdminLog('修改页面SEO['. $id . ']成功');
        }
    }
    public function destroy()
    {
        $id = I('param.id', 0, 'intval');

        if ($this->_model->delete($id)) {
            $this->ajaxReturn(array(
                'status' => 1,
                'info' => '操作成功',
            ));
            addAdminLog('删除页面SEO[' . $id . ']成功');
        } else {
            $this->ajaxReturn(array(
                'status' => 0,
                'info' => '操作失败',
            ));
        }
    }
    public function verifyName()
    {
        $returnData = array(
            'status' => 0,
        );

        $name = I('post.name');
        if ($this->_model->nameExists($name)) {
            $returnData = array(
                'status' => 1,
                'msg' => '该名字已存在',
            );
        }
        $this->ajaxReturn($returnData);
    }
    public function verifyPath()
    {
        $returnData = array(
            'status' => 0
        );

        $path = I('post.name');
        if ($this->_model->pathExists($path)) {
             $returnData = array(
                 'status' => 1,
                 'msg' => '该路径已存在',
             );
        }
        $this->ajaxReturn($returnData);
    }
}
