<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserAddress extends Model
{
    public $table = 'user_address';
    public $primaryKey = 'address_id';

    /**
     * 单个商品自提地址
     */
    public static function goodSelfGetAddressConfig($goodId)
    {
        $res = DB::table((new UserAddress())->table)->where(['good_id'=>$goodId,'status'=>1])->selectRaw('address_id as ITEMNO,concat(pct_code_name,address) as ITEMNAME,address_name,mobile,pct_code,address')->get();
        return $res?$res:[];
    }

    public static function selfGetAddressConfig()
    {
        $res = DB::table((new UserAddress())->table)->where(['user_id'=>-1,'status'=>1])->selectRaw('address_id as ITEMNO,concat(pct_code_name,address) as ITEMNAME,address_name,mobile,pct_code,address')->get();
        return $res?$res:[];
    }

    public static function mineAddressConfig($userId)
    {
        $res = DB::table((new UserAddress())->table)->where(['user_id'=>$userId,'status'=>1])->selectRaw('address_id as ITEMNO,concat(pct_code_name,address) as ITEMNAME')->orderBy('is_default','desc')->get();
        return $res?$res:[];
    }

    public static function mineAddressList($userId)
    {
        $res = DB::table((new UserAddress())->table)->where(['user_id'=>$userId,'status'=>1])->orderBy('is_default','desc')->get();
        return $res?$res:[];
    }


    public static function selfGetAddressList()
    {
        $res = DB::table((new UserAddress())->table)->where(['user_id'=>-1,'status'=>1])->orderBy('is_default','desc')->get();
        return $res?$res:[];
    }
}