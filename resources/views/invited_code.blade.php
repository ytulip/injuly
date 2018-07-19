@extends('_layout.master')
@section('title')
    <title>注册-邀请码</title>
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

    <div class="login-panel padding-passport">
        <form class="login-form" id="data_form">

            <div class="cus-info-panel cus-info-panel-20">
                <div class="cus-info-panel-line">
                <div class="cus-row">
                    <div class="cus-row-col-3">
                        <span class="fs-16-fc-212229" style="line-height:46px; ">邀请码</span>
                    </div>
                    <div class="cus-row-col-9">
                        <input class="fs-16-fc-212229" type="text" style=";margin-bottom: 0;border: none;height: 46px;" placeholder="请输入邀请码" value="" name="invited_code"/>
                    </div>
                </div>
                </div>
            </div>

            {{--<div class="mui-input-row">--}}
                {{--<label>邀请码</label>--}}
                {{--<input type="text" class="mui-input-clear" name="invited_code" placeholder="请输入邀请码">--}}
            {{--</div>--}}
        </form>

        <div style="margin: 18px 20px;"><span class="ques-icon in-bl-v-m" style="margin-right: 8px"></span><span class="fs-12-fc-030303 in-bl-v-m">如何获取邀请码</span></div>

        <div style="padding: 0 20px;"><a class="btn-block1 m-t-20" id="next_step">下一步</a></div>
    </div>

    <div class="low-alert"><span style="display: inline-block;font-size: 16px;line-height: 20px;">有邀请码</span><a href="/passport/login" class="lms-link-1" style="line-height: 20px;display: inline-block;margin-left: 8px;">去登录</a></div>


@stop

@section('script')
<script>
    $(function () {
        new SubmitButton({
            selectorStr:"#next_step",
            url:'/passport/invited-code',
            data:function()
            {
                return $('#data_form').serialize();
            },
            redirectTo:'/passport/register'
        });
    });
</script>
@stop