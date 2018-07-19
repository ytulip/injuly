@extends('_layout.master')
@section('title')
    <title>完善资料</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
    </style>
@stop
@section('container')



    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">完善资料</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div class="login-panel padding-passport">
        {{--<div class="opr-h-wrap">--}}
            {{--<h3 class="opr-h">完善资料</h3>--}}
        {{--</div>--}}
        <form class="login-form mui-input-group" id="data_form">
            <div class="cus-info-panel cus-info-panel-20">
                <div class="cus-info-panel-line inner-line">
                    <div class="cus-row">
                        <div class="cus-row-col-3"><span class="fs-16-fc-030303" style="line-height: 48px;">姓名</span></div>
                        <div class="cus-row-col-9">
                            <input type="text" class="mui-input-clear fs-16-fc-030303" name="real_name" value="{{\Illuminate\Support\Facades\Request::input('real_name')}}" >
                        </div>
                    </div>
                </div>
                <div class="cus-info-panel-line">
                    <div class="cus-row">
                        <div class="cus-row-col-3"><span class="fs-16-fc-030303" style="line-height: 48px;">身份证号</span></div>
                        <div class="cus-row-col-9">
                            <input type="text" class="mui-input-clear fs-16-fc-030303" name="id_card" value="{{\Illuminate\Support\Facades\Request::input('id_card')}}">
                        </div>
                    </div>
                </div>

            </div>
            {{--<div class="mui-input-row">--}}
                {{--<label><span class="fs-16-fc-030303">姓名</span></label>--}}
                {{--<input type="text" class="mui-input-clear fs-16-fc-030303" name="real_name" value="{{\Illuminate\Support\Facades\Request::input('real_name')}}" >--}}
            {{--</div>--}}
            {{--<div class="mui-input-row">--}}
                {{--<label><span class="fs-16-fc-030303">身份证号</span></label>--}}
                {{--<input type="text" class="mui-input-clear fs-16-fc-030303" name="id_card" value="{{\Illuminate\Support\Facades\Request::input('id_card')}}">--}}
            {{--</div>--}}

                {{--<div class="in-bl-line" onclick="addModAddress()" style="padding: 10px 0;">--}}
                {{--<div class="in-bl-line-item" style="width: 35%;vertical-align: top;"><span class="fs-16-fc-030303" style="padding-left: 15px;">收货地址</span></div>--}}
                {{--<div class="in-bl-line-item" style="width: 65%">--}}
                    {{--<div class="mui-row">--}}
                    {{--<div class="mui-col-sm-6 mui-col-xs-6 t-al-r"><span class="fs-14-fc-030303">{{\App\Util\Kit::issetThenReturn($address,'address_name')}}</span></div>--}}
                        {{--<div class="mui-col-sm-6 mui-col-xs-6 t-al-r"><span>{{\App\Util\Kit::issetThenReturn($address,'mobile')}}</span></div>--}}
                    {{--</div>--}}
                    {{--<div class="mui-row"><span class="fs-16-fc-030303">{{\App\Util\Kit::issetThenReturn($address,'address')}}</span></div>--}}
                {{--</div>--}}
                {{--<div></div>--}}
            {{--</div>--}}

            {{--<div class="mui-row m-t-10" onclick="addModAddress()">--}}
                {{--<label>收货地址</label>--}}
                {{--<div>--}}
                    {{--<div class="mui-input-row">--}}
                        {{--<div>{{\App\Util\Kit::issetThenReturn($address,'address_name')}}</div>--}}
                        {{--<div>{{\App\Util\Kit::issetThenReturn($address,'mobile')}}</div>--}}
                    {{--</div>--}}
                    {{--<div class="mui-input-row">{{\App\Util\Kit::issetThenReturn($address,'address')}}</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div><a href="/passport/retrieve-password">找回密码</a></div>--}}
        </form>
        <div style="padding: 0 42px;"><a class="btn-block1 m-t-20" id="next_step">完成</a></div>
        <div style="padding: 0 42px;text-align: center;" class="m-t-20"><a class="m-t-20 lms-link-1" style="text-align: center;" href="/passport/login-out">暂不完善，退出登录</a></div>
    </div>
@stop

@section('script')
    <script>
        var pageConfig = {
            address_id:'{{\Illuminate\Support\Facades\Request::input('address_id','')}}'
        }

        function addModAddress() {
            location.href = "/user/add-mod-address?callback=" + encodeURIComponent('/passport/fill-user-info?real_name='+encodeURIComponent($('input[name="real_name"]').val())+'&id_card=' + encodeURIComponent($('input[name="id_card"]').val())) + "&address_id=" + pageConfig.address_id;
        }

        $(function () {
            new SubmitButton({
                selectorStr:"#next_step",
                url:'/user/fill-user-info',
                prepositionJudge:function(){
                    if(!($('input[name="real_name"]').val())) {
                        mAlert('请输入姓名');
                        return;
                    }

                    if(!/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/.test($('input[name="id_card"]').val())){
                        mAlert('请输入正确的身份证号');
                        return;
                    };

//                    if(!(pageConfig.address_id > 0)) {
//                        mAlert('请添加收货地址');
//                        return;
//                    }

                    return true;
                },
                data:function()
                {
                    return $('#data_form').serialize();
                },
                redirectTo:'/user/index'
            });
        });
    </script>
@stop