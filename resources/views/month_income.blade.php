@extends('_layout.master')
@section('title')
    <title>本月收入</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        footer .in-bl-line{line-height: 40px;}
        .info-item{width: 60%;}
        .opr-item{width: 40%;}

        .graph-wrap{padding: 30px;}
        .graph-panel{width: 100%;background-color: #ffffff;position: relative;}
        .user-header-img{position: absolute;width: 38px;height: 38px;border-radius: 38px;overflow: hidden;display: inline-block;top:0;left:50%;transform: translate(-50%,-50%);-webkit-transform:translate(-50%,-50%); }
        .user-header-img img{width: 100%;border-radius: 38px;}
        .graph-here{padding-top: 50px;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'本月收入'])--}}
    <div class="cus-row p-l-r-14" style="background-color: #F89A03;">
        <div class="cus-row-col-4"><a href="/user/finance"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;color:#ffffff !important;">本月收入</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div style="background-color: #F89A03;padding: 40px 0;">
        <p style="text-align: center"><span style="font-size: 48px;
color: #FFFFFF;">{{\App\Util\Kit::priceFormat($user->monthIncome())}}</span></p>
    </div>


    {{--<ul class="mui-table-view">--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate" href="/user/month-income">本月收入{{$user->monthIncome()}}</a>--}}
        {{--</li>--}}

        {{--<li class="mui-table-view-cell mui-row">--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">直接开发</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$monthIncomeDetail['direct_vip']['count']}}个</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$monthIncomeDetail['direct_master']['count']}}个</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">￥{{$monthIncomeDetail['direct_vip']['price'] + $monthIncomeDetail['direct_master']['price']}}</div>--}}
        {{--</li>--}}

        {{--<li class="mui-table-view-cell mui-row">--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">间接开发</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$monthIncomeDetail['indirect_vip']['count']}}个</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$monthIncomeDetail['indirect_master']['count']}}个</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">￥{{$monthIncomeDetail['indirect_vip']['price'] + $monthIncomeDetail['indirect_master']['price']}}</div>--}}
        {{--</li>--}}

        {{--<li class="mui-table-view-cell mui-row">--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">一代辅导</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3"></div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$monthIncomeDetail['up']['count']}}个</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">￥{{$monthIncomeDetail['up']['price']}}</div>--}}
        {{--</li>--}}

        {{--<li class="mui-table-view-cell mui-row">--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">二代辅导</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3"></div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">{{$monthIncomeDetail['super']['count']}}个</div>--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">￥{{$monthIncomeDetail['super']['price']}}</div>--}}
        {{--</li>--}}

    {{--</ul>--}}

    <div class="card-item" style="height: 55px;margin-top: 15px;border-top: 1px solid #EBEAEA;" onclick="goHref('/user/direct-indirect-record')">
        <div style="margin-right: 8px;width: 25px;height: 25px;display: inline-block;"><img src="/images/icon_star1@2x.png" style="width: 25px;"/></div>
        <div class="fs-16-fc-212229" style="line-height: 25px;display: inline-block;vertical-align: top;">开发收入</div>
        <div style="float: right;    transform: translateY(3px);"><span><i class="next-icon"></i></span></div>
    </div>

    <div style="border-bottom: 1px solid #EBEAEA;"></div>

    <div class="card-item">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-6"><span class="fs-16-fc-212229">直接开发</span></div>
            <div class="cus-row-col-6 t-al-r"><span class="fs-16-fc-f89a03">￥{{$monthIncomeDetail['direct_vip']['price'] + $monthIncomeDetail['direct_master']['price']}}</span><br/><span class="fs-12-fc-212229">高级会员{{$monthIncomeDetail['direct_master']['count']}}个</span></div>
        </div>
    </div>

    <div style="padding-left: 15px;background-color: #ffffff">
        <div style="border-bottom: 1px solid #EBEAEA;"></div>
    </div>


    <div class="card-item" style="border-bottom: 1px solid #EBEAEA;">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-6"><span class="fs-16-fc-212229">间接开发</span></div>
            <div class="cus-row-col-6 t-al-r"><span class="fs-16-fc-f89a03">￥{{$monthIncomeDetail['indirect_vip']['price'] + $monthIncomeDetail['indirect_master']['price']}}</span><br/><span class="fs-12-fc-212229">高级会员{{$monthIncomeDetail['indirect_master']['count']}}个</span></div>
        </div>
    </div>



    <div class="card-item" style="height: 55px;margin-top: 15px;border-top: 1px solid #EBEAEA;" onclick="goHref('/user/up-super-record')">
        <div style="margin-right: 8px;width: 25px;height: 25px;display: inline-block;"><img src="/images/icon_star2@2x.png" style="width: 25px;"/></div>
        <div class="fs-16-fc-212229" style="line-height: 25px;display: inline-block;vertical-align: top;">辅导收入</div>
        <div style="float: right;    transform: translateY(3px);"><span><i class="next-icon"></i></span></div>
    </div>

    <div style="border-bottom: 1px solid #EBEAEA;"></div>

    <div class="card-item">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-6"><span class="fs-16-fc-212229">一代辅导</span></div>
            <div class="cus-row-col-6 t-al-r"><span class="fs-16-fc-f89a03">￥{{$monthIncomeDetail['up']['price']}}</span><br/><span class="fs-12-fc-212229">{{$monthIncomeDetail['up']['count']}}个</span></div>
        </div>
    </div>

    <div style="padding-left: 15px;background-color: #ffffff">
        <div style="border-bottom: 1px solid #EBEAEA;"></div>
    </div>

    <div class="card-item" style="border-bottom: 1px solid #EBEAEA;">
        <div class="cus-row cus-row-v-m">
            <div class="cus-row-col-6"><span class="fs-16-fc-212229">二代辅导</span></div>
            <div class="cus-row-col-6 t-al-r"><span class="fs-16-fc-f89a03">￥{{$monthIncomeDetail['super']['price']}}</span><br/><span class="fs-12-fc-212229">{{$monthIncomeDetail['super']['count']}}个</span></div>
        </div>
    </div>
@stop

@section('script')
@stop