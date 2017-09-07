<?php

namespace Home\Widget;

use Common\Controller\WidgetController;

class ArticleWidget extends WidgetController
{
    private $viewPath = 'ViewTemplate';

    /**
     * 底部系统文章
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-07T16:12:56+0800
     * @return   [type]                   [description]
     */
    public function footerSystemAricle()
    {

        $viewFile     = $this->viewPath . ':' . 'Article_footerSystemAricle';
        $article_cate = D('ArticleCategory')->getCategory(1); //system文章类下
        if (count($article_cate) > 0) {

            $cate_name = array();
            $element   = "";
            foreach ($article_cate as $key => $c_val) {
                $articles = $li_element = "";
                $articles = D('Article')->getArticle("c_id=" . $c_val['id'], 6);

                foreach ($articles as $value) {

                    $link = "";
                    if (str_replace(" ", "", $value['link'])) {
                        $link = $value['link'];
                    } else {

                        $link = "/Article/index/id/{$value['id']}";
                    }

                    $li_element .= "<li><a href='$link' target='_blank'>{$value['title']}</a></li>";
                }

                $element .= "<div class='item'>
                                <p class='dt'>{$c_val['name']}</p>
                                <ul>
                                {$li_element}
                                </ul>
                            </div>";
            }

        }

        $this->assign("element", $element);

        $content = $this->fetch($viewFile);
        echo $content;
    }
}
