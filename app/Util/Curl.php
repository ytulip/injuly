<?php
namespace App\Util;
use App\Log\Facades\Logger;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Curl {
    static public $curlopt_proxy = false;

    const CURL_CODE_ORIGIN = 'origin';

    public function __construct()
    {

    }


    /**
     * 通过curl去请求一个下载地址
     * @param $url
     */
    public function down($url,$fileHandle){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        }

        //$outfile = fopen('test2.txt','wb');  //保存到本地文件的文件名
        //curl_setopt($ch,CURLOPT_FILE,$outfile);

        curl_setopt($ch,CURLOPT_FILE,$fileHandle);
        $rtn = curl_exec($ch);

        if(!curl_errno($ch)){
            curl_close($ch);
            return true;
        }else{
            curl_close($ch);
            return false;
        }

    }

    static public function getHttpServer($withProtocol = false) {
        if (App::environment('product')) {
            return 'https://www.jieqianme.com';
        } else {
            if ($withProtocol) {
                if(isset($_SERVER['HTTP_HOST'])){
                    return 'http://' . $_SERVER['HTTP_HOST'];
                }
                return 'http://j1i2e3q4i5a6n7m8e9t0e1s2t.jieqianme.cn/';
            } else {
                return $_SERVER['HTTP_HOST'];
            }
        }
    }

    /**
     * 为安硕的application/x-www-form-urlencoded请求单独构造的post请求
     */
    static public function curlPostForAs($url,$postData){
        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);

        self::setProxy($ch);

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        }

        //设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //添加post类型
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData) );
        //执行请求，获得回复
        $r = curl_exec($ch);
        return $r;
    }

    /*
   * curl发送post请求接收返回的数据但不输出
   * 第一个形参变量是请求地址url,其类型为字符串
   * 第二个形参变量是通过post方式提交的数据,其类型为数组
   * 返回请求处理的结果,其类型为对象数组
   * @param encode 把编码装换成utf8
   */

    static public function curlPostRaw($url, $postDate, $type = false, $code = 'origin',$encode = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: text/plain'));

        self::setProxy($ch);

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }

        //设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //添加post类型
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDate);
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK'));
            $r = iconv($check, 'UTF-8', $r);
        }
        curl_close($ch);
        switch ($code) {
            case 'json' :
                if ($type) {
                    return json_decode($r, true);
                }
                return json_decode($r);
                break;
            case 'origin' :
                return $r;
                break;
        }
        return null;
    }

    /*
     * curl发送post请求接收返回的数据但不输出
     * 第一个形参变量是请求地址url,其类型为字符串
     * 第二个形参变量是通过post方式提交的数据,其类型为数组
     * 返回请求处理的结果,其类型为对象数组
     * @param encode 把编码装换成utf8
     */

    static public function curlPost($url, $postDate, $type = false, $code = 'origin',$encode = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        self::setProxy($ch);

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }

        //设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //添加post类型
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDate);
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK'));
            $r = iconv($check, 'UTF-8', $r);
        }
        curl_close($ch);
        switch ($code) {
            case 'json' :
                if ($type) {
                    return json_decode($r, true);
                }
                return json_decode($r);
                break;
            case 'origin' :
                return $r;
                break;
        }
        return null;
    }

    /**
     * CAE请求
     * @param $url
     * @param $postDate
     * @param bool $type
     * @param string $code
     * @param bool $encode
     * @return mixed|null|string
     */
    static public function curlPostForCAE($url, $postDate, $type = false, $code = 'origin',$encode = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));

        self::setProxy($ch);

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }

        //设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //添加post类型
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDate);
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK'));
            $r = iconv($check, 'UTF-8', $r);
        }
        curl_close($ch);
        switch ($code) {
            case 'json' :
                if ($type) {
                    return json_decode($r, true);
                }
                return json_decode($r);
                break;
            case 'origin' :
                return $r;
                break;
        }
        return null;
    }


    static public function curlPostWithCookie($url, $postDate, $type = false, $code = 'origin',$encode = false) {
        $cookie_jar = storage_path()."/pic.cookie";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if(env('CURL_PROXY')){

        }

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }


        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);

        //设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //添加post类型
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDate);
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK'));
            $r = iconv($check, 'UTF-8', $r);
        }
        curl_close($ch);
        switch ($code) {
            case 'json' :
                if ($type) {
                    return json_decode($r, true);
                }
                return json_decode($r);
                break;
            case 'origin' :
                return $r;
                break;
        }
        return null;
    }

    /*
     * curl发送post请求接收返回的数据但不输出
     * param url
     * param array('key1'=>'value1','key2'=>'value2',...)
     * @param bool $encode 编码装换成utf8
     */

    static public function curlGet($url, $getDate, $type = false, $code = 'json',$encode = false) {
        $ch = curl_init();
        if (!empty($getDate) && is_array($getDate)) {
            $i = 0;
            foreach ($getDate as $k => $v) {
                ++$i;
                if ($i == 1) {
                    $url = ($url . '?' . $k . '=' . $v);
                } else {
                    $url .= ('&' . $k . '=' . $v);
                }
            }
        }
        Logger::info('api路径为:'.$url);
        Logger::info('api路径为:'.$url,'an');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,30); //超时

        self::setProxy($ch);

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        }
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK', 'UTF-8'));
            $r = iconv($check, 'UTF-8', $r);
        }
		$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
        curl_close($ch);

        switch ($code) {
            case 'json' :
                if($type)
                {
                    return json_decode($r,true);
                }
                return json_decode($r);
                break;
            case 'origin' :
				if($httpCode ==404){
					return false;
				}
                return $r;
                break;
        }
        return null;
    }

    static public function curlMultiPost($urlArr, $code = 'json') {

    }

    /**
     * curlMultiGet的请求
     * @param $urlArr
     * @param string $code
     * @param boolean $type
     */
    static public function curlMultiGet($urlArr, $code = 'json', $type = false) {
        $mh = curl_multi_init();

        foreach ($urlArr as $i => $url) {
            $conn[$i] = curl_init($url);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
            //设置返回do.php页面输出内容
            curl_multi_add_handle($mh, $conn[$i]);
            //添加线程
        }

        $active = null;

        //do{$n=curl_multi_exec($mh,$active);}while($active);//会造成CPU过高

        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        while ($active and $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach ($urlArr as $i => $url) {
            switch ($code) {
                case 'json' :
                    if ($type) {
                        //return json_decode($r, true);
                        $res[$i] = json_decode(curl_multi_getcontent($conn[$i]), true);
                    } else {
                        $res[$i] = json_decode(curl_multi_getcontent($conn[$i]));
                    }
                    break;
                case 'origin' :
                    $res[$i] = curl_multi_getcontent($conn[$i]);
                    break;
            }
            curl_close($conn[$i]);
        }
        return $res;
    }


    /**
     * @param $ch
     */
    static public function setProxy(&$ch){
        if(env('CURL_PROXY')){
            curl_setopt($ch,CURLOPT_PROXY,config('extension.proxy_server'));
            curl_setopt($ch,CURLOPT_PROXYPORT,config('extension.proxy_server_port'));
        }
    }

}

