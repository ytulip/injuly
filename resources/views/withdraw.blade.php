@extends('_layout.master')
@section('title')
    <title>提现申请</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'提现申请'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/withdraw"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">提现申请</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

<form id="data_form">
    <input name="withdraw" value="{{\Illuminate\Support\Facades\Request::input('withdraw')}}" type="hidden"/>
    <input name="withdraw_type" value="{{\Illuminate\Support\Facades\Request::input('withdraw_type')}}" type="hidden"/>
    <input name="account" value="{{\Illuminate\Support\Facades\Request::input('account')}}" type="hidden"/>
    <div class="p-l-r-14" >
        <div style="border:1px solid #EBEAEA;">
        <div><label class="cus-label-1">辣木膳登录密码</label></div>
        <div style="border-top:1px solid #EBEAEA;"><input type="password" class="mui-input-password" name="password" placeholder="请输入辣木膳登录密码" data-input-password="2" style="border: none;
    font-size: 14px;margin-bottom: 0;height: 48px;"></div>
        </div>
<div>
            <div style="margin-top: 12px;margin-bottom: 30px;"> <span><i class="attention-icon"></i></span><span class="fs-12-fc-212229">输入辣木膳购买系统登录密码，以便我们核对身份，请勿输入提现账户密码，我们不会向您索取任何第三方密码</span></div>
        </div>

        <div style="border:1px solid #EBEAEA;background-color: #ffffff">
            <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">姓名</span></div>
                <div class="cus-row-col-9"><input class="fs-16-fc-909094" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" readonly value="{{$user->real_name}}"/></div>
            </div>

            <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">手机</span></div>
                <div class="cus-row-col-9"><input class="fs-16-fc-909094" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" readonly value="{{\App\Util\Kit::phoneHide($user->phone)}}"/></div>
            </div>

            <div class="cus-row" style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">验证</span></div>
                <div class="cus-row-col-6"><input name="withdraw_sms_code" class="fs-16-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;"/></div>
                <div class="cus-row-col-3"><a class="get-code-btn" href="javascript:void(0)"><span class="fs-14-fc-98CC3D" style="display: inline-block;line-height: 44px;">获取验证码</span></a></div>
            </div>
            {{--<div class="cus-input-row fs-16-fc-212229"><label>姓名</label><input/></div>--}}
            {{--<div class="cus-input-row fs-16-fc-212229"><label>手机</label><input/></div>--}}
            {{--<div class="cus-input-row"><label>姓名</label><input/></div>--}}
            <div style="border-top:1px solid #EBEAEA;padding: 24px 28px;">
                <a class="btn-block1" href="javascript:void(0);" id="next_step">提交申请</a>
            </div>
        </div>

        <div style="margin-top: 12px;text-align: center;"><span class="fs-12-fc-212229">提现金额:{{\Illuminate\Support\Facades\Request::input('withdraw')}}</span></div>
    </div>

</form>

    {{--<div class="p-l-r-14">--}}
        {{--<div style="background-color: #ffffff;border: 1px solid #EBE9E9;">--}}
            {{--<div class="p-all-14"><span class="fs-14-fc-212229">辣木膳登录密码</span></div>--}}
            {{--<div class="p-all-14" style="border-top:1px solid #EBEAEA;"><input class="cus-input-1" placeholder="请输入辣木膳登录密码" type="password"/></div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class=" ">--}}
        {{--<form id="data_form">--}}
        {{--<ul class="mui-table-view income-list">--}}
            {{--<li class="mui-table-view-cell mui-row"><div class="mui-col-sm-2 mui-col-xs-2"></div><div class="mui-col-sm-7 mui-col-xs-7">可提现金额</div><div class="mui-col-sm-3 mui-col-xs-3">￥{{$user->charge}}</div></li>--}}
            {{--<li class="mui-table-view-cell mui-row"><div class="mui-col-sm-2 mui-col-xs-2"></div><div class="mui-col-sm-7 mui-col-xs-7">申请提现金额<input name="withdraw" type="number"/></div><div class="mui-col-sm-3 mui-col-xs-3"></div></li>--}}
            {{--<li class="mui-table-view-cell mui-row"><div class="mui-col-sm-2 mui-col-xs-2"></div><div class="mui-col-sm-7 mui-col-xs-7">姓名:{{$user->real_name}}</div><div class="mui-col-sm-3 mui-col-xs-3"></div></li>--}}
            {{--<li class="mui-table-view-cell mui-row"><div class="mui-col-sm-2 mui-col-xs-2"></div><div class="mui-col-sm-7 mui-col-xs-7">手机号:{{\App\Util\Kit::phoneHide($user->phone)}}</div><div class="mui-col-sm-3 mui-col-xs-3"></div></li>--}}
            {{--<li class="mui-table-view-cell mui-row"><div class="mui-col-sm-2 mui-col-xs-2"></div><div class="mui-col-sm-7 mui-col-xs-7">验证码<input name="withdraw_sms_code" type="number"/></div><div class="mui-col-sm-3 mui-col-xs-3"><a class="get-code-btn" href="javascript:void(0)"><span style="display: inline-block;line-height: 44px;color:#2966E2;">获取验证码</span></a></div></li>--}}
        {{--</ul>--}}
        {{--</form>--}}
    {{--</div>--}}

    {{--<footer class="fix-bottom">--}}
        {{--<a class="btn-block1 remove-radius" href="javascript:void(0);" id="next_step">下一步</a>--}}
    {{--</footer>--}}
@stop

@section('script')
<script>

//    function nextStep()
//    {
//
//    }

    $(function(){
        $('.get-code-btn').click(function(){

            if ( $(this).hasClass('get-code-lock') ) {
                return null;
            }

            $(this).addClass('get-code-lock');
            $(this).attr('data-second',60);
            $(this).find('span').html($(this).attr('data-second') + '秒');
            (function(a){
                var countDownHandler = setInterval(function(){
                    $(a).attr('data-second',$(a).attr('data-second') - 1);
                    if( $(a).attr('data-second') < 1) {
                        clearInterval(countDownHandler);
                        $(a).removeClass('get-code-lock');
                        $(a).find('span').html('获取验证码');
                        return;
                    }
                    $(a).find('span').html($(a).attr('data-second') + '秒');
                },1000);
            })(this);

            //TODO:请求验证码
            $.post('/user/withdraw-sms',{},function(data){
                if(data.status) {
                    mAlert('发送成功');
                } else {
                    mAlert(data.desc);
                }
            },'json').error(function(){
                alert('网络异常！');
            });

        });
    });


$(function () {
    new SubmitButton({
        selectorStr:"#next_step",
        url:'/user/withdraw-confirm',
        prepositionJudge:function(){

            $withdraw = parseFloat($('input[name="withdraw"]').val()).toFixed(2);
            if (isNaN($withdraw) || $withdraw <= 0) {
                mAlert('请输入正确的金额');
                return;
            }

            if(!(/^[0-9]{6}$/.test($('input[name="withdraw_sms_code"]').val()))) {
                mAlert('请输入正确的验证码');
                return;
            }
            return true;
        },
        callback:function(obj,data){
            if( data.status ) {
                location.href = "/user/withdraw-success?withdraw=" + parseFloat($('input[name="withdraw"]').val()).toFixed(2);
            } else {
                mAlert(data.desc);
            }
        },
        data:function()
        {
            return $('#data_form').serialize();
        }
    });
});
</script>
@stop