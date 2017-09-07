<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserAddressModel extends RelationModel
{
    public function getAddressList($condition='1=1',$fetchAll=true,$order_by='id')
    {
        $data = M('user_address')
            ->where($condition)
            ->order($order_by);
        if ($fetchAll) {
            return $data->select();
        } else {
            return $data->find();
        }
    }

    //删除
    public function destroy($id)
    {
        try {
            $this->delete($id);
            return array(
                'status' => 1,
                'info' => "处理成功",
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => "处理失败",
            );
        }
    }

    //设置默认值
    public function setDefault($id ,$user_id)
    {
        $this->where(array('user_id'=>$user_id))
            ->save(array('is_default'=>0));
        if ($this
            ->where(array('id'=>$id))
            ->save(array('is_default'=>1))) {
            return array(
                'status' => 1,
                'info' => "处理成功",
            );
        } else {
            return array(
                'status' => 0,
                'info' => "处理失败",
            );
        }
    }

    //更新或添加
    public function add($map, $id = null)
    {
        if ($id) {
            $result = M('user_address')
                ->where("id = {$id}");
            $type = 'save';
        } else {
            $result = M('user_address');
            $type = 'add';
        }
        try {
            $result->{$type}($map);
            return array(
                'status' => 1,
                'info' => "处理成功",
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => "处理失败",
            );
        }
    }

    // 获取用户详细地址 用于订单
    public function getByUser($userId, $limit = 3, $anotherCond = true)
    {
        /*user_address.id,user_address.user_id,user_address.consignee,user_address.user_address.city,
                        user_address.district,user_address.address,user_address.zipcode,
                        user_address.phone_tel,user_address.phone_mob,user_address.email,
                        user_address.is_default,*/
        $field_list = 'user_address.*,
                        region.region_name as country';

        $condition = 'user_address.user_id=' . $userId . ' AND ' . $anotherCond;

        /*$sql = 'SELECT '
            . $field_list
            . ' FROM (user_address'
            . ' INNER JOIN region region1 ON user_address.country_id=region1.id)'
           // . ' INNER JOIN region region2 ON user_address.state_id=region2.id)'
            . ' WHERE ' . $condition
            . ' ORDER BY user_address.is_default DESC'
            . ' LIMIT ' . $limit;*/
        return $this->field($field_list)
                    ->where($condition)
                    ->join('region ON user_address.country_id=region.id')
                    ->order('user_address.is_default DESC, id DESC')
                    ->limit($limit)
                    ->select();

        //return $this->query($sql);
    }

    public function getAddressById($addrId)
    {
        $field_list = 'consignee,consignee_idcard,country_id,state,city,district,address,
                       zipcode,phone_tel,phone_mob,email';

        return $this->field($field_list)->where('id=' . $addrId)->find();
    }
}
