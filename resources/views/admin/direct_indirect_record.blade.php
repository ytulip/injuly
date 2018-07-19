@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 开发记录'])
@section('style')
    <style>
        .cus-select{border: none;padding: 0;}
    </style>
@stop
@section('left_content')
    <form id="data_form">
        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">{{$user->real_name}}的辅导记录</div>
        </div>

        <div class="block-card m-t-10">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">开发时间</div>
                <div class="col-md-1 col-lg-1">
                    <select class="cus-select" name="cash_type"><option value="">开发方式</option><option value="301" @if(\Illuminate\Support\Facades\Request::input('cash_type')==301) selected @endif>直接</option><option value="302" @if(\Illuminate\Support\Facades\Request::input('cash_type')==302) selected @endif>间接</option></select>
                </div>
                <div class="col-md-2 col-lg-2">
                    <select class="cus-select" name="vip_level"><option value="">开发等级</option><option value="1" @if(\Illuminate\Support\Facades\Request::input('vip_level')==1) selected @endif>VIP会员</option><option value="2" @if(\Illuminate\Support\Facades\Request::input('vip_level')==2) selected @endif>高级会员</option></select>
                </div>
                <div class="col-md-2 col-lg-2">新会员姓名</div>
                <div class="col-md-2 col-lg-2">新会员手机号</div>
                <div class="col-md-2 col-lg-2">注册时间</div>
                <div class="col-md-1 col-lg-1">收入</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row" onclick="goDetail({{$val->refer_id}})">
                    <div class="col-md-2 col-lg-2">{{\App\Util\Kit::dateFormat($val->created_at)}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->cash_type == \App\Model\CashStream::CASH_TYPE_BENEFIT_DIRECT?'直接':'间接'}}</div>
                    <div class="col-md-2 col-lg-2">{{\App\Model\User::levelText($val->vip_level)}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_model->real_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_model->phone}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_model->created_at}}</div>
                    <div class="col-md-1 col-lg-1">￥{{$val->pay_status?$val->price:'0.00'}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->render(); ?></div>
    </form>

@stop

@section('script')
    <script>
        function goDetail(id)
        {
            location.href = '/admin/index/order-detail?order_id=' + id;
        }

        $('select[name="cash_type"],select[name="vip_level"]').change(function(){
            location.href = '/admin/index/direct-indirect-record?user_id={{$user->id}}&' +  $('#data_form').serialize() ;
        });
    </script>
@stop