<?php
namespace App\Util;

use App\Log\src\Logger;
use App\Model\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    public static function attempt(array $array){
        $info = Admin::where('email',$array['email'])->first();
        if(!empty($info) && (!$info->is_disable)){
            if(Hash::check($array['password'],$info->password)){
                Session::push('admin', $info);
                return array('status'=>true,'data'=>'登录成功');
            }else{
                return array('status'=>false,'desc'=>'密码不正确，请重试');
            }
        }else{
            return array('status'=>false,'desc'=>'该用户不存在，或已被禁用，请联系管理员');
        }
    }

    public static function check()
    {
        if(Session::has('admin')){
            return true;
        }else{
            return false;
        }
    }

    public static function id()
    {
        return Session::get('admin')[0]->id;
    }

    public static function user(){
        return Session::get('admin')[0];
    }

    public static function logOut(){
        Session::flush();
    }

    public static function hasPower($powerId)
    {
        $power = Session::get('admin')[0]->power;
        if($power)
        {
            $power = json_decode($power,true);
        } else {
            $power = [];
        }

        return in_array($powerId,$power);
    }
}