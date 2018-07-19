@extends('admin.master',['headerTitle'=>'资产管理 <span class="title-gap">></span> 购买管理 <span class="title-gap">></span> 新增购买'])
@section('style')

@stop
@section('left_content')
    <div class="mt-32 padding-col">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>购买人姓名</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>身份等级</p>
                </div>
            </div>
        </div>

        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>手机号码</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>身份证号码</p>
                </div>
            </div>
        </div>

        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>直接开发者手机号</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>购买模式</p>
                </div>
            </div>
        </div>

        <div class="row mt-32">
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>一代辅导会员</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="block-card">
                    <p>二代辅导会员</p>
                </div>
            </div>
        </div>

        <div>
            <div class="row" style="margin-top: 60px;">
                <div class="col-md-4 col-lg-4">
                </div>
                <div class="col-md-4 col-lg-4">
                </div>
                <div class="col-md-4 col-lg-4">
                    <button type="button" class="btn btn-success col-gray-btn">购买</button>
                </div>
            </div>
        </div>
    </div>
@stop