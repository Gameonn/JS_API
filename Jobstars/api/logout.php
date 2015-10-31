<?php
//this is an api to logout users
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

$success=$msg="0";$data=array();

// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$uid=$_REQUEST['user_id'];

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
}
else{
	$sql="select id from users where id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("id",$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();

	if(count($result)){
		$sql="update users set apn_id='' where id=:id";
		$sth=$conn->prepare($sql);
		$sth->bindValue("id",$uid);
		try{$sth->execute();
		$success="1";
		$msg="Logout Successful";
		}
		catch(Exception $e){}
	}
	else{
		$success="0";
		$msg="Invalid user";
	}
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
/*if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else*/
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>