@extends('admin.master',['headerTitle'=>'会员管理'])
@section('style')
    <style>
        .mb-12{
            margin-bottom: 12px;
        }

        .t-al-r{text-align: right;}

        .user-list-item{font-size: 14px;padding: 6px 2px;}
        .user-list-item:hover{background-color: #f5f5f5}
    </style>
@stop
@section('left_content')
    <div class="mt-32 padding-col">
        <h4>会员信息</h4>
        <div class="row m-t-20">
            <div class="col-md-12 col-lg-12">

                <form class="form-inline" id="search_form">
                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">未提货状态:</label>
                        <div class="input-group">
                            <select type="text" class="form-control" id="exampleInputAmount" name="get_status">
                                <option></option>
                                <option value="1" {{\Illuminate\Support\Facades\Request::input('get_status') == "1"?' selected':''}}>报单未提</option>
                                <option value="2" {{\Illuminate\Support\Facades\Request::input('get_status') == "2"?' selected':''}}>复购未提</option>
                                <option value="3" {{\Illuminate\Support\Facades\Request::input('get_status') == "3"?' selected':''}}>活动未提</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;
    padding: 0;
    text-align: left;">未退款:</label>
                        <div class="input-group">
                            <select type="text" class="form-control" id="exampleInputAmount" name="activity_turn">
                                <option></option>
                                <option value="1" {{\Illuminate\Support\Facades\Request::input('activity_turn') == "1"?' selected':''}}>未退款</option>
                            </select>
                        </div>
                    </div>



                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <input type="text" class="form-control" id="exampleInputAmount" name="keyword" placeholder="输入姓名、手机号搜索" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}">
                            <div class="input-group-addon" onclick="search()"><i class="fa fa-search"></i></div>
                        </div>
                    </div>

                    <div class="form-group v-a-b">
                        <div class="input-group">
                            <a class="btn btn-info" href="javascript:commonDownload()">下载</a>
                        </div>
                    </div>
                </form>
            </div>
            {{--<div class="col-md-2 col-lg-2">--}}
                {{--<a class="btn btn-info" href="javascript:commonDownload()">下载</a>--}}
            {{--</div>--}}
        </div>
        <div class="block-card">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">会员ID</div>
                <div class="col-md-1 col-lg-1">姓名</div>
                <div class="col-md-1 col-lg-1">身份等级</div>
                <div class="col-md-1 col-lg-1">手机号码</div>
                <div class="col-md-2 col-lg-2">身份证号码</div>
                <div class="col-md-1 col-lg-1">报单</div>
                <div class="col-md-1 col-lg-1">提货</div>
                <div class="col-md-1 col-lg-1">活动</div>
                <div class="col-md-1 col-lg-1">未退款</div>
            </div>
            @foreach($paginate as $item)
                <div class="row paginate-list-row" onclick="goDetail({{$item->id}})">
                    <div class="col-md-2 col-lg-2">{{$item->id}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->real_name}}</div>
                    <div class="col-md-1 col-lg-1">{{\App\Model\User::levelText($item->vip_level)}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->phone}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->id_card}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->get_good}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->re_get_good}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->activity_get_good}}</div>
                    <div class="col-md-1 col-lg-1">{{$item->needToTurn()?'是':'否'}}</div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>

    </div>

    <div style="position: fixed;right: 60px;bottom: 60px;">
        <div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="javascript:openAddMember()"><i class="fa fa-plus"></i></a></div>
    </div>


    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="up_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>升级活动会员</h4>
            <form id="edit_data_form">
                <input name="user_id" type="hidden"/>
                <div class="row mb-12" id="up_user_phone_panel">
                    <div class="col-md-3 col-lg-3 t-al-r">上级手机号:</div>
                    <div class="col-md-9 col-lg-9" style="position: relative;">
                        <input class="form-control" name="new_user_up_phone" readonly/>

                        <div style="position: absolute;left:15px;right: 15px;top: 36px;padding: 12px;border: 1px solid rgb(211,211,211);height: 300px;background-color: #ffffff;z-index: 999;overflow: scroll;display: none;" id="user_list_scroll2">
                            <input class="form-control" name="search_user"/>
                            <div id="user-list2">
                                {{--<div class="user-list-item">18681224578-水水-510322198712324712</div>--}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">报单库存:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="get_good" value="0"/>
                    </div>
                </div>
            </form>
            <div>
                <button type="button" class="btn btn-success col-gray-btn mt-32" id="up_next_step">升级</button>
                <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="closeAddMember()">取消</button>
            </div>
        </div>
    </div>

    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="add_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>活动会员报单</h4>
            <form id="data_form">

                <div class="row mb-12" id="up_user_phone_panel">
                    <div class="col-md-3 col-lg-3 t-al-r">上级手机号:</div>
                    <div class="col-md-9 col-lg-9" style="position: relative;">
                        <input class="form-control" name="new_user_up_phone" readonly/>

                        <div style="position: absolute;left:15px;right: 15px;top: 36px;padding: 12px;border: 1px solid rgb(211,211,211);height: 300px;background-color: #ffffff;z-index: 999;overflow: scroll;display: none;" id="user_list_scroll">
                            <input class="form-control" name="search_user"/>
                            <div id="user-list">
                                {{--<div class="user-list-item">18681224578-水水-510322198712324712</div>--}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">手机号:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="new_user_phone"/>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">姓名:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="new_user_real_name"/>
                    </div>
                </div>
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">身份证:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="new_user_id_card"/>
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
            user_list:{!! json_encode(DB::table('users')->selectRaw('phone,id_card,real_name')->orderBy('id','desc')->where('vip_level',\App\Model\User::LEVEL_MASTER)->get()) !!}
        }


        $(document).click(function(e){
            e = window.event || e; // 兼容IE7
            obj = $(e.srcElement || e.target);
            if ($(obj).is("#up_user_phone_panel,#up_user_phone_panel *,#up_member_panel,#up_member_panel *")) {
                // alert('内部区域');
                console.log('内部区域');
            } else {
                console.log('你的点击不在目标区域');
                if($('#user_list_scroll').css('display') == 'block') {
                    $('#user_list_scroll').hide();
                }

                if($('#user_list_scroll2').css('display') == 'block') {
                    $('#user_list_scroll2').hide();
                }
            }
        });

        $('body').on('click','.user-list-item',function(){
            $('input[name="new_user_up_phone"]').val( $(this).attr('data-phone') );
            $('#user_list_scroll,#user_list_scroll2').hide();
        });

        $('#add_member_panel input[name="new_user_up_phone"]').click(function(){
            // console.log(456);
            if($('#user_list_scroll').css('display') == 'block') {
                $('#user_list_scroll').hide();
            }else {
                $('#user_list_scroll').show();
            }
        });


        $('#up_member_panel input[name="new_user_up_phone"]').click(function(){
            // console.log(456);
            if($('#user_list_scroll2').css('display') == 'block') {
                $('#user_list_scroll2').hide();
            }else {
                $('#user_list_scroll2').show();
            }
        });


        $('#add_member_panel input[name="search_user"]').bind('input propertychange', function() {
            // console.log($(this).val());
            changeUserList($(this).val());
        });


        $('#up_member_panel input[name="search_user"]').bind('input propertychange', function() {
            console.log($(this).val());
            changeUserList2($(this).val());
        });

        changeUserList();
        changeUserList2();



        function changeUserList2(searchVal)
        {
            $('#up_member_panel input[name="search_user"]').val();

            var innerHtml = '';

            $.each(pageConfig.user_list,function(ind,obj){

                if(searchVal) {
                    var showFlag = false;

                    if ( obj.phone === null || obj.phone.indexOf(searchVal) != 0 )
                    {

                    } else {
                        showFlag = true;
                    }


                    if ( obj.real_name === null || obj.real_name.indexOf(searchVal) != 0 )
                    {

                    } else {
                        showFlag = true;
                    }

                    if ( obj.id_card === null || obj.id_card.indexOf(searchVal) != 0 )
                    {

                    } else {
                        showFlag = true;
                    }

                    if( ! showFlag )
                    {
                        return true;
                    }

                    // if( obj.phone.indexOf(searchVal) != 0 && obj.real_name.indexOf(searchVal) != 0 &&  obj.id_card.indexOf(searchVal) != 0 ) {
                    //     return;
                    // }
                }

                innerHtml += '<div class="user-list-item" data-phone="'+obj.phone+'">' + obj.phone + '-' + obj.real_name + '-'+obj.id_card+'</div>';
            });

            console.log(innerHtml);
            $('#up_member_panel #user-list2').html('');
            $('#up_member_panel #user-list2').html(innerHtml);
        }


        function changeUserList(searchVal)
        {
            $('#add_member_panel input[name="search_user"]').val();

            var innerHtml = '';

            $.each(pageConfig.user_list,function(ind,obj){

                if(searchVal) {
                    var showFlag = false;

                    if ( obj.phone === null || obj.phone.indexOf(searchVal) != 0 )
                    {

                    } else {
                        showFlag = true;
                    }


                    if ( obj.real_name === null || obj.real_name.indexOf(searchVal) != 0 )
                    {

                    } else {
                        showFlag = true;
                    }

                    if ( obj.id_card === null || obj.id_card.indexOf(searchVal) != 0 )
                    {

                    } else {
                        showFlag = true;
                    }

                    if( ! showFlag )
                    {
                        return true;
                    }

                    // if( obj.phone.indexOf(searchVal) != 0 && obj.real_name.indexOf(searchVal) != 0 &&  obj.id_card.indexOf(searchVal) != 0 ) {
                    //     return;
                    // }
                }

                innerHtml += '<div class="user-list-item" data-phone="'+obj.phone+'">' + obj.phone + '-' + obj.real_name + '-'+obj.id_card+'</div>';
            });

            // console.log(innerHtml);
            $('#add_member_panel #user-list').html('');
            $('#add_member_panel #user-list').html(innerHtml);
        }

        // changeUserList();



        function goDetail(id)
        {
            location.href = '/admin/index/member-detail?user_id=' + id;
        }

        // $('#add_member_panel').show();

        function closeAddMember()
        {
            $('#add_member_panel').hide();
            $('#up_member_panel').hide();
        }

        function openAddMember()
        {
            $('#add_member_panel').show();
        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }


        new SubmitButton({
            selectorStr:'#next_step',
            url:'/admin/index/add-activity-user',
            prepositionJudge:function()
            {
                if(!(/^1[0-9][0-9]\d{8}$/.test($('input[name="new_user_phone"]').val()))) {
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
                    mAlert('添加成功');
                    location.href="/admin/index/activity-members";
                } else {
                    mAlert(data.desc);
                }
            },
            data:function(){
                return $('#data_form').serialize();
            }
        });


        new SubmitButton({
            selectorStr:'#up_next_step',
            url:'/admin/index/up-activity-user',
            prepositionJudge:function()
            {
                return true;
            },
            callback:function(el,data)
            {
                if(data.status){
                    mAlert('添加成功');
                    location.href="/admin/index/activity-members";
                } else {
                    mAlert(data.desc);
                }
            },
            data:function(){
                return $('#edit_data_form').serialize();
            }
        });

        function upUser(id)
        {
            event.stopPropagation();
            // alert(3);
            $('input[name="user_id"]').val(id);
            $('#up_member_panel').show();
        }


    </script>
@stop