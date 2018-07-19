<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PAY_WECHAT = 1;
    const PAY_ALIPAY = 2;
    const PAY_lMS = 3;
    const PAY_ADMIN = 4;

    const FEE_DIRECT = 1;
    const FEE_INDIRECT = 2;
    const FEE_UP = 3;
    const FEE_SUPER = 4;

    const ORDER_STATUS_INIT = 0;
    const ORDER_STATUS_WAIT_DELIVER = 1;
    const ORDER_STATUS_DELIVERED = 2;
    const ORDER_STATUS_WAIT_SELF_GET = 3;
    const ORDER_STATUS_SELF_GOT = 4;
    const ORDER_STATUS_ADMIN_BUY = 5;
    const ORDER_STATUS_CONSUMER_CONFIRM = 6;

    const BUY_TYPE_ACTIVITY = 3;
    const BUY_TYPE_REREPORT = 2;
    const BUY_TYPE_REPORT = 1;
    const BUY_TYPE_NEW_REPORT = 4;

    public function needPay()
    {
        return $this->pay_status?false:true;
    }

    public function getVipLevel()
    {
        $productAttr = ProductAttr::find($this->product_attr_id);
        return $productAttr->vip_level;
    }

    public function getInvitedCode()
    {
        $invitedCodes = InvitedCodes::where('refer_order_id',$this->id)->first();
        return isset($invitedCodes->invited_code)?$invitedCodes->invited_code:'';
    }

    public function benefitInit()
    {
        $benefitArr = [];
        $productAttr = ProductAttr::find($this->product_attr_id);

        $indirectUser = User::find($this->user_id);
        $directUser = User::find($this->immediate_user_id);

        //分报单跟复购两种情况
        if( in_array($this->buy_type,[Order::BUY_TYPE_REPORT,Order::BUY_TYPE_NEW_REPORT]) )
        {

            //直接开发费用的产生
            $benefitArr[] = (object)['user'=>$directUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_DIRECT,'price'=>$productAttr->single_direct_price * $this->quantity,'sub_cash_type'=>1];
            $this->direct_id = $directUser->id;


            $upUser = User::find($directUser->parent_id);
            $superUser = User::find($directUser->indirect_id);
        } elseif( $this->buy_type == Order::BUY_TYPE_REREPORT )
        {
            $upUser = User::find($indirectUser->parent_id);
            $superUser = User::find($indirectUser->indirect_id);
        }

        //辅导费用的产生
        if($upUser) {
            $this->up_id = $upUser->id;
            $benefitArr[] = (object)['user'=>$upUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_UP,'price'=>$this->isRebuy()?$productAttr->rebuy_up_price * $this->quantity:$productAttr->up_price,'sub_cash_type'=>1];
        }

        if($superUser) {
            $this->super_id = $upUser->id;
            $benefitArr[] = (object)['user'=>$superUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_SUPER,'price'=>$this->isRebuy()?$productAttr->rebuy_super_price * $this->quantity:$productAttr->super_price,'sub_cash_type'=>2];
        }

        $this->save();


        foreach($benefitArr as $key=>$val)
        {

            /*这里来算提成了*/
            $cashStream = new CashStream();
            $cashStream->price = $val->price;
            $cashStream->user_id = $val->user->id;
            $cashStream->cash_type = $val->cash_type;
            $cashStream->sub_cash_type = $val->sub_cash_type;
            $cashStream->pay_status = CashStream::CASH_STATUS_FROZEN;
//            $cashStream->refer_user_id = $userId;
            $cashStream->refer_id = $this->id;
            $cashStream->vip_level = $productAttr->vip_level;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->save();

            $user = User::find($cashStream->user_id);
            $user->increment('charge_frozen',$cashStream->price);
        }

        return true;
    }

    public function benefitNew($userId)
    {
        $cashStreamArr = CashStream::whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT,CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->where('refer_id',$this->id)->get();

        foreach($cashStreamArr as $key=>$cashStream)
        {
            /*这里来算提成了*/
            $cashStream->pay_status = CashStream::CASH_STATUS_FROZEN;
            $cashStream->refer_user_id = $userId;
            $cashStream->unfreeze_day = date('Y-m-d H:i:s',strtotime('+30 days',strtotime(date('Y-m-d'))));  //date('+30 days');
            $cashStream->save();
            $user = User::find($cashStream->user_id);
            $user->increment('charge_frozen',$cashStream->price);
        }
    }

    public function benefitNow()
    {
        $cashStreamArr = CashStream::whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT,CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->where('refer_id',$this->id)->get();

        foreach($cashStreamArr as $key=>$cashStream)
        {
            /*这里来算提成了*/
            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->refer_user_id = $this->user_id;
            $cashStream->save();
            $user = User::find($cashStream->user_id);
            $user->increment('charge_frozen',$cashStream->price);
        }

    }

    public function benefit($userId)
    {
        $benefitArr = [];
        $productAttr = ProductAttr::find($this->product_attr_id);

        $indirectUser = User::find($this->user_id);


        $upUser = User::find($indirectUser->parent_id);
        $superUser = User::find($indirectUser->indirect_id);
        $directUser = User::find($this->immediate_user_id);

        $benefitArr[] = (object)['user'=>$directUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_DIRECT,'price'=>$productAttr->direct_price,'sub_cash_type'=>1];

        $benefitArr[] = (object)['user'=>$indirectUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_INDIRECT,'price'=>$productAttr->indirect_price,'sub_cash_type'=>2];

        if($upUser) {
            $benefitArr[] = (object)['user'=>$upUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_UP,'price'=>$productAttr->up_price,'sub_cash_type'=>1];
        }

        if($superUser) {
            $benefitArr[] = (object)['user'=>$superUser,'cash_type'=>CashStream::CASH_TYPE_BENEFIT_SUPER,'price'=>$productAttr->super_price,'sub_cash_type'=>2];
        }

        foreach($benefitArr as $key=>$val)
        {
            //if($val->price == 0) continue;


            if($val->user->vip_level != User::LEVEL_MASTER)
            {
                //等级跃迁
                $directCount = $val->user->as_direct_count;
                $val->user->increment('as_direct_count',1);
                if($directCount >= 2)
                {
                    $val->user->vip_level = User::LEVEL_MASTER;
                    $val->user->save();
                } else {
                    $val->price = 0;
                }
            }

            /*这里来算提成了*/
            $cashStream = new CashStream();
            $cashStream->price = $val->price;
            $cashStream->user_id = $val->user->id;
            $cashStream->cash_type = $val->cash_type;
            $cashStream->sub_cash_type = $val->sub_cash_type;
            $cashStream->pay_status = CashStream::CASH_STATUS_FROZEN;
            $cashStream->refer_user_id = $userId;
            $cashStream->refer_id = $this->id;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->vip_level = $productAttr->vip_level;
            $cashStream->save();
            $val->user->increment('charge_frozen',$cashStream->price);
        }

        return true;
    }

    //利润、购买收入、开发支出、辅导支出
    public function cashInfo()
    {
        $directIndirectPrice = CashStream::where('refer_id',$this->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT])->sum('price');
        $upSuperPrice = CashStream::where('refer_id',$this->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->sum('price');

        $directPrice = CashStream::where('refer_id',$this->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT])->sum('price');;
        $indirectPrice = CashStream::where('refer_id',$this->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_INDIRECT])->sum('price');;
        $upPrice = CashStream::where('refer_id',$this->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP])->sum('price');;
        $superPrice = CashStream::where('refer_id',$this->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_SUPER])->sum('price');;


        $indirectUser = User::find($this->user_id);
        $upUser = User::find($indirectUser->parent_id);
        $superUser = User::find($indirectUser->indirect_id);
        $directUser = User::find($this->immediate_user_id);

        if(!$upUser){
            $upUser = (object)['real_name'=>'','phone'=>''];
        }

        if(!$superUser){
            $superUser = (object)['real_name'=>'','phone'=>''];
        }


        return ['benefit'=>$this->need_pay - $directIndirectPrice - $upSuperPrice,'directIndirectPrice'=>$directIndirectPrice,'upSuperPrice'=>$upSuperPrice,'price'=>$this->need_pay,'indirectUser'=>$indirectUser,'upUser'=>$upUser,'superUser'=>$superUser,'directUser'=>$directUser,'directPrice'=>$directPrice,'indirectPrice'=>$indirectPrice,'upPrice'=>$upPrice,'superPrice'=>$superPrice];
    }




    /**
     * 根據invited_code來获得产品
     */
    public static function getOrderByInvitedCode($invitedCode)
    {
        $invitedCode = InvitedCodes::tryCurrentInstance($invitedCode);
        $orderId = $invitedCode->refer_order_id;
        return Order::find($orderId);
    }


    public static function orderStatusText($status)
    {
        $res = '';
        switch ($status) {
            case Order::ORDER_STATUS_INIT:
                $res = '待付款';
                break;
            case Order::ORDER_STATUS_WAIT_DELIVER:
                $res = '待发货';
                break;
            case Order::ORDER_STATUS_DELIVERED:
                $res = '已发货';
                break;
            case Order::ORDER_STATUS_WAIT_SELF_GET:
                $res = '待取货';
                break;
            case Order::ORDER_STATUS_SELF_GOT:
                $res = '已取货';
                break;
        }
        return $res;

    }


    public function invitedCodeInfo()
    {
        $invitedCode = InvitedCodes::where('refer_order_id',$this->id)->first();
        return $invitedCode;
    }


    public function isUsedInvited()
    {
        $invitedCode = $this->invitedCodeInfo();
        if($invitedCode) {
            return $invitedCode->code_status?true:false;
        }
        else{return false;}
    }

    public function isRebuy()
    {
        return ($this->buy_type == 2)?true:false;
    }

    public function buyTypeText()
    {
        $text = '';
        switch($this->buy_type)
        {
            case 1:
                $text = '邀请新会员';
                break;
            case 2:
                $text = '复购';
                break;
            case 3:
                $text = '活动';
                break;
            case 4:
                $text = '新用户报单';
                break;
                default;
        }
        return $text;
    }

    public function paySuccess()
    {

        //是否生产邀请码
        if( $this->buy_type == Order::BUY_TYPE_REPORT ) {
            InvitedCodes::makeRecord($this->id);
        }

//        //生成开发辅导初始记录
//        $this->benefitInit();
//
//        //复购的话，立即分润
//        if( $this->isRebuy() )
//        {
//            $this->benefitNow();
//        }
    }

    /**
     * 订单是否今天需要签0不需要签，1需要签，2签过了
     */
    public function needSignToday()
    {
        if( $this->buy_type != Order::BUY_TYPE_ACTIVITY )
        {
            return 0;
        }

        if( in_array($this->sign_status,[1,2]) )
        {
            if( $this->sign_status == 1)
            {
                //查看是否超出了起签的时间,超过3天不允许
//                if( $this->isPassFirstSign() )
//                {
//                    return 0;
//                }else
//                {
//                    return 1;
//                }
                return 1;
            }

            if( $this->sign_status == 2)
            {
                //判断今天是否已经签过了
                if ( !$this->hasSignToday() )
                {
                    return 1;
                } else
                {
                    return 2;
                }

            }
        }

        return 0;

    }

    public function isPassFirstSign()
    {
        //沒有则认为是第一次签到，创建签到记录
        $endTime = strtotime("+4 days",strtotime(date('Y-m-d',strtotime($this->confirm_date))));
        if ( $endTime < time() )
        {
            //超过了签到的限制
            return true;
        }

        return false;
    }


    /**
     * 判断今天是否已将签过
     */
    public function hasSignToday()
    {
        $signRecord = SignRecord::where('user_id',$this->user_id)->where('date',date('Y-m-d'))->first();


        if( !($signRecord instanceof  SignRecord) )
        {
            return false;
        }

        if( $signRecord->sign_status )
        {
            return true;
        }

        return false;
    }


}