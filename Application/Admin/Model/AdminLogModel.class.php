<?php

namespace Admin\Model;

use Think\Model;

// 处理权限的逻辑类
class AdminLogModel extends Model
{

    public static function addLog($log)
    {
        $adminRow = Session('admin');
        return (new static())->data(array(
            'admin_id'   => $adminRow['id'],
            'admin_name' => $adminRow['name'],
            'add_time'   => NOW_TIME,
            'ip'         => get_client_ip(),
            'log'        => $log,
        ))->add();
    }
}
