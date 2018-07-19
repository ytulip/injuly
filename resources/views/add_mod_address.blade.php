@extends('_layout.master')
@section('title')
    <title>新增（修改）地址</title>
@stop
@section('style')
    <style>
        html,body{background-color: #F8F8F8;}
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
            padding: 0 17px !important;
            overflow: hidden;
        }
        .city-panel-header a{
            line-height: 48px;
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
            width: 50%;
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
        input::-webkit-input-placeholder{color: #bebebe;  }
        textarea::-webkit-input-placeholder{color: #bebebe;  }


        .mui-input-row label {
            font-family: 'Helvetica Neue', Helvetica, sans-serif;
            line-height: 1.1;
            float: left;
            width: 30%;
            padding: 15px 15px;
        }


        .mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea {
            float: right;
            width: 70%;
            margin-bottom: 0;
            padding-left: 0;
            border: 0;
        }


        select, textarea, input[type='text'], input[type='search'], input[type='password'], input[type='datetime'], input[type='datetime-local'], input[type='date'], input[type='month'], input[type='time'], input[type='week'], input[type='number'], input[type='email'], input[type='url'], input[type='tel'], input[type='color'] {
            padding: 14px 15px !important;
            margin-bottom: 0;
            border:none;
        }

        .mui-input-row {
            margin-top: 2px;
        }
    </style>
@stop
@section('container')

    <div class="cus-row p-l-r-14">
        <div class="cus-row-col-4"><a href="/user/addresses"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">添加新地址</span></div>
        <div class="cus-row-col-4 t-al-r"><a href="javascript:void(0);" id="next_step"><span class="fs-16-fc-212229">保存</span></a></div>
    </div>

    <form id="form_data">
        <input name="address_id" value="{{\App\Util\Kit::issetThenReturn($address,'address_id')}}" style="display: none;"/>
        <input name="is_default" value=@if(!\App\Util\Kit::issetThenReturn($address,'is_default')) 0 @else 1 @endif style="display: none;"/>
    <div>

        {{--<div class="mui-input-row">--}}
            {{--<label><span class="fs-16-fc-030303">收货人</span></label>--}}
            {{--<input type="text" class="mui-input-clear fs-16-fc-030303" name="real_name" value="{{\Illuminate\Support\Facades\Request::input('real_name')}}" >--}}
        {{--</div>--}}
        {{--<div class="mui-input-row">--}}
            {{--<label><span class="fs-16-fc-030303">联系电话</span></label>--}}
            {{--<input type="text" class="mui-input-clear fs-16-fc-030303" name="id_card" value="{{\Illuminate\Support\Facades\Request::input('id_card')}}">--}}
        {{--</div>--}}

        {{--<div class="mui-input-row">--}}
            {{--<label><span class="fs-16-fc-030303">所在地区</span></label>--}}
            {{--<input type="text" class="mui-input-clear fs-16-fc-030303" name="id_card" value="{{\Illuminate\Support\Facades\Request::input('id_card')}}">--}}
        {{--</div>--}}

        {{--<div class="mui-input-row input-row-type1">--}}
            {{--<label>详细地址:</label>--}}
            {{--<textarea type="text" class="mui-input-clear noscrollbars" name="address" onkeyup="autoGrow(this);">{{\App\Util\Kit::issetThenReturn($address,'pct_code_name') . \App\Util\Kit::issetThenReturn($address,'address')}}</textarea>--}}
        </div>

        <div class="cus-info-panel cus-info-panel-20">
            <div class="cus-info-panel-line inner-line">
                <div class="cus-row cus-row-v-m">
                    <div class="cus-row-col-3" style="line-height: 48px;">
                        <span class="fs-14-fc-212229">收货人</span>
                    </div>
                    <div class="cus-row-col-8">
                        <input type="text" placeholder="输入收货人姓名" class="mui-input-clear fs-14-fc-212229" name="real_name" value="{{\App\Util\Kit::issetThenReturn($address,'address_name')}}">
                    </div>
                    <div class="cus-row-col-1"></div>
                </div>
            </div>

            <div class="cus-info-panel-line inner-line">
                <div class="cus-row">
                    <div class="cus-row-col-3" style="line-height: 48px;">
                        <span class="fs-14-fc-212229">联系电话</span>
                    </div>
                    <div class="cus-row-col-8">
                        <input placeholder="输入联系电话" type="text" class="mui-input-clear fs-14-fc-212229" name="phone" value="{{\App\Util\Kit::issetThenReturn($address,'mobile')}}">
                    </div>
                    <div class="cus-row-col-1"></div>
                </div>
            </div>

            <div class="cus-info-panel-line inner-line">
                <div class="cus-row">
                    <div class="cus-row-col-3" style="line-height: 48px;">
                        <span class="fs-14-fc-212229">所在地区</span>
                    </div>
                    <div class="cus-row-col-8">
                        <div id="city">
                        <j-city cname="city_code" cvalue="{{\App\Util\Kit::issetThenReturn($address,'pct_code')}}"></j-city>
                        </div>
                    </div>
                    <div class="cus-row-col-1 t-al-r"><span class="next-icon"></span></div>
                </div>
            </div>

            <div class="cus-info-panel-line">
                <div class="cus-row cus-row-v-t">
                    <div class="cus-row-col-3" style="line-height: 48px;">
                        <span class="fs-14-fc-212229">详细地址</span>
                    </div>
                    <div class="cus-row-col-8">
                        <textarea type="text" class="mui-input-clear noscrollbars fs-14-fc-212229" name="address" placeholder="街道、门牌号等" onkeyup="autoGrow(this);">{{\App\Util\Kit::issetThenReturn($address,'pct_code_name') . \App\Util\Kit::issetThenReturn($address,'address')}}</textarea>
                    </div>
                    <div class="cus-row-col-1"></div>
                </div>
            </div>
        </div>


        {{--<div class="mui-input-row input-row-type1">--}}
            {{--<label><span class="fs-14-fc-212229">收货人</span></label>--}}
            {{--<input type="text" placeholder="输入收货人姓名" class="mui-input-clear fs-14-fc-212229" name="real_name" value="{{\App\Util\Kit::issetThenReturn($address,'address_name')}}">--}}
        {{--</div>--}}

        {{--<div class="mui-input-row input-row-type1">--}}
            {{--<label><span class="fs-14-fc-212229">联系电话</span></label>--}}
            {{--<input placeholder="输入联系电话" type="text" class="mui-input-clear fs-14-fc-212229" name="phone" value="{{\App\Util\Kit::issetThenReturn($address,'mobile')}}">--}}
        {{--</div>--}}

        {{--<div class="mui-input-row input-row-type1" style="position: relative;">--}}
            {{--<label><span class="fs-14-fc-212229">所在地区</span></label>--}}
            {{--<div id="city">--}}
                {{--<j-city cname="city_code" cvalue="{{\App\Util\Kit::issetThenReturn($address,'pct_code')}}"></j-city>--}}
            {{--</div>--}}
            {{--<div style="position: absolute;top:50%;right:16px;transform:translateY(-50%) ">--}}
                {{--<span class="next-icon"></span>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="mui-input-row input-row-type1">--}}
            {{--<label><span class="fs-14-fc-212229">详细地址</span></label>--}}
            {{--<textarea type="text" class="mui-input-clear noscrollbars fs-14-fc-212229" name="address" placeholder="街道、门牌号等" onkeyup="autoGrow(this);">{{\App\Util\Kit::issetThenReturn($address,'pct_code_name') . \App\Util\Kit::issetThenReturn($address,'address')}}</textarea>--}}
        {{--</div>--}}

        <div class="cus-row" style="padding: 0 16px;background-color: #ffffff;border: 1px solid #EBE9E9;margin-top: 30px;">
            <div class="cus-row-col-6"><span class="fs-14-fc-212229" style="line-height: 48px;" >设为默认</span></div>
            <div class="cus-row-col-6 t-al-r in-bl-v-m"><span class="@if(!\App\Util\Kit::issetThenReturn($address,'is_default')) off-icon @else on-icon @endif" onclick="setDefault()" id="default"></span></div>

        </div>
    </div>

    </form>
    {{--<footer class="fix-bottom">--}}
        {{--<div><a class="btn-block1 remove-radius" id="next_step">保存</a></div>--}}
    {{--</footer>--}}
@stop

@section('script')
<script src="/js/vue.js"></script>
<script type="text/x-template" id="j-city">
    <div>
        <input v-bind:name="cname" type="hidden" v-model="hold.id"/>
        <input v-model="hold.name" class="fs-14-fc-212229" style="line-height: 20px;padding: 14px 15px;" v-on:click="maskshow = 1" readonly onfocus='this.blur();'/>
        <div class="city-mask" v-if="maskshow">
            <div class="city-panel">
                <div class="city-panel-header"><a v-on:click="no">取消</a><a v-on:click="yes">确定</a></div>
                <div class="city-panel-body">
                    <div class="city-barrier"></div>
                    <ul class="province-list cityul" data-classify="p" v-on:touchstart="cityTouchStart" v-on:touchmove="cityTouchMove" v-on:touchend="cityTouchEnd" v-bind:style="{ top: ptop + 'px' }"><li v-for="province in provinces" v-bind:data-id="province.id"><a>@{{ province.name }}</a></li></ul>
                    <ul class="city-list cityul" data-classify="c" v-on:touchstart="cityTouchStart" v-on:touchmove="cityTouchMove" v-on:touchend="cityTouchEnd" v-bind:style="{ top: ctop + 'px' }"><li v-for="city in citys" v-bind:data-id="city.id"><a>@{{ city.name }}</a></li></ul>
                </div>env
            </div>
        </div>
    </div>
</script>
<script src="/js/citydata.js"></script>
<script>
    var pageConfig = {
        callback:'{!! urldecode(\Illuminate\Support\Facades\Request::input('callback',urlencode('/user/addresses'))) !!}',
        address_id:'{{\App\Util\Kit::issetThenReturn($address,'id')}}'
    };

    function autoGrow (oField) {
        if (oField.scrollHeight > oField.clientHeight) {
            oField.style.height = oField.scrollHeight + "px";
        }
    }

    function setDefault()
    {
        if( $('#default').hasClass('on-icon') ){
            $('#default').removeClass('on-icon').addClass('off-icon');
            $('input[name="is_default"]').val(0);
        } else {
            $('#default').removeClass('off-icon').addClass('on-icon');
            $('input[name="is_default"]').val(1);
        }
    }


    Vue.component('j-city', {
        // 声明 props
        props: ['cname','cvalue'],
        // 就像 data 一样，prop 可以用在模板内
        // 同样也可以在 vm 实例中像 “this.message” 这样使用
        template: '#j-city',
        data:function(){
            var datas = {};
            datas.lineHeight = 37;//单位为px
            datas.lines = 2;
            datas.cityMap = new CityData();
            datas.maskshow = 0;
            datas.provinces = datas.cityMap.provinces;
            datas.citys = [];
            datas.ptop = datas.lineHeight * datas.lines;
            datas.ctop = datas.lineHeight * datas.lines;
            datas.startPoint = {y:0,classify:''};
            datas.movePoint = {y:0};
            datas.hold = {id:this.cvalue,name:datas.cityMap.codeName(this.cvalue)}
            return datas;
        },
        created:function(){
//            this.citys = this.cityMap.cityList(this.hold.id);
            this.calcTop(this.hold.id);
        },
        // 在 `methods` 对象中定义方法
        methods: {
            cityTouchStart:function(e){
                e.preventDefault();
                this.startPoint.y = e.targetTouches[0].screenY;
                var target = e.target;
                while (true) {
                    if (!target.classList.contains("cityul")) {
                        target = target.parentElement;
                    } else {
                        break
                    }
                }
                //this.startPoint.classify = $(target).attr('data-classify');
                this.startPoint.classify = target.getAttribute('data-classify');
            },
            cityTouchMove:function(e){
                e.preventDefault();
                this.movePoint.y = e.targetTouches[0].screenY;
                if(this.startPoint.classify == 'p'){
                    this.ptop = this.ptop + (this.movePoint.y - this.startPoint.y);
                }else if(this.startPoint.classify == 'c'){
                    this.ctop = this.ctop + (this.movePoint.y - this.startPoint.y);
                }else{
                    return false;
                }
                this.startPoint.y = this.movePoint.y;
            },
            cityTouchEnd:function(e){
                var length = 0;
                if(this.startPoint.classify == 'p'){
                    length = this.provinces.length;
                }else if(this.startPoint.classify == 'c'){
                    length = this.citys.length;
                }else{
                    return false;
                }

                heightLimit = [this.lines * this.lineHeight, -(length - this.lines -1) * this.lineHeight];

                var remainder = '';
                if(this.startPoint.classify == 'p') {
                    if(this.ptop > heightLimit[0]){
                        this.ptop = 74;
                    }else if(this.ptop < heightLimit[1]){
                        this.ptop = -(this.provinces.length - 3)* 37;
                    }else{
                        remainder = this.ptop % 37;
                        if(remainder <= 0 && remainder > - 18){
                            this.ptop = parseInt(this.ptop / 37) * 37;
                        }else{
                            this.ptop = parseInt(this.ptop / 37 - 1) * 37;
                        }
                    }

                    /*调取相对应的市*/
                    var provinceNow = this.provinceNow();
                    //this.citys = provinceNow.child;
                    this.calcTop(provinceNow['id']);

                }else if(this.startPoint.classify == 'c'){
                    if(this.ctop > heightLimit[0]){
                        this.ctop = 74;
                    }else if(this.ctop < heightLimit[1]){
                        this.ctop = -(this.citys.length - 3)* 37;
                    }else{
                        remainder = this.ptop % 37;
                        if(remainder <= 0 && remainder > - 18){
                            this.ctop = parseInt(this.ctop / 37) * 37;
                        }else{
                            this.ctop = parseInt(this.ctop / 37 - 1) * 37;
                        }
                    }
                }
            },
            calcTop:function(code){
                var ind = this.cityMap.provinceIndex(code);
                this.ptop =  -(ind - this.lines) * this.lineHeight;
                this.citys = this.provinceNow().child;
                var ind2 = this.cityMap.cityIndex(code);
                this.ctop = -(ind2 - this.lines) * this.lineHeight;
            },
            provinceNow:function(){
                var index = this.lines - (this.ptop / this.lineHeight);
                return this.provinces[index];
            },
            cityNow:function(){
                var index = this.lines - (this.ctop / this.lineHeight);
                return this.citys[index];
            },
            no:function(){
                this.maskshow = 0;
                this.calcTop(this.hold.id);
            },
            yes:function(){
                this.maskshow = 0;
                var cityCode = this.cityNow().id;
                this.hold = {
                    id:cityCode,
                    name:this.cityMap.codeName(cityCode)
                };
                this.calcTop(this.hold.id);
            }
        }
    });

    new Vue({
        el:'#city'
    });

    new SubmitButton({
        selectorStr:"#next_step",
        url:"/user/add-mod-address",
        callback:function(obj,data){
            if(data.status) {
                if(pageConfig.callback != '/user/addresses') {
                    location.href = pageConfig.callback + '&address_id=' + data.data;
                } else {
                    location.href = pageConfig.callback;
                }
            } else {
                mAlert(data.desc);
            }
        },
        data:function()
        {
            return $("#form_data").serialize();
        }
    });
</script>
@stop