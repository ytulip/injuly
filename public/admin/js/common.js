

/**
 * 写一个按钮类，防止多次触发
 * @constructor
 */
function SubmitButton(config){
    var defaultConfig = {
        prepositionJudge:function(){
            return true;
        },
        callback:function(obj,data){
            if(data.status){
                location.href=obj.config.redirectTo;
            }else{
                // alert(data.desc);
                //提示
                layer.open({
                    content: data.desc,
                    skin: 'msg',
                    time: 2 //2秒后自动关闭
                });
            }
        },
        error:function(){
            mAlert('网络异常');
        },
        lockTips:function(){}, //被锁定后触发的提示
        url:'',
        data:function(){
            return {}
        },
        selectorStr:'',
        redirectTo:'',
        type:'post',
    }
    this.target = '';
    this.config = $.extend(defaultConfig,config);
    this.lock = false;
    this.selector = $(this.config.selectorStr);
    (function(a){
        $('body').on('click',a.config.selectorStr,function(){
            if(a.lock === true){
                a.config.lockTips();
                return;
            }else{
                a.target = this;
                a.lock = true;
                a.selector.addClass('submit-dis');
            }
            /*前置判断,如果前置判断返回true,则继续执行，否则中段*/
            if(!a.config.prepositionJudge(a.target)){
                a.lock = false;
                a.selector.removeClass('submit-dis');
                return;
            }

            switch(a.config.type){
                case 'post':
                    $.post(a.config.url, a.config.data(a.target),function(data){
                        /*回调函数*/
                        a.config.callback(a,data);
                        a.lock = false; //更改锁状态
                        a.selector.removeClass('submit-dis');
                    },'json').error(function(){
                        // a.config.callback(a,data);
                        // alert('网络异常');
                        a.config.error();
                        a.lock = false; //更改锁状态
                        a.selector.removeClass('submit-dis');
                    });
                    break;
                case 'get':
                    $.get(a.config.url, a.config.data(a.target),function(data){
                        /*回调函数*/
                        a.config.callback(a,data);
                        a.lock = false; //更改锁状态
                        a.selector.removeClass('submit-dis');
                    },'json').error(function(){
                        // a.config.callback(a,data);
                        // alert('网络异常');
                        a.config.error();
                        a.lock = false; //更改锁状态
                        a.selector.removeClass('submit-dis');
                    });
                    break;
                case 'ajax':
                    $.ajax({
                        type: "post",
                        url: a.config.url,
                        data: a.config.data(a.target),
                        dataType: "json",
                        success: function (data) {
                            a.lock = false; //更改锁状态
                            a.config.callback(a,data);
                            a.selector.removeClass('submit-dis');
                        },
                        error: function () {
                            // a.config.callback(a,data);
                            // alert('网络异常');
                            a.config.error();
                            a.lock = false; //更改锁状态
                            a.selector.removeClass('submit-dis');
                        }
                    });
                    break;
            }
        })
    })(this);
}

function mAlert(msg){
    layer.msg(msg,{time: 3000});
};

$('body').on('click','.editor-pen-btn',function(){
    var $_inputObj = $(this).parent().parent().find('input');
    var val = $_inputObj.val();
    $_inputObj.val('').focus().val(val);
});

function preview(htmlContent){
    $.base64.utf8encode = true;
    // console.log($.base64.btoa(htmlContent));
    // console.log($.base64.atob($.base64.btoa(htmlContent),true));
    // console.log(urlEncode($.base64.btoa(htmlContent)));

    url =  '/admin/index/preview?htmlContent='+ encodeURIComponent($.base64.btoa(htmlContent));
    // console.log(url);
    // console.log(encodeURIComponent(url));

    $('body').append('<div class="preview-simulator"><div class="common-mask"></div><div class="phone-simulator"><img src="/images/iphone-bg.png"><iframe class="phone-simulator-iframe" src="'+url+'"></iframe></div></div>');
}

$('body').on('click','.preview-simulator .common-mask',function(){
    $('.preview-simulator').remove();
});

function commonDownload()
{
    // console.log(location.href);
    // location.href = '';
    location.href = location.href + ((location.href.indexOf('?') === -1)?'?':'&') + 'download=1';
}