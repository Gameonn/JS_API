<?php
//this is an api to add jobs for kids

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");

//random file name generator
function randomFileNameGenerator($prefix){
	$r=substr(str_replace(".","",uniqid($prefix,true)),0,20);
	if(file_exists("../uploads/$r")) randomFileNameGenerator($prefix);
	else return $r;
}

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

//["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

$uid=$_REQUEST['user_id'];
$kid=(array)$_REQUEST['kid_id'];
$title=$_REQUEST['title'];
$description=$_REQUEST['description']?$_REQUEST['description']:"";
$days=$_REQUEST['days']?$_REQUEST['days']:"";
$bonus_job=$_REQUEST['bonus_job']?$_REQUEST['bonus_job']:0;
$repeat_job=$_REQUEST['repeat_job']?$_REQUEST['repeat_job']:0;
$is_saved=$_REQUEST['is_saved']?$_REQUEST['is_saved']:0;
$image=$_FILES['image'];
$start_date=date('Y-m-d');
$week_check=$_REQUEST['week_check'] ? $_REQUEST['week_check']:0;
//$day_name=date("l");

//if kid id is -1 then all kids for that user is assigned that job else only specified kid

$dy=json_decode($days);// json decode of days array

		$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$image['name']));
		if(@move_uploaded_file($image['tmp_name'], "../uploads/$randomFileName")){
			
			$image_path=$randomFileName;
		}
		else{
		$image_path="";
		}

if(!($uid && $kid && $title && $days)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	//fetching all kids of that user
	if($kid[0]==-1){
	$kid=DataClass::getKidsList($uid);
	}
	
	foreach($kid as $row){
	
	//adding jobs and related details
	$sql="INSERT INTO `jobs` (`id`, `user_id`, `kid_id`,`image`, `title`, `description`, `generic`, `bonus_job`, `repeat_job`, `history_job`,`created_on`,`updated_on`) VALUES (DEFAULT, :user_id, :kid_id,:image,:title,:description, :is_saved, :bonus_job,:repeat_job,1,NOW(),NOW())";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('title',$title);
	$sth->bindValue('description',$description);
	$sth->bindValue('bonus_job',$bonus_job);
	$sth->bindValue('repeat_job',$repeat_job);
	$sth->bindValue('is_saved',$is_saved);
	$sth->bindValue('image',$image_path);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('kid_id',$row);
	try{$sth->execute();
	$success='1';
	$msg='Job Added';
	$job_id=$conn->lastInsertId();
	}
	catch(Exception $e){}
	
	
	//days entry in job status table
	if($bonus_job){

		$day1 = date('w');
		
		if($week_check){
		$week_start = date('Y-m-d', strtotime('-'.$day1.' days +8 days' ));// for next week
		$week_end = date('Y-m-d', strtotime('+'.(13-$day1).' days'));
		}
		else{
		$week_start = date('Y-m-d');// for current week
		$week_end = date('Y-m-d', strtotime('+'.(6-$day1).' days'));
		}
		
		for($date = strtotime($week_start); $date <= strtotime($week_end); $date = strtotime("+1 day", $date)){
			$cr_date= date('Y-m-d',$date);
			$dayrow = date("l", $date);
			
			$sql="INSERT INTO `job_status` (`id`, `job_id`, `kid_id`,`day`,`is_completed`,`is_approved`, `created_on`) 
				   VALUES (DEFAULT, :job_id, :kid_id,:day,0,0,:cr_date)";
			$sth=$conn->prepare($sql);
			$sth->bindValue('job_id',$job_id);
			$sth->bindValue('day',$dayrow);
			$sth->bindValue('kid_id',$row);
			$sth->bindValue('cr_date',$cr_date);
			try{$sth->execute();}
			catch(Exception $e){}
		
		}

	}
	else{
		foreach($dy as $dayrow){
		
		$cr_date=gmdate('Y-m-d H:i:s',strtotime($dayrow));
		$sql="INSERT INTO `job_status` (`id`, `job_id`, `kid_id`,`day`,`is_completed`,`is_approved`, `created_on`) VALUES (DEFAULT, :job_id, :kid_id,:day,0,0,:cr_date)";
		
		$sth=$conn->prepare($sql);
		$sth->bindValue('job_id',$job_id);
		$sth->bindValue('day',$dayrow);
		$sth->bindValue('kid_id',$row);
		$sth->bindValue('cr_date',$cr_date);
		try{$sth->execute();}
		catch(Exception $e){}
		}		
	}
	
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