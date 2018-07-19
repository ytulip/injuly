@extends('_layout.master')
@section('title')
    <title>邀请码</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
        .order-item{background-color: #ffffff;padding: 20px 17px;border-top: 1px solid #EBEAEA;border-bottom: 1px solid #ebeaea;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买记录'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/center"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">邀请码</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div class="m-t-20">
        {{--<div class="order-item list-mg-bottom">--}}
            {{--<div class="mui-row">--}}
                {{--<div class="mui-col-sm-4 mui-col-xs-4">邀请码</div>--}}
                {{--<div class="mui-col-sm-4 mui-col-xs-4">邀请模式</div>--}}
                {{--<div class="mui-col-sm-4 mui-col-xs-4">使用情况</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        @if(count($invited_list))
        @foreach($invited_list as $item)
            <div class="order-item list-mg-bottom" style="margin-bottom: 15px;">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-6"><span class="fs-24-fc-212229" @if($item->code_status) style="color:#909094;" @endif>{{$item->invited_code}}</span><br/><span class="fs-14-fc-212229" style="margin-top: 4px;display: inline-block;@if($item->code_status)color:#909094;@endif">高级会员</span></div>
                    {{--<div class="mui-col-sm-4 mui-col-xs-4">{{\App\Model\ProductAttr::find($item->product_attr_id)->attr_des}}</div>--}}
                    <div class="cus-row-col-6 t-al-r">{!!  $item->code_status?'<span class="fs-14-fc-909094">已使用</span>':'<span class="fs-14-fc-98CC3D">未使用</span>'!!}</div>
                </div>
            </div>
        @endforeach
            @else
            <div class="t-al-c" style="margin-top: 80px;"><img src="/images/icon_img.png" style="display: inline-block;"/></div>
            <div class="t-al-c" style="margin-top: 24px;"><span style="font-size: 16px;color:#a8a8a8;">暂无内容</span></div>
        @endif
    </div>

@stop