<?php
//this is an api to complete/incomplete job by kid

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
$kid_id=$_REQUEST['kid_id'];
$day=$_REQUEST['day'];

if(!($job_id && $kid_id && $day)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	
	
	$sql="select * from `job_status` where job_id=:id and day=:day and kid_id=:kid_id and DATE_FORMAT(created_on,'%Y-%m-%d')=CURDATE()";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	$complete_status=$res[0]['is_completed'];
	
	$is_complete=$complete_status?0:1;
	
	//set particular job as complete/incomplete for the day
	$sql="update `job_status` set is_completed=:complete_status where job_id=:id and day=:day and kid_id=:kid_id";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);	
	$sth->bindValue('complete_status',$is_complete);
	try{$sth->execute();
	$success='1';
	$msg='Job Completed';
	$data = DataClass::get_week_jobs($uid,2)? DataClass::get_week_jobs($uid,2):[];
	}
	catch(Exception $e){}
	
	}
	else{
	
	$dayname=gmdate('l');
	$sql="INSERT INTO `job_status` (`id`, `job_id`, `kid_id`,`day`,`is_completed`,`is_approved`, `created_on`) VALUES (DEFAULT, :job_id, :kid_id,:day,1,0,UTC_TIMESTAMP())";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('job_id',$job_id);
	$sth->bindValue('day',$dayname);
	$sth->bindValue('kid_id',$kid_id);
	try{$sth->execute();
	$success='1';
	$msg="Job Completed";
	
	$data = DataClass::get_week_jobs($uid,2)? DataClass::get_week_jobs($uid,2):[];
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