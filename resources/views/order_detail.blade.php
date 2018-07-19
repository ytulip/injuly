@extends('_layout.master')
@section('title')
    <title>购买详情</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
        .order-item{background-color: #ffffff;padding: 15px;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买详情'])--}}

    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/orders"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">购买详情</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div style="border-top:1px solid #EBEAEA;background-color: #ffffff;border-bottom:1px solid #EBEAEA;">
        <div class="cus-row cus-row-bborder cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">订单编号</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">{{$order->id}}</span></div>
        </div>

        <div class="cus-row @if(!$order->isRebuy()) cus-row-bborder @endif cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">订单模式</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">{{$order->buyTypeText()}}</span></div>
        </div>

        @if( $order->buy_type == 1)
        <div class="cus-row cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">直接开发者</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">{{$direct->real_name}} {{\App\Util\Kit::phoneHide($direct->phone)}}</span></div>
        </div>
            @endif
    </div>


    @if(!$order->isRebuy())
    <div style="border-top:1px solid #EBEAEA;background-color: #ffffff;border-bottom:1px solid #EBEAEA;margin-top: 14px;">
        <div class="cus-row cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">邀请码</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">{{$order->getInvitedCode()}} </span><span class="fs-14-fc-98CC3D">{{$order->isUsedInvited()?'已使用':'未使用'}}</span></div>
        </div>
    </div>
    @endif


    <div style="border-top:1px solid #EBEAEA;background-color: #ffffff;border-bottom:1px solid #EBEAEA;margin-top: 14px;">
        <div class="cus-row cus-row-bborder cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">支付时间</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">{{date('Y-m-d H:i',strtotime($order->pay_time))}}</span></div>
        </div>

        <div class="cus-row cus-row-bborder cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">支付方式</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">{{\App\Model\CashStream::payTypeText($order->pay_type)}}</span></div>
        </div>

        <div class="cus-row cus-row-v-t" style="padding-left: 20px;">
            <div class="cus-row-col-3 t-al-l"><span class="fs-14-fc-212229" style="line-height: 46px;">实付款</span></div>
            <div class="cus-row-col-9 fs-14-fc-212229 t-al-r" style="line-height: 46px;padding-right: 20px; "><span class="fs-14-fc-212229">￥{{$order->need_pay}}</span></div>
        </div>
    </div>

    @if($order->deliver_type == \App\Model\Deliver::SELF_GET)
    <div class="cus-info-panel cus-info-panel-20" style="margin-top: 14px;">
        <div class="cus-info-panel-line">
            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-3">
                    <span class="fs-14-fc-212229" style="line-height: 46px;">自提信息</span>
                </div>
                <div class="cus-row-col-9 t-al-r" style="padding-top: 13px;padding-bottom: 13px;">
                    <span class="fs-12-fc-212229">{{$order->address_name}}  {{$order->address_phone}}</span><br/>
                    <span class="fs-12-fc-212229">{{$order->address}}</span>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="cus-info-panel cus-info-panel-20" style="margin-top: 14px;">
            <div class="cus-info-panel-line inner-line">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-3">
                        <span class="fs-14-fc-212229" style="line-height: 46px;">收货信息</span>
                    </div>
                    <div class="cus-row-col-9 t-al-r" style="padding-top: 13px;padding-bottom: 13px;">
                        <span class="fs-12-fc-212229">{{$order->address_name}}  {{$order->address_phone}}</span><br/>
                        <span class="fs-12-fc-212229">{{$order->address}}</span>
                    </div>
                </div>
            </div>

            <div class="cus-info-panel-line">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-3">
                        <span class="fs-14-fc-212229" style="line-height: 46px;">物流信息</span>
                    </div>
                    <div class="cus-row-col-9 t-al-r" style="padding-top: 13px;padding-bottom: 13px;">
                        <span class="fs-12-fc-212229">{{$order->deliver_compnay_name}}</span><br/>
                        <span class="fs-12-fc-212229">{{$order->deliver_number}}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif


    {{--<div class="m-t-20">--}}
        {{--@if($order->deliver_type == \App\Model\Deliver::SELF_GET)--}}
            {{--<div class="mui-row order-item">--}}
                {{--<div class="mui-col-sm-3 mui-col-xs-3">自提信息:</div>--}}
                {{--<div class="mui-col-sm-9 mui-col-xs-9 t-al-r">{{$order->address_name}}&nbsp;&nbsp;&nbsp;&nbsp;{{$order->address_phone}}<br/>{{$order->address}}</div>--}}
            {{--</div>--}}
            {{--@else--}}
            {{--<div class="mui-row order-item">--}}
                {{--<div>收货信息:</div>--}}
                {{--<div class="mui-col-sm-9 mui-col-xs-9 t-al-r">{{$order->address_name}}&nbsp;&nbsp;&nbsp;&nbsp;{{$order->address_phone}}<br/>{{$order->address}}</div>--}}
            {{--</div>--}}
            {{--<div class="mui-row order-item">--}}
                {{--<div>物流信息:</div>--}}
                {{--<div></div>--}}
            {{--</div>--}}
            {{--@endif--}}
    {{--</div>--}}

@stop

@section('script')
@stop