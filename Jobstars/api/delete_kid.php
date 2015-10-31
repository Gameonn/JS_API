<?php
//this is an api to delete kid

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");


$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
//0-none, 1-monthly,2-yearly
$kid=$_REQUEST['kid_id'];
$uid=$_REQUEST['user_id'];

if(!($kid && $uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="delete from `kids` where id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$kid);
	try{$sth->execute();
	$success='1';
	$msg='Kid Deleted';
	$data = DataClass::get_all_kids_user($uid)? DataClass::get_all_kids_user($uid):[];
	}
	catch(Exception $e){}
	
	$sql="delete from `jobs` where kid_id=:kid";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	
	$sql="delete from `job_status` where kid_id=:kid";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	
	$sql="delete from `connect` where kid_id=:kid";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid',$kid);
	try{$sth->execute();}
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