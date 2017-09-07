<?php

namespace Common\Model;

use Think\Model\RelationModel;

class CreateSnModel extends RelationModel
{
    public function fetchCurrent($tableName)
    {
        $data = D('create_sn')
            ->field('current')
            ->where("table_name = '{$tableName}'")
            ->find();
        return $data['current'];
    }

    //$update?'update':'insert'
    public function update($tableName, $current, $update = true)
    {
        $data['current'] = $current;
        if ($update) {
            $this->where("table_name = '{$tableName}'")
                ->save($data);
        } else {
            $data['table_name'] = $tableName;
            $this->add($data);
        }
    }
}
