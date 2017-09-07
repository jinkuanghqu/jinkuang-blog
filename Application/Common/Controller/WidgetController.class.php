<?php
namespace Common\Controller;

use Think\Controller;

class WidgetController extends Controller
{
    /**
     * [_initialize description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-03T10:11:23+0800
     * @return   [type]                   [description]
     */
    protected function _initialize()
    {
        // 设置变量调用
        C(setting());

        if (session('user.user_id') === null) {
            session('user.user_id', 0);
        }

        layout(false);
    }

}
