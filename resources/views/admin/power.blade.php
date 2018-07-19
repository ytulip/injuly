@extends('admin.master',['headerTitle'=>'管理员列表'])
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
        <div class="row">
            <div class="col-md-6 col-lg-6"><h4>管理员信息</h4></div>
            <div class="col-md-4 col-lg-4">

                <form class="form-inline" id="search_form">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="exampleInputAmount" name="keyword" placeholder="输入姓名、手机号搜索" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}">
                            <div class="input-group-addon" onclick="search()"><i class="fa fa-search"></i></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2 col-lg-2">
                <a class="btn btn-info" href="javascript:commonDownload()">下载</a>
            </div>
        </div>
        <div class="block-card">
            <div class="row paginate-list-row">
                <div class="col-md-2 col-lg-2">ID</div>
                <div class="col-md-2 col-lg-2">用户名</div>
                <div class="col-md-2 col-lg-2">是否禁用</div>
                <div class="col-md-2 col-lg-2">创建时间</div>
                <div class="col-md-4 col-lg-4">操作</div>
            </div>
            @foreach($paginate as $item)
                <div class="row paginate-list-row bg-none">
                    <div class="col-md-2 col-lg-2">{{$item->id}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->email}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->is_disable?'是':'否'}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->created_at}}</div>
                    <div class="col-md-4 col-lg-4">@if($item->is_disable) <a class="btn btn-primary" href="javascript:setDisable({{$item->id}},0)">启用</a> @else <a class="btn btn-primary" href="javascript:setDisable({{$item->id}},1)">禁用</a> @endif<a class="btn btn-primary m-l-12" href="javascript:editAdmin({{$item->id}},'{{$item->email}}','{{$item->power}}')">编辑</a></div>
                </div>
            @endforeach
        </div>

        <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>

    </div>

    <div style="position: fixed;right: 60px;bottom: 60px;">
        <div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="javascript:openAddMember()"><i class="fa fa-plus"></i></a></div>
    </div>


    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="edit_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>编辑管理员</h4>
            <form id="edit_data_form">
                <input name="power_array" type="hidden"/>
                <input name="id" type="hidden"/>
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">登录名:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="email"/>
                    </div>
                </div>
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">是否重置密码:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="" name="reset_pwd" type="checkbox" value="1"/>
                    </div>
                </div>
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">重置密码:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="password" type="password"/>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">确认重置密码:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="confirm_password" type="password"/>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">权限选择:</div>
                    <div class="col-md-9 col-lg-9">
                        {{--<input class="form-control" name="confirm_password" type="password"/>--}}
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="1"/><label>概况</label>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="2"/><label>商品管理</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="3"/><label>活动商品管理</label>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="4"/><label>内容管理</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="5"/><label>会员管理</label>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="6"/><label>活动会员管理</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="7"/><label>打卡记录</label>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="8"/><label>资产管理</label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <input type="checkbox" class="power_check" value="9"/><label>管理员列表</label>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            <div>
                <button type="button" class="btn btn-success col-gray-btn mt-32" id="edit_next_step">确认</button>
                <button type="button" class="btn btn-success col-gray-btn mt-32" onclick="closeAddMember()">取消</button>
            </div>
        </div>
    </div>


    <div style="position: fixed;top:0;bottom:0;left:0;right: 0;background-color: rgba(0,0,0,.6);z-index: 99;display: none;" id="add_member_panel">
        <div style="padding: 14px;background-color: #ffffff;border-radius: 8px;transform: translate(-50%,-50%);position: absolute;top:50%;left: 50%;width: 540px;">
            <h4>新增管理员</h4>
            <form id="data_form">
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">登录名:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="email"/>
                    </div>
                </div>
                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">密码:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="password" type="password"/>
                    </div>
                </div>

                <div class="row mb-12">
                    <div class="col-md-3 col-lg-3 t-al-r">确认密码:</div>
                    <div class="col-md-9 col-lg-9">
                        <input class="form-control" name="confirm_password" type="password"/>
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
            user_list:{!! json_encode(DB::table('users')->selectRaw('phone,id_card,real_name')->orderBy('id','desc')->get()) !!}
        }

        function setDisable(id,status)
        {
            $.ajax(
                {
                    url:'/admin/index/modify-admin',
                    data:{id:id,status:status},
                    dataType:'json',
                    success:function(data)
                    {
                        mAlert('操作成功');
                        location.reload(true);
                    },
                    error:function(){
                        mAlert('网络异常');
                    }
                }
            );
        }


        $(document).click(function(e){
            e = window.event || e; // 兼容IE7
            obj = $(e.srcElement || e.target);
            if ($(obj).is("#up_user_phone_panel,#up_user_phone_panel *")) {
                // alert('内部区域');
                console.log('内部区域');
            } else {
                console.log('你的点击不在目标区域');
                if($('#user_list_scroll').css('display') == 'block') {
                    $('#user_list_scroll').hide();
                }
            }
        });


        $('body').on('click','.user-list-item',function(){
            $('input[name="new_user_up_phone"]').val( $(this).attr('data-phone') );
            $('#user_list_scroll').hide();
        });

        $('input[name="new_user_up_phone"]').click(function(){
            // console.log(456);
            if($('#user_list_scroll').css('display') == 'block') {
                $('#user_list_scroll').hide();
            }else {
                $('#user_list_scroll').show();
            }
        });
        ;


        $('input[name="search_user"]').bind('input propertychange', function() {
            // console.log($(this).val());
            changeUserList($(this).val());
        });



        changeUserList();

        function changeUserList(searchVal)
        {
            $('input[name="search_user"]').val();

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
            $('#user-list').html('');
            $('#user-list').html(innerHtml);
        }

        // changeUserList();



        function goDetail(id)
        {
            location.href = '/admin/index/member-detail?user_id=' + id;
        }

        // $('#add_member_panel').show();

        function closeAddMember()
        {
            $('#edit_member_panel').hide();
            $('#add_member_panel').hide();
        }

        function openAddMember()
        {
            $('#add_member_panel').show();
//            $('#edit_member_panel').show();
        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        // edit_next_step
        new SubmitButton({
            selectorStr:'#edit_next_step',
            url:'/admin/index/modify-admin',
            prepositionJudge:function()
            {
                var powerArr = [];
                $('.power_check').each(function(){
                    if($(this).prop('checked'))
                    {
//                        console.log($(this).val());
                        powerArr.push($(this).val());
                    }
                });
                $('input[name="power_array"]').val(JSON.stringify(powerArr));
//                console.log($('#edit_data_form').serialize());
//                return  false;
                if(!($('#edit_data_form input[name="email"]').val())) {
                    mAlert('请输入登录名');
                    return;
                }

                if( $('input[name="reset_pwd"]').prop('checked') && !($('#edit_data_form input[name="password"]').val())){
                    mAlert('请输入密码');
                    return;
                }

                if( $('input[name="reset_pwd"]').prop('checked') && ($('#edit_data_form input[name="password"]').val() != $('#edit_data_form input[name="confirm_password"]').val()) ){
                    mAlert('两次输入的密码不一致');
                    return;
                }

                return true;
            },
            callback:function(el,data)
            {
                if(data.status){
                    mAlert('修改成功');
                    location.href="/admin/index/power";
                } else {
                    mAlert(data.desc);
                }
            },
            data:function(){
                return $('#edit_data_form').serialize();
            }
        });


        new SubmitButton({
            selectorStr:'#next_step',
            url:'/admin/index/add-admin',
            prepositionJudge:function()
            {
                // if(!(/^1[3|4|5|8|7][0-9]\d{8}$/.test($('input[name="new_user_phone"]').val()))) {
                //     mAlert('请输入正确的手机号');
                //     return false;
                // }

                if(!($('#add_member_panel input[name="email"]').val())) {
                    mAlert('请输入登录名');
                    return;
                }

                if(!($('#add_member_panel input[name="password"]').val())){
                    mAlert('请输入密码');
                    return;
                }

                if( $('#add_member_panel input[name="password"]').val() != $('#add_member_panel input[name="confirm_password"]').val() ){
                    mAlert('两次输入的密码不一致');
                    return;
                }

                return true;
            },
            callback:function(el,data)
            {
                if(data.status){
                    mAlert('添加成功');
                    location.href="/admin/index/power";
                } else {
                    mAlert(data.desc);
                }
            },
            data:function(){
                return $('#data_form').serialize();
            }
        });

        function  editAdmin(id,email,power) {
            //设置属性
            $('#edit_data_form input[name="id"]').val(id);
            $('#edit_data_form input[name="email"]').val(email);
            $('#edit_member_panel').show();

            $('.power_check').prop('checked',false);

            if( power )
            {
                var powers = JSON.parse(power);
                console.log(powers);
                $(powers).each(function (ind,obj) {
                    // console.log(obj);
                    $('.power_check[value='+obj+']').prop('checked',true);
                });
            }
            // powers.each(function(ind,obj){
            //     console.log(obj);
            // });
        }


    </script>
@stop