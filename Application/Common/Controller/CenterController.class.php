<?php
namespace Common\Controller;

use Think\Controller;

class CenterController extends HomeController
{
    protected $_userId;

    protected $guestViewPages = array(
        'AccountSet/vipClub',
    );

    protected function _initialize()
    {
        // 设置变量调用
        parent::_initialize();
        layout('Layout/center');
        $userSession = session('user');
        if ($userSession['id']) {
            $this->_userId = $userSession['id'];
            $this->assign('userInfo', $userSession);
        } else if (in_array(CONTROLLER_NAME . '/' . ACTION_NAME, $this->guestViewPages)){

        }else {
            $this->redirect('Home/Member/login');
        }

        //生成菜单
        $homeMenuModel = new \Home\Model\HomeMenuModel();
        $topType       = $homeMenuModel->getTopConfig(); //获取菜单值
        $topMenu       = $homeMenuModel->topBar(); //获取菜单列表
        $leftMenu      = $homeMenuModel->getLeftList($topType); //获取左侧菜单列表
        $withoutMenu   = $homeMenuModel->withoutMenu();
        $this->assign('topType', $topType)
            ->assign('topMenu', $topMenu)
            ->assign('leftMenu', $leftMenu)
            ->assign('withoutMenu', $withoutMenu);
    }
}
