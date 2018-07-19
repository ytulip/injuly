@extends('admin.master',['headerTitle'=>'商品管理 <span class="title-gap">></span> 编辑定价规则'])
@section('style')
    <style>
        #edui1{width:100%!important;}
        .essay_img{position: absolute;top:50%;right: 40px;width: 60px;height: 60px;border-radius: 8px;transform: translateY(-50%);-webkit-transform: translateY(-50%);overflow: hidden;}
        .essay_img img{width: 60px;height: 60px;border-radius: 8px;}
    </style>
@stop
@section('left_content')
    <form id="data_form">
        <input value="{{\Illuminate\Support\Facades\Request::input('id')}}" name="attr_id" class="dpn"/>
    <div class="row mt-32">
        <div class="col-md-12 col-lg-12">
            <div class="block-card">
                <p>规则名称</p>
                <div class="row">
                    <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="attr_des" value="{{$attr->attr_des}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-32">
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>购买数量限制</p>
                <div class="row">
                    <div class="col-md-11 col-lg-11"><input class="form-control no-border-input" name="quantity" value="{{$attr->quantity}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>购买价格</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="single_price" value="{{$attr->single_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-32">
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>直接开发费用</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="single_direct_price" value="{{$attr->single_direct_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>间接开发费用</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="single_indirect_price" value="{{$attr->single_indirect_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-32">
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>一代辅导费用</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="single_up_price" value="{{$attr->single_up_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>二代辅导费用</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-110"><input class="form-control no-border-input" name="single_super_price" value="{{$attr->single_super_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-32">
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>复购价格</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="rebuy_price" value="{{$attr->rebuy_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>复购一代辅导费用</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="rebuy_up_price" value="{{$attr->rebuy_up_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>\
    </div>


    <div class="row mt-32">
        <div class="col-md-6 col-lg-6">
            <div class="block-card">
                <p>复购二代辅导费用</p>
                <div class="row">
                    <div class="col-md-1 col-lg-1" style="line-height: 34px;">￥</div>
                    <div class="col-md-10 col-lg-10"><input class="form-control no-border-input" name="rebuy_super_price" value="{{$attr->rebuy_super_price}}"/></div>
                    <div class="col-md-1 col-lg-1"><a class="fl-r editor-pen-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </div>
    </div>

    </form>
    <div class="row">
        <div class="col-md-10 col-lg-10"></div>
        <div class="col-md-2 col-lg-2"><button type="button" class="btn btn-success col-gray-btn mt-32" id="next_step">保存</button></div></div>
@stop

@section('script')
<script>
    new SubmitButton({
        selectorStr:"#next_step",
        url:"/admin/index/good-attr",
        callback:function(obj,data){
            if(data.status) {
                //location.reload();
                mAlert('保存成功');
            } else {
                mAlert(data.desc);
            }
        },
        data:function()
        {
            return $("#data_form").serialize();
        }
    });
</script>
@stop