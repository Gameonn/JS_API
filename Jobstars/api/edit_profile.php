<?php
//this is an api to register users on the server

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();

//random file name generator
function randomFileNameGenerator($prefix){
	$r=substr(str_replace(".","",uniqid($prefix,true)),0,20);
	if(file_exists("../uploads/$r")) randomFileNameGenerator($prefix);
	else return $r;
}


// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
$uid=$_REQUEST['user_id'];
$first_name=$_REQUEST['first_name']?$_REQUEST['first_name']:"";
$last_name=$_REQUEST['last_name']?$_REQUEST['last_name']:"";
$city=$_REQUEST['city']?$_REQUEST['city']:"";
$country=$_REQUEST['country']?$_REQUEST['country']:'';
$gender=$_REQUEST['gender']?$_REQUEST['gender']:'';
$image=$_FILES['image'];
 
$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$image['name']));
		if(@move_uploaded_file($image['tmp_name'], "../uploads/$randomFileName")){
			//$success="1";
			$image_path=$randomFileName;
		}
		else{
		$image_path="";
		}
global $conn;
if(!$uid){
$success='0';
$msg="Incomplete Parameters";
}
else{
if($image_path){
 $sql="update users set first_name=:fname,last_name=:lname,city=:city,country=:country,gender=:gender,image=:image where id=:id";
 }
 else{
  $sql="update users set first_name=:fname,last_name=:lname,city=:city,country=:country,gender=:gender where id=:id";
 }
 $sth=$conn->prepare($sql);
 $sth->bindValue("fname",$first_name);
 $sth->bindValue("lname",$last_name);
 $sth->bindValue('city',$city);
 $sth->bindValue('country',$country);
 $sth->bindValue('gender',$gender);
 $sth->bindValue('id',$uid);
 if($image_path) $sth->bindValue('image',$image_path);
 try{$sth->execute();
	$success=1;
	$msg="User info updated";
	$profile=DataClass::get_profile_by_id($uid);
	$data=$profile?$profile:[];
 }	
 catch(Exception $e){
 //echo $e->getMessage();
 }	
	


}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success=="1"){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>