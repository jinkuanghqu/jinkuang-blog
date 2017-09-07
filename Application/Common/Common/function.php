<?php

/**
 * 获取毫秒
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-02-18T17:57:41+0800
 * @return   [type]                   [description]
 */
function millisecond()
{
    return time() . '000';
}

/**
 * 求二维数组键值的和
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-02-19T16:19:14+0800
 * @param    [type]                   $array [description]
 * @param    [type]                   $key   [description]
 * @return   [type]                          [description]
 */
function arraySumItem($array, $key)
{
    $sum = 0;
    foreach ($array as $value) {
        $sum = $value[$key];
    }
    return $sum;
}

if (!function_exists('array_column')) {
    function array_column($input, $columnKey, $indexKey = null)
    {
        return \Org\Util\ArrayList::array_columns($input, $columnKey, $indexKey);
    }
}

/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
 */
function setting($k = '')
{
    $config = S('config');
    if ($config !== false) {
        if ($k == '') {
            return $config;
        } else {
            return isset($config[$k]) ? $config[$k] : null;
        }
    }

    if ($k == '') {
        $setting = D('setting')->field('key,value')->select();
        foreach ($setting as $k => $v) {
            $config[$v['key']] = $v['value'];
        }
        S('config', $config);
        return $config;
    } else {
        $model  = D('setting');
        $result = $model->field('key,value')->where("key='{$k}'")->find();
        if (empty($result)) {
            return '';
        }
        return $result['value'];
    }
}

/**
 * 去除搜索提交过来的空值
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-02T11:46:03+0800
 * @return   [type]                   [description]
 */
function trimSeachNullKey($params = array())
{
    if (empty($params)) {
        $params = I('get.');
    }

    if (empty($params)) {
        return $params;
    }

    foreach ($params as $key => $value) {
        if ($value == '') {
            unset($params[$key]);
        }
    }

    return $params;

}

/**
 * 商品URL
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-04T14:17:11+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function gUrl($id)
{
    return U('/Goods/index', array('id' => $id));
}

/**
 * 类目URL
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-04T14:18:27+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function cUrl($id)
{
    return U('/Category/index', array('id' => $id));
}
/**
 * 卖家URL
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-04T14:19:01+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function sUrl($id)
{
    return U('/Store/index', array('id' => $id));
}

/**
 * 文章URL
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-04T14:20:05+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function aUrl($id)
{
    return U('/Article/index', array('id' => $id));
}
/**
 * 文章分类 URL
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-04T14:20:34+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function acUrl($id)
{
    return U('/ArticleCategory/index', array('id' => $id));
}

/**
 * 仓库是存在
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-10T16:33:06+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function existsWarehouseById($id)
{
    $isVoid = (new Common\Model\WarehouseModel())->find($id);
    if ($isVoid) {
        return true;
    }
    return false;
}

/**
 * 发送邮件
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-10T16:31:37+0800
 * @param    [type]                   $to      [description]
 * @param    [type]                   $title   [description]
 * @param    [type]                   $content [description]
 * @param    [string]                 $prefix  [MAIL or SUB_MAIL]
 * @return   [type]                            [description]
 */
