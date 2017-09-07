<?php

namespace Common\Model;

use Think\Model;

class TopSuppliersModel extends Model
{

    protected $_link = array();

    protected $_validate = array(
        array('category_code', 'number', '分类代码必须为数字'),
        array('category_name', 'require', '分类名称不能为空'),
        array('category_sort', 'number', '分类排序必须为数字'),
        array('store_name', 'require', '店铺名称不能为空'),
        array('store_id', 'number', '店铺ID必须为数字'),
        array('brand_code', 'require', '品牌代码不能为空'),
        array('brand_name', 'require', '品牌名称不能为空'),
        array('company_name', 'require', '公司名称不能为空'),
        array('mian_products', 'require', '主营产品不能为空'),
        array('introduction', 'require', '公司介绍不能为空'),
        array('store_sort', 'number', '店铺排序必须为数字'),
        array('picture', 'require', '公司照片不能为空')
    );

    public function addOne($data)
    {
        if (!$this->create($data)) {
            return false;
        } else {
            return $this->add();
        }
    }
    public function updateOne($data, $cond)
    {
        if (!$this->create($data, 2)) {
            // 对data数据进行验证
            return $this->getError();
        } else {
            // 验证通过 可以进行其他数据操作
            return $this->where($cond)->save();
        }
    }
    /**
     * 获取推荐供应商列表
     * @author veter
     * @date   2016-05-16T13:52:25+0800
     * @return [type]                   [description]
     */
    public function getSupplierList($condition, $curPage, $pageSize)
    {
        $condition = array_merge($condition, array());
        $rows = $this->where($condition)
                     ->order('category_sort ASC, store_sort ASC')
                     ->page($curPage . ',' . $pageSize)
                     ->select();

        $totalCount = $this->where($condition)->count();

        $Page = new \Org\Util\Page($totalCount, $pageSize);
        //  分页跳转的时候保证查询条件
        $Page->parameter = I('get.');

        $pageLinks = $Page->show();
        return array($rows, $pageLinks);
    }
    /**
     * 获取分类列表（去除重复值）
     * @author veter
     * @date   2016-05-16T13:55:34+0800
     * @return [type]                   [description]
     */
    public function getCategoryList()
    {
        return $this->query('SELECT DISTINCT(category_code),category_name,category_sort FROM top_suppliers WHERE store_id=0 ORDER BY category_sort ASC');
    }
    /**
     * 获取所有推荐供应商（首页调用）
     * @author veter
     * @date   2016-05-17T15:02:24+0800
     * @param  [type]                   $condition [description]
     * @return [type]                              [description]
     */
    public function getAll($condition = 'store_id>0')
    {
        $fieldStr = 'category_code,category_name,store_id,brand_code,brand_name';
        $fieldStr .= 'company_name,main_products,picture,introduction,brand.logo as brand_logo';
        $rows = $this->where($condition)
                     ->join('brand ON top_suppliers.brand_code=brand.code')
                     ->order('category_sort ASC, store_sort ASC')
                     ->select();

        $supplierList = array();
        foreach ($rows as $row) {
            $supplierList[$row['category_code']][] = $row;
        }
        unset($rows);
        return $supplierList;
    }
}
