<?php
namespace Common\Controller;

class MemberController extends HomeController
{
    /**
     * [_initialize description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-03T10:11:23+0800
     * @return   [type]                   [description]
     */
    protected function _initialize()
    {
        parent::_initialize();
        layout('Layout/member');
    }
}
