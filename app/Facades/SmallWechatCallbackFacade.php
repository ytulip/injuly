<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * 提供门面模式
 * Class WechatCallbackFacade
 * @package App\Facades
 */
class SmallWechatCallbackFacade extends Facade{
    protected static function getFacadeAccessor() { return 'SmallWechatCallback'; }
}