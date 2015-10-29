<?php
//this is an api to get user rewards on the server

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


	$data = DataClass::get_rewards()? DataClass::get_rewards():[];
	
	
	if($data){
	$success='1';
	$msg="Rewards Details";
	}
	else{
	$success='0';
	$msg="No data found";
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