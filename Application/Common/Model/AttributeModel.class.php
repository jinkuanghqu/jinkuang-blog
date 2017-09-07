<?php

/**
 * 商品属性模型
 *
 * @author: veter
 *
*/

namespace Common\Model;

use Think\Model\RelationModel;

class AttributeModel extends RelationModel
{

     protected $_link = array(
        // 该属性所属的分类
        'Category' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Category',
            'foreign_key'   => 'cate_id',
            'mapping_name'  => 'category',
        ),

     );

}
