<?php
//this is an api to validate users

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$email=$_REQUEST['email'];
$code=$_REQUEST['code'];

if(!($email && $code)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="select * from users where email=:email and verification_code=:code";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	$sth->bindValue('code',$code);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	$success='1';
	$msg="Code Verified";
	}
	else{
	$success='0';
	$msg="Invalid Code";
	}

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