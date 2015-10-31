<?php
//this is an api to update user access rights based on a kid

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


//ACCESS RIGHTS 
//0-read only access, 1-full access right
$kid=$_REQUEST['kid_id'];
$user_id=$_REQUEST['user_id']?$_REQUEST['user_id']:"";
$access=$_REQUEST['access'];

if(!($kid && $user_id && $access)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="update `grownup_alias` set access=:access where kid_id=:kid_id and user_id=:user_id";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('access',$access);
	try{$sth->execute();
	$success='1';
	$msg='Rights enabled to grownup';
	}
	catch(Exception $e){}

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