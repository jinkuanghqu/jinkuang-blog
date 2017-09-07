<?php

namespace Common\Model;

use Think\Model\RelationModel;

class MemberPaypointsLogModel extends RelationModel
{
	//会员积分变动记录
    protected $_link = array(
	
        'member_paypoints_log' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_paypoints_log',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_paypoints_log',
        ),
    );		

}
