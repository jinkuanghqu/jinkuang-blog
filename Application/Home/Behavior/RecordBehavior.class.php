<?php
namespace Home\Behavior;

use \Think\Behavior;

/**
 *
 */
class RecordBehavior extends Behavior
{
    //行为执行入口
    public function run(&$param)
    {
        $old = session('url.now') == null ? '/' : session('url.now');
        if ($old == __INFO__ || CONTROLLER_NAME == 'Member') {
            return;
        }
        session('url.now', __INFO__);
        session('url.old', $old);
    }
}
