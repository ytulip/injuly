@extends('admin.master',['headerTitle'=>'会员管理 > 购买管理'])
@section('left_content')
    <div class="mt-32 padding-col">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>购买总量</p>
                    <div>{{$orderStatical->countValidOrder()}}</div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>比例</p>
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <p class="font-12">{{$attrPercent['attr1']->quantity}}盒</p>
                            <p class="font-12">{{$attrPercent['attr2']->quantity}}盒</p>
                        </div>
                        <div class="col-md-8 col-lg-8">
                            <div>
                            <canvas id="chart-Doughnut" width="100%"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>待处理</p>
                    <div>{{$orderStatical->countWaitingDeal()}}</div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3"></div>
        </div>
        {{--<div class="">--}}
            {{--<input type="text"  style="margin-left: 14px" class="dateinput form-control" readonly/>--}}
        {{--</div>--}}
        <h4>购买信息</h4>
        <div class="row m-t-20">
            {{--<div class="col-md-2 col-lg-2">--}}
                {{--<h4>购买信息</h4>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--<label for="dtp_input2" class="col-md-2 control-label">Date Picking</label>--}}
                {{--<div class="input-group date form_date col-md-5" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">--}}
                    {{--<input class="form-control" size="16" type="text" value="" readonly>--}}
                    {{--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>--}}
                    {{--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>--}}
                {{--</div>--}}
                {{--<input type="hidden" id="dtp_input2" value="" /><br/>--}}
            {{--</div>--}}

            <div class="col-md-12 col-lg-12">
                <form class="form-inline" id="search_form">

                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">开始时间</label>
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('start_time')}}" name="start_time">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input2" value="" /><br/>
                    </div>


                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">结束时间</label>
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('end_time')}}" name="end_time">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input2" value="" /><br/>
                    </div>

                    {{--<div class="in-bl-line-item">开始时间:</div>--}}
                    {{--<div class="in-bl-line-item form_date" style="position: relative;">--}}
                        {{--<input type="text"  style="margin-left: 14px;width: 146px;" class="form-control" name="start_time" readonly value="{{\Illuminate\Support\Facades\Request::input('start_time')}}"/>--}}
                    {{--</div>--}}


                    {{--<div class="in-bl-line-item" style="margin-left: 42px;">结束时间:</div>--}}
                    {{--<div class="in-bl-line-item" style="position: relative;">--}}
                        {{--<input type="text"  style="margin-left: 14px;width: 146px;" class="dateinput form-control" name="end_time" readonly value="{{\Illuminate\Support\Facades\Request::input('end_time')}}"/>--}}
                    {{--</div>--}}


                    {{--<div class="form-group">--}}
                        {{--<div class="input-group" style="padding-bottom: 0;">--}}
                            {{--<select type="text" class="form-control" name="order_status" id="exampleInputAmount">--}}
                                {{--<option></option>--}}
                                {{--<option value="1" {{(\Illuminate\Support\Facades\Request::input('order_status') == 1)?' selected':''}}>未发货</option>--}}
                            {{--</select>--}}
                            {{--<div class="input-group-addon" onclick="search()"><i class="fa fa-search"></i></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <a class="btn btn-info" href="javascript:commonDownload()">下载</a>
                        </div>
                    </div>
                </form>
            </div>
            {{--<div class="col-md-2 col-lg-2">--}}
                {{--<a class="btn btn-info" href="javascript:commonDownload()" style="margin-top: 20px;">下载</a>--}}
            {{--</div>--}}
        </div>
        <div class="block-card">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">订单编号</div>
                <div class="col-md-2 col-lg-2">购买时间</div>
                <div class="col-md-2 col-lg-2">购买人姓名</div>
                <div class="col-md-2 col-lg-2">联系方式</div>
                <div class="col-md-1 col-lg-1">购买数量</div>
                <div class="col-md-1 col-lg-1">购买价格</div>
                <div class="col-md-2 col-lg-2">购买类型</div>
            </div>
            @foreach($paginate as $item)
                <div class="row paginate-list-row" onclick="goDetail({{$item->order_id}})">
                    <div class="col-md-2 col-lg-2">{{$item->order_id}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->pay_time}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->real_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->phone}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->quantity}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->need_pay}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->buyTypeText()}}</div>
                </div>
            @endforeach
        </div>
        <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>

    </div>
@stop

@section('script')
    <script src="/admin/js/Chart.js"></script>
    <script>
        var pageConfig = {
            doughnutData:[{value:{{$attrPercent['attr1_count']}},color:'#2c82be',label:{{$attrPercent['attr1_count']}}},{value:{{$attrPercent['attr2_count']}},color:'#76ddfb',label:{{$attrPercent['attr2_count']}}}]
        }

        function goDetail(id)
        {
            location.href = '/admin/index/order-detail?order_id=' + id;
        }

        var ctx = document.getElementById("chart-Doughnut").getContext("2d");
        window.myDoughnut = new Chart(ctx).Doughnut(pageConfig.doughnutData, {responsive : true});


        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        // $('.form_date').datetimepicker({
        //     language:  'zh-CN',
        //     weekStart: 1,
        //     todayBtn:  1,
        //     autoclose: 1,
        //     todayHighlight: 1,
        //     startView: 2,
        //     minView: 2,
        //     forceParse: 0
        // });


//        new Chart().Doughnut({},{});
    </script>
@stop