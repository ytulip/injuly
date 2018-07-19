@extends('_layout.master')
@section('title')
    <title>账单</title>
@stop
@section('style')
    <style>
        html,body{background-color: rgb(239,243,246);}
        footer .in-bl-line{line-height: 40px;}

        .income-list .cus-row{position: relative;    padding: 8px 15px;}

        .income-list .cus-row:after{
            position: absolute;
            right: 0;
            bottom: 0;
            left: 15px;
            height: 1px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: #c8c7cc;
        }
    </style>


    <style>
        #city *{
            padding: 0;margin: 0;
        }
        html,body{background-color: rgb(239,243,246);}
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
            border-bottom: solid 1px #e2e2e2;
        }
        .city-panel-header a{
            line-height: 48px;
            /*padding: 0 12px;*/
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
    </style>
    {{--<link rel="stylesheet" href="/js/mui.ext.dtpicker.css"/>--}}
    {{--<link rel="stylesheet" href="/js/mui.picker.css"/>--}}
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'账单'])--}}
    <div class="cus-row p-l-r-14 cus-row-v-m">
        <div class="cus-row-col-4"><a href="/user/finance"><i class="back-icon"></i></a></div>
        <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">账单</span></div>
        <div class="cus-row-col-4 t-al-r" id="city">
            {{--<i class="fa fa-calendar" style="font-size: 21px;line-height: 68px;display: inline-block;"></i>--}}
            <j-city cname="city_code" cvalue=""></j-city>
        </div>
    </div>

    <div id="check_by_month_list">
        {{--<ul class="mui-table-view income-list">--}}
                {{--<li class="mui-table-view-cell bg-color-2">--}}
                    {{--本月--}}
                {{--</li>--}}
        {{--</ul>--}}
        {{--<ul class="mui-table-view income-list">--}}
            {{--<li class="mui-table-view-cell">--}}
                {{--10月--}}
            {{--</li>--}}
        {{--</ul>--}}
    </div>
@stop

@section('script')

    <script type="text/x-template" id="j-city">
        <div>
            <input v-bind:name="cname" type="hidden" v-model="hold.id"/>

            <span class="calendar-icon" v-on:click="maskshow = 1"></span>
            {{--<input v-model="hold.name" v-on:click="maskshow = 1" readonly/>--}}
            <div class="city-mask" v-if="maskshow">
                <div class="city-panel">
                    <div class="city-panel-header"><a v-on:click="no">取消</a><a v-on:click="yes">确定</a></div>
                    <div class="city-panel-body">
                        <div class="city-barrier"></div>
                        <ul class="province-list cityul" data-classify="p" v-on:touchstart="cityTouchStart" v-on:touchmove="cityTouchMove" v-on:touchend="cityTouchEnd" v-bind:style="{ top: ptop + 'px' }"><li v-for="province in provinces" v-bind:data-id="province.id"><a>@{{ province.name }}</a></li></ul>
                        <ul class="city-list cityul" data-classify="c" v-on:touchstart="cityTouchStart" v-on:touchmove="cityTouchMove" v-on:touchend="cityTouchEnd" v-bind:style="{ top: ctop + 'px' }"><li v-for="city in citys" v-bind:data-id="city.id"><a>@{{ city.name }}</a></li></ul>
                    </div>
                </div>
            </div>
        </div>
    </script>
<script src="/js/vue.js"></script>
    <script src="/js/citydata.js"></script>
{{--<script src="/js/mui.picker.js"></script>--}}
{{--<script src="/js/mui.ext.dtpicker.js"></script>--}}
<script>
    var pageConfig = {
      year:'{{date('Y')}}',
      month:'{{date('m')}}',
      yearMonth:'{{date('Y-m')}}'
    };
