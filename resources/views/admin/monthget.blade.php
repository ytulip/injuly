@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 提货记录'])
@section('left_content')
    <div class="mt-32 padding-col">



        <div class="row">
            <div class="col-md-3 col-lg-3" onclick="goHref('/admin/index/users?get_status=1')">
                <div class="block-card">
                    <p>报单</p>
                    <div>{{\App\Model\User::sum('get_good')}}箱未提</div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3" onclick="goHref('/admin/index/users?get_status=2')">
                <div class="block-card">
                    <p>复购</p>
                    <div>{{\App\Model\User::sum('re_get_good')}}箱未提</div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3" onclick="goHref('/admin/index/users?get_status=3')">
                <div class="block-card">
                    <p>活动</p>
                    <div>{{\App\Model\User::sum('activity_get_good')}}箱未提</div>
                </div>
            </div>
        </div>

        <h4>提货申请</h4>

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
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">发货状态</label>
                        <div class="input-group">
                            <select type="text" class="form-control" id="exampleInputAmount" name="get_status">
                                <option></option>
                                <option value="1" {{\Illuminate\Support\Facades\Request::input('get_status') == "1"?' selected':''}}>待发货</option>
                                <option value="2" {{\Illuminate\Support\Facades\Request::input('get_status') == "2"?' selected':''}}>已发货</option>
                                <option value="3" {{\Illuminate\Support\Facades\Request::input('get_status') == "3"?' selected':''}}>已自提</option>
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
                {{--<a class="btn btn-info" href="javascript:commonDownload()" style="margin-top: 18px;">下载</a>--}}
            {{--</div>--}}
        </div>
        <div class="block-card m-t-10">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">编号</div>
                <div class="col-md-2 col-lg-2">提货人姓名</div>
                <div class="col-md-2 col-lg-2">联系方式</div>
                <div class="col-md-1 col-lg-1">提货数量</div>
                <div class="col-md-1 col-lg-1">提货类型</div>
                <div class="col-md-1 col-lg-1">提货方式</div>
                <div class="col-md-1 col-lg-1">发货状态</div>
                <div class="col-md-2 col-lg-2">提货时间</div>
            </div>
            @foreach($paginate as $item)
                <div class="row paginate-list-row" onclick="goDetail({{$item->get_id}})"
                >
                    <div class="col-md-2 col-lg-2">{{$item->get_id}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->real_name}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->phone}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->count}}</div>
                    <div class="col-md-1 col-lg-1">{{($item->getTypeText())}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->deliverTypeText()}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->statusText()}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->get_created_at}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>

    </div>
@stop

@section('script')
<script>
    function goDetail(id)
    {
        location.href = '/admin/index/get-good-detail?id=' + id;
    }

    function search()
    {
//    alert(1);
//    $val = $('#search_user').val();
        $('#search_form').submit();

    }
</script>
    @stop