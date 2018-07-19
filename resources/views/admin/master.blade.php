<!DOCTYPE HTML>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->
    <link href="/admin/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="/admin/css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="/admin/css/morris.css" type="text/css"/>
    <!-- Graph CSS -->
    <link href="/admin/css/font-awesome.css" rel="stylesheet">
    <link href="/admin/css/admin.css" rel="stylesheet">
    <link href="/js/plugin/dateinput/bootstrap-datetimepicker.css" rel="stylesheet"/>
    <!-- jQuery -->
    <script src="/admin/js/jquery-2.1.4.min.js"></script>
    <!-- //jQuery -->
    <!-- lined-icons -->
    <link rel="stylesheet" href="/admin/css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <style>

        #menu-academico-sub{display: none;}
        .menu-fa{display: none;}
        .sidebar-collapsed .menu-fa{display: inline-block;}
        .sidebar-collapsed #menu-academico-sub{display: inline-block;}
        .sidebar-collapsed .sub-menu{display: none;}

        .sub-menu{padding-left: 40px;}

        #admin_message_wrap #admin_message_panel{display: none;}
        #admin_message_wrap:hover #admin_message_panel{display: block;}


        #admin_user_panel {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 110px;
            text-align: center;
            background-color: #ffffff;
        }

        #admin_user_panel li {
            display: block;
            border-bottom: 1px solid #e9e9ea;
        }

        #admin_user_wrap:hover #admin_user_panel{display: block;}
    </style>
    @section('style')
        @show
