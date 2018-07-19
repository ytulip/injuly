@extends('_layout.master')
@section('title')
    <title>个人中心</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        footer .in-bl-line{line-height: 40px;}
        .info-item{width: 60%;}
        .opr-item{width: 40%;}

        .user-header-img {
            width: 54px;
            height: 54px;
            border-radius: 54px;
            overflow: hidden;
            display: inline-block;
            border: 1px solid #eeeeee;
        }

        .setting-icon{
            display: inline-block;
            width: 19px;
            height: 19px;
            background: url('/images/user_icon_set@2x.png') no-repeat;
            background-size:19px 19px;
            vertical-align: middle;
            margin-right: 26px;
        }

        .address-icon{
            display: inline-block;
            width: 18px;
            height: 20px;
            background: url('/images/user_icon_pin@2x.png') no-repeat;
            background-size:18px 20px;
            vertical-align: middle;
            margin-right: 27px;
        }

        .bill-icon{
            display: inline-block;
            width: 15px;
            height: 18px;
            background: url('/images/user_icon_manage@2x.png') no-repeat;
            background-size:15px 18px;
            vertical-align: middle;
            margin-right: 30px;
        }

        .fin-icon{
            display: inline-block;
            width: 18px;
            height: 18px;
            background: url('/images/user_icon_fi@2x.png') no-repeat;
            background-size:18px 18px;
            vertical-align: middle;
            margin-right: 26px;
        }

        .mui-table-view-cell {
            position: relative;
            overflow: hidden;
            padding: 12px 15px;
            -webkit-touch-callout: none;
        }



    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'个人中心'])--}}

    <div style="background-image: linear-gradient(-215deg, #C8E859 0%, #A8D73D 46%, #97CD2D 71%);padding-bottom: 94px;">
        <div class="cus-row p-l-r-14">
            <div class="cus-row-col-4"><a href="/user/index"><i class="back-icon"></i></a></div>
            <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">个人中心</span></div>
            <div class="cus-row-col-4 t-al-r"></div>
        </div>

        <div style="position: absolute;background: #FFFFFF;
border: 1px solid #EBE9E9;
box-shadow: 0 0 4px 0 rgba(0,0,0,0.09);
border-radius: 5px;top:68px;left:14px;right:14px;height: 188px;z-index: 99; " onclick="goHref('/user/info')">
            <div style="margin-top: 25px;text-align: center;"><img src="{{$user->header_img}}" style="width: 74px;height: 74px;border-radius: 74px;display: inline-block"/></div>
            <div style="margin-top: 12px" class="t-al-c"><span class="fs-16-fc-212229">{{$user->real_name}}</span></div>
            <div class="t-al-c"><span class="vip-icon in-bl-v-m"></span><span class="fs-14-fc-f89a03 in-bl-v-m">高级会员</span></div>

            <div style="position: absolute;top:50%;right: 13px;transform: translateY(-50%)"><span class="next-icon"></span></div>
        </div>
    </div>



    {{--<div style="overflow: hidden" class="card-item list-mg-bottom list-mg-top navigate" href="/user/info" onclick="goHref('/user/info')">--}}
        {{--<div class="mui-row">--}}

                {{--<div class="user-header-img"><img src="{{$user->header_img}}"/></div>--}}
                {{--<div class="in-bl" style="vertical-align: top;margin-left: 8px;">{{$user->real_name}}<br/><span style=" background-color: #3a90b8;color: #ffffff;line-height: 20px;font-size: 12px;border-radius: 16px;padding: 0 8px;display: inline-block;">{{\App\Model\User::levelText($user->vip_level)}}</span></div>--}}

        {{--</div>--}}
    {{--</div>--}}

    <div class="card-item list-mg-bottom" style="margin-top: 124px;border-top: 1px solid #EBE9E9;border-bottom: 1px solid #EBE9E9;">
        <div>
            <span class="in-bl-v-m"><img src="/images/user_icon_gift@2x.png" style="width: 22px;"/></span>
            <a href="/user/invited-list" class="in-bl-v-m"><span class="fs-14-fc-212229">你有</span><span class="fs-14-fc-f89a03">{{$user->validInvitedCodeCount()}}个邀请码</span><span class="fs-14-fc-212229">未使用，邀请会员，双方获利</span></a></div>
    </div>

    <ul class="mui-table-view" style="margin-top: 14px;">
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/finance"><span class="in-bl-v-m"><img src="/images/user_icon_fi@2x.png" style="width: 18px;"/></span><span class="in-bl-v-m">资产管理</span><span class="in-bl-fl-r">￥{{number_format($user->charge + $user->charge_frozen,2)}}</span></a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<span><img src="/images/user_icon_manage@2x.png" style="width: 15px;"/></span>--}}
            {{--<a class="mui-navigate-right" href="/user/orders">购买管理<span class="in-bl-fl-r">{{$user->countOrder()}}</span></a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<span><img src="/images/user_icon_pin@2x.png" style="width: 18px;"/></span>--}}
            {{--<a class="mui-navigate-right" href="/user/addresses">收货地址管理</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<span><img src="/images/user_icon_set@2x.png" style="width: 19px;"/></span>--}}
            {{--<a class="mui-navigate-right" href="/user/setting">设置</a>--}}
        {{--</li>--}}
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="/user/finance"><i class="fin-icon"></i><span class="fs-16-fc-212229 in-bl-v-m">资产管理</span></a>
        </li>
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="/user/orders"><i class="bill-icon"></i><span class="fs-16-fc-212229 in-bl-v-m">购买管理</span></a>
        </li>
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="/user/addresses"><i class="address-icon"></i><span class="fs-16-fc-212229 in-bl-v-m">收货地址管理</span></a>
        </li>
    </ul>

    <ul class="mui-table-view" style="margin-top: 14px;">
    <li class="mui-table-view-cell">
        <a class="mui-navigate-right" href="/user/setting"><i class="setting-icon"></i><span class="fs-16-fc-212229 in-bl-v-m">设置</span></a>
    </li>
    </ul>


@stop

@section('script')
@stop