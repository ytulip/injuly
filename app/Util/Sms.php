<?php

namespace  App\Util;
use App\Model\SmsManager;

/**
 * 短信验证码
 * Class Sms
 * @package Util
 */
class Sms
{
    public function send($text,$mobile)
    {
        $smsManager = new SmsManager();
        $smsManager->mobile = $mobile;
        $smsManager->content = $text;
        $smsManager->save();
        return true;
    }
}