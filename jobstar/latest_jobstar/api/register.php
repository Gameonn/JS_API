<?php
//this is an api to register users on the server

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
require_once('../PHPMailer_5.2.4/class.phpmailer.php');
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$email=$_REQUEST['email'];
$password=isset($_REQUEST['password']) && $_REQUEST['password'] ? $_REQUEST['password'] : '';
$apnid=$_REQUEST['apn_id']?$_REQUEST['apn_id']:"";


global $conn;

if(!($email && $password && $email!='null')){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
			
	$sth=$conn->prepare("SELECT * from users where email=:email");
	$sth->bindValue("email",$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($result)){
		$success="0";
		$msg="Email is already taken";
	}
	else{

	$sql="INSERT INTO `users` (`id`,`superuser`, `apn_id`, `first_name`, `last_name`, `email`, `password`, `city`, `country`, `gender`, `image`,`verification_code`, `is_deleted`, `created_on`, `updated_on`, `deleted_on`) VALUES (DEFAULT,0, :apnid, '', '',:email,:password, '', '', '', '','', 0, NOW(), NOW(),'');";
		$sth=$conn->prepare($sql);
		$sth->bindValue("apnid",$apnid);
		$sth->bindValue("email",$email);
		$sth->bindValue("password",$password);
		
		try{
		$sth->execute();
		$user_id=$conn->lastInsertId();
		$success='1';
		$msg="User Registered Successfully";
		}
		catch(Exception $e){}		
	}
	}	

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"user_id"=>$user_id));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>