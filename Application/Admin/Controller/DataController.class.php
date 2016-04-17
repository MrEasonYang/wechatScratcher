<?php
namespace Admin\Controller;
use Think\Controller;
class DataController extends Controller {
    public function getexcel () {
        $username = session('wechat_username');
        $key = session('wechat_key');
        if ($key == md5($username)) {
            $databaseOfWinner = M('winner');
            $data = $databaseOfWinner->select();
            import("Org.Util.PHPExcel");
            import("Org.Util.PHPExcel.Writer.Excel5");
            import("Org.Util.PHPExcel.IOFactory.php");
            $fileName = "winner";
            $HeadArr = array("序号","openId","appKey","姓名","电话","地址","邮编","收件人","奖品");
            $this->excel($fileName,$HeadArr,$data);
        } else {
            $this->error('您尚未登陆，即将跳转至登陆页',U('Admin/Index/index'));
        }
    }

    private function excel($fileName,$headArr,$data){
        //对数据进行检验
        if(empty($data) || !is_array($data)){
            $this->error('暂无数据');
            return 0;
        }
        //检查文件名
        if(empty($fileName)){
            exit;
        }
        $date = date("Y-m-d-H-i-s");
        $fileName .= "_{$date}.xls";
        
        //创建PHPExcel对象
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        \PHPExcel_Cell::stringFromColumnIndex(0);
        \PHPExcel_Cell::columnIndexFromString('AA');
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        $cacheSettings = array( 'memoryCacheSize ' => '64MB');
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            
        //设置表头
        $key = ord("A");
        foreach($headArr as $v){
            $column = chr($key);
            if ($key > 90) {
                $keyNow = $key - 26;
                $column = chr($keyNow);
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue('A'.$column.'1', $v);
                $key ++;
            } else {
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($column.'1', $v);
                $key ++;
            }
        }
            
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        $row = 2;
        $key = ord("A");
        foreach ($data as $k => $firstValue) {
            foreach ($firstValue as $secondValue) {
                $column = chr($key);
                if ($key > 90) {
                    $keyNow = $key - 26;
                    $column = chr($keyNow);
                    $objActSheet->setCellValue('A'.$column.$row, $secondValue);
                    $key ++;
                } else {
                    $objActSheet->setCellValue($column.$row, $secondValue);
                    $key ++;
                }
            }
            $key = ord("A");
            $row++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        // $objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
}