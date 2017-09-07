<?php

/**
 * 文件上传控制器
 *
 * @author jinkkuanghqu@gmail.com
 */

namespace Admin\Controller;

use Common\Controller\AdminController;
use Org\Util\FileUpdateASDataURL;

class SimpleUploadController extends AdminController
{
    /**
     * 上传文件
     * @author jinkkuanghqu@gmail.com
     * @param  $base64Data string base64编码后的上传文件内容; $oldFilePath string 旧的文件路径
     * @return false if not uploaded; filepath if upload success;
     *
     *
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function upload($base64Data, $oldFilePath)
    {
        $upload_result = array(
            'error' => 1,
            'info'  => '上传失败',
        );

        if (FileUpdateASDataURL::isDataURL($base64Data)) {
            $fileUpdateASDataURL = new FileUpdateASDataURL();
            if (($fileName = $fileUpdateASDataURL->update($base64Data)) === false) {
                $upload_result['info'] = $fileUpdateASDataURL->getError();
                $this->error($upload_result['info']);
            } else {
                $fileDirUtil = new \Org\Util\FileDirUtil();
                if (!empty($oldFilePath) && !$fileDirUtil->unlinkFile($oldFilePath)) {
                    $upload_result['info'] = "文件删除失败:[{$oldFilePath}]";
                    $this->error($upload_result['info']);
                }
            }
            $upload_result['error'] = 0;
            $upload_result['info']  = $fileName;
        } else {
            return false;
        }

        return $upload_result['info'];
    }
}
