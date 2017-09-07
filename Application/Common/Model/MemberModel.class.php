<?php

namespace Common\Model;

use Think\Model\RelationModel;

class MemberModel extends RelationModel
{
	//会员表
    protected $_link = array(
	
        'member_address' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_address',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_address',
        ),
        'member_collect' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_collect',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_collect',
        ),
        'member_paypoints_log' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_paypoints_log',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_paypoints_log',
        ),
        'member_view' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'member_view',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'member_view',
        ),		
		
    );

    /**
    * get user_id from email
    *@author simon
    */
    public function getUserId($type,$condition)
    {
        if($type === 0){
            
            return $this->Field('id')->where($condition)->find();
           
        }
        
        if($type == 1){
            
            return $this->Field('id')->where($condition)->select();
            
        }
        
        if($type == 2){
            
            return $this->Field('id')->select();
            
        }
    }


/*
    public function getUserEmail($condition)
    {
        
        return $this->Field('email')->where($condition)->select();
        
    }*/

}
