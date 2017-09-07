<?php

namespace Common\Model;

use Think\Model\RelationModel;

class AttachmentsModel extends RelationModel
{
    public function addRow($condition)
    {
        $id = $this->add($condition);
        if ($id) {
            return array(
               'status' => 1,
               'info' => '新增成功',
            );
        } else {
            return array(
                'status' => 0,
                'info' => '新增失败',
            );
        }
    }
}
