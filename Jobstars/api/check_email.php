<?php
//this is an api to check email

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


global $conn;

if(!($email && $email!='null')){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
			
	$sth=$conn->prepare("select * from users where email=:email");
	$sth->bindValue("email",$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($result)){
		$success="0";
		$msg="Email is already taken";
	}
	else{	
	$success="1";
	$msg="New Email";
	}	
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+

echo json_encode(array("success"=>$success,"msg"=>$msg));
?>