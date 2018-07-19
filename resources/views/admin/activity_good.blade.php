@extends('admin.master',['headerTitle'=>'商品管理'])
@section('style')
    <style>
        #edui1{width:100%!important;}
        .essay_img{position: absolute;top:50%;right: 40px;width: 60px;height: 60px;border-radius: 8px;transform: translateY(-50%);-webkit-transform: translateY(-50%);overflow: hidden;}
        .essay_img img{width: 60px;height: 60px;border-radius: 8px;}
    </style>
@stop
@section('left_content')
    <div class="row mt-32">
        <div class="col-md-9 col-lg-9">
            <div class="block-card">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <p>商品名称</p>
                        {{--<div><div class="" contenteditable="true"></div><a class="fl-r"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>--}}
                        {{--<div class="form-horizontal"><div class="form-group"><input class="form-control"/><a class="fl-r"><i class="fa fa-pencil" aria-hidden="true"></i></a></div></div>--}}
                        <div class="row">
                            <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="title" value="{{isset($product->product_name)?$product->product_name:''}}"/></div>
                            <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-32">
                <div class="col-md-12 col-lg-12">
                    <div class="block-card">
                        <p>商品介绍</p>
                        <!-- 加载编辑器的容器 -->
                        <script id="container" name="content" type="text/plain">
                        </script>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3 col-lg-3">

            <div class="block-card">
                <p>群二维码</p>
                <a href="javascript:uploadCover()"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <div class="essay_img">
                    <img src="{{$product->cover_image}}"/>
                </div>
            </div>

            <div class="block-card mt-32">
                <p>售价</p>
                {{--<div class="row">--}}
                {{--<div class="col-md-11 col-lg-9 font-color2-12">邀请VIP会员</div>--}}
                {{--<div class="col-md-1 col-lg-3"><a href="/admin/index/good-attr?id=1" class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>--}}
                {{--</div>--}}
                <div class="row">
                    <div class="col-md-11 col-lg-9 font-color2-12"><input class="form-control no-border-input font-color2-12" name="price" value="{{$product->price}}"/></div>
                    <div class="col-md-1 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>


            <div class="block-card mt-32">
                <p>活动天数</p>
                {{--<div class="row">--}}
                {{--<div class="col-md-11 col-lg-9 font-color2-12">邀请VIP会员</div>--}}
                {{--<div class="col-md-1 col-lg-3"><a href="/admin/index/good-attr?id=1" class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>--}}
                {{--</div>--}}
                <div class="row">
                    <div class="col-md-11 col-lg-9 font-color2-12"><input class="form-control no-border-input font-color2-12" name="activity_days" value="{{$product->activity_days}}"/></div>
                    <div class="col-md-1 col-lg-3"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>

            <div class="block-card mt-32">
                <p>取货地址 <a onclick="addModAddress(this,0)" href="javascript:void(0)" class="fl-r"><i class="fa fa-plus"></i></a></p>
                @foreach($addresses as $item)
                    <div class="row">
                        <div class="col-md-11 col-lg-9 font-color2-12" style="word-break:keep-all;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{$item->ITEMNAME}}
                        </div>
                        <div class="col-md-1 col-lg-3"><a data-address="{{$item->address}}" data-real_name="{{$item->address_name}}" data-phone="{{$item->mobile}}" data-city_code="{{$item->pct_code}}" class="fl-r editor-pen-btn" href="javascript:void(0)" onclick="addModAddress(this,{{$item->ITEMNO}})"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="essayPreview()">预览</button>
            <button type="button" class="btn btn-success col-gray-btn mt-32" id="do_publish">发布</button>
        </div>
    </div>

    <form style="display: none;" id="data-form">
        <input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>

    </form>

    <div style="position: fixed;top:0;left:0;right: 0;bottom: 0;z-index: 9999;"  class="dpn" id="address_panel">
        <div style="position: fixed;top:0;left:0;right: 0;bottom: 0;background-color: rgba(22,4,4,.7)" onclick="closeAddressMask()"></div>
        <form id="address_form">
            <div style="padding: 40px;background-color: #ffffff;border-radius: 8px;" class="position-50-percent">
                <input  name="address_id"  style="display: none;">
                <input  name="good_id"  type="hidden" value="2">
                <p class="m-t-10">联系人姓名:</p>
                <div class="m-t-10"><input class="form-control" name="real_name" /></div>
                <p class="m-t-10">联系人电话:</p>
                <div class="m-t-10"><input class="form-control" name="phone" /></div>
                <input value="" name="city_code" type="hidden"/>
                <p class="m-t-10">省市区:</p>
                <div class="form-inline"><select id="province" class="form-control"></select>
                    <select id="city" class="form-control"></select>
                    <select id="town" class="form-control"></select></div>
                <p style="margin-top: 20px;">详细地址:</p>
                <textarea class="form-control" style="height: 120px;" name="address">

            </textarea>

                <div style="margin-top: 10px;text-align: right;">
                    <button type="button" class="btn btn-primary btn-radius" id="add_mod_address">添加</button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('script')
    <!-- 配置文件 -->
    <script type="text/javascript" src="/admin/js/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/admin/js/ueditor/ueditor.all.js"></script>
    <script src="/js/town.js"></script>
    <script src="/js/vue.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var pageConfig = {
            content:'{!! isset($product->context)?$product->context:'' !!}',
            context_deliver:'{!! isset($product->context_deliver)?$product->context_deliver:'' !!}',
            context_server:'{!! isset($product->context_server)?$product->context_server:'' !!}',
        }

        var town = new Town({
            selectorStr:"input[name='city']",
            callback:function(val,text){
                console.log(val);
                $('input[name="city_code"]').val(val);
            }
        });

        function closeAddressMask()
        {
            $('#address_panel').hide();
        }

        function addModAddress(target,a)
        {

            //去设置值
            $('input[name="real_name"]').val($(target).attr('data-real_name'));
            $('input[name="phone"]').val($(target).attr('data-phone'));
            $('input[name="address_id"]').val(a);
            $('textarea[name="address"]').val($(target).attr('data-address'));

            town.setValue($(target).attr('data-city_code'));
//            console.log($(target).attr('data-city_code'));

            $('#address_panel').show();
            if(a) {
                $('#add_mod_address').html('修改');
            }else{
                $('#add_mod_address').html('添加');
            }
        }

        function essayPreview()
        {
            var content = ue.getContent();
            if( content.length == 0 ) {
                mAlert('内容不能为空');
                return;
            }
            preview(content);
        }

        var ue = UE.getEditor('container');
        ue.ready(function() {
            //设置编辑器的内容
            ue.setContent(pageConfig.content);
        });

        new SubmitButton({
            selectorStr:"#do_publish",
            prepositionJudge:function(){

                if ( !$('input[name="title"]').val() )
                {
                    mAlert('标题不能为空');
                    return;
                }

                var content = ue.getContent();
                if( content.length == 0 ) {
                    mAlert('内容不能为空');
                    return;
                }

                return true;
            },
            data:function(){
                return {title:$('input[name="title"]').val(),content:ue.getContent(),price:$('input[name="price"]').val(),cover_image:$('.essay_img').find('img').attr('src'),activity_days:$('input[name="activity_days"]').val()};
            },
            redirectTo:'/admin/index/activity-good'
        });

        function uploadCover(){
            $('input[name="images[]"]').click();
        }


        $('body').on('change','input[name="images[]"]',function(){
            if(this.value){
                var formData = new FormData($("#data-form")[0]);
                $.ajax({
                    url:'/admin/index/album-image',
                    data:formData,
                    type:'post',
                    contentType: false,
                    processData: false,
                    dataType:'json',
                    success:function(data){
                        $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>');
                        if(data.status) {
                            $('.essay_img').find('img').attr('src',data.data[0]);
                        } else {
                            alert(data.desc);
                        }
                    }
                });
            }
        });



        new SubmitButton({
            selectorStr:"#add_mod_address",
            url:"/admin/index/add-mod-address",
            callback:function(obj,data){
                if(data.status) {
                    location.reload();
                } else {
                    mAlert(data.desc);
                }
            },
            data:function()
            {
                return $("#address_form").serialize();
            }
        });
    </script>
@stop