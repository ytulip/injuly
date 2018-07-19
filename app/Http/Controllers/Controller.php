<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    private $currentDateTime = null;

    /**
     * @desc model验证，如果如果验证未通过返回一个ResourceError对象
     * @param $attributes
     * @param $rules
     * @return mixed
     */
    public function validate($attributes,$rules,$customAttributes=[],$message=[]){
        $validator = Validator::make($attributes,$rules,$message,$customAttributes);
        if($validator->passes()){
            return true;
        }else{
            /**
             * 获取第一条错误信息
             */
            $message = $validator->messages();
            echo json_encode(["status"=>0,"desc"=>$message->first()]);
            exit;
        }
    }

    protected function getCurrentDateTime()
    {
        if ( $this->currentDateTime )
        {
            return $this->currentDateTime;
        }

        $this->currentDateTime = date('Y-m-d H:i:s');
        return $this->currentDateTime;
    }

    public function jsonReturn($flag,$val = "")
    {
        if($flag)
        {
            return json_encode(['status'=>1,'data'=>$val],JSON_UNESCAPED_UNICODE);
        }
        return json_encode(['status'=>0,'desc'=>$val],JSON_UNESCAPED_UNICODE);
    }
}
