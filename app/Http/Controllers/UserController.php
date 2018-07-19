<?php

namespace App\Http\Controllers;

use App\Model\CashStream;
use App\Model\Deliver;
use App\Model\Essay;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\DealString;
use App\Util\Kit;
use App\Util\SmsTemplate;
use Faker\Provider\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Ytulip\Ycurl\Kits;

class UserController extends Controller
{

    private $user;
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    //首页
    public function getIndex()
    {
        $product = Product::find(1);
        $list = DB::table('essays')->orderBy('sort','desc')->get();
        return view('index')->with('product',$product)->with('list',$list);
    }

    /**
     * 个人中心
     */
    public function getCenter()
    {
        return view('center')->with('user',$this->user);
    }

    public function getReportBill()
    {
        return view('report_bill')->with('attr1',ProductAttr::find(1))->with('attr2',ProductAttr::find(2))->with('product',Product::getDefaultProduct())->with('user',$this->user);
    }

    /**
     * 高级会购买
     */
    public function postReportBill()
    {
        $this->validate(Request::all(),[
            'buy_type'=>'required',
//            'immediate_phone'=>'required|exists:users,phone',
            'deliver_type'=>'required',
            'self_get_deliver_address'=>'exists:user_address,address_id',
            'mine_deliver_address'=>'exists:user_address,address_id'
        ]);

        $indirectUser = User::find(Auth::id());
        if ( $indirectUser->vip_level != User::LEVEL_MASTER ) {
            return $this->jsonReturn(0,'成为高级会员才能提单');
        }

        $immediateUser = null;
        if(Request::input('buy_type') == 1) {
            $immediateUser = User::where('phone',Request::input('immediate_phone'))->first();
            if(!$immediateUser)
            {
                $this->jsonReturn(0,'直接开发者不存在');
            }

        }


        $productAttr = ProductAttr::find(2);


        if( Deliver::DELIVER_HOME == Request::input('deliver_type') ) {
            $address = UserAddress::find(Request::input('mine_deliver_address'));
        } else {
            $address = UserAddress::find(Request::input('self_get_deliver_address'));
        }

        //生产订单
        $order = new Order();
        $order->user_id = $indirectUser->id;
        $order->immediate_user_id = $immediateUser?$immediateUser->id:null;
        $order->product_id = $productAttr->product_id;
        $order->product_attr_id = $productAttr->id;
        $order->buy_type = Request::input('buy_type');

        if(Request::input('buy_type') == 1) {
            $order->need_pay = $productAttr->price;
        } else {
//            $order->need_pay = $productAttr->rebuy_price;
            $order->quantity = intval(Request::input('quantity'));
            $order->need_pay = $order->quantity * $productAttr->rebuy_price;
        }
        $order->address = $address->pct_code_name . $address->address;
        $order->address_name = $address->address_name;
        $order->address_phone = $address->mobile;
        $order->deliver_type = Request::input('deliver_type');

        $order->save();

        return $this->jsonReturn(1,$order->id);
    }

    /*支付订单页面*/
    public function getPayBill()
    {
        $orderId = Request::input('order_id');
        $order = Order::where(['user_id'=>Auth::id(),'id'=>$orderId])->first();
        if( !$order ) {
            dd('购买不存在，或者无权访问');
        }

        if( $order->status ) {
            dd('请勿重复购买');
        }
        $user = User::getCurrentUser();
        return view('pay_bill')->with('order',$order)->with('user',$user)->with('product',Product::getDefaultProduct());
    }

    public function getFinance()
    {
        $user = User::getCurrentUser();
        return view('finance')->with('user',$user);
    }

    public function getOrders()
    {
        $res = DB::table('orders')->leftJoin('products','product_id','=','products.id')->leftJoin('product_attrs','product_attr_id','=','product_attrs.id')->where('user_id',Auth::id())->where('order_status','>',0)->selectRaw('*,orders.created_at as order_created_at,orders.id as order_id')->orderBy('orders.id','desc')->get();
        $res = $res?$res:[];
        return view('orders')->with('orders',$res);
    }

