@extends('admin.master',['headerTitle'=>'资产管理 > 购买管理 > 购买详情'])
@section('style')
    <style>
        .user-header{
            width: 48px;
            height: 48px;
            border-radius: 48px;
            overflow: hidden;
            position: absolute;
            bottom: 0;
            left:15px;
        }

        .user-header img{
            width: 48px;
            height: 48px;
            border-radius: 48px;
        }

    </style>
@stop
@section('left_content')
    <div class="mt-32 padding-col">
        <h4>基本信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">订单编号</p>
                    <p class="text-desc-decoration">{{$order->id}}</p>
                </div>

                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">购买类型</p>
                    <p class="text-desc-decoration">{{$order->buyTypeText()}}</p>
                </div>

                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">直接开发者/邀请人</p>
                    <p class="text-desc-decoration">@if(\App\Model\User::find($order->immediate_user_id)) {{\App\Model\User::find($order->immediate_user_id)->real_name}}({{\App\Model\User::find($order->immediate_user_id)->phone}}) @else &nbsp; @endif</p>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">购买时间</p>
                    <p class="text-desc-decoration">{{date('Y-m-d H:i',strtotime($order->created_at))}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">购买数量</p>
                    <p class="text-desc-decoration">{{$order->quantity}}箱</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">购买价格</p>
                    <p class="text-desc-decoration">￥{{$order->need_pay}}</p>
                </div>
            </div>
        </div>
        @if(false)
        <h4>开发信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">直接开发者</p>
                    <p class="text-desc-decoration">{{$orderCash['directUser']->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">联系方式</p>
                    <p class="text-desc-decoration">{{$orderCash['directUser']->phone}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">直接开发获利</p>
                    <p class="text-desc-decoration">￥{{$orderCash['directPrice']}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">间接开发者</p>
                    <p class="text-desc-decoration">{{$orderCash['indirectUser']->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">联系方式</p>
                    <p class="text-desc-decoration">{{$orderCash['indirectUser']->phone}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">间接开发获利</p>
                    <p class="text-desc-decoration">￥{{$orderCash['indirectPrice']}}</p>
                </div>
            </div>
        </div>
        <h4>辅导信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">一代辅导者</p>
                    <p class="text-desc-decoration">{{$orderCash['upUser']->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">联系方式</p>
                    <p class="text-desc-decoration">{{$orderCash['upUser']->phone}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">一代辅导获利</p>
                    <p class="text-desc-decoration">￥{{$orderCash['upPrice']}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">二代辅导者</p>
                    <p class="text-desc-decoration">{{$orderCash['superUser']->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">联系方式</p>
                    <p class="text-desc-decoration">{{$orderCash['superUser']->phone}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">二代辅导获利</p>
                    <p class="text-desc-decoration">￥{{$orderCash['superPrice']}}</p>
                </div>
            </div>
        </div>
        @if( in_array($order->buy_type,[\App\Model\Order::BUY_TYPE_REPORT]) && $invitedCode )
        <h4>新会员信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">会员邀请码</p>
                    <p class="text-desc-decoration">{{$invitedCode->invited_code}}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">新会员注册时间</p>
                    <p class="text-desc-decoration">{{$invitedCode->userInfo('created_at')}}</p>
                </div>

                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">新会员姓名</p>
                    <p class="text-desc-decoration">{{$invitedCode->userInfo('real_name')}}</p>
                </div>

                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">新会员手机号码</p>
                    <p class="text-desc-decoration">{{$invitedCode->userInfo('phone')}}</p>
                </div>
            </div>
        </div>
        @endif
            <h4>资金信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">利润</p>
                    <p class="text-desc-decoration">￥{{$orderCash['benefit']}}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">购买收入</p>
                    <p class="text-desc-decoration">￥{{$orderCash['price']}}</p>
                </div>

                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">开发支出</p>
                    <p class="text-desc-decoration">￥{{$orderCash['directIndirectPrice']}}</p>
                </div>

                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">辅导支出</p>
                    <p class="text-desc-decoration">￥{{$orderCash['upSuperPrice']}}</p>
                </div>
            </div>
        </div>
            @endif
    </div>
@stop
@section('script')
<script>
    var pageConfig = {
        order_id:{{$order->id}}
    }

    $(function(){
        new SubmitButton({
            selectorStr:"#send",
            url:'/admin/index/set-order-status',
            prepositionJudge:function() {
                var deliverCompanyName = $('input[name="deliver_company_name"]').val();
                var deliverNumber = $('input[name="deliver_number"]').val();
                if (!deliverCompanyName || !deliverNumber) {
                    mAlert('物流信息有误');
                    return false;
                }
                return true;
            },
            data:function(){
                var deliverCompanyName = $('input[name="deliver_company_name"]').val();
                var deliverNumber = $('input[name="deliver_number"]').val();
                return {deliver_company_name:deliverCompanyName,deliver_number:deliverNumber,order_id:pageConfig.order_id,status:2};
            }
        });
    });
</script>
    @stop