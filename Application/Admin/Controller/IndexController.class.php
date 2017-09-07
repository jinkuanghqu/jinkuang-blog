<?php
namespace Admin\Controller;

use Common\Controller\AdminController;

class IndexController extends AdminController
{
    /**
     * 后台框架
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T11:23:22+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $menuList = D('Admin')->getMenuList();
        $this->assign('menuList', $menuList);
        layout('Layout/main');
        $this->display();
    }

    /**
     * 后台起始页面
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T11:23:42+0800
     * @return   [type]                   [description]
     */
    public function main()
    {
        $this->display('mainTestView');
    }

    /**
     * 后台起始页面
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T11:23:42+0800
     * @return   [type]                   [description]
     */
    public function mainTestView()
    {
        $this->display();
    }

    /**
     * 删除缓存
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T11:29:13+0800
     * @return   [type]                   [description]
     */
    public function clearCache()
    {
        (new \Org\Util\FileDirUtil())->unlinkDir(CACHE_PATH . 'Home/');
        (new \Org\Util\FileDirUtil())->unlinkDir(TEMP_PATH);
        //Temp

        $this->ajaxReturn(array(
            'status' => 1,
            'info'   => '缓存删除成功！',
        ));

    }

    public function download()
    {
        $result = download(base64_decode(I('get.url')));
        if (!$result['status']) {
            $this->error($result['info']);
        }
    }

}
