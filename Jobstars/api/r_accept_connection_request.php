<?php

require_once("../php_include/db_connection.php");

$user_id = $_REQUEST['user_id'];
$kid_id  = $_REQUEST['kid_id'];

$users = array();

if(!empty($user_id) && !empty($kid_id)){
	
	global $conn;
	$sql="select id,status from connect WHERE user_id2=:user_id AND kid_id=:kid_id";
	$sth=$conn->prepare($sql);
	$sth->bindParam(':user_id',$user_id);
	$sth->bindParam(':kid_id',$kid_id);
	try{
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if($result){
			$status = $result[0]['status'];
			$id = $result[0]['id'];
		}
	}
	catch(Exception $e){}
	
	if(!empty($id)){
		if($status){
		$sql="DELETE from connect where id=:id";
		}else{
		$sql="update connect set status=1 WHERE id=:id";
		}
		
		$sth=$conn->prepare($sql);
		$sth->bindParam(':id',$id);
		//$sth->bindParam(':status',$status);
		try{$sth->execute();}
		catch(Exception $e){}
	}
	
	$sql="select (select concat(users.first_name,' ',users.last_name) from users WHERE users.id=k.user_id) as username,
	(select users.email from users WHERE users.id=k.user_id) as email,
	k.id,k.name,round(DATEDIFF(date(now()),k.dob) / 365.25) AS age,k.gender,k.image,k.city,k.country,c.status FROM connect c JOIN kids k ON c.kid_id=k.id WHERE user_id2=:user_id and c.is_deleted='n'";
	$sth=$conn->prepare($sql);
	$sth->bindParam(':user_id',$user_id);
	try{
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $key=>$value){
			$users[]=$value;
		}
	}catch(Exception $e){}
	$success='1';$msg='success';
}else{
$success='0';$msg='Incomplete Parameters';
}

echo json_encode(array('success'=>$success,'msg'=>$msg,'users'=>$users));
