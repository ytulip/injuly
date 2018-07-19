
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登录</title>
</head>
<style>
    body{
        font-family:Arial,"Lucida Grande","Microsoft Yahei","Hiragino Sans GB","Hiragino Sans GB W3",SimSun,"PingFang SC",STHeiti;
    }

    .abso-parent-bottom{
        position: absolute;
        bottom: 0;
        left:0;
        right: 0;
    }

    .in-bl-line{font-size: 0}
    .in-bl-line-item{font-size: 14px;display: inline-block;}

    header{
        background-color: #ffffff;
        line-height: 60px;
        height: 60px;
        position: fixed;
        top:0;
        right: 0;
        left: 0;
        z-index: 99;
    }
    .header-wrap{
        width: 1000px;
        margin: 0 auto;
        position: relative;
    }

    .subheader{
        position: absolute;
        left:0;
        right: 0;
        top:60px;
        background-color: rgb(245,245,245);
        height: 30px;
    }

    .subheader-wrap{
        width: 1000px;
        margin: 0 auto;
    }

    .subheader-wrap .right-info {
        line-height: 30px;
        float: right;
    }

    .header_img_opr ul{
        display: none;
        list-style: none;
        padding: 0;
        margin: 0;
        width: 110px;
        text-align: center;
        background-color: #ffffff;
    }

    .header_img_opr:hover ul{
        display: block;
        list-style: none;
    }

    .header_img_opr ul li{
        display: block;
        border-bottom:1px solid #e9e9ea;
    }

    .header_img_opr:hover ul li{
        display: block;
        list-style: none;
    }

    .header_img_opr ul a{
        display: block;
        width: 100%;
    }


    .right-info a{
        color:rgb(153,153,153);
        cursor: pointer;
    }


    .common-mask{
        position: fixed;
        top:0;
        left:0;
        right: 0;
        bottom: 0;
        background-color: #222222;
        opacity: .5;
        z-index: 99;
        /*display: none;*/
    }

    .login-panel{
        position: fixed;
        top:50%;
        left: 50%;
        transform: translate(-50%,-50%);
        -webkit-transform: translate(-50%,-50%);
        width: 520px;
        min-height:100px;
        border-radius: 5px;
        font-size: 14px;
        background: #fff;
        box-shadow:rgb(51, 51, 51);
        z-index: 999;
        padding: 30px 110px;
        line-height: normal;
        /*display: none;*/
    }

    .cute_input{
        font-size: 14px;
        border-radius: 5px;
        padding: 5px;
        vertical-align: middle;
        line-height: 18px;
        border: solid 1px #DDD;
        outline-color: #ff4611;;
    }


    .v-h-middle{

    }


    .text-al{
        text-align: left !important;
        font-size: 14px;
        color:#333;
    }

    .text-ac{
        text-align: center;
    }






    .map-wrapper{
        margin-top: 60px;
        height: 440px;
    }

    .content-container{
        width: 1000px;
        margin: 0 auto;
    }
    .content-container{
        text-align: center;
    }

    .content-container .content-panel{
        display: inline-block;
    }

    .content-container .articles{
        width: 700px;
        text-align: left;
    }


    .articles h1{
        line-height: 35px;
        overflow: hidden;
        font-size: 24px;
        color: #a6551f;
        font-weight: normal;
    }

    .articles p{
        font-size: 14px;
        line-height: 24px;
        color:#4c4d4d;
    }

    .video-wrap{
        width: 700px;
        overflow: hidden;
    }

    .video-wrap video{
        display: block;
        width: 100%;
    }
    /*.content-container .sticks{*/
    /*width: 220px;*/
    /*}*/

    @media only screen and (max-width: 1200px) {

    }

    @media only screen and (max-width: 540px) {

    }


    @media only screen and (max-width: 540px) {

    }

    @media only screen and (max-width: 540px) {

    }

    a{text-decoration: none !important;}

    /*l-r-divide*/
    .center-menu {
        list-style: none;
        display: block;

    }

    .center-menu .first-level {line-height: 50px;font-size: 16px;color:#333;}
    .center-menu .second-level {line-height: 40px;font-size: 14px;color:#333;padding:0 20px;}
    .center-menu a {color:#333!important;}
    .center-menu .active{background: #fafafa}
    .center-menu .active a{color: #fc5832!important;}

    .center-menu li {display: block;}
    .center-menu  ul {list-style: none;margin: 0;padding: 0}




    .r-l-divide-l{
        float: left;
        width: 200px;
        padding-top: 40px;
        text-align: left;
    }

    .r-l-divide-r{
        float: right;
        width: 800px;
        padding-top: 40px;
        text-align: left;
    }

    .formTit {
        padding: 7px 20px;
        margin: 20px 0 15px;
        font-size: 20px;
        color: #666;
        border-bottom: 1px solid #e8e8e8;
    }

    /*aside*/
    .aside-body li{height: 40px;padding: 8px 3px;}
    .aside-body{margin: 0;padding: 0;}
    .aside-body .num {
        float: left;
        width: 24px;
        height: 24px;
        text-align: center;
        font-size: 16px;
        margin-right: 10px;
        background: #ec4d00;
        color: #fff;
    }

    .aside-body a{line-height: 24px;display: block;float:left;text-align: left;}
    .app-qcode{position: relative;}
    .app-code-show{display: none;}
    .app-qcode:hover .app-code-show{
        display: block;
    }

    .fix-center-content{
        position: fixed;
        top:50%;
        left:50%;
        -webkit-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
    }

    /*vue*/
    .vue-hide{display: none!important;}
    /*end of vue*/

    /*footer*/
    footer {color:#999;}
    footer a{color:#999 !important;text-decoration: none;}
    footer a:visited{color:#999}
    /* end of footer*/
</style>
<link href="/admin/css/bootstrap.min.css" rel="stylesheet"/>
<style>
    html,body{width: 100%;background-color: #f1f1f1; }
    .container{
        width: 100%;
        min-height: 100%;
        text-align: center;
        font-size: 14px;
    }


    .main {
        width: 400px;
        margin: 60px auto 0;
        padding: 50px 50px 30px;
        background-color: #fff;
        border-radius: 4px;
        box-shadow: 0 0 8px rgba(0,0,0,.1);
        vertical-align: middle;
        display: inline-block;
    }

    .active {
        font-weight: 700;
        color: #ea6f5a;
        border-bottom: 2px solid #ea6f5a;
        font-size: 18px;
    }

    .get-code-btn{margin-left:6px;}
</style>
<body>
<div class="container">
    <form id="data_form">
        <div class="main">
            <a id="js-sign-up-btn" class="active" href="/sign_up">登录</a>
            <div style="margin-top: 25px;">
                <div style="margin-top: 6px;"><input placeholder="输入邮箱" class="form-control" name="email"/></div>
                <div style="margin-top: 6px;"><input type="password" placeholder="请输入密码" class="form-control" name="password"/></div>
            </div>
            <div style="margin-top: 20px;"><button type="button" class="btn btn-success" id="next_step">立即登录</button></div>
        </div>
    </form>
</div>
</body>
<script src="/admin/js/jquery-2.1.4.min.js"></script>
<script src="/admin/js/jquery.nicescroll.js"></script>
<script src="/admin/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="/admin/js/bootstrap.min.js"></script>
<!-- /Bootstrap Core JavaScript -->
<!-- morris JavaScript -->
<script src="/admin/js/jquery.base64.js"></script>
<script src="/admin/js/raphael-min.js"></script>
<script src="/admin/js/morris.js"></script>
<script src="/admin/js/echarts.simple.min.js?v={{env('VERSION')}}"></script>
<script src="/admin/js/layer/layer/layer.js"></script>
<script src="/admin/js/common.js"></script>
<script>

    $(function () {
        new SubmitButton({
            selectorStr:"#next_step",
            url:'/passport/admin-login',
            prepositionJudge:function(){
                if(!$('input[name="email"]').val()) {
                    mAlert('请输入管理员账号');
                    return;
                }

                if(!/^[~!@#$%^&*A-Za-z0-9]{6,20}$/.test($('input[name="password"]').val())){
                    mAlert('密码格式不符合要求');
                    return;
                };

                return true;
            },
            callback:function(obj,data){
                if(data.status){
                    location.href = '/admin/index/total';
                } else {
                    mAlert(data.desc);
                }
            },
            data:function()
            {
                return $('#data_form').serialize();
            }
        });
    });
</script>
</html>