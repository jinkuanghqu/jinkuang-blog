<?php

namespace Common\Model;

use Think\Model\RelationModel;

class MemberCollectModel extends RelationModel
{

	//会员收藏
    protected $_link = array(
	
        'member_collect' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_collect',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_collect',
        ),
    );		

}
