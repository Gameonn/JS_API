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
$kid2=$_REQUEST['kid_id'];
$code=$_REQUEST['code'];
$grownup=$_REQUEST['grownup']?$_REQUEST['grownup']:0;// return all grownups linked else all buddies

if(!($uid && $kid2 && $code)){
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
	$res=$sth->fetchAll(PDO::FETCH_ASSOC);
	if(count($res)){
	//$kid1=DataClass::getkidId($code,$uid);
	$kid1=DataClass::getkidIdFromCode($code);
	
	if($kid1!=$kid2){
	if($kid1){
	
	$sql="SELECT * from friends where kid_id1 IN (:kid1,:kid2) and kid_id2 IN (:kid1,:kid2)";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid1",$kid1);
	$sth->bindValue("kid2",$kid2);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	$success='1';
	$msg="Already Connected";
	if($grownup)
		$data=DataClass::getLinkedGrownups($kid2)?DataClass::getLinkedGrownups($kid2):[];
	else
		$data=DataClass::get_buddy_list($kid2)?DataClass::get_buddy_list($kid2):[];
	
	}
	else{
	
	$sql="INSERT into friends(id,kid_id1,kid_id2,status,created_on) VALUES(DEFAULT,:kid1,:kid2,1,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid1",$kid1);
	$sth->bindValue("kid2",$kid2);
	try{$sth->execute();
	$success='1';
	$msg="Kids Connected";
	
	DataClass::AddBadge($kid1,'friend');
	DataClass::AddBadge($kid2,'friend');
	
		if($grownup)
		$data=DataClass::getLinkedGrownups($kid2)?DataClass::getLinkedGrownups($kid2):[];
		else
		$data=DataClass::get_buddy_list($kid2)?DataClass::get_buddy_list($kid2):[];
	}
	catch(Exception $e){}
	}
	
	}
	else{
	
	$success='0';
	$msg="Wrong Buddy Code";
	}
	}
	else{
	$success='0';
	$msg="Same kids can not be friends";
		
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