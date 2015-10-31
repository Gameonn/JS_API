<?php
//this is an api to fetch connected friends 

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

$kid_id=$_REQUEST['kid_id'];

if(!($kid_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="select * from kids where id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$kid_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){

	$data=DataClass::get_connected_friends($user_id)?DataClass::get_connected_friends($user_id):[];
	if($data){
	$success='1';
	$msg='Records Found';
	}
	else{
	$success='0';
	$msg="No Records Found";
	}
	}
	else{
	$success='0';
	$msg="Invalid User";
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