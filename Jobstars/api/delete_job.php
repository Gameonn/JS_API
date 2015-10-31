<?php
//this is an api to delete comments

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$jid=$_REQUEST['job_id'];


if(!($jid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$sql="UPDATE jobs set is_deleted=1 WHERE id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$jid);
	try{$sth->execute();
	$success='1';
	$msg='Job Deleted';
	}
	catch(Exception $e){}


	/*$sql="DELETE from `jobs` where id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$jid);
	try{$sth->execute();
	$success='1';
	$msg='Job Deleted';
	}
	catch(Exception $e){}
	
	$sql="DELETE from `job_status` where job_id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$jid);
	try{$sth->execute();}
	catch(Exception $e){}
	
	$sql="DELETE from `comments` where job_id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$jid);
	try{$sth->execute();}
	catch(Exception $e){}
	
	$sql="DELETE from `likes` where job_id=:id";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$jid);
	try{$sth->execute();}
	catch(Exception $e){}*/

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