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
global $conn;

$day = date('w');
$week_start = date('Y-m-d', strtotime('-'.$day.' days +8 days' ));
$week_end = date('Y-m-d', strtotime('+'.(13-$day).' days'));

for($date = strtotime($week_start); $date <= strtotime($week_end); $date = strtotime("+1 day", $date)){
		echo date('Y-m-d',$date)." ";
		echo date("l", $date)." ";
}

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
	
	$data = DataClass::get_jobs_data($uid)? DataClass::get_jobs_data($uid):[];
	//$data = DataClass::get_week_jobs($uid,6)? DataClass::get_week_jobs($uid,6):[];
	
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