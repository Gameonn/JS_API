<?php
//this is an api to fetch raffle for kid

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

	$path=BASE_PATH."uploads/";
	
	$sql="SELECT id,title,concat('$path',option1) as option1,concat('$path',option2) as option2,concat('$path',option3) as option3,
	concat('$path',option4) as option4, answer,active from `raffles` where active=1 ORDER BY created_on DESC";	
	
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	
	$sql="SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) - CURDATE() as days_left";	
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$res1=$sth->fetchAll(PDO::FETCH_ASSOC);
	$days_left=$res1[0]['days_left'];
	
	if(!count($result)){
	
	$success='0';
	$msg="No Raffle Found";
	}
	else{
	
	$success='1';
	$msg='New Raffle';
	$data=$result;
	}
	
	



// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+


if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data,"days_left"=>$days_left));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>