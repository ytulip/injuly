@extends('_layout.master')
@section('title')
    <title>购买记录</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
        .order-item{background-color: #ffffff;padding: 15px;}

        .income-list{font-size: 12px;}
        .mui-table-view{background-color: inherit;}
        .mui-table-view:before{display: none;}
        .mui-table-view:after{display: none;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买记录'])--}}

    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/center"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">购买管理</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div class="mui-table-view income-list">
        @if(count($orders))
        @foreach($orders as $order)
            <li style="padding: 22px;margin-bottom: 14px;background-color: #ffffff;border-top: 1px solid #ebeaea;border-bottom:1px solid #ebeaea;" onclick="goDetail({{$order->order_id}})">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-6"><span class="fs-12-fc-909094">{{date('Y-m-d',strtotime($order->order_created_at))}}</span></div>
                    <div class="cus-row-col-6 t-al-r"><span class="fs-12-fc-909094">{{\App\Model\Order::orderStatusText($order->order_status)}}</span></div>
                </div>
                <div class="cus-row" style="margin-top: 6px;"><span class="fs-14-fc-212229">邀请高级会员</span></div>
                <div class="cus-row cus-row-v-m" style="margin-top: 6px;">
                    <div class="cus-row-col-6">
                        <span class="fs-14-fc-212229">购买数量: {{$order->quantity}}盒</span>
                    </div>
                    <div class="cus-row-col-6 t-al-r">
                        <span>实付款</span><span class="fs-14-fc-f89a03">￥{{$order->need_pay}}</span>
                    </div>
                </div>
            </li>
            {{--<div class="order-item list-mg-bottom" onclick="goDetail({{$order->order_id}})">--}}
                {{--<div class="mui-row">--}}
                    {{--<div class="mui-col-sm-3 mui-col-xs-3 small-a">{{date('Y-m-d',strtotime($order->order_created_at))}}</div>--}}
                    {{--<div class="mui-col-sm-6 mui-col-xs-6"></div>--}}
                    {{--<div class="mui-col-sm-3 mui-col-xs-3 small-a t-al-r">{{\App\Model\Order::orderStatusText($order->order_status)}}</div>--}}
                {{--</div>--}}
                {{--<div class="mui-row">--}}
                    {{--<div class="mui-col-sm-3 mui-col-xs-3">--}}
                        {{--<img src="{{$order->cover_image}}" style="width: 64px;height: 64px;border-radius: 8px;display: inline-block"/>--}}
                    {{--</div>--}}
                    {{--<div class="mui-col-sm-5 mui-col-xs-5">--}}
                        {{--<h3 style="font-size: 16px;">{{$order->attr_des}}</h3>--}}
                        {{--<p style="margin-top: 21px;" class="small-a-black">购买数量:{{$order->quantity}}盒</p>--}}
                    {{--</div>--}}
                    {{--<div class="mui-col-sm-4 mui-col-xs-4 small-a-black t-al-r" style="margin-top: 43px;">￥{{$order->need_pay}}</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            @endforeach
            @else
            <div class="t-al-c" style="margin-top: 80px;"><img src="/images/icon_img.png" style="display: inline-block;"/></div>
            <div class="t-al-c" style="margin-top: 24px;"><span style="font-size: 16px;color:#a8a8a8;">暂无内容</span></div>
        @endif
    </div>

@stop

@section('script')
    <script>
        function goDetail(orderId)
        {
            location.href = '/user/order-detail?order_id=' + orderId;
        }
    </script>
@stop