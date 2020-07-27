<?php
function exportExcel($filename,$department,$room,$person,$tel,$name,$property,$date,$time,$apparatus){  
    require_once 'PHPExcel/IOFactory.php';  
    require_once 'PHPExcel.php';  
    require_once 'PHPExcel/Writer/Excel2007.php';  
      
    $template = '/www/wwwroot/app.rainss.cn/meet/class/Template.xls';          //使用模板  
    $objPHPExcel = PHPExcel_IOFactory::load($template);     //加载excel文件,设置模板  
      
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);  //设置保存版本格式  
      
    //写入数据到表格  
    $objActSheet = $objPHPExcel->getActiveSheet();  
    $objActSheet->setCellValue('B2',$department);
    $objActSheet->setCellValue('D2',$person);
    $objActSheet->setCellValue('F2',$tel);
    $objActSheet->setCellValue('B3',$name);
    $objActSheet->setCellValue('B4',$property);
    $objActSheet->setCellValue('B5',$room);
    $objActSheet->setCellValue('B6',$date);
    $objActSheet->setCellValue('E6',$time);
    $objActSheet->setCellValue('B7',$apparatus);
         
    // 保存至本地Excel表格  
    //$objWriter->save($filename.'.xls');   
    header("Pragma: public");  
    header("Expires: 0");  
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");  
    header("Content-Type:application/force-download");  
    header("Content-Type:application/vnd.ms-execl");  
    header("Content-Type:application/octet-stream");  
    header("Content-Type:application/download");;  
    header('Content-Disposition:attachment;filename="'.$filename.'.xls"');  
    header("Content-Transfer-Encoding:binary");
    $objWriter->save('php://output');  
} 

?>