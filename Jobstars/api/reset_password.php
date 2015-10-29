<?php
//this is an api to recover password
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once "../php_include/db_connection.php"; 
require_once('../PHPMailer_5.2.4/class.phpmailer.php');

$success=$msg="0";$data=array();

// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
$pwd=$_REQUEST['current_password'];
$email=$_REQUEST['email'];
$new_pwd=$_REQUEST['new_password'];
if(!($pwd && $new_pwd && $email)){
	$success="0";
	$msg="Incomplete Parameters";
}
else{
	$sql="select * from users where email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue("email",$email);
	try{$sth->execute();}catch(Exception $e){}
	$res=$sth->fetchAll();
	
		if(count($res)){
		$success='1';
		$msg="Password updated";
		$sth=$conn->prepare("update users set password=:password where email=:email and is_deleted=0");
		$sth->bindValue('password',$new_pwd);
		$sth->bindValue('email',$email);
		try{$sth->execute();}
		catch(Exception $e){}
		
		}
		else{
			$success="0";
			$msg="Invalid Email";
		}
	}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>