<?php
namespace Common\Controller;

use Think\Controller;
use Think\Log;

class LiteController extends Controller
{
    /**
     * [_initialize description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-03T10:11:23+0800
     * @return   [type]                   [description]
     */
    protected function _initialize()
    {
        if (!IS_CLI) {
            E('Lite 模块只能命令行执行！');
            Log::record('Lite 模块只能命令行执行！', Log::ERR);
        }
        // 设置变量调用
        C(setting());
    }
}
