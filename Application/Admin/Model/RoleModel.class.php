<?php
namespace Admin\Model;

use Think\Model\RelationModel;

class RoleModel extends RelationModel
{
    protected $tableName = 'role';

    protected $_link = array(
        'AuthRule' => array(
            'mapping_type'         => self::MANY_TO_MANY,
            'class_name'           => 'AuthRule',
            'mapping_name'         => 'rules',
            'foreign_key'          => 'role_id',
            'relation_foreign_key' => 'auth_rule_id',
            'relation_table'       => 'role_auth_rule',
        ),
    );
}
