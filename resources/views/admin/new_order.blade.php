@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 购买管理<span class="title-gap">></span> 新增购买'])
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

        .form-control[readonly]{
            background-color: inherit;
        }

    </style>
@stop
@section('left_content')
    <form id="data_form">
        <input name="user_id" value="" class="dpn"/>
        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>购买人姓名</p>
                    <div class="row">
                    <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="real_name" value=""/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>身份等级</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11">
                            <input class="form-control no-border-input" name="vip_level" value="" readonly=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>手机号</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11">
                            <input class="form-control no-border-input" name="phone" value="" readonly=""/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>身份证号码</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11">
                            <input class="form-control no-border-input" name="id_card" value="" readonly=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>直接开发者手机号</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="immediate_phone" value=""/></div>
                        <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>购买模式</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11">{!!  \App\Model\SyncModel::productAttr('product_attr_id',false,1) !!}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                <p>一代辅导会员</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11">
                            <input class="form-control no-border-input" name="up_info" value="" readonly=""/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>二代辅导会员</p>
                    <div class="row">
                        <div class="col-md-11 col-lg-11">
                            <input class="form-control no-border-input" name="super_info" value="" readonly=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-10 col-lg-10"></div>
        <div class="col-md-2 col-lg-2"><button type="button" class="btn btn-success col-gray-btn mt-32" id="next_step">购买</button></div></div>

    </form>
@stop
@section('script')
<script>


    var userList = [];

    function chooseUser()
    {
        var userIndex = $('input[name="user_radio"]:checked').val();
//        console.log(userIndex);
        if( userIndex === undefined){
            return;
        }

        $('input[name="vip_level"]').val(userList[userIndex].vip_level_text);
        $('input[name="phone"]').val(userList[userIndex].phone);
        $('input[name="id_card"]').val(userList[userIndex].id_card);
        $('input[name="user_id"]').val(userList[userIndex].id);
        $('input[name="up_info"]').val(userList[userIndex].up_real_name + ' ' +userList[userIndex].up_phone);
        $('input[name="super_info"]').val(userList[userIndex].super_real_name + ' ' +userList[userIndex].super_phone);

        layer.closeAll();
    }

    $('input[name="real_name"]').blur(function(){
        var real_name = $(this).val();
        if(!real_name) {
            return;
        }

        //弹出遮罩层
        var nameShadow = layer.load(1, {
            shade: [0.8,'#222222'] //0.1透明度的白色背景
        });

        $('input[name="user_id"]').val('');
        $('input[name="vip_level"]').val('');
        $('input[name="phone"]').val('');
        $('input[name="id_card"]').val('');
        $('input[name="up_info"]').val('');
        $('input[name="super_info"]').val('');

        $.ajax(
            {
                url:'/admin/index/get-user-by-name',
                data:{real_name:real_name},
                dataType:'json',
                success:function(data){
                    layer.closeAll();
                    if(!data.data.length)
                    {
                        mAlert('用户不存在');
                    }

                    if(data.data.length == 1) {
                        //只有一个
                        $('input[name="vip_level"]').val(data.data[0].vip_level_text);
                        $('input[name="phone"]').val(data.data[0].phone);
                        $('input[name="id_card"]').val(data.data[0].id_card);
                    } else {
                        //有多个用户,弹框选择
                        userList = data.data;

                        var innerHtml = '<div style="padding: 20px;">';
                        $.each(data.data,function(ind,obj){
                            innerHtml += '<div><input name="user_radio" type="radio" value="'+ind+'"/><lable>'+obj.id_card+'</lable></div>';
                        });

                        innerHtml += '<div><button type="button" class="btn btn-success col-gray-btn mt-32" onclick="chooseUser()">确定</button></div></div>';

//                        /**
//                         * 选择用户
//                         */
//                        function chooseUser()
//                        {
//                             var userIndex = $('input[name="user_radio"]:checked').val();
//                             console.log(userIndex);
//                        }

                        //自定页
                        layer.open({
                            type: 1,
                            skin: 'layui-layer-demo', //样式类名
                            closeBtn: 0, //不显示关闭按钮
                            anim: 2,
                            shadeClose: true, //开启遮罩关闭
                            content: innerHtml
                        });
                    }
                },
                error:function()
                {
                    layer.closeAll();
                    mAlert('网络异常');
                }
            }
        );


    });

    new SubmitButton({
        selectorStr:'#next_step',
        url:'/admin/index/new-order',
        callback:function(el,data)
        {
            if(data.status){
                location.href="/admin/index/invited-code?order_id=" + data.data;
            } else {
                mAlert(data.desc);
            }
        },
        data:function(){
            return $('#data_form').serialize();
        }
    });
</script>
@stop