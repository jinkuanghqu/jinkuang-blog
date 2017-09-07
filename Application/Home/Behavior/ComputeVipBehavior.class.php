<?php
namespace Home\Behavior;

use \Common\Model\OrderModel;

class ComputeVipBehavior
{
    const SECOND_PRICE = 5000;
    const FRIST_PRICE  = 3000;
    const SECOND_TIME  = 31536000;
    const FRIST_TIME   = 259200;

    private $secondTimeAge;
    private $fristTimeAge;

    public function run($orderId)
    {
        $this->secondTimeAge = time() - self::SECOND_TIME;
        $this->fristTimeAge  = time() - self::FRIST_TIME;

        $userId = D('Order')->getFieldById($orderId, 'user_id');

        $userRow = D('User')->find($userId);

        // 用户是vip会员 并且在有效期内
        if ($userRow['level'] == 2 &&
            $userRow['level_time'] >= $this->secondTimeAge
        ) {
            return true;
        }

        // 用户是vip2会员并且不在有效期内 or 用户是vip1  统计最近一年的收益
        if (($userRow['level'] == 2 && $userRow['level_time'] < $this->secondTimeAge) || ($userRow['level'] == 1)) {
            $this->secondLevel($userRow);
            return true;
        }

        // 用户不是vip并且不在有效期内
        if ($userRow['level'] == 0) {
            $this->firstLevel($userRow);
            return true;
        }

    }

    /**
     * 正式vip计算
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-28T15:21:42+0800
     * @param    [type]                   $userRow [description]
     * @return   [type]                            [description]
     */
    public function secondLevel($userRow)
    {
        $this->secondTimeAge  = time() - self::FRIST_TIME;
        $map['finished_time'] = [
            ['gt', $this->secondTimeAge],
            ['lt', time()],
        ];
        $map['status'] = array('in', [
            OrderModel::ORDER_FINISHED,
            OrderModel::ORDER_COMMENTED,
        ]);
        $map['user_id'] = $userRow['id'];

        $amount = D('Order')->where($map)->sum('order_amount');

        if ($amount > self::SECOND_PRICE) {
            D('User')->where([
                'id' => $userRow['id'],
            ])->save([
                'level'      => 2,
                'level_time' => time(),
            ]);
        }

        return intval($amount);
    }

    /**
     * vip2计算
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-28T15:28:14+0800
     * @param    [type]                   $userRow [description]
     * @return   [type]                            [description]
     */
    public function firstLevel($userRow)
    {
        $this->fristTimeAge = time() - self::FRIST_TIME;

        $map['finished_time'] = [
            ['gt', $this->fristTimeAge],
            ['lt', time()],
        ];
        $map['status'] = array('in', [
            OrderModel::ORDER_FINISHED,
            OrderModel::ORDER_COMMENTED,
        ]);
        $map['user_id'] = $userRow['id'];

        $amount = D('Order')->where($map)->sum('order_amount');

        if ($amount > self::FRIST_PRICE) {
            D('User')->where([
                'id' => $userRow['id'],
            ])->save([
                'level'      => 1,
                'level_time' => time(),
            ]);
        }

        return intval($amount);
    }
}
