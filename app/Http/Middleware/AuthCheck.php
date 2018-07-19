<?php

namespace  App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuthCheck
{
    public function handle($request, \Closure $next)
    {
//        dd(Request::segment(2));
        if(!Auth::check() && !in_array(Request::segment(2),['index','good-detail','essay','good-detail-xcx'])){
            if ($request->ajax()) {
                return json_encode(['status'=>0,'desc'=>'请先登录']);
            } else {
                return redirect()->guest('/passport/login');
            }
        }

        if( !Auth::check() ) {
            return $next($request);
        }

        //判断是否补全了资料
        if( !Auth::user()->id_card && !in_array(Request::segment(2),['add-mod-address','fill-user-info'])) {
            return redirect()->guest('/passport/fill-user-info');
        }

        return $next($request);
    }
}
