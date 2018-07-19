<?php
namespace App\Model;
use App\Util\Kit;
use Illuminate\Support\Facades\DB;

class SyncModel{


    static private function nameGetCallback($code,$callback){
        $res = $callback();
        foreach($res as $item){
            if($item->ITEMNO == $code){
                return $item->ITEMNAME;
            }
        }
        return '';
    }

    static private  function selectedCallBack($name,$value,$callback){
        $defaultAttr = array(
            'name'=>'',
            'class'=>'sync-select'
        );

        if(is_array($name)) {
            $config = array_merge($defaultAttr, $name);
        }else{
            $defaultAttr['name'] = $name;
            $config = $defaultAttr;
        }

        $res = $callback();
        $str = sprintf('<select name="%s" class="%s" %s>',$config['name'],$config['class'],isset($config['id'])?'id="' . $config['id'] . '"':'');

        if(isset($config['defaultNull']) && ($config['defaultNull']= true) || !$value){
            $str .= '<option value="">--请选择--</option>';
        }

        foreach($res as $item){
            $str .= sprintf('<option value="%s" %s>%s</option>',$item->ITEMNO,(($item->ITEMNO == $value) && ($value !== false))?'selected':'',$item->ITEMNAME);
        }
        $str .= "</select>";
        return $str;
    }



    static public function productAttr($name,$value = false,$useParam = [])
    {
        return self::selectedCallBack($name,$value,function() use ($useParam){
            return Product::getProductAttrsConfig($useParam);
        });
    }

    static public function selfGetAddress($name,$value = false)
    {
        return self::selectedCallBack($name,$value,function(){
            return UserAddress::selfGetAddressConfig();
        });
    }

    public static function mineAddress($name,$value = false,$useParam = [])
    {
        return self::selectedCallBack($name,$value,function() use ($useParam){
            return UserAddress::mineAddressConfig($useParam);
        });
    }



    /**
     * @param $name
     * @param bool $value
     * @return string
     */
    static public function deliverType($name,$value = false){
        return self::selectedCallBack($name,$value,function(){
            return Deliver::deliverTypeConfig();
        });
    }


    static public function pctnameByCode($code)
    {
        $res = DB::table('code_library')->where('ITEMNO',$code)->first();
        return Kit::issetThenReturn($res,'ITEMNAME');
    }


//    static public function areaCodeToCity(){
//        $res = SyncCodeLibraryModel::areaCodeToCity();
//
//        $firstLevel = array();
//        $secondeLevel = array();
//        foreach($res as $item){
//            if(($item->ITEMNO%10000) == 0){
//                //array_push($firstLevel,array('name'=>$item->ITEMNAME,'value'=>$item->ITEMNO,'data'=>array()));
//                array_push($firstLevel,array('name'=>$item->ITEMNAME,'id'=>$item->ITEMNO,'child'=>array()));
//            }
//        }
//
//        foreach($res as $item){
//            if(($item->ITEMNO%10000) == 0){
//                continue;
//            }
//            $parentValue = intval($item->ITEMNO/10000) * 10000;
//            foreach($firstLevel as $key=>$val){
//                /*
//                if($val['value'] == $parentValue){
//                    array_push($firstLevel[$key]['data'],array('name'=>str_replace($val['name'],'',$item->ITEMNAME),'val'=>$item->ITEMNO));
//                    break;
//                }
//                */
//                if($val['id'] == $parentValue){
//                    array_push($firstLevel[$key]['child'],array('name'=>str_replace($val['name'],'',$item->ITEMNAME),'id'=>$item->ITEMNO));
//                    break;
//                }
//            }
//        }
//
//        //return $firstLevel;
//        return array("data"=>$firstLevel);
//    }
//
//
//    static public function areaCodeToTown(){
//        $res = SyncCodeLibraryModel::areaCodeToTown();
//
//        $firstLevel = array();
//        foreach($res as $item){
//            if(($item->ITEMNO%10000) == 0){
//                //省放进去
//                array_push($firstLevel,array('name'=>$item->ITEMNAME,'id'=>$item->ITEMNO,'child'=>array()));
//            }
//        }
//
//        foreach($res as $item){
//            if(($item->ITEMNO%10000) == 0){
//                continue;
//            }
//
//            if(($item->ITEMNO%100) != 0){
//                continue;
//            }
//            $parentValue = intval($item->ITEMNO/10000) * 10000;
//            foreach($firstLevel as $key=>$val){
//                if($val['id'] == $parentValue){
//                    //市
//                    array_push($firstLevel[$key]['child'],array('name'=>str_replace($val['name'],'',$item->ITEMNAME),'id'=>$item->ITEMNO));
//                    break;
//                }
//            }
//        }
//
//        foreach($res as $item){
//            if(($item->ITEMNO%10000) == 0){
//                continue;
//            }
//            if(($item->ITEMNO%100) == 0){
//                continue;
//            }
//            $parentValue = intval($item->ITEMNO/100) * 100;
//
//            foreach($firstLevel as $key=>$val){
//                if(count($val['child'])>0){
//                    foreach($val['child'] as $keycity=>$city){
//                        if($parentValue == $city['id']){
//                            //县
//                            $firstLevel[$key]['child'][$keycity]['child'][] = array('name'=>$item->ITEMNAME,
//                                'id'=>$item->ITEMNO);
//                            break;
//                        }
//                    }
//                }
//            }
//        }
//        return array("data"=>$firstLevel);
//    }
}