<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CashStream extends Model
{
    const CASH_TYPE_WECHAT_BUY_PRODUCT = 101;
    const CASH_TYPE_ALIPAY_BUY_PRODUCT = 102;
    const CASH_TYPE_LMS_BUY_PRODUCT = 103;

    const CASH_TYPE_LMS_PAY_BUY_PRODUCT = 201;

    const CASH_TYPE_BENEFIT_DIRECT = 301;
    const CASH_TYPE_BENEFIT_INDIRECT = 302;
    const CASH_TYPE_BENEFIT_UP = 303;
    const CASH_TYPE_BENEFIT_SUPER = 304;

    const CASH_TYPE_WITHDRAW = 401;
    const CASH_TYPE_WITHDRAW_AGREE = 402;
    const CASH_TYPE_WITHDRAW_REFUSE = 403;

    const CASH_TYPE_ACTIVITY_WITHDRAW = 501;
    const CASH_TYPE_ACTIVITY_WITHDRAW_AGREE = 502;
    const CASH_TYPE_ACTIVITY_WITHDRAW_REFUSE = 503;

    const CASH_TYPE_TURNBACK = 501;

    const CASH_STATUS_FROZEN = 2;
    const CASH_STATUS_PAYED = 1;
    const CASH_STATUS_WAITING_PAY = 0;

    const CASH_DIRECTION_IN = 1;
    const CASH_DIRECTION_OUT = 2;

    const CASH_WITHDRAW_TYPE_ALIPAY = 1;
    const CASH_WITHDRAW_TYPE_WECHAT = 2;

    const CASH_PAY_TYPE_ALIPAY = 1;
    const CASH_PAY_TYPE_WECHAT = 2;
    const CASH_PAY_TYPE_LMS = 3;

    public $table = 'cash_stream';

    public static function incomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT,CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER];
    }

    public static function systemIncomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_LMS_PAY_BUY_PRODUCT,CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT,CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT];
    }

    public static function  alipayIncomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT];
    }

    public static function wechatIncomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT];
    }

    public static function wechatOutcomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_WITHDRAW_AGREE];
    }

    public static function alipayOutcomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_WITHDRAW_AGREE];
    }

    public static function systemOutcomeCashTypeArr()
    {
        return [CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT,CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER,CashStream::CASH_TYPE_WITHDRAW];
    }

    public static function payOrderCashTypeArr()
    {
        return [CashStream::CASH_TYPE_LMS_PAY_BUY_PRODUCT,CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT,CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT];
    }

    public static function userIncomeOutcomeArr()
    {
        $income = [CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT,CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER];
        $outcome = [CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT,CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT,CashStream::CASH_TYPE_LMS_BUY_PRODUCT];
        return array_merge($income,$outcome);
    }

    public static function cashTypeText($cashType)
    {
        $cashTypeText = '';
        switch($cashType)
        {
            case CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT:
                $cashTypeText = '购买';
                break;
            case CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT:
                $cashTypeText = '购买';
                break;
            case CashStream::CASH_TYPE_LMS_BUY_PRODUCT:
                $cashTypeText = '购买';
                break;
            case CashStream::CASH_TYPE_BENEFIT_DIRECT:
                $cashTypeText = '直接开发获利';
                break;
            case CashStream::CASH_TYPE_BENEFIT_INDIRECT:
                $cashTypeText = '间接开发获利';
                break;
            case CashStream::CASH_TYPE_BENEFIT_UP:
                $cashTypeText = '一代辅导获利';
                break;
            case CashStream::CASH_TYPE_BENEFIT_SUPER:
                $cashTypeText = '二代辅导获利';
                break;
        }
        return $cashTypeText;
    }

    public static function payTypeText($type)
    {
        $res = '';
        switch ($type )
        {
            case CashStream::CASH_PAY_TYPE_ALIPAY:
                $res = '支付宝';
                break;
            case CashStream::CASH_PAY_TYPE_WECHAT:
                $res = '微信';
                break;
            case CashStream::CASH_PAY_TYPE_LMS:
                $res = '余额';
                break;
        }
        return $res;
    }

    public static function withdrawTypeText($type)
    {
        $res = '';
        switch ($type )
        {
            case CashStream::CASH_WITHDRAW_TYPE_ALIPAY:
                $res = '支付宝';
                break;
            case CashStream::CASH_WITHDRAW_TYPE_WECHAT:
                $res = '微信';
                break;
        }
        return $res;
    }

    public static function withdrawStatusText($status)
    {
        $res = '';
        switch ($status )
        {
            case 1:
                $res = '通过';
                break;
            case 2:
                $res = '拒绝';
                break;
            default:
                $res = "待处理";
        }
        return $res;
    }

}