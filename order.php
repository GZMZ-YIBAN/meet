<?php
require('config.php');
require 'app_config.php';    
require_once('SUBMAILAutoload.php');
date_default_timezone_set('Asia/Shanghai'); 
//检测是否登录
 $verify_request = @$_COOKIE['verify_request'];
 $postStr = pack("H*", $verify_request);
 $postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, 'cd67615d613165d42f67401fe0342690', $postStr, MCRYPT_MODE_CBC, 'b19046a4baf4e76f');
 $postInfo = rtrim($postInfo);
 $postArr = json_decode($postInfo,true);
 if($postArr['visit_user']['username'] == false){
   die("<script>alert('请登录！');self.location = 'index.php?act=login';</script>");
 }
$mysqli = new mysqli($mysql_host,$mysql_user,$mysql_pwd,$mysql_db);
if($mysqli->connect_errno){ //连接成功errno应该为0
    die('Connect Error:'.$mysqli->connect_error);
}
$mysqli->set_charset('utf8');
if(@$_POST['submit'] == '提交'){
//	exit("<script>alert('系统临时进行维护,暂停申请');</script>");
	$department = $_POST['department'];
	$person = $_POST['person'];
	$tel_s = $_POST['tel'];
	//$email = $_POST['email'];
	$name = $_POST['name'];
	$room = $_POST['room'];
	$property = $_POST['property'];
	$date = $_POST['date'];
	//$reason = $_POST['reason'];
	$time = $_POST['start_hour']." - ".$_POST['end_hour'];
	$apparatus = '';
	foreach($_POST['apparatus'] as $val){
		if($apparatus == true){
			$sign = ',';
		}else{
			$sign = '';
		}
		$apparatus = $apparatus.$sign.$val;
	}
	//$remark = $_POST['remark'];
	$sql = "insert into `boardroom` (`department`,`room`,`person`,`tel`,`name`,`property`,`date`,`time`,`apparatus`,`ybuid`)values(?,?,?,?,?,?,?,?,?,?)";
	$mysqli_stmt=$mysqli->prepare($sql);
	//第一个参数表明变量类型，有i(int),d(double),s(string),b(blob)
	$mysqli_stmt->bind_param('ssssssssss',$department,$room,$person,$tel_s,$name,$property,$date,$time,$apparatus,$postArr['visit_user']['userid']);
	//执行预处理语句
	if($mysqli_stmt->execute()){
        $submail=new MESSAGEXsend($message_configs);    
        $submail->setTo($tel);
        $submail->SetProject('kdkxz1');
        $submail->AddVar('tissue',$department);
        $submail->AddVar('classroom',$room);
        $submail->AddVar('name',$person);
        $submail->AddVar('id',$mysqli->insert_id);   
        $submail->AddVar('time',$date.' '.$time.'点'); 
        $submail->AddVar('tel',$tel);
        $submail->AddVar('reason',$name);
        $xsend=$submail->xsend();  
        echo "<script>alert('预约信息已经提交，预约码：{$mysqli->insert_id}，审核成功后凭预约码获得会议室使用资格！');window.location.href='index.php';</script>";
       // print_r($xsend);
	}else{
	 	echo $mysqli_stmt->error;
	}
	//print_r($_POST);
}
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8"> 
	<title>会议室预约</title>
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
	input[type=submit]{
		text-align:center;
		font-weight:bold;
		font-size:1em;
		border-width:2;
		border-color:#000;
		border-radius:8px;
		width:100%;
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
	.div-img{
	    background-color: rgba(255, 255, 255, 0.3);
		display: block;
		padding: 10px;
	}
	.box-text {
		border: 1px solid white;
		color: blue;
		font-style: italic;
		width: 100%;
		height: 100%;
	}
	.title-text {
		font-size: 3.8em;
		padding-top: 20px;
		padding-bottom: 25px;
		text-align: center;
	}
	.btn_form{
		width:100%;
		margin-top:4px;
	}
	.alert {
		margin: 0 auto;
		margin-top: 5px;
		max-width: 700px;
		height: auto;
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
					会议室预约信息填写
				</div>	
			</div>
		<div class="panel-body">
			<div class="alert alert-info">
				<!--活动中需要的器材数目请填写在备注处（如<kbd>无线话筒*3</kbd> 若不注明<kbd>默认为1</kbd>），一行填写一项，如活动性质为其他类型请在备注中注明活动性质，--><u>请自行留意自己所选择的时间不要与其他组织冲突，否则可能无法申请成功</u>。
			</div>
			<br />
			<form class="form-horizontal" action="" method="POST">
				<div class="form-group">
					<label class="col-md-2 control-label">申请部门:</label>
					<div class="col-md-10">
						<input class="form-control" name="department" type="text" value="" required="required" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">申请场地:</label>
					<div class="col-md-10">
						<select class="form-control" name="room">
						<option value="2001学生学业辅导讲堂">2001学生学业辅导讲堂</option>
						<option value="3002学生活动策划室">3002学生活动策划室</option>
						<option value="3014素质拓展厅">3014素质拓展厅</option>
						<option value="1002会议室">1002会议室</option>
						<option value="1008会议室">1008会议室</option>
						<option value="1006会议室">1006会议室</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">负 责 人:</label>
					<div class="col-md-10">
						<input class="form-control" name="person" type="text" value="" required="required" >
					</div>
				</div>
				
				<!--<div class="form-group has-feedback">
					<label for="inputEmail3" class="col-md-2 control-label">电子邮箱:</label>
					<div class="col-md-10">
						<input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" required="required" >
					</div> 
				</div>-->
				
				<div class="form-group">
					<label class="col-md-2 control-label">联系电话:</label>
					<div class="col-md-10">
						<input class="form-control" name="tel" type="text" value="" required="required" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">活动名称及参加人数:</label>
					<div class="col-md-10">
						<input class="form-control" name="name" type="text" value="" required="required" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">活动性质:</label>
					<div class="col-md-10">
						<div class="radio">
							<label>
								<input type="radio" name="property" value="校级活动" checked>校级活动&emsp;
							</label>
							<label>
								<input type="radio" name="property" value="学院活动">学院活动&emsp;
							</label>
							<label>
								<input type="radio" name="property" value="学生组织活动">学生组织活动&emsp;
							</label>
							<label>
								<input type="radio" name="property" value="会议会务">会议会务&emsp;
							</label>
							<br />
							<label>
								<input type="radio" name="property" value="文娱活动">文娱活动&emsp;
							</label>
							<label>
								<input type="radio" name="property" value="学术讲座">学术讲座&emsp;
							</label>
							<label>
								<input type="radio" name="property" value="公益活动">公益活动&emsp;
							</label>
							<label>
								<input type="radio" name="property" value="其他">其他&emsp;
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">场地使用日期:</label>
					<div class="col-md-4">
						<select class="form-control" name="date">
							<option value="<?php echo date('Y-m-d');?>"><?php echo date('Y-m-d');?></option>
							<option value="<?php echo date('Y-m-d',strtotime("+1 day"));?>"><?php echo date('Y-m-d',strtotime("+1 day"));?></option>
							<option value="<?php echo date('Y-m-d',strtotime("+2 day"));?>"><?php echo date('Y-m-d',strtotime("+2 day"));?></option>
							
						</select>
					</div>
					<div class="col-md-3">
						<select class="form-control" name="start_hour">
							<option value="07:00">07:00点</option>
							<option value="08:00">08:00点</option>
							<option value="09:00">09:00点</option>
							<option value="10:00">10:00点</option>
							<option value="11:00">11:00点</option>
							<option value="12:00">12:00点</option>
							<option value="13:00">13:00点</option>
							<option value="14:00">14:00点</option>
							<option value="15:00">15:00点</option>
							<option value="16:00">16:00点</option>
							<option value="17:00">17:00点</option>
							<option value="18:00">18:00点</option>
							<option value="19:00">19:00点</option>
							<option value="20:00">20:00点</option>
							<option value="21:00">21:00点</option>
							<option value="22:00">22:00点</option>
							<option value="23:00">23:00点</option>
						</select>
					</div>
					<div class="col-md-3">
						<select class="form-control" name="end_hour">
							<option value="07:00">07:00点</option>
							<option value="08:00">08:00点</option>
							<option value="09:00">09:00点</option>
							<option value="10:00">10:00点</option>
							<option value="11:00">11:00点</option>
							<option value="12:00">12:00点</option>
							<option value="13:00">13:00点</option>
							<option value="14:00">14:00点</option>
							<option value="15:00">15:00点</option>
							<option value="16:00">16:00点</option>
							<option value="17:00">17:00点</option>
							<option value="18:00">18:00点</option>
							<option value="19:00">19:00点</option>
							<option value="20:00">20:00点</option>
							<option value="21:00">21:00点</option>
							<option value="22:00">22:00点</option>
							<option value="23:00">23:00点</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">活动所需器材:</label>
					<div class="col-md-10">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="apparatus[]" value="调音">调音&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="灯光">灯光&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="投影仪">投影仪&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="电脑">电脑&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="有线话筒">有线话筒&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="无线话筒">无线话筒&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="话筒架">话筒架&emsp;
							</label>
							<label>
								<input type="checkbox" name="apparatus[]" value="其他">其他&emsp;
							</label>
						</div>
					</div>
				</div>
				<!--
				<div class="form-group">
					<label class="col-md-2 control-label">申请理由:</label>
					<div class="col-md-10">
						<textarea name="reason" class="form-control" required="required" ></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">备注信息:</label>
					<div class="col-md-10">
						<textarea name="remark" class="form-control"></textarea>
					</div>
				</div>
				-->
				<div class="warning">
					<ol>
						<li><u>使用方应遵守国家法律和学校规定，不得用以开展有损学校或有碍社会公序良俗的活动；</u></li>
							
						<li><u>使用场地应服从管理，注意防火防盗、安全, 不在舞台以外的任何地方粘贴和布置装饰物,不擅自使用、挪动、更改、撤换中心设备设施,使用完毕，清除装饰物、垃圾，并归位相关设备设施；</u></li>
						<li><u>校级活动申请由承办单位审批，学院活动申请由学院党委副书记审批，学生组织活动由相关学生组织负责人审批；</u></li>
						
					</ol>
				</div>
				<div class="col-md-10 col-md-offset-2">
					<label>
						<center><strong>
						<input type="checkbox" name="" id="accept">&emsp;<font color="red">我确认我已阅读以上注意事项，并承诺遵守注意事项！</font>&emsp;
						</center></strong>
					</label>
				</div>
				
				<div class="form-group">
					 <div class="col-md-12">
						<div class="col-md-6"><input id="submit" type="submit" name="submit" class="btn btn_form btn-primary" value="提交" disabled="disabled"> </div>
						<div class="col-md-6"><input type="reset" class="btn btn_form btn-danger" value="重置"> </div>
					 </div>
				</div>	
			</form>
		</div>
		</div>
		
	
</div>
</body>
<script>
$(function(){
	$("#accept").change(function(){
		if($("#accept").is(':checked') == true ){
			$("#submit").removeAttr("disabled");
		}else{
			$("#submit").attr({"disabled":"disabled"});
		}
	});
});
</script>
</html>