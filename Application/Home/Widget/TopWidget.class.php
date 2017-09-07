<?php

namespace Home\Widget;

use Common\Controller\WidgetController;

class TopWidget extends WidgetController
{
    private $viewPath = 'ViewTemplate';

    public function topMenu(){
        $viewFile     = $this->viewPath . ':' . 'topMenu';
        $articleCate = D('article_category');
        $categoryData = $articleCate -> field("id,pid,name")->where("pid=11 and is_hide=0 and is_system=1")->order("sort asc")->select();
        $this->assign('topMenu',$categoryData);
        $content = $this->fetch($viewFile);
        echo $content;
    }
}
