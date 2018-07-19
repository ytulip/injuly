@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 概况 <span class="title-gap">></span> 辅导记录'])
@section('style')
    <style>
        .cus-select{border: none;padding: 0;}
    </style>
@stop
@section('left_content')

    <div class="row mt-32">
        <div class="col-md-3 col-lg-3">
            <div class="block-card">
                <p>一代辅导次数</p>
                <p class="price-big">{{$upCount}}</p>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="block-card">
                <p>一代辅导分红</p>
                <p class="price-big">￥{{\App\Util\Kit::priceFormat($up)}}</p>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="block-card">
                <p>二代辅导次数</p>
                <p class="price-big">{{$superCount}}</p>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="block-card">
                <p>二代辅导分红</p>
                <p class="price-big">￥{{\App\Util\Kit::priceFormat($super)}}</p>
            </div>
        </div>
    </div>

    <div class="row m-t-10">
        <div class="col-md-6 col-lg-6"><h4>开发记录</h4></div>
        <div class="col-md-4 col-lg-4 fl-r">

            <form class="form-inline" id="search_form">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" id="exampleInputAmount" name="keyword" placeholder="输入姓名搜索" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}">
                        <div class="input-group-addon" onclick="search()"><i class="fa fa-search"></i></div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="block-card">
        <div class="row paginate-list-row">
            <div class="col-md-2 col-lg-2">辅导时间</div>
            <div class="col-md-2 col-lg-2">辅导模式</div>
            <div class="col-md-2 col-lg-2">辅导者</div>
            <div class="col-md-2 col-lg-2">新会员姓名</div>
            <div class="col-md-2 col-lg-2">辅导分红</div>
        </div>
        @foreach($paginate as $item)
            <div class="row paginate-list-row" onclick="goDetail({{$item->id}})">
                <div class="col-md-2 col-lg-2">{{\App\Util\Kit::dateFormat($item->created_at)}}</div>
                <div class="col-md-2 col-lg-2">{{\App\Model\CashStream::cashTypeText($item->cash_type)}}</div>
                <div class="col-md-2 col-lg-2">{{$item->real_name}}</div>
                <div class="col-md-2 col-lg-2">{{$item->refer_real_name}}</div>
                <div class="col-md-2 col-lg-2">{{$item->price}}</div>
            </div>
        @endforeach
    </div>

    <div class="fl-r"><?php echo $paginate->render(); ?></div>

@stop

@section('script')
    <script>
        function goDetail(id)
        {
            location.href = '/admin/index/order-detail?order_id=' + id;
        }

        function search()
        {
            $('#search_form').submit();
        }
    </script>
@stop