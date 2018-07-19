<?php

namespace App\Console\Commands;

use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\Message;
use App\Model\Order;
use App\Model\SignRecord;
use App\Model\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class SignAlert extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'alert:sign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '超过三天未打卡的提醒';


    public function fire()
    {
//        $list = Order::where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('sign_status',1)->leftJoin('users','users.id','=','orders.user_id')->where('health_status',1)->get();

//        foreach( $list  as $Key=>$item )
//        {
//            Logger::info($item->phone . '-' . $item->user_id . '准备发送打卡提醒','sign_alert');
//
//        }

        $orders = Order::where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('pay_status',1)->get();

        Logger::info('一共有' .count($orders). '活动订单','sign_alert');
        foreach( $orders as $key=>$item)
        {
            Logger::info('正在处理' .$item->user_id. '活动订单','sign_alert');
            $count = SignRecord::where('user_id',$item->user_id)->count();
            if(!$count)
            {
                Logger::info('该用户' .$item->user_id. '还未开始打卡，无法发送提醒','sign_alert');
                continue;
            }

            $signRecord = SignRecord::where('user_id',$item->user_id)->where('sign_status',1)->orderBy('id','desc')->first();
            Logger::info('用户' .$item->user_id . '最后一次打卡在' . $signRecord->date,'sign_alert');
            if( strtotime('-3 days',strtotime(date('Y-m-d'))) > strtotime($signRecord->date) )
            {
                Logger::info('用户' .$item->user_id . '发送打卡提醒' . $signRecord->date,'sign_alert');
                Message::addSignAlert($signRecord->id);
            } else
            {
                Logger::info('用户' .$item->user_id . '忽略' . $signRecord->date,'sign_alert');
            }

        }
    }
}
