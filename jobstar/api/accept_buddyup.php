<?php
//this is an api to accept connection

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

$uid=$_REQUEST['user_id'];
$kid=$_REQUEST['kid_id'];
$code=$_REQUEST['code'];

if(!($uid && $kid && $code)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="select * from users where id=:id and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){

	$sql="select * from friends where kid_id2=:kid and buddy_key=:code";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid",$kid);
	$sth->bindValue("code",$code);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	$sql="update friends set status=1 where kid_id2=:kid and buddy_key=:code";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid",$kid);
	$sth->bindValue("code",$code);
	try{$sth->execute();
	$success='1';
	$msg="Status Updated";
	}
	catch(Exception $e){}
	
	$data=DataClass::get_buddy_details($kid,$code)?DataClass::get_buddy_details($kid,$code):[];
	
	}
	else{
	$success='0';
	$msg="No Result Found";
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