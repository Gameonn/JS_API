<?php
//this is an api to add comments

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");


$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$jid=$_REQUEST['job_id'];
$uid=$_REQUEST['user_id'];
$comment=$_REQUEST['comment'];

if(!($uid && $jid && $comment)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="INSERT INTO `comments` (`id`, `user_id`, `job_id`,`comment`, `created_on`) VALUES (DEFAULT, :user_id, :job_id,:comment,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('comment',$comment);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('job_id',$jid);
	try{$sth->execute();
	$success='1';
	$msg='Comment Added';
	$all_comments=DataClass::get_comments_by_jobid($jid);
	}
	catch(Exception $e){
	//echo $e->getMessage();
	}
	
	}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$all_comments));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>