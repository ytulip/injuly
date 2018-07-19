@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 活动退款 <span class="title-gap">></span> 退款详情'])
@section('style')

@stop
@section('left_content')
    <div class="mt-32 padding-col">
        <h4>退款信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">申请人姓名</p>
                    <p class="text-desc-decoration">{{$user->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">联系方式</p>
                    <p class="text-desc-decoration">{{$user->phone}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">申请时间</p>
                    <p class="text-desc-decoration">{{$withdraw->created_at}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">申请金额</p>
                    <p class="text-desc-decoration">￥{{$withdraw->price}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">账户余额</p>
                    <p class="text-desc-decoration">￥{{$user->charge + $user->charge_frozen}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">退款方式</p>
                    <p class="text-desc-decoration">{{\App\Model\CashStream::withdrawTypeText($withdraw->withdraw_type)}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">退款账号</p>
                    <p class="text-desc-decoration">{{$withdraw->withdraw_account}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">退款银行</p>
                    <p class="text-desc-decoration">{{$withdraw->withdraw_bank}}</p>
                </div>
            </div>
        </div>
        <h4>退款处理</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">处理方式</p>
                    @if(!$withdraw->withdraw_deal_status)
                        <select style="padding: 0;" name="agree">
                            <option></option>
                            <option value="1">同意</option>
                            <option value="2">拒绝</option>
                        </select>
                        {{--<div class="dropdown">--}}
                        {{--<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">--}}
                        {{--&nbsp;&nbsp;&nbsp;&nbsp;--}}
                        {{--<span class="caret"></span>--}}
                        {{--</button>--}}
                        {{--<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">--}}
                        {{--<li><a href="#">同意</a></li>--}}
                        {{--<li><a href="#">拒绝</a></li>--}}
                        {{--</ul>--}}
                        {{--</div>--}}
                    @else
                        <p class="text-desc-decoration">{{\App\Model\CashStream::withdrawStatusText($withdraw->withdraw_deal_status)}}</p>
                    @endif
                </div>



                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">原因备注</p>
                    @if(!$withdraw->withdraw_deal_status)
                        <div class="row"><div class="col-md-9 col-lg-9"><input class="form-control no-border-input bt-line-1" name="remark"></div><div class="col-md-3 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div> </div>
                    @else
                        <p class="text-desc-decoration">{{$withdraw->remark}}</p>
                    @endif
                </div>


                @if($withdraw->withdraw_deal_status == 1)
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">同意退款时间</p>
                     <p class="text-desc-decoration">{{$withdraw->updated_at}}</p>
                </div>
                @endif


            </div>
        </div>

        <div>
            <div class="row" style="margin-top: 60px;">
                <div class="col-md-4 col-lg-4">
                </div>
                <div class="col-md-4 col-lg-4">
                </div>
                <div class="col-md-4 col-lg-4">
                    @if(!$withdraw->withdraw_deal_status) <button type="button" class="btn btn-success col-gray-btn" id="next_step">保存</button> @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        $(function(){
            new SubmitButton({
                selectorStr:"#next_step",
                prepositionJudge:function(){
                    if( !$('select[name="agree"]').val() )
                    {
                        mAlert('请选择处理方式');
                        return;
                    }
                    return true;
                },
                url:'/admin/index/deal-withdraw',
                data:function(){
                    return {id:{{\Illuminate\Support\Facades\Request::input('withdraw_id')}},agree:$('select[name="agree"]').val(),remark:$('input[name="remark"]').val()}
                },
                redirectTo:'{{$_SERVER['REQUEST_URI']}}'
            });
        });
    </script>
@stop