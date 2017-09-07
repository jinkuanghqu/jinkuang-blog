<?php
namespace Common\Controller;

use Think\Controller;

class HomeController extends Controller
{
    /**
     * [_initialize description]
     * @author jinkuanghqu@gmail.com
     * @dateTime 2016-03-03T10:11:23+0800
     * @return   [type]                   [description]
     */

    protected $_userId = 0;
    protected $_ntkfUserInfo;
    protected $_ntkfParam;
    protected function _initialize()
    {
        // 设置变量调用
        // C(setting());
        
        // if (session('user.user_id') === null) {
        //     session('user.user_id', 0);
        // }

        // $userSession = session('user');
        // if ($userSession['id']) {
        //     $this->_userId = $userSession['id'];
        // }
        // // add by veter
        // $this->_ntkfParam = array(
        //     'siteid' => 'bh_1000',
        //     'settingid' => 'bh_1001_9999',
        //     'uid' => empty($this->_userId) ? '' : $this->_userId,
        //     'uname' => empty($userSession['email']) ? '' : $userSession['email'],
        //     'isvip' => 0,
        //     'userlevel' => empty($userSession['ugrade']) ? 0 : $userSession['ugrade'],
        // );
        // $this->assign('useNtkf', true);
        // $this->assign('ntkfParam', json_encode($this->_ntkfParam));
        // // End;
        // $this->assign('userInfo', $userSession);
        
        layout('Layout/main');
    }

    /**
     * 前端跳转页面
     * @author jinkuanghqu@gmail.com
     * @dateTime 2016-03-26T17:12:01+0800
     * @param    [type]                   $msg     [description]
     * @param    integer                  $type    跳转类型 0 错误 1 正确
     * @param    string                   $jumpUrl 跳转页面
     * @return   [type]                            [description]
     */
    public function jump($msg, $type = 0, $jumpUrl = 'javascript:history.back(-1);')
    {
        layout(false);
        $this->assign('data', [
            'msg'     => $msg,
            'type'    => $type,
            'jumpUrl' => $jumpUrl,
        ]);
        $this->display('Common:jump');
        exit;
    }

    /**
     * 页面不存在！
     * @author jinkuanghqu@gmail.com
     * @dateTime 2016-04-06T15:29:57+0800
     * @return   [type]                   [description]
     */
    public function _empty()
    {
        header("HTTP/1.1 404 Not Found");
        $this->display('Common:404');
    }
}
