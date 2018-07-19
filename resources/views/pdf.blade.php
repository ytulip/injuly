@extends('_layout.master')
@section('title')
    <title>零风险承诺</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        #pdf_container img{width: 100%;margin-bottom: 12px;}
    </style>
@stop

@section('container')

    <form id="data_form">
        <div class="cus-row p-l-r-14">
            <div class="cus-row-col-4"><a href="javascript:history.go(-1)"><i class="back-icon"></i></a></div>
            <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">零风险承诺</span></div>
            <div class="cus-row-col-4 t-al-r"></div>
        </div>


        <div id="pdf_container">
            <img src="/pdf/0001.jpg">
            <img src="/pdf/0002.jpg">
            <img src="/pdf/0003.jpg">
        </div>
    </form>

    <!--<input type="text" placeholder="请输入您的手机号" class="phone-input"/>-->
    {{--<form id="data_form">--}}
    {{--<input class="weui-input phone-input" name="phone" type="text"  pattern="[0-9]*" placeholder="请输入您的手机号码">--}}
    {{--<div class="weui-cell get-code">--}}


    {{--<div class="weui-cell__bd">--}}
    {{--<input class="weui-input" type="password" name="password"  placeholder="输入密码 ">--}}
    {{--</div>--}}

    {{--<div class="weui-cell__bd">--}}
    {{--<input class="weui-input" type="number" name="register_sms_code" pattern="[0-9]*" placeholder="请输入6位验证码 ">--}}
    {{--</div>--}}
    {{--<div class="weui-cell__hd">--}}
    {{--<a class="get-code-btn" href="javascript:void(0)"><span style="display: inline-block;line-height: 44px;color:#2966E2;">获取验证码</span></a>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<a href="javascript:;" class="weui-btn send-ok-btn" id="next_step">确认</a>--}}
    {{--</form>--}}
@stop

@section('script')

@stop