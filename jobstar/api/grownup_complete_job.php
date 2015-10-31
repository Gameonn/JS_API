<?php
//this is an api to complete/incomplete job by grownup

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once('DataClass.php');
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
$date=date('Y-m-d');
$day_name=date("l");
$uid=$_REQUEST['user_id'];
$job_id=$_REQUEST['job_id'];
$kid_id=$_REQUEST['kid_id'];
$day=$_REQUEST['day']?$_REQUEST['day']:$day_name;
$week_parameter=$_REQUEST['week'];
if(!($job_id && $kid_id && $day)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	//select * from `job_status` where job_id=167 and day='Monday' and kid_id=142 and DATE_FORMAT(created_on,'%Y-%m-%d')=CURDATE()
	
	$sql="SELECT * from `job_status` where job_id=:id and day=:day and kid_id=:kid_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$approved_status=$res[0]['is_approved'];
	
	$is_approved=$approved_status?0:1;
	
	$WhereClause="AND DATE_FORMAT(job_status.created_on,'%Y-%m-%d') BETWEEN (UTC_DATE()-INTERVAL 1 DAY) AND (UTC_DATE())";
	
	//set particular job as complete/incomplete for the day
	$sql="UPDATE `job_status` set is_approved=:is_approved where job_id=:id AND day=:day AND kid_id=:kid_id $WhereClause";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);	
	$sth->bindValue('is_approved',$is_approved);
	try{$sth->execute();
	$success='1';
	$msg='Job Approved';
	
	if($week_parameter==1)
	$data = DataClass::get_week_jobs($uid,2)? DataClass::get_week_jobs($uid,2):[];
	else
	$data = DataClass::get_day_jobs($uid,2)? DataClass::get_day_jobs($uid,2):[];
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