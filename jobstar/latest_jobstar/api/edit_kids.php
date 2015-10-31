<?php
//this is an api to update kids info

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
//random file name generator
function randomFileNameGenerator($prefix){
	$r=substr(str_replace(".","",uniqid($prefix,true)),0,20);
	if(file_exists("../uploads/$r")) randomFileNameGenerator($prefix);
	else return $r;
}

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
//0-none, 1-monthly,2-yearly
$alias=$_REQUEST['alias']?$_REQUEST['alias']:"";
$dob=$_REQUEST['dob']?$_REQUEST['dob']:"";
$gender=$_REQUEST['gender']?$_REQUEST['gender']:"";
$city=$_REQUEST['city']?$_REQUEST['city']:"";
$country=$_REQUEST['country']?$_REQUEST['country']:"";
$name=$_REQUEST['name'];
$id=$_REQUEST['kid_id'];
$uid=$_REQUEST['user_id'];

if(!($id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="update `kids` set name=:name,dob=:dob, gender=:gender,city=:city,country=:country where id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('name',$name);
	$sth->bindValue('dob',$dob);
	$sth->bindValue('gender',$gender);
	$sth->bindValue('city',$city);
	$sth->bindValue('country',$country);
	$sth->bindValue('id',$id);
	try{$sth->execute();
	$success='1';
	$msg='Kid Info Updated';
	}
	catch(Exception $e){}
	
	$sql="select * from grownup_alias where kid_id=:kid_id and user_id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$id);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){
	
	$sql="UPDATE `grownup_alias` set alias=:alias where kid_id=:kid_id and user_id=:user_id";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$id);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('alias',$alias);
	try{$sth->execute();}
	catch(Exception $e){}
	}
	else{
	$sql="INSERT INTO `grownup_alias` (`id`, `kid_id`, `user_id`, `alias`, `access`, `status`) VALUES (DEFAULT,:kid_id, :user_id, :alias,0,1)";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$id);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('alias',$alias);
	try{$sth->execute();}
	catch(Exception $e){}
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