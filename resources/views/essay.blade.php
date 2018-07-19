@extends('_layout.master')
@section('title')
    <title>详情</title>
@stop
@section('style')
    <style>
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
        .item-opr span{line-height: 40px;display: inline-block;}
        .show-img{width: 100%;border-radius: 12px;}
        .pro-essay-barr{border-bottom: 1px solid #9c9c9c;margin: 20px 0;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'最新动态'])--}}

    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/index"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">详情</span></div>
        <div class="cus-row-col-4 t-al-r"></div>
    </div>

    <div class="padding-container">

        <img  src="{{$essay->cover_image}}" style="width: 100%"/>
        <h3 style="margin-top: 34px;font-size: 20px;font-color:#212229">{{$essay->title}}</h3>
        <div class="small-a" style="font-size: 12px;margin-top:6px;color:#a8a8a8;">{{date('Y年m月d日 H:i',strtotime($essay->created_at))}}</div>
        <iframe src="/passport/show-essay?id={{$essay->id}}" frameborder="0" scrolling="no" id="test" onload="this.height=100" style="width: 100%;"></iframe>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        function reinitIframe(){
            var iframe = document.getElementById("test");
            try{
                var bHeight = iframe.contentWindow.document.body.scrollHeight;
                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
                var height = Math.max(bHeight, dHeight);
                iframe.height = height;
                console.log(height);
            }catch (ex){}
        }
        window.setInterval("reinitIframe()", 200);
    </script>
    <script>
        $(function () {
            new SubmitButton({
                selectorStr:"#next_step",
                url:'/passport/login',
                data:function()
                {
                    return $('#data_form').serialize();
                },
                redirectTo:'/index'
            });
        });

    </script>
@stop