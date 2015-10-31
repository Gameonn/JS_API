<?php
//this is an api to update email address

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


$password=$_REQUEST['password'];
$uid=$_REQUEST['user_id'];



if(!($uid && $password )){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	
	$sql="update users set password=:password where id=:uid";
	$sth=$conn->prepare($sql);
	$sth->bindValue('password',$password);
	$sth->bindValue('uid',$uid);
	try{$sth->execute();
	$success='1';
	$msg="Passcode Changed";
	}
	catch(Exception $e){}

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