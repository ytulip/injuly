function CusSelect(config)
{

    var configDefault = {
        itemArr:[],
        callback:function(){

        }
    }

    this.config = $.extend(configDefault,config);
    this.itemArrLength = this.config.itemArr.length;
    var liHmtl = '';
    $.each(this.config.itemArr,function(key,value){
        liHmtl += '<li>'+value.name+'</li>';
    });
    var innerHtml = '<div class="city-mask" id="city_mask_'+this.config.idSpecial+'"><div class="city-panel"><div class="city-panel-header"><a class="do-cancel">取消</a><a class="do-yes">确定</a></div> <div class="city-panel-body"><div class="city-barrier"></div> <ul style="padding-left: 0;margin: 0;" data-classify="p" class="province-list cityul" id="pp'+this.config.idSpecial+'">'+liHmtl+'</ul></div></div></div>';
    $('body').append(innerHtml);
    $('.city-mask').hide();


    var pp = document.querySelector('#pp' + this.config.idSpecial);
    this.hostEl = '#city_mask_' + this.config.idSpecial;
    var doYes = document.querySelector(this.hostEl + ' .do-yes');
    var doCancel = document.querySelector(this.hostEl +' .do-cancel');



    pp.addEventListener('touchstart',cityTouchStart);
    pp.addEventListener('touchmove',cityTouchMove);
    pp.addEventListener('touchend',cityTouchEnd);

    doCancel.addEventListener('click',no);
    doYes.addEventListener('click',yes);



    //

    var thisClass = this;
    pp.style.top = thisClass.ptop + 'px';

    $(this.config.triggerEl).click(function(){
        thisClass.show();
    });

    function cityTouchStart(e)
    {
        e.preventDefault();
        thisClass.startPoint.y = e.targetTouches[0].screenY;
        var target = e.target;
        while (true) {
            if (!target.classList.contains("cityul")) {
                target = target.parentElement;
            } else {
                break
            }
        }
        //this.startPoint.classify = $(target).attr('data-classify');
        thisClass.startPoint.classify = target.getAttribute('data-classify');
    }

    function cityTouchMove(e)
    {
        e.preventDefault();
        thisClass.movePoint.y = e.targetTouches[0].screenY;
        thisClass.ptop = thisClass.ptop + (thisClass.movePoint.y - thisClass.startPoint.y);
        // if(thisClass.startPoint.classify == 'p'){
        //     thisClass.ptop = thisClass.ptop + (thisClass.movePoint.y - thisClass.startPoint.y);
        // }else{
        //     return false;
        // }
        // console.log(thisClass.ptop);
        pp.style.top = thisClass.ptop + 'px';
        thisClass.startPoint.y = thisClass.movePoint.y;
    }

    function no()
    {
        $(pp).parents('.city-mask').hide();
    }

    function yes()
    {
        var index = thisClass.lines - (thisClass.ptop / thisClass.lineHeight);
        // return this.provinces[index];
        $(pp).parents('.city-mask').hide();
        console.log(index);
        console.log(thisClass.config.itemArr[index]);
        // alert(thisClass.config.itemArr[index].name);
        $(thisClass.config.triggerEl).val(thisClass.config.itemArr[index].name);
        $(thisClass.config.triggerEl).attr('cus-select-value',thisClass.config.itemArr[index].value);
        thisClass.config.callback(thisClass.config.itemArr[index].value);
    }

    // no:function(){
    //     this.maskshow = 0;
    //     this.calcTop(this.hold.id);
    // },
    // yes:function(){
    //     this.maskshow = 0;
    //     var cityCode = this.cityNow().id;
    //     this.hold = {
    //         id:cityCode,
    //         name:this.cityMap.codeName(cityCode)
    //     };
    //     this.calcTop(this.hold.id);
    // }

    function cityTouchEnd()
    {
        var length = 0;
        // if(this.startPoint.classify == 'p'){
        //     length = this.provinces.length;
        // }else if(this.startPoint.classify == 'c'){
        //     length = this.citys.length;
        // }else{
        //     return false;
        // }

        length = thisClass.itemArrLength;

        heightLimit = [thisClass.lines * thisClass.lineHeight, -(length - thisClass.lines -1) * thisClass.lineHeight];

        console.log(heightLimit);

        var remainder = '';
        // if(this.startPoint.classify == 'p') {
        console.log(thisClass.ptop);
            if(thisClass.ptop > heightLimit[0]){
                thisClass.ptop = 74;
                pp.style.top = thisClass.ptop + 'px';
            }else if(thisClass.ptop < heightLimit[1]){
                thisClass.ptop = -(thisClass.itemArrLength - 3)* 37;
                pp.style.top = thisClass.ptop + 'px';
            }else{
                remainder = thisClass.ptop % 37;
                if(remainder <= 0 && remainder > - 18){
                    thisClass.ptop = parseInt(thisClass.ptop / 37) * 37;
                    pp.style.top = thisClass.ptop + 'px';
                }else{
                    console.log('there');
                    //parseInt(thisClass.ptop / 37 - 1)
                    // thisClass.ptop = parseInt(thisClass.ptop / 37 - 1) * 37;
                    // pp.style.top = thisClass.ptop + 'px';

                    if(thisClass.itemArrLength == 1) {
                        thisClass.ptop = 37;
                        pp.style.top = thisClass.ptop + 'px'
                        return;
                    }

                    if(thisClass.itemArrLength == 2){
                        thisClass.ptop = 74;
                        pp.style.top = thisClass.ptop + 'px'
                        return;
                    }

                    thisClass.ptop = parseInt(thisClass.ptop / 37 - 1) * 37;
                    pp.style.top = thisClass.ptop + 'px';
                }
            }

            /*调取相对应的市*/
            // var provinceNow = this.provinceNow();
            //this.citys = provinceNow.child;
            // this.calcTop(provinceNow['id']);

        // }else if(this.startPoint.classify == 'c'){
        //     if(this.ctop > heightLimit[0]){
        //         this.ctop = 74;
        //     }else if(this.ctop < heightLimit[1]){
        //         this.ctop = -(this.citys.length - 3)* 37;
        //     }else{
        //         remainder = this.ptop % 37;
        //         if(remainder <= 0 && remainder > - 18){
        //             this.ctop = parseInt(this.ctop / 37) * 37;
        //         }else{
        //             this.ctop = parseInt(this.ctop / 37 - 1) * 37;
        //         }
        //     }
        // }

    }
}


CusSelect.prototype.startPoint = {y:0,classify:''};
CusSelect.prototype.movePoint = {y:0};
CusSelect.prototype.lineHeight = 37;//单位为px
CusSelect.prototype.lines = 2;
CusSelect.prototype.ptop = 74;
CusSelect.prototype.show = function()
{
    $('#city_mask_' + this.config.idSpecial ).show()
}
// CusSelect.prototype.itemArr = [];