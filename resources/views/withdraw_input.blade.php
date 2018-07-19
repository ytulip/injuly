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
        <div class="cus-row-col-4"><a href="/user/finance"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">提现申请</span></div>
        <div class="cus-row-col-4 t-al-r"><span class="fs-16-fc-212229" style="line-height: 68px;" onclick="goNext()">下一步</span></div>
    </div>


    <div class="p-l-r-14">
        <div style="border:1px solid #EBEAEA;background-color: #ffffff">
            <div class="cus-row cus-row-bborder">
                <div class="cus-row-col-6 " style="padding:0 16px;"><span class="fs-14-fc-212229" style="line-height: 46px;">可提金额</span></div>
                <div class="cus-row-col-6 t-al-r" style="padding:0 16px;"><span class="fs-14-fc-212229" style="line-height: 46px;">{{$user->charge}}</span></div>
            </div>

            <div class="cus-row">
                <div class="cus-row-col-12 " style="padding:0 16px;"><span class="fs-14-fc-212229" style="line-height: 46px;">提现金额</span></div>

                <div style="padding:8px 16px 39px 16px;"><input name="withdraw" class="fs-36-fc-212229" style="display: inline-block;width: 100%;text-align: center;border: none;padding: 0;" placeholder="0.00" type="number" /></div>
            </div>
        </div>

        <div style="border:1px solid #EBEAEA;background-color: #ffffff;margin-top: 14px;">
            <div class="cus-row cus-row-bborder">
                <div class="cus-row-col-6 " style="padding:0 16px;"><span class="fs-14-fc-212229" style="line-height: 46px;">提现至</span></div>
                <div class="cus-row-col-6 t-al-r" style="padding:0 16px;position: relative;"><select name="withdraw_type" style="padding: 0;margin: 0;line-height: 46px;    direction: rtl;padding-right: 16px;"><option value="1">支付宝</option><option value="2">微信</option></select>
                    <span style="position: absolute;top:50%;right: 16px;transform: translateY(-50%)"><i class="next-icon" style="transform:  rotate(90deg);"></i></span>
                </div>
            </div>

            <div class="cus-row">
                <div class="cus-row-col-12 " style="padding:0 12px;">
                    <input class="fs-16-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;text-align: center;" name="account" placeholder="请正确输入提现账号"/>
                </div>
            </div>

        </div>


        <div style="margin-top: 12px;margin-bottom: 30px;"> <span><i class="attention-icon"></i></span><span class="fs-12-fc-212229">每月两次提现机会，每次提现不得少于1000元</span></div>
    </div>
@stop

@section('script')
    <script>

        //    function nextStep()
        //    {
        //
        //    }

        function goNext()
        {
            $withdraw = parseFloat($('input[name="withdraw"]').val()).toFixed(2);
            if (isNaN($withdraw) || $withdraw < 1000) {
                mAlert('请输入正确的金额');
                return;
            }

            if( !$('input[name="account"]').val() ) {
                mAlert('请填收款账号');
                return;
            }

            location.href = "/user/withdraw-confirm?withdraw=" + $withdraw + "&withdraw_type="+$('select[name="withdraw_type"]').val()+"&account=" + $('input[name="account"]').val();
        }

    </script>
@stop