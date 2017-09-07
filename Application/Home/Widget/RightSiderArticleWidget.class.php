<?php

namespace Home\Widget;

use Common\Controller\WidgetController;

class RightSiderArticleWidget extends WidgetController
{
    private $viewPath = 'ViewTemplate';

    public function newArticle(){
        $viewFile     = $this->viewPath . ':' . 'newArticle';
        $articleObj = D('article');
        $newArticleData = $articleObj -> field("id,title") -> where("isdisplay = 1") -> order("add_time desc") -> limit(20) ->select();
        $this->assign('newArticleData',$newArticleData);
        $content = $this->fetch($viewFile);
        echo $content;
    }
}
