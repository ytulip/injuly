@extends('_layout.master')
@section('title')
    <title>设置</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        footer .in-bl-line{line-height: 40px;}
        .info-item{width: 60%;}
        .opr-item{width: 40%;}

        .mui-table-view-cell {
            position: relative;
            overflow: hidden;
            padding: 12px 15px;
            -webkit-touch-callout: none;
        }

    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'设置'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/center"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">设置</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>



    <ul class="mui-table-view">
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="/passport/retrieve-password"><span class="fs-16-fc-212229">修改密码</span></a>
        </li>
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="#"><span class="fs-16-fc-212229">关于我们</span></a>
        </li>
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="/user/modify-phone"><span class="fs-16-fc-212229">绑定手机</span></a>
        </li>
    </ul>

    <div style="margin-top: 17px;line-height: 48px;text-align: center;background-color: #ffffff;border-top: 1px solid #EBE9E9;border-bottom: 1px solid #EBE9E9;"><a style="display: block;font-size: 16px;
color: #FA3F26;" href="/passport/login-out">退出登录</a></div>


@stop

@section('script')
@stop