    public function getOrderDetail()
    {
        $order = Order::where('user_id',Auth::id())->where('id',Request::input('order_id'))->first();
        if (!$order)
        {
            dd('无效订单');
        }
        return view('order_detail')->with('order',$order)->with('direct',User::find($order->immediate_user_id))->with('productAttr',ProductAttr::find($order->product_attr_id));
    }

    public function getAddresses()
    {
        return view('addresses')->with('addressList',UserAddress::mineAddressList(Auth::id()));
    }

    public function getSetting()
    {
        return view('setting');
    }

    /*支付订单*/
    public function anyOrderPay()
    {

        /*应该带一个戳，避免重复支付*/

        $orderId = Request::input('order_id');
        $order = Order::find($orderId);
        if(!$order) {
            dd('订单存在');
        }

        if ($order->user_id != Auth::id()) {
            dd('无权支付');
        }

        $user = User::find(Auth::id());

        if(!$order->needPay()) {
            return $this->jsonReturn(0,'请勿重复支付');
        }

        $payType = Request::input('pay_type');

        //余额支付
        if( $payType == Order::PAY_lMS ) {
            if ( $order->need_pay > $user->charge ) {
                return $this->jsonReturn(0,'余额不足');
            }

            //开始扣钱了哟，更新余额
            $user->charge = $user->charge - $order->need_pay;
            $user->save();


            //余额支付
            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->pay_status = 1;
            $cashStream->cash_type = CashStream::CASH_TYPE_LMS_PAY_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->compare_price = $user->charge;
            $cashStream->save();

            //更改订单信息
            $order->pay_status = 1;
            $order->order_status = ($order->deliver_type == Deliver::SELF_GET)?Order::ORDER_STATUS_WAIT_SELF_GET:Order::ORDER_STATUS_WAIT_DELIVER;
            $order->pay_type = CashStream::CASH_PAY_TYPE_LMS;
            $order->pay_time = date('Y-m-d H:i:s');
            $order->save();

            //生成邀请码，如果是复购的话没有邀请码

            $order = Order::find($order->id);
            $order->paySuccess();

//            if( $order->buy_type == 1 ) {
//                InvitedCodes::makeRecord($order->id);
//            }
//
//            //生成开发辅导初始记录
//            $order->benefitInit();

            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->pay_status = 1;
            $cashStream->cash_type = CashStream::CASH_TYPE_LMS_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->save();
            Message::addReport($order->id);

            return $this->jsonReturn(1);
        }

        if( $payType == Order::PAY_ALIPAY )
        {
            require_once base_path() .'/plugin/alipay/wappay/service/AlipayTradeService.php';
            require_once base_path().'/plugin/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';

            require  base_path() .'/plugin/alipay/config.php';

//            var_dump($config);
//            var_dump(config('alipay'));
//            exit;
            $config = config('alipay');

//            var_dump($config);
//            exit;


            //创建支付订单
            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->save();

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $cashStream->id;

            //订单名称，必填
            $subject = '辣木膳素食全餐';

            //付款金额，必填
            if( env('PAY_TEST')) {
                $total_amount = 0.01;
            } else {
                $total_amount = $cashStream->price;
            }

            //商品描述，可空
            $body = '';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;

        }

        if( $payType == Order::PAY_WECHAT )
        {
            require_once base_path() . "/plugin/wechatpay/lib/WxPay.Api.php";
            require_once base_path() . "/plugin/wechatpay/example/WxPay.JsApiPay.php";


            $tools = null;
            $openid = "";
            if( Kit::isWechat() )
            {
                //
                $openid = \WechatCallback::getOpenidInThisUrlDealWithError(function () {
                    App::abort('404');
                });
                $tools = new \JsApiPay();
            }

            //创建支付订单
            $cashStream = new CashStream();
            $cashStream->refer_id = $order->id;
            $cashStream->cash_type = CashStream::CASH_TYPE_ALIPAY_BUY_PRODUCT;
            $cashStream->user_id = Auth::id();
            $cashStream->price = $order->need_pay;
            $cashStream->save();

            //付款金额，必填
            if( env('PAY_TEST')) {
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
//            $input->SetOpenid($openId);

            if( Kit::isWechat() )
            {
                $input->SetOpenid($openid);
                $order = \WxPayApi::unifiedOrder($input);
                $jsApiParameters = $tools->GetJsApiParameters($order);
                return view('pay')->with('jsApiParameters',$jsApiParameters);
//                return $this->jsonReturn(1,'/finance/pay?data=' . urlencode(json_encode($jsApiParameters)) );
            } else {
                $input->SetTrade_type("MWEB");
                $wxorder = \WxPayApi::unifiedOrder($input);
                return $this->jsonReturn(1,['mweb_url'=>$wxorder['mweb_url'] . '&redirect_url=' . urlencode(env('WECHAT_RETURN_URL') . '?order_id=' . $order->id )]);
            }

        }
    }

    public function getAddModAddress()
    {
        $this->validate(Request::all(),[
            'address_id'=>'exists:user_address,address_id',
        ]);
        $addressId = Request::input('address_id');
        $address = null;
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                dd('无效地址');
            }
        }
        return view('add_mod_address')->with('address',$address);
    }

    public function postAddModAddress()
    {
        $this->validate(Request::all(),[
            'address_id'=>'exists:user_address,address_id',
            'real_name'=>'required',
            'phone'=>'required',
            'city_code'=>'required',
            'address'=>'required'
        ]);

        $addressId = Request::input('address_id');
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                return $this->jsonReturn(0,'无效地址');
            }

            $address->address_name = Request::input('real_name');
            $address->mobile = Request::input('phone');
            $address->pct_code = Request::input('city_code');
            $address->pct_code_name = '';
            $address->address = Request::input('address');
            $address->is_default = Request::input('is_default');
            $address->save();
        } else {
            //新增
            $address = new UserAddress();
            $address->user_id = Auth::id();
            $address->address_name = Request::input('real_name');
            $address->mobile = Request::input('phone');
            $address->pct_code = Request::input('city_code');
            $address->pct_code_name = SyncModel::pctnameByCode($address->pct_code);
            $address->address = Request::input('address');
            $address->is_default = Request::input('is_default');
            $address->save();
        }

        if($address->is_default)
        {
            UserAddress::where('user_id',Auth::id())->where('is_default',1)->whereNotIn('address_id',[$address->address_id])->update(['is_default'=>0]);
        }

        //是否设置为默认


        return $this->jsonReturn(1,$address->address_id);
    }

    public function postDeleteAddress(){
        $this->validate(Request::all(),[
            'address_id'=>'required|exists:user_address,address_id',
        ]);

        $addressId = Request::input('address_id');
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                return $this->jsonReturn(0,'无效地址');
            }
            $address->delete();
        }
        return $this->jsonReturn(1);
    }

    public function postSetDefaultAddress(){
        $this->validate(Request::all(),[
            'address_id'=>'required|exists:user_address,address_id',
        ]);

        $addressId = Request::input('address_id');
        if($addressId) {
            //修改
            $address = UserAddress::where('address_id',$addressId)->first();
            if($address->user_id != Auth::id() || !$address->status) {
                return $this->jsonReturn(0,'无效地址');
            }
//            $address->save();
            DB::update("update user_address set is_default = case when address_id = {$addressId} then 1 else 0 end where user_id =  " . Auth::id());
        }
        return $this->jsonReturn(1);
    }


    public function getMonthIncome()
    {
        $user = User::getCurrentUser();
//        var_dump($user->monthIncomeDetail());
//        exit;
        return view('month_income')->with('user',$user)->with('monthIncomeDetail',$user->monthIncomeDetail());
    }

    public function getUpSuperRecord()
    {
        return view('up_super_record')->with('list',$this->user->upAndSuperList());
    }

    public function getDirectIndirectRecord()
    {
        return view('direct_indirect_record')->with('list',$this->user->directAndIndirectList());
    }

    /**
     * 账单
     */
    public function getCheck()
    {
        return view('check');
    }

    /**
     * 搜索账单
     */
    public function getSearchCheck()
    {
        $query = DB::table('cash_stream')->where('user_id',$this->user->id)->where('pay_status',1)->whereIn('cash_type',CashStream::userIncomeOutcomeArr());
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
        return $res;
    }

    /**
     * 提现功能
     */
    public function getWithdraw()
    {
        return view('withdraw_input')->with('user',$this->user);
    }


    public function getWithdrawConfirm()
    {
//        if ( !Session::get('withdraw_confirm_ok'))
//        {
//            return Redirect::to('/user/withdraw');
//        }
//
//        Session::forget('withdraw_confirm_ok');

        $withdraw = round(Request::input('withdraw'),2);
        if( $withdraw <= 0 ) {
            dd('金额有误');
        }

        return view('withdraw')->with('withdraw',$withdraw)->with('user',$this->user);

    }

    /**
     *
     */
    public function postWithdraw()
    {
        if ( Session::get('withdraw_sms_code') !=  ($this->user->phone . '_' . Request::input('withdraw_sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        Session::put('withdraw_confirm_ok',1);
        Session::forget('withdraw_sms_code');


        return $this->jsonReturn(1);

//        $withdraw = Request::input('withdraw');
//        if( $withdraw <= 0 ) {
//            return $this->jsonReturn(0,'金额有误');
//        }

//        $withdraw = round($withdraw,2);
//
//        $res = DB::query("update users set charge = case when charge - $withdraw >= 0 then charge then charge where user_id = {$this->user->id}");
//        var_dump($res);
//        exit;
//
//        $cashStream = new CashStream();
//        $cashStream->user_id = $this->user->id;
//        $cashStream->cash_type = CashStream::CASH_TYPE_WITHDRAW;
//        $cashStream->direction = CashStream::CASH_DIRECTION_OUT;
//        $cashStream->pay_status = 1;
//        $cashStream->price = $withdraw;
//        $cashStream->save();
    }

    public function postWithdrawConfirm()
    {
        //验证密码
        if ( !Hash::check(Request::input('password'),$this->user->password))
        {
            return $this->jsonReturn(0,'密码有误');
        }

        if ( Session::get('withdraw_sms_code') !=  ($this->user->phone . '_' . Request::input('withdraw_sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

//        Session::put('withdraw_confirm_ok',1);
        Session::forget('withdraw_sms_code');

        $withdraw = round(Request::input('withdraw'),2);
        if( $withdraw < env('WITHDRAW_LOW_LIMIT',1000) ) {
            return $this->jsonReturn(0,'提现金额不能小于'.env('WITHDRAW_LOW_LIMIT',1000).'元');
        }

        $withdraw = round($withdraw,2);


        //每月最大提现次数限制
        $withdrawCount = CashStream::where('user_id',$this->user->id)->where('cash_type',CashStream::CASH_TYPE_WITHDRAW)->whereRaw('DATE_FORMAT( created_at, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" ) ')->count();

        if( $withdrawCount > env('WITHDRAW_COUNT_LIME',2) )
        {
            return $this->jsonReturn(0,'提现次数超出本月最大限制');
        }

        $res = DB::update("update users set charge = case when charge - $withdraw >= 0 then charge - $withdraw else charge end where id = {$this->user->id}");


        if( !$res ) {
            return $this->jsonReturn(0,'金额有误');
        }

        $cashStream = new CashStream();
        $cashStream->user_id = $this->user->id;
        $cashStream->cash_type = CashStream::CASH_TYPE_WITHDRAW;
        $cashStream->direction = CashStream::CASH_DIRECTION_OUT;
        $cashStream->pay_status = 1;
        $cashStream->withdraw_account = Request::input('account');
        $cashStream->price = $withdraw;
        $cashStream->withdraw_type = Request::input('withdraw_type');
        $cashStream->save();

        return $this->jsonReturn(1);

    }

    public function postWithdrawSms()
    {
        $code = DealString::random(6,'number');
        $smsTemplate = new SmsTemplate(SmsTemplate::WITHDRAW_SMS);
        $smsTemplate->sendSms($this->user->phone,['code'=>$code]);
        Session::put('withdraw_sms_code',($this->user->phone . '_' . $code));
        return $this->jsonReturn(1);
    }

    public function getWithdrawSuccess()
    {
        return view('withdraw_success');
    }

    public function getFillUserInfo()
    {
        return view('fill_user_info');
    }

    public function postFillUserInfo()
    {
        $user = User::getCurrentUser();
        $user->real_name = Request::input('real_name');
        $user->id_card = Request::input('id_card');
        $user->save();
        return $this->jsonReturn(1);
    }

    public function getGoodDetail()
    {
        return view('good_detail')->with('product',Product::getDefaultProduct());
    }

    public function getGoodDetailXcx()
    {
        return view('good_detail_xcx')->with('product',Product::getDefaultProduct());
    }

    public  function getEssay()
    {
        $essay = Essay::find(Request::input('id'));
        if( !$essay )
        {
            dd('文章不存在');
        }
        return view('essay')->with('essay',$essay);
    }

    public function getInvitedList()
    {
        $invitedList = $this->user->myInvitedCodes();
        return view('invited_list')->with('invited_list',$invitedList);
    }

    public function getInfo()
    {
        return view('info')->with('user',$this->user);
    }

    /**
     * 设置头像
     */
    public function getHeaderImg()
    {
        return view('header_img');
    }

    public function postHeaderImg()
    {
        $files = \Illuminate\Support\Facades\Request::file('images');
        $count = count($files);

        if( $count != 1)
        {
            return json_encode(["status"=>0,"desc"=>"文件个数异常"],JSON_UNESCAPED_UNICODE);
        }

        $imagesInfo = [];
        foreach( $files as $key=>$file )
        {
            $imageExtension = $file->getClientOriginalExtension(); //上传文件的后缀
            if(!in_array($imageExtension,['jpg','png','gif','jpeg'])){
                return json_encode(['status'=>0,'desc'=>'文件格式异常'],JSON_UNESCAPED_UNICODE);
            }
            $imagesInfo[] = $imageSaveName =  bin2hex(base64_encode( time() . $key)) . '.' . $imageExtension; //文件保存的名字
        }

        $res = [];
        $result = false;
        foreach($files as $key=>$file)
        {
            if ( $file->move('imgsys',$imagesInfo[$key] ) ){
                $result = true;
                $res[] = '/imgsys/' . $imagesInfo[$key];
            } else {
                $result = false;
                break;
            }
        }

        if($result) {
            $this->user->header_img = $res[0];
            $this->user->save();
            //写入数据库
            return json_encode(['status'=>1,'data'=>$res]);
        }else {
            return json_encode(['status'=>0,'desc'=>"上传异常"],JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 修改绑定手机号
     */
    public function getModifyPhone()
    {
        return view('modify_phone');
    }

    public function postModifyPhone()
    {
        if ( Session::get('modify_phone_sms_code') !=  (Request::input('phone') . '_' . Request::input('sms_code')) )
        {
            return $this->jsonReturn(0,'验证码错误');
        }

        $user = User::where('phone',Request::input('phone'))->first();

        if( $user )
        {
            return $this->jsonReturn(0,'手机号已被使用');
        }

        $user = User::getCurrentUser();
        $user->phone = Request::input('phone');
        $user->save();

        return $this->jsonReturn(1);

    }

    public function anyModifyPhoneSms()
    {
        $this->validate(Request::all(),[
            'phone'=>'required',
        ]);


        $code = DealString::random(6,'number');


        $smsTemplate = new SmsTemplate(SmsTemplate::MODIFY_PHONE_SMS);


        $smsTemplate->sendSms(Request::input('phone'),['code'=>$code]);
        Session::put('modify_phone_sms_code',(Request::input('phone') . '_' . $code));
        return $this->jsonReturn(1);
    }

    /**
     *
     */
    public function anySubUserList()
    {
        return view('sub_user_list')->with('list',$this->user->subList());
    }
}