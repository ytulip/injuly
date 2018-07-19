<?php

return [
    'wechat'=>[
        'appid'=>env('WECHAT_APPID'),
        'appsercret'=>env('WECHAT_APPSERCRET'),
        'mechid'=>env('WECHAT_MECHID'),
        'key'=>env('WECHAT_KEY'),
        'token'=>env('WECHAT_TOKEN'),
        'token_path'=>storage_path() . '/wechat/access_token.json',
        'ticket_path'=>storage_path() . '/wechat/jsapi_ticket.json',
        'menu_path'=>storage_path() . '/wechat/menu.json' //自定义菜单存储路径
    ],
    'small_wechat'=>[
        'appid'=>env('SMALL_APPID'),
        'appsercret'=>env('SMALL_APPSECRET'),
        'mechid'=>env(''),
        'key'=>env(''),
        'token'=>env(''),
        'token_path'=>env('SMALL_TOKEN_PATH',storage_path() . '/wechat/small_access_token.json'),
        'ticket_path'=>env('SMALL_TICKET_PATH',storage_path() . '/wechat/small_jsapi_ticket.json'),
        'menu_path'=>env('SMALL_MENU_PATH',storage_path() . '/wechat/small_menu.json') //自定义菜单存储路径
    ],
    'pay_close_text'=>'微信支付功能调整中，支付功能暂时无法使用，可以正常进行健康打卡'
];
