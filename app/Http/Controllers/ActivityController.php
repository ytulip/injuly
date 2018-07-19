<?php

namespace App\Http\Controllers;

use App\Console\Commands\SignAlert;
use App\Facades\SmallWechatCallbackFacade;
use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\Deliver;
use App\Model\Essay;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\MonthGetGood;
use App\Model\MonthGetGoodTmp;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\SignRecord;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\AdminAuth;
use App\Util\Curl;
use App\Util\Kit;
use App\Util\SmsTemplate;
use App\Util\DealString;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /*验证*/
    public function anyTakePartIn()
    {

        Logger::info(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'no agent','bind_user');
        Logger::info(Request::all(),'bind_user');

        if ( Cache::get('register_sms_code' . Request::input('phone') ) !=  (Request::input('phone') . '_' . Request::input('register_sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        $phone = Request::input('phone');
        $user = User::where('phone',$phone)->first();
        $openidUser = User::where('openid',Request::input('openid'))->first();
        if( $user instanceof  User)
        {
            //用户已经存在了，查看是否已经参加过活动了
            if( $user->hasTakePartInActivity() )
            {
                //绑定openid哟
                if ( !$user->openid )
                {
                    if(!($openidUser instanceof  User))
                    {
                        //占用openid了哟
                        $user->openid = Request::input('openid');
                        $user->save();

                        return json_encode(['status'=>0,'desc'=>'您已参加了活动，请重新进入小程序开始打卡','flag'=>1],JSON_UNESCAPED_UNICODE);
                    }
                } else
                {
                    return $this->jsonReturn(0,'该用户已经绑定其他小程序');
                }
            }

            //判断openid是否存在
            if( $user->openid) {
                //已将绑定了opneid
                if( $user->openid != Request::input('openid'))
                {
                    return $this->jsonReturn(0,'该手机号已经在其它小程序上使用');
                }
            } else
            {
                $user->openid = Request::input('openid');
            }


            if($openidUser instanceof  User)
            {
                if ( $user->id != $openidUser->id )
                {
                    return $this->jsonReturn(0,'该小程序已被其它手机号绑定');
                }
            }

        } else
        {
            //查看openid是否被使用
            if($openidUser instanceof  User)
            {
                return $this->jsonReturn(0,'该小程序已被其它手机号绑定');
            }

            $user = new User();
            $user->phone = $phone;
            $user->openid = Request::input('openid');
            $user->vip_level = User::LEVEL_ACTIVITY;
            $user->origin_vip_level = User::LEVEL_ACTIVITY;
        }

        $user->real_name = Request::input('real_name');
        $user->id_card = Request::input('id_card');
        $user->save();

        //下活动单
//        $this->makeActivityOrder($user->id);
        return $this->jsonReturn(1);
    }

    /**
     * 用户登录
     */
    public function anyLoginIn()
    {
        Logger::info(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'no agent','bind_user_login');
        Logger::info(Request::all(),'bind_user_login');

        if ( Cache::get('register_sms_code' . Request::input('phone') ) !=  (Request::input('phone') . '_' . Request::input('register_sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        if( !Request::input('openid'))
        {
            return $this->jsonReturn(0,'参数错误');
        }

        $phone = Request::input('phone');
        $user = User::where('phone',$phone)->first();
        $openidUser = User::where('openid',Request::input('openid'))->first();


        //case 1
        if( ($user instanceof  User) && ($openidUser instanceof User))
        {
            //用户存在
            if( $user->id == $openidUser->id)
            {
                return $this->jsonReturn(1,['user'=>$user]);
            } else
            {
                return $this->jsonReturn(0,'手机号已被使用');
            }
        }


        //case 2
        if( ($user instanceof  User) && !($openidUser instanceof User))
        {
            if( $user->openid )
            {
                return $this->jsonReturn(0,'该用户已绑定其他小程序');
            } else
            {
                $user->openid = Request::input('openid');
                $user->save();
                return $this->jsonReturn(1,['user'=>$user]);
            }
        }


        //case 3
        if( !($user instanceof  User) && ($openidUser instanceof User))
        {
            return $this->jsonReturn(0,'用户信息不匹配');
        }

        //case 4
        if( !($user instanceof  User) && !($openidUser instanceof User))
        {
//            return $this->jsonReturn(0,'用户信息不匹配');
            $user = new User();
            $user->phone = $phone;
            $user->openid = Request::input('openid');
            $user->vip_level = User::LEVEL_ACTIVITY;
            $user->origin_vip_level = User::LEVEL_ACTIVITY;
            $user->real_name = Request::input('real_name');
            $user->id_card = Request::input('id_card');
            $user->save();
            return $this->jsonReturn(1,['user'=>$user]);
        }
    }

    private function makeActivityOrder($userId)
    {
        $order = new Order();
        $order->buy_type = Order::BUY_TYPE_ACTIVITY;

    }

    /**
     * 上传图片
     */
    public function anyUploadImage()
    {
        Logger::info(Request::all(),'upload_image');
        $file = \Illuminate\Support\Facades\Request::file('file');
        $count = count($file);
        if( $count != 1)
        {
            return json_encode(["status"=>0,"desc"=>"文件个数异常"],JSON_UNESCAPED_UNICODE);
        }


        $imageExtension = $file->getClientOriginalExtension();
        if(!in_array($imageExtension,['jpg','png','gif','jpeg'])){
            return json_encode(['status'=>0,'desc'=>'文件格式异常'],JSON_UNESCAPED_UNICODE);
        }

        $imageSaveName = 'wxs' . bin2hex(base64_encode( time() )) . '.' . $imageExtension;
        if ( $file->move('imgsys',$imageSaveName) )
        {
            $res = ['/imgsys/' . $imageSaveName];
            return json_encode(['status'=>1,'data'=>$res]);
        } else
        {
            return json_encode(['status'=>0,'desc'=>"上传异常"],JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 支付活动
     */
    public function anyActivityPay()
    {

        if( env('PAY_CLOSE') )
        {
            return $this->jsonReturn(0,config('customer.pay_close_text'));
        }

        //根据openid找用户
        $openid = Request::input('openid');
        $user = User::where('openid',$openid)->first();

        if( !$openid || !($user instanceof  User))
        {
            return $this->jsonReturn(0,'无效用户');
        }

        require_once base_path() . "/plugin/swechatpay/lib/WxPay.Api.php";
        require_once base_path() . "/plugin/swechatpay/example/WxPay.JsApiPay.php";

        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('pay_status',0)->first();
        if( !$order instanceof  Order )
        {
            //return $this->jsonReturn(0,'订单无效或不存在');
            //没有订单则创建订单哟
            $order = new Order();
            $order->user_id = $user->id;
            $order->buy_type = Order::BUY_TYPE_ACTIVITY;
            $order->need_pay = Product::find(2)->price;
            $order->quantity = 1;
            $order->save();
        }

        if ( $order->pay_status )
        {
            return $this->jsonReturn(0,'请勿重复支付');
        }

        //上级会员信息，应该上级必须是高级会员才可以哟
        $phone = Request::input('phone');
        if( !$phone )
        {
            return $this->jsonReturn(0,'上级会员不能为空');
        }

        $immediateUser = User::where('phone',$phone)->first();
        if( !($immediateUser instanceof  User) )
        {
            return $this->jsonReturn(0,'无效的上级会员');
        }

        if ( $immediateUser->vip_level != User::LEVEL_MASTER )
        {
            return $this->jsonReturn(0,'无效的身份等级');
        }


        //保存最新的订单消息
        $order->immediate_user_id = $immediateUser->id;
        $order->deliver_type = Request::input('deliver_type');
        $order->address = Request::input('address');
        $order->address_name = Request::input('address_name');
        $order->address_phone = Request::input('address_phone');
        $order->save();


        //创建支付订单
        $cashStream = new CashStream();
        $cashStream->refer_id = $order->id;
        $cashStream->cash_type = CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT;
        $cashStream->user_id = Auth::id();
        $cashStream->price = $order->need_pay;
        $cashStream->save();

        //付款金额，必填
        if( env('PAY_TEST') || in_array($user->id,explode(",",env('TEST_PAY_USER')))) {
            $total_amount = 1;
        } else {
            $total_amount = $cashStream->price * 100;
        }

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("辣木膳素食全餐");
        $input->SetAttach("辣木膳素食全餐");
        $input->SetOut_trade_no($cashStream->id);//这个订单号是特殊的
        $input->SetTotal_fee($total_amount); //钱是以分计的
        $input->SetTime_start(date("YmdHis"));
        $input->SetGoods_tag("辣木膳素食全餐");
        $input->SetNotify_url(env('WECHAT_NOTIFY_URL'));
        $input->SetTrade_type("JSAPI");

        $input->SetOpenid($openid);
        $payOrder = \WxPayApi::unifiedOrder($input);
        $tools = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($payOrder);
        return $this->jsonReturn(1,$jsApiParameters);
    }


    public function anyDoSign()
    {
        $user = $this->judgeUserByOpenid();

        //判断用户是否可以打卡
        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();

        if( !($order instanceof  Order) )
        {
            return $this->jsonReturn(0,'请先参加打卡活动');
        }

//        if( $order->confirm_date == "0000-00-00" )
//        {
//            return $this->jsonReturn(0,'请先确认收货');
//        }

        $signRecord = SignRecord::where('user_id',$user->id)->where('date',date('Y-m-d'))->first();

        if ( !($signRecord instanceof  SignRecord) )
        {
//            if ( $order->isPassFirstSign() )
//            {
//                return $this->jsonReturn(0,'超过了首次打卡时间');
//            }

            //创建签到记录
            $activityDays = Product::find(2)->activity_days;
            for( $i = 0; $i < $activityDays; $i++ )
            {
                $signRecord = new SignRecord();
                $signRecord->order_id = $order->id;
                $signRecord->user_id = $order->user_id;
                $signRecord->date = date('y-m-d',strtotime("+ $i days"));
                $signRecord->save();
            }

            $order->sign_status = 2;
            $order->save();

        } else
        {
            if ( $signRecord->sign_status )
            {
                return $this->jsonReturn(0,'请勿重复打卡');
            }
        }


        $signRecord = SignRecord::where('user_id',$user->id)->where('date',date('Y-m-d'))->first();

        $signProv = (object)[
            "signTypeIndex"=>Request::input('signTypeIndex'),
            "countIndex"=>Request::input('countIndex'),
            "imgPath1Save"=>Request::input('imgPath1Save'),
            "imgPath2Save"=>Request::input('imgPath2Save'),
            "imgPath3Save"=>Request::input('imgPath3Save'),
            "water"=>Request::input('water'),
            "weight"=>Request::input('weight'),
            "baseInfo"=>Request::input('baseInfo'),
            "wcCount"=>Request::input('wcCount'),
        ];

        $signRecord->sign_prov = json_encode($signProv,JSON_UNESCAPED_UNICODE);
        $signRecord->sign_status = 1;
        $signRecord->quantity = Request::input('countIndex') + 1;
        $signRecord->save();


        //计算已经打卡的包数
        $quantitySum = SignRecord::where('user_id',$user->id)->sum('quantity');
        $order->sign_quantity = $quantitySum;
        if( $quantitySum >= 100 )
        {
            $order->sign_status = 3;
        }
        $order->save();

        return $this->jsonReturn(1);

    }

    public function anyPayInfo()
    {
        $openid = Request::input('openid');
        $user = User::where('openid',$openid)->first();

        if( !$openid || !($user instanceof  User))
        {
            return $this->jsonReturn(0,'无效用户');
        }


        $product = Product::find(2);
        $data = [];
        $data['price'] = $product->price;
        $data['real_name'] = $user->real_name;
        $data['phone'] = $user->phone;
//        $data['addresses'] = UserAddress::goodSelfGetAddressConfig(2);
        $addresses = UserAddress::goodSelfGetAddressConfig(2);
        $data['addressList'] = $addresses;
        $data['addresses'] = [];

//        $data['openid'] =
        foreach ($addresses as $key=>$val)
        {
            $data['addresses'][] = $val->address_name . ' ' . $val->mobile . ' ' . $val->ITEMNAME;
        }


        //推荐人信息
        $data['recommondPhone'] = "";
        if( Request::input('recommond_user') )
        {
            $recommondUser = User::find(Request::input('recommond_user'));
            if( $recommondUser instanceof  User )
            {
                if( $recommondUser->phone && $recommondUser->vip_level )
                {
                    $data['recommondPhone'] = $recommondUser->phone;
                }
            }
        }

        return $this->jsonReturn(1,$data);
    }

    /**
     * 确认收货
     */
    public function anyConfirmDeliver()
    {
        $openid = Request::input('openid');
        $user = User::where('openid',$openid)->first();

        if( !$openid || !($user instanceof  User))
        {
            return $this->jsonReturn(0,'无效用户');
        }

        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();

        if ( !($order instanceof  Order) )
        {
            return $this->jsonReturn(0,'订单不存在');
        }

        if(!in_array($order->order_status,[Order::ORDER_STATUS_DELIVERED,Order::ORDER_STATUS_WAIT_SELF_GET,Order::ORDER_STATUS_SELF_GOT]))
        {
            return $this->jsonReturn(0,'状态异常');
        }

        $order->order_status = Order::ORDER_STATUS_CONSUMER_CONFIRM;
        $order->confirm_date = date('Y-m-d H:i:s');
        $order->sign_status = 1;
        $order->save();
        return $this->jsonReturn(1);
    }

    /**
     * 打卡页面信息渲染
     */
    public function anySignInfo()
    {
        $commonInfo = $this->commonInfo();



        //是否需要打卡
        $commonInfo['needSignToday'] = 0;
        if( isset($commonInfo['order']) )
        {
            $order = $commonInfo['order'];
            $commonInfo['needSignToday'] = $order->needSignToday();
        }
        Logger::info('请求打卡判断返回' . json_encode($commonInfo),'sign_info');

        return $this->jsonReturn(1,$commonInfo);

    }

    private function commonInfo()
    {

        if(!env('OPENID_TEST')) {
            $requestUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='.\SmallWechatCallback::getAppId().'&secret='.\SmallWechatCallback::getAppSecret().'&js_code=' . Request::input('code'). '&grant_type=authorization_code';
            Logger::info('code请求:' . $requestUrl,'xcx');
            $response = file_get_contents($requestUrl);
            Logger::info($response,'xcx');

            $response = json_decode($response);
            if( !isset($response->openid) )
            {
                echo $this->jsonReturn(0);
                exit;
            }
        } else {
            $response = (Object)['openid'=>env('OPENID_TEST')];
        }

        $data = [];
        $data['openid'] = $response->openid;
        $data['user'] = \App\Model\User::where('openid',$response->openid)->first();

        if( $data['user'] )
        {
            $data['order'] = Order::where('user_id',$data['user']->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();
        }
        return $data;
    }


    private function commonInfoNew()
    {

        $response = (Object)['openid'=>Request::input('openid')];

        $data = [];
        $data['openid'] = $response->openid;
        $data['user'] = \App\Model\User::where('openid',$response->openid)->whereRaw('openid != ""')->first();

        if( $data['user'] )
        {
            $data['order'] = Order::where('user_id',$data['user']->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();
        }
        return $data;
    }

    private function judgeUserByOpenid()
    {
        //根据openid找用户
        $openid = Request::input('openid');
        $user = User::where('openid',$openid)->first();

        if( !$openid || !($user instanceof  User))
        {
            return $this->jsonReturn(0,'无效用户');
        }

        return $user;
    }

    /**
     * 保存信息
     */
    public function anySaveHealth()
    {
        $user = $this->judgeUserByOpenid();
        $step = Request::input('step');

        if( !$user->health_info )
        {
            //初始化json数据模型
            $healthInfo = (Object)[];
            $healthInfo->first = (Object)['tall'=>'','blood_press'=>'','weight'=>'','waistline'=>'','blood_glucose'=>'','cover_image'=>'','oppo_image'=>''];
            $healthInfo->second = [];
            $healthInfo->third = [];
        } else
        {
            $healthInfo = json_decode($user->health_info);
        }


        if( $step == 1)
        {
            $healthInfo->first->tall = Request::input('tall');
            $healthInfo->first->blood_press = Request::input('blood_press');
            $healthInfo->first->weight = Request::input('weight');
            $healthInfo->first->waistline = Request::input('waistline');
            $healthInfo->first->blood_glucose = Request::input('blood_glucose');
            $healthInfo->first->cover_image = Request::input('cover_image');
            $healthInfo->first->oppo_image = Request::input('oppo_image');

        }elseif( $step == 2)
        {
            $healthInfo->second = Request::input('data');

        }elseif( $step == 3)
        {
            $user->health_finished_at = date('Y-m-d H:i:s ');
            $healthInfo->third = Request::input('data');
        }




        $user->health_status = ($step == 3)?1:$user->health_status;
        $user->health_info = json_encode($healthInfo);
        $user->save();

        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();

        if( $order instanceof  Order )
        {
            //兼容逻辑
            $order->sign_status = $order->sign_status?$order->sign_status:1;
            $order->save();
        }

        return $this->jsonReturn(1);
    }

    public function getHealthInfo()
    {
        $commonInfo = $this->commonInfo();
        $commonInfo['health_tags'] = $commonInfo['user']->getHealthTagArray();

        return $this->jsonReturn(1,$commonInfo);
    }

    public function getQRInfo()
    {
        $commonInfo = $this->commonInfo();
        $user = $commonInfo['user'];
        $commonInfo['image'] = '';
        if( $user  instanceof  User)
        {

            $filePath = "xcx/{$user->id}.jpg";
            if( file_exists(public_path($filePath)))
            {
                $commonInfo['image'] = "https://lamushan.com/" . $filePath;
            } else
            {
                $token = SmallWechatCallbackFacade::getAccessToken();
                $requestURI = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $token . '';
                $data = ['scene'=>$user->id,'page'=>'pages/health/route'];
                $response = Curl::curlPostRaw($requestURI,json_encode($data),false,'origin');
//        var_dump($response);
                file_put_contents(public_path($filePath),$response);
                $commonInfo['image'] = "https://lamushan.com/" . $filePath;
            }

        }

//        $commonInfo['image'] = "https://lamushan.com/lmsxcx.jpg";

        return $this->jsonReturn(1,$commonInfo);

    }

    public function getUserInfo()
    {
        $commonInfo = $this->commonInfo();
        $user = $commonInfo['user'];
        $total = SignRecord::where('user_id',$user->id)->count();
        $sign = SignRecord::where('user_id',$user->id)->where('sign_status',1)->count();
        $commonInfo['signScore'] = "$sign" . '/' ." $total";
        return $this->jsonReturn(1,$commonInfo);
    }

    public function getSignList()
    {
        if( Request::input('id') )
        {
            $commInfo['user'] = User::find(Request::input('id'));
        } else {
            $commInfo = $this->commonInfo();
        }
        $user = $commInfo['user'];
        $signRecord = SignRecord::where('user_id',$user->id)->whereRaw('date <= "'.date('Y-m-d').'"')->get();
        $commInfo['signRecord'] = $signRecord;
        $commInfo['needSignToday'] = 0;

        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();


        $commInfo['order'] = null;
        if( $order instanceof  Order)
        {
            $commInfo['order'] = $order;
        }


        $currentDayRecord = SignRecord::where('user_id',$user->id)->whereRaw('date = "'.date('Y-m-d').'"')->first();

        if($currentDayRecord instanceof  SignRecord)
        {
            if( !$currentDayRecord->sign_status)
            {
                $commInfo['needSignToday'] = 1;
            }
        }

        return $this->jsonReturn(1,$commInfo);
    }


    public function getPoolInfo()
    {
        $commonInfo = $this->commonInfo();
//        $commonInfo['image'] = "https://lamushan.com/lmsxcx.jpg";
        $product = Product::find(2);
        $commonInfo['image'] = "https://lamushan.com" . $product->cover_image;

        return $this->jsonReturn(1,$commonInfo);
    }

    //TODO:后期加上权限控制，先放开吧
    public function getSignDetail()
    {
        $signRecord = SignRecord::find(Request::get('id'));
        $provObject = json_decode($signRecord->sign_prov);

        if( isset($provObject->countIndex))
        {
            $provObject->countIndex += 1;
            $signRecord->sign_prov = json_encode($provObject,JSON_UNESCAPED_UNICODE);
        }

        return $this->jsonReturn(1,$signRecord);
    }

//    public function get
    public function getUserInfoNew()
    {
        $commonInfo = $this->commonInfoNew();
//        $user = $commonInfo['user'];
//        $total = SignRecord::where('user_id',$user->id)->count();
//        $sign = SignRecord::where('user_id',$user->id)->where('sign_status',1)->count();
//        $commonInfo['signScore'] = "$sign" . '/' ." $total";
        return $this->jsonReturn(1,$commonInfo);
    }


    public function anySubUserList()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        if ( !($user instanceof  User) )
        {
            return $this->jsonReturn(1,$commonInfo);
        }

        $list = Order::where('immediate_user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('pay_status',1)->leftJoin('users','users.id','=','orders.user_id')->orderBy('orders.id','desc')->selectRaw('*,DATE_FORMAT(orders.created_at,"%Y-%m-%d") as order_created')->get();

        if(!$list)
        {
            $list = [];
        }

        $commonInfo['list'] = $list;

        return $this->jsonReturn(1,$commonInfo);
    }



    public function anyReportPay()
    {
        if( env('PAY_CLOSE') )
        {
            return $this->jsonReturn(0,config('customer.pay_close_text'));
        }

        //根据openid找用户
        $openid = Request::input('openid');
        $user = User::where('openid',$openid)->first();

        Logger::info(json_encode(Request::all()),'pay_request');

        if( !$openid || !($user instanceof  User))
        {
            return $this->jsonReturn(0,'无效用户');
        }

        require_once base_path() . "/plugin/swechatpay/lib/WxPay.Api.php";
        require_once base_path() . "/plugin/swechatpay/example/WxPay.JsApiPay.php";


        $productAttr = ProductAttr::find(2);


        //生产订单
        $order = new Order();
        $order->user_id = $user->id;
        $order->product_id = $productAttr->product_id;
        $order->product_attr_id = $productAttr->id;

        if( Request::input('buy_type') == Order::BUY_TYPE_REREPORT)
        {

            $quantity = Request::input('quantity');
            if( $quantity < 3)
            {
                return $this->jsonReturn(0,'复购至少3箱起');
            }

            $order->buy_type = Order::BUY_TYPE_REREPORT;
            $order->need_pay =$productAttr->rebuy_price * $quantity;
            $order->quantity = $quantity;
            $order->frozen_quantity = $quantity;
            $order->single_up_pay = $productAttr->rebuy_up_price;
            $order->single_super_pay = $productAttr->rebuy_super_price;
        } else
        {
            if( $user->vip_level == User::LEVEL_ACTIVITY )
            {
                $order->buy_type = Order::BUY_TYPE_NEW_REPORT;
            } else
            {
                $order->buy_type == Order::BUY_TYPE_REPORT;
            }
            $order->need_pay = $productAttr->price;
            $order->quantity = $productAttr->quantity / 10;
            $order->frozen_quantity =$productAttr->quantity / 10;
            $order->single_direct_pay = $productAttr->single_direct_price;
            $order->single_up_pay = $productAttr->single_up_price;
            $order->single_super_pay = $productAttr->single_super_price;
        }



        if ( $order->pay_status )
        {
            return $this->jsonReturn(0,'请勿重复支付');
        }
        $phone = Request::input('phone');

        //上级会员信息，应该上级必须是高级会员才可以哟
        if( Request::input('buy_type') == Order::BUY_TYPE_REREPORT)
        {
            //复购不需要上级会员
            if ( $user->vip_level != User::LEVEL_MASTER )
            {
                return $this->jsonReturn(0,'没有复购权限');
            }
        } else
        {
            if( !$phone )
            {
                return $this->jsonReturn(0,'上级会员不能为空');
            }
        }

        $immediateUser = User::where('phone',$phone)->first();
        if( !($immediateUser instanceof  User) )
        {
            return $this->jsonReturn(0,'无效的上级会员');
        }

        if ( $immediateUser->vip_level != User::LEVEL_MASTER )
        {
            return $this->jsonReturn(0,'无效的身份等级');
        }


        //保存最新的订单消息
        $order->immediate_user_id = $immediateUser->id;
        $order->save();


        //创建支付订单
        $cashStream = new CashStream();
        $cashStream->refer_id = $order->id;
        $cashStream->cash_type = CashStream::CASH_TYPE_WECHAT_BUY_PRODUCT;
        $cashStream->user_id = $user->id;
        $cashStream->price = $order->need_pay;
        $cashStream->save();

        //付款金额，必填
        if( env('REPORT_PAY_TEST') || in_array($user->id,explode(",",env('TEST_PAY_USER')))) {
            $total_amount = 1;
        } else {
            $total_amount = $cashStream->price * 100;
        }

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("辣木膳素食全餐");
        $input->SetAttach("辣木膳素食全餐");
        $input->SetOut_trade_no($cashStream->id);//这个订单号是特殊的
        $input->SetTotal_fee($total_amount); //钱是以分计的
        $input->SetTime_start(date("YmdHis"));
        $input->SetGoods_tag("辣木膳素食全餐");
        $input->SetNotify_url(env('WECHAT_NOTIFY_URL'));
        $input->SetTrade_type("JSAPI");

        $input->SetOpenid($openid);
        $payOrder = \WxPayApi::unifiedOrder($input);
        $tools = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($payOrder);
        return $this->jsonReturn(1,$jsApiParameters);
    }


    public function anyGetGoodInfo()
    {

        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        $product = Product::find(2);
        $commonInfo['price'] = $product->price;
        $commonInfo['real_name'] = $user->real_name;
        $commonInfo['phone'] = $user->phone;
//        $data['addresses'] = UserAddress::goodSelfGetAddressConfig(2);
        $addresses = UserAddress::selfGetAddressConfig();
        $commonInfo['addressList'] = $addresses;
        $commonInfo['addresses'] = [];


        //
        $count = MonthGetGood::where('user_id',$commonInfo['user']->id)->whereRaw('date_format(get_date,"%Y-%m") = "' . date('Y-m') . '"')->where('get_type',1)->sum('count');

        $commonInfo['total'] = $user->get_good;
        $commonInfo['currentMonth'] = 2 - $count;

//        $data['openid'] =
        foreach ($addresses as $key=>$val)
        {
            $commonInfo['addresses'][] = $val->address_name . ' ' . $val->mobile . ' ' . $val->ITEMNAME;
        }


        return $this->jsonReturn(1,$commonInfo);
    }


    public function anyTryGetGood()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        if ( !($user instanceof User) )
        {
            return $this->jsonReturn(0,'用户不存在');
        }

        $quantityCount = Request::input('quantityCount');
        if ( $quantityCount > $user->get_good)
        {
            return $this->jsonReturn(0,'超过可提货箱数');
        }

        $count = MonthGetGood::where('user_id',$commonInfo['user']->id)->whereRaw('date_format(get_date,"%Y-%m") = "' . date('Y-m') . '"')->where('get_type',1)->sum('count');

        if( env('OPEN_QUANTITY_COUNT_LIMIT',true) && ($quantityCount + $count > 2) )
        {
            return  $this->jsonReturn(0,'超过本月可提货最大箱数');
        }


        //这里如果是自提的话，很奇怪的,不会立即生成记录，只会生成一条临时的记录
        if( Request::input('self_get_need_scan') && (Request::input('deliver_type') == 1) )
        {
            $monthGetGood = new MonthGetGoodTmp();
            $monthGetGood->user_id = $user->id;
            $monthGetGood->count = $quantityCount;
            $monthGetGood->get_date = date('Y-m-d');
            $monthGetGood->deliver_type = Request::input('deliver_type');
            $monthGetGood->get_status = ($monthGetGood->deliver_type == 1)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $monthGetGood->address = Request::input('address');
            $monthGetGood->address_name = Request::input('address_name');
            $monthGetGood->address_phone = Request::input('address_phone');
            $monthGetGood->save();

            return $this->jsonReturn(1,$monthGetGood->id);
        }

        $monthGetGood = new MonthGetGood();
        $monthGetGood->user_id = $user->id;
        $monthGetGood->count = $quantityCount;
        $monthGetGood->get_date = date('Y-m-d');
        $monthGetGood->deliver_type = Request::input('deliver_type');
        $monthGetGood->get_status = ($monthGetGood->deliver_type == 1)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
        $monthGetGood->address = Request::input('address');
        $monthGetGood->address_name = Request::input('address_name');
        $monthGetGood->address_phone = Request::input('address_phone');
        $monthGetGood->save();

        //更改记录
        $user->get_good = $user->get_good - $quantityCount;
        $user->save();

        //生成辅导记录
        $mineUpUser = User::find($user->parent_id);


        if( $mineUpUser instanceof  User)
        {
            $upUser = User::find($mineUpUser->parent_id);
            $superUser = User::find($mineUpUser->indirect_id);
        } else
        {
            $upUser = null;
            $superUser = null;
        }

        $productAttr = ProductAttr::find(2);

        if( $upUser )
        {
            $cashStream = new CashStream();
            $cashStream->price = $productAttr->single_up_price * $quantityCount;
            $cashStream->compare_price = $upUser->charge;
            $cashStream->user_id = $upUser->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_UP;
            $cashStream->sub_cash_type = 1;
            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->refer_id = $monthGetGood->id;
            $cashStream->refer_user_id = $monthGetGood->user_id;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->save();

            $upUser->increment('charge',$cashStream->price);
        }

        if( $superUser )
        {
            $cashStream = new CashStream();
            $cashStream->price = $productAttr->single_super_price * $quantityCount;
            $cashStream->compare_price = $superUser->charge;
            $cashStream->user_id = $superUser->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_SUPER;
            $cashStream->sub_cash_type = 2;
            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->refer_id = $monthGetGood->id;
            $cashStream->refer_user_id = $monthGetGood->user_id;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->save();

            $superUser->increment('charge',$cashStream->price);
        }

        return $this->jsonReturn(1);
    }


    /**
     * 复购提货
     */
    public function anyTryGetReGood()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        if ( !($user instanceof User) )
        {
            return $this->jsonReturn(0,'用户不存在');
        }

        Logger::info(Request::all(),'get_good');


        $quantityCount = Request::input('quantityCount');



        $whichGetGood = '';
        switch(Request::input('get_type'))
        {
            case 2:
                $whichGetGood = 're_get_good';
                break;
            case 3:
                $whichGetGood = 'activity_get_good';
                break;
        }

        if( $quantityCount == -1 )
        {
            $quantityCount = $user->$whichGetGood;
        }

        if( ($quantityCount < 1) || ($user->$whichGetGood  < 1))
        {
            return $this->jsonReturn(0,'暂无法提货');
        }


        if ( $quantityCount > $user->$whichGetGood)
        {
            return $this->jsonReturn(0,'超过可提货箱数');
        }




        //这里如果是自提的话，很奇怪的,不会立即生成记录，只会生成一条临时的记录
        if( Request::input('self_get_need_scan') && (Request::input('deliver_type') == 1))
        {
            $monthGetGood = new MonthGetGoodTmp();
            $monthGetGood->user_id = $user->id;
            $monthGetGood->count = $quantityCount;
            $monthGetGood->get_date = date('Y-m-d');
            $monthGetGood->deliver_type = Request::input('deliver_type');
            $monthGetGood->get_status = ($monthGetGood->deliver_type == 1)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $monthGetGood->address = Request::input('address');
            $monthGetGood->address_name = Request::input('address_name');
            $monthGetGood->address_phone = Request::input('address_phone');
            $monthGetGood->get_type = Request::input('get_type');
            $monthGetGood->save();

            return $this->jsonReturn(1,$monthGetGood->id);
        }

        //更改记录
        $user->$whichGetGood = $user->$whichGetGood - $quantityCount;
        $user->save();


        $monthGetGood = new MonthGetGood();
        $monthGetGood->user_id = $user->id;
        $monthGetGood->count = $quantityCount;
        $monthGetGood->get_date = date('Y-m-d');
        $monthGetGood->deliver_type = Request::input('deliver_type');
        $monthGetGood->get_status = ($monthGetGood->deliver_type == 1)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;;
        $monthGetGood->address = Request::input('address');
        $monthGetGood->address_name = Request::input('address_name');
        $monthGetGood->address_phone = Request::input('address_phone');
        $monthGetGood->get_type =  Request::input('get_type');;
        $monthGetGood->save();


        if($monthGetGood->get_type == 3)
        {
            return $this->jsonReturn(1);
        }


        //生成辅导记录
        $upUser = User::find($user->parent_id);
        $superUser = User::find($user->indirect_id);

        $productAttr = ProductAttr::find(2);

        if( $upUser )
        {
            $cashStream = new CashStream();
            $cashStream->price = $productAttr->single_up_price * $quantityCount;
            $cashStream->compare_price = $upUser->charge;
            $cashStream->user_id = $upUser->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_UP;
            $cashStream->sub_cash_type = 1;
            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->refer_id = $monthGetGood->id;
            $cashStream->refer_user_id = $monthGetGood->user_id;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->save();

            $upUser->increment('charge',$cashStream->price);
        }

        if( $superUser )
        {
            $cashStream = new CashStream();
            $cashStream->price = $productAttr->single_super_price * $quantityCount;
            $cashStream->compare_price = $superUser->charge;
            $cashStream->user_id = $superUser->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_SUPER;
            $cashStream->sub_cash_type = 2;
            $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
            $cashStream->refer_id = $monthGetGood->id;
            $cashStream->refer_user_id = $monthGetGood->user_id;
            $cashStream->direction = CashStream::CASH_DIRECTION_IN;
            $cashStream->save();

            $superUser->increment('charge',$cashStream->price);
        }



        return $this->jsonReturn(1);
    }

    /**
     * 单条记录的详情
     */
    public function anyMonthGetGoodInfo()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        $monthGetGood = MonthGetGood::where('user_id',$user->id)->orderBy('id','desc')->first();

        $commonInfo['monthGetGood'] = $monthGetGood;
        return $this->jsonReturn(1,$commonInfo);
    }


    public function anyReportInfo()
    {
        $productAttr = ProductAttr::find(2);
        return $this->jsonReturn(1,['product_attr'=>$productAttr]);
    }

    /**
     *
     */
    public function anyReReportInfo()
    {
       $product = Product::find(2);
       return $this->jsonReturn(1,['product'=>$product]);
    }


    /**
     * 最后一笔付过款的订单
     */
    public function anyLastPayedOrder()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        $commonInfo['order'] = Order::where('orders.user_id',$user->id)->where('pay_status',1)->orderBy('orders.id','desc')->leftJoin('invited_codes','orders.id','=','refer_order_id')->first();

        return $this->jsonReturn(1,$commonInfo);
    }

    /**
     * 活动会员升级为高级会员
     */
    public function anyUpUser()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        if( !($user instanceof  User) )
        {
            return $this->jsonReturn(0,'无效用户');
        }

        if( $user->vip_level != User::LEVEL_ACTIVITY )
        {
            return $this->jsonReturn(0,'已经是高级用户');
        }



        $this->validate(Request::all(),[
            'invited_code'=>"required"
        ]);



        $invitedCode = Request::input('invited_code');
        $invitedCode = InvitedCodes::tryCurrentInstanceValid($invitedCode);
        if(!$invitedCode)
        {
            return $this->jsonReturn(0,'邀请已失效');
        }

        //升级用户
        $invitedCode->code_status = 1;
        $invitedCode->user_id = $user->id;
        $invitedCode->save();

        $order = Order::find($invitedCode->refer_order_id);
        $orderUser = User::find($order->immediate_user_id);

        $user->vip_level = User::LEVEL_MASTER;
        //上级，上上级信息写入,提货数量写入
        $user->parent_id = $orderUser->id;
        $user->indirect_id = $orderUser->parent_id;
        $user->get_good = $order->quantity;
        $user->save();

        //直接开发获利
        $cashStream = new CashStream();
        $cashStream->price = ProductAttr::find(2)->direct_price;
        $cashStream->compare_price = $orderUser->charge;
        $cashStream->user_id = $orderUser->id;
        $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_DIRECT;
        $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
        $cashStream->refer_id = $order->id;
        $cashStream->refer_user_id = $user->id;
        $cashStream->direction = CashStream::CASH_DIRECTION_IN;
        $cashStream->save();

        $orderUser->increment('charge',$cashStream->price);

        return $this->jsonReturn(1);
    }


    /**
     * 获得用户的邀请码记录
     */
    public function anyInvitedCode()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        if( !($user instanceof  User) )
        {
            $commonInfo['used_list'] = [];
            $commonInfo['valid_list'] = [];
            return $this->jsonReturn(1,$commonInfo);
        }

        $used_list = Order::where('orders.user_id',$user->id)->where('buy_type',Order::BUY_TYPE_REPORT)->leftJoin('invited_codes','orders.id','=','invited_codes.refer_order_id')->leftJoin('users','users.id','=','invited_codes.user_id')->orderBy('invited_codes.updated_at','desc')->selectRaw('*,invited_codes.updated_at as invited_time')->where('code_status','1')->get();
        if( $used_list )
        {
            $commonInfo['used_list'] = $used_list;
        }


        $valid_list = Order::where('orders.user_id',$user->id)->where('buy_type',Order::BUY_TYPE_REPORT)->leftJoin('invited_codes','orders.id','=','invited_codes.refer_order_id')->orderBy('orders.id','desc')->where('code_status','0')->get();
        if( $used_list )
        {
            $commonInfo['valid_list'] = $valid_list;
        }

        return $this->jsonReturn(1,$commonInfo);

    }

    /**
     * 获取提货记录
     */
    public function anyGetGoodRecord()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        $selfGet = MonthGetGood::where('user_id',$user->id)->where('deliver_type',Deliver::SELF_GET)->orderBy('id','desc')->get();
        $delvierHome = MonthGetGood::where('user_id',$user->id)->where('deliver_type',Deliver::DELIVER_HOME)->orderBy('id','desc')->get();

        $commonInfo['selfGet'] = $selfGet;
        $commonInfo['delvierHome'] = $delvierHome;

        return $this->jsonReturn(1,$commonInfo);
    }

    /**
     * 手机二维码图片
     */
    public function anySelfGetPic()
    {
        $id = Request::input('id');
        if(!$id)
        {
            dd('无效参数');
        }

        $token = \SmallWechatCallback::getAccessToken();
        $requestURI = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $token . '';
        $data = ['scene'=>$id,'page'=>env('SELF_GET_PATH','pages/index/index')];
        $response = Curl::curlPostRaw($requestURI,json_encode($data),false,'origin');
        header('Content-type: image/jpg');
        echo $response;
        exit;
    }

    /*
     *
     */
    public function anyMonthGetRecord()
    {
        $id = Request::input('id');
        $record = MonthGetGood::find($id);
        return $this->jsonReturn(1,$record);
    }

    /**
     * 消息列表
     */
    public function anyMessageList()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        $messageList = Message::where('to_uid',$user->id)->orderBy('id','desc')->selectRaw('*,date_format(created_at,"%Y-%m-%d") as created_at_date')->get();
        $commonInfo['messageList'] = $messageList;
        return $this->jsonReturn(1,$commonInfo);
    }

    public function anyReadMessage()
    {
        $id = Request::input('id');
        $message = Message::find($id);
        if( !($message instanceof  Message) )
        {
            return $this->jsonReturn(0,'消息不存在');
        }

        $message->view_status = $message->view_status?$message->view_status:1;
        $message->save();
        return $this->jsonReturn(1,['data'=>$message]);
    }


    /**
     * 扫码确认自提
     * 1120用户不在，
     * 1121该用户没有扫码权限
     * 1220订单不存在
     */
    public function anyConfirmSelfGet()
    {

        Logger::info(Request::all(),'confirm_get');


        $commonInfo = $this->commonInfo();
        $user = $commonInfo['user'];

        if( !($user instanceof  User) )
        {
            return $this->jsonReturn(1,['code'=>1120,'desc'=>'用户不在']);
        }

        $adminIds = explode(',',env('SELF_GET_ADMIN'));
        if(!in_array($user->id,$adminIds))
        {
            return $this->jsonReturn(1,['code'=>1121,'desc'=>'该用户没有扫码权限']);
        }

        $id = Request::input('id');
        Logger::info($id . '-' . $user->id,'confirm_get');

        //这里走两条路，
        if(strpos($id,'c') === 0)
        {
            //存在c位，执行新逻辑
            return $this->newConfirm(str_replace('c','',$id),$user);
        }


        $monthGetGood = MonthGetGood::find($id);
        if( !($monthGetGood instanceof  MonthGetGood) )
        {
            return $this->jsonReturn(1,['code'=>1220,'desc'=>'订单不存在']);
        }

        if($monthGetGood->deliver_type != 1)
        {
            return $this->jsonReturn(1,['code'=>1221,'desc'=>'该订单非自提订单']);
        }

        if( $monthGetGood->get_status != Order::ORDER_STATUS_WAIT_SELF_GET)
        {
            return $this->jsonReturn(1,['code'=>1221,'desc'=>'该订单已被提货']);
        }

        $monthGetGood->get_status = Order::ORDER_STATUS_SELF_GOT;
        $monthGetGood->confirm_user_id = $user->id;
        $monthGetGood->confirm_at = date('Y-m-d H:i:s');
        $monthGetGood->save();

        return $this->jsonReturn(1,['code'=>200,'desc'=>'提货成功']);
    }

    private function newConfirm($id,$adminUser)
    {
        $monthGetGoodTmp = MonthGetGoodTmp::find($id);
        if( !($monthGetGoodTmp instanceof  MonthGetGoodTmp) )
        {
            return $this->jsonReturn(1,['code'=>1123,'desc'=>'无效二维码']);
        }


        if( $monthGetGoodTmp->confirm_user_id )
        {
            return $this->jsonReturn(1,['code'=>1124,'desc'=>'该二维码已被使用']);
        }

        $user = User::find($monthGetGoodTmp->user_id);

        if( $monthGetGoodTmp->get_type == 1)
        {
            //报单
            $quantityCount = $monthGetGoodTmp->count;
            if ( $quantityCount > $user->get_good)
            {
                return $this->jsonReturn(1,['code'=>1124,'desc'=>'超过可提货箱数']);
            }

            $count = MonthGetGood::where('user_id',$user->id)->whereRaw('date_format(get_date,"%Y-%m") = "' . date('Y-m') . '"')->where('get_type',1)->sum('count');

            if( env('OPEN_QUANTITY_COUNT_LIMIT',true) && ($quantityCount + $count > 2) )
            {
                return $this->jsonReturn(1,['code'=>1124,'desc'=>'超过本月可提货最大箱数']);
            }

            $monthGetGood = new MonthGetGood();
            $monthGetGood->user_id = $user->id;
            $monthGetGood->count = $quantityCount;
            $monthGetGood->get_date = date('Y-m-d');
            $monthGetGood->deliver_type = $monthGetGoodTmp->deliver_type;
            $monthGetGood->get_status = ($monthGetGood->deliver_type == 1)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $monthGetGood->address = $monthGetGoodTmp->address;
            $monthGetGood->address_name = $monthGetGoodTmp->address_name;
            $monthGetGood->address_phone = $monthGetGoodTmp->address_phone;
            $monthGetGood->tmp_id = $monthGetGoodTmp->id;
            $monthGetGood->confirm_user_id = $adminUser->id;
            $monthGetGood->confirm_at = date('Y-m-d H:i:s');
            $monthGetGood->save();

            $monthGetGoodTmp->confirm_user_id = $adminUser->id;
            $monthGetGoodTmp->confirm_at = date('Y-m-d H:i:s');
            $monthGetGoodTmp->save();

            //更改记录
            $user->get_good = $user->get_good - $quantityCount;
            $user->save();

            //生成辅导记录
            $mineUpUser = User::find($user->parent_id);


            if( $mineUpUser instanceof  User)
            {
                $upUser = User::find($mineUpUser->parent_id);
                $superUser = User::find($mineUpUser->indirect_id);
            } else
            {
                $upUser = null;
                $superUser = null;
            }

            $productAttr = ProductAttr::find(2);

            if( $upUser )
            {
                $cashStream = new CashStream();
                $cashStream->price = $productAttr->single_up_price * $quantityCount;
                $cashStream->compare_price = $upUser->charge;
                $cashStream->user_id = $upUser->id;
                $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_UP;
                $cashStream->sub_cash_type = 1;
                $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
                $cashStream->refer_id = $monthGetGood->id;
                $cashStream->refer_user_id = $monthGetGood->user_id;
                $cashStream->direction = CashStream::CASH_DIRECTION_IN;
                $cashStream->save();

                $upUser->increment('charge',$cashStream->price);
            }

            if( $superUser )
            {
                $cashStream = new CashStream();
                $cashStream->price = $productAttr->single_super_price * $quantityCount;
                $cashStream->compare_price = $superUser->charge;
                $cashStream->user_id = $superUser->id;
                $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_SUPER;
                $cashStream->sub_cash_type = 2;
                $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
                $cashStream->refer_id = $monthGetGood->id;
                $cashStream->refer_user_id = $monthGetGood->user_id;
                $cashStream->direction = CashStream::CASH_DIRECTION_IN;
                $cashStream->save();

                $superUser->increment('charge',$cashStream->price);
            }

            return $this->jsonReturn(1,['code'=>200,'desc'=>'提货成功']);

        }else if( in_array($monthGetGoodTmp->get_type,[2,3]))
        {
            //复购
            $quantityCount = $monthGetGoodTmp->count;

            $whichGetGood = $monthGetGoodTmp->whichGetGood();

            if( $quantityCount == -1 )
            {
                $quantityCount = $user->$whichGetGood;
            }

            if( ($quantityCount < 1) || ($user->$whichGetGood  < 1))
            {
                return $this->jsonReturn(1,['code'=>1124,'desc'=>'暂无法提货']);
            }


            if ( $quantityCount > $user->$whichGetGood)
            {
                return $this->jsonReturn(1,['code'=>1124,'desc'=>'超过可提货箱数']);
            }

            //更改记录
            $user->$whichGetGood = $user->$whichGetGood - $quantityCount;
            $user->save();


            $monthGetGood = new MonthGetGood();
            $monthGetGood->user_id = $user->id;
            $monthGetGood->count = $quantityCount;
            $monthGetGood->get_date = date('Y-m-d');
            $monthGetGood->deliver_type =  $monthGetGoodTmp->deliver_type;
            $monthGetGood->get_status = ($monthGetGood->deliver_type == 1)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;;
            $monthGetGood->address = $monthGetGoodTmp->address;
            $monthGetGood->address_name = $monthGetGoodTmp->address_name;
            $monthGetGood->address_phone = $monthGetGoodTmp->address_phone;
            $monthGetGood->get_type = $monthGetGoodTmp->get_type;
            $monthGetGood->tmp_id = $monthGetGoodTmp->id;
            $monthGetGood->confirm_user_id = $adminUser->id;
            $monthGetGood->confirm_at = date('Y-m-d H:i:s');
            $monthGetGood->save();

            $monthGetGoodTmp->confirm_user_id = $adminUser->id;
            $monthGetGoodTmp->confirm_at = date('Y-m-d H:i:s');
            $monthGetGoodTmp->save();


            if( $monthGetGood->get_type == 3)
            {
                return $this->jsonReturn(1,['code'=>200,'desc'=>'提货成功']);
            }

            //生成辅导记录
            $upUser = User::find($user->parent_id);
            $superUser = User::find($user->indirect_id);

            $productAttr = ProductAttr::find(2);

            if( $upUser )
            {
                $cashStream = new CashStream();
                $cashStream->price = $productAttr->single_up_price * $quantityCount;
                $cashStream->compare_price = $upUser->charge;
                $cashStream->user_id = $upUser->id;
                $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_UP;
                $cashStream->sub_cash_type = 1;
                $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
                $cashStream->refer_id = $monthGetGood->id;
                $cashStream->refer_user_id = $monthGetGood->user_id;
                $cashStream->direction = CashStream::CASH_DIRECTION_IN;
                $cashStream->save();

                $upUser->increment('charge',$cashStream->price);
            }

            if( $superUser )
            {
                $cashStream = new CashStream();
                $cashStream->price = $productAttr->single_super_price * $quantityCount;
                $cashStream->compare_price = $superUser->charge;
                $cashStream->user_id = $superUser->id;
                $cashStream->cash_type = CashStream::CASH_TYPE_BENEFIT_SUPER;
                $cashStream->sub_cash_type = 2;
                $cashStream->pay_status = CashStream::CASH_STATUS_PAYED;
                $cashStream->refer_id = $monthGetGood->id;
                $cashStream->refer_user_id = $monthGetGood->user_id;
                $cashStream->direction = CashStream::CASH_DIRECTION_IN;
                $cashStream->save();

                $superUser->increment('charge',$cashStream->price);
            }

            return $this->jsonReturn(1,['code'=>200,'desc'=>'提货成功']);
        }

    }


    public function anyTeam()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        if( !($user instanceof  User) )
        {
            $commonInfo['sub_user'] = [];
            $commonInfo['activity_user'] = [];
        } else {
            $commonInfo['sub_user'] = User::where('parent_id',$user->id)->orderBy('id','desc')->selectRaw('users.*,date_format(created_at,"%Y-%m-%d") as created_at_day')->get();
            $commonInfo['activity_user'] = Order::where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('immediate_user_id',$user->id)->where('pay_status',1)->leftJoin('users','user_id','=','users.id')->selectRaw('users.*,date_format(users.created_at,"%Y-%m-%d") as created_at_day ')->get();
        }


        return $this->jsonReturn(1,$commonInfo);
    }


    public function anyCashInfo()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        $commonInfo['total_cash'] = $user->charge + $user->charge_frozen;
        $commonInfo['up_and_super_count'] = $user->upAndSuperCount();
        $commonInfo['direct_and_indirect_count'] = $user->directAndIndirectCount();
        $commonInfo['month_income'] = $user->monthIncome();

        return $this->jsonReturn(1,$commonInfo);
    }


    public function anyUpComment()
    {
        $id = Request::input('id');
        $signRecord = SignRecord::find($id);
        $signRecord->up_comment = Request::input('content');
        $signRecord->save();
        return $this->jsonReturn(1);
    }

    public function anyDirectList()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

//        $orders = Order::where('direct_id',$user->id)->where('pay_status',1)->orderBy('invited_codes.updated_at','desc')->join('invited_codes','invited_codes.refer_order_id','=','orders.id')->where('code_status',1)->leftJoin('users','users.id','=','invited_codes.user_id')->selectRaw('*,invited_codes.updated_at as register_time')->get();

        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        $orders = CashStream::where('user_id',$user->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT])->orderBy('cash_stream.id','desc')->leftJoin('users','cash_stream.refer_user_id','=','users.id')->selectRaw('cash_stream.*,real_name,phone')->get();

        if( !$orders )
        {
            $orders = [];
        }
        $commonInfo['orders'] = $orders;
        return $this->jsonReturn(1,$commonInfo);
    }


    public function anyUpSuperList()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        $upSuperList = CashStream::where('cash_stream.user_id',$user->id)->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->orderBy('cash_stream.id','desc')->leftJoin('users','cash_stream.refer_user_id','=','users.id')->selectRaw('cash_stream.*,real_name,phone,month_get_good.count')->leftJoin('month_get_good','month_get_good.id','=','cash_stream.refer_id')->get();

        if( !$upSuperList )
        {
            $upSuperList = [];
        }
        $commonInfo['up_super_list'] = $upSuperList;

        return $this->jsonReturn(1,$commonInfo);

    }

    public function anyWithdrawSms()
    {
        $code = DealString::random(6,'number');
        $smsTemplate = new SmsTemplate(SmsTemplate::WITHDRAW_SMS);
        $smsTemplate->sendSms(Request::input('phone'),['code'=>$code]);
        $expiresAt = Carbon::now()->addMinutes(30);
        Cache::put('withdraw_sms_code' . Request::input('phone'), (Request::input('phone') . '_' . $code),$expiresAt);
        return $this->jsonReturn(1);
    }

    /**
     *提现
     */
    public function anyWithdraw()
    {



        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];


        if ( Cache::get('withdraw_sms_code' . Request::input('phone') ) !=  (Request::input('phone') . '_' . Request::input('withdraw_sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        $withdraw = round(Request::input('withdraw'),2);
        if( $withdraw < env('WITHDRAW_LOW_LIMIT',1000) ) {
            return $this->jsonReturn(0,'提现金额不能小于'.env('WITHDRAW_LOW_LIMIT',1000).'元');
        }

        $withdraw = round($withdraw,2);


        //提现的时间限制
        if( date('Y-m-d') < env('WITHDRAW_LIMIT_DATE','2018-06-03'))
        {
            return $this->jsonReturn(0,'试运行期间无法提现,预计提现时间为6月2日以后，谢谢理解');
        }


        //每月最大提现次数限制
        $withdrawCount = CashStream::where('user_id',$user->id)->where('cash_type',CashStream::CASH_TYPE_WITHDRAW)->whereRaw('DATE_FORMAT( created_at, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" ) ')->count();

        if( $withdrawCount > env('WITHDRAW_COUNT_LIME',2) )
        {
            return $this->jsonReturn(0,'提现次数超出本月最大限制');
        }

        $res = DB::update("update users set charge = case when charge - $withdraw >= 0 then charge - $withdraw else charge end where id = {$user->id}");


        if( !$res ) {
            return $this->jsonReturn(0,'金额有误');
        }

        $cashStream = new CashStream();
        $cashStream->user_id = $user->id;
        $cashStream->cash_type = CashStream::CASH_TYPE_WITHDRAW;
        $cashStream->direction = CashStream::CASH_DIRECTION_OUT;
        $cashStream->pay_status = 1;
        $cashStream->withdraw_account = Request::input('account');
        $cashStream->price = $withdraw;
        $cashStream->withdraw_type = 3;
        $cashStream->withdraw_bank = Request::input('withdraw_type');
        $cashStream->save();

        return $this->jsonReturn(1);
    }


    public function getBill()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];
        $query = DB::table('cash_stream')->where('user_id',$user->id)->where('pay_status',1)->whereIn('cash_type',CashStream::userIncomeOutcomeArr());
        if(Request::input('month'))
        {
            $query->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . Request::input('month') . '"');
        }
        $query->orderBy('id','desc')->selectRaw('*,DATE_FORMAT(created_at,"%Y-%m") as created_month');
        $count = 30;
        if(Request::input('all')) {
            $count = $query->count();
            $count = $count?$count:30;
        }
        $res = $query->paginate();
        foreach ($res as $key=>$val) {
            $res[$key]->price_with_char = (($val->direction == 1)?"+":"-") . "￥" . $val->price;
            $res[$key]->cash_type_text = CashStream::cashTypeText($val->cash_type);
            $res[$key]->created_at_text = Kit::dateFormat2($val->created_at);
        }

        $res=$res->toJson();

        return $this->jsonReturn(1,json_decode($res));
    }

    public function getUserList()
    {
        $res = DB::table('users')->selectRaw('phone,id_card,real_name')->orderBy('id','desc')->where('vip_level',User::LEVEL_MASTER)->get();
        return $this->jsonReturn(1,$res);
    }

    public function getUserActivity()
    {
        
    }

    public function getSignQuantity()
    {
        set_time_limit(3600);
        $records = SignRecord::where('sign_status',1)->get();

        foreach ($records as $record) {
            echo $record->id . '<br/>';
            $signProv = json_decode($record->sign_prov);
            $record->quantity = $signProv->countIndex + 1;
            $record->save();
        }
    }


    /**
     * 打卡成绩单
     */
    public function getSignReport()
    {
        $commonInfo = $this->commonInfoNew();

        $user = $commonInfo['user'];

        //最后一笔打卡信息
        $signRecord = SignRecord::where('user_id',$user->id)->where('sign_status',1)->orderBy('id','desc')->first();
        $commonInfo['sign_record'] = $signRecord;


        $commonInfo['sign_days'] = SignRecord::where('user_id',$user->id)->where('sign_status',1)->count();

        return $this->jsonReturn(1,$commonInfo);
    }

    public function getTurnBack()
    {
        //判断是否满足30天要求
        $commonInfo = $this->commonInfoNew();

        $user = $commonInfo['user'];

        if( ! ($user instanceof  User) )
        {
            return $this->jsonReturn(0,'无效用户');
        }

        //判断是否打卡完成，或者是否超过30天
        $order = $user->getActivityPayedOrder();

        if( ! ($order instanceof  Order))
        {
            return $this->jsonReturn(0,'未参加活动');
        }

        if ( !$order->health_over_info_status || !(time() > (strtotime($order->health_over_date) + 86400 * 30)) || ($order->health_over_date < '2000-01-01'))
        {
            return $this->jsonReturn(0,'打卡完成30天后才可提现');
        }

        //判断是否有提现记录
        $cashStream = CashStream::where('user_id',$user->id)->where('cash_type',CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->whereIn('withdraw_deal_status',[0,1])->first();

        if( $cashStream instanceof  CashStream )
        {
            return $this->jsonReturn(0,'请勿重复申请退款');
        }


//        //
        if( Request::input('doInsert') )
        {
            $cashStream = new CashStream();
            $cashStream->user_id = $user->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_ACTIVITY_WITHDRAW;
            $cashStream->price = $order->need_pay;
            $cashStream->pay_status = 1;

            $cashStream->withdraw_account = Request::input('account');
            $cashStream->withdraw_type = 3;
            $cashStream->withdraw_bank = Request::input('type');

            $cashStream->save();
        }
//        $cashStream = new CashStream();
//        $cashStream->user_id = $user->id;
//        $cashStream->cash_type = CashStream::CASH_TYPE_ACTIVITY_WITHDRAW;
//        $cashStream->price = $order->price;
//        $cashStream->pay_status = 1;
//        $cashStream->save();

        return $this->jsonReturn(1);
    }


    /**
     * 打卡结束后，保存健康信息
     */
    public function anySaveOverHealth()
    {
        $user = $this->judgeUserByOpenid();

        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('pay_status',1)->first();


        if( !($order instanceof  Order) )
        {
            return $this->jsonReturn(0,'无效活动订单');
        }


        $healthInfo = (Object)[];
        $healthInfo->tall = Request::input('tall');
        $healthInfo->blood_press = Request::input('blood_press');
        $healthInfo->weight = Request::input('weight');
        $healthInfo->waistline = Request::input('waistline');
        $healthInfo->blood_glucose = Request::input('blood_glucose');
        $healthInfo->cover_image = Request::input('cover_image');
        $healthInfo->oppo_image = Request::input('oppo_image');


        $order->health_over_info = json_encode($healthInfo);
        $order->health_over_info_status = 1;
        $order->health_over_date = date('Y-m-d');
        $order->save();

        return $this->jsonReturn(1);
    }


    /**
     * 解绑用户
     */
    public function anyUnbind()
    {
        $commonInfo = $this->commonInfoNew();
        $user = $commonInfo['user'];

        if( !($user instanceof  User) )
        {
            return $this->jsonReturn(0,'该用户已退出或者用户不存在');
        }

        $user->openid = '';
        $user->save();
        return $this->jsonReturn(1);
    }

}