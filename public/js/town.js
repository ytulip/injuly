/**
 * 借钱么城市选择
 * @constructor
 */
function Town(config){
    var defaultConfig = {
        province:'#province',
        city:'#city',
        town:'#town',
        initCode:0,
        callback:function(){

        },
        class:'',
        defaultNull:true,
        is_show:true
    };



    this.config = $.extend(defaultConfig,config);

    this.host = $(this.config.selectorStr);
    if(this.host.val()){
        this.initCode = this.host.val();
    }else{
        this.initCode = this.config.initCode;
    }

    this.levelOneDefault = parseInt(parseInt(this.initCode)/10000) * 10000;
    this.levelOneSecond = parseInt(parseInt(this.initCode)/100) * 100;
    this.levelOneThird = this.initCode;

    this.province = $(this.config.province);
    this.city = $(this.config.city);
    this.town = $(this.config.town);
    this.is_show = $(this.config.is_show);
    this.init();
}

Town.prototype.init = function(){
    (function(a){
        $.ajax({
            type:'get',
            url:'/town.json',
            async:false,
            dataType:'json',
            success:function(data){
                a.cityMap = data;
            }
        })
    })(this);

    /**
     * 初始化一级数据
     */
    (function(a){
        var innerHtml = '';
        if(a.config.defaultNull){
            innerHtml += '<option value="">--请选择--</option>';
        }
        $.each(a.cityMap,function(index,obj){
            var selectedFlag = '';

            if(a.levelOneDefault == obj.id){
                selectedFlag = 'selected';
                a.levelOneDefault = false;
            }
            // if(a.province.attr('data_val') == obj.id){
            //     selectedFlag = 'selected';
            // }
            innerHtml += '<option value="'+obj.id+'" ' + selectedFlag+'>'+obj.name+'</option>';
        });
        a.province.html(innerHtml);
        a.province.change();
    })(this);

    this.addListenerP();
    this.province.change();
    this.addListenerT();
};

/**
 * 添加省change监听
 */
Town.prototype.addListenerP = function(){
    (function(a){
        a.province.change(function(){
            var value = $(this).val();
            var data = [];

            $.each(a.cityMap,function(index,obj){
                if(obj.id == value){
                    return data = obj.child;
                }
            });

            //加载第二级
            var innerHtml = '<option value="">请选择</option>';

            /*
             如果省选中 4个直辖市，第二级隐藏，让第三级包含所有区、县
             */
            if($.inArray(value,['110000','120000','310000','500000']) >= 0){
                innerHtml += '<option value="' + value + '" selected>'+ a.province.find(':selected').text()+'</option>';
                a.city.hide();
            }else {
                if(a.config.is_show){
                    a.city.show();
                }
                $.each(data,function(index,obj){
                    var selectedFlag = '';

                    if(a.levelOneSecond == obj.id){
                        selectedFlag = 'selected';
                        a.levelOneSecond = false;
                    }

                    if($.trim(obj.name) == "市辖区" || $.trim(obj.name) == "县") {
                        //console.log( obj.id +  obj.name);
                    }else{
                        //console.log( obj.name + '====');
                        innerHtml += '<option value="' + obj.id + '"' + selectedFlag + '>' + obj.name + '</option>';
                    }
                });
            }

            a.city.html(innerHtml);
            a.city.change();
        });
    })(this);
    this.addListenerC();
    this.city.change();
};

/**
 * 添加市change监听
 */
Town.prototype.addListenerC = function(){
    (function(a){
        a.city.change(function(){
            var value = $(this).val();
            var data = [];
            var innerHtml = '<option value="">请选择</option>';
            /*
             如果省选中 4个直辖市，则直接搜索所有所有直辖市下县和区
             */
            if($.inArray(value,['110000','120000','310000','500000']) >= 0) {
                $.each(a.cityMap, function (index, obj) {
                    $.each(obj.child, function (index, town_arr) {
                        if (town_arr.id.substr(0,2) == value.substr(0,2)) {
                            data.push(town_arr.child);
                        }
                    });
                });
                $.each(data,function(index,obj){
                    $.each(obj,function(index,obj2){
                        var selectedFlag = '';
                        if(a.levelOneThird == obj.id){
                            selectedFlag = 'selected';
                            a.levelOneThird = false;
                        }
                        if(a.town.attr('data_val') == obj2.id){
                            selectedFlag = 'selected';
                        }
                        innerHtml += '<option value="'+obj2.id+'"'+ selectedFlag +'>'+obj2.name+'</option>';
                    });
                });
            }else {
                $.each(a.cityMap, function (index, obj) {
                    $.each(obj.child, function (index, town_arr) {
                        if (town_arr.id == value) {
                            return data = town_arr.child;
                        }
                    });
                });

                $.each(data,function(index,obj){
                    var selectedFlag = '';
                    if(a.levelOneThird == obj.id){
                        selectedFlag = 'selected';
                        a.levelOneThird = false;
                    }
                    if($.trim(obj.name) == "市辖区" || $.trim(obj.name) == "县") {
                        //console.log( obj.id +  obj.name);
                    }else{
                        innerHtml += '<option value="'+obj.id+'"'+ selectedFlag +'>'+obj.name+'</option>';
                    }

                });
            }

            if (a.town.attr('data_val') == -1) {
                innerHtml += '<option value="-1" selected>其他</option>';
            } else {
                innerHtml += '<option value="-1">其他</option>';
            }

            a.town.html(innerHtml);
            a.town.change();
        });
    })(this);
};

Town.prototype.addListenerT = function(){
    (function(a){
        a.town.change(function(){
            a.config.callback($(this).val(),'');
        });
    })(this);
}


Town.prototype.setValue = function(cityCode){
    // a.prototype.
    this.levelOneDefault = parseInt(parseInt(cityCode)/10000) * 10000;
    this.levelOneSecond = parseInt(parseInt(cityCode)/100) * 100;
    this.levelOneThird = cityCode;
    this.province.val(this.levelOneDefault?this.levelOneDefault:'');
    this.province.change();
}