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
//$code=$_REQUEST['code'];
$myid = $_REQUEST['my_id'];

if(!($uid && $myid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="SELECT * from users where id=:id and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){
	
	/*	$sql="select * from connect where user_id2=:uid and code=:code";
	$sth=$conn->prepare($sql);
	$sth->bindValue("uid",$uid);
	$sth->bindValue("code",$code);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();  */
	
	$sql="select * from connect where user_id2=:uid and user_id1=:my_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("uid",$uid);
	$sth->bindValue("my_id",$myid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	$success='1';
	$msg="Result Found";
	
	$status = $result[0]['status'];
	
	/*
	$sql="update connect set status=1 where user_id2=:uid and code=:code";
	$sth=$conn->prepare($sql);
	$sth->bindValue("uid",$uid);
	$sth->bindValue("code",$code);
	try{$sth->execute();}
	catch(Exception $e){}
	*/
	if($result[0]['status']=='1'){
		$sql="update connect set status=0 where user_id2=:uid and user_id1=:my_id";
		$sth=$conn->prepare($sql);
		$sth->bindValue("uid",$uid);
		$sth->bindValue("my_id",$myid);
		try{$sth->execute();}
		catch(Exception $e){}
	}else{
		$sql="update connect set status=1 where user_id2=:uid and user_id1=:my_id";
		$sth=$conn->prepare($sql);
		$sth->bindValue("uid",$uid);
		$sth->bindValue("my_id",$myid);
		try{$sth->execute();}
		catch(Exception $e){}
	}
	
	
	$data=DataClass::get_details($uid,$code);
	
	if($data){
	$success='1';
	$msg="Result Found";
	}
	else{
	$success='0';
	$msg="No Result Found";
	}
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
