<?php
//this is an api to add connection
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once('../PHPMailer_5.2.4/class.phpmailer.php');
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$uid=$_REQUEST['user_id'];

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="select users.email,connect.*,date_format(connect.update_on,'%Y-%m-%d') as update_date from connect join users on users.id=connect.user_id2 where user_id2=:user_id and status=0";
	$sth=$conn->prepare($sql);
	
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$connect=$sth->fetchAll(PDO::FETCH_ASSOC);
	$date2=date('Y-m-d');
	$date2=date_create($date2);
	if(count($connect)){
	foreach($connect as $row){
	$date1=$row['update_date'];
	$date1=date_create($date1);
	$cid=$row['id'];
	$diff=date_diff($date1,$date2);
	
	$c_date=$diff->format("%R%a days");
	$email=$row['email'];
	if($c_date>=4){
	$rnd_code= DataClass::generateRandomString(12);
	$sql="update `connect` set code=:code,update_on=NOW() where id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('code',$rnd_code);
	$sth->bindValue('id',$cid);
	try{$sth->execute();
	$msg1='Connection Code Updated For Jobstars.';
	DataClass::sendEmail($email,$msg1,$rnd_code,SMTP_EMAIL);
	}
	catch(Exception $e){}
	}
	}
	}
	
	$sql="select connect.user_id1,connect.user_id2,connect.kid_id,connect.code from connect where user_id2=:user_id and status=0";
	$sth=$conn->prepare($sql);
	
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$connect1=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($connect1)){
	$success="1";
	$msg="Records Found";
	$data=$connect1;
	}
	else{
	$success="0";
	$msg="No Result Found";
	}
	
	}


// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>