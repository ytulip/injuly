@extends('_layout.master')
@section('title')
    <title>购买</title>
@stop
@section('style')
    <style>

        html,body{background-color: #f8f8f8;}
        /*footer a{line-height: 40px;display: block;font-size: 16px;background: #0000C2;color:#ffffff;text-align: center;}*/

    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买完成'])--}}

    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">购买</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div class="p-l-r-14">
    <div style="background: #FFFFFF;
border: 1px solid #EBE9E9;padding-top:60px;padding-bottom:34px;">
        <div class="t-al-c"><img src="/images/icon_success.png" style="display: inline-block;"/></div>
        @if( $order->buy_type == 1 )
        <div class="t-al-c" style="margin-top: 42px;"><span class="fs-14-fc-212229">获得高级会员邀请码</span></div>

        <div class="t-al-c" style="margin-top: 20px;">
            {{--<input  class="fs-24-fc-212229" id="invited_code"  value="{{$invited_code}}"  style="display: inline-block;border: none;" type="text" readonly onfocus='this.blur();'>--}}
            <span class="fs-24-fc-212229" >{{$invited_code}}</span>
            <span class="copy-icon invited_code_copy" style="margin-left: 4px;cursor: pointer;"  data-clipboard-text="{{$invited_code}}"></span>
        </div>
            @else
            <div class="t-al-c" style="margin-top: 42px;"><span class="fs-14-fc-212229">复购成功</span></div>
        @endif
    </div>
    </div>

    @if( $order->buy_type == 1 )

    <div class="p-l-r-14" style="margin-top: 18px;">
        <span><i class="attention-icon"></i></span><span class="fs-12-fc-212229">邀请码使用一次后即失效，请妥善保管并及时告知新会员，以便完成会员注册。在个人中心查看邀请码的使用情况。</span>
    </div>
    @endif

    <div style="padding: 0 42px;margin-top: 42px;"><a href="/user/center" style="border: 1px solid #98CC3D;
border-radius: 100px;line-height: 40px;text-align: center;display: inline-block;width: 100%;"><span style="font-size: 17px;
color: #98CC3D;">完成</span></a></div>

    {{--<div style="text-align: center;margin-top: 30px;"><img src="/images/success1_03.png" style="width: 100px;margin: 0 auto;display: inline-block;"/></div>--}}
    {{--<div style="text-align: center;">购买成功</div>--}}
    {{--<div class="padding-container" style="margin-top: 30px;">--}}
        {{--<p style="font-size: 20px;font-weight: bold;color:#000000;">获得高级客户邀请码</p>--}}
        {{--<p style="color:#ff5722;line-height: 24px;font-size: 16px;border-bottom: 1px solid #B2B2B2;">{{$invited_code}}</p>--}}
        {{--<p>邀请码使用一次后即失效，请妥善保管并及时告知新会员，以便其完成会员注册。</p>--}}
        {{--<p>可以在个人中心中查看所有获得的邀请码使用情况。</p>--}}
    {{--</div>--}}
    {{--<footer class="fix-bottom">--}}
        {{--<a href="/user/center" class="btn-block1 remove-radius">完成</a>--}}
    {{--</footer>--}}

@stop

@section('script')
<script src="/js/clipboard.js"></script>
<script>


    var clipboard = new Clipboard(".invited_code_copy");

    clipboard.on('success', function(e) {
        mAlert('已复制');
//            console.info('Action:', e.action);
//            console.info('Text:', e.text);
//            console.info('Trigger:', e.trigger);

        // e.clearSelection();
    });
//
    clipboard.on('error', function(e) {
        mAlert('不支持复制');
//            console.error('Action:', e.action);
//            console.error('Trigger:', e.trigger);
    });

//    function copy()
//    {
//        new Clipboard('#invited_code');
//
//        clipboard.on('success', function(e) {
//            mAlert('已复制');
////            console.info('Action:', e.action);
////            console.info('Text:', e.text);
////            console.info('Trigger:', e.trigger);
//
//           // e.clearSelection();
//        });
//
//        clipboard.on('error', function(e) {
//            mAlert('不允许复制');
////            console.error('Action:', e.action);
////            console.error('Trigger:', e.trigger);
//        });
////        var clipBoardContent=$("#invited_code").html();
////        window.clipboardData.setData("Text",clipBoardContent);
////        document.execCommand("Copy"); // 执行浏览器复制命令
////        mAlert('已复制');
//    }
</script>
@stop