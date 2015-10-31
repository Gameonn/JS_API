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
//0-none, 1-monthly,2-yearly
$uid=$_REQUEST['user_id'];
$kid=(array)$_REQUEST['kid_id'];
$title=$_REQUEST['title'];
$description=$_REQUEST['description']?$_REQUEST['description']:"";
$days=$_REQUEST['days']?$_REQUEST['days']:"";
$bonus_job=$_REQUEST['bonus_job']?$_REQUEST['bonus_job']:0;
$repeat_job=$_REQUEST['repeat_job']?$_REQUEST['repeat_job']:0;
$image=$_FILES['image'];
//$date=date('Y-m-d');
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
	$sql="INSERT INTO `jobs` (`id`, `user_id`, `kid_id`,`image`, `title`, `description`, `generic`, `bonus_job`, `repeat_job`, `created_on`,`updated_on`) VALUES (DEFAULT, :user_id, :kid_id,:image,:title,:description, 0, :bonus_job,:repeat_job,NOW(),NOW())";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('title',$title);
	$sth->bindValue('description',$description);
	$sth->bindValue('bonus_job',$bonus_job);
	$sth->bindValue('repeat_job',$repeat_job);
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
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
/*if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else*/
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>