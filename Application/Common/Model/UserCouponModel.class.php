<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserCouponModel extends RelationModel
{
    //获取用户所有的coupon列表
    public function getUserCouponList($userId)
    {
        $coupon = D('user_coupon')
            ->field('user_coupon.*,coupon.use_type,coupon.use_condition,coupon.money,coupon.name')
            ->join('LEFT JOIN coupon_detail ON user_coupon.coupon_detail_id = coupon_detail.id')
            ->join('LEFT JOIN coupon on coupon_detail.coupon_id = coupon.id')
            ->where([
                'user_coupon.user_id'  => $userId,
                'coupon_detail.status' => 1,
            ])
            ->where('coupon_detail.status = 1')
            ->order('status,end_time DESC,use_time DESC')
            ->select();
        // echo D('user_coupon')->_sql();
        //对所有的coupon进行分类 all unused used expired
        $arr = $all = $unused = $used = $expired = $toExpired = array();
        foreach ($coupon as $key => $val) {
            switch ($val['use_type']) {
                //颜色显示
                case 1:
                    $val['color'] = 'yellow';
                    break;
                case 2:
                    $val['color'] = 'purple';
                    break;
                case 3:
                    $val['color'] = 'blue';
                    break;
                case 4:
                    $val['color'] = 'green';
                    break;
                case 5:
                    $val['color'] = 'green';
                    break;
                default:
                    $val['color'] = 'green';
                    break;
            }

            $all[] = $val['id'];
            if ($val['status']) {
                $used[]       = $val['id'];
                $val['mark']  = 'about-used';
                $val['color'] = 'gray';
            } else {
                if ($val['end_time'] > time()) {
                    $unused[] = $val['id'];
                    //设置快过期时间 30天
                    if ($val['end_time'] - 2592000 < time()) {
                        $toExpired[] = $val['id'];
                        $val['mark'] = 'about-expired';
                    }
                } else {
                    $expired[]    = $val['id'];
                    $val['mark']  = 'expired';
                    $val['color'] = 'gray';
                }
            }

            $arr[$val['id']] = $val;
        }
        return array(
            'data'      => $arr, //所有优惠券，包括键值对
            'all'       => $all,
            'unused'    => $unused,
            'used'      => $used,
            'expired'   => $expired,
            'toExpired' => $toExpired,
        );

    }
}
