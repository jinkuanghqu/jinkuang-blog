<?php

/**
 * 获取登录用户的ID
 * @author jinkuanghqu@gmail.com
 * @dateTime 2016-03-10T10:26:56+0800
 * @return   [type]                   [description]
 */
function getUserId()
{
    $userId = session('user.id');
    if ($userId == false) {
        return 0;
    }
    return $userId;
}

// 设置页面不缓存
function setNoCache()
{
    header('Cache-Control:no-cache,must-revalidate,no-store');
    header('Pragma:no-cache');
    header('Expires:-1');
}

// 计算时差
function timediff($begin_time, $end_time)
{
    if ($begin_time < $end_time) {
        $starttime = $begin_time;
        $endtime   = $end_time;
    } else {
        $starttime = $end_time;
        $endtime   = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days     = intval($timediff / 86400);
    $remain   = $timediff % 86400;
    $hours    = intval($remain / 3600);
    $remain   = $remain % 3600;
    $mins     = intval($remain / 60);
    $secs     = $remain % 60;
    $res      = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
    return $res;
}

/**
 * 获取上一页
 * @author jinkuanghqu@gmail.com
 * @dateTime 2016-03-17T11:22:36+0800
 * @return   [type]                   [description]
 */
function previousPage()
{
    return (session('url.old') == null) ? '/Index/index' : '/' . session('url.old');
}

// 获取当月开始时间（秒）
function getMonthStartTime($time = '') {
    if (empty($time)) $time = time();
    return strtotime(date('Y-m-01', $time));
}

// 获取当月结算时间（秒）
function getMonthEndTime($time = '') {
    if (empty($time)) $time = time();
    return date('Y-m-d', strtotime(date('Y-m-01', $time) . ' +1 month -1 day'));
}

