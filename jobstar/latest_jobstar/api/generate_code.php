<?php
//this is an api to generate_code

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
$uid=$_REQUEST['user_id'];

if(!($kid && $uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="select * from buddyup where kid_id=:kid_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	$success='1';
	$msg="Buddy Code For Kid";
	$code=$result[0]['buddy_code'];
	}
	else{
	$success='1';
	$msg="Buddy Code For Kid";
	$code=DataClass::generateBuddyCode(8);
	
	$sql="INSERT INTO `buddyup` (`id`, `user_id`, `kid_id`,`buddy_code`,`created_on`) VALUES (DEFAULT, :user_id, :kid_id,:buddy_code,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('buddy_code',$code);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	
	
	}
	
}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"buddy_code"=>$code));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>