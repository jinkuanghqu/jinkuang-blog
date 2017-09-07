<?php

/**
 * 管理员日志
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-02-24T16:15:39+0800
 * @param    [type]                   $log [description]
 */
function addAdminLog($log)
{
    \Admin\Model\AdminLogModel::addLog($log);
}

/**
 * 文件上传
 * @param $base64Data string 经过base64编码后的内容; $oldFilePath string 旧的文件路径
 * @return false if fail, upload file path if success
 */
/*
function simpleUpload($base64Data, $oldFilePath)
{
$Upload = new \Admin\Controller\SimpleUploadController;

return $Upload->upload($base64Data, $oldFilePath);
}
 */
/**
 * 添加上传文件的记录
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-03-02T15:29:04+0800
 * @param    string                   $type [文件所属类型代码]
 * @param    string                   $filename [文件名称]
 * @param    string                   $referenceNo [文件所属外部ID]
 */
/*
function addAttachments($type, $filename, $referenceNo = 0)
{
return (new \Admin\Model\AttachmentsModel())->addAttachments($type, $filename, $referenceNo);
}
 */
/**
 * 更新上传文件的外部关联号
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-03-02T15:43:54+0800
 * @param    [type]                   $addAttachmentId [description]
 * @param    [type]                   $param           [description]
 * @return   [type]                                    [description]
 */
function updateAttachments($addAttachmentId, $referenceNo)
{
    return (new \Admin\Model\AttachmentsModel())->where("id='{$addAttachmentId}'")->save(['reference_no' => $referenceNo]);
}

/**
 * 删除文件并且文件记录
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-03-02T15:48:26+0800
 * @param    [type]                   $type        [description]
 * @param    [type]                   $referenceNo [description]
 * @return   [type]                                [description]
 */
function deleteAttachments($type, $referenceNo)
{
    if (is_array($referenceNo) && $referenceNo) {
        foreach ($referenceNo as $value) {
            deleteAttachments($type, $value);
        }
    } else {
        $attachmentsModel = new \Admin\Model\AttachmentsModel();
        $attachmentsRows  = $attachmentsModel->where([
            'reference_no' => $referenceNo,
            'type'         => $type,
        ])->select();

        // 循环删除图片
        foreach ($attachmentsRows as $value) {
            if (file_exists($value['path'])) {
                unlink($value['path']);
            }
        }

        return $attachmentsModel->where([
            'reference_no' => $referenceNo,
            'type'         => $type,
        ])->delete();
    }

}

/**
 * [uploadExcel description]
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-05-03T15:40:13+0800
 * @return   [type]                   [description]
 */
function uploadExcel($name = "excel")
{
    $upload           = new \Think\Upload(); // 实例化上传类
    $upload->maxSize  = 3145728; // 设置附件上传大小
    $upload->exts     = array('xls', 'xlsx'); // 设置附件上传类型
    $upload->rootPath = './Public/'; // 设置附件上传根目录
    $upload->savePath = 'tempUploadExcel/'; // 设置附件上传（子）目录
    // 上传文件
    $info = $upload->uploadOne($_FILES[$name]);
    if (!$info) {
        return [
            'status'  => 0,
            'message' => $upload->getError(),
        ];
    }

    $filename = './Public/' . $info['savepath'] . $info['savename'];

    $data = importExcel($filename);
    return $data;
}

/**
 * 导入excel
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-05-03T15:38:53+0800
 * @param    [type]                   $file [description]
 * @return   [type]                         [description]
 */
function importExcel($file)
{
    if (!file_exists($file)) {
        return array("status" => 0, 'message' => 'file not found!');
    }
    Vendor("PHPExcel.PHPExcel.IOFactory");
    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    try {
        $PHPReader = $objReader->load($file);
    } catch (Exception $e) {}
    if (!isset($PHPReader)) {
        return array("status" => 0, 'message' => 'read error!');
    }

    $objWorksheet = $PHPReader->getSheet(0);
    // $i             = 0;
    // foreach ($allWorksheets as $objWorksheet) {
    $sheetname      = $objWorksheet->getTitle();
    $allRow         = $objWorksheet->getHighestRow(); //how many rows
    $highestColumn  = $objWorksheet->getHighestColumn(); //how many columns
    $allColumn      = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $array["Title"] = $sheetname;
    $array["Cols"]  = $allColumn;
    $array["Rows"]  = $allRow;
    $arr            = array();
    $isMergeCell    = array();
    foreach ($objWorksheet->getMergeCells() as $cells) {
        //merge cells
        foreach (PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
            $isMergeCell[$cellReference] = true;
        }
    }
    for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
        $row = array();
        for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn++) {
            $cell    = $objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
            $afCol   = PHPExcel_Cell::stringFromColumnIndex($currentColumn + 1);
            $bfCol   = PHPExcel_Cell::stringFromColumnIndex($currentColumn - 1);
            $col     = PHPExcel_Cell::stringFromColumnIndex($currentColumn);
            $address = $col . $currentRow;
            $value   = $objWorksheet->getCell($address)->getValue();
            if (substr($value, 0, 1) == '=') {
                return array("status" => 0, 'message' => 'can not use the formula!');
                exit;
            }
            if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_NUMERIC) {
                $cellstyleformat = $cell->getStyle($cell->getCoordinate())->getNumberFormat();
                $formatcode      = $cellstyleformat->getFormatCode();
                if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
                    $value = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($value));
                } else {
                    $value = PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                }
            }
            if ($isMergeCell[$col . $currentRow] && $isMergeCell[$afCol . $currentRow] && !empty($value)) {
                $temp = $value;
            } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$col . ($currentRow - 1)] && empty($value)) {
                $value = $arr[$currentRow - 1][$currentColumn];
            } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$bfCol . $currentRow] && empty($value)) {
                $value = $temp;
            }
            $row[$currentColumn] = $value;
        }
        $arr[$currentRow] = $row;
    }
    $array["Content"] = $arr;
    // $i++;
    // }
    unset($objWorksheet);
    unset($PHPReader);
    unset($PHPExcel);
    unlink($file);
    return array("status" => 1, "data" => $array);
}

/**
 * [exportExcel description]
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-05-05T10:57:06+0800
 * @param    [type]                   $expTitle     [description]
 * @param    [type]                   $expCellName  [description]
 * @param    [type]                   $expTableData [description]
 * @return   [type]                                 [description]
 */
function exportExcel($expTitle, $expTableData, $fileName)
{
    $cellNum = count($expTitle);
    vendor("PHPExcel.PHPExcel");
    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

    $objPHPExcel = new PHPExcel();

    for ($i = 0; $i < $cellNum; $i++) {
        $columnIndex = PHPExcel_Cell::stringFromColumnIndex($i);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnIndex . '1', $expTitle[$i]);
    }

    $i = 2;
    foreach ($expTableData as $data) {
        $j = 0;
        foreach ($data as $cellValue) {
            $columnIndex = PHPExcel_Cell::stringFromColumnIndex($j);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnIndex . $i, $cellValue);
            $j++;
        }
        $i++;
    }

    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $fileName . '.xlsx"');
    header("Content-Disposition:attachment;filename=$fileName.xlsx"); //attachment新窗口打印inline本窗口打印
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}

/**
 * [showjsmessage description]
 * @authorjinkkuanghqu@gmail.com
 * @dateTime 2016-05-04T10:18:46+0800
 * @param    [type]                   $message [description]
 * @return   [type]                            [description]
 */
function showjs($message)
{
    echo '<script type="text/javascript">' . $message . '</script>';
    ob_flush();
    flush();
}
