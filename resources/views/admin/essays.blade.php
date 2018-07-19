@extends('admin.master',['headerTitle'=>'内容管理'])
@section('left_content')
<div class="mt-32">
    <div class="row">
        <div class="col-md-1 col-lg-1"></div>
        <div class="col-md-11 col-lg-11">
            <div class="block-card-nbg2">
            <div class="row">
                <div class="col-md-1 col-lg-1">序号</div>
                <div class="col-md-8 col-lg-8">标题</div>
                <div class="col-md-2 col-lg-2">发布时间</div>
                <div class="col-md-1 col-lg-1">编辑</div>
            </div>
            </div>
        </div>
    </div>

    @foreach($list as $key=>$item)
    <div class="row paginate-list-row2">
        <div class="col-md-1 col-lg-1"></div>
        <div class="col-md-11 col-lg-11">
            <div class="block-card">
            <div class="row">
                <div class="col-md-1 col-lg-1">{{$key + 1}}</div>
                <div class="col-md-8 col-lg-8">{{$item->title}}</div>
                <div class="col-md-2 col-lg-2">{{$item->created_at}}</div>
                <div class="col-md-1 col-lg-1"><a href="/admin/index/edit-essay?id={{$item->id}}"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
            </div>
            </div>
        </div>
    </div>
        @endforeach

</div>
<div style="position: fixed;right: 60px;bottom: 60px;">
    <div style="width: 56px;height:56px;border-radius:96px;background-color:#697b8c; "><a style="width: 100%;display: block;line-height: 56px;text-align: center;font-size: 40px;color:#ffffff;" href="/admin/index/edit-essay"><i class="fa fa-plus"></i></a></div>
</div>
@stop
