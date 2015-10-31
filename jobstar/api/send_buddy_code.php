<?php
//this is an api to add buddy and send buddy code

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
$kid=$_REQUEST['kid_id'];
$uid=$_REQUEST['user_id'];

if(!($kid && $email)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$code = DataClass::generateRandomString(12);
	$sql="SELECT kids.id as kid_id from users join kids on kids.user_id=users.id WHERE email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){
	
	foreach($res as $key=>$value){
	
		$sql="select * from friends where kid_id1=:kid1 and kid_id2=:kid2 
			  UNION
			  select * from friends where kid_id1=:kid2 and kid_id2=:kid1";
		$sth=$conn->prepare($sql);
		$sth->bindValue('kid1',$kid);
		$sth->bindValue('kid2',$value['kid_id']);
		try{$sth->execute();}
		catch(Exception $e){}
		$fr[$key]=$sth->fetchAll();
		
		if(!count($fr[$key])){
		
		$sql="INSERT INTO `friends` (`id`, `kid_id1`, `kid_id2`,`buddy_key`,`status`,`created_on`,`updated_on`) VALUES (DEFAULT, :kid_id, :kid2,:code,0,NOW(),NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('code',$code);
		$sth->bindValue('kid_id',$kid);
		$sth->bindValue('kid2',$value['kid_id']);
		$c=0;
		try{$c=$sth->execute();}
		catch(Exception $e){}
		}
		else{
		$success='0';
		$msg='Already sent';
		}
	
	}
		if($c){
		$success='1';
		$msg='Buddy Code sent.';
		$msg1="Buddy Code for Jobstars";
		DataClass::sendEmail($email,$msg1,$code,SMTP_EMAIL);
		}
	}
	else{
	$success='0';
	$msg="Invalid email or no kid is linked with it";
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