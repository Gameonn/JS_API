<?php
//this is an api to complete/incomplete job by kid

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$date=date('Y-m-d');
$uid=$_REQUEST['user_id'];
$kid=$_REQUEST['kid_id'];
//$flag=$_REQUEST['flag']?$_REQUEST['flag']:0;
$user_date=$_REQUEST['user_date']?$_REQUEST['user_date']:$date;
$day_name=date("l");

if(!($uid && $kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	//select * from `job_status` where job_id=167 and day='Monday' and kid_id=142 and DATE_FORMAT(created_on,'%Y-%m-%d')=CURDATE()
	$sql="SELECT * from `job_status` where kid_id=:kid_id and day=:day";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('day',$day_name);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$stat=$res[0]['is_completed'];
	$is_completed=$stat?0:1;
	
	
	//all jobs set as complete/incomplete by kid for that day(multiple jobs)
	$sql="UPDATE `job_status` set is_completed=:status where kid_id=:kid_id and day=:day";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('day',$day_name);
	$sth->bindValue('status',$is_completed);
	try{$sth->execute();
	$success='1';
	$msg='Jobs Completed';
	$data = DataClass::get_jobs_data($uid)? DataClass::get_jobs_data($uid):[];
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