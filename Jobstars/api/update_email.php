<?php
//this is an api to update email address

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once('../PHPMailer_5.2.4/class.phpmailer.php');
require_once("DataClass.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$email=$_REQUEST['email'];
$uid=$_REQUEST['user_id'];

if(!($uid && $email && $email!='null' )){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	$sql="select * from users where email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$uid1=$res[0]['id'];
	
	//same users
		if($uid==$uid1){
		$success='1';
		$msg="Using Same Email Address";
		}
		else{
			if(count($res)){

				$success='0';
				$msg="Email Already Used";
			}
			else{
			$sql="update users set email=:email where id=:uid";
			$sth=$conn->prepare($sql);
			$sth->bindValue('email',$email);
			$sth->bindValue('uid',$uid);
			try{$sth->execute();
			$success='1';
			$msg="Email Updated";
			}
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