@extends('admin.master',['headerTitle'=>'资产管理 > 提货管理 > 提货详情'])
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
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">提货时间</p>
                    <p class="text-desc-decoration">{{date('Y-m-d H:i',strtotime($order->created_at))}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">提货数量</p>
                    <p class="text-desc-decoration">{{$order->count}}箱</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">提货方式</p>
                    <p class="text-desc-decoration">{{($order->getTypeText())}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">收货方式</p>
                    <p class="text-desc-decoration">{{\App\Model\Deliver::deliverTypeText($order->deliver_type)}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">收货地址</p>
                    <p class="text-desc-decoration">{{$order->address}}</p>
                </div>
            </div>
        </div>
        <h4>物流信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">物流状态</p>
                    <p class="text-desc-decoration">{{\App\Model\Order::orderStatusText($order->get_status)}}</p>
                </div>
            </div>


            @if($order->get_status == \App\Model\Order::ORDER_STATUS_WAIT_DELIVER)
                <div class="deliver_panel">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 sm-tag-text" >快递公司</div>
                        <div class="col-md-2 col-lg-2 sm-tag-text">快递单号</div>
                        <div class="col-md-3 col-lg-3"><button type="button" class="btn btn-success" id="addDeliverInfo">添加</button>
                            <button type="button" class="btn btn-success" id="send">发货</button>
                        </div>
                    </div>
                </div>
                @endif

            @if($order->get_status == \App\Model\Order::ORDER_STATUS_DELIVERED)
                <div class="deliver_panel">
                    <div class="row m-b-10">
                        <div class="col-md-4 col-lg-4 sm-tag-text" >快递公司</div>
                        <div class="col-md-2 col-lg-2 sm-tag-text">快递单号</div>
                    </div>

                    @if( $order->deliver_array)
                        @foreach(json_decode($order->deliver_array) as $key=>$item)
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <p class="text-desc-decoration">{{$item->deliver_company_name}}</p>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <p class="text-desc-decoration">{{$item->deliver_number}}</p>
                                </div>
                            </div>
                            @endforeach
                    @endif
                </div>
                @endif
        </div>

        <h4>辅导信息</h4>
    </div>
@stop
@section('script')
    <script>
        var pageConfig = {
            order_id:{{$order->id}}
        }

        // $('.remove-line-btn').click(function(){
        //     $(this).parents('.deliver-row').remove();
        // });

        $('body').on('click','.remove-line-btn',function(){
            $(this).parents('.deliver-row').remove();
        })


        $('#addDeliverInfo').click(function () {
            $('.deliver_panel').append('<div class="row deliver-row"> <div class="col-md-4 col-lg-4"><div class="row"><div class="col-md-9 col-lg-9"><input class="form-control no-border-input bt-line-1" name="deliver_company_name"></div><div class="col-md-3 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div> </div></div><div class="col-md-4 col-lg-4"><div class="row"><div class="col-md-9 col-lg-9"><input class="form-control no-border-input bt-line-1" name="deliver_number"></div><div class="col-md-3 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div> </div></div><div class="col-md-4 col-lg-4"><a class="remove-line-btn"><i class="fa fa-remove" aria-hidden="true"></i></a></div></div>');
        });

        $(function(){
            new SubmitButton({
                selectorStr:"#send",
                url:'/admin/index/set-get-status',
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
                    var deliverArray = [];
                    $('.deliver-row').each(function(ind,obj){
                        deliverArray.push({deliver_company_name:$(obj).find('input[name="deliver_company_name"]').val(),deliver_number:$(obj).find('input[name="deliver_number"]').val()});
                    });
                    return {deliver_array:JSON.stringify(deliverArray),order_id:pageConfig.order_id,status:2}
//                    var deliverCompanyName = $('input[name="deliver_company_name"]').val();
//                    var deliverNumber = $('input[name="deliver_number"]').val();
//                    return {deliver_company_name:deliverCompanyName,deliver_number:deliverNumber,order_id:pageConfig.order_id,status:2};
                }
            });
        });
    </script>
@stop