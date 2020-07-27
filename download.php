<?php
/*
 * 数据导出
*/
//导入文件
require_once './config.php';
require_once './class/export.php';
//mysqli连接
$mysqli = new mysqli($mysql_host,$mysql_user,$mysql_pwd,$mysql_db);
if($mysqli->connect_errno){
    die('Connect Error:'.$mysqli->connect_error);
}
//设置编码
$mysqli->set_charset('utf8');
//导出所有数据
if(@$_GET['type'] == 'all'){
  $query = $mysqli->query('select `id`,`room`,`type`,`desc`,`tel`,`RepairPerson`,`SubmitTime` from dorm_order where state = 1');
  $list = $query->fetch_all(MYSQLI_ASSOC);
  exportExcel($list,"[所有维修订单]".date('YmdHis')."",array('id','room','type','desc','tel','RepairPerson','SubmitTime'));
}
?>