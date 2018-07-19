@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 概况'])
@section('style')
 <style>
     .out-line{background-color:#f0f0f0;line-height: 40px;font-size: 12px;margin-top: 20px;}
 </style>
    @stop
@section('left_content')
    <div class="mt-32 padding-col">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>现有资产</p>
                    <p class="price-big">￥{{$totalStatical->incomeOutcome}}</p>
                    <div><i class="increment-icon"></i>￥{{\App\Util\Kit::priceFormat($totalStatical->preMonth->incomeOutcome)}}</div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>支付宝资产</p>
                    <p class="price-big">￥{{$totalStatical->alipayIncomeOutcome}}</p>
                    <div><i class="increment-icon"></i>￥{{\App\Util\Kit::priceFormat($totalStatical->preMonth->alipayIncomeOutcome)}}</div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>微信资产</p>
                    <p class="price-big">￥{{$totalStatical->wechatIncomeOutcome}}</p>
                    <div><i class="increment-icon"></i>￥{{\App\Util\Kit::priceFormat($totalStatical->preMonth->wechatIncomeOutcome)}}</div>
                </div>

            </div>
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>资产分布</p>
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <p class="font-12">盒</p>
                            <p class="font-12">盒</p>
                        </div>
                        <div class="col-md-8 col-lg-8">
                            <div>
                                <canvas id="chart-Doughnut" width="100%"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="mt-32 padding-col">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="block-card">
                    <p>现有资产</p>
                    <div id="statical" style="width: 100%;height: 240px;"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-32 padding-col">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>总收入</p>
                    <p class="price-big">￥{{$totalStatical->income}}</p>
                    <div><i class="increment-icon"></i>￥{{\App\Util\Kit::priceFormat($totalStatical->preMonth->income)}}</div>
                </div>
            </div>

            <div class="col-md-9 col-lg-9">
                <div class="block-card">
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <p>总支出</p>
                            <p class="price-big">￥{{$withdraw + $directIndirect + $upSuperCount}}</p>
                            <div><i class="increment-icon"></i>￥{{\App\Util\Kit::priceFormat($totalStatical->preMonth->outcome)}}</div>
                        </div>
                        <div class="col-md-8 col-lg-8">
                            <div class="row out-line">
                                <div class="col-md-3 col-lg-3">提现支出</div>
                                <div class="col-md-4 col-lg-4">提现次数:{{$withdrawCount}}次</div>
                                <div class="col-md-5 col-lg-5">提现总金额:￥{{$withdraw}}</div>
                            </div>
                            <a href="/admin/index/direct-indirect-record-all"><div class="row out-line">
                                <div class="col-md-3 col-lg-3">开发分红</div>
                                <div class="col-md-4 col-lg-4">开发次数:{{$directIndirectCount}}次</div>
                                <div class="col-md-5 col-lg-5">开发分红总金额：￥{{$directIndirect}}</div>
                                </div></a>
                            <a href="/admin/index/up-super-record-all"><div class="row out-line">
                                <div class="col-md-3 col-lg-3">辅导分红</div>
                                <div class="col-md-4 col-lg-4">辅导次数:{{$upSuperCount}}次</div>
                                <div class="col-md-5 col-lg-5">辅导分红总金额:￥{{$upSuper}}</div>
                                </div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="/admin/js/Chart.js"></script>
    <script>
        var pageConfig = {
            members:{{$totalStatical->increaseMember}},
            vip:{{$totalStatical->increaseVip}},
            master:{{$totalStatical->increaseMaster}},
            dataList:{!!json_encode($totalStatical->orderGraph)!!},
            doughnutData:[{value:{{$totalStatical->alipayIncomeOutcome}},color:'#2c82be',label:{{$totalStatical->alipayIncomeOutcome}}},{value:{{$totalStatical->wechatIncomeOutcome}},color:'#76ddfb',label:{{$totalStatical->wechatIncomeOutcome}}}]
        };


        var ctx = document.getElementById("chart-Doughnut").getContext("2d");
        window.myDoughnut = new Chart(ctx).Doughnut(pageConfig.doughnutData, {responsive : true});

        // 基于准备好的dom，初始化echarts实例
//        var myChart = echarts.init(document.getElementById('main'));
        var staticalChart = echarts.init(document.getElementById('statical'));

        // 指定图表的配置项和数据
//        option = {
//            tooltip: {
//                trigger: 'item',
//                formatter: "{a} <br/>{b}: {c} ({d}%)"
//            },
//            legend: {
//                orient: 'vertical',
//                x: 'left',
//                data:['直接访问','邮件营销']
//            },
//            series: [
//                {
//                    name:'访问来源',
//                    type:'pie',
//                    radius: ['50%', '70%'],
//                    avoidLabelOverlap: false,
//                    label: {
//                        normal: {
//                            show: false,
//                            position: 'center'
//                        },
//                        emphasis: {
//                            show: true,
//                            textStyle: {
//                                fontSize: '30',
//                                fontWeight: 'bold'
//                            }
//                        }
//                    },
//                    labelLine: {
//                        normal: {
//                            show: false
//                        }
//                    },
//                    data:[
//                        {value:335},
//                        {value:310},
//                    ]
//                }
//            ]
//        };

        //    data = [["2000-06-05",116],["2000-06-06",129],["2000-06-07",135],["2000-06-08",86],["2000-06-09",73],["2000-06-10",85],["2000-06-11",73],["2000-06-12",68],["2000-06-13",92],["2000-06-14",130],["2000-06-15",245],["2000-06-16",139],["2000-06-17",115],["2000-06-18",111],["2000-06-19",309],["2000-06-20",206],["2000-06-21",137],["2000-06-22",128],["2000-06-23",85],["2000-06-24",94],["2000-06-25",71],["2000-06-26",106],["2000-06-27",84],["2000-06-28",93],["2000-06-29",85],["2000-06-30",73],["2000-07-01",83],["2000-07-02",125],["2000-07-03",107],["2000-07-04",82],["2000-07-05",44],["2000-07-06",72],["2000-07-07",106],["2000-07-08",107],["2000-07-09",66],["2000-07-10",91],["2000-07-11",92],["2000-07-12",113],["2000-07-13",107],["2000-07-14",131],["2000-07-15",111],["2000-07-16",64],["2000-07-17",69],["2000-07-18",88],["2000-07-19",77],["2000-07-20",83],["2000-07-21",111],["2000-07-22",57],["2000-07-23",55],["2000-07-24",60]];
        data = pageConfig.dataList;

        var dateList = data.map(function (item) {
            return item[0];
        });
        var valueList = data.map(function (item) {
            return item[1];
        });

        optionStatical = {

            // Make gradient line here
            visualMap: [{
                show: false,
                type: 'continuous',
                seriesIndex: 0,
                min: 0,
                max: 400
            }],


            title: [{
                left: 'center',
                text: 'Gradient along the y axis'
            }],
            tooltip: {
                trigger: 'axis'
            },
            xAxis: [{
                data: dateList
            }],
            yAxis: [{
                splitLine: {show: false}
            }],
            grid: [{
                bottom: '10%'
            }],
            series: [{
                type: 'line',
                showSymbol: false,
                data: valueList
            }]
        };;

        // 使用刚指定的配置项和数据显示图表。
//        myChart.setOption(option);
        staticalChart.setOption(optionStatical);
    </script>
@stop
