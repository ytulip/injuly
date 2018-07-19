<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>头像</title>
    <link rel="stylesheet" href="/js/plugin/avatarUpload-master/css/default.css">
    <link rel="stylesheet" href="/css/mui.css?v={{env('VERSION')}}"/>
    <link rel="stylesheet" href="/css/style.css?v={{env('VERSION')}}"/>
    <style>
        html,body{background-color: rgb(239,243,246);}
    </style>
</head>
<body>

<div class="cus-row p-l-r-14">
    <div class="cus-row-col-4"><a href="/user/center"><i class="back-icon"></i></a></div>
    <div class="cus-row-col-4 t-al-c"><span class="fs-17-fc-212229" style="line-height: 68px;">头像</span></div>
    <div class="cus-row-col-4 t-al-r"><a href="javascript:void(0)" id="select"><span class="fs-16-fc-212229">上传</span></a></div>
</div>

<div id="conWrap">
    <!--拖动选择层-->
    <div id="pictureUpload">
        <div id="pictureUpload-bg"></div>
        <div id="pictureUpload-mask"></div>
    </div>

    <!--操作按钮-->
    {{--<div id="button">--}}
        {{--<div id="select" class="active">选择</div>--}}
        {{--<div id="preview">预览</div>--}}
        {{--<div id="submit">上传</div>--}}
        {{--<div id="createLocalImg">生成</div>--}}
    {{--</div>--}}

    <a class="btn-block1" href="javascript:void(0);" id="submit">提交申请</a>

    <!--文件域-->
    <input type="file" id="fileElem" multiple accept="image/*" style="display:none">

    <!-- 操作提示 -->
    {{--<div id="pictureUpload-help">--}}
        {{--<p>提示:</p>--}}
        {{--<p>单指拖动</p>--}}
        {{--<p>双指缩放</p>--}}
    {{--</div>--}}

    <!--用于生成和预览-->
    <div id="canvasWrap" style="display: none;">
        <canvas id="canvas"></canvas>
    </div>
</div>

<script src="/js/plugin/avatarUpload-master/js/jquery3.1.1-min.js"></script>
<script src="/js/plugin/avatarUpload-master/js/hammer.min.js"></script>
<script src="/js/plugin/avatarUpload-master/js/avatarUpload.js"></script>
<script src="/js/plugin/layer_mobile/layer.js?v={{env('VERSION')}}"></script>
<script type="text/javascript">

    $(function(){

        //定义发送二进制的函数
        function sendFile(fileblob) {
            layer.open({type: 2});
            var fd = new FormData();
            fd.append("images[]", fileblob,"123.jpg");


            //上传图片
            $.ajax({
                url:'/user/header-img',
                data:fd,
                type:'post',
                contentType: false,
                processData: false,
                dataType:'json',
                success:function(data){
                    layer.closeAll();
                    if(data.status) {
                        alert('修改成功');
                        location.href = '/user/info';
                    } else {
                        alert(data.desc);
                    }
                },
                error:function(){
                    layer.closeAll();
                    alert('网络异常');
                }
            });
        }

        var options = {
            containerId: "#pictureUpload",
            uploadBgId: "#pictureUpload-bg",
            fileId: "#fileElem",
            canvasId: "#canvas",
            container: {
                width: $("#pictureUpload").width(),
                height: $("#pictureUpload").height()
            },
            clip:{
                width: $("#pictureUpload-mask").width(),
                height: $("#pictureUpload-mask").height()
            },
            imgQuality:1
        }



        var txUpload = avatarUpload(options);
        $("#select").click(txUpload.selectImg)
        $("#preview").click(txUpload.createImg)
        $("#submit").click(function(){
            txUpload.submit(sendFile);
        })
        $("#createLocalImg").click(function(){
            txUpload.createLocalImg("localImg","canvasWrap","localImg");
        });
        //文件 onchange事件
        $("#fileElem").on("change",  function(){
            txUpload.handleFiles(function(){
                $("#preview, #submit, #createLocalImg").addClass('active');
            })
        });


    })

</script>
</body>
</html>