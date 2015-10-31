<?php
//this is an api to get linked users

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


if(!($kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	
	$path=BASE_PATH."uploads/";
	$sql="SELECT users.id,users.first_name,users.last_name,users.email,users.city,users.country,users.gender,concat('$path',users.image) as image,grownup_alias.access,grownup_alias.alias FROM `users` JOIN grownup_alias ON grownup_alias.user_id=users.id WHERE grownup_alias.kid_id=:kid_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($res)){
	$success='1';
	$msg="Linked Users";
	$data=$res;
	}
	else{
	$success='0';
	$msg="No linked user found";
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