<?php
/**
 *Model类
 */

namespace Admin\Model;

use Think\Model;

class AttachmentsModel extends Model
{
    /**
     * 添加
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-02T15:30:38+0800
     */
    public function addAttachments($type, $filename, $referenceNo)
    {
        if (!file_exists($filename)) {
            return false;
        }
        // 文件大小
        $size = filesize($filename);
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);

        return $this->data(array(
            'type'         => $type,
            'reference_no' => $referenceNo,
            'ext'          => $ext,
            'path'         => $filename,
            'size'         => $size,
            'add_time'     => NOW_TIME,
            'update_time'  => NOW_TIME,
        ))->add();
    }
}