//    new Vue({
//        el:
//    });


    function initList(month)
    {
        if(typeof(month) == 'undefined'){
            pData = {};
        }else{
            pData = {month:month};
        }
        $.get('/user/search-check?all=1',pData,function(data){
            $('#check_by_month_list').html('');

            if(!data.data.length){
                $('#check_by_month_list').html('<div class="t-al-c" style="margin-top: 80px;"><img src="/images/icon_img.png" style="display: inline-block;"/></div>\n' +
                    '                <div class="t-al-c" style="margin-top: 24px;"><span style="font-size: 16px;color:#a8a8a8;">暂无内容</span></div>');
                return;
            }

            $.each(data.data,function(obj,ind){
                if( !$('ul[data-month="'+ind.created_month+'"]').length )
                {
                    //创建一个ul
                    var monthTxt = ind.created_month;
                    if( ind.created_month == pageConfig.yearMonth) {
                        monthTxt = "本月";
                    }

                    if(monthTxt.indexOf(pageConfig.year) != -1)
                    {
                        monthTxt = ind.created_month.substr(5,2) + "月";
                    }

                    $('#check_by_month_list').append('<ul class="mui-table-view income-list" data-month="'+ind.created_month+'"><li class="mui-table-view-cell"><span style=";border-left:solid 6px #98CC3D;margin-right: 10px;"></span>'+monthTxt+'</li></ul>');
                }

                //填充数据
                $('ul[data-month="'+ind.created_month+'"]').append('<li class="cus-row cus-row-v-m"><div class="cus-row-col-6"><span class="fs-16-fc-212229">'+ind.cash_type_text+'</span><br/><span class="fs-12-fc-909094">'+ind.created_at_text+'</span></div><div class="cus-row-col-6 t-al-r"><span class="fs-17-fc-212229">'+ind.price_with_char+'</span></div></li>');

            });
        },'json').error(function(){
            mAlert('网络异常！');
        });
    }

    $(function(){
        initList();
//        $.get('/user/search-check?all=1',{},function(data){
//            $.each(data.data,function(obj,ind){
//                if( !$('ul[data-month="'+ind.created_month+'"]').length )
//                {
//                    //创建一个ul
//                    var monthTxt = ind.created_month;
//                    if( ind.created_month == pageConfig.yearMonth) {
//                        monthTxt = "本月";
//                    }
//
//                    if(monthTxt.indexOf(pageConfig.year) != -1)
//                    {
//                        monthTxt = ind.created_month.substr(5,2) + "月";
//                    }
//
//                    $('#check_by_month_list').append('<ul class="mui-table-view income-list" data-month="'+ind.created_month+'"><li class="mui-table-view-cell"><span style=";border-left:solid 6px #98CC3D;margin-right: 10px;"></span>'+monthTxt+'</li></ul>');
//                }
//
//                //填充数据
//                $('ul[data-month="'+ind.created_month+'"]').append('<li class="cus-row cus-row-v-m"><div class="cus-row-col-6"><span class="fs-16-fc-212229">'+ind.cash_type_text+'</span><br/><span class="fs-12-fc-909094">'+ind.created_at_text+'</span></div><div class="cus-row-col-6 t-al-r"><span class="fs-17-fc-212229">'+ind.price_with_char+'</span></div></li>');
//
//            });
//        },'json').error(function(){
//            mAlert('网络异常！');
//        });
    });

//    var dtPicker = new mui.DtPicker({
//        type:"month"
//    });


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
            datas.cityMap = new CityData({url:"/index/calendar-json"});
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

                            if(this.provinces.length == 1) {
                                this.ptop = 37;
//                                pp.style.top = thisClass.ptop + 'px'
//                                return;
                            }

                            if(this.provinces.length == 2){
                                this.ptop = 74;
//                                pp.style.top = thisClass.ptop + 'px'
//                                return;
                            }else{
                                this.ptop = parseInt(this.ptop / 37 - 1) * 37;
                            }
//                            this.ptop = parseInt(this.ptop / 37 - 1) * 37;
                        }
                    }

                    /*调取相对应的市*/
                    var provinceNow = this.provinceNow();
                    console.log(provinceNow);
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


                var month = 20  + parseInt(cityCode / 10000).toString() + '-' + parseInt(cityCode / 100).toString().substring(2);

                //ajax请求
                initList(month);

            }
        }
    });

    new Vue({
        el:'#city'
    });
</script>
@stop