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
$uid=$_REQUEST['user_id'];
$kid_id=$_REQUEST['kid_id'];
$user_date=$_REQUEST['user_date']?$_REQUEST['user_date']:$date;
$day=date("l");
$flag=$_REQUEST['flag']?$_REQUEST['flag']:1;

//1-day 2-week

if(!($kid_id )){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	//select * from `job_status` where job_id=167 and day='Monday' and kid_id=142 and DATE_FORMAT(created_on,'%Y-%m-%d')=UTC_DATE()
	$sql="select count(job_status.id) as is_approved_status from `job_status` where day=:day and kid_id=:kid_id and is_approved=0";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$complete_status=$res[0]['is_approved_status'];
	
	$is_complete=$complete_status?1:0;
	
	//bonus job clause
	$BonusjobClause="AND jobs.bonus_job=1 AND DATE(job_status.created_on) BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY) AND 
					(SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY)) ";

	/*
	
	$sql="update `job_status` set is_approved=1,day=:day where kid_id=:kid_id $BonusjobClause ";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid_id);	
	$sth->bindValue('day',$day);	
	try{$sth->execute();}
	catch(Exception $e){}	
	
	*/
	
	
	
	//if($flag==2)
	$WhereClause="AND DATE_FORMAT(job_status.created_on,'%Y-%m-%d') BETWEEN (UTC_DATE()-INTERVAL 1 DAY) AND (UTC_DATE())";
	//else
	//$WhereClause="AND day={$day} and Date_format(created_on,'%Y-%m-%d')= UTC_DATE()";
	
	
	//set jobs as complete/incomplete for the day(multiple jobs)
	$sql="update `job_status` set is_approved=:complete_status where kid_id=:kid_id $WhereClause ";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid_id);	
	$sth->bindValue('complete_status',$is_complete);
	try{
	$sth->execute();
	$success='1';
	$msg='Job Approved';
	$data = DataClass::get_week_jobs($uid,2)? DataClass::get_week_jobs($uid,2):[];
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
