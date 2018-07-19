function CityData(config){
    this.cityMap = {};
    this.config = {};
    if ( typeof(config) == "undefined" ) {
        config = {};
    }
    this.config.url = (typeof (config.url) != "undefined")?config.url:'/js/city.json';

    (function(a){
        $.ajax({
            type:'get',
            url:a.config.url,
            async:false,
            dataType:'json',
            success:function(data){
                a.cityMap = data;
                //补全第二级
                $.each(a.cityMap,function(index,obj){
                    if(!obj.child.length){
                        a.cityMap[index].child = [{id:obj.id,name:obj.name}];
                    }
                });
            }
        })
    })(this);

    this.provinces = [];
    (function(a){
        $.each(a.cityMap,function(index,obj){
            a.provinces.push(obj);
        })
    })(this);
}

CityData.prototype.codeName = function(code){
    var codeName = '';
    (function(a){
        $.each(a.provinces,function(index,obj){
            if( obj.id ==  parseInt(code / 10000) * 10000 ){
                codeName = obj.name;
                $.each(obj.child,function(i,o){
                    if((o.id == parseInt(code / 100) * 100) && (code % 10000)){
                        codeName += o.name;
                        return false;
                    }
                });
                return false;
            }
        });
    })(this);
    return codeName;
}

CityData.prototype.provinceIndex = function(code){
    var ind = 0;
    (function(a){
        $.each(a.provinces,function(index,obj){
            if( obj.id ==  parseInt(code / 10000) * 10000 ) {
                ind = index;
                return false;
            }
        });
    })(this);
    return ind;
}

CityData.prototype.cityIndex = function(code){
    var ind = 0;
    var pind = this.provinceIndex(code);
    $.each(this.provinces[pind].child,function(index,obj){
        if(obj.id == parseInt(code / 100) * 100){
            ind = index;
            return false;
        }
    });
    return ind;
}


CityData.prototype.cityList = function(code){
    var citys = [];
    (function(a){
        $.each(a.provinces,function(index,obj){
            if( obj.id ==  parseInt(code / 10000) * 10000 ) {
                citys = obj.child;
                return false;
            }
        });
    })(this);
    return citys;
}