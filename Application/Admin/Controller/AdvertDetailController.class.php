<?php

namespace Admin\Controller;

use Common\Controller\AdminController;
use Org\Util\FileUpdateASDataURL;

class AdvertDetailController extends AdminController
{

    /**
     * [index description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T19:38:42+0800
     * @return   [type]                   [description]
     */
    public function index()
    {

        $webCallModel       = D('WebCall');
        $webCallDetailModel = D('WebCallDetail');

        $params = trimSeachNullKey();

        $total      = $webCallDetailModel->where($params)->count();
        $pagesize   = C('PAGE_SIZE');
        $pageObject = new \Org\Util\Page($total, $pagesize);
        $pages      = $pageObject->show();

        $page = I('get.p', 0);

        $webCallDetailRows = $webCallDetailModel->where($params)->order('sort ASC')->relation(true)->page($page)->limit($pagesize)->select();

        $webCallRows = $webCallModel->select();

        $this->assign('webCallDetailRows', $webCallDetailRows);
        $this->assign('pages', $pages);
        $this->assign('params', $params);
        $this->assign('webCallRows', $webCallRows);
        $this->display();
    }

    /**
     * [edit description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T19:55:46+0800
     * @return   [type]                   [description]
     */
    public function edit()
    {
        $webCallModel       = D('WebCall');
        $webCallDetailModel = D('WebCallDetail');

        $id = I('get.id');

        $webCallDetailRow = $webCallDetailModel->find($id);
        if (!$webCallDetailRow) {
            $this->error('调用内容不存在！');
        }

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '修改失败',
            );

            $params = I('post.');

            if (FileUpdateASDataURL::isDataURL($params['image'])) {
                $fileUpdateASDataURL = new FileUpdateASDataURL();
                if (($fileName = $fileUpdateASDataURL->update($params['image'])) === false) {
                    $data['info'] = $fileUpdateASDataURL->getError();
                    $this->ajaxReturn($data);
                }
                // 删除原来的文件记录
                deleteAttachments('WebCallDetail', $id);

                // 记录上传文件
                addAttachments('WebCallDetail', $fileName, $id);

                $params['image'] = $fileName;
            }

            $addData = array(
                "web_call_id" => $params['web_call_id'],
                "title"       => $params['title'],
                "href"        => $params['href'],
                "image"       => $params['image'],
                "sort"        => $params['sort'],
                'update_time' => NOW_TIME,
            );

            $isVoid = $webCallDetailModel->where("id='{$id}'")->save($addData);

            if ($isVoid === falae) {
                $this->ajaxReturn($data);
            }

            $data['status'] = 1;
            $data['info']   = '修改成功';
            $data['url']    = U('/Admin/AdvertDetail');

            $this->ajaxReturn($data);
        }

        $webCallRows = $webCallModel->select();

        $this->assign('webCallDetailRow', $webCallDetailRow);
        $this->assign('webCallRows', $webCallRows);
        $this->display('add');
    }

    /**
     * [add description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T19:35:24+0800
     */
    public function add()
    {
        $webCallModel = D('WebCall');
        $webCallRows  = $webCallModel->select();

        if (IS_AJAX) {
            $webCallDetailModel = D('WebCallDetail');

            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );

            $params = I('post.');

            if (FileUpdateASDataURL::isDataURL($params['image'])) {
                $fileUpdateASDataURL = new FileUpdateASDataURL();
                if (($fileName = $fileUpdateASDataURL->update($params['image'])) === false) {
                    $data['info'] = $fileUpdateASDataURL->getError();
                    $this->ajaxReturn($data);
                }
                // 记录上传文件
                $addAttachmentId = addAttachments('WebCallDetail', $fileName);

                $params['image'] = $fileName;
            }

            $addData = array(
                "web_call_id" => $params['web_call_id'],
                "title"       => $params['title'],
                "href"        => $params['href'],
                "image"       => $params['image'],
                "sort"        => $params['sort'],
                'add_time'    => NOW_TIME,
                'update_time' => NOW_TIME,
            );

            $isVoid = $webCallDetailModel->data($addData)->add();

            if ($isVoid === falae) {
                $this->ajaxReturn($data);
            }

            // 更新上传文件的外部关联号
            if (isset($addAttachmentId) && $addAttachmentId) {
                updateAttachments($addAttachmentId, $isVoid);
            }

            $data['status'] = 1;
            $data['info']   = '添加成功';
            $data['url']    = U('/Admin/AdvertDetail');

            $this->ajaxReturn($data);
        }
        $this->assign('webCallRows', $webCallRows);
        $this->display();
    }

    /**
     * [sort description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-01T19:35:40+0800
     * @return   [type]                   [description]
     */
    public function sort()
    {
        $data               = array('status' => 0, 'info' => '修改失败');
        $value              = I('post.name');
        $id                 = I('get.id');
        $webCallDetailModel = D('WebCallDetail');

        $isViod = $webCallDetailModel->where(array('id' => $id))->save(array(
            'sort' => $value,
        ));
        if ($isViod === false) {
            $this->ajaxReturn($data);
        }
        $data['status'] = 1;
        $data['info']   = '修改成功';

        addAdminLog("修改调用内容排序：[{$id}] 成功！");

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
        $data               = array('status' => 0, 'info' => '删除失败');
        $webCallDetailModel = D('WebCallDetail');
        $id                 = I('id');

        if (!is_array($id)) {
            $id = array($id);
        }

        $isViod = $webCallDetailModel->where(array('id' => array('in', $id)))->delete();

        if ($isViod === false) {
            $this->ajaxReturn($data);
        }
        //删除上传的文件
        deleteAttachments('WebCallDetail', $id);

        addAdminLog("删除调用内容：[" . implode(',', $id) . "] 成功！");

        $data['status'] = 1;
        $data['info']   = '删除成功';
        $this->ajaxReturn($data);
    }
}
