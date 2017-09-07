<?php

namespace Home\Widget;

use Common\Controller\WidgetController;

class CategoryWidget extends WidgetController
{
    private $model;

    private $viewPath = "ViewTemplate";

    public function _initialize()
    {
        parent::_initialize();

        $this->model = D('Category');
    }
    /**
     * 分类树
     * @author jinkuanghqu@gmail.com
     * @dateTime 2016-03-07T14:42:18+0800
     * @return   [type]                   [description]
     */
    public function tree()
    {
        $data = array();

        $data = $this->model->getAllVisible();

        $category_list = $this->model->makeTree($data);
        $this->assign('data', $category_list);
        $viewFile = $this->viewPath . ':' . 'Categroy_tree';
        $content  = $this->fetch($viewFile);

        echo $content;
    }

    /**
     * [FunctionName description]
     * @author jinkuanghqu@gmail.com
     * @dateTime 2016-03-07T14:42:37+0800
     * @param    string                   $value [description]
     */
    public function indexSaveBig($categoryIds)
    {
        if ($categoryIds == '') {
            return false;
        }
        //$categoryIds = array(74,75,76);
        $where = array(
            'id'        => array('in', $categoryIds),
            'is_hidden' => 0,
        );

        $categoryRows = $this->model->field('id,code,name,sort,logo,level')
            ->order('sort DESC')->where($where)
            ->select();
        //dump(D('Category')->getLastSql());
        //dump($categoryRows);
        // 1 是 SAVE BIG 推荐类型的ID
        foreach ($categoryRows as $key => $value) {
            $where = array();
            if ($value['level'] = 1) {
                $where['category_code'] = $value['code'];
            } else if ($value['level'] = 2) {
                $where['second_category_code'] = $value['code'];
            } else {
                $where['third_category_code'] = $value['code'];
            }
            $categoryRows[$key]['goods'] = D('Goods')->getBySdCategory(1, 9,
                array(
                    'order' => 'sale DESC',
                    'where' => $where,
                )
            );
            //dump(D('Goods')->getLastSql());
        }

        $this->assign('categoryRows', $categoryRows);
        $content = $this->fetch($this->viewPath . ':' . 'Categroy_indexSaveBig');
        echo $content;
    }

    //获取可用的顶级分类
    public function getUsableCartgory()
    {

        $condition              = array();
        $condition['parent_id'] = array('EQ', 0);
        $condition['is_hidden'] = array('EQ', 0);
        $search_category        = D("Category")->getUsableCartgory($condition);
        $this->assign('search_category', $search_category);
        $content = $this->fetch($this->viewPath . ':' . 'Categroy_Search');
        echo $content;

    }
}
