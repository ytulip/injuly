@extends('admin.master',['headerTitle'=>'打卡记录 > 记录详情'])
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
    <div class="mt-32 padding-col">
        <h4>基础信息</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">姓名</p>
                    <p class="text-desc-decoration">{{$user->real_name}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">身份证号</p>
                    <p class="text-desc-decoration">{{$user->id_card}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">手机号</p>
                    <p class="text-desc-decoration">{{$user->phone}}</p>
                </div>
            </div>



            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">当前已卡次数</p>
                    <p class="text-desc-decoration">{{count($list)}}</p>
                </div>
                <div class="col-md-6 col-lg-6">
                    <p class="sm-tag-text">当前已食用数量</p>
                    <p class="text-desc-decoration">{{$countSum}}</p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">打卡类型</p>
                    <p class="text-desc-decoration">{{\App\Util\Kit::signType( $signDetail->signTypeIndex)}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">吃的数量</p>
                    <p class="text-desc-decoration">{{$signDetail->countIndex + 1}}</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">排便次数</p>
                    <p class="text-desc-decoration">{{$signDetail->wcCount}}</p>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">喝水量</p>
                    <p class="text-desc-decoration">{{$signDetail->water}}(ml)</p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">体重</p>
                    <p class="text-desc-decoration">{{$signDetail->weight}}(kg)</p>
                </div>
                <div class="col-md-4 col-lg-4"></div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12 col-lg-12">
                    <p class="sm-tag-text">使用感受</p>
                    <p class="text-desc-decoration">{{$signDetail->baseInfo}}</p>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">早餐照</p>
                    <p class="text-desc-decoration">
                        <img src="{{$signDetail->imgPath1Save}}" style="width: 100%;"/>
                    </p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">午餐照</p>
                    <p class="text-desc-decoration">
                        <img src="{{$signDetail->imgPath2Save}}" style="width: 100%;"/>
                    </p>
                </div>
                <div class="col-md-4 col-lg-4">
                    <p class="sm-tag-text">晚餐照</p>
                    <p class="text-desc-decoration">
                        <img src="{{$signDetail->imgPath3Save}}" style="width: 100%;"/>
                    </p>
                </div>
            </div>

        </div>
    </div>


    <div class="mt-32 padding-col">
        <h4>管理员评论</h4>
        <div class="block-card">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <textarea  name="comment" style="width: 100%;height: 120px;">{{$signRecord->comment}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12" style="text-align: right;">
                    <button type="button" class="btn btn-success" id="send">提交</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')

<script>
    var pageConfig = {
        id:{{$signRecord->id}}
    }

    $(function(){
        new SubmitButton({
            selectorStr:"#send",
            url:'/admin/index/sign-comment',
            prepositionJudge:function() {
                var  comment = $('textarea[name="comment"]').val();
                if (!comment) {
                    mAlert('评论不能为空');
                    return false;
                }
                return true;
            },
            data:function(){
                var  comment = $('textarea[name="comment"]').val();
                return {comment:comment,id:pageConfig.id};
            }
        });
    });
</script>

    @stop