<?php
//this is an api to answer raffle by kid

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$kid=$_REQUEST['kid_id'];
$raffle_id=$_REQUEST['raffle_id'];
$answer=$_REQUEST['answer'];
$status=$_REQUEST['status'];

if(!($raffle_id && $answer && $kid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
	
	$sql="SELECT * from `kid_raffles` where kid_id=:kid_id and raffle_id=:raffle_id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('raffle_id',$raffle_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	$status=$result[0]['status'];
	
	$success='0';
	if($status)
	$msg="Already won this raffle";
	else
	$msg="You failed to achieve this raffle. Try Again next week";
	}
	else{
	$sql="INSERT INTO kid_raffles VALUES(DEFAULT,:raffle_id,:kid_id,:kid_answer,:status,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('kid_id',$kid);
	$sth->bindValue('raffle_id',$raffle_id);
	$sth->bindValue('kid_answer',$answer);
	$sth->bindValue('status',$status);
	try{$sth->execute();
	$success='1';
	$msg='Raffle Answered';
	
	if($status)
	DataClass::AddBadge($kid,'raffle');
	
	}
	catch(Exception $e){}
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