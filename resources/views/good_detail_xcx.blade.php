@extends('_layout.master')
@section('title')
    <title>商品详情</title>
@stop
@section('style')
    <style>
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
        .item-opr span{line-height: 40px;display: inline-block;}
        .show-img{width: 100%;border-radius: 12px;}
        .pro-essay-barr{border-bottom: 1px solid #9c9c9c;margin: 20px 0;}


        .active-tab{font-weight: bold;position: relative;}


        .active-tab:after{
            border-bottom:solid 4px #98CC3D;
            position: absolute;
            right: 0;
            left: 0;
            content:'';
            display: block;
            top:22px;
        }



        .active-iframe{
            display: block !important;
        }

        .btn3{background-image: linear-gradient(-137deg, #B9E77D 0%, #78CD09 50%);  box-shadow: 0 8px 16px 0 rgba(139,217,75,0.46);border-radius: 44px;line-height: 44px;font-size: 16px;color:#ffffff;font-weight: 800;text-align: center;}

        .btn3:hover{color:#ffffff;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'辣木膳购买系统'])--}}
    <div class="info-vue" style="margin-top: 16px;">
        <div class="cus-row">
            <div class="cus-row-col-4 t-al-c"><span class="fs-14-fc-212229 " v-bind:class="{ 'active-tab': (tabIndex == 1) }" v-on:click="setTab(1)">详细介绍</span></div>
            <div class="cus-row-col-4 t-al-c"><span class="fs-14-fc-212229" v-bind:class="{ 'active-tab': (tabIndex == 2) }" v-on:click="setTab(2)">配送方式</span></div>
            <div class="cus-row-col-4 t-al-c"><span class="fs-14-fc-212229" v-bind:class="{ 'active-tab': (tabIndex == 3) }" v-on:click="setTab(3)">售后说明</span></div>
        </div>

        <div style="border-bottom:solid 1px #e9e9e9;margin-top: 10px;"></div>

        <div class="padding-container">
            {{--<div class="product-list">--}}
            {{--<div class="product-item">--}}
            {{--<div class="item-info">--}}
            {{--<img class="show-img" src="{{$product->cover_image}}"/>--}}
            {{--</div>--}}
            {{--<div class="item-opr"><span>{{$product->product_name}}</span></div>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--<div style="padding: 0 10px;"><div class="pro-essay-barr"></div></div>--}}

            <iframe src="/passport/good-detail?product_id={{$product->id}}&index=0" frameborder="0" scrolling="no" id="test" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;" v-bind:class="{ 'active-iframe': (tabIndex == 1) }"></iframe>

            <iframe src="/passport/good-detail?product_id={{$product->id}}&index=1" frameborder="0" scrolling="no" id="test1" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;"  v-bind:class="{ 'active-iframe': (tabIndex == 2) }"></iframe>


            <iframe src="/passport/good-detail?product_id={{$product->id}}&index=2" frameborder="0" scrolling="no" id="test2" onload="this.height=100" style="width: 100%;margin-bottom: 50px;display: none;"  v-bind:class="{ 'active-iframe': (tabIndex == 3) }"></iframe>

            <footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">

                <a class="btn3 m-t-20" href="javascript:buy()" style="margin-top: 0;display: block;">购买</a>
                {{--<div class="mui-row" style="line-height: 40px;">--}}
                {{--<a class="mui-col-sm-2 mui-col-xs-2 btn-block2" href="tel:{{$product->consumer_service_phone}}">客服</a>--}}
                {{--<a class="mui-col-sm-10 mui-col-xs-10 btn-block1 remove-radius" href="/user/report-bill?product_id={{$product->id}}">立即下单</a>--}}
                {{--</div>--}}
            </footer>
        </div>

    </div>
@stop

@section('script')
    <script src="/js/vue.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
    <script type="text/javascript">
        function reinitIframe(){
            var iframe = document.getElementById("test");
            try{
//                var bHeight = iframe.contentWindow.document.body.scrollHeight;
//                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
//                var height = Math.max(bHeight, dHeight);
                iframe.height = iframe.contentWindow.document.body.clientHeight;
//                console.log(height);
            }catch (ex){}

            var iframe = document.getElementById("test1");
            try{
//                var bHeight = iframe.contentWindow.document.body.scrollHeight;
//                console.log(iframe.contentWindow.document.body.clientHeight);
//                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
//                console.log(bHeight,dHeight);
//                var height = Math.max(bHeight, dHeight);
                iframe.height = iframe.contentWindow.document.body.clientHeight;
//                console.log(height);
            }catch (ex){}


            var iframe = document.getElementById("test2");
            try{
//                var bHeight = iframe.contentWindow.document.body.scrollHeight;
//                var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
//                var height = Math.max(bHeight, dHeight);
                iframe.height = iframe.contentWindow.document.body.clientHeight;
//                console.log(height);
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

        function buy(){
            wx.miniProgram.navigateTo(
                {
                    url:{!!  \Illuminate\Support\Facades\Request::input('rebuy')?'"/pages/rereport/pay"':'"/pages/report/pay"' !!}
                });
        }




        new Vue({
            el:".info-vue",
            data:{tabIndex:1},
            methods:{
                setTab:function(index){
                    this.tabIndex = index;
                }
            }
        });
    </script>
@stop