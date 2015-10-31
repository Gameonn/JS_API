<?php
//this is an api to get user job details on the server

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
error_reporting(0);
$uid=$_REQUEST['user_id'];
$flag=$_REQUEST['flag']?$_REQUEST['flag']:2;

//1-prev 2- current 3- next  4-this month  5- this year  6-all time

global $conn;

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
	
	$data = DataClass::get_week_jobs($uid,$flag)? DataClass::get_week_jobs($uid,$flag):[];
	
	/*$kids=DataClass::get_kids_by_user($uid);
	
	if($kids){
	foreach($kids as $val){
	$data['extra_counts'][]=DataClass::get_bonus_counts($kid_id,$flag)?DataClass::get_bonus_counts($kid_id,$flag):[];
	}
	}*/

	if($data){
	$success='1';
	$msg="User Job Details";
	}
	else{
	$success='0';
	$msg="No data found for this user";
	}	
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=="1"){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>