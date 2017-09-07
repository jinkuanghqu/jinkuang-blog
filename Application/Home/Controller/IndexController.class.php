<?php
namespace Home\Controller;

use Common\Controller\HomeController;


class IndexController extends HomeController
{

    public function index(){
        
    	$article = D('article');
    	$articleData = $article -> where("isdisplay = 1") -> order("sort asc") -> select();
    	$total_count = count($articleData);
    	$page_num =  C('PAGE_SIZE', null, 20);
    	$Page = new \Org\Util\Page($total_count, $page_num);
    	$page_links = $Page->show();
    	$this->assign('pages',$page_links);
    	$this->assign('articleData',$articleData);
        $this->display();
    }
}
