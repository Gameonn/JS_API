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

$uid=$_REQUEST['user_id'];

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{	
	/*
	$sql="select * from `job_status` where kid_id IN (select kids.id from users left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id={$uid}
	UNION 
	select kids.id from users join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id={$uid}) and job_status.created_on<=NOW()";	
	*/
	
	$sql="select count(job_status.id) as is_approved_count from `job_status` where kid_id IN (select kids.id from users 
	left join kids on kids.user_id=users.id 
	left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id={$uid}
	UNION 
	select kids.id from users join `connect` on `connect`.`user_id2`=users.id 
	left join kids on kids.id=`connect`.`kid_id` 
	left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id 
	where users.id={$uid}) AND DATE_FORMAT(job_status.created_on,'%Y-%m-%d') BETWEEN  (UTC_DATE()-INTERVAL 1 DAY) AND (UTC_DATE()) and is_approved=0";	
		
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$complete_status=$res[0]['is_approved_count'];
	
	$is_complete=$complete_status?1:0;
	
	
	//set jobs as complete/incomplete for the day(multiple jobs)
	/*
	$sql="update `job_status` set is_approved=:complete_status where kid_id IN (select kids.id from users left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id={$uid}
	UNION 
	select kids.id from users join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id={$uid}) and job_status.created_on<=NOW()";	
	*/
	
	$WhereClause="AND DATE_FORMAT(job_status.created_on,'%Y-%m-%d') BETWEEN  (UTC_DATE()-INTERVAL 1 DAY) AND (UTC_DATE())";
	
	$sql="update `job_status` set is_approved=:complete_status where kid_id 
	IN (select kids.id from users left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id={$uid}
	UNION 
	select kids.id from users join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id={$uid}) $WhereClause ";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('complete_status',$is_complete);
	try{$sth->execute();
	$success='1';
	$msg='Job Approved';
	//$data = DataClass::get_jobs_data($uid)? DataClass::get_jobs_data($uid):[];
	}
	catch(Exception $e){}	
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
