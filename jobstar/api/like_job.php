<?php
//this is an api to like jobs

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once('DataClass.php');
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$uid=$_REQUEST['user_id'];
$job_id=$_REQUEST['job_id'];


if(!($uid && $job_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="select * from likes where job_id=:job_id and user_id=:user_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('job_id',$job_id);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	$success='1';
	$msg="Already Liked";
	}
	else{
	$sql="INSERT into likes values(DEFAULT,:job_id,:user_id,1,'like',0,NOW())";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('job_id',$job_id);
	try{$sth->execute();
	$success='1';
	$msg='Liked Job';
	$data=DataClass::get_likes_by_jobid($job_id);
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