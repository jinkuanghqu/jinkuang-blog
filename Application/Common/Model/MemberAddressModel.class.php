<?php

namespace Common\Model;

use Think\Model\RelationModel;

class MemberAddressModel extends RelationModel
{

	//会员地址
    protected $_link = array(
	
        'member_address' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'member_address',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_address',
        ),
    );		
}
