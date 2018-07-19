<?php

namespace App\Model;

use App\Log\Facades\Logger;
use App\Util\Kit;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    const MESSAGE_TYPE_REPORT = 1;
    const MESSAGE_TYPE_WITHDRAW = 2;
    const MESSAGE_TYPE_SIGN_COMMENT = 11;
    const MESSAGE_TYPE_SIGN_ALERT = 12;

    public $table = 'message';

    /**
     * 标题栏消息概览
     */
    static public function messageSummary()
    {
        $res = Message::where('msg_type',0)->orderBy('id','desc')->limit(3)->get();
        if(!$res)
        {
            $res = [];
        }

        return $res;
    }

    /**
     * 增加购买信息
     * @param $orderId
     */
    static public function addReport($orderId)
    {

        $order = Order::find($orderId);
        $user = User::find($order->user_id);
        $productAttr = ProductAttr::find($order->product_attr_id);

        $message = new Message();
        $message->title = '购买提醒';
        $message->content = $user->real_name . "(".Kit::phoneHide($user->phone).")" . "邀请{$productAttr->attr_des},购买{$productAttr->quantity}盒";
        $message->save();
    }


    
    static public function addSignComment($signId)
    {
        $signRecord = SignRecord::find($signId);

        $message = new Message();
        $message->title = '导师留言';
        $message->content = '您的健康打卡记录获得了导师评价，快来看看吧！';
        $message->msg_type = Message::MESSAGE_TYPE_SIGN_COMMENT;
        $message->refer_id = $signId;
        $message->to_uid = $signRecord->user_id;
        $message->save();
    }

    public static function addSignAlert($signId)
    {
        $signRecord = SignRecord::find($signId);

        $message = new Message();
        $message->title = '打卡提醒';
        $message->content = '您已超过3天没有进行健康打卡了哦，现在开始打卡吧。';
        $message->msg_type = Message::MESSAGE_TYPE_SIGN_ALERT;
        $message->refer_id = $signId;
        $message->to_uid = $signRecord->user_id;
        $message->save();
    }
}