<?php
//this is an api to get bonus job counts for current week

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

global $conn;

if(!($kid_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
	
	$counts=DataClass::get_bonus_job_count($kid_id)?DataClass::get_bonus_job_count($kid_id):[];
	if($counts){
	$success='1';
	$msg="Kids Bonus Job count";
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
echo json_encode(array("success"=>$success,"msg"=>$msg,"bonus_count"=>$counts));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>