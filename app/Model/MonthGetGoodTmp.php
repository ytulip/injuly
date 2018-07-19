<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MonthGetGoodTmp extends Model
{
    protected $table = 'month_get_good_tmp';


    public function whichGetGood()
    {
        if($this->get_type == 1)
        {

        }else if($this->get_type == 2)
        {
            return 're_get_good';
        } else if($this->get_type == 3)
        {
            return 'activity_get_good';
        }

        return '';
    }
}