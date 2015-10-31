<?php
//this is an api to remove buddyup
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$kid1=$_REQUEST['kid_id1'];
$kid2=$_REQUEST['kid_id2'];


if(!($kid1 && $kid2)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{


	$sql="SELECT * from friends WHERE kid_id1 IN (:kid1,:kid2) and kid_id2 IN (:kid1,:kid2)";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid1",$kid1);
	$sth->bindValue("kid2",$kid2);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(!count($result)){
	
	$success='0';
	$msg="No friend found";
	}
	else{
	
	$sql="DELETE  FROM `friends` WHERE kid_id1 IN (:kid1,:kid2) and kid_id2 IN (:kid1,:kid2)";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid1",$kid1);
	$sth->bindValue("kid2",$kid2);
	try{$sth->execute();
	$success='1';
	$msg="Unfriend Success";
	$data=DataClass::get_buddy_list($kid1)?DataClass::get_buddy_list($kid1):[];
	}
	catch(Exception $e){}
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