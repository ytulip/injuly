<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\RandomPool;
use App\Model\SmsManager;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class IndexController extends Controller
{
    //首页
    public function getIndex()
    {
        $product = Product::find(1);
        $list = DB::table('essays')->orderBy('sort','desc')->get();
        return view('index')->with('product',$product)->with('list',$list);
    }

    public function getTest()
    {
//        $directUser = User::find(53);
//        $directUser->increment('charge_frozen',111);
//        $directUser->save();
        //DB::table('users')->where('id',53)->increment('charge_frozen',111);
        dd(4);
//        set_time_limit(3600);
//        RandomPool::make(1000);
//        exit;
    }

    public function getCalendarJson()
    {
//        [{"name":2018,"value":180000,"child":[{"name":"01","value":180100}]}]
        $startMonth = '2017-11';

        $currentMonth = $startMonth;
        $data = [];

        while(date('Y',strtotime($currentMonth)) <= date('Y'))
        {
            $currentTime = strtotime($currentMonth);
            $Y  = date('Y',$currentTime);
            $y = date('y',$currentTime);
            $data[] = (object)["name"=>$Y,"id"=>$y . '0000',"child"=>[]];
            $currentMonth = date('Y-m',strtotime('+1 year',$currentTime));
        }


        foreach($data as $key=>$val){
            if($data[$key]->name == date('Y')){
                for($i = 1;$i <= date('m');$i++)
                {
                    if($i < 10) {
                        $date = '0' . $i;
                    }else{
                        $date = $i;
                    }
                    $data[$key]->child[] = (object)["name"=>$date,"id"=>$data[$key]->id + $date * 100];
                }
            }else{
                for($i = 1;$i < 13; $i++)
                {
                    if($i < 10) {
                        $date = '0' . $i;
                    }else{
                        $date = $i;
                    }
                    $data[$key]->child[] = (object)["name"=>$date,"id"=>$data[$key]->id + $date * 100];
                }
            }

        }

        return Response::json($data);
    }


    public function getSms()
    {
        $query = SmsManager::orderBy('id','desc');

        if(Request::input('mobile'))
        {
            $query->where('mobile',Request::input('mobile'));
        }

        $res = $query->limit(10)->get();

        foreach($res as $key=>$val)
        {
            echo $val->mobile  . ':' . $val->content . '<br/>';
        }

        exit;
    }

    /**
     * 充点钱
     */
    public function getSetCharge()
    {
        $user = User::where('phone',Request::input('mobile'))->first();
        if(!$user) {
            dd('用户不存在');
        }

        $charge = intval(Request::input('charge'));

        $user->charge = $charge;

        $user->save();
        dd('修改成功');
    }

}