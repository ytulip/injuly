@extends('_layout.master')
@section('title')
    <title>注册-邀请码</title>
@stop
@section('style')
<style>
    html,body{background-color: #f8f8f8;}
</style>
    @stop

@section('container')

<form id="data_form">
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/passport/invited-code"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">注册</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>


    <div style="border-top:1px solid #EBEAEA;background-color: #ffffff">
        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-16-fc-212229" style="line-height: 46px;">手机号</span></div>
            <div class="cus-row-col-9"><input class="fs-16-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" placeholder="请输入手机号" value="" name="phone"/></div>
        </div>

        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-16-fc-212229" style="line-height: 46px;">验证码</span></div>
            <div class="cus-row-col-6"><input name="register_sms_code" class="fs-16-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" placeholder="请输入验证码" name="register_sms_code"/></div>
            <div class="cus-row-col-3"><a class="get-code-btn" href="javascript:void(0)"><span class="lms-link-1" style="display: inline-block;line-height: 44px;">获取验证码</span></a></div>
        </div>


        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-16-fc-212229" style="line-height: 46px;">设置密码</span></div>
            <div class="cus-row-col-9"><input class="fs-16-fc-212229" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" placeholder="设置密码" value="" type="password" name="password"/></div>
        </div>


        <div class="cus-row" style="padding-left: 16px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-16-fc-212229" style="line-height: 46px;">确认密码</span></div>
            <div class="cus-row-col-9"><input class="fs-16-fc-212229" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" placeholder="确认密码" value="" type="password" name="password_confirm"/></div>
        </div>
        {{--<div class="cus-input-row fs-16-fc-212229"><label>姓名</label><input/></div>--}}
        {{--<div class="cus-input-row fs-16-fc-212229"><label>手机</label><input/></div>--}}
        {{--<div class="cus-input-row"><label>姓名</label><input/></div>--}}
    </div>
</form>


<div style="border-top:1px solid #EBEAEA;padding: 24px 28px;">
    <a class="btn-block1" href="javascript:void(0);" id="next_step">提交申请</a>
</div>

<p class="t-al-c"><span class="agree-icon" style="vertical-align: middle"></span><span class="fs-12-fc-030303" style="vertical-align: middle">下一步即同意<a href="/passport/pdf">《零风险承诺》</a></span></p>

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
    <script>
        $(function(){
            $('.get-code-btn').click(function(){

                if(!(/^1[3|4|5|8|7][0-9]\d{8}$/.test($('input[name="phone"]').val()))) {
                    mAlert('请输入正确的手机号');
                    return;
                }

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
                $.post('/passport/register-sms',{phone:$('input[name="phone"]').val()},function(data){
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
                url:'/passport/register',
                prepositionJudge:function(){
                    if(!(/^1[3|4|5|8|7][0-9]\d{8}$/.test($('input[name="phone"]').val()))) {
                        mAlert('请输入正确的手机号');
                        return;
                    }

                    if(!/^[~!@#$%^&*A-Za-z0-9]{6,20}$/.test($('input[name="password"]').val())){
                        mAlert('密码格式不符合要求');
                        return;
                    };

                    if(!(/^[0-9]{6}$/.test($('input[name="register_sms_code"]').val()))) {
                        mAlert('请输入正确的验证码');
                        return;
                    }

                    if($('input[name="password"]').val() != $('input[name="password_confirm"]').val() ) {
                        mAlert('两次密码不一致');
                        return;
                    }

                    return true;
                },
                callback:function(obj,data){
                    if(data.status){
                        layer.open({
                            content: '注册成功,3秒后跳到登录页面',
                            skin: 'msg',
                            time: 3, //2秒后自动关闭
                            end:function(){
                                location.href = '/passport/login';
                            }
                        });
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