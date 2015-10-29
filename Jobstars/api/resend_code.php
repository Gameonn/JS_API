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

global $conn;

if(!($email && $email!='null')){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
			
	$success="1";
	$v_code = DataClass::generateRandomString();
	
	$msg="Verification Code Sent";
	$message="Verification Code for Jobstars";
	
	$body_mail="<div style='font-size:16x;line-height:1.4;'>
				<p>Thank you for registering on the JobStars App!</p>
				<p>Please enter the following verification code to complete the registration process: <b>".$v_code. "</b></p>
				<p>Thanks and Enjoy!</p>
				<p>The JobStars Team </p><br>
				<p> ------------------------------------------------------------------------ </p>
				<p>Check out tutorials at www.jobstars.rocks/tutorials</p>
				
				<p>Email us for help at support@jobstars.rocks</p>
				</div>";
	
	DataClass::sendEmail($email,$message,$body_mail,SMTP_EMAIL);
	
	/*$msg="Verification Code Sent";
	$message="Verification Code";
	$txt="Verification Code for Jobstars ".$v_code;
	DataClass::sendEmail($email,$message,$txt,SMTP_EMAIL);*/
	 
	
}	

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$v_code));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>