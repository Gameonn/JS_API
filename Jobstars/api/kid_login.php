<?php
//this is an api to login users

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$name=$_REQUEST['name'];
$password=$_REQUEST['password'];
$user_id=$_REQUEST['user_id'];
if(!($name && $password && $user_id )){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$data = DataClass::get_kid_profile($name,$password,$user_id)? DataClass::get_kid_profile($name,$password,$user_id):[];
	$kid_id= $data[0]['kid_id'];
	$bonus_count= DataClass::get_bonus_job_count($kid_id)? DataClass::get_bonus_job_count($kid_id):0;
	if($data){
	$success='1';
	$msg="Login Successful";
	
	}
	else{
	$success='0';
	$msg="Wrong User Credentials";
	}

}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data,"bonus_job_count"=>$bonus_count));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>