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
    @include('segments.header',['headerTile'=>'提现申请'])
    <div class=" ">
        <form id="data_form">
            <input type="hidden" name="withdraw" value="{{$withdraw}}"/>
            <ul class="mui-table-view income-list">
                <li class="mui-table-view-cell">申请提现金额&nbsp;&nbsp;￥{{$withdraw}}</li>
                <li class="mui-table-view-cell">提现至<select name="withdraw_type"><option value="1">支付宝</option><option value="2">微信</option></select></li>
                <li class="mui-table-view-cell"><div id="withdraw_type_account1">支付宝账号:</div><div id="withdraw_type_account2">微信账号:</div><input name="account" type="text"/></li>
                <li class="mui-table-view-cell">辣木膳登录密码:<input name="password" type="password"/></li>
            </ul>
        </form>
    </div>

    <footer class="fix-bottom">
        <a class="btn-block1 remove-radius" href="javascript:void(0);" id="next_step">立即申请</a>
    </footer>
@stop

@section('script')
    <script>
        $(function(){
            $('select[name="withdraw_type"]').change(function(){
                if ( $(this).val() == 1 )
                {
                    $("#withdraw_type_account1").show();
                    $("#withdraw_type_account2").hide();
                }

                if ( $(this).val() == 2 )
                {
                    $("#withdraw_type_account1").hide();
                    $("#withdraw_type_account2").show();
                }

            });

            $('select[name="withdraw_type"]').change();
        });


        $(function () {
            new SubmitButton({
                selectorStr:"#next_step",
                url:'/user/withdraw-confirm',
                prepositionJudge:function(){
                    if( !$('input[name="account"]').val() ) {
                        mAlert('请填收款账号');
                        return;
                    }
                    return true;
                },
                callback:function(obj,data){
                    if( data.status ) {
                        location.href = "/user/withdraw-success";
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