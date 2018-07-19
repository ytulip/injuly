@extends('admin.master',['headerTitle'=>'内容管理 <span class="title-gap">></span> 编辑文章'])
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
                        <p>标题</p>
                        {{--<div><div class="" contenteditable="true"></div><a class="fl-r"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>--}}
                        {{--<div class="form-horizontal"><div class="form-group"><input class="form-control"/><a class="fl-r"><i class="fa fa-pencil" aria-hidden="true"></i></a></div></div>--}}
                        <div class="row">
                            <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="title" value="{{isset($essay->title)?$essay->title:''}}"/></div>
                            <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="block-card mt-32">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <p>副标题</p>
                        {{--<div><div class="" contenteditable="true"></div><a class="fl-r"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>--}}
                        {{--<div class="form-horizontal"><div class="form-group"><input class="form-control"/><a class="fl-r"><i class="fa fa-pencil" aria-hidden="true"></i></a></div></div>--}}
                        <div class="row">
                            <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="sub_title" value="{{isset($essay->sub_title)?$essay->sub_title:''}}"/></div>
                            <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-32">
                <div class="col-md-12 col-lg-12">
                    <div class="block-card">
                        <p>内容</p>
                        <!-- 加载编辑器的容器 -->
                        <script id="container" name="content" type="text/plain">
这里写你的初始化内容
</script>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="block-card">
                <p>封面图片</p>
                <a href="javascript:uploadCover()"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <div class="essay_img">
                    <img src="{{isset($essay->cover_image)?$essay->cover_image:'/imgsys/1.jpg'}}"/>
                </div>
            </div>

            <button type="button" class="btn btn-success col-gray-btn mt-32">删除</button>
            <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="essayPreview()">预览</button>
            <button type="button" class="btn btn-success col-gray-btn mt-32" id="do_publish">发布</button>
        </div>
    </div>

    <form style="display: none;" id="data-form">
        <input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>

    </form>
    {{--<div class="preview-simulator">--}}
    {{--<div class="common-mask"></div>--}}
    {{--<div class="phone-simulator">--}}
        {{--<img src="/images/iphone-bg.png"/>--}}
        {{--<iframe class="phone-simulator-iframe" src="/iframe.html"></iframe>--}}
    {{--</div>--}}
    {{--</div>--}}

    <img src="/images/iphone-bg.png" style="width: 0;height: 0;visibility: hidden;"/>
@stop

@section('script')
<!-- 配置文件 -->
<script type="text/javascript" src="/admin/js/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/admin/js/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var pageConfig = {
        essay_id:'{{isset($essay->id)?$essay->id:''}}',
        content:'{!! isset($essay->context)?$essay->context:''!!}'
    }

    var ue = UE.getEditor('container');
    ue.ready(function() {
        //设置编辑器的内容
        ue.setContent(pageConfig.content);
    });

    function essayPreview()
    {
        var content = ue.getContent();
        if( content.length == 0 ) {
            mAlert('内容不能为空');
            return;
        }
        preview(content);
    }

//    function publish()
//    {
//        var content = ue.getContent();
//        if( content.length == 0 ) {
//            alert('内容不能为空');
//            return;
//        }
//    }

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
            return {title:$('input[name="title"]').val(),content:ue.getContent(),cover_image:$('.essay_img').find('img').attr('src'),sub_title:$('input[name="sub_title"]').val()};
        },
        redirectTo:'/admin/index/essays'
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
                         $('.essay_img').find('img').attr('src',data.data[0]); 'http://static.liaoliaoy.com/' + data.data[0];
                    } else {
                        alert(data.desc);
                    }
                }
            });
        }
    });
</script>
    @stop