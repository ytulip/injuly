@extends('_layout.master')
@section('title')
    <title>个人信息</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        footer .in-bl-line{line-height: 40px;}
        .info-item{width: 60%;}
        .opr-item{width: 40%;}

        .user-header-img {
            width: 34px;
            height: 34px;
            border-radius: 34px;
            overflow: hidden;
            display: inline-block;
            border: 1px solid #eeeeee;
        }

        .card-item{font-size: 14px;position: relative;}

        .card-item.navigate:after{
            right: 15px;
            content: '\e583';
            font-family: Muiicons;
            font-size: inherit;
            line-height: 1;
            position: absolute;
            top: 50%;
            display: inline-block;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            text-decoration: none;
            color: #bbb;
            -webkit-font-smoothing: antialiased;
        }

    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'个人设置'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/center"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">个人信息</span></div>
        <div class="cus-row-col-4 t-al-r"><a href="/user/check"></a></div>
    </div>


    <div class="cus-info-panel cus-info-panel-20">
        <div class="cus-info-panel-line" style="padding-top: 18px;padding-bottom: 18px;" onclick="goHref('/user/header-img')">
           <div class="cus-row cus-row-v-m">
               <div class="cus-row-col-3"><span class="fs-16-fc-212229">头像</span></div>
               <div class="cus-row-col-8 t-al-r"><img src="{{$user->header_img}}" style="width: 60px;height: 60px;border-radius: 60px;"/></div>
               <div class="cus-row-col-1 t-al-r">
                   <span class="next-icon"></span>
               </div>
           </div>
        </div>
    </div>


    <div class="cus-info-panel cus-info-panel-20" style="margin-top: 15px;">
        <div class="cus-info-panel-line inner-line" style="padding-top: 12px;padding-bottom: 12px;">
            <div class="cus-row cus-row-v-m" >
                <div class="cus-row-col-3"><span class="fs-16-fc-212229">姓名</span></div>
                <div class="cus-row-col-9 t-al-r"><span class="fs-16-fc-212229">{{$user->real_name}}</span></div>
            </div>
        </div>

        <div class="cus-info-panel-line inner-line" style="padding-top: 12px;padding-bottom: 12px;">
            <div class="cus-row cus-row-v-m" >
                <div class="cus-row-col-3"><span class="fs-16-fc-212229">ID</span></div>
                <div class="cus-row-col-9 t-al-r"><span class="fs-16-fc-212229">{{$user->id}}</span></div>

            </div>
        </div>

        <div class="cus-info-panel-line" style="padding-top: 12px;padding-bottom: 12px;">
            <div class="cus-row cus-row-v-m" >
                <div class="cus-row-col-3"><span class="fs-16-fc-212229">等级</span></div>
                <div class="cus-row-col-9 t-al-r"><span class="fs-16-fc-212229">{{\App\Model\User::levelText($user->vip_level)}}</span></div>

            </div>
        </div>
    </div>


    <div class="cus-info-panel cus-info-panel-20" style="margin-top: 15px;">
        <div class="cus-info-panel-line inner-line" style="padding-top: 12px;padding-bottom: 12px;">
            <div class="cus-row cus-row-v-m" >
                <div class="cus-row-col-3"><span class="fs-16-fc-212229">手机号</span></div>
                <div class="cus-row-col-9 t-al-r"><span class="fs-16-fc-212229">{{\App\Util\Kit::phoneHide($user->phone)}}</span></div>
            </div>
        </div>

        <div class="cus-info-panel-line inner-line" style="padding-top: 12px;padding-bottom: 12px;">
            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-3"><span class="fs-16-fc-212229">身份证号</span></div>
                <div class="cus-row-col-9 t-al-r"><span class="fs-16-fc-212229">{{\App\Util\Kit::phoneIdCard($user->id_card)}}</span></div>
            </div>
        </div>

        {{--<div class="cus-info-panel-line" style="padding-top: 12px;padding-bottom: 12px;">--}}
            {{--<div class="cus-row cus-row-v-m" style="padding-top: 12px;padding-bottom: 12px;">--}}
                {{--<div class="cus-row-col-3"><span class="fs-16-fc-212229">等级</span></div>--}}
                {{--<div class="cus-row-col-8 t-al-r"><span class="fs-16-fc-212229">{{\App\Model\User::levelText($user->vip_level)}}</span></div>--}}
                {{--<div class="cus-row-col-1 t-al-r">--}}
                    {{--<span class="next-icon"></span>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>


    {{--<div class="card-item m-t-10 list-mg-bottom navigate">--}}
        {{--<div class="mui-row">--}}
            {{--<div class="mui-col-sm-3 mui-col-xs-3">头像</div>--}}
            {{--<a class="mui-col-sm-8 mui-col-xs-8 t-al-r" href="/user/header-img"><img src="{{$user->header_img}}" class="user-header-img"/></a>--}}
            {{--<div class="mui-col-sm-1 mui-col-xs-1"></div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="card-item list-mg-bottom">--}}
    {{--<div class="mui-row">--}}
        {{--<div class="mui-col-sm-3 mui-col-xs-3">姓名</div>--}}
        {{--<div class="mui-col-sm-8 mui-col-xs-8 t-al-r small-a-plus">{{$user->real_name}}</div>--}}
        {{--<div class="mui-col-sm-1 mui-col-xs-1"></div>--}}
    {{--</div>--}}
    {{--</div>--}}

        {{--<div class="card-item list-mg-bottom">--}}
    {{--<div class="mui-row">--}}
        {{--<div class="mui-col-sm-3 mui-col-xs-3">用户ID</div>--}}
        {{--<div class="mui-col-sm-8 mui-col-xs-8 t-al-r small-a-plus">{{$user->id}}</div>--}}
        {{--<div class="mui-col-sm-1 mui-col-xs-1"></div>--}}
    {{--</div>--}}
    {{--</div>--}}

            {{--<div class="card-item m-b-10">--}}
    {{--<div class="mui-row">--}}
        {{--<div class="mui-col-sm-3 mui-col-xs-3">身份</div>--}}
        {{--<div class="mui-col-sm-8 mui-col-xs-8 t-al-r small-a-plus">{{\App\Model\User::levelText($user->vip_level)}}</div>--}}
        {{--<div class="mui-col-sm-1 mui-col-xs-1"></div>--}}
    {{--</div>--}}
    {{--</div>--}}


                {{--<div class="card-item list-mg-bottom">--}}
    {{--<div class="mui-row">--}}
        {{--<div class="mui-col-sm-3 mui-col-xs-3">手机号</div>--}}
        {{--<div class="mui-col-sm-8 mui-col-xs-8 t-al-r small-a-plus">{{\App\Util\Kit::phoneHide($user->phone)}}</div>--}}
        {{--<div class="mui-col-sm-1 mui-col-xs-1"></div>--}}
    {{--</div>--}}
    {{--</div>--}}

                    {{--<div class="card-item list-mg-bottom">--}}
    {{--<div class="mui-row">--}}
        {{--<div class="mui-col-sm-3 mui-col-xs-3">身份证号</div>--}}
        {{--<div class="mui-col-sm-8 mui-col-xs-8 t-al-r small-a-plus">{{\App\Util\Kit::phoneIdCard($user->id_card)}}</div>--}}
        {{--<div class="mui-col-sm-1 mui-col-xs-1"></div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<ul class="mui-table-view">--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/finance">资产管理</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/orders">购买管理</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/addresses">收货地址管理</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/setting">设置</a>--}}
        {{--</li>--}}
    {{--</ul>--}}


@stop

@section('script')
@stop