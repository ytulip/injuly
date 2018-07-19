<?php

namespace App\Http\Controllers;


use App\Model\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class OrderController extends Controller
{
    public $currentOrder;

    public function getReportSuccess()
    {
        $this->orderOwnerCheck();
        if( !$this->currentOrder->pay_status )
        {
            dd('状态异常');
        }
        return view('report_success')->with('order',$this->currentOrder)->with('invited_code',$this->currentOrder->getInvitedCode());
    }

    private function orderOwnerCheck()
    {
        $orderId = Request::input('order_id');
        $order = Order::where(['user_id'=>Auth::id(),'id'=>$orderId])->first();
        if( !$order ) {
            dd('购买不存在，或者无权访问');
        }

        $this->currentOrder = $order;

    }

}