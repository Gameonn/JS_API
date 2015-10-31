<?php
//this is an api to get all kids badge details on the server

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$kid=$_REQUEST['kid_id'];

global $conn;

if(!($kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 
	
	$data = DataClass::getBadge($kid)? DataClass::getBadge($kid):[];

	if($data){
	$success='1';
	$msg="Kid Badge Details";
	}
	else{
	$success='0';
	$msg="No data found for this kid";
	}	
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=="1"){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>