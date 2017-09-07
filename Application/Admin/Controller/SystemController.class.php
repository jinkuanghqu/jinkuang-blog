<?php
namespace Admin\Controller;

use Common\Controller\AdminController;
use Org\Util\FileUpdateASDataURL;

class SystemController extends AdminController
{
    /**
     * [editSystem description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-07T10:18:15+0800
     * @return   [type]                   [description]
     */
    public function editSystem()
    {
        $settingModel = D('Setting');

        $settingRows = $settingModel->order('sort ASC')->select();

        if (IS_AJAX) {
            $data = array(
                'status' => 0,
                'info'   => '修改失败',
            );
            $params = I('post.');
            foreach ($settingRows as $value) {
                if ($value['input_types'] == 'file') {
                    if (FileUpdateASDataURL::isDataURL($params[$value['key']])) {
                        $fileUpdateASDataURL = new FileUpdateASDataURL();
                        //上传
                        if (($fileName = $fileUpdateASDataURL->update($params[$value['key']])) === false) {
                            $error = $fileUpdateASDataURL->getError();
                        }
                        $params[$value['key']] = $fileName;

                        // 删除原来的文件记录
                        deleteAttachments('Setting', $value['key']);

                        // 记录上传文件
                        addAttachments('Setting', $fileName, $value['key']);
                    }
                }

                $settingModel->where("`key`='{$value['key']}'")->setField('value', $params[$value['key']]);
            }

            $data['status'] = 1;
            $data['info']   = '修改成功';
            $this->ajaxReturn($data);
        }

        $settingRowsForArrange = array();
        foreach ($settingRows as $value) {
            $settingRowsForArrange[$value['group']][$value['key']] = $value;
        }
        $this->assign('groups', $settingModel->getGroupNames());
        $this->assign('settingRowsForArrange', $settingRowsForArrange);
        $this->display();
    }

    /**
     * 添加
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-26T14:46:06+0800
     */
    public function add()
    {
        $settingModel = D('Setting');
        if (IS_AJAX) {

            $data = array(
                'status' => 0,
                'info'   => '添加失败',
            );
            $params = I('post.');

            $settingRow = $settingModel->find($params['key']);
            if (!empty($settingRow)) {
                $data['info'] = "变量名称已存在！";
                $this->ajaxReturn($data);
            }

            $isViod = $settingModel->data(array(
                'key'         => $params['key'],
                'value'       => $params['value'],
                'sort'        => isset($params['sort']) ? $params['sort'] : 255,
                'description' => $params['description'],
                'input_types' => $params['input_types'],
                'group'       => $params['group'],
            ))->add();

            if ($isViod === false) {
                $this->ajaxReturn($data);
            }

            $data['status'] = 1;
            $data['info']   = '添加成功';
            $data['url']    = U('/Admin/System/editSystem');
            $this->ajaxReturn($data);
        }
        $this->assign('groups', $settingModel->getGroupNames());
        $this->assign('inputTypes', $settingModel->getInputTypes());
        $this->display();
    }

    /**
     * 验证
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-29T16:49:39+0800
     * @return   [type]                   [description]
     */
    public function verifyKey()
    {
        $data = array('status' => 0);

        $settingModel = D('Setting');
        $name         = I('post.name');
        $key          = I('get.key', false);

        if ($key == $name) {
            $this->ajaxReturn($data);
        } else {
            $settingRow = $settingModel->find($name);
        }

        if (!empty($settingRow)) {
            $data['status'] = 1;
            $data['msg']    = '菜单链接已存在！';
        }

        $this->ajaxReturn($data);

    }
}
