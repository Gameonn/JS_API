<?php
//this is an api to add kids

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
//0-none, 1-monthly,2-yearly
$uid=$_REQUEST['user_id'];
$alias=$_REQUEST['alias']?$_REQUEST['alias']:"";
$dob=$_REQUEST['dob']?$_REQUEST['dob']:"";
$gender=$_REQUEST['gender']?$_REQUEST['gender']:"";
$city=$_REQUEST['city']?$_REQUEST['city']:"";
$country=$_REQUEST['country']?$_REQUEST['country']:"";
$name=$_REQUEST['name'];
$password=$_REQUEST['password'];
//$image=$_FILES['image'];
$image=$_REQUEST['image'];

/*
$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$image['name']));
		if(@move_uploaded_file($image['tmp_name'], "../uploads/$randomFileName")){
			//$success="1";
			$image_path=$randomFileName;
		}
		else{
		$image_path="";
		}
		*/

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$success='1';
	$msg='Kid Added';

	$sql="INSERT INTO `kids` (`id`, `user_id`, `name`, `password`, `dob`, `gender`, `city`, `country`, `image`, `created_on`,`plan`) VALUES (DEFAULT, :user_id, :name,:password,:dob, :gender, :city,:country,:image,NOW(),0)";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('name',$name);
	$sth->bindValue('password',$password);
	$sth->bindValue('dob',$dob);
	$sth->bindValue('gender',$gender);
	$sth->bindValue('city',$city);
	$sth->bindValue('country',$country);
	$sth->bindValue('image',$image);
	$sth->bindValue('user_id',$uid);
	try{$sth->execute();
	$kid=$conn->lastInsertId();
	}
	catch(Exception $e){
	//echo $e->getMessage();
	}
	
	$sql="INSERT INTO `grownup_alias` (`id`, `kid_id`, `user_id`, `alias`, `access`, `status`) VALUES (DEFAULT,:kid_id, :user_id, :alias,1,1)";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('user_id',$uid);
	$sth->bindValue('alias',$alias);
	try{$sth->execute();}
	catch(Exception $e){
	//echo $e->getMessage();
	}

}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"image"=>$image));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>