<?php
//this is an api to update user setting parameters

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
error_reporting(E_ALL);
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$uid=$_REQUEST['user_id'];
$daily_progress_email=$_REQUEST['daily_progress_email']?$_REQUEST['daily_progress_email']:0;
$weekly_progress_email=$_REQUEST['weekly_progress_email']?$_REQUEST['weekly_progress_email']:0;
$weekly_raffle_email=$_REQUEST['weekly_raffle_email']?$_REQUEST['weekly_raffle_email']:0;
$pending_job_email=$_REQUEST['pending_job_email']?$_REQUEST['pending_job_email']:0;
$pending_job_notification=$_REQUEST['pending_job_notification']?$_REQUEST['pending_job_notification']:0;
$buddy_email=$_REQUEST['buddy_email']?$_REQUEST['buddy_email']:0;
$connection_email=$_REQUEST['connection_email']?$_REQUEST['connection_email']:0;


if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="select * from user_setting where user_id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){
	
	$sql="update user_setting set daily_progress_email=:daily_progress_email,weekly_progress_email=:weekly_progress_email,weekly_raffle_email=:weekly_raffle_email,pending_job_notification=:pending_job_notification,pending_job_email=:pending_job_email,buddy_email=:buddy_email,connection_email=:connection_email where user_id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('daily_progress_email',$daily_progress_email);
	$sth->bindValue('weekly_progress_email',$weekly_progress_email);
	$sth->bindValue('weekly_raffle_email',$weekly_raffle_email);
	$sth->bindValue('pending_job_email',$pending_job_email);
	$sth->bindValue('pending_job_notification',$pending_job_notification);
	$sth->bindValue('buddy_email',$buddy_email);
	$sth->bindValue('connection_email',$connection_email);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();
	$success='1';
	$msg="Settings Updated Successfully";
	}
	catch(Exception $e){}
	
	}
	else{
	$sql="INSERT INTO `user_setting` (`id`, `user_id`, `daily_progress_email`, `weekly_progress_email`, `weekly_raffle_email`, `pending_job_notification`, `pending_job_email`, `buddy_email`, `connection_email`, `created_on`) VALUES (DEFAULT, :user_id, :daily_progress_email,:weekly_progress_email,:weekly_raffle_email, :pending_job_notification, :pending_job_email,:buddy_email,:connection_email,NOW())";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('daily_progress_email',$daily_progress_email);
	$sth->bindValue('weekly_progress_email',$weekly_progress_email);
	$sth->bindValue('weekly_raffle_email',$weekly_raffle_email);
	$sth->bindValue('pending_job_notification',$pending_job_notification);
	$sth->bindValue('pending_job_email',$pending_job_email);
	$sth->bindValue('buddy_email',$buddy_email);
	$sth->bindValue('connection_email',$connection_email);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();
	$success='1';
	$msg="Settings Updated Successfully";
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