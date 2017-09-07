<?php
return array(
    'app_begin'          => array('Behavior\CheckLangBehavior'),
    'app_end'            => array('Home\Behavior\RecordBehavior'),
    'pay_end'            => array('Home\Behavior\PayEndBehavior'),
    // 检查订单的库存库存
    'pay_start'          => array('Home\Behavior\CheckStockForOrderBehavior'),
    //采购单支付行为
    'purchase_pay_start' => array('Home\Behavior\PurchasePayStartBehavior'),
    'purchase_pay_end'   => array('Home\Behavior\PurchasePayEndBehavior'),
    // 积分统计
    'order_finished_end' => array('Home\Behavior\ComputeVipBehavior'),

    // 积分变动日志
    'paypoints_log'      => array('Home\Behavior\UserPaypointsLogBehavior'),
);
