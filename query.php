<?php
//error_reporting(0);
require('config.php');
require 'app_config.php';    
require_once('SUBMAILAutoload.php');
//引入数据导出类
require_once('./class/export.php');
date_default_timezone_set('Asia/Shanghai'); 
$verify_request = @$_COOKIE['verify_request'];
$postStr = pack("H*", $verify_request);
$postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, 'cd67615d613165d42f67401fe0342690', $postStr, MCRYPT_MODE_CBC, 'b19046a4baf4e76f');
$postInfo = rtrim($postInfo);
$postArr = json_decode($postInfo,true);
if($postArr['visit_user']['username'] == false){
   die("<script>alert('请登录！');self.location = 'index.php?act=login';</script>");
 }
$id = @$_GET['id'];
if(!is_numeric($id) && $id != false){
	exit("error");
}
$mysqli = new mysqli($mysql_host,$mysql_user,$mysql_pwd,$mysql_db);
if($mysqli->connect_errno){ //连接成功errno应该为0
    die('Connect Error:'.$mysqli->connect_error);
}
$mysqli->set_charset('utf8');
$query = $mysqli->query('select * from boardroom where id='.$id);
if($query == true){
	$row = $query->fetch_assoc();
	if(@$_GET['action'] =="down"){
  exportExcel('[会议室]'.$id.date(Ymdhis),$row['department'],$row['room'],$row['person'],$row['tel'],$row['name'],$row['property'],$row['date'],$row['time'],$row['apparatus']);
 }
	if(!$row){
		echo "<script>alert('未查询到数据');</script>";
	}
}
?>

<head>
	<meta charset="utf-8"> 
	<title>会议室在线预约</title>
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
	p{
		text-indent:2em;
	}
	td,th{
		text-align:center;
	}
	th{
		background-color:#fff;
	}
	.select{
		width:100%;
		margin-bottom:10px;
		text-align:center;
		font-weight:bold;
		font-size:1em;
		border: 1px solid #000;
		border-radius:8px;
		background-color:#fff;
		height:32px;
	}
	.panel-primary>.panel-heading{
		color:#fff;
		background-color: #00a65a;
		border-color:#00a65a;
	}
	.navbar-inverse .navbar-nav >li>a:focus, .navbar-inverse .navbar-nav>li>a:hover{
		background-color:transparent;
		color: #fff;
	}
	.navbar-inverse{
		background-color:#3c8dbc;
		border-color:#3c8dbc;
		color: #fff;
	}
	.navbar-inverse .navbar-nav>li>a{
		color:#fff;
	}
	.navbar-inverse .navbar-toggle:focus, .navbar-inverse .navbar-toggle:hover{
		background-color: #3c8dbc;
	}
	.navbar-inverse .navbar-toggle {
    	border-color: #fff;
	}
	   .col-md-8,.col-md-4{
		padding-right:5px;
		padding-left:5px;
	}
	.navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:focus, .navbar-inverse .navbar-nav>.open>a:hover {
		color: #fff;
		background-color: rgba(40, 98, 130, 0.32);
	}
	.navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover {
		color: #fff;
		background-color: rgba(40, 98, 130, 0.32);
	}
	.navbar-inverse .navbar-nav .open .dropdown-menu>li>a {
		color: #c0dcd8;
	}

	*{
		font-family: 微软雅黑;
	}
	.panel{
		margin:0 auto; 
		max-width:700px;
	}
	.alert{
		margin:0 auto;
		margin-top:5px;
		max-width:700px;
		height:80px;
	}

	</style>
</head>
<body scrollTop="0">
<nav class="navbar navbar-inverse">
		<div class="navbar-header">
			<a href="index.php" class="navbar-brand">
			<img src="./meet.png"  height="24px" width="24px">
			</a>
			<button class="navbar-toggle collapsed" data-toggle="collapse"  data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
		</div>
		<div id="mynavbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="index.php"><span class="glyphicon glyphicon-calendar"></span>&nbsp;首页</a></li>
				<li><a href="order.php"><span class="glyphicon glyphicon-edit"></span>&nbsp;申请预约</a></li>
              	<li><a href="query.php"><span class="glyphicon glyphicon-search"></span>&nbsp;预约查询</a></li>
			</ul>
			<!-- 导航条中的下拉菜单 -->
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>  &nbsp;<?php echo $postArr['visit_user']['username'].'(uid:'.$postArr['visit_user']['userid'].')';?></a>
				</li>
			</ul>
		</div>
</nav>
	<div class="container">	
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					预约码查询
				</div>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="" method="GET">
					<div class="form-group has-warning">
						<label class="col-md-2 control-label">预约码:</label>
						<div class="col-md-8">
							<input class="form-control" name="id" type="text" placeholder="请输入你的预约码">
						</div>
						<label class="col-md-2"><input type="submit" class="btn btn-primary btn_form" value="查询"></label>
					</div>
				</form>
			</div>
		</div>
      <br />
	 <div class="panel panel-info">
	     <div class="panel-heading">
	         <div class="panel-title">
	             活动中心室内场地申请表
	         </div>
	     </div>
	     <table class="table table-striped table-hover table-bordered">
	     <tr>
	         <th>申请部门</th>
	         <td colspan="2"><?php echo $row['department'] ;?></td>
	         <th>会议室</th>
	         <td colspan="2"><?php echo $row['room'] ;?></td>
	     </tr>
	     <tr>
	         <th>负责人</th>
	         <td colspan="2"><?php echo $row['person'] ;?></td>
	         <th>联系电话</th>
	         <td colspan="2"><?php echo $row['tel'] ;?></td>
	     </tr>
	     <tr>
	         <th>活动名称</th>
	         <td colspan="2"><?php echo $row['name'] ;?></td>
	         <th>活动性质</th>
	         <td colspan="2"><?php echo $row['property'] ;?></td>
	     </tr>
      <tr>
      <th>活动时间</th>
	     <td colspan="5"><?php echo $row['date'].' '.$row['time'] ;?></td>
      </tr>
	     <tr>
	         <th>活动器材</th>
	         <td colspan="3"><?php echo $row['apparatus'] ;?></td>
	         <th>审核状态</th>
				<?php
                   if($row){
                     if($row['status'] == -1){
                              echo "<td><span class=\"label label-warning\">等待处理</span></td>";
                          }elseif($row['status'] == 0){
                              echo "<td><span class=\"label label-danger\">审核失败</span></td>";
                          }else{
                              echo "<td><span class=\"label label-success\">通过审核</span></td>";
                          }
                   }else{
                          echo "<td></td>";
                   }
        
                   
				?>
	     </tr>
	     <tr>
	         <th>导出数据</th>
	         <td align="center"><?php if($row['department'] == true){ echo "<a href=\"?action=down&id={$id}\" class=\"btn btn-info\">导出本条数据表</a>";}else{ echo "<font color=\"red\">请先查询</font>"; }?></td>
	     </tr>
	     </table>
	 </div>
</div>
</body>
</html>