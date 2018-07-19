<?php

namespace App\Util;

use App\Model\CashStream;
use App\Model\Order;

class OrderStatical
{
    public function countValidOrder($attrArr = [1,2])
    {
        $query = Order::where('pay_status',1)->whereIn('product_attr_id',$attrArr);
        return $query->count();
    }

    public function countWaitingDeal()
    {
        return Order::whereIn('order_status',[Order::ORDER_STATUS_WAIT_DELIVER,Order::ORDER_STATUS_WAIT_SELF_GET])->count();
    }
}