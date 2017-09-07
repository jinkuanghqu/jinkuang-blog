<?php
namespace Admin\Model;

use Think\Model;

/**
 *
 */
class SettingModel extends Model
{
    /**
     * 获取分组中文名
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-07T10:49:38+0800
     * @return   [type]                   [description]
     */
    public function getGroupNames()
    {
        return [
            'main'      => '网站设置',
            'display'   => '显示设置',
            'email'     => '通知邮箱设置',
            'sub_email' => '订阅邮箱设置',
        ];
    }

    /**
     * type 属性规定 input 元素的类型。
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-07T11:51:35+0800
     * @return   [type]                   [description]
     */
    public function getInputTypes()
    {
        return [
            'date'           => '日期字段（带有 calendar 控件）',
            'datetime'       => '日期字段（带有 calendar 和 time 控件）',
            'datetime-local' => '日期字段（带有 calendar 和 time 控件）',
            'email'          => 'e-mail 地址的文本字段',
            'file'           => '文件上传',
            'hidden'         => '隐藏输入字段',
            'month'          => '日期字段的月（带有 calendar 控件）',
            'number'         => '带有 spinner 控件的数字字段',
            'password'       => '密码字段',
            'radio'          => '单选按钮',
            'range'          => '带有 slider 控件的数字字段',
            'text'           => '单行输入字段',
            'time'           => '日期字段的时、分、秒（带有 time 控件）',
            'url'            => '用于 URL 的文本字段。',
            'week'           => '日期字段的周（带有 calendar 控件）',
            'textarea'       => '多行文本框',
        ];
    }
}
