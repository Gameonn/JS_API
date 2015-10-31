<?php
//this is an api to remove job from history

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");


$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$uid=$_REQUEST['user_id'];
$kid=$_REQUEST['kid_id'];
$job_id=$_REQUEST['job_id'];

		
if(!($uid && $kid && $job_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	
	$sql="UPDATE `jobs` set history_job=0 WHERE kid_id=:kid_id AND id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('id',$job_id);
	try{$sth->execute();
	
	$success='1';
	$msg='Job Removed from History';
	$data = DataClass::get_history_jobs($uid)? DataClass::get_history_jobs($uid):[];
	}
	catch(Exception $e){}
	
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