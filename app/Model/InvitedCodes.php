<?php

namespace App\Model;

use App\Util\DealString;
use Illuminate\Database\Eloquent\Model;

class InvitedCodes extends Model
{

    private $userModel = null;

    /**
     * todo:这个唯一码要改进，不然会出现主键冲突
     * 生产一个新的邀请码,7位数字和字母的组合
     * @param $orderId
     *
     */
    static public function makeRecord($orderId)
    {
        $newInvitedModel = null;


        $newInvitedModel = new InvitedCodes();
        $newInvitedModel->invited_code = RandomPool::random();
        $newInvitedModel->refer_order_id = $orderId;
        $newInvitedModel->save();

        return $newInvitedModel;
    }


    /**
     * 根据获得第一个实例
     * @param $invitedCode
     */
    static public function tryCurrentInstance($invitedCode = null)
    {
        return self::where(['invited_code'=>$invitedCode])->first();
    }

    /**
     * 根据获得第一个有效实例
     * @param $invitedCode
     */
    static public function tryCurrentInstanceValid($invitedCode = null)
    {
        return self::where(['invited_code'=>$invitedCode,'code_status'=>0])->first();
    }

    public function userInfo($infoTag)
    {
        if(!$this->code_status)
        {
            return '';
        }

        if(!$this->userModel)
        {
            $this->userModel = User::find($this->user_id);
        }

        return $this->userModel->$infoTag;

    }


}