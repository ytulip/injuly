<?php

namespace App\Util;
use App\Model\CashStream;
use App\Model\User;
use Illuminate\Support\Facades\DB;

/**
 * 统计月度数据
 * Class MonthStatical
 * @package App\Util
 */
class TotalStatical
{
    public $month = null;
    public $increaseMember = 0;
    public $increaseVip = 0;
    public $increaseMaster = 0;
    public $income = 0;
    public $outcome = 0;
    public $incomeOutcome = 0;
    public $payOrderCount = 0;
    public $preMonth = 0;
    public $orderGraph = [];
    public $_alipayIncome = '';
    public $_alipayOutcome = 0;
    public $_wechatIncome = 0;
    public $_wechatOutcome = 0;
    public $alipayIncomeOutcome = 0;
    public $wechatIncomeOutcome = 0;

    public function __construct($month = null)
    {
        if($month !== null) {
            $this->month = date('Y-m',strtotime(" -1 month"));
        } else {
            $this->month = $month;
        }

        $this->init();

        /*上一个月的*/
        $this->preMonth = new MonthStatical();
        $this->preMonth->init();

//        /*在统计最近半年的电子对账单*/
//        for( $i = 1; $i <= 6; $i++ )
//        {
//            $month = date('Y-m',strtotime(" -$i month"));
//            $monthStatical = new MonthStatical($month);
//            $this->orderGraph[] = ['month'=>$monthStatical->month,'total'=>$monthStatical->monthIncomeOutcome()];
//        }

        //统计从第一笔记录开始的电子对账单
        $minUpdatedAt = DB::table('cash_stream')->where('pay_status',1)->min('updated_at');
        while(true) {
            $minUpdatedAt = date('Y-m',strtotime($minUpdatedAt));
            if ( $minUpdatedAt > date('Y-m'))
            {
                break;
            }
            $monthStatical = new MonthStatical($minUpdatedAt);
            $this->orderGraph[] = [$monthStatical->month,$monthStatical->monthIncomeOutcome()];
            $minUpdatedAt = date('Y-m',strtotime(' +1 month',strtotime($minUpdatedAt)));
        }
    }

    public function init()
    {
        $this->increaseVip = $this->monthIncreaseMember(['vip_level'=>User::LEVEL_VIP]);
        $this->increaseMaster = $this->monthIncreaseMember(['vip_level'=>User::LEVEL_MASTER]);
        $this->increaseMember = $this->increaseVip + $this->increaseMaster;
        $this->income = $this->monthIncome();
        $this->outcome = $this->monthOutcome();
        $this->incomeOutcome = $this->income - $this->outcome;
        $this->payOrderCount = $this->monthPayedOrderCount();
        $this->_alipayIncome = $this->alipayIncome();
        $this->_alipayOutcome = $this->alipayOutcome();
        $this->_wechatIncome = $this->wechatIncome();
        $this->_wechatOutcome = $this->wechatOutcome();
        $this->alipayIncomeOutcome = $this->_alipayIncome - $this->_alipayOutcome;
        $this->wechatIncomeOutcome = $this->_wechatIncome - $this->_wechatOutcome;
    }

    /**
     *
     */
    private function monthIncreaseMember($filterMemberArr = [])
    {
        $query = DB::table('users');
        if( $filterMemberArr ) {
            $query->where($filterMemberArr);
        }
        return $query->count();
    }

    /**
     * 月收入
     * 1、就只有购买的收入(余额支付,微信支付宝支付的算updated_at而且状态要为1)
     */
    private function monthIncome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::systemIncomeCashTypeArr())->where('pay_status',1);
        return $query->sum('price');
    }

    private function monthOutcome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::systemOutcomeCashTypeArr())->where('pay_status',1);
        return $query->sum('price');
    }

    private function alipayIncome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::alipayIncomeCashTypeArr())->where('withdraw_type',CashStream::CASH_WITHDRAW_TYPE_WECHAT)->where('pay_status',1);
        return $query->sum('price');
    }

    private function alipayOutcome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::alipayOutcomeCashTypeArr())->where('withdraw_type',CashStream::CASH_WITHDRAW_TYPE_ALIPAY)->where('pay_status',1);
        return $query->sum('price');
    }

    private function wechatIncome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::wechatIncomeCashTypeArr())->where('pay_status',1);
        return $query->sum('price');
    }

    private function wechatOutcome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::wechatOutcomeCashTypeArr())->where('pay_status',1);
        return $query->sum('price');
    }

    private function monthPayedOrderCount()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::payOrderCashTypeArr())->where('pay_status',1);
        return $query->count();
    }

    static public function withdrawInfo()
    {

        $totalStatical = new TotalStatical();


        //支付宝待处理申请金额
        $alipayNeedDeal = CashStream::where('cash_type',CashStream::CASH_TYPE_WITHDRAW)->where('withdraw_type',CashStream::CASH_WITHDRAW_TYPE_ALIPAY)->where('withdraw_deal_status',0)->sum('price');

        //微信待处理申请金额
        $wechatNeedDeal = CashStream::where('cash_type',CashStream::CASH_TYPE_WITHDRAW)->where('withdraw_type',CashStream::CASH_WITHDRAW_TYPE_WECHAT)->where('withdraw_deal_status',0)->sum('price');

        return ['alipay'=>$totalStatical->alipayIncome() - $totalStatical->alipayOutcome(),'alipayNeedDeal'=>$alipayNeedDeal,'wechat'=>$totalStatical->wechatIncome() - $totalStatical->wechatOutcome(),'wechatNeedDeal'=>$wechatNeedDeal];
    }
}
