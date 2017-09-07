<?php
namespace Home\Controller;

use Common\Controller\HomeController;


class ArticleController extends HomeController
{

    public function details(){
        $id = I('get.id');
    	$article = D('article');
    	$articleDetails = $article -> where("id = $id") -> find();
    	$this->assign('articleDetails',$articleDetails);
        $this->display();
    }
}
