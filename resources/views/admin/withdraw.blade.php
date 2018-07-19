@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 提现管理'])
@section('left_content')
    <div class="mt-32 padding-col">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <div class="block-card">
                    <p>已提现奖金</p>
                    <p class="price-big">￥{{\App\Model\CashStream::where('cash_type',\App\Model\CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->sum('price') - \App\Model\CashStream::where('cash_type',\App\Model\CashStream::CASH_TYPE_WITHDRAW_REFUSE)->sum('price')}}</p>
                </div>
            </div>


            <div class="col-md-3 col-lg-3" onclick="goHref('/admin/index/members?charge=1')">
                <div class="block-card">
                    <p>未提现奖金</p>
                    <p class="price-big">￥{{\App\Model\User::sum('charge')}}</p>
                </div>
            </div>
            {{--<div class="col-md-3 col-lg-3">--}}
                {{--<div class="block-card">--}}
                    {{--<p>支付宝待处理申请金额</p>--}}
                    {{--<p class="price-big">￥{{$withdrawInfo['alipayNeedDeal']}}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-3 col-lg-3">--}}
                {{--<div class="block-card">--}}
                    {{--<p>微信资产</p>--}}
                    {{--<p class="price-big">￥{{$withdrawInfo['wechat']}}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-3 col-lg-3">--}}
                {{--<div class="block-card">--}}
                {{--<p>微信待处理申请金额</p>--}}
                    {{--<p class="price-big font-color3">￥{{$withdrawInfo['wechatNeedDeal']}}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <h4>提现申请</h4>
        <div class="row m-t-20">
            <div class="col-md-12 col-lg-12">
                <form class="form-inline" id="search_form">

                        <div class="form-group">
                            <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">开始时间</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('start_time')}}" name="start_time" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" id="dtp_input2" value="" /><br/>
                        </div>

                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">结束时间</label>
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('end_time')}}" name="end_time" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input2" value="" /><br/>
                    </div>

                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <select type="text" class="form-control" name="withdraw_deal_status" id="exampleInputAmount">
                                <option></option>
                                <option value="0" {{(\Illuminate\Support\Facades\Request::input('withdraw_deal_status') === '0')?' selected':''}}>待处理</option>
                                <option value="1" {{(\Illuminate\Support\Facades\Request::input('withdraw_deal_status') == 1)?' selected':''}}>通过</option>
                                <option value="2" {{(\Illuminate\Support\Facades\Request::input('withdraw_deal_status') == 2)?' selected':''}}>拒绝</option>
                            </select>
                        </div>
                    </div>


                        <div class="form-group v-a-b">
                            <div class="input-group">
                                <input type="text" class="form-control" id="exampleInputAmount" placeholder="输入姓名、手机号搜索" name="keyword" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}">
                                <div class="input-group-addon"><i class="fa fa-search" onclick="search()"></i></div>
                            </div>
                        </div>

                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <a class="btn btn-info" href="javascript:commonDownload()">下载</a>
                        </div>
                    </div>
                </form>
            </div>
            {{--<div class="col-md-2 col-lg-2">--}}
                {{--<a class="btn btn-info" href="javascript:commonDownload()">下载</a>--}}
            {{--</div>--}}
        </div>
        <div class="block-card">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">申请时间</div>
                <div class="col-md-2 col-lg-2">申请人姓名</div>
                <div class="col-md-2 col-lg-2">联系方式</div>
                <div class="col-md-2 col-lg-2">申请金额</div>
                <div class="col-md-2 col-lg-2">提现方式</div>
                <div class="col-md-2 col-lg-2">处理状态</div>
            </div>
            @foreach($paginate as $item)
                <div class="row paginate-list-row" onclick="goDetail({{$item->withdraw_id}})">
                    <div class="col-md-2 col-lg-2">{{$item->withdraw_created_at}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->real_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->phone}}</div>
                    <div class="col-md-2 col-lg-2">￥{{number_format($item->price,2)}}</div>
                    <div class="col-md-2 col-lg-2">{{\App\Model\CashStream::withdrawTypeText($item->withdraw_type)}}</div>
                    <div class="col-md-2 col-lg-2">{{\App\Model\CashStream::withdrawStatusText($item->withdraw_deal_status)}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>

    </div>
    @stop

@section('script')
<script>
    function goDetail(id)
    {
        location.href = '/admin/index/withdraw-detail?withdraw_id=' + id;
    }

    function search()
    {
//    alert(1);
//    $val = $('#search_user').val();
        $('#search_form').submit();

    }
</script>
    @stop