<?php

namespace App\Model;

class Deliver
{
    const SELF_GET = 1;
    const DELIVER_HOME = 2;

    static public function deliverTypeConfig()
    {
        return array((object)(array('ITEMNO'=>1,'ITEMNAME'=>'自取')),(object)(array('ITEMNO'=>2,'ITEMNAME'=>'送货上门')));
    }

    public static function deliverTypeText($type)
    {
        $res = '';
        switch ($type)
        {
            case Deliver::SELF_GET:
                $res = '自取';
                break;
            case Deliver::DELIVER_HOME:
                $res = '送货上门';
                break;
        }
        return $res;
    }
}