@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 购买管理<span class="title-gap">></span> 新增购买'])
@section('style')
@stop
@section('left_content')
    <p class="mt-32" style="text-align: center;">订单已经提交成功，邀请码是{{$order->getInvitedCode()}}</p>
@stop