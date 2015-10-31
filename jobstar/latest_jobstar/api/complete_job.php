<?php
//this is an api to complete/incomplete job by kid

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+

require_once("../php_include/db_connection.php");
require_once("DataClass.php");
require_once ('../easyapns/apns.php');
require_once('../easyapns/classes/class_DbConnect.php');
$db = new DbConnect('localhost', 'root', 'core2duo', 'codebrew_jobstar');
$success=$msg="0";$data=array();

// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$uid=$_REQUEST['user_id'];
$job_id=$_REQUEST['job_id'];
$kid_id=$_REQUEST['kid_id'];
$day=$_REQUEST['day'];
$week_parameter=$_REQUEST['week'];

if(!($uid && $job_id && $kid_id && $day)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	
	$sql="SELECT * from users WHERE id=:user_id and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$apnid=$res[0]['apn_id'];
	
	//checking whether the job is of same day or previous day
	$check_job=DataClass::CheckJobCurrentDate($job_id,$kid_id,$day);
	if($check_job)
	$job_date=gmdate('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s") ));
	else
	$job_date= gmdate('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s") . " -1 day"));

	
	$sql="SELECT * from `job_status` WHERE job_id=:id and day=:day and kid_id=:kid_id and DATE(`created_on`) BETWEEN (CURDATE() - INTERVAL 1 DAY) AND (CURDATE())";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	
			$day1 = date('w');
		
		$week_start = date('Y-m-d', strtotime('-'.$day1.' days +1 days' )); // for next week
		$week_end = date('Y-m-d', strtotime('+'.(6-$day1).' days'));
	
	$sql="UPDATE `job_status` set inactive=1 WHERE job_id=:id and day!=:day and kid_id=:kid_id and DATE(`created_on`) BETWEEN ('$week_start') AND ('$week_end')";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);	
	try{$sth->execute();}
	catch(Exception $e){}
	
	
	//set particular job as complete/incomplete for the day
	$sql="UPDATE `job_status` set is_completed=1 WHERE job_id=:id and day=:day and kid_id=:kid_id and DATE(`created_on`) BETWEEN (CURDATE() - INTERVAL 1 DAY) AND (CURDATE())";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);	
	try{$sth->execute();
	$success='1';
	$msg='Job Completed';
	$type=2;
	if(!empty($apnid)){
		try{
		$apns->newMessage($apnid);
		$apns->addMessageAlert($msg);
		$apns->addMessageSound('x.wav');
		$apns->addMessageCustom('u', $uid);
		$apns->addMessageCustom('k', $kid_id);
		$apns->addMessageCustom('t', $type);
		$apns->queueMessage();
		$apns->processQueue();
		}
		catch(Exception $e){}
		
	}
	
	
	if($week_parameter==1)
	$data = DataClass::get_week_jobs($uid,2)? DataClass::get_week_jobs($uid,2):[];
	else
	$data = DataClass::get_day_jobs($uid,2)? DataClass::get_day_jobs($uid,2):[];
	
	}
	catch(Exception $e){}
	
	}
	else{
	
	//$dayname=gmdate('l');
	$sql="INSERT INTO `job_status` (`id`, `job_id`, `kid_id`,`day`,`is_completed`,`is_approved`, `created_on`) VALUES (DEFAULT, :job_id, :kid_id,:day,1,0,:job_date)";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('job_id',$job_id);
	$sth->bindValue('day',$day);
	$sth->bindValue('kid_id',$kid_id);
	$sth->bindValue('job_date',$job_date);
	try{$sth->execute();
	$success='1';
	$msg="Job Completed";
	$type=2;
	if(!empty($apnid)){
		try{
		$apns->newMessage($apnid);
		$apns->addMessageAlert($msg);
		$apns->addMessageSound('x.wav');
		$apns->addMessageCustom('u', $uid);
		$apns->addMessageCustom('k', $kid_id);		
		$apns->addMessageCustom('t', $type);
		$apns->queueMessage();
		$apns->processQueue();
		}
		catch(Exception $e){}
		
	}
	
	if($week_parameter==1)
	$data = DataClass::get_week_jobs($uid,2)? DataClass::get_week_jobs($uid,2):[];
	else
	$data = DataClass::get_day_jobs($uid,2)? DataClass::get_day_jobs($uid,2):[];
	
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