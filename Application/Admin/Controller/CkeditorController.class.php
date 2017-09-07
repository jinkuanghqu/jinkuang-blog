<?php

/**
 * 富文本编辑器图片上传控制器
 *
 * @author veter
 */

namespace Admin\Controller;

use Common\Controller\AdminController;

class CkeditorController extends AdminController
{
    protected $model;

    function __construct()
    {
        parent::__construct();
        layout(false);
    }
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {

    }

    public function upload()
    {
        file_put_contents('ckeditor-upload.txt',json_encode($_FILES));
        $extensions = array("jpg","bmp","gif","png");
        $uploadFilename = $_FILES['upload']['name'];
        $extension = pathInfo($uploadFilename,PATHINFO_EXTENSION);
        if (in_array($extension,$extensions)) {  
            $uploadPath = str_replace("\\",'/',realpath("Public")."/uploads/");

            $date = date('Ymd') . '/';
            $uploadPath .= $date;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath);
            }

            $uuid = str_replace('.','',uniqid("",TRUE)).".".$extension;
            $desname = $uploadPath.$uuid;
            
            $previewname = '/Public/uploads/'. $date . '/' . $uuid;

            $tag = move_uploaded_file($_FILES['upload']['tmp_name'],$desname);
            file_put_contents('ckeditor-upload.txt', $desname, FILE_APPEND);

            $callback = $_REQUEST["CKEditorFuncNum"];  
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".$previewname."','');</script>";  
        } else {  
            echo "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.jpg/.gif/.bmp/.png文件）</font>";  
        }
    }
}
