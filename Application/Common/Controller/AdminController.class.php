<?php
namespace Common\Controller;

use Think\Controller;

class AdminController extends Controller
{
    /**
     * [_initialize description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-01-29T11:12:30+0800
     * @return   [type]                   [description]
     */
    protected function _initialize()
    {
        // 设置变量调用
        C(setting());

        // 登录用户信息
        $adminRow = session('admin');
        $this->assign('adminRow', $adminRow);

        // 面包屑
        $breadcrumbRows = D('AuthRule')->getBreadcrumb();
        $this->assign('breadcrumbRows', $breadcrumbRows);

        layout('Layout/iframe');
    }

}
