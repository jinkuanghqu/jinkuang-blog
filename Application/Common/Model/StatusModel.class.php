<?php

namespace Common\Model;

use Think\Model\RelationModel;;

class StatusModel extends RelationModel
{
    public static function bool()
    {
        return array(
            '1' => '启用',
            '0' => '禁用',
         );
    }
    //RBAC 等级node.level
    public static function nodeLevel()
    {
        return array(
            '1' => '模块',
            '2' => '操作',
        );
    }

    //coupon.use_type 前台英文优惠券使用类型
    public static function couponUseType()
    {
        return array(
            '1' => 'No limit',//无限制
            '2' => 'Shop',//店铺
            '3' => 'Category',//品类
            '4' => 'Goods',//商品
            '5' => 'logistics',//物流
        );
    }

    //coupon.use_type 后台中文优惠券使用类型
    public static function couponUseTypeCn()
    {
        return array(
            '1' => '无限制',
            '2' => '店铺',
            '3' => '品类',
            '4' => '商品',
            '5' => '物流',
        );
    }

    //coupon.send_type 优惠券发放类型
    public static function couponSendType()
    {
        return array(
            '1' => '买家领取',
            '2' => '平台发放',
        );
    }

    //coupon_detail.use_status 优惠券状态
    public static function couponDetailUseStatus()
    {
        return array(
            '0' => '未使用',
            '1' => '已使用',
        );
    }

    //coupon_detail.send_status 优惠券状态
    public static function couponDetailSendStatus()
    {
        return array(
            '0' => '未发送',
            '1' => '已发送',
        );
    }

    //user_paypoints_log.type 积分类型
    public static function userPaypointsLogType()
    {
        return array(
            '1' => '+',
            '2' => '-',
        );
    }

    //goods_comment.grade 评价级别
    public static function GoodsCommentGrade()
    {
        return array(
            '1' => '好评',
            '2' => '中评',
            '3' => '差评',
        );
    }
    //goods_comment.grade 评价级别  前台
    public static function GoodsCommentGradeHome()
    {
        return array(
            '1' => 'positive',
            '2' => 'moderate',
            '3' => 'negative',
        );
    }

    //message.status 站内信状态
    public static function messageStatus()
    {
        return array(
            '0' => 'Unread',
            '1' => 'Read',
        );
    }

    //purchase.status 采购单状态
    public static function purchaseStatus()
    {
        return array(
            '0' => '草稿',
            '1' => '已审核',
            '2' => '已报价',
            '3' => '给付定金',
            '4' => '待生产',
            '5' => '生产中',
            '6' => '已生产',
            '7' => '待发货',
            '8' => '已发货',
            '9' => '已收货',
            '91' => '未通过',
            '92' => '无报价',
            '93' => '已取消',
        );
    }

    //purchase.pause 采购但中止状态
    public static function purchasePause()
    {
        return array(
            '0' => '未中止',
            '1' => '中止申请',
            '2' => '已中止',
        );
    }

    //purchase.inquiry_type 询价类型
    public static function purchaseInquiryType()
    {
        return array(
            '1' => '广泛征集',
            '2' => '其它',
        );
    }

    //purchase.type 类型
    public static function purchaseType()
    {
        return array(
            '1' => '现货',
            '2' => '通关周转',
        );
    }

    //purchase.contact_type 联系类型
    public static function purchaseContactType()
    {
        return array(
            '1' => '公开',
            '2' => '报价后联系',
        );
    }

}
