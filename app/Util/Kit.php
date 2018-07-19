<?php

namespace  App\Util;

class Kit
{
    public static function phoneHide($phone)
    {
        return substr_replace($phone,'****',3,4);
    }

    public static function phoneIdCard($idCard)
    {
        return substr_replace($idCard,'**************',2,14);
    }

    public static function columnMonthFilter($query,$month,$column = 'created_at')
    {
        $query->whereRaw('DATE_FORMAT('.$column.',"%Y-%m") = "' . $month . '"');
    }

    public static function issetThenReturn($obj,$attr)
    {
        return isset($obj->$attr)?$obj->$attr:'';
    }

    public static function priceFormat($price)
    {
        return number_format($price,2);
    }

    public static function dateFormat($dateStr)
    {
        return date('Y.m.d H.i',strtotime($dateStr));
    }

    public static function dateFormat2($dateStr)
    {
        return date('m月d日 H:i',strtotime($dateStr));
    }

    /**
     * @desc 相等查询
     * @param $query
     * @param $arr
     */
    static public function equalQuery($query,$arr){
        $arr = array_filter($arr,function($val){
            if($val === '0' || $val === 0){
                return true;
            }
            return $val?true:false;
        });
        if($arr){
            $query->where($arr);
        }
    }

    public static function compareBelowZeroQuery($query,$arr)
    {
        $arr = array_filter($arr,function($val){
            if($val === '0' || $val === 0){
                return true;
            }
            return $val?true:false;
        });

        foreach ($arr as $key=>$item)
        {
            if( $item )
            {
                $query->where($key,'>',0);
            }else
            {
                $query->where($key,'=',0);
            }
        }
    }

    public static function isWechat()
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ){  //微信
            return true;
        }

        return false;
    }

    static public function signType($type)
    {
        if( $type = 0)
        {
            $str = '随食';
        }elseif( $type == 1)
        {
            $str = '换食';
        } else {
            $str = '代食';
        }

        return $str;
    }
}
