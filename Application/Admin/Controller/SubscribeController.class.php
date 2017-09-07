<?php
namespace Admin\Controller;

use Common\Controller\HomeController;
use Common\Model\SubscribeModel;

class SubscribeController extends AdminController
{
    private $_model;

    protected function _initialize()
    {
        parent::_initialize();
        if (!$this->_model) {
            $this->_model = new SubscribeModel();
        }
    }

    public function index()
    {
        $condition = $this->_model->condition();
        $subscribeData = $this->_model->getSubscribeList($condition);
        //获取激活状态
        $activeStatus = \Common\Model\StatusModel::bool();
        $this->assign('pages', $subscribeData['pages'])
            ->assign('subscribeList', $subscribeData['list'])
            ->assign('activeStatus', $activeStatus);
        $this->display('Subscribe/index');
    }

    public function destroy()
    {
        if (I('get.id')) {
            $condition['id'] = I('get.id');
        } else {
            $condition['id'] = array('IN', I('get.ids'));
        }
        $result = $this->_model->destroy($condition);
        $this->ajaxReturn($result);
    }
}
