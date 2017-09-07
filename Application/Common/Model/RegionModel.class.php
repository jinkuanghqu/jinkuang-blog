<?php

namespace Common\Model;

use Think\Model\RelationModel;

class RegionModel extends RelationModel
{
    protected $_link = array(
        'parent' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name'   => 'Region',
            'foreign_key'  => 'parent_id',
            'mapping_name' => 'region',
            'parent_key'   => 'parent_id',
        ),
    );

    /**
     * 通过parentId获取区域
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-10T17:14:37+0800
     * @param    string                   $parentId [description]
     * @return   [type]                             [description]
     */
    public function getRegionByPid($parentId = '0')
    {
        return $this->where(['parent_id' => $parentId])->select();
    }

    /**
     * 通过 id 获取所有的父类
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-11T13:56:38+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function getParentById(&$regionList, $id = 0)
    {
        $regionRow = $this->find($id);
        if (!$regionRow) {
            return false;
        }
        $regionRow['child'] = $regionList;
        $regionList         = $regionRow;
        if ($regionRow['parent_id'] != 0) {
            $this->getParentById($regionList, $regionRow['parent_id']);
        }

    }

    public function getRegionlist($condition = '1=1', $fetchAll = true, $order = 'id')
    {
        $data = M('region')
            ->where($condition)
            ->order($order_by);
        if ($fetchAll) {
            return $data->select();
        } else {
            return $data->find();
        }
    }

    //返回用于多级联动地址数组
    public function regionStep()
    {
        $eq = $top = $resultChild = $parentArr = $regionList = array();

        $regionData = M('region')
            ->field('id,region_name,parent_id')
            ->select();
        foreach ($regionData as $key => $value) {
            $parentArr[]      = $value['parent_id'];
            $eq[$value['id']] = $value['region_name'];
            if ($value['parent_id'] == 0) {
                $top[] = $value['id'];
            }
        }
        $parentArr = array_flip(array_flip($parentArr));
        foreach ($regionData as $key => $value) {
            if (in_array($value['parent_id'], $parentArr)) {
                $resultChild[$value['parent_id']][] = $value['id'];
            }
        }
        return array(
            'top'  => $top, //顶级的id集合(国家)
            'eq'   => $eq, //id=>region_name对应
            'data' => $resultChild, //所有有子元素的集合
        );
    }
}
