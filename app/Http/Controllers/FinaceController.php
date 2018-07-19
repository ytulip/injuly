<?php

namespace App\Http\Controllers;

use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\Deliver;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\Order;
use App\Model\ProductAttr;
use App\Model\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class FinaceController extends Controller
{
    public function anyAlipayNotify()
    {
        Logger::info(Request::getContent());

        //验证有效性
        $config = config('alipay');
        require_once base_path() . '/plugin/alipay/wappay/service/AlipayTradeService.php';

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        if($result) {
            //判断是否处理过
            $cashStream = CashStream::find($arr['out_trade_no']);

            if(!$cashStream || $cashStream->pay_status) {
                return "fail";
            }

            $order = Order::find($cashStream->refer_id);


            if(!$order->needPay()) {
                return "fail";
            }

            //更改订单信息
            $order->pay_status = 1;
            $order->order_status = ($order->deliver_type == Deliver::SELF_GET)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $order->pay_type = CashStream::CASH_PAY_TYPE_ALIPAY;
            $order->pay_time = date('Y-m-d H:i:s');
            $order->save();

            //生成邀请码，如果是复购的话没有邀请码
//            if( $order->buy_type == 1 ) {
//                InvitedCodes::makeRecord($order->id);
//            }

            //生成开发辅导初始记录
            $order = Order::find($order->id);
            $order->paySuccess();
            $cashStream->pay_status = 1;
            $cashStream->save();
            Message::addReport($order->id);



            echo "success";		//请不要修改或删除
        }else {
            //验证失败
            echo "fail";	//请不要修改或删除
        }
    }

    /**
     * 支付宝同步返回
     */
    public function anyAlipayReturn(){
        Logger::info(Request::getContent());

        //验证有效性
        $config = config('alipay');
        require_once base_path() . '/plugin/alipay/wappay/service/AlipayTradeService.php';

        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        if($result) {
            //判断是否处理过
            $cashStream = CashStream::find($arr['out_trade_no']);

            if(!$cashStream || $cashStream->pay_status) {
                return Redirect::to('/order/report-success?order_id=' . $cashStream->refer_id);
            }

            $order = Order::find($cashStream->refer_id);


            if(!$order->needPay()) {
                return Redirect::to('/order/report-success?order_id=' . $cashStream->refer_id);
            }

            //更改订单信息
            $order->pay_status = 1;
            $order->order_status = ($order->deliver_type == Deliver::SELF_GET)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $order->pay_type = CashStream::CASH_PAY_TYPE_ALIPAY;
            $order->pay_time = date('Y-m-d H:i:s');
            $order->save();

            //生成邀请码
            //生成邀请码，如果是复购的话没有邀请码
//            if( $order->buy_type == 1 ) {
//                InvitedCodes::makeRecord($order->id);
//            }

            //生成开发辅导初始记录
            $order = Order::find($order->id);
            $order->paySuccess();
            $cashStream->pay_status = 1;
            $cashStream->save();
            Message::addReport($order->id);



            return Redirect::to('/order/report-success?order_id=' . $cashStream->refer_id);		//请不要修改或删除
        }else {
            //验证失败
            echo "fail";	//请不要修改或删除
        }
    }

    /**
     * 微信支付回调
     */
    public function anyWechatNotify()
    {
        Logger::info(Request::getContent(),'wxs');
        $request = Request::getContent();
        $wxObj = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);
        if( $wxObj->result_code != "SUCCESS"){
            return;
        }
        $tradeNo = $wxObj->out_trade_no;

        //判断是否处理过
        $cashStream = CashStream::find($tradeNo);

        if(!$cashStream || $cashStream->pay_status) {
            return "SUCCESS";
        }

        $order = Order::find($cashStream->refer_id);


        if(!$order->needPay()) {
            return "SUCCESS";
        }

        //更改订单信息
        $order->pay_status = 1;
        $order->order_status = ($order->deliver_type == Deliver::SELF_GET)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
        $order->pay_type = CashStream::CASH_PAY_TYPE_WECHAT;
        $order->pay_time = date('Y-m-d H:i:s');
        $order->save();

        //生成邀请码
        //生成邀请码，如果是复购的话没有邀请码
//        if( $order->buy_type == 1 ) {
//            InvitedCodes::makeRecord($order->id);
//        }


        if( $order->buy_type == Order::BUY_TYPE_ACTIVITY )
        {
            //活动付款成功

        } else {
            //生成开发辅导初始记录
            $order = Order::find($order->id);
            $order->paySuccess();
        }

        $cashStream->pay_status = 1;
        $cashStream->save();

        if( $order->buy_type == Order::BUY_TYPE_ACTIVITY )
        {
            //user表的activity_pay
            $user = User::find($order->user_id);
            $user->activity_pay = 1;
            $user->activity_get_good = $user->activity_get_good + 1;
            $user->save();
            ///TODO
        } elseif( $order->buy_type == Order::BUY_TYPE_NEW_REPORT){
            $user = User::find($order->user_id);

//            if( $user->vip_level == User::LEVEL_ACTIVITY )
//            {
//                $user->vip_level = User::LEVEL_MASTER;
//                $user->save();
//            }

            //直接产生开发费用哟
            $newOrder = Order::find($order->refer_order_id);
            $orderUser = User::find($order->immediate_user_id);

            $user->vip_level = User::LEVEL_MASTER;
            //上级，上上级信息写入,提货数量写入
            $user->parent_id = $orderUser->id;
            $user->indirect_id = $orderUser->parent_id;
            $user->get_good = $order->quantity;
            $user->save();

            //直接开发获利
            $cashStream = new CashStream();
            $cashStream->price = ProductAttr::find(2)->direct_price;
            $cashStream->compare_price = $orderUser->charge;
            $cashStream->user_id = $orderUser->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_DIRECT;
            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->refer_id = $order->id;
            $cashStream->refer_user_id = $user->id;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->save();

            $orderUser->increment('charge',$cashStream->price);


//            $user->increment('get_good',12);
        } elseif( $order->buy_type == Order::BUY_TYPE_REREPORT) {
            $user = User::find($order->user_id);
            $user->increment('re_get_good',$order->quantity);
        }elseif( $order->buy_type == Order::BUY_TYPE_REPORT){
            $user = User::find($order->user_id);

            //报单不产生提货记录
        }else{
            Message::addReport($order->id);
        }


        return "SUCCESS";

    }


    /**
     * 微信支付回调
     */
    public function anyWechatReturn()
    {
    }

}