<?php
namespace App\Providers;

use App\Services\MyWechatResponse;
use Illuminate\Support\ServiceProvider;

/**
 * Class WechatServiceProvider
 * @package App\Providers
 */
class SmallWechatServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('SmallWechatCallback',function(){
            return new \Ytulip\Ycurl\WechatCallback(config('customer.small_wechat'),new MyWechatResponse());
        });
    }

    public function boot()
    {
    }
}