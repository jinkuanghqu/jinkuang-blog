<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserPaypointsLogModel extends RelationModel
{
    const PAYPOINTS_FROM_GOODS_COMMENT = 1, // 商品评论
          PAYPOINTS_FROM_ORDER_FINISH  = 2; // 订单完成

    //获取用户id的积分情况 积分规则交易产生的积分次年年底失效
    public function getPoint($userId)
    {
        //获取当前用户所有积分
        $userInfo = D('user')->where("id = {$userId}")->find();

        $pointAll = $userInfo['paypoints'];
        //获取去年1月1号之前积分使用情况
        $time = strtotime((date('Y')-1).'-01-01');
        $data = D('user_paypoints_log')
                ->field(array('SUM(amount)'=>'amount','type'))
                ->where("user_id = {$userId} AND add_time < {$time}")
                ->group('type')
                ->select();
        $lost = 0;
        foreach ($data as $key => $value) {
            if ($value['type'] == 1) {
                $lost += $value['amount'];
            }else {
                $lost -= $value['amount'];
            }
        }

        return array(
            'all' => $pointAll,//所有积分
            'have' => $pointAll - $lost,//可使用积分
            'lost' => $lost,//过期积分
        );
    }

    //积分变动列表
    public function pointChangeList($userId)
    {
        $count = D('user_paypoints_log')->where("user_id = {$userId}")->count();

        $perPage = 4;
        $Page = new \Org\Util\Page($count, $perPage);
        $_GET['p'] = I('get.p') ? I('get.p') : 1;
        $fieldList = 'user_paypoints_log.*';
        $list = $this->field($fieldList)
                     ->where("user_paypoints_log.user_id = {$userId}")
                     ->page($_GET['p'].','.$perPage)
                     ->select();

        $modelOrderGoods = M('OrderGoods');
        $modelOrder      = M('Order');
        foreach ($list as &$v) {
            $fromJson = json_decode($v['from_json']);
            if ($fromJson->from_to == self::PAYPOINTS_FROM_GOODS_COMMENT) {
                $goodsInfo = $modelOrderGoods->field('`order`.order_sn,order_goods.goods_name,order_goods.goods_image')
                                             ->join('INNER JOIN `order` ON `order`.id=order_goods.order_id')
                                             ->where("order_goods.order_id={$fromJson->order_id} AND order_goods.goods_id={$fromJson->goods_id}")
                                             ->find();
            } else if ($fromJson->from_to == self::PAYPOINTS_FROM_ORDER_FINISH) {
                $goodsInfo = $modelOrder->field('`order`.order_sn,order_goods.goods_name,order_goods.goods_image')
                                        ->join('INNER JOIN order_goods ON `order`.id=order_goods.order_id')
                                        ->where("`order`.id={$fromJson->order_id}")
                                        ->find();

            }
            $v = array_merge($v, $goodsInfo);
        }

        $show = $Page->show();

        return array('count'=>$count, 'pages' => $show, 'list' => $list);
    }
}
