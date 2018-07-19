@extends('_layout.master')
@section('title')
    <title>开发记录</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        .item-footer{margin-top: 4px;}
        .item-header,.item-footer{background-color: #ffffff;padding: 10px;}
        .income-list{font-size: 12px;}
        .mui-table-view{background-color: inherit;}
        .mui-table-view:before{display: none;}
        .mui-table-view:after{display: none;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'开发记录'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/finance"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">开发记录</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div class=" ">
        <ul class="mui-table-view income-list">
            @if(count($list))
            @foreach($list as $key=>$item)
                <li style="padding: 22px;margin-bottom: 14px;background-color: #ffffff;border-top: 1px solid #ebeaea;border-bottom:1px solid #ebeaea;">
                    <div class="cus-row cus-row-v-m">
                        <div class="cus-row-col-6"><span class="fs-16-fc-212229">{{$item->real_name}} {{\App\Util\Kit::phoneHide($item->phone)}}</span></div>
                        <div class="cus-row-col-6 t-al-r"><span class="fs-12-fc-909094">@if($item->sub_cash_type == 1) 直接开发 @else 间接开发 @endif</span></div>
                    </div>
                    <div class="cus-row" style="margin-top: 6px;"><span class="fs-14-fc-212229">高级会员</span></div>
                    <div class="cus-row cus-row-v-m" style="margin-top: 6px;">
                        <div class="cus-row-col-6">
                            <span class="fs-14-fc-212229">开发时间 {{date('Y-m-d H:i',strtotime($item->created_at))}}</span>
                        </div>
                        <div class="cus-row-col-6 t-al-r">
                            <span>获利</span><span class="fs-14-fc-f89a03">{{$item->price}}</span>
                        </div>
                    </div>
                </li>
                {{--<li class="mui-table-view-cell">--}}
                    {{--<a class="mui-navigate mui-row">--}}
                        {{--<div class="mui-col-sm-3 mui-col-xs-3">@if($item->sub_cash_type == 1) 直 @else 间 @endif <br/> @if($item->sub_cash_type == 1) vip会员 @else 高级会员 @endif </div>--}}
                        {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$item->real_name}}<br/>{{$item->phone}}</div>--}}
                        {{--<div class="mui-col-sm-3 mui-col-xs-3">{{date('Y-m-d',strtotime($item->created_at))}}<br/>{{date('H:i',strtotime($item->created_at))}}</div>--}}
                        {{--<div class="mui-col-sm-3 mui-col-xs-3">￥{{$item->price}}</div>--}}

                    {{--</a>--}}
                {{--</li>--}}
            @endforeach
                @else
                <div class="t-al-c" style="margin-top: 80px;"><img src="/images/icon_img.png" style="display: inline-block;"/></div>
                <div class="t-al-c" style="margin-top: 24px;"><span style="font-size: 16px;color:#a8a8a8;">暂无内容</span></div>
            @endif
        </ul>
    </div>
@stop

@section('script')
@stop