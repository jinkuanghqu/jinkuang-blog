<?php

namespace Home\Widget;

use Common\Controller\WidgetController;

class CommonWidget extends WidgetController
{
    private $viewPath = "ViewTemplate";

    /**
     * [tdk description]
     * @author jinkuanghqu@gmail.com
     * @dateTime 2016-03-07T16:47:10+0800
     * @return   [type]                   [description]
     */
    public function tdk($tdk = array())
    {

        if (empty($tdk)) {
            $cond = array('page_path' => substr(__SELF__, 1));

            $pageTdk = M('Seo')->field('title,keywords,description')->cache(true)->where($cond)->find();
            if($pageTdk){
                $tdk = $pageTdk;
            }else{
                $tdk = array(
                    'title' => C('sitename'),
                    'keywords' => C('keywords'),
                    'description' => C('description'),
                );
            }
        }

        $this->assign('tdk', $tdk);
        $viewFile = $this->viewPath . ':' . 'Common_tdk';
        $content  = $this->fetch($viewFile);

        echo $content;
    }
}