function sendMail($to, $title, $content, $prefix = 'MAIL')
{
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer; //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host     = C($prefix . '_HOST'); //smtp服务器的名称
    $mail->SMTPAuth = C($prefix . '_SMTPAUTH'); //启用smtp认证
    $mail->Username = C($prefix . '_USERNAME'); //你的邮箱名
    $mail->Password = C($prefix . '_PASSWORD'); //邮箱密码
    $mail->From     = C($prefix . '_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C($prefix . '_FROMNAME'); //发件人姓名
    $mail->AddAddress($to);
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C($prefix . '_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet = C($prefix . '_CHARSET'); //设置邮件编码
    $mail->Subject = $title; //邮件主题
    $mail->Body    = $content; //邮件内容
    // $mail->AltBody = "备用显示"; //邮件正文不支持HTML的备用显示
    try {
        $mail->send();
        return array(
            'status' => 1,
            'info'   => 'send successful',
        );
    } catch (phpmailerException $e) {
        return array(
            'status' => 0,
            'info'   => $e->errorMessage(),
        );
    } catch (Exception $e) {
        return array(
            'status' => 0,
            'info'   => $e->getMessage(),
        );
    }
}

/**
 * 页面图片显示
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-14T15:00:01+0800
 * @param    [type]                   $filename 文件名称
 * @param    [type]                   $size     尺寸
 */
function P($filename, $width = 0, $height = 0)
{
    $width  = (int) $width;
    $height = (int) $height;

    if (substr($filename, 0, 1) == '.') {
        $filename = substr($filename, 1);
    }
    // 长高有一个为0
    if ($width == 0 || $height == 0) {
        return $filename;
    }

    if (substr($filename, 0, 4) != 'http') {
        $filename .= (APP_DEBUG != true) ? "!{$width}!{$height}" : "?width={$width}&height={$height}";
    }

    return $filename;
}

/**
 * [CS description]
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-17T10:50:04+0800
 * @param    [type]                   $length [description]
 * @param    [type]                   $str    [description]
 * @param    string                   $suffix [description]
 */
function CS($length, $str, $suffix = '...')
{
    if (strlen($str) > $length) {
        $str = substr($str, 0, $length) . $suffix;
    }
    return $str;
}

/**
 * 文件上传
 * @param $base64Data string 经过base64编码后的内容; $oldFilePath string 旧的文件路径
 * @return false if fail, upload file path if success
 */
function simpleUpload($base64Data, $oldFilePath)
{
    $Upload = new \Common\Controller\SimpleUploadController;

    return $Upload->upload($base64Data, $oldFilePath);
}

/**
 * 添加上传文件的记录
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-02T15:29:04+0800
 * @param    string                   $type [文件所属类型代码]
 * @param    string                   $filename [文件名称]
 * @param    string                   $referenceNo [文件所属外部ID]
 */
function addAttachments($type, $filename, $referenceNo = 0)
{
    return (new \Admin\Model\AttachmentsModel())->addAttachments($type, $filename, $referenceNo);
}

/**
 * 订单日志记录
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-18T11:44:18+0800
 * @param    String                $remark       备注
 * @param    Int                   $orderId      订单ID
 * @param    Int                   $operatorType 操作人类型
 * @param    Int                   $operatorId   操作人Id
 * @param    Int                   $beforeStatus 操作前状态
 * @param    Int                   $afterStatus  操作后状态
 */
function OLog($remark, $orderId, $operatorType = 0, $operatorId = 0, $beforeStatus = 0, $afterStatus = 0)
{
    $data = array(
        'remark'        => $remark,
        'order_id'      => $orderId,
        'operator_type' => $operatorType,
        'operator_id'   => $operatorId,
        'before_status' => $beforeStatus,
        'after_status'  => $afterStatus,
        'add_time'      => time(),
    );
    D('OrderLog')->add($data);
}

//采购单操作日志
function PLog($remark, $purchaseId, $operatorType = 0, $operatorId = 0, $beforeStatus = 0, $afterStatus = 0)
{
    $data = array(
        'remark'        => $remark,
        'purchase_id'   => $purchaseId,
        'operator_type' => $operatorType,
        'operator_id'   => $operatorId,
        'before_status' => $beforeStatus,
        'after_status'  => $afterStatus,
        'add_time'      => time(),
    );
    D('purchase_log')->add($data);
}

//上传文件命名
function saveNameRule()
{
    //命名逻辑
    return date('Y-m-d') . '.' . time() . rand(1000, 99999);
}

function download($file, $name = '')
{
    $fileName = $name ? $name : pathinfo($file, PATHINFO_FILENAME);
    $filePath = realpath($file);

    $fp = fopen($filePath, 'rb');

    if (!$filePath || !$fp) {
        return array(
            'status' => 0,
            'info'   => 'Files Not Found',
        );
    }

    $fileName         = $fileName . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
    $encoded_filename = urlencode($fileName);
    $encoded_filename = str_replace("+", "%20", $encoded_filename);

    header('HTTP/1.1 200 OK');
    header("Pragma: public");
    header("Expires: 0");
    header("Content-type: application/octet-stream");
    header("Content-Length: " . filesize($filePath));
    header("Accept-Ranges: bytes");
    header("Accept-Length: " . filesize($filePath));

    $ua = $_SERVER["HTTP_USER_AGENT"];
    if (preg_match("/MSIE/", $ua)) {
        header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
    } else if (preg_match("/Firefox/", $ua)) {
        header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
    } else {
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    }

    fpassthru($fp);
    exit;
}

/**
 * nging 的 concat 模块
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-24T15:16:56+0800
 * @param    string                   $value [description]
 */
function NConcat($files, $ext = 'css', $path = 'Public/css')
{
    $extHtml = array(
        'css' => '<link rel="stylesheet" type="text/css" href="{{$filename}}" />',
        'js'  => '<script type="text/javascript" src="{{$filename}}"></script>',
    );

    if (empty($files) || !isset($extHtml[$ext])) {
        return false;
    }

    $path = '/' . $path . '/';

    if (!is_array($files)) {
        $file = $path . $files;
        return str_ireplace('{{$filename}}', $file, $extHtml[$ext]);
    }

    $href = $html = '';
    foreach ($files as $file) {
        if (APP_DEBUG == true) {
            $file = $path . $file;
            $html .= str_ireplace('{{$filename}}', $file, $extHtml[$ext]);
        } else {
            $href .= $file . ',';
        }
    }

    if (APP_DEBUG == true) {
        return $html;
    }
    $href = rtrim($href, ',');

    $file = $path . '??' . $href;

    return str_ireplace('{{$filename}}', $file, $extHtml[$ext]);
}

/**
 * 校验日期格式是否正确
 *
 * @param string $date 日期
 * @param string $formats 需要检验的格式数组
 * @return boolean
 */
function checkDateIsValid($date, $formats = array("Y-m-d", "Y/m/d"))
{
    $unixTime = strtotime($date);
    if (!$unixTime) {
        //strtotime转换不对，日期格式显然不对。
        return false;
    }
    //校验日期的有效性，只要满足其中一个格式就OK
    foreach ($formats as $format) {
        if (date($format, $unixTime) == $date) {
            return true;
        }
    }

    return false;
}
