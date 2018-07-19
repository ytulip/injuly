@extends('_layout.master')
@section('title')
    <title>资产管理</title>
@stop
@section('style')
    <style>
        html,body{background-color: #f8f8f8;}
        footer .in-bl-line{line-height: 40px;}
        .info-item{width: 60%;}
        .opr-item{width: 40%;}

        .graph-wrap{padding: 30px;}
        .graph-panel{width: 100%;background-color: #ffffff;position: relative;}
        .user-header-img{position: absolute;width: 38px;height: 38px;border-radius: 38px;overflow: hidden;display: inline-block;top:0;left:50%;transform: translate(-50%,-50%);-webkit-transform:translate(-50%,-50%); }
        .user-header-img img{width: 100%;border-radius: 38px;}
        .graph-here{padding-top: 50px;}

        .fin-total{
            background-image: linear-gradient(-215deg, #C8E859 0%, #A8D73D 46%, #97CD2D 71%);
            border-radius: 5px;
            padding: 27px;
        }

        .withdraw-btn{border: 1px solid #FFFFFF;
            border-radius: 100px;width: 81px;line-height: 30px;position: absolute;text-align: center;right: 27px;bottom: 27px;}

        .fin-total p{margin-bottom: 0;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'资产管理'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/center"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">资产管理</span></div>
        <div class="cus-row-col-4 t-al-r"><a href="/user/check"><span class="fs-16-fc-212229">账单</span></a></div>
    </div>

    <div class="p-l-r-14">
        <div class="fin-total" style="position: relative;">
            <p class="fs-14-fc-ffffff">总资产</p>
            <p class="fs-36-fc-ffffff">￥{{number_format(($user->charge + $user->charge_frozen),2)}}</p>
            <p class="fs-12-fc-ffffff" style="margin-top: 30px;">可支配资金:￥{{$user->charge}}</p>
            <p class="fs-12-fc-ffffff">未结算资金:￥{{$user->charge_frozen}}</p>
            <a class="withdraw-btn" href="/user/withdraw"><span class="fs-14-fc-ffffff">提现</span></a>
        </div>
    </div>

    <div class="p-l-r-14">
        <div style="margin-top: 19px;padding: 14px;background: #FFFFFF;border: 1px solid #EBE9E9;border-radius: 5px;">
            <a href="/user/month-income">
            <div class="cus-row cus-row-v-m" style="margin-bottom: 14px;">
                <div class="cus-row-col-6"><span style=";border-left:solid 6px #98CC3D;margin-right: 10px;height: 22px;display: inline-block"></span><span class="fs-16-fc-212229 in-bl-v-t" style="line-height: 22px;">本月收入</span></div>
                <div class="cus-row-col-6 t-al-r"><span class="fs-16-fc-f89a03">￥{{$user->monthIncome()}}</span><i class="next-icon"></i></div>
                {{--<div class="cus-row-col-1"></div>--}}
            </div>
            </a>

            <div style="border-top:1px solid #EBEAEA;margin: 0 -14px;"></div>

            <div class="cus-row">
                <div class="cus-row-col-6" style="position: relative;height: 106px">
                    <div class="ab-t-t-x-y">
                        <a href="/user/direct-indirect-record">
                        <p class="t-al-c"><span class="fs-24-fc-212229">{{$user->directAndIndirectCount()}}</span></p>
                            <p class="t-al-c" style="margin-top: 20px;"><span class="fs-14-fc-909094">开发记录</span></p></a>
                    </div>
                    <div style="border-right: 1px solid #EBEAEA;height: 66px;position: absolute;right: 0;top:20px;"></div>
                </div>
                <div class="cus-row-col-6" style="position: relative;height: 106px;">
                    <div class="ab-t-t-x-y"><a href="/user/up-super-record">
                        <p class="t-al-c"><span class="fs-24-fc-212229">{{$user->upAndSuperCount()}}</span></p>
                            <p class="t-al-c" style="margin-top: 20px;"><span class="fs-14-fc-909094">辅导记录</span></p></a>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="p-l-r-14">
        <div style="margin-top: 19px;padding: 14px;background: #FFFFFF;border: 1px solid #EBE9E9;border-radius: 5px;">
            <a href="/user/sub-user-list">
                <div class="cus-row cus-row-v-m" style="">
                    <div class="cus-row-col-6"><span style=";border-left:solid 6px #98CC3D;margin-right: 10px;height: 22px;display: inline-block"></span><span class="fs-16-fc-212229 in-bl-v-t" style="line-height: 22px;">下级会员</span></div>
                    <div class="cus-row-col-6 t-al-r"><span class="fs-16-fc-f89a03">{{count($user->subList())}}</span><i class="next-icon"></i></div>
                    {{--<div class="cus-row-col-1"></div>--}}
                </div>
            </a>
        </div>

    </div>

    {{--<div class="graph-wrap">--}}
        {{--<div class="graph-panel">--}}
            {{--<a class="user-header-img"><img src="{{$user->header_img}}"/></a>--}}
            {{--<div class="graph-here">--}}
                {{--<div class="forth circle" style="text-align: center;position: relative;">--}}
                    {{--<span>solid fill, <br/> custom angle</span>--}}
                    {{--<div class="x-de-50" style="margin-top: 60px;">--}}
                        {{--<p>总资产</p>--}}
                        {{--<p class="fs-26-fc-black">￥{{number_format(($user->charge + $user->charge_frozen),2)}}</p>--}}
                    {{--</div>--}}

                    {{--<div style="position: absolute;bottom: 0;width: 90px;height: 40px;z-index: 999;background-color: #ffffff;left: 50%;transform: translateX(-50%);-webkit-transform: translateX(-50%)">--}}
                        {{--<a href="/user/withdraw" style="background-color: rgb(0,164,247);line-height: 30px;border-radius: 20px;display: inline-block;color:#ffffff;width: 80px;font-size: 14px;margin-top: 15px;">提现</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="mui-row m-t-30 m-b-10">--}}
                {{--<div class="mui-col-sm-6 mui-col-xs-6 small-a-plus t-al-c">可支配资金:￥{{$user->charge}}</div>--}}
                {{--<div class="mui-col-sm-6 mui-col-xs-6 small-a-plus t-al-c">未结算资金:￥{{$user->charge_frozen}}</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<a class="withdraw-btn">提现</a>--}}
    {{--</div>--}}

    {{--<ul class="mui-table-view">--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/month-income">本月收入{{$user->monthIncome()}}</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/direct-indirect-record">开发记录{{$user->directAndIndirectCount()}}</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/up-super-record">辅导记录{{$user->upAndSuperCount()}}</a>--}}
        {{--</li>--}}
        {{--<li class="mui-table-view-cell">--}}
            {{--<a class="mui-navigate-right" href="/user/check">账单</a>--}}
        {{--</li>--}}
    {{--</ul>--}}

@stop

@section('script')
    <script src="/js/plugin/circle-progress/circle-progress.js"></script>
<script>
    $('.forth.circle').circleProgress({
        startAngle:Math.PI * 2/4,
        value: {{($user->charge + $user->charge_forzen)%10000/10000}},
        lineCap: 'round',
        fill: { color: '#fcab53' },
        size:180
    });
</script>
@stop