<?php

namespace App\Util;

use App\Log\Facades\Logger;
use App\Model\Base\UniqueCodeModel;
use App\Util\Enum;
use App\Util\Sms;

class SmsTemplate extends Enum
{
    const REGISTER_SMS = 'register_sms';
    const PASSWORD_SMS = 'password_sms';
    const WITHDRAW_SMS = 'withdraw_sms';
    const MODIFY_PHONE_SMS = 'modify_phone_sms';
    const DELIVER_SMS = 'deliver_sms';

    private static function tmpConfig()
    {
        return [
            'register_sms'=>['template_code'=>'SMS_126890093'],
            'password_sms'=>['template_code'=>'SMS_126875684'],
            'withdraw_sms'=>['template_code'=>'SMS_126865728'],
            'modify_phone_sms'=>['template_code'=>'SMS_126866180'],
            'deliver_sms'=>['template_code'=>'SMS_133960721']
        ];
    }


    public function tmpValue($key)
    {
        return self::tmpConfig()[$this->value][$key];
    }

    /**
     * @param mobile
     * @param array $arr
     */
    public function sendSms($mobile,Array $arr = [])
    {
        require_once  base_path('plugin/aliyun-dysms-php-sdk/api_demo/SmsDemo.php');

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new \Aliyun\Api\Sms\Request\V20170525\SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("三膳善");

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($this->tmpValue('template_code'));

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        Logger::info($mobile . '发送短信','sms');
        Logger::info($arr,'sms');
        $request->setTemplateParam(json_encode($arr, JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
//        $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
//        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = \SmsDemo::getAcsClient()->getAcsResponse($request);

//        Logger::info($acsResponse,'sms');



//        $tmpConfig = SmsTemplate::tmpConfig();
//        $tmpStr = isset($tmpConfig[$this->value])?$tmpConfig[$this->value]:'';
//        foreach ($arr as $key=>$val){
//            $tmpStr = str_replace('${'.$key.'}',$val,$tmpStr);
//        }
//        $tmpStr = preg_replace('%\$\{[A-z_1-9]\}%','',$tmpStr);
//        //TODO:这里需要采取什么样的策略，如果发送失败后
//        (new Sms())->send($tmpStr,$mobile);
    }
}
