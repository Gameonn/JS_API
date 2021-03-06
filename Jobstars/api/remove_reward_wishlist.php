<?php
//this is an api to add connection
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$reward_id=$_REQUEST['reward_id'];
$kid=$_REQUEST['kid_id'];


if(!($reward_id && $kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{


	$sql="select * from kids_wishlist where kid_id=:kid_id and reward_id=:reward_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('reward_id',$reward_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(!count($res)){
	
	$success='0';
	$msg="No such reward in wishlist";
	
	}
	else{
	
	$sql="DELETE  FROM `kids_wishlist` where kid_id=:kid_id and reward_id=:reward_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('reward_id',$reward_id);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();
	$success='1';
	$msg="Reward removed from wishlist";
	
	}
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