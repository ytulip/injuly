<?php

namespace  App\Http\Middleware;

use App\Util\AdminAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminCheck
{
    public function handle($request, \Closure $next)
    {

        if(in_array(Request::segment(3),['preview']))
        {
            return $next($request);
        }


        if(!AdminAuth::check()){
            if ($request->ajax()) {
                return json_encode(['status'=>0,'desc'=>'请先登录']);
            } else {
                return redirect()->guest('/passport/admin-login');
            }
        }

        return $next($request);
    }
}
