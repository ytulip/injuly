@extends('admin.master',['headerTitle'=>'会员管理 > 会员详情'])
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

    .statical-record{
        line-height: 40px;
        font-size: 14px;
        padding: 8px;
        margin-bottom: 10px;
    }

    .statical-record span{
        line-height: 40px;
        display: inline-block;
        float: right;
        font-size: 12px;
    }

    .has-bg{background-color: #f0f0f0;}

</style>
@stop
@section('left_content')

    <div style="position: fixed;right: 60px;bottom: 60px;z-index: 999;">
        <div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="javascript:openAddMember()"><i class="fa fa-edit"></i></a></div>
    </div>

    <div class="mt-32 padding-col">
        <h4>基础信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4" style="padding-left: 75px;position: relative">
                    <a class="user-header"><img src="{{$user->header_img}}"/></a>
                    <p class="sm-tag-text">姓名</p>
                    <p class="text-desc-decoration">{{$user->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">用户ID</p>
                    <p class="text-desc-decoration">{{$user->id}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">身份等级</p>
                    <p class="text-desc-decoration">{{\App\Model\User::levelText($user->vip_level)}}</p>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">身份证号码</p>
                    <p class="text-desc-decoration">{{$user->id_card}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">手机号码</p>
                    <p class="text-desc-decoration">{{$user->phone}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">报单可提（箱）</p>
                    <p class="text-desc-decoration">{{$user->get_good}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">复购可提（箱）</p>
                    <p class="text-desc-decoration">{{$user->re_get_good}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">活动可提（箱）</p>
                    <p class="text-desc-decoration">{{$user->activity_get_good}}</p>
                </div>
            </div>
        </div>
        <h4>资产信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">总资产</p>
                    <p class="text-desc-decoration">￥{{number_format($user->charge + $user->charge_frozen,2)}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">可使用资产</p>
                    <p class="text-desc-decoration">￥{{$user->charge}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">未结算资产</p>
                    <p class="text-desc-decoration">￥{{$user->charge_frozen}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <div class="statical-record has-bg">提现次数<span>{{$staticalCashStream['withdrawCount']}}次</span></div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="statical-record">提现金额<span>￥{{\App\Util\Kit::priceFormat($staticalCashStream['withdraw'])}}</span></div>
                </div>
                <div class="col-md-4 col-lg-4">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <a href="/admin/index/direct-indirect-record?user_id={{$user->id}}"><div class="statical-record has-bg">直接开发次数<span>{{$staticalCashStream['directCount']}}次</span></div></a>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="statical-record">直接开发获利<span>￥{{\App\Util\Kit::priceFormat($staticalCashStream['direct'])}}</span></div>
                </div>
                <div class="col-md-4 col-lg-4">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <a href="/admin/index/up-super-record?user_id={{$user->id}}"><div class="statical-record has-bg">一代辅导次数<span>{{$staticalCashStream['upCount']}}次</span></div></a>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="statical-record">一代辅导获利<span>￥{{\App\Util\Kit::priceFormat($staticalCashStream['up'])}}</span></div>
                </div>
                <div class="col-md-4 col-lg-4">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <a href="/admin/index/up-super-record?user_id={{$user->id}}"><div class="statical-record has-bg">二代辅导次数<span>{{$staticalCashStream['superCount']}}次</span></div></a>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="statical-record">二代辅导获利<span>￥{{\App\Util\Kit::priceFormat($staticalCashStream['super'])}}</span></div>
                </div>
                <div class="col-md-4 col-lg-4">
                </div>
            </div>
        </div>
        <h4>会员关系</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">直接开发会员</p>
                    <p class="text-desc-decoration">{{isset($relationMap->direct->real_name)?$relationMap->direct->real_name:''}}&nbsp&nbsp{{isset($relationMap->direct->phone)?$relationMap->direct->phone:''}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">一代辅导会员</p>
                    <p class="text-desc-decoration">{{isset($relationMap->up->real_name)?$relationMap->up->real_name:''}}&nbsp&nbsp{{isset($relationMap->up->phone)?$relationMap->up->phone:''}}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">二代辅导会员</p>
                    <p class="text-desc-decoration">{{isset($relationMap->super->real_name)?$relationMap->super->real_name:''}}&nbsp&nbsp{{isset($relationMap->super->phone)?$relationMap->super->phone:''}}</p>
                </div>
            </div>
        </div>

        <h4>下级会员</h4>
        <div class="block-card">
            <div class="row">

                <div class="col-md-6 col-lg-6">
<a href="/admin/index/sub-member-list?user_id={{$user->id}}&sub_type=1">
    <div class="statical-record has-bg">下级会员<span>{{$user->subListCount()}}个</span></div> </a>
                </div>
                <div class="col-md-6 col-lg-6"><a href="/admin/index/sub-member-list?user_id={{$user->id}}&sub_type=2"><div class="statical-record has-bg">下下级会员<span>{{$user->subDeepListCount()}}个</span></div></a></div>
            </div>
        </div>

        <h4>活动信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">是否参加活动</p>
                    <p class="text-desc-decoration">{{$user->hasTakePartInActivity()?'是':'否'}}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">打卡次数</p>
                    <p class="text-desc-decoration"><a href="/admin/index/sign-list?user_id={{$user->id}}">{{$signQuantity}}</a></p>
                </div>
            </div>
        </div>

        <h4>报单/复购/活动标记</h4>
        <div class="block-card" style="margin-bottom:120px;">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <textarea class="form-control" style="height: 120px;" name="comment">{{$user->get_remark}}</textarea>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12" style="text-align: right;">
                    <button type="button" class="btn btn-success" id="send">提交</button>
                </div>
            </div>
        </div>
    </div>


    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="add_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>修改会员信息</h4>
            <form id="data_form">

                <input value="{{\Illuminate\Support\Facades\Request::input('user_id')}}" name="user_id" type="hidden"/>
                {{--<div class="row mb-12" id="up_user_phone_panel">--}}
                    {{--<div class="col-md-3 col-lg-3 t-al-r">上级手机号:</div>--}}
                    {{--<div class="col-md-9 col-lg-9" style="position: relative;">--}}
                        {{--<input class="form-control" name="new_user_up_phone" readonly/>--}}

                        {{--<div style="position: absolute;left:15px;right: 15px;top: 36px;padding: 12px;border: 1px solid rgb(211,211,211);height: 300px;background-color: #ffffff;z-index: 999;overflow: scroll;display: none;" id="user_list_scroll">--}}
                            {{--<input class="form-control" name="search_user"/>--}}
                            {{--<div id="user-list">--}}
                                {{--<div class="user-list-item">18681224578-水水-510322198712324712</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">手机号:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="new_user_phone" value="{{$user->phone}}"/>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">姓名:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="new_user_real_name" value="{{$user->real_name}}"/>
                    </div>
                </div>
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">身份证:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="new_user_id_card" value="{{$user->id_card}}"/>
                    </div>
                </div>
            </form>
            <div>
                <button type="button" class="btn btn-success col-gray-btn mt-32" id="next_step">确认</button>
                <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="closeAddMember()">取消</button>
            </div>
        </div>
    </div>
@stop

@section('script')
<script>

    var pageConfig = {
        user_id:{{$user->id}}
    }

    function closeAddMember()
    {
        $('#add_member_panel').hide();
    }

    function openAddMember()
    {
        $('#add_member_panel').show();
    }


    new SubmitButton({
        selectorStr:'#next_step',
        url:'/admin/index/modify-user',
        prepositionJudge:function()
        {
            if(!(/^1[3|4|5|8|7][0-9]\d{8}$/.test($('input[name="new_user_phone"]').val()))) {
                mAlert('请输入正确的手机号');
                return false;
            }

            if(!($('input[name="new_user_real_name"]').val())) {
                mAlert('请输入姓名');
                return;
            }

            if(!/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/.test($('input[name="new_user_id_card"]').val())){
                mAlert('请输入正确的身份证号');
                return;
            }

            return true;
        },
        callback:function(el,data)
        {
            if(data.status){
                mAlert('修改成功');
//                location.href="/admin/index/members";
                location.reload(true);
            } else {
                mAlert(data.desc);
            }
        },
        data:function(){
            return $('#data_form').serialize();
        }
    });

    new SubmitButton({
        selectorStr:"#send",
        url:'/admin/index/get-remark',
        prepositionJudge:function() {
            var  comment = $('textarea[name="comment"]').val();
            if (!comment) {
                mAlert('标记不能为空');
                return false;
            }
            return true;
        },
        data:function(){
            var  comment = $('textarea[name="comment"]').val();
            return {get_remark:comment,user_id:pageConfig.user_id};
        }
    });
</script>
    @stop