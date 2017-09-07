<?php
namespace Home\Controller;

use Common\Controller\HomeController;


class CategoryController extends HomeController
{

    public function cateList(){
        $id = I('get.id');
    	$article = D('article');
    	$articleData = $article -> where("c_id = $id") -> select();
    	$this->assign('articleData',$articleData);
        $this->display();
    }
}