</head>
<body>
<div class="page-container">
    <!--/content-inner-->
    <div class="left-content">
        <div class="mother-grid-inner">
        <div class="row header-title">
            <div class="col-md-6 col-lg-6">{!! $headerTitle !!}</div>
            <div class="col-md-3 col-lg-3">&nbsp;</div>
            <div class="col-md-3 col-lg-3">
                <div style="line-height: 52px;display: inline-block;position: relative;" id="admin_message_wrap"><i class="fa fa-envelope" style="margin-left: 40px;"></i>

                    <div style="position: absolute;width: 420px;z-index: 99;right:0;" id="admin_message_panel">
                        <div style="line-height: 50px;background-color: #485465;padding: 0 10px;color:#ffffff;">消息中心</div>
                        <div class="message-list" style="background-color: #ffffff;">
                            @foreach(\App\Model\Message::messageSummary() as $key=>$val)
                                <div class="message-item" style="padding: 10px;border: 1px solid #e0e0e0;">
                                <div class="row" style="line-height: 14px;">
                                <div class="col-md-3 col-lg-3 font-color2-12"><span >{{$val->title}}</span></div>
                                <div class="col-md-5 col-lg-5"></div>
                                <div class="col-md-4 col-lg-4 font-color2-12">{{date('Y.m.d H.i',strtotime($val->created_at))}}</div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 col-lg-12">
                                <div style="font-size: 14px;line-height: 24px;">{{$val->content}}</div>
                                </div>
                                </div>
                                </div>
                                @endforeach
                            {{--<div class="message-item" style="padding: 10px;border: 1px solid #e0e0e0;">--}}
                                {{--<div class="row" style="line-height: 14px;">--}}
                                    {{--<div class="col-md-3 col-lg-3 font-color2-12"><span >购买提醒</span></div>--}}
                                    {{--<div class="col-md-5 col-lg-5"></div>--}}
                                    {{--<div class="col-md-4 col-lg-4 font-color2-12">2017.12.19 19.20</div>--}}
                                {{--</div>--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-12 col-lg-12">--}}
                                    {{--<div style="font-size: 14px;line-height: 24px;">张三(137xxxx2345)邀请高级会员,购买120盒</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div style="line-height: 50px;background-color: #a8b7c5;text-align: center;">查看更多</div>
                    </div>
                </div>
                <div style="line-height: 52px;display: inline-block;position: relative;" id="admin_user_wrap"><i  style="margin-left: 40px;position: relative;" class="fa fa-user"></i>

                    <ul style="position: absolute;z-index: 99;right:0;border:1px solid rgb(233, 233, 234);" id="admin_user_panel">
                        <li><a href="/passport/admin-login-out">退出登录</a></li>
                    </ul>
                </div>
            </div>
        </div>
        @section('left_content')

            @show
        </div>
        <div style="margin-bottom: 45px;"></div>
    </div>
    <!--//content-inner-->
    <!--/sidebar-menu-->
    <div class="sidebar-menu">
        <header class="logo1">
            <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
        </header>
        <div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
        <div class="menu">
            <ul id="menu" >
                <li @if(!\App\Util\AdminAuth::hasPower(1)) class="dpn" @endif><a href="/admin/index/total"><i class="fa fa-tachometer menu-fa"></i> <span>概况</span><div class="clearfix"></div></a></li>

                <li id="menu-academico" @if(!\App\Util\AdminAuth::hasPower(2)) class="dpn" @endif><a href="/admin/index/good"><i class="fa fa-envelope nav_icon menu-fa"></i><span>商品管理</span><div class="clearfix"></div></a></li>
                <li id="menu-academico" @if(!\App\Util\AdminAuth::hasPower(3)) class="dpn" @endif><a href="/admin/index/activity-good"><i class="fa fa-envelope nav_icon menu-fa"></i><span>活动商品管理</span><div class="clearfix"></div></a></li>

                <li @if(!\App\Util\AdminAuth::hasPower(4)) class="dpn" @endif><a href="/admin/index/essays"><i class="fa fa-picture-o menu-fa" aria-hidden="true"></i><span>内容管理</span><div class="clearfix"></div></a></li>

                <li id="menu-academico" @if(!\App\Util\AdminAuth::hasPower(5)) class="dpn" @endif><a href="/admin/index/members"><i class="fa fa-bar-chart menu-fa"></i><span>会员管理</span><div class="clearfix"></div></a></li>

                <li id="menu-academico" @if(!\App\Util\AdminAuth::hasPower(6)) class="dpn" @endif><a href="/admin/index/activity-members"><i class="fa fa-bar-chart menu-fa"></i><span>活动会员管理</span><div class="clearfix"></div></a></li>

                <li id="menu-academico" @if(!\App\Util\AdminAuth::hasPower(7)) class="dpn" @endif><a href="/admin/index/sign-list"><i class="fa fa-bar-chart menu-fa"></i><span>打卡记录</span><div class="clearfix"></div></a></li>

                <li id="menu-academico" @if(!\App\Util\AdminAuth::hasPower(8)) class="dpn" @endif><a href="#"><i class="fa fa-list-ul menu-fa" aria-hidden="true"></i><span> 资产管理</span> <div class="clearfix"></div></a>
                    <ul id="menu-academico-sub" >
                        <li id="menu-academico-avaliacoes" ><a href="/admin/index/total-finance">资产概括</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="/admin/index/orders">购买管理</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="/admin/index/withdraw">提现管理</a></li>
                    </ul>
                    <div class="sub-menu">
                        <a href="/admin/index/total-finance">资产概括</a>
                        <a href="/admin/index/orders">购买管理</a>
                        <a href="/admin/index/get-good">提货记录</a>
                        <a href="/admin/index/withdraw">提现管理</a>
                        <a href="/admin/index/activity-trunback">活动退款</a>
                    </div>
                </li>
                <li @if(!\App\Util\AdminAuth::hasPower(9)) class="dpn" @endif><a href="/admin/index/power"><i class="fa fa-tachometer menu-fa"></i> <span>管理员列表</span><div class="clearfix"></div></a></li>
            </ul>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    var toggle = true;

    $(".sidebar-icon").click(function() {
        if (toggle)
        {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position":"absolute"});
        }
        else
        {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function() {
                $("#menu span").css({"position":"relative"});
            }, 400);
        }

        toggle = !toggle;
    });
</script>
<!--js -->
<script src="/admin/js/jquery.nicescroll.js"></script>
<script src="/admin/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="/admin/js/bootstrap.min.js"></script>
<!-- /Bootstrap Core JavaScript -->
<!-- morris JavaScript -->
<script src="/admin/js/jquery.base64.js"></script>
<script src="/admin/js/raphael-min.js"></script>
<script src="/admin/js/morris.js"></script>
<script src="/admin/js/echarts.simple.min.js?v={{env('VERSION')}}"></script>
<script src="/admin/js/layer/layer/layer.js"></script>
<script src="/admin/js/common.js"></script>
<script>
    jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
</script>
<script src="/js/plugin/dateinput/bootstrap-datetimepicker.js"></script>
<script src="/js/plugin/dateinput/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    function goHref(href)
    {
        location.href = href;
    }
</script>
@section('script')
    @show
</body>
</html>