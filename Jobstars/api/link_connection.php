<?php
//this is an api to link connection

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

$uid=$_REQUEST['user_id1'];
$uid2=$_REQUEST['user_id2'];

if(!($uid && $uid2)){
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

	$sql="select * from connect where user_id2=:uid2 and user_id1=:uid 
		  UNION
		  select * from connect where user_id2=:uid and user_id1=:uid2";
	$sth=$conn->prepare($sql);
	$sth->bindValue("uid",$uid);
	$sth->bindValue("uid2",$uid2);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	$status=$result[0]['status'];
	
	$stat=$status?0:1;
		if(count($result)){
		$success='1';
		$msg="Success";
		$rnd_code= DataClass::generateRandomString(12);
		$sql="update connect set status=:status where user_id2 IN (:uid2,:uid) and user_id1 IN (:uid,:uid2)";
		$sth=$conn->prepare($sql);
		$sth->bindValue("uid",$uid);
		$sth->bindValue("uid2",$uid2);
		$sth->bindValue('status',$stat);
		try{$sth->execute();}
		catch(Exception $e){}

		}
		else{
		$success='0';
		$msg="No Result Found";
		}
	}
	else{
	$success='0';
	$msg="Invalid user";
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