<?php

/**
 * 商品推荐分类模型
 *
 * @author: veter
 *
 */

namespace Common\Model;

use Think\Model\RelationModel;

class SdCategoryModel extends RelationModel
{
    protected $_link = array(
        // 该分类下的商品
        'goods_sdcategory'    => array(
            'mapping_type' => self::HAS_MANY,
            'class_name'   => 'GoodsSdcategory',
            'foreign_key'  => 'sdcategory_id',
            'mapping_name' => 'goods_sdcategory',
            'mapping_field'=> 'goods_id',
        ),
    );

    public function getGoodsIdByCategory($sdcategory_id)
    {
        $result =  $this->where(array('id' => $sdcategory_id))
                        ->relation('goods_sdcategory')
                        ->select();
    }

    public function getAll()
    {
        return $this->select();
    }
}
