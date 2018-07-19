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
class MonthStatical
{
    public $month = null;
    public $increaseMember = 0;
    public $increaseVip = 0;
    public $increaseMaster = 0;
    public $income = 0;
    public $outcome = 0;
    public $incomeOutcome = 0;
    public $payOrderCount = 0;
    public $_alipayIncome = 0;
    public $_alipayOutcome = 0;
    public $_wechatIncome = 0;
    public $_wechatOutcome = 0;
    public $alipayIncomeOutcome = 0;
    public $wechatIncomeOutcome = 0;

    public function __construct($month = null)
    {
        if($month === null) {
            $this->month = date('Y-m',strtotime(" -1 month"));
        } else {
            $this->month = $month;
        }
    }

    public function init()
    {
        $this->increaseVip = $this->monthIncreaseMember(['origin_vip_level'=>User::LEVEL_VIP]);
        $this->increaseMaster = $this->monthIncreaseMember(['origin_vip_level'=>User::LEVEL_MASTER]);
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

    public function monthIncomeOutcome()
    {
        return $this->monthIncome() - $this->monthOutcome();
    }

    /**
     *
     */
    private function monthIncreaseMember($filterMemberArr = [])
    {
        $query = DB::table('users');
        Kit::columnMonthFilter($query,$this->month);
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
        Kit::columnMonthFilter($query,$this->month,'updated_at');
        return $query->sum('price');
    }

    private function monthOutcome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::systemOutcomeCashTypeArr())->where('pay_status',1);
        Kit::columnMonthFilter($query,$this->month);
        return $query->sum('price');
    }

    private function monthPayedOrderCount()
    {
        $query = DB::table('cash_stream')->where('cash_type',CashStream::payOrderCashTypeArr())->where('pay_status',1);
        Kit::columnMonthFilter($query,$this->month,'updated_at');
        return $query->count();
    }


    private function alipayIncome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::alipayIncomeCashTypeArr())->where('withdraw_type',CashStream::CASH_WITHDRAW_TYPE_WECHAT)->where('pay_status',1);
        Kit::columnMonthFilter($query,$this->month,'updated_at');
        return $query->sum('price');
    }

    private function alipayOutcome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::alipayOutcomeCashTypeArr())->where('withdraw_type',CashStream::CASH_WITHDRAW_TYPE_ALIPAY)->where('pay_status',1);
        Kit::columnMonthFilter($query,$this->month,'updated_at');
        return $query->sum('price');
    }

    private function wechatIncome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::wechatIncomeCashTypeArr())->where('pay_status',1);
        Kit::columnMonthFilter($query,$this->month,'updated_at');
        return $query->sum('price');
    }

    private function wechatOutcome()
    {
        $query = DB::table('cash_stream')->whereIn('cash_type',CashStream::wechatOutcomeCashTypeArr())->where('pay_status',1);
        Kit::columnMonthFilter($query,$this->month,'updated_at');
        return $query->sum('price');
    }
}
