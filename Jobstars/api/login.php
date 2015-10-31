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

$email=$_REQUEST['email'];
$password=$_REQUEST['password'];
$apn_id=$_REQUEST['apn_id']?$_REQUEST['apn_id']:"";

if(!($email && $password && $email!='null')){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$data = DataClass::get_profile($email,$password)? DataClass::get_profile($email,$password):[];
	if($data){
	$success='1';
	$msg="Login Successful";
	/*if($apn_id)
	$sql="update users set apn_id=:apn_id where email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	if($apn_id) $sth->bindValue('apn_id',$apn_id);
	
	try{$sth->execute();}
	catch(Exception $e){} */
	}
	else{
	$success='0';
	$msg="Invalid Email or Password";
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