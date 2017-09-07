<?php
namespace Home\Behavior;

use \Think\Behavior;

/**
 *
 */
class SessionBehavior extends Behavior
{
    //行为执行入口
    public function run(&$param)
    {
        return;
        $sessionId = cookie('Home_Session_Id');
        if ($sessionId) {
            session_id($sessionId);
        }
        cookie('Home_Session_Id', session_id());
    }
}
