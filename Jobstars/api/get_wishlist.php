<?php
//this is an api to get wishlist
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("DataClass.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$uid=$_REQUEST['user_id'];

if(!($uid)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{

	$path=BASE_PATH."uploads/";
	
	$kid=DataClass::getKidsList($uid);
	
		foreach($kid as $row){
		
		$num.=$row.',';
		}
		
		$kid_ids=rtrim($num,',');

	$sql="SELECT kids_wishlist.kid_id,reward.id as reward_id,reward.category_id,reward.title,reward.description,reward.price,reward.status,category.name as category_name, category.filepath as category_image,
	(SELECT group_concat(concat('$path',rewardimages.image) SEPARATOR ',') 	FROM rewardimages WHERE rewardimages.reward_id=reward.id ORDER BY defaultImage DESC) as reward_image, 
	(SELECT group_concat(tags.name SEPARATOR ',') 	FROM tags WHERE tags.reward_id=reward.id) as tag_name FROM kids_wishlist join `reward` on reward.id=kids_wishlist.reward_id
	join category on category.id=reward.category_id where kid_id IN ($kid_ids)";
	
	$sth=$conn->prepare($sql);
	//$sth->bindValue('kid_id',$kid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(!count($res)){
	
	$success='0';
	$msg="No reward in wishlist";
	
	}
	else{
	$success='1';
	$msg="Reward removed from wishlist";	
	$data=$res;
	}
	}
	


// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+


if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>