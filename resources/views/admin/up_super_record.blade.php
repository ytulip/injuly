@extends('admin.master',['headerTitle'=>'会员管理 <span class="title-gap">></span> 会员详情 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>

    </style>
@stop
@section('left_content')
    <form id="data_form">
        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">{{$user->real_name}}的辅导记录</div>
        </div>

        <div class="block-card m-t-10">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">辅导时间</div>
                <div class="col-md-1 col-lg-1"><select class="cus-select" name="cash_type"><option value="">辅导方式</option><option value="303" @if(\Illuminate\Support\Facades\Request::input('cash_type')==303) selected @endif>一代</option><option value="304" @if(\Illuminate\Support\Facades\Request::input('cash_type')==304) selected @endif>二代</option></select></div>
                <div class="col-md-1 col-lg-1"><select class="cus-select" name="vip_level"><option value="">辅导等级</option><option value="1" @if(\Illuminate\Support\Facades\Request::input('vip_level')==1) selected @endif>VIP会员</option><option value="2" @if(\Illuminate\Support\Facades\Request::input('vip_level')==2) selected @endif>高级会员</option></select></div>
                <div class="col-md-2 col-lg-2">提货者姓名</div>
                <div class="col-md-2 col-lg-2">提货者手机号</div>
                <div class="col-md-2 col-lg-2">注册时间</div>
                <div class="col-md-2 col-lg-2">收入</div>
            </div>

            @foreach($paginate as $key=>$val)
                <div class="row paginate-list-row" onclick="goDetail({{$val->refer_id}})">
                    <div class="col-md-2 col-lg-2">{{\App\Util\Kit::dateFormat($val->created_at)}}</div>
                    <div class="col-md-1 col-lg-1">{{$val->cash_type == \App\Model\CashStream::CASH_TYPE_BENEFIT_UP?'一代':'二代'}}</div>
                    <div class="col-md-1 col-lg-1">{{\App\Model\User::levelText($val->vip_level)}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_model->real_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_model->phone}}</div>
                    <div class="col-md-2 col-lg-2">{{$val->user_model->created_at}}</div>
                    <div class="col-md-2 col-lg-2">￥{{$val->pay_status?$val->price:'0.00'}}</div>
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
            location.href = '/admin/index/up-super-record?user_id={{$user->id}}&' +  $('#data_form').serialize() ;
        });
    </script>
@stop