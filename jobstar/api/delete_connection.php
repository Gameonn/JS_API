<?php
//this is an api to delete connection

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once('DataClass.php');
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$kid=$_REQUEST['kid_id'];
$uid1=$_REQUEST['user_id1'];// parent id
$uid2=$_REQUEST['user_id2'];// connected grownup id

if(!($uid1 && $uid2 && $kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="delete from `grownup_alias` where kid_id=:kid and user_id=:user_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid',$kid);
	$sth->bindValue('user_id',$uid2);
	try{$sth->execute();}
	catch(Exception $e){}
	
	$sql="delete from `connect` where user_id2=:uid2 and user_id1=:uid1 and kid_id=:kid_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('uid1',$uid1);
	$sth->bindValue('uid2',$uid2);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();
	$success='1';
	$msg='Connection Deleted';
	$data= DataClass::get_connect_data($uid1)? DataClass::get_connect_data($uid1):[];
	}
	catch(Exception $e){}
	

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