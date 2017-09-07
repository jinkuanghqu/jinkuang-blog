<?php

namespace Common\Model;

use Think\Model\RelationModel;

class MemberViewModel extends RelationModel
{
	//会员浏览记录
    protected $_link = array(
	
        'member_view' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_view',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_view',
        ),
    );			

}
