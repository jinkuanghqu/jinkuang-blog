<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserViewModel extends RelationModel
{

    public function dateCategory($userId, $type = 0)
    {
        $timeBegin = strtotime(date('Y-m-d'));
        for ($sevenDay = 7; $sevenDay > 0; $sevenDay--) {
            $dateNow = $this->getOneDayRow($userId, $timeBegin, $type);
            if ($dateNow) {
                $result[$timeBegin] = $dateNow;
            }
            $timeBegin -= 86400;
        }
        $early = D('user_view')
            ->field('user_view.id,user_view.add_time,goods.name,goods.price,goods.image')
            ->join("LEFT JOIN goods ON user_view.item_id = goods.id")
            ->where("user_view.user_id = {$userId}")
            ->where("user_view.type = {$type}")
            ->where("user_view.add_time < {$timeBegin}")
            ->order('user_view.add_time DESC')
            ->limit(8)
            ->select();
        if ($early) {
            $result['early'] = $early;
        }
        return $result;
    }

    private function getOneDayRow($userId, $timeBegin, $type){
        return D('user_view')
            ->field('user_view.id,user_view.add_time,goods.name,goods.price,goods.image')
            ->join("LEFT JOIN goods ON user_view.item_id = goods.id")
            ->where("user_view.user_id = {$userId}")
            ->where("user_view.type = {$type}")
            ->where(array('user_view.add_time'=>array('BETWEEN',"{$timeBegin},".($timeBegin+86400))))
            ->order('user_view.add_time DESC')
            ->limit(8)
            ->select();
    }

    //删除浏览记录
    public function destory($id)
    {
        try {
            D('user_view')->delete($id);
            return array(
                'status' => 1,
                'info' => "处理成功",
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => "处理失败",
            );
        }
    }

    //批量删除浏览记录
    public function destoryPack($time, $userId, $type)
    {
        try {
            if (is_numeric($time)) {
                D('user_view')
                ->where("user_id = {$userId}")
                ->where("type = {$type}")
                ->where(array("add_time"=>array("BETWEEN","{$time},".($time+86400))))
                ->order("add_time DESC")
                ->limit(8)
                ->delete();
            } else {
                $nowTime = strtotime(date('Y-m-d')) - 86400*7;
                D('user_view')
                ->where("user_id = {$userId}")
                ->where("type = {$type}")
                ->where("add_time < {$nowTime}")
                ->order("add_time DESC")
                ->limit(8)
                ->delete();
            }
            return array(
                'status' => 1,
                'info' => "处理成功",
                'url' => U("UserCenter/userView"),
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => "处理失败",
            );
        }
    }
}
