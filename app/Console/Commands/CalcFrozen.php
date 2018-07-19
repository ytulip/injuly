<?php

namespace App\Console\Commands;

use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class CalcFrozen extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'calc:frozen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步还款计划';


    public function fire()
    {
        $res = CashStream::where('pay_status',CashStream::CASH_STATUS_FROZEN)->where('unfreeze_day','<',date('Y-m-d H:i:s'))->get();

        Logger::info('总数为:' . count($res),'cal_frozen');

        if(!$res) {
            exit;
        }

        foreach( $res as $key=>$cashStream )
        {
            Logger::info('处理:' . $key . "资金流水{$cashStream->id}",'cal_frozen');
            $user = User::find($cashStream->user_id);

            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->save();

            //forzen decrement
            $user->decrement('charge_frozen',$cashStream->price);

            //charge increment
            $user->increment('charge',$cashStream->price);
        }

        Logger::info('处理完成','cal_frozen');
    }
}
