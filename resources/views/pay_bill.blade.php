@extends('_layout.master')
@section('title')
    <title>购买</title>
@stop
@section('style')
    <style>

        html,body{background-color: #f8f8f8;}
        /*footer .in-bl-line{line-height: 40px;}*/
        .info-item{width: 60%;}
        .opr-item{width: 40%;}
        .btn-bg-f5{background-color: #f5a623;}

    </style>
@stop
@section('container')
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/report-bill?product_id=1"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">购买</span></div>
        <div class="cus-row-col-4 t-al-r"><a href="tel:{{$product->consumer_service_phone}}"><span class="fs-16-fc-212229">客服</span></a></div>
    </div>

    <form id="form_data" style="display: none;">
        <input name="order_id" value="{{\Illuminate\Support\Facades\Request::input('order_id')}}" style="display: none;"/>
        <p style="padding-top: 30px;padding-left: 15px;">支付方式:</p>
        <select name="pay_type">
            {{--<option value="1">微信</option>--}}
            {{--<option value="2">支付宝</option>--}}
            <option value="3">余额</option>
        </select>
    </form>


    <div style="border-top:1px solid #EBEAEA;background-color: #ffffff">
        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">购买方式</span></div>
            <div class="cus-row-col-9" style="vertical-align: top;">
                {{--<input class="fs-14-fc-909094" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" readonly value="" placeholder="请选择购买数量"/>--}}
                {{--{!! \App\Model\SyncModel::productAttr('product_attr_id',false,\Illuminate\Support\Facades\Request::input('product_id'))!!}--}}
                {{--<select class="cus-select" style="margin: 0;display: inline-block;padding: 10px 15px;height: 46px;">--}}
                {{--<option value="">请选择购买数量</option>--}}
                {{--</select>--}}
                <input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" name="product_id" readonly value="@if($order->buy_type==1)邀请新会员@else复购@endif" onfocus='this.blur();' placeholder="请选择购买数量"/>

            </div>
            {{--<div class="cus-row-col-1" style="line-height: 44px;">--}}
                {{--<span><i class="next-icon"></i></span>--}}
            {{--</div>--}}
        </div>

        @if($order->buy_type == 1)
        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">直接开发者</span></div>
            <div class="cus-row-col-9"><input class="fs-14-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;" value="{{\App\Model\User::find($order->immediate_user_id)->phone}}" placeholder="请填写直接开发者手机号" readonly onfocus='this.blur();' name="immediate_phone"/></div>
        </div>
            @else
            <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
                <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">购买数量</span></div>
                <div class="cus-row-col-9"><input class="fs-14-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;" value="{{$order->quantity}}" placeholder="" readonly onfocus='this.blur();' name="immediate_phone"/>
                </div>
            </div>
        @endif

        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">收货方式</span></div>
            <div class="cus-row-col-9" style="vertical-align: top;">
                <input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" name="deliver_type" readonly value="{{\App\Model\Deliver::deliverTypeText($order->deliver_type)}}" onfocus='this.blur();' placeholder="请选择收货方式"/>
                {{--<select class="cus-select" style="margin: 0;display: inline-block;padding: 10px 15px;height: 46px;">--}}
                {{--<option value="">请选择收货方式</option>--}}
                {{--<option value="1">自提</option>--}}
                {{--<option value="2">送货上门</option>--}}
                {{--</select>--}}
            </div>
            {{--<div class="cus-row-col-1" style="line-height: 44px;">--}}
                {{--<span><i class="next-icon"></i></span>--}}
            {{--</div>--}}
        </div>

        <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
            <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">收货地址</span></div>
            <div class="cus-row-col-9"><input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;text-overflow: ellipsis;" readonly value="{{$order->address}}" onfocus='this.blur();' placeholder="请选择收货地址" name="address-show" /></div>
            {{--<div class="cus-row-col-1" style="line-height: 44px;">--}}
                {{--<span><i class="next-icon"></i></span>--}}
            {{--</div>--}}
        </div>


        {{--<div class="cus-row" style="padding-left: 16px;">--}}
        {{--<div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">验证</span></div>--}}
        {{--<div class="cus-row-col-6"><input name="withdraw_sms_code" class="fs-16-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;"/></div>--}}
        {{--<div class="cus-row-col-3"><a class="get-code-btn" href="javascript:void(0)"><span style="display: inline-block;line-height: 44px;color:#2966E2;">获取验证码</span></a></div>--}}
        {{--</div>--}}
        {{--<div class="cus-input-row fs-16-fc-212229"><label>姓名</label><input/></div>--}}
        {{--<div class="cus-input-row fs-16-fc-212229"><label>手机</label><input/></div>--}}
        {{--<div class="cus-input-row"><label>姓名</label><input/></div>--}}
        {{--<div style="border-top:1px solid #EBEAEA;padding: 24px 28px;">--}}
        {{--<a class="btn-block1" href="javascript:void(0);" id="next_step">提交申请</a>--}}
        {{--</div>--}}
    </div>

    <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-6">
                <span class="fs-24-fc-212229">￥</span><span class="fs-24-fc-212229" id="price_label">{{number_format($order->need_pay,2)}}</span>
            </div>
            <div class="cus-row-col-6">
                <a class="btn-block1 m-t-20" href="javascript:void(0)"  style="margin-top: 0;" onclick="switchPay(1)">立即付款</a>
            </div>
        </div>

        <button style="display: none;" id="next_step"></button>
        <button style="display: none;" id="wechatpay"></button>
        {{--<div class="in-bl-line">--}}
            {{--<div class="in-bl-line-item info-item"><a class="btn-block1 remove-radius btn-bg-f5">合计:￥{{number_format($order->need_pay,2)}}</a></div>--}}
            {{--<div class="in-bl-line-item opr-item" onclick="switchPay(1)"><a class="btn-block1 remove-radius">立即支付</a></div>--}}
            {{--<button style="display: none;" id="next_step"></button>--}}
        {{--</div>--}}
    </footer>

    <div style="position: fixed;bottom: 71px;left:0;right: 0;top:0;background-color: rgba(33,34,41,.2);display: none;" id="payPanel">
        <div style="position: absolute;bottom: 0;left:0;right: 0;background-color: #ffffff;padding: 0 17px;">
            <div class="cus-row cus-row-v-m" style="border-bottom: 1px solid #ebe9e9;">
                <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="line-height: 47px;">选择付款方式</span></div>
                <div class="cus-row-col-6 t-al-r"><span class="close-icon" onclick="switchPay(0)"></span></div>
            </div>

            <div class="cus-row" onclick="javascript:$('#next_step').click()">
                <div class="in-bl-v-m" style="padding: 0 16px;"><span class="lmspay-icon"></span></div>
                <div class="in-bl-v-m"><span class="fs-16-fc-212229" style="line-height: 72px;" >辣木膳支付</span></div>
                <div class="in-bl-v-m" style="float: right;margin-top: 27.5px;"><span class="fs-16-fc-212229" >￥{{$user->charge}}</span></div>
            </div>

            <div class="cus-row" onclick="javascript:$('#wechatpay').click()">
                <div class="in-bl-v-m" style="padding: 0 16px;"><span class="wechat-icon"></span></div>
                <div class="in-bl-v-m"><span class="fs-16-fc-212229" style="line-height: 72px;" >微信支付</span></div>
            </div>

            <div class="cus-row" onclick="javascript:alipay()">
                <div class="in-bl-v-m" style="padding: 0 16px;"><span class="alipay-icon"></span></div>
                <div class="in-bl-v-m"><span class="fs-16-fc-212229" style="line-height: 72px;">支付宝支付</span></div>
            </div>


        </div>
    </div>
@stop

@section('script')
    <script>

        var pageConfig =
            {
                isWechat:{{\App\Util\Kit::isWechat()?'true':'false'}}
            }

        new SubmitButton({
            selectorStr:"#next_step",
            url:"/user/order-pay",
            callback:function(obj,data){
                if(data.status) {
                    location.href = "/order/report-success?order_id=" + {{\Illuminate\Support\Facades\Request::input("order_id")}};
                } else {
                    mAlert(data.desc);
                }
            },
            data:function()
            {
                return $("#form_data").serialize();
            }
        });


        new SubmitButton({
            selectorStr:"#wechatpay",
            prepositionJudge:function(){
                if(pageConfig.isWechat) {
                    location.href = "/user/order-pay?" + $("#form_data").serialize() + "&pay_type=1" ;
                    return false;
                }
                return true;
            },
            url:"/user/order-pay",
            callback:function(obj,data){
                if(data.status) {
                    {{--location.href = "/order/report-success?order_id=" + {{\Illuminate\Support\Facades\Request::input("order_id")}};--}}
                    location.href = data.data.mweb_url;
                } else {
                    mAlert(data.desc);
                }
            },
            data:function()
            {
                var formData = $("#form_data").serializeJSON();
                formData['pay_type'] = 1;
                return formData;
//                return $("#form_data").serialize();
            }
        });

        function switchPay(a)
        {
            if ( a  ){
                $('#payPanel').show();
            } else {
                $('#payPanel').hide();
            }
        }

        function alipay()
        {
            update_submit('/user/order-pay',{order_id:$('input[name="order_id"]').val(),pay_type:2},'post');
        }

//        function wechatpay()
//        {
//            update_submit('/user/order-pay',{order_id:$('input[name="order_id"]').val(),pay_type:1},'post');
//        }
    </script>
@stop