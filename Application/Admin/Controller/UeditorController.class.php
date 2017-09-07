<?php
namespace Admin\Controller;

use Common\Controller\AdminController;
use \Org\Util\Ueditor;

/**
 * ueditor 处理方法
 */
class UeditorController extends AdminController
{
    /**
     * [index description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-28T17:58:54+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $action = I('get.action');

        $ueditorObject = new Ueditor();

        $result = $ueditorObject->doAction($action);

        $this->ajaxReturn($result);
    }

}
