@extends('_layout.master')
@section('title')
    <title>身份验证</title>
@stop

@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
    </style>
@stop

@section('container')

    <!--<input type="text" placeholder="请输入您的手机号" class="phone-input"/>-->
    <form id="data_form">

        <input type="hidden" name="openid" value="{{\Illuminate\Support\Facades\Request::input('openid')}}"/>

        <div style="border-top:1px solid #EBEAEA;background-color: #ffffff">
            <div class="cus-row cus-row-bborder cus-row-v-b" style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">手机号</span></div>
                <div class="cus-row-col-9"><input class="fs-16-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;" placeholder="请输入手机号" value="" name="phone"/></div>
            </div>

            <div class="cus-row cus-row-bborder cus-row-v-b"  style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">验证码</span></div>
                <div class="cus-row-col-6"><input name="register_sms_code" class="fs-16-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;" placeholder="请输入验证码" name="register_sms_code"/></div>
                <div class="cus-row-col-3" style="height: 47px;"><a class="get-code-btn" href="javascript:void(0)" style="line-height: 44px;"><span class="lms-link-1">获取验证码</span></a></div>
            </div>


            <div class="cus-row cus-row-bborder cus-row-v-b" style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">姓名</span></div>
                <div class="cus-row-col-9"><input class="fs-16-fc-212229" style="margin-bottom: 0;border: none;height: 46px;" placeholder="输入姓名" value="" type="text" name="real_name"/></div>
            </div>


            <div class="cus-row" style="padding-left: 16px;">
                <div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">身份证</span></div>
                <div class="cus-row-col-9"><input class="fs-16-fc-212229" style="margin-bottom: 0;border: none;height: 46px;" placeholder="输入身份证号" value="" type="text" name="id_card"/></div>
            </div>


        </div>


        <div style="border-top:1px solid #EBEAEA;padding: 24px 28px;">
            <a class="btn3" style="display: block;" href="javascript:void(0);" id="next_step">确认</a>
        </div>

        </div>


    </form>
@stop

@section('script')
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script>

        var pageConfig =
            {
                openid:'{{\Illuminate\Support\Facades\Request::input('openid')}}',
                activity:{{\Illuminate\Support\Facades\Request::input('type')?1:0}},
                requestUrl: '{{\Illuminate\Support\Facades\Request::input('type')?'/activity/take-part-in':'/activity/login-in'}}'
            }

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
                $.post('/passport/register-sms',{phone:$('input[name="phone"]').val(),storage_type: 'cache'},function(data){
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
                url:pageConfig.requestUrl,
                prepositionJudge:function(){
                    if(!(/^1[3|4|5|8|7][0-9]\d{8}$/.test($('input[name="phone"]').val()))) {
                        mAlert('请输入正确的手机号');
                        return;
                    }

                    {{--if(!/^[~!@#$%^&*A-Za-z0-9]{6,20}$/.test($('input[name="password"]').val())){--}}
                        {{--mAlert('密码格式不符合要求');--}}
                        {{--return;--}}
                    {{--};--}}

                    if(!(/^[0-9]{6}$/.test($('input[name="register_sms_code"]').val()))) {
                        mAlert('请输入正确的验证码');
                        return;
                    }


                    if(!($('input[name="real_name"]').val())) {
                        mAlert('请输入姓名');
                        return;
                    }

//                    if(!/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/.test($('input[name="id_card"]').val())){
//                        mAlert('请输入正确的身份证号');
//                        return;
//                    };

                    if(!($('input[name="id_card"]').val()))
                    {
                        mAlert('请输入身份证号');
                        return;
                    }


//                    if($('input[name="password"]').val() != $('input[name="password_confirm"]').val() ) {
//                        mAlert('两次密码不一致');
//                        return;
//                    }

                    return true;
                },
                callback:function(obj,data){
                    if(data.status){
                        mAlert('认证成功');
//                        layer.open({
//                            content: '修改成功,3秒后跳到登录页面',
//                            skin: 'msg',
//                            time: 3, //2秒后自动关闭
//                            end:function(){
//                                location.href = '/passport/login';
//                            }
//                        });
                        if ( pageConfig.activity )
                        {
                            wx.miniProgram.redirectTo({
                                url: '/pages/activity/pay?openid=' + pageConfig.openid
                            })
                        } else
                        {
                            wx.miniProgram.switchTab({
                                url: '/pages/mine/index'
                            })
                        }
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