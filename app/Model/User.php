<?php

namespace App\Model;

use App\Log\Facades\Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    const LEVEL_ACTIVITY = 0;
    const LEVEL_VIP = 1;
    const LEVEL_MASTER = 2;

    public static function levelText($level)
    {
        $text = '';
        switch ($level)
        {
            case User::LEVEL_ACTIVITY:
                $text = '活动会员';
                break;
            case User::LEVEL_VIP:
                $text = "VIP会员";
                break;
            case User::LEVEL_MASTER:
                $text = "高级会员";
                break;
        }
        return $text;
    }

    public function monthIncome()
    {
        return DB::table('cash_stream')->where('user_id',$this->id)->whereIn('cash_type',CashStream::incomeCashTypeArr())->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . date('Y-m') . '"')->where('pay_status','>','0')->sum('price');
    }

    public function validInvitedCodeCount()
    {
        return count($this->myInvitedCodes(['code_status'=>0]));
    }

    private function monthCountAndSum($filterArr)
    {
        $price = DB::table('cash_stream')->where('user_id',$this->id)->where($filterArr)->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . date('Y-m') . '"')->where('pay_status','>','0')->sum('price');
        $count = DB::table('cash_stream')->where('user_id',$this->id)->where($filterArr)->whereRaw('DATE_FORMAT(created_at,"%Y-%m") = "' . date('Y-m') . '"')->where('pay_status','>','0')->count('id');
        return ['count'=>$count,'price'=>$price];
    }

    public function monthIncomeDetail()
    {
        $res = [];
        $res['total'] = ['price'=>$this->monthIncome()];
        $res['direct_vip'] =$this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_DIRECT,'sub_cash_type'=>User::LEVEL_VIP]);;
        $res['direct_master'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_UP,'sub_cash_type'=>User::LEVEL_MASTER]);;
        $res['indirect_vip'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_INDIRECT,'sub_cash_type'=>User::LEVEL_VIP]);;
        $res['indirect_master'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_INDIRECT,'sub_cash_type'=>User::LEVEL_MASTER]);;
        $res['up'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_UP]);
        $res['super'] = $this->monthCountAndSum(['cash_type'=>CashStream::CASH_TYPE_BENEFIT_SUPER]);
        return $res;
    }

    public function upAndSuperCount()
    {
        return DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->where('user_id',$this->id)->where('pay_status','>',0)->count();
    }

    public function upAndSuperList()
    {
        $list = DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_UP,CashStream::CASH_TYPE_BENEFIT_SUPER])->where('user_id',$this->id)->leftJoin('users','cash_stream.refer_user_id','=','users.id')->where('pay_status','>',0)->selectRaw('cash_stream.*,users.phone,users.vip_level,users.real_name')->orderBy('cash_stream.id','desc')->get();
        return $list?$list:[];
    }

    public function directAndIndirectList()
    {
        $list = DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT])->where('user_id',$this->id)->leftJoin('users','cash_stream.refer_user_id','=','users.id')->where('pay_status','>',0)->selectRaw('cash_stream.*,users.phone,users.vip_level,users.real_name')->orderBy('cash_stream.id','desc')->get();
        return $list?$list:[];
    }

    public function directAndIndirectCount()
    {
        return DB::table('cash_stream')->whereIn('cash_type',[CashStream::CASH_TYPE_BENEFIT_DIRECT,CashStream::CASH_TYPE_BENEFIT_INDIRECT])->where('user_id',$this->id)->where('pay_status','>',0)->count();
    }

    public function relationMap()
    {
        $invitedCode = InvitedCodes::where('user_id',$this->id)->first();
        if( !$invitedCode ) {
            $indirect = null;
            $direct = null;
        } else {
            $order = Order::getOrderByInvitedCode($invitedCode->invited_code);
            $indirect = User::find($order->user_id);
            $direct = User::find($order->immediate_user_id);
        }
        $up = User::find($this->parent_id);
        $super = User::find($this->indirect_id);

        return (object)['direct'=>$direct,'indirect'=>$indirect,'up'=>$up,'super'=>$super];
    }

    public static function getCurrentUser()
    {
        return User::find(Auth::id());
    }

    /**
     * 作为简介开发者的邀请码们
     */
    public function myInvitedCodes($filter=[])
    {
        $res = Order::where('orders.user_id',$this->id)->where('order_status','>',0)->leftJoin('invited_codes','orders.id','=','refer_order_id')->where(function($query) use($filter){
            if($filter){
                $query->where($filter);
            }
        })->orderBy('invited_codes.id','desc')->get();
        return $res?$res:[];
    }

    public function countOrder()
    {
        return DB::table('orders')->where('user_id',Auth::id())->where('order_status','>',0)->count();
    }

    public function staticalCashStream()
    {
        $withdrawCount = CashStream::where('cash_type',CashStream::CASH_TYPE_WITHDRAW_AGREE)->where('user_id',$this->id)->count();
        $withdraw = CashStream::where('cash_type',CashStream::CASH_TYPE_WITHDRAW_AGREE)->where('user_id',$this->id)->sum('price');

        $directCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_DIRECT)->where('user_id',$this->id)->count();
        $direct = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_DIRECT)->where('user_id',$this->id)->sum('price');

        $indirectCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_INDIRECT)->where('user_id',$this->id)->count();
        $indirect = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_INDIRECT)->where('user_id',$this->id)->sum('price');

        $upCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_UP)->where('user_id',$this->id)->count();
        $up = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_UP)->where('user_id',$this->id)->sum('price');

        $superCount = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_SUPER)->where('user_id',$this->id)->count();
        $super = CashStream::where('cash_type',CashStream::CASH_TYPE_BENEFIT_SUPER)->where('user_id',$this->id)->sum('price');

        return ['withdraw'=>$withdraw,'withdrawCount'=>$withdrawCount,'direct'=>$direct,'directCount'=>$directCount,'indirect'=>$indirect,'indirectCount'=>$indirectCount,'up'=>$up,'upCount'=>$upCount,'super'=>$super,'superCount'=>$superCount];

    }

    public function getDefaultAddress()
    {

    }


    /**
     * 下级会员列表
     */
    public function subList()
    {
        $res = User::where('parent_id',$this->id)->orderBy('id','desc')->get();
        if(!$res)
        {
            $res = [];
        }

        return $res;
    }

    /**
     * 下级会员列表
     */
    public function subListCount()
    {
        return User::where('parent_id',$this->id)->orderBy('id','desc')->count();
    }


    /**
     * 下级会员列表
     */
    public function subDeepList()
    {
        $res = User::where('indirect_id',$this->id)->orderBy('id','desc')->get();
        if(!$res)
        {
            $res = [];
        }

        return $res;
    }

    /**
     * 下级会员列表
     */
    public function subDeepListCount()
    {
        return User::where('indirect_id',$this->id)->orderBy('id','desc')->count();
    }

    /**
     * 已经参加过活动啦
     */
    public function hasTakePartInActivity()
    {
        $order = Order::where('user_id',$this->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('pay_status',1)->first();
        return ($order instanceof  Order)?$order:false;
    }

    /**
     *
     */
    public function getHealthTagArray()
    {
        $str1 = '记忆下降,面颊泛红,眼痒,眼血丝,眼酸,眼胀,眼花,口干,口渴,唇裂,舌硬,口歪,口溃疡,舌溃疡,口味重,舌苔厚,地图舌,舌白点,舌质紫暗,锯齿,心悸,心律异常,胸闷,胸痛,胸胀,心绞痛,心跳快,心跳慢,心烦狂燥,眼斜,手足麻木,手足发青,手脚热,手脚凉,腿胀,腿血管瘤,腿浮肿,皮肤易青,皮肤脱皮,失眠多梦,易醒,唇白,唇青,唇麻,迟炖,懒言,结巴,头发稀,头发干,脱发,头疼,脑鸣,头晕,思维断电,偏头痛,晕车,晕机,冒冷汗,后背凉,夜盲症,眼疲劳,眼屎多,麦粒肿,眼仁黄,眉骨痛,眼干涩,眼飞影,视力模糊,无泪,眼怕光,流泪,眼圈黑,脂肪粒,起夜,尿频,尿急,耳内潮湿,耳内有脓,耳屎多,耳痛,耳痒,耳鸣,耳聋,听力下降,尿痛,脓尿味怪,睾丸肿块,疝气,肛门瘙痒,便血,性情急,易怒,抑郁症,无激情,手足抽搐,手抖,感冒时间长,形状消瘦,关节疼,关节肿,口苦,喉痒,右腹肝区闷痛,易落枕,脖子硬,右肩酸,右肩麻,右肩痛,皮肤痒,后背痘,乳头凹陷,乳头流脓,乳房肿块,乳房增生,头部怕冷,头麻,自汗,多汗,盗汗,皮肤干燥,打喷嚏,打鼾,嗓子干,鼻塞流涕,流血,牙龈出血,牙龈肿,嗅觉不灵,酒糟鼻,哮喘,鼻炎,咳嗽,痰多,痰黄,痰黑,痰白,痰血,支气管炎,声音嘶哑,脱肛,痔疮,口苦,口臭,口咸,口腥,脚气,脚臭,脚出水,低热37.3-38,不感冒,易感冒,便溏不净,便秘,颈部水牛背,叹气,气短喘,游走性脂肪粒,易打哈气,腰酸痛,脊椎僵硬疼痛,肩颈疼痛,面偏黑,眼袋黑,眼圈浮肿,口腔异味,牙齿松动,次数少,平衡差,易摔跤,静脉曲张,冻疮,四肢厥冷,四肢无力,脚后跟痛,指甲凹,指甲竖纹,指甲无半月痕,指甲半月痕少,个头矮,发育慢,白发,鬼剃头,体重突增10%,体重突减10%,不孕不育,眉毛脱,睫毛脱,淋巴肿大,嗜睡,失眠,头油腻,面油腻,面黄,长斑,痤疮,长痘,贫血,低血压,低血糖,手指长倒刺,磨牙,指甲断,打嗝,恶心,胃寒,胃胀,食欲差,易饱,消化不良,胃反酸,偏食,厌食,口甜果味,口味偏咸,食欲过旺,腹胀屁多,腹胀屁臭,大便不成形,身体异味,肥胖,将军肚,容易扭伤,过敏性鼻炎,过敏性咽炎,各种过敏,手心出汗,黑痣变大,黑痣变多,蜘蛛痣,皮肤红点,游走性疼痛';
        $str2 = '记忆下降,面颊泛红,眼痒,眼血丝,眼酸,眼胀,眼花,口干,口渴,唇裂,舌硬,口歪,口溃疡,舌溃疡,口味重,舌苔厚,地图舌,舌白点,舌质紫暗,锯齿,心悸,心律异常,胸闷,胸痛,胸胀,心绞痛,心跳快,心跳慢,心烦狂燥,眼斜,手足麻木,手足发青,手脚热,手脚凉,腿胀,腿血管瘤,腿浮肿,皮肤易青,皮肤脱皮,失眠多梦,易醒,唇白,唇青,唇麻,迟炖,懒言,结巴,头发稀,头发干,脱发,头疼,脑鸣,头晕,思维断电,偏头痛,晕车,晕机,冒冷汗,后背凉,夜盲症,眼疲劳,眼屎多,麦粒肿,眼仁黄,眉骨痛,眼干涩,眼飞影,视力模糊,无泪,眼怕光,流泪,眼圈黑,脂肪粒,起夜,尿频,尿急,耳内潮湿,耳内有脓,耳屎多,耳痛,耳痒,耳鸣,耳聋,听力下降,尿痛,脓尿味怪,肛门瘙痒,便血,性情急,易怒,抑郁症,无激情,手足抽搐,手抖,感冒时间长,形状消瘦,关节疼,关节肿,口苦,喉痒,右腹肝区闷痛,易落枕,脖子硬,右肩酸,右肩麻,右肩痛,皮肤痒,后背痘,乳头凹陷,乳头流脓,乳房肿块,乳房增生,头部怕冷,头麻,自汗,多汗,盗汗,皮肤干燥,打喷嚏,打鼾,嗓子干,鼻塞流涕,流血,牙龈出血,牙龈肿,嗅觉不灵,酒糟鼻,哮喘,鼻炎,咳嗽,痰多,痰黄,痰黑,痰白,痰血,支气管炎,声音嘶哑,脱肛,痔疮,口苦,口臭,口咸,口腥,脚气,脚臭,脚出水,低热37.3-38,不感冒,易感冒,便溏不净,便秘,颈部水牛背,叹气,气短喘,游走性脂肪粒,易打哈气,腰酸痛,脊椎僵硬疼痛,肩颈疼痛,面偏黑,眼袋黑,眼圈浮肿,口腔异味,牙齿松动,虫牙,咽干异物感,性欲低,尿床,多沫,尿不净,尿血,尿等待,浑浊,尿道口灼烧,尿道发炎,无小便,次数少,平衡差,易摔跤,静脉曲张,冻疮,四肢厥冷,四肢无力,脚后跟痛,指甲凹,指甲竖纹,指甲无半月痕,指甲半月痕少,个头矮,发育慢,白发,鬼剃头,体重突增10%,体重突减10%,不孕不育,眉毛脱,睫毛脱,淋巴肿大,嗜睡,失眠,头油腻,面油腻,面黄,长斑,痤疮,长痘,贫血,低血压,低血糖,手指长倒刺,磨牙,指甲断,打嗝,恶心,胃寒,胃胀,食欲差,易饱,消化不良,胃反酸,偏食,厌食,口甜果味,口味偏咸,食欲过旺,腹胀屁多,腹胀屁臭,大便不成形,身体异味,肥胖,将军肚,容易扭伤,过敏性鼻炎,过敏性咽炎,各种过敏,手心出汗,黑痣变大,黑痣变多,蜘蛛痣,皮肤红点,游走性疼痛,女性例假,月经量多,月经量少,月经提前,月经推后,经期急燥,月经胸胀,经期厌食,经期浮肿,小腹怕凉,白带色黄,白带腥臭,经期困倦,经期时间长,痛经,月经长痘,月红腰疼,小腹闷痛,经期头痛,产后脱发,产后抑郁,产后色斑,经期怕冷,阴道异味,阴道瘙痒,阴道血丝';

        if(substr($this->id_card,-2,1)%2==0){
            $str = $str2;
        }else{
            $str = $str1;
        }

        return explode(',',$str);
    }



    public static function makeActivityUser($arr)
    {
        $user = \App\Model\User::where('phone',$arr['phone'])->first();

        if($user instanceof \App\Model\User)
        {
            Logger::info('用户已存在' . $arr['phone'],'cz');

            if( $user->activity_pay )
            {
                Logger::info('用户已存在已支付活动订单' . $arr['phone'],'cz');
                return ['status'=>false,'desc'=>'用户已存在已支付活动订单'];
            }

            $user->activity_pay = 1;
            $user->save();
        } else
        {
            Logger::info('用户新建' . $arr['phone'],'cz');
            $user = new \App\Model\User();
            $user->phone = $arr['phone'];
            $user->vip_level = \App\Model\User::LEVEL_ACTIVITY;
            $user->origin_vip_level = \App\Model\User::LEVEL_ACTIVITY;

            $user->real_name = $arr['real_name'];
            $user->id_card = $arr['id_card'];
            $user->activity_pay = 1;
            $user->save();
        }


        //下单
        //没有订单则创建订单哟

        //判断是否有活动订单
        $order = Order::where('user_id',$user->id)->where('buy_type',Order::BUY_TYPE_ACTIVITY)->first();

        if( !$order instanceof  Order)
        {
            $order = new Order();
        }
        $order->user_id = $user->id;
        $order->buy_type = Order::BUY_TYPE_ACTIVITY;
        $order->need_pay = Product::find(2)->price;
        $order->immediate_user_id = $arr['immediate_id'];

        $order->pay_status = 1;
        $order->order_status = Order::ORDER_STATUS_DELIVERED;
        $order->quantity = 1;
//        $order->pay_type = CashStream::CASH_PAY_TYPE_WECHAT;
        $order->pay_time = date('Y-m-d H:i:s');
        $order->activity_by_admin = 1;
        $order->save();

        $user->activity_get_good = 1;
        $user->save();

        return ['status'=>true,'desc'=>'创建活动订单成功'];

    }

    /**
     * 获得活动支付的订单
     */
    public function getActivityPayedOrder()
    {
        return Order::where('buy_type',Order::BUY_TYPE_ACTIVITY)->where('user_id',$this->id)->where('pay_status',1)->first();
    }

    /**
     * 是否需要去退款
     */
    public function needToTurn()
    {
        $order = $this->getActivityPayedOrder();
        if( !($order instanceof  Order) )
        {
            return false;
        }

        $count = CashStream::where('cash_type',CashStream::CASH_TYPE_ACTIVITY_WITHDRAW)->whereNotIn('withdraw_deal_status',[2])->count();

        if( !$count )
        {
            return false;
        }

        return true;
    }
}