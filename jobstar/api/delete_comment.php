<?php
//this is an api to delete comments

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
//0-none, 1-monthly,2-yearly
$cid=$_REQUEST['comment_id'];


if(!($cid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$success='1';
	$msg='Comment Deleted';

	$sql="delete from `comments` where id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$cid);
	try{$sth->execute();}
	catch(Exception $e){
	echo $e->getMessage();
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