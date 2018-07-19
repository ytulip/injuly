<?php
namespace App\Util;
use App\Util\OSS\OssCommon;
use Symfony\Component\HttpKernel\Tests\DataCollector\DumpDataCollectorTest;

/**
 * 下载excel
 * Class DownloadExcel
 */
class DownloadExcel{

    static public function setInit(){
        set_time_limit(env('download_time_limit',3600));
        ini_set('memory_limit', env('download_memory_limit','1024M'));
    }

//    /**
//     * 保存CSV文件
//     * @param Array $data
//     */
//    static public function publicStorageCSV(Array $data){
//
//    }

    /**
     * 公用的下载excel的方法
     * @param Array $data
     */
    static public function publicDownloadExcel(Array $data,$forceFormat = true){
        ob_clean();
        //创建对象
        $objPHPExcel = new \PHPExcel();
        //设置属性
        $objPHPExcel->getProperties()->setCreator("lms")
            ->setTitle("lms");
        //创建当前活动工作表对象
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('B')->setWidth(20);//改变此处设置的长度数值



        //设置标题
        $i = 1;
        foreach($data['title'] as $key=>$val){
            $index = $key + 1;
            $objActSheet->setCellValue(self::getColumnByNum($index) . $i,$val);
        }

        //设置内容
        foreach($data['data'] as $item){
            $i++;
            foreach($item as $key=>$val){
                $index = $key + 1;
                if( $forceFormat || (isset($data['format_text_array']) && in_array($index,$data['format_text_array']))){
                    $objActSheet->setCellValueExplicit(self::getColumnByNum($index) . $i,$val,\PHPExcel_Cell_DataType::TYPE_STRING);
                }else{
                    $objActSheet->setCellValue(self::getColumnByNum($index) . $i,$val);
                }
            }
        }


        $saveName = $data['name'].'.xlsx';
        //保存文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Pragma:public');
        header('Content-Type:application/x-msexecl;name="'.$saveName.'"');
        header("Content-Disposition:inline;filename=\"$saveName\"");
        $objWriter->save('php://output');
    }


    //phpexcel操作设置列宽
    static public function getColumnByNum($index){
        switch($index){
            case 1:
                return 'A';
            case 2:
                return 'B';
            case 3:
                return 'C';
            case 4:
                return 'D';
            case 5:
                return 'E';
            case 6:
                return 'F';
            case 7:
                return 'G';
            case 8:
                return 'H';
            case 9:
                return 'I';
            case 10:
                return 'J';
            case 11:
                return 'K';
            case 12:
                return 'L';
            case 13:
                return 'M';
            case 14:
                return 'N';
            case 15:
                return 'O';
            case 16:
                return 'P';
            case 17:
                return 'Q';
            case 18:
                return 'R';
            case 19:
                return 'S';
            case 20:
                return 'T';
            case 21:
                return 'U';
            case 22:
                return 'V';
            case 23:
                return 'W';
            case 24:
                return 'X';
            case 25:
                return 'Y';
            case 26:
                return 'Z';
            case 27:
                return 'AA';
            case 28:
                return 'AB';
            case 29:
                return 'AC';
            case 30:
                return 'AD';
            case 31:
                return 'AE';
            case 32:
                return 'AF';
            case 33:
                return 'AG';
            case 34:
                return 'AH';
            case 35:
                return 'AI';
            case 36:
                return 'AJ';
            case 37:
                return 'AK';
            case 38:
                return 'AL';
            case 39:
                return 'AM';
        }
    }


    static public function createTitleExcel($title,$filename){
        ob_clean();
        //创建对象
        $objPHPExcel = new \PHPExcel();
        //设置属性
        $objPHPExcel->getProperties()->setCreator("zhangliang")
            ->setTitle("baiqian_stsr");
        //创建当前活动工作表对象
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('B')->setWidth(20);//改变此处设置的长度数值

        foreach($title as $key=>$val){
            $index = $key + 1;
            $objActSheet->setCellValue(self::getColumnByNum($index) . 1,$val);
        }

        //保存文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(storage_path() . '/download/' . $filename);
    }

    static public function continueWrite($data,$filename,$offset){
        $objReader = \PHPExcel_IOFactory::load(storage_path() . '/download/' . $filename);
        $objActSheet = $objReader->getActiveSheet();

        $i = $offset + 1;
        foreach($data as $item){
            $i++;
            foreach($item as $key=>$val){
                $index = $key + 1;
                $objActSheet->setCellValue(self::getColumnByNum($index) . $i,$val);
            }
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objReader, 'Excel2007');
        $objWriter->save(storage_path() . '/download/' . $filename);

        return $objWriter;
    }
}