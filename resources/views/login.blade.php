@extends('_layout.master')
@section('title')
    <title>登录</title>
@stop
@section('style')
<style>
    html,body{background-color: #f8f8f8;}
    .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
</style>
    @stop
@section('container')

    <div style="text-align: center;padding: 30px 0;">
        <img src="/images/login_logo@2x.png" style="display: inline-block;width: 39%;"/>
    </div>

    <form id="data_form">
    <div class="login-panel padding-passport">

        <div class="cus-info-panel cus-info-panel-20">
            <div class="cus-info-panel-info inner-line">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-2">
                        <span class="fs-16-fc-030303" style="line-height: 48px;">用户</span>
                    </div>
                    <div class="cus-row-col-9">
                        <input  class="fs-16-fc-030303" type="number" style="margin-bottom: 0;border: none;" placeholder="请输入手机号或用户ID" name="phone" />
                    </div>
                    <div class="cus-row-col-1"></div>
                </div>
            </div>

            <div class="cus-info-panel-info">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-2"> <span class="fs-16-fc-030303" style="line-height: 48px;">密码</span></div>
                    <div class="cus-row-col-9">
                        <input  class="fs-16-fc-030303" type="password" style="margin-bottom: 0;border: none;" placeholder="请输入密码" name="password"/>
                    </div>
                    <div class="cus-row-col-1">
                        <span class="ques-icon" onclick="goHref('/passport/retrieve-password')"></span>
                    </div>
                </div>
            </div>

        </div>
        {{--<div class="cus-row">--}}
            {{--<div class="cus-row-col-2"></div>--}}
        {{--</div>--}}
        {{--<div class="cus-row">--}}
            {{--<div class=""></div>--}}
            {{--<div></div>--}}
        {{--</div>--}}
        {{--<form class="login-form mui-input-group" id="data_form">--}}
            {{--<div class="mui-input-row">--}}
                {{--<label>用户</label>--}}
                {{--<input type="text" class="mui-input-clear" name="phone" placeholder="请输入用户手机号">--}}
            {{--</div>--}}
            {{--<div class="mui-input-row">--}}
                {{--<label>密码</label>--}}
                {{--<input type="password" class="mui-input-password" name="password" placeholder="请输入密码">--}}
            {{--</div>--}}
            {{--<div><a href="/passport/retrieve-password">找回密码</a></div>--}}
        {{--</form>--}}
        {{--<div class="t-al-r m-t-10"><a href="/passport/retrieve-password" class="small-a">找回密码</a></div>--}}
        <div style="padding: 0 20px;"><a class="btn-block1 m-t-20" id="next_step">登录</a></div>
    </div>

    </form>
    <div class="low-alert"><span style="display: inline-block;font-size: 16px;line-height: 20px;">有邀请码</span><a href="/passport/invited-code" class="lms-link-1" style="line-height: 20px;display: inline-block;margin-left: 8px;">去注册</a></div>
@stop

@section('script')
    <script>
        $(function () {
            new SubmitButton({
                selectorStr:"#next_step",
                url:'/passport/login',
                data:function()
                {
                    return $('#data_form').serialize();
                },
                redirectTo:'/user/index'
            });
        });
    </script>
@stop