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

$kid_id=$_REQUEST['kid_id'];
$flag=$_REQUEST['flag']?$_REQUEST['flag']:2;

global $conn;

if(!($kid_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
	
	$counts=DataClass::get_bonus_counts($kid_id,$flag)?DataClass::get_bonus_counts($kid_id,$flag):[];
	if($counts){
	$success='1';
	$msg="Kids Job Details";
	}
	else{
	$success='0';
	$msg="No data found for this kid";
	}	
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=="1"){
echo json_encode(array("success"=>$success,"msg"=>$msg,"extra_counts"=>$counts));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>