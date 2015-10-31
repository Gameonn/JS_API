<?php
//this is an api to add connection
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
require_once('../PHPMailer_5.2.4/class.phpmailer.php');

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$email=$_REQUEST['email'];
$uid=$_REQUEST['user_id'];
$kid=(array)$_REQUEST['kid_id'];


//if kid_id is -1 then all kids for that user are selected for connection else only specified kid is connected

if(!($uid && $email && $kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$code = DataClass::generateRandomString(12);
	$sql="select * from users where email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$uid2=$res[0]['id'];
	if(count($res)){
	
	if($uid==$uid2){
	$success='0';
	$msg="Both Users are same";
	}
	else{
	//fetching all kids of that user
	if($kid[0]==-1){
	$kid=DataClass::get_kids_New($uid,$uid2);
	}
	
	foreach($kid as $val){
	
	$sql="select * from connect where user_id1=:user_id1 and user_id2=:user_id2 and kid_id=:kid_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id1',$uid);
	$sth->bindValue('user_id2',$uid2);
	$sth->bindValue('kid_id',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$res[$key]=$sth->fetchAll();
	
	if(!count($res[$key])){
	
	$sql="INSERT INTO `connect` (`id`, `user_id1`, `user_id2`,`kid_id`,`code`,`status`,`is_deleted`,`created_on`,`update_on`) VALUES (DEFAULT, :user_id1, :user_id2, :kid_id, :code, 0,0,NOW(),NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('code',$code);
	$sth->bindValue('user_id1',$uid);
	$sth->bindValue('user_id2',$uid2);
	$sth->bindValue('kid_id',$val);
	try{$c=$sth->execute();}
	catch(Exception $e){}
	}
	}
	
	if($c){
	$success='1';
	$msg='Connection Code sent.';
	$msg1="Connection Code for Jobstars";
	$msg2="Connection Code for Jobstars ".$code;
	DataClass::sendEmail($email,$msg1,$msg2,SMTP_EMAIL);
	}
	else{
	$success='0';
	$msg="Already Connected";
	}
	}
	}
	else{
	$success='0';
	$msg="Invalid Email";
	}
	}


// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
/*if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else*/
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>