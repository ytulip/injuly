@extends('_layout.master')
@section('title')
    <title>购买</title>
@stop
@section('style')
<link href="/js/plugin/mobile-select/mobileSelect.css" rel="stylesheet"/>
<style>

    #city *{
        padding: 0;margin: 0;
    }
    html,body{background-color: #f8f8f8;}
    .item-footer{margin-top: 4px;}
    .mui-input-row{margin-top: 2px;}
    .item-header,.item-footer{background-color: #ffffff;padding: 10px;}

    .city-mask{
        position: fixed;
        top:0;
        bottom: 0;
        right: 0;
        left: 0;
        background-color: rgba(0,0,0,.6);
        z-index: 999;
    }

    .city-panel{
        position: absolute;
        height: 215px;
        background-color: #ffffff;
        display: inline-block;
        right: 0;
        left: 0;
        bottom: 0;
    }

    .city-panel li{
        line-height: 37px;;
        list-style: none;
    }

    .city-panel-header{
        overflow: hidden;
    }
    .city-panel-header a{
        line-height: 32px;
        padding: 0 12px;
    }

    .city-panel-header a:nth-child(1){
        float: left;
    }

    .city-panel-header a:nth-child(2){
        float: right;
        color:#47b5ca;
    }

    .city-panel-body{
        height: 185px;
        position: relative;
        overflow: hidden;
    }
    .province-list{
        position: absolute;
        top:0;
        bottom:0;
        left: 0;
        width: 100%;
        text-align: center;
    }

    .city-list{
        position: absolute;
        top:0;
        bottom:0;
        right: 0;
        width: 50%;
        text-align: center;
    }

    .city-barrier{
        position: absolute;
        height: 37px;
        right: 0;
        left: 0;
        top:74px;
        border-top: solid 1px #e2e2e2;
        border-bottom: solid 1px #e2e2e2;
    }

    #city input{border: none;padding: 10px 0; }

    .info-item{width: 60%;}
    .opr-item{width: 40%;}

    .btn-bg-d1{background-color: #d1d1d1;}
    .btn-bg-9b{background-color: #9b9b9b;}
    .btn-bg-f5{background-color: #f5a623;}

    /*.select-default{font-size: 14px;color:#bebebe;}*/
    /*.select-default option{color:#212229}*/
    /*.select-default option:nth-child(1){color:#bebebe!important;}*/

    input::-webkit-input-placeholder{color: #bebebe;  }

</style>
    @stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'购买/邀请新会员'])--}}
    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/good-detail?product_id=1"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">购买</span></div>
        <div class="cus-row-col-4 t-al-r"><a href="tel:{{$product->consumer_service_phone}}"><span class="fs-16-fc-212229">客服</span></a></div>
    </div>

    <form id="form_data">
        <div style="border-top:1px solid #EBEAEA;background-color: #ffffff">
            <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
                <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">购买方式</span></div>
                <div class="cus-row-col-8" style="vertical-align: top;">
                    {{--<input class="fs-14-fc-909094" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" readonly value="" placeholder="请选择购买数量"/>--}}
                    {{--{!! \App\Model\SyncModel::productAttr('product_attr_id',false,\Illuminate\Support\Facades\Request::input('product_id'))!!}--}}
                    {{--<select class="cus-select" style="margin: 0;display: inline-block;padding: 10px 15px;height: 46px;">--}}
                        {{--<option value="">请选择购买数量</option>--}}
                    {{--</select>--}}
                    <input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" name="product_id" readonly value="" onfocus='this.blur();' placeholder="请选择购买数量"/>

                </div>
                <div class="cus-row-col-1" style="line-height: 44px;">
                    <span><i class="next-icon"></i></span>
                </div>
            </div>


            <div class="cus-row cus-row-bborder" id="quantity_line" style="padding-left: 16px;display: none;">
                <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">数量(盒)</span></div>
                <div class="cus-row-col-9" style="vertical-align: top;">
                    {{--<input class="fs-14-fc-909094" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" readonly value="" placeholder="请选择购买数量"/>--}}
                    {{--{!! \App\Model\SyncModel::productAttr('product_attr_id',false,\Illuminate\Support\Facades\Request::input('product_id'))!!}--}}
                    {{--<select class="cus-select" style="margin: 0;display: inline-block;padding: 10px 15px;height: 46px;">--}}
                    {{--<option value="">请选择购买数量</option>--}}
                    {{--</select>--}}
                    <input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" name="quantity" value="" placeholder="10的整数倍，30起购"/>

                </div>
            </div>

            <div class="cus-row cus-row-bborder" style="padding-left: 16px;" id="immediate_line">
                <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">直接开发者</span></div>
                <div class="cus-row-col-9"><input class="fs-14-fc-212229" type="text" style="margin-bottom: 0;border: none;height: 46px;" value="{{$user->phone}}" placeholder="请填写直接开发者手机号" name="immediate_phone"/></div>
            </div>

            <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
                <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">收货方式</span></div>
                <div class="cus-row-col-8" style="vertical-align: top;">
                    <input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;" name="deliver_type" readonly value="" onfocus='this.blur();' placeholder="请选择收货方式"/>
                    {{--<select class="cus-select" style="margin: 0;display: inline-block;padding: 10px 15px;height: 46px;">--}}
                        {{--<option value="">请选择收货方式</option>--}}
                        {{--<option value="1">自提</option>--}}
                        {{--<option value="2">送货上门</option>--}}
                    {{--</select>--}}
                </div>
                <div class="cus-row-col-1" style="line-height: 44px;">
                    <span><i class="next-icon"></i></span>
                </div>
            </div>

            <div class="cus-row cus-row-bborder" style="padding-left: 16px;">
                <div class="cus-row-col-3"><span class="fs-14-fc-212229" style="line-height: 46px;">收货地址</span></div>
                <div class="cus-row-col-8"><input class="fs-14-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;text-overflow: ellipsis;" readonly value="" onfocus='this.blur();' placeholder="请选择收货地址" name="address-show" onclick="chooseAddress()"/></div>
                <div class="cus-row-col-1" style="line-height: 44px;">
                    <span><i class="next-icon"></i></span>
                </div>
            </div>


            {{--<div class="cus-row" style="padding-left: 16px;">--}}
                {{--<div class="cus-row-col-3 t-al-c"><span class="fs-16-fc-212229" style="line-height: 46px;">验证</span></div>--}}
                {{--<div class="cus-row-col-6"><input name="withdraw_sms_code" class="fs-16-fc-212229" type="text" style="line-height: 46px;margin-bottom: 0;border: none;height: 46px;"/></div>--}}
                {{--<div class="cus-row-col-3"><a class="get-code-btn" href="javascript:void(0)"><span style="display: inline-block;line-height: 44px;color:#2966E2;">获取验证码</span></a></div>--}}
            {{--</div>--}}
            {{--<div class="cus-input-row fs-16-fc-212229"><label>姓名</label><input/></div>--}}
            {{--<div class="cus-input-row fs-16-fc-212229"><label>手机</label><input/></div>--}}
            {{--<div class="cus-input-row"><label>姓名</label><input/></div>--}}
            {{--<div style="border-top:1px solid #EBEAEA;padding: 24px 28px;">--}}
                {{--<a class="btn-block1" href="javascript:void(0);" id="next_step">提交申请</a>--}}
            {{--</div>--}}
        </div>
        {{--<ul class="mui-table-view">--}}
            {{--<li class="mui-table-view-cell">--}}
                {{--<div class="mui-row">--}}
                    {{--<div class="mui-col-sm-2 mui-col-xs-2"></div>--}}
                    {{--<div class="mui-col-sm-10 mui-col-xs-10">购买数量</div>--}}
                {{--</div>--}}
                {{--{!! \App\Model\SyncModel::productAttr('product_attr_id',false,\Illuminate\Support\Facades\Request::input('product_id'))!!}--}}
            {{--</li>--}}
            {{--<li class="mui-table-view-cell">--}}
                {{--<div class="mui-row">--}}
                    {{--<div class="mui-col-sm-2 mui-col-xs-2"></div>--}}
                    {{--<div class="mui-col-sm-10 mui-col-xs-10">直接开发者</div>--}}
                {{--</div>--}}
                {{--<input type="number" name="immediate_phone"/>--}}
            {{--</li>--}}
            {{--<li class="mui-table-view-cell">--}}
                {{--<div class="mui-row">--}}
                    {{--<div class="mui-col-sm-2 mui-col-xs-2"></div>--}}
                    {{--<div class="mui-col-sm-10 mui-col-xs-10">收货方式</div>--}}
                {{--</div>--}}
    {{--{!!\App\Model\SyncModel::deliverType('deliver_type')!!}--}}
{{--</li>--}}
{{--<li class="mui-table-view-cell">--}}
    {{--<div class="mui-row">--}}
        {{--<div class="mui-col-sm-2 mui-col-xs-2"></div>--}}
        {{--<div class="mui-col-sm-10 mui-col-xs-10">收货地址</div>--}}
    {{--</div>--}}
    {{--{!! \App\Model\SyncModel::selfGetAddress('self_get_deliver_address')!!}--}}
    {{--{!! \App\Model\SyncModel::mineAddress('mine_deliver_address',false,\Illuminate\Support\Facades\Auth::id())!!}--}}
{{--</li>--}}
{{--</ul>--}}
</form>

<footer class="fix-bottom" style="background-color: #ffffff;padding: 14px;border-top:1px solid #EBE9E9 ;">
    <div class="cus-row cus-row-v-m">
        <div class="cus-row-col-6">
            <span class="fs-24-fc-212229">￥</span><span class="fs-24-fc-212229" id="price_label">00.00</span>
        </div>
        <div class="cus-row-col-6">
            <a class="btn-block1 m-t-20" href="javascript:void(0)" id="next_step" style="margin-top: 0;">立即付款</a>
        </div>
    </div>
{{--<div class="in-bl-line">--}}
    {{--<div class="in-bl-line-item info-item remove-radius" style="width: 60%;"><a class="btn-block1 remove-radius btn-bg-d1" id="total_price">合计￥00.00</a></div>--}}
    {{--<div class="in-bl-line-item remove-radius" id="next_step" style="width: 40%"><a class="btn-block1 remove-radius btn-bg-9b">立即下单</a></div>--}}
{{--</div>--}}
</footer>

<div class="address-list-vue vue-none" v-if="showFlag">
    <div class="address-list-wrap" style="position: fixed;top:0;right: 0;bottom: 0;left:0;background-color: #f8f8f8;">
        <div class="cus-row p-l-r-14">
            <div class="cus-row-col-3"><a href="javascript:void(0)" v-on:click="showFlag = false"><i class="back-icon"></i></a></div>
            <div class="cus-row-col-6 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">选择收货地址</span></div>
            <div class="cus-row-col-3 t-al-r"><a href="/user/addresses"><span class="fs-16-fc-212229">管理</span></a></div>
        </div>

        <div class="address-list" v-if="currentValue == 2">
            @foreach(\App\Model\UserAddress::mineAddressList($user->id) as $item)
            <div class="address-item" style="padding:14px 16px;background-color: #ffffff;margin-bottom: 17px;border-top: 1px solid #ebeaea;border-bottom: 1px solid #ebeaea" v-on:click="bingo" data-address="{{$item->pct_code_name}}{{$item->address}}" data-id="{{$item->address_id}}">
                <p><span class="fs-14-fc-212229">{{$item->address_name}} {{$item->mobile}}</span></p>
                <p><span class="fs-14-fc-212229">{{$item->pct_code_name}}{{$item->address}}</span></p>
            </div>
                @endforeach
        </div>

        <div class="address-list" v-if="currentValue == 1">
            @foreach(\App\Model\UserAddress::selfGetAddressList() as $item)
                <div class="address-item" style="padding:14px 16px;background-color: #ffffff;margin-bottom: 17px;border-top: 1px solid #ebeaea;border-bottom: 1px solid #ebeaea;" v-on:click="bingo" data-address="{{$item->pct_code_name}}{{$item->address}}" data-id="{{$item->address_id}}">
                    <p><span class="fs-14-fc-212229">{{$item->address_name}} {{$item->mobile}}</span></p>
                    <p><span class="fs-14-fc-212229">{{$item->pct_code_name}}{{$item->address}}</span></p>
                </div>
            @endforeach
        </div>
    </div>

</div>
@stop

@section('script')
<script src="/js/vue.js"></script>
<script src="/js/plugin/mobile-select/mobileSelect.js"></script>
<script src="/js/cus-select.js"></script>
<script>

    function chooseAddress(){
        addressVue.show();
    }

    var pageConfig = {
        attr1_price:'{{number_format($attr1->price,2)}}',
        attr2_price:'{{number_format($attr2->price,2)}}',
        productIds:{!! \App\Model\Product::getProductAttrsConfigJson() !!},
        buyType:[{"value":1,"name":"邀请新会员(120盒)","price":"{{\App\Model\ProductAttr::find(2)->price}}"},{"value":2,"name":"复购(30盒起购)","price":"{{\App\Model\ProductAttr::find(2)->rebuy_price}}"}]
    }

    new CusSelect({
        itemArr:[{value:1,name:'自提'},{value:2,name:'送货上门'}],
        triggerEl:'input[name="deliver_type"]',
        idSpecial:1,
        callback:function(id){
            //清除地址选项
            addressVue.currentValue = id;
            $('input[name="address-show"]').val('');
            $('input[name="address-show"]').attr('cus-select-value','');

        }
    });


    $('input[name="quantity"]').bind('input propertychange', function() {
        // console.log($(this).val());
//        changeUserList($(this).val());
        price = pageConfig.buyType[1]['price'] * parseInt($('input[name="quantity"]').val());
        $('#price_label').html(price);
    })

    new CusSelect({
        itemArr:pageConfig.buyType,
        triggerEl:'input[name="product_id"]',
        idSpecial:2,
        callback:function(id){

            if(id == 2)
            {
                $('#immediate_line').hide();
                $('#quantity_line').show();
            } else if( id == 1)
            {
                $('#immediate_line').show();
                $('#quantity_line').hide();
            }

            var price = 0.00;
            $.each(pageConfig.buyType,function(ind,obj){
                console.log(obj.value);
                if(obj.value == id)
                {
                    if( id == 2)
                    {
                        price = obj.price * parseInt($('input[name="quantity"]').val());
                        $('#price_label').html(price);
                        return false;
                    } else {
                        price = obj.price;
                        $('#price_label').html(price);
                        return false;
                    }
                }
            });
        }
    });


    $('.cus-select').change(function(){

//        alert(123);

        if($(this).val()==''){
            $(this).addClass('select-default');
        }else{
            $(this).removeClass('select-default');
        }
    });

    $('.cus-select').change();





//    var mobileSelect1 = new MobileSelect({
//        trigger: '.next-icon-quantity',
//        title: '单项选择',
//        wheels: [
//            {data:['周日','周一','周二','周三','周四','周五','周六']}
//        ],
//        position:[2], //Initialize positioning,
//        triggerDisplayData:false,
//        callback:function(indexArr, data){
//            console.log(data); //Returns the selected json data
//        }
//    });

$(function(){

/*配送方式改变*/
    $('select[name="deliver_type"]').change(function(){
        if($(this).val() == 1) {
            $('select[name="self_get_deliver_address"]').show();
            $('select[name="mine_deliver_address"]').hide();
        } else if($(this).val() == 2) {
            $('select[name="self_get_deliver_address"]').hide();
            $('select[name="mine_deliver_address"]').show();
        }  else {
            $('select[name="self_get_deliver_address"]').hide();
            $('select[name="mine_deliver_address"]').hide();
        }

    });

    $('select[name="deliver_type"]').change();

});

$('select[name="product_attr_id"]').change(function(){
    var attrId = $(this).val();
    if(attrId == 1)
    {
        $('#total_price').html('合计:￥' + pageConfig.attr1_price).addClass('btn-bg-f5');
        $('#next_step a').removeClass('btn-bg-9b');
    } else if (attrId == 2 )
    {
        $('#total_price').html('合计￥' + pageConfig.attr2_price).addClass('btn-bg-f5');
        $('#next_step a').removeClass('btn-bg-9b');
    }else
    {
        $('#total_price').html('合计￥' + '00.00').removeClass('btn-bg-f5');
        $('#next_step a').addClass('btn-bg-9b');
    }
});

    $('select[name="product_attr_id"]').change();

new SubmitButton({
    selectorStr:"#next_step",
    url:"/user/report-bill",
    prepositionJudge:function()
    {
        if(!$('input[name="product_id"]').attr('cus-select-value')){
            mAlert('请选择购买方式');
            return false;
        }


        if( $('input[name="product_id"]').attr('cus-select-value') == 2)
        {
            var quantity = $('input[name="quantity"]').val();
            if((quantity % 10 != 0) || (quantity/10 < 3) )
            {
                mAlert('购买数量必须为10的整数倍，30起购');
                return false;
            }
        }

        if(!$('input[name="immediate_phone"]').val()){
            mAlert('请输入直接开发者');
            return false;
        }

        if(!$('input[name="deliver_type"]').attr('cus-select-value')){
            mAlert('请选择收货方式');
            return false;
        }


        if(!$('input[name="address-show"]').attr('cus-select-value')){
            mAlert('请选择收货地址');
            return false;
        }

        return true;
    },
    callback:function(obj,data){
        if(data.status) {
            location.href = "/user/pay-bill?order_id=" + data.data;
        } else {
            mAlert(data.desc);
        }
    },
    data:function()
    {
        var product_attr_id = $('input[name="product_id"]').attr('cus-select-value');
        var immediate_phone = $('input[name="immediate_phone"]').val();
        var quantity = $('input[name="quantity"]').val();
        var deliver_type = $('input[name="deliver_type"]').attr('cus-select-value');
        var data = {buy_type:product_attr_id,immediate_phone:immediate_phone,deliver_type:deliver_type,quantity:quantity};
        if(deliver_type == 1){
            data = $.extend(data,{self_get_deliver_address:$('input[name="address-show"]').attr('cus-select-value')});
        } else {
            data = $.extend(data,{mine_deliver_address:$('input[name="address-show"]').attr('cus-select-value')});
        }
        return data;
        //return $("#form_data").serialize();
    }
});

var addressVue = new Vue({
    el:'.address-list-vue',
    data:{showFlag:0,currentValue:0},
    created:function(){
        $('.vue-none').removeClass('vue-none');
    },
    methods:{
        bingo:function(event){
            $dataAddress = $(event.currentTarget).attr('data-address');
            $('input[name="address-show"]').val($dataAddress);
            $('input[name="address-show"]').attr('cus-select-value',$(event.currentTarget).attr('data-id'));
            this.showFlag = 0;
        },
        show:function(){
            this.showFlag = 1;
        }
    }
});

</script>
@stop