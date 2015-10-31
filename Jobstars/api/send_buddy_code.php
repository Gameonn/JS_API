<?php
//this is an api to send buddy code

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
$kid_id=$_REQUEST['kid_id'];
$code=$_REQUEST['code'];


if(!($uid && $kid_id && $code)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$user_ids=json_decode($uid);
	
	foreach($user_ids as $k=>$val){
	
	$sql="SELECT * from buddyup where user_id=:user_id and kid_id=:kid_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid_id);
	$sth->bindValue('user_id',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$res[$k]=$sth->fetchAll(PDO::FETCH_ASSOC);
		
		if(!count($res[$k])){
			
			$user=DataClass::getUserIds($val);
			$email=$user[0]['email'];
			
			$sql="INSERT INTO `buddyup` (`id`, `user_id`, `kid_id`,`buddy_code`,`created_on`) VALUES (DEFAULT, :user_id, :kid_id,:buddy_code,NOW())";
			$sth=$conn->prepare($sql);
			$sth->bindValue('buddy_code',$code);
			$sth->bindValue('kid_id',$kid_id);
			$sth->bindValue('user_id',$val);
			try{$sth->execute();
			$success='1';
			$msg='Buddy Code sent.';
			$msg1="Buddy Code for Jobstars";
			
			//DataClass::sendEmail($email,$msg1,$code,SMTP_EMAIL);
			}
			catch(Exception $e){}
		}
		else{
		$success='0';
		$msg='Already sent';
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