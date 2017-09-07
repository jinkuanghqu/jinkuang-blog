<?php
namespace Home\TagLib;

use Think\Template\TagLib;

class Web extends TagLib
{
    protected $tags = array(
        'goods' => array('attr' => 'storeCode,brandCode,categoryCode,limit,type,order'),
    );

    /**
     * 前端产品调用
     * <web:goods storeCode="E0400" limit="5" type="1" order="sale DESC" >
     * {{$goodsRow['name']}}
     * </web:goods>
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-03T17:11:34+0800
     * @param    [type]                   $tag     [description]
     * @param    [type]                   $content [description]
     * @return   [array] 参数内容['id']['spu']['name']['model']['image']['store_code']['store_name']['brand_code']['brand_name']['category_code']['category_name']['second_category_code']['second_category_name']['third_category_code']['third_category_name']['keywords']['seo_title']['seo_description']['status']['weight']['unit']['sale']['visit']['favorite']['paypoints_allow']['stock']['price']['attribute']['description']['sort']['add_time']['update_time']
     */
    public function _goods($tag, $content)
    {
        $limit = $tag['limit'];
        $type  = isset($tag['type']) ? intval($tag['type']) : 0;
        $order = isset($tag['order']) && $tag['order'] != '' ? $tag['order'] : 'update_time DESC';

        $condition = '1=1';
        // 分类
        if (isset($tag['categoryCode']) && $tag['categoryCode'] != '') {
            $condition .= "AND category_code='{$tag['categoryCode']}'";
        }
        //品牌
        if (isset($tag['brandCode']) && $tag['brandCode'] != '') {
            $condition .= "AND category_code='{$tag['brandCode']}'";
        }
        //专家
        if (isset($tag['storeCode']) && $tag['storeCode'] != '') {
            $condition .= "AND category_code='{$tag['storeCode']}'";
        }

        $str = '<?php $goodsModel = D("Goods");';
        if ($type > 0) {
            $str .= '$goodsModel->join("goods_sdcategory gsdc ON goods.id=gsdc.goods_id AND gsdc.sdcategory_id = ' . $type . '");';
        }
        $str .= '$goodsRows = $goodsModel->field($field_list)' .
            '->where($condition)' .
            '->order("' . $order . '")' .
            '->limit(' . $limit . ')' .
            '->select();';
        $str .= 'foreach ($goodsRows as $goodsRow):?>';
        $str .= $content;
        $str .= '<?php endforeach; ?>';
        return $str;
    }
}
