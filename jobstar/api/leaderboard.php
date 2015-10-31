<?php
//this is an api to show job leaderboard
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$flag=$_REQUEST['flag']?$_REQUEST['flag']:2;
//flag values  1- prev week leader board and 2- current week leader board


	//fetching kids leader board details ordering by position(maximum completed jobs on top)
	$data = DataClass::get_leaderboard($flag)? DataClass::get_leaderboard($flag):[];
	
	
	if($data){
	$success='1';
	$msg="Kids Jobs Leaderboard Details";
	}
	else{
	$success='0';
	$msg="No data found";
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