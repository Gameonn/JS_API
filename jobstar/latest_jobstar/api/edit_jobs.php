<?php
//this is an api to update jobs info

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

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

$uid=$_REQUEST['user_id'];
$kid=$_REQUEST['kid_id'];
$title=$_REQUEST['title'];
$description=$_REQUEST['description']?$_REQUEST['description']:"";
$days=$_REQUEST['days']?$_REQUEST['days']:"";
$bonus_job=$_REQUEST['bonus_job']?$_REQUEST['bonus_job']:0;
$repeat_job=$_REQUEST['repeat_job']?$_REQUEST['repeat_job']:0;
$image=$_FILES['image'];
$job_id=$_REQUEST['job_id'];
$dy=json_decode($days); // json decode of days array

	
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
	if($job_id){
	
	//$bonus_job_stats=getCurrentJobBonus($job_id);
	
	if($image_path)
	$sql="UPDATE `jobs` set title=:title,description=:description,bonus_job=:bonus_job,repeat_job=:repeat_job,image=:image where id=:id";	
	else
	$sql="UPDATE `jobs` set title=:title,description=:description,bonus_job=:bonus_job,repeat_job=:repeat_job where id=:id";	
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('title',$title);
	$sth->bindValue('description',$description);
	$sth->bindValue('bonus_job',$bonus_job);
	$sth->bindValue('repeat_job',$repeat_job);
	if($image_path) $sth->bindValue('image',$image_path);
	$sth->bindValue('id',$job_id);
	try{$sth->execute();
	$success='1';
	$msg='Job Updated';
	}
	catch(Exception $e){}
	
	if(!($bonus_job)){
		//days array
		if($dy){
		foreach($dy as $key=>$val){
			$m=$val;
			$dys.='"'.$m.'",';
		}
		
		$num= rtrim($dys, ', ');
				
		$sql="DELETE from job_status where job_id=:job_id and kid_id=:kid_id and day NOT IN ($num) and DATE(job_status.created_on) >=CURDATE()";
		$sth=$conn->prepare($sql);
		$sth->bindValue('job_id',$job_id);
		$sth->bindValue('kid_id',$kid);
		try{$sth->execute();}
		catch(Exception $e){}
		}
	
	
	foreach($dy as $row){
	
	$cr_date=gmdate('Y-m-d H:i:s',strtotime($row));//creation date based on day
	$sql="SELECT * from job_status where job_id=:job_id and kid_id=:kid_id and day=:day";
	$sth=$conn->prepare($sql);
	$sth->bindValue('job_id',$job_id);
	$sth->bindValue('day',$row);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	$job_stat=$sth->fetchAll();
	
	if(!$job_stat){
	
	$sql="INSERT INTO `job_status` (`id`, `job_id`, `kid_id`,`day`,`is_completed`,`is_approved`, `created_on`) VALUES (DEFAULT, :job_id, :kid_id,:day,0,0,:cr_date)";
	$sth=$conn->prepare($sql);
	$sth->bindValue('job_id',$job_id);
	$sth->bindValue('day',$row);
	$sth->bindValue('cr_date',$cr_date);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	}
	
	}
	
	}//if not bonus job then only days would be editable
	}//if job_id present
	else{
	
	$sql="INSERT INTO `jobs` (`id`, `user_id`, `kid_id`,`image`, `title`, `description`, `generic`, `bonus_job`, `repeat_job`, `history_job`, `created_on`,`updated_on`) VALUES (DEFAULT, :user_id, :kid_id,:image,:title,:description, 0, :bonus_job,:repeat_job,1,UTC_TIMESTAMP(),UTC_TIMESTAMP())";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('title',$title);
	$sth->bindValue('description',$description);
	$sth->bindValue('bonus_job',$bonus_job);
	$sth->bindValue('repeat_job',$repeat_job);
	$sth->bindValue('image',$image_path);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('kid_id',$kid);
	try{$sth->execute();
	$success='1';
	$msg='Job Added';
	$job_id=$conn->lastInsertId();
	}
	catch(Exception $e){}
	
		foreach($dy as $row){
			$cr_date=gmdate('Y-m-d H:i:s',strtotime($row));//creation date based on day
			$sql="INSERT INTO `job_status` (`id`, `job_id`, `kid_id`,`day`,`is_completed`,`is_approved`, `created_on`) VALUES (DEFAULT, :job_id, :kid_id,:day,0,0,:cr_date)";
			
			$sth=$conn->prepare($sql);
			$sth->bindValue('job_id',$job_id);
			$sth->bindValue('day',$row);
			$sth->bindValue('kid_id',$kid);
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