@extends('_layout.master')
@section('title')
    <title>辣木膳购买</title>
@stop
@section('style')
    <style>
        /*html,body{background-color: #f8f8f8;}*/
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
        /*.item-opr span{line-height: 40px;display: inline-block;}*/
        .show-img{width: 100%;border-radius: 12px;}
        .pro-essay-barr{border-bottom: 1px solid #9c9c9c;margin: 20px 0;}
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'辣木膳购买系统'])--}}


    <div class="cus-row p-l-r-14 cus-row-v-m">
        <div class="cus-row-col-4"></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">辣木膳购买</span></div>
        <div class="cus-row-col-4 t-al-r"><a href="/user/center"><i class="user-icon"></i></a></div>
    </div>

    <div class="padding-container" style="margin-top: -24px;">
        <div class="product-list">
            <div class="product-item">
                <div class="item-info" style="margin: 0 -10px;" onclick="goHref('/user/good-detail?product_id=1')">
                    <img class="show-img" src="{{$product->cover_image}}" style="border-radius: 0;"/>
                </div>
                {{--<div class="item-opr"><span>{{$product->product_name}}</span><a href="/user/good-detail?product_id=1" class="btn1 fl-r">立即购买</a></div>--}}

                <div><a class="btn-block1 m-t-20" href="/user/good-detail?product_id=1">立即购买</a></div>
            </div>
        </div>

        {{--<div style="padding: 0 10px;"><div class="pro-essay-barr"></div></div>--}}

        <p style="margin-top: 27px;">最新动态</p>

        <div class="product-list">
            @foreach($list as $item)
            <div class="product-item" style="border: 1px solid #EBE9E9;
box-shadow: 0 1px 4px 0 rgba(0,0,0,0.09);
border-radius: 5px;margin-bottom: 14px;" onclick="goHref('/user/essay?id={{$item->id}}')">


                <div class="item-info" style="">
                    <img class="show-img" src="{{$item->cover_image}}" style="border-radius: 0;"/>
                </div>
                    <div  style="padding: 8px 13px;" class="item-opr"><span class="fs-16-fc-212229">{{$item->title}}</span><br/><span class="fs-12-fc-a6a6a6">{{$item->sub_title}}</span></div>
                    {{--<a href="#" class="fl-r small-a"  style="padding-top:16px;">{{date('Y-m-d',strtotime($item->created_at))}}</a></a>--}}

            </div>
            @endforeach
        </div>

    </div>
@stop

@section('script')
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