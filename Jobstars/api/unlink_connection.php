<?php
//this is an api to unlink connection -only parent of kid can unlink connection

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
$kid=$_REQUEST['kid_id'];

if(!($uid && $uid2 && $kid)){
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
	
	if(count($result)){
	$success='1';
	$msg="Connection Removed";
	$rnd_code= DataClass::generateRandomString(12);
	
	$sql="DELETE from connect where user_id2 IN (:uid2,:uid) and user_id1 IN (:uid,:uid2) and kid_id=:kid_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("uid",$uid);
	$sth->bindValue("uid2",$uid2);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	
	$data= DataClass::get_connect_data($uid)? DataClass::get_connect_data($uid):[];
		/*$sql="update grownup_alias set access=0 where user_id=:uid and kid_id IN (select connect.kid_id from connect where user_id2=:uid2 and user_id1=:uid UNION
			select connect.kid_id from connect where user_id2=:uid and user_id1=:uid2)";
		$sth=$conn->prepare($sql);
		$sth->bindValue("uid",$uid);
		$sth->bindValue("uid2",$uid2);
		try{$sth->execute();}
		catch(Exception $e){}*/
		//$data=DataClass::get_details($uid,$code);
		
		/*if($data){
		$success='1';
		$msg="Result Found";
		}
		else{
		$success='0';
		$msg="No Result Found";
		}*/
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