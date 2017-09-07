<?php
namespace Home\Behavior;

class IntegralStatisticsBehavior
{
    public function run($orderId)
    {
        D('Order')->find($orderId);

    }
}
