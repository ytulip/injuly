<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthGetGood extends Model
{
    protected $table = 'month_get_good';

    public function getTypeText()
    {
        $text = '';
        switch ($this->get_type)
        {
            case 1:
                $text = '报单';
                break;
            case 2:
                $text = '复购';
                break;
            case 3:
                $text = '提货';
                break;
            default:
                break;
        }
        return $text;
    }


    public function deliverTypeText()
    {
        $text = '';
        switch ($this->deliver_type)
        {
            case 1:
                $text = '自提';
                break;
            case 2:
                $text = '邮寄';
                break;
            default:
                break;
        }
        return $text;

    }

    public function statusText()
    {
        $text = '';
        switch ($this->get_status)
        {
            case 1:
                $text = '待发货';
                break;
            case 2:
                $text = '已发货';
                break;
            case 3:
                $text = '已自提';
                break;
            default:
                break;
        }
        return $text;
    }


}