<?php
//this is an api to go premium

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


//PLAN 
//0-none, 1-monthly,2-yearly
$kid=$_REQUEST['kid_id'];
$plan=$_REQUEST['plan'];


if(!($kid && $plan)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="select * from kids where kid_id=:kid_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){
	
	$sql="update kids set plan=:plan where id=:id";
	$sth->bindValue('plan',$plan);
	$sth->bindValue('id',$kid);
	try{$sth->execute();
	$success='1';
	$msg="Kids premium plan updated";
	}
	catch(Exception $e){}
	}
	else{
	$success='0';
	$msg="Invalid Details";
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