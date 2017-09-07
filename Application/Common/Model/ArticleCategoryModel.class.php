<?php

namespace Common\Model;

use Think\Model\RelationModel;

class ArticleCategoryModel extends RelationModel
{
    //文章分类
    protected $_link = array(
        'article_category' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'article_category',
            'mapping_name'  => 'article_category',
        ),
    );

    public function getCategory($pid)
    {
        
        return $this->field("id,pid,name")->where("pid=$pid and is_hide=0 and is_system=1")->order("sort asc")->select();
        
    }

}
