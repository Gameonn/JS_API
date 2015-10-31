<?php

class DataClass{

//random string generation function
public static function generateRandomString($length = 5){
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters='0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


//random buddy code
public static function generateBuddyCode($length = 8){
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters='0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


//add badge to kid
public static function AddBadge($kid_id,$type){

global $conn;
$sql="INSERT into badge(id,kid_id,badge_type,created_on) VALUES(DEFAULT,:kid_id,:badge_type,NOW())";
$sth=$conn->prepare($sql);
$sth->bindValue('kid_id',$kid_id);
$sth->bindValue('badge_type',$type);
try{$sth->execute();}
catch(Exception $e){}

return true;

}

public static function getkidId($buddy_code,$user_id){

global $conn;
$sql="SELECT kid_id from buddyup WHERE user_id=:user_id and buddy_code=:buddy_code";

$sth=$conn->prepare($sql);
$sth->bindValue('user_id',$user_id);
$sth->bindValue('buddy_code',$buddy_code);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$kid_id=$result[0]['kid_id'];

return $kid_id;

}


//fetch bonus job status of current job
public static function getCurrentJobBonus($job_id){

global $conn;
$sql="SELECT bonus_job FROM jobs WHERE jobs.id=:job_id";

$sth=$conn->prepare($sql);
$sth->bindValue('job_id',$job_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$bonus_job=$result[0]['bonus_job'];

return $bonus_job;

}

//checking whether the job is of current date or previous date
public static function CheckJobCurrentDate($job_id,$kid_id,$day){

global $conn;
$sql="SELECT * from job_status WHERE job_id=:job_id and kid_id=:kid_id and day=:day and DATE(created_on)=UTC_DATE";
$sth=$conn->prepare($sql);
$sth->bindValue('kid_id',$kid_id);
$sth->bindValue('job_id',$job_id);
$sth->bindValue('day',$day);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$kid_id=$result[0]['kid_id'];

return $kid_id;

}

//fetching user details based on user_Id
public static function getUserIds($user_id){

global $conn;
$sql="SELECT * from users WHERE users.id=:user_id";
$sth=$conn->prepare($sql);
$sth->bindValue('user_id',$user_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);

return $result;
}


//fetch user and kids connected details based on user_id and code
public static function get_details($uid,$code){

global $conn;
$sql="select users.*,users.city as u_city,users.country as u_country,users.gender as u_gender,users.id as uid,users.image as u_image,kids.*,
kids.id as kid,(YEAR(NOW())-YEAR(kids.dob)) AS age1, (SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,kids.city as k_city,kids.country as k_country,kids.gender as k_gender,kids.image as k_image from `connect`
JOIN users on users.id=`connect`.user_id1 join kids on kids.id=`connect`.kid_id where code=:code";

$sth=$conn->prepare($sql);
$sth->bindValue('code',$code);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);

	if($res){
	foreach($res as $key=>$value){
		$data['kids']=array(
					"kid_id"=> $value['kid'],
		            "name"=> $value['name']?$value['name']:"",
		            "city"=> $value['k_city']?$value['k_city']:"",
		            "country"=> $value['k_country']?$value['k_country']:"",
	           	    "age"=> $value['age']?$value['age']:0,
		            "gender"=> $value['k_gender']?$value['k_gender']:"",
		            "dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
		            "image"=> $value['k_image']?$value['k_image']:""     
			);
		}
		

		$data['user']=array(
					"user_id"=> $res[0]['uid']?$res[0]['uid']:"",
		            "first_name"=> $res[0]['first_name']?$res[0]['first_name']:"",
		            "last_name"=> $res[0]['last_name']?$res[0]['last_name']:"",
		            "email"=> $res[0]['email'],
		            "city"=> $res[0]['u_city']?$res[0]['u_city']:"",
		            "country"=> $res[0]['u_country']?$res[0]['u_country']:"",
		            "gender"=> $res[0]['u_gender']?$res[0]['u_gender']:"",
		            "image"=> $res[0]['u_image']?BASE_PATH."timthumb.php?src=uploads/".$res[0]['u_image']:""
		             
			);
				
	}
	
return $data;
}

//fetch user and buddy details based on buddy code and kid_id
public static function get_buddy_details($kid,$code){

global $conn;
$sql="select users.*,users.city as u_city,users.country as u_country,users.gender as u_gender,users.id as uid,users.image as u_image,kids.*,kids.id as kid,
(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,kids.city as k_city,kids.country as k_country,kids.gender as k_gender,kids.image as k_image from `friends` 
JOIN kids on kids.id=`friends`.kid_id1 join users on users.id=kids.user_id where buddy_key=:code";

$sth=$conn->prepare($sql);
$sth->bindValue('code',$code);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);

	if($res){
	foreach($res as $key=>$value){
		$data['kids']=array(
					"kid_id"=> $value['kid'],
		            "name"=> $value['name']?$value['name']:"",
		            "city"=> $value['k_city']?$value['k_city']:"",
		            "country"=> $value['k_country']?$value['k_country']:"",
	           	    "age"=> $value['age']?$value['age']:0,
		            "gender"=> $value['k_gender']?$value['k_gender']:"",
		            "dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
		            "image"=> $value['k_image']?$value['k_image']:""     
			);
		}
		

		$data['user']=array(
					"user_id"=> $res[0]['uid']?$res[0]['uid']:"",
		            "first_name"=> $res[0]['first_name']?$res[0]['first_name']:"",
		            "last_name"=> $res[0]['last_name']?$res[0]['last_name']:"",
		            "email"=> $res[0]['email'],
		            "city"=> $res[0]['u_city']?$res[0]['u_city']:"",
		            "country"=> $res[0]['u_country']?$res[0]['u_country']:"",
		            "gender"=> $res[0]['u_gender']?$res[0]['u_gender']:"",
		            "image"=> $res[0]['u_image']?BASE_PATH."timthumb.php?src=uploads/".$res[0]['u_image']:""
		             
			);
				
	}
	
return $data;
}

//fetching full access rights kids through connection 
public static function getFullAccessKids($user_id){

global $conn;
$sql="SELECT kid_id from grownup_alias join kids on kids.id=grownup_alias.kid_id WHERE grownup_alias.`user_id` = :user_id AND grownup_alias.access=1";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);

return $result;
}


//fetching user rights for a kid
public static function getUserRights($user_id,$kid_id){

global $conn;
$sql="SELECT count(kids.id) as is_kid,(SELECT grownup_alias.access from grownup_alias WHERE `user_id` = :user_id AND `kid_id` = :kid_id) as status from kids 
		WHERE kids.user_id=:user_id and kids.id=:kid_id";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$is_kid=$result[0]['is_kid'];
$status=$result[0]['status'];

$is_connected=$is_kid?$is_kid:$status;
$is_cc=$is_connected?$is_connected:'0';
return $is_cc;

}

//fetching badges for a kid and its count
public static function getBadge($kid_id){

global $conn;
$sql="SELECT badge.badge_type, COUNT(*) as badge_count FROM badge WHERE badge.kid_id=:kid_id GROUP BY badge_type ORDER BY id DESC ";
$sth=$conn->prepare($sql);
$sth->bindValue('kid_id',$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
return $result;

}

//fetching buddy list for your kids
public static function get_rewards(){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path1=BASE_PATH."images/category/";
$path=BASE_PATH."uploads/";
$sql="SELECT reward.id as reward_id,reward.category_id,reward.title,reward.description,reward.price,reward.status,category.name as category_name, concat('$path1',category.filepath) as category_image,
	(SELECT rewardimages.image 	FROM rewardimages WHERE rewardimages.reward_id=reward.id and rewardimages.defaultImage='1') as reward_image, 
	(SELECT group_concat(tags.name SEPARATOR ',') 	FROM tags WHERE tags.reward_id=reward.id) as tag_name FROM `reward` 
	join category on category.id=reward.category_id";
$sth=$conn->prepare($sql);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);

	if(count($res)){
 
	foreach($res as $key=>$value){
		if(!ISSET($final[$value['category_id']])){
			$final[$value['category_id']]=array(
					"category_id"=> $value['category_id'],
		            "category_name"=> $value['category_name']?$value['category_name']:"",
		            "category_image"=> $value['category_image']?$value['category_image']:"",
		            "rewards"=>array()  
			);	
		}
	 
		if(!ISSET($final[$value['category_id']]['rewards'][$value['reward_id']])){
			if($value['reward_id']){
				$final[$value['category_id']]['rewards'][$value['reward_id']]=array(
						"reward_id"=>$value['reward_id']?$value['reward_id']:"",
						"title"=>$value['title']?$value['title']:"",
						"description"=> $value['description']? $value['description']:"",
						"reward_image"=>$value['reward_image']?BASE_PATH."images/reward/".$value['reward_id'].'/'.$value['reward_image']:"",
						"price"=>$value['price']?$value['price']:"0",
						"status"=>$value['status']?$value['status']:"0"
				
					);
				}
			}
		}		
		
		foreach($final as $key=>$value){
			$data2=array();//initializing an empty array
		foreach($value['rewards'] as $value2){
			$data2[]=$value2;
			}
		$value['rewards']=$data2;
					
			$data[]=$value;
		}	
	}

return $data;
}


//fetching buddy list for your kids
public static function get_buddy_list($kid_id){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path=BASE_PATH."uploads/";
$sql="SELECT kids.id as kid,kids.name,kids.dob,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age, kids.gender,kids.city,kids.country,kids.image FROM kids JOIN friends ON friends.kid_id2=kids.id WHERE friends.kid_id1=:kid_id 
UNION
SELECT kids.id as kid,kids.name,kids.dob,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age, kids.gender,kids.city,kids.country,kids.image FROM kids JOIN friends ON friends.kid_id1=kids.id WHERE friends.kid_id2=:kid_id ";
$sth=$conn->prepare($sql);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);

	if(count($res)){
 
	foreach($res as $key=>$value){
		 
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[]=array(
						"kid_id"=>$value['kid']?$value['kid']:"",
						"name"=>$value['name']?$value['name']:"",
						"dob"=> $value['dob']? date_format(date_create($value['dob']),'d-m-Y'):"",
						"age"=>$value['age']?$value['age']:0,
						"gender"=>$value['gender']?$value['gender']:"",
						"city"=>$value['city']?$value['city']:"",
						"country"=>$value['country']?$value['country']:"",
						"image"=> $value['image']?$value['image']:"",
						"status"=>$value['status']?$value['status']:'0'
				
					);
				}
			}
		}		
		
		foreach($final as $key=>$value){
			$data2=array();//initializing an empty array
		foreach($value['kids'] as $value2){
			$data2[]=$value2;
			}
		$value['kids']=$data2;
					
			$data[]=$value;
		}	
	}

return $final;
}

//fetching comments based on job id
public static function get_comments_by_jobid($job_id){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path=BASE_PATH."uploads/";
//query without alias
/*$sql="select comments.id as comment_id,comments.comment,CONCAT('$path',users.image) as image, users.id as comment_user_id,users.first_name as name 
from users join comments on comments.user_id=users.id where comments.job_id=:job_id" ;*/

$sql="SELECT comments.id AS comment_id, comments.comment, ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id
WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS alias, CONCAT(  '$path', users.image ) AS image, users.id AS comment_user_id, 
users.first_name AS name FROM users JOIN comments ON comments.user_id = users.id WHERE comments.job_id =:job_id";
$sth=$conn->prepare($sql);
$sth->bindValue("job_id",$job_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);
return $res;
}

//fetching likes differentiated by job id
public static function get_likes_by_jobid($job_id){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path=BASE_PATH."uploads/";
$sql="select likes.id as like_id,ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id
WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS alias,CONCAT('$path',users.image) as image, users.id as like_user_id,users.first_name as name from users 
JOIN likes on likes.user_id=users.id where likes.job_id=:job_id" ;
$sth=$conn->prepare($sql);
$sth->bindValue("job_id",$job_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);
return $res;
}

//fetching all kids(connected as well as own kids) by user id
public static function get_kids_by_user($uid){

global $conn;
	$sql="select users.id as uid,users.city as u_city,kids.*,kids.id as kid,grownup_alias.alias,grownup_alias.access from users 
	left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id=:user_id
	UNION 
	select users.id as uid,users.city as u_city,kids.*,kids.id as kid,grownup_alias.alias,grownup_alias.access from users 
	join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` 
	left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("user_id",$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	
		foreach($res as $key=>$value){
			$data[]=$value['kid'];
			}
	}
return $data;
}

//connected status=1 and own kids
public static function getKidsList($uid){

global $conn;
	$sql="select users.id as uid,users.city as u_city,kids.*,kids.id as kid,grownup_alias.alias,grownup_alias.access from users 
	left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id=:user_id
	UNION 
	select users.id as uid,users.city as u_city,kids.*,kids.id as kid,grownup_alias.alias,grownup_alias.access from users 
	join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` 
	left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id=:user_id and `connect`.status=1";
	$sth=$conn->prepare($sql);
	$sth->bindValue("user_id",$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	
		foreach($res as $key=>$value){
			$data[]=$value['kid'];
			}
	}
return $data;
}


public static function get_kids_New($uid,$uid2){

global $conn;
	
	$sql="select users.id as uid,users.city as u_city,kids.*,kids.id as kid,grownup_alias.alias,grownup_alias.access from users 
	left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("user_id",$uid);
	//$sth->bindValue("uid2",$uid2);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	
		foreach($res as $key=>$value){
			$data[]=$value['kid'];
			}
	}
	
return $data;
}


//get kids approved status 
public static function getKidApproved($kid_id){

global $conn;

	$sql="SELECT count(job_status.id) as j_count FROM `job_status` WHERE job_status.kid_id=:kid_id 
	AND is_approved=0 AND DATE(job_status.created_on) BETWEEN (CURDATE() - INTERVAL 1 DAY) AND (CURDATE()) ";
	$sth=$conn->prepare($sql);
	$sth->bindValue("kid_id",$kid_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$count=$result[0]['j_count'];
	$all_approved=$count?"0":"1";
	
	return $all_approved;
}



//get all approved status that all jobs of an adult are approved or not-- including all full access kids
public static function getAllApproved($user_id){

global $conn;

$sql="SELECT count(job_status.id) as j_count FROM `job_status` WHERE job_status.kid_id IN 
	(select kids.id from users join kids on kids.user_id=users.id 
	left join grownup_alias on grownup_alias.user_id=users.id AND grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id 
	where users.id=:user_id
	UNION 
	select kids.id from users join `connect` on 
	`connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` left join grownup_alias on grownup_alias.user_id=users.id and 
	grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id=:user_id and connect.status=1 and grownup_alias.access=1) 
	AND is_approved=0 AND DATE(job_status.created_on) BETWEEN (CURDATE() - INTERVAL 1 DAY) AND (CURDATE()) ";
	$sth=$conn->prepare($sql);
	$sth->bindValue("user_id",$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$count=$result[0]['j_count'];
	$all_approved=$count?"0":"1";
	
	return $all_approved;
}


//get all kids detail of a user
public static function get_all_kids_user($uid){

global $conn;
	$sql="select users.id as uid,users.city as u_city,kids.*,(YEAR(NOW())-YEAR(kids.dob)) AS age1, (SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age, kids.id as kid,grownup_alias.alias,grownup_alias.access from users 
	join kids on kids.user_id=users.id 
	left join grownup_alias on grownup_alias.user_id=users.id AND grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id 
	where users.id=:user_id
	UNION 
	select users.id as uid,users.city as u_city,kids.*,(YEAR(NOW())-YEAR(kids.dob)) AS age1, (SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age, kids.id as kid,grownup_alias.alias,grownup_alias.access from users join `connect` on 
	`connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` left join grownup_alias on grownup_alias.user_id=users.id and 
	grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id=:user_id and connect.status=1 and grownup_alias.access=1";
	$sth=$conn->prepare($sql);
	$sth->bindValue("user_id",$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if($result){
	
	foreach($result as $key=>$value){
		if($value['kid']){
			$data[]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['password']?$value['password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:"0",
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:0,
					"image"=> $value['image']?$value['image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"access"=> $value['access']?$value['access']:""
				);
			}
		}
	}
	
return $data;
}

public static function get_all_kid_ids($uid){

global $conn;
	$sql="select kids.id from users join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id 
	AND grownup_alias.kid_id=kids.id left join user_setting on user_setting.user_id=users.id where users.id=:user_id
	UNION 
	select kids.id from users join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` 
	left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
	left join user_setting on user_setting.user_id=users.id where users.id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("user_id",$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
return $result;
}


//fetching user profile details by email and password
public static function get_profile($email,$password){

global $conn;
	$sql="select users.* from users where email=:email and password=:password";
	$sth=$conn->prepare($sql);
	$sth->bindValue("email",$email);
	$sth->bindValue("password",$password);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	if(count($res)){
	
		foreach($res as $key=>$value){
			$data[]=array(
					"user_id"=> $value['id'],
		            "first_name"=> $value['first_name']?$value['first_name']:"",
		            "last_name"=> $value['last_name']?$value['last_name']:"",
		            "email"=> $value['email'],
		            "city"=> $value['city']?$value['city']:"",
		            "country"=> $value['country']?$value['country']:"",
		            "gender"=> $value['gender']?$value['gender']:"",
		            "image"=> $value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:""
		             
			);
		}
	}
return $data;
}

//fetching leader board with highest completed_jobs on top
public static function get_leaderboard($flag){

//flag  1- prev week 2-current week
global $conn;
if($flag==1){
$WhereClause="AND job_status.created_on < (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) - INTERVAL 7 DAY) ";
}
else{
$WhereClause="AND job_status.created_on < (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY)) ";
}

//old_query
//SELECT kids.*,(YEAR(NOW())-YEAR(kids.dob)) AS age,(select count(job_status.id) from job_status where job_status.kid_id=kids.id )as total_jobs,(select count(job_status.id) from job_status where job_status.kid_id=kids.id and job_status.is_completed=1)as completed_jobs FROM `kids` where kids.is_deleted=0 order by completed_jobs DESC

	$sql="SELECT kids.*,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,(select count(job_status.id) from job_status where job_status.kid_id=kids.id )as total_jobs,
	(select count(job_status.id) from job_status where job_status.kid_id=kids.id and job_status.is_completed=1)as completed_jobs,
	(select count(job_status.id) from job_status where job_status.kid_id=kids.id and job_status.is_approved=1)as approved_jobs 
	FROM `kids` join job_status on job_status.kid_id=kids.id where kids.is_deleted=0 $WhereClause group by kids.id  ORDER BY completed_jobs DESC";
	
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$data=array();
	
	if(count($res)){
		$r=1;
		foreach($res as $key=>$value){
			$data[]=array(
				"kid_id"=> $value['id'],
				"position"=>(string)$r,
				"name"=> $value['name']?$value['name']:"",
				"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
				"age"=> $value['age']?$value['age']:"0",
				"gender"=> $value['gender']?$value['gender']:"",
				"city"=> $value['city']?$value['city']:"",
				"country"=> $value['country']?$value['country']:"",
				"total_jobs"=> $value['total_jobs']?$value['total_jobs']:"0",
				"completed_jobs"=> $value['completed_jobs']?$value['completed_jobs']:"0",
				"approved_jobs"=> $value['approved_jobs']?$value['approved_jobs']:"0",
				"image"=> $value['image']?$value['image']:""
	             
			);
		$r=$r+1;
		}
	}
return $data;
}


//fetching user profile details by id
public static function get_profile_by_id($uid){

global $conn;
	$sql="select users.* from users where id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue("id",$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if(count($res)){
	
		foreach($res as $key=>$value){
			$data[]=array(
				"user_id"=> $value['id'],
				"first_name"=> $value['first_name']?$value['first_name']:"",
				"last_name"=> $value['last_name']?$value['last_name']:"",
				"email"=> $value['email'],
				"city"=> $value['city']?$value['city']:"",
				"country"=> $value['country']?$value['country']:"",
				"gender"=> $value['gender']?$value['gender']:"",
				"image"=> $value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:""      
			);
		}
	}
return $data;
}


//get kids profile by name,password during login
public static function get_kid_profile($name,$password,$uid){

global $conn;
$data=array();
$sql="select kids.*,(YEAR(NOW())-YEAR(kids.dob)) AS age from kids where name=:name and password=:password and user_id=:user_id and is_deleted=0";
$sth=$conn->prepare($sql);
$sth->bindValue("name",$name);
$sth->bindValue("password",$password);
$sth->bindValue('user_id',$uid);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

	if(count($res)){

		foreach($res as $key=>$value){
			$data[]=array(
				"kid_id"=> $value['id'],
	            "name"=> $value['name']?$value['name']:"",
	            "dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
				"age"=> $value['age']?$value['age']:0,
	            "city"=> $value['city']?$value['city']:"",
	            "country"=> $value['country']?$value['country']:"",
	            "gender"=> $value['gender']?$value['gender']:"",
	            "image"=> $value['image']?$value['image']:""
	             
				);
		}
	}
return $data;
}


//fetching bonus job count,friend count, completed_jobs count, approved jobs count, rewards count, grownup_connected count, badges count, raffles count
public static function get_bonus_counts($kid_id,$flag){

global $conn;
//date parameters
$cur_date=date('Y-m-d');  $mon=date("m");  $yr=date("Y");  $d2=$yr.'-'.$mon.'-01';  $d1=$yr.'-01-01';

if($flag==1){
$WhereClause="AND Date_format(jobs.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY - INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) - INTERVAL 7 DAY) ";
$WhereClause1="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY - INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) - INTERVAL 7 DAY) ";
}
elseif($flag==2){
$WhereClause="AND Date_format(jobs.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY)) ";
$WhereClause1="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY)) ";
}
elseif($flag==3){
$WhereClause="AND Date_format(jobs.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY + INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) + INTERVAL 7 DAY) ";
$WhereClause1="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY + INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) + INTERVAL 7 DAY) ";
}
elseif($flag==4){
$WhereClause="AND Date_format(jobs.created_on,'%Y-%m-%d') BETWEEN '$d2' AND CURDATE() ";
$WhereClause1="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN '$d2' AND CURDATE() ";
}
elseif($flag==5){
$WhereClause="AND Date_format(jobs.created_on,'%Y-%m-%d') BETWEEN '$d1' AND CURDATE() ";
$WhereClause1="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN '$d1' AND CURDATE() ";
}
else{}

$sql="SELECT count(jobs.id) as bonus_count,(SELECT count(job_status.id) FROM `job_status` join jobs on jobs.id=job_status.job_id WHERE job_status.kid_id=:kid_id 
	AND jobs.bonus_job=1 and job_status.is_approved=1 $WhereClause1) as bonus_approved_jobs,(SELECT count(`connect`.id) from `connect` where kid_id=:kid_id AND status=1) as grownup_connected,
(SELECT count(`friends`.id) from `friends` where (friends.kid_id1=:kid_id or friends.kid_id2=:kid_id ) AND friends.status=1 ) as friends_count,
(SELECT COUNT(likes.id) from likes join jobs on jobs.id=likes.job_id join kids on kids.id=jobs.kid_id where likes.type='badge' and kids.id=:kid_id) as badge, 
(select count(job_status.id) from job_status where job_status.kid_id=:kid_id and job_status.is_completed=1 $WhereClause1) as completed_jobs,
(select count(job_status.id) from job_status where job_status.kid_id=:kid_id and job_status.is_approved=1 $WhereClause1) as approved_jobs 
from jobs where jobs.bonus_job=1 and kid_id=:kid_id $WhereClause";

$sth=$conn->prepare($sql);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);

		if($res){
			$data=array(	
				'friends'=>$res[0]['friends_count']?$res[0]['friends_count']:'0',
				'grownups_connected'=>$res[0]['grownup_connected']?$res[0]['grownup_connected']+1:'1',
				'completed_jobs'=>$res[0]['completed_jobs']?$res[0]['completed_jobs']:'0',
				'approved_jobs'=>$res[0]['approved_jobs']?$res[0]['approved_jobs']:'0',
				'rewards'=>$res[0]['rewards']?$res[0]['rewards']:'0',
				'bonus_jobs'=>$res[0]['bonus_count']?$res[0]['bonus_count']:'0',
				'bonus_approved_jobs'=>$res[0]['bonus_approved_jobs']?$res[0]['bonus_approved_jobs']:'0',
				'badges'=>$res[0]['badges']?$res[0]['badges']:'0',
				'raffles'=>$res[0]['raffles']?$res[0]['raffles']:'0'
			);
		}
return $data;
}


//bonus jobs count of kids in one week
public static function get_bonus_job_count($kid_id){

global $conn;
$sql="SELECT count(id) as bonus_count from jobs where (DATE(`created_on`) BETWEEN (CURDATE()- INTERVAL 6 DAY) AND (CURDATE())) AND jobs.bonus_job=1 
	  AND kid_id=:kid_id";
$sth=$conn->prepare($sql);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

return $res[0]['bonus_count'];
}


//fetching all jobs of a kid
public static function get_all_jobs($kid){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path=BASE_PATH."uploads/";
$sql="SELECT jobs.*,jobs.id as job_id,jobs.created_on as job_date,concat('$path',jobs.image) as image FROM `jobs` where kid_id=:kid_id";

$sth=$conn->prepare($sql);
$sth->bindValue('kid_id',$kid);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll(PDO::FETCH_ASSOC);

return $res;
}


//fetching connection data
public static function get_connect_data($user_id){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path=BASE_PATH."uploads/";

//select users.id,concat('$path',users.image) as user_image,users.first_name,users.gender,users.city,users.country,users.last_name,users.email from connect join users on users.id=connect.user_id1 where connect.user_id2=:uid and connect.status=1 UNION
$sql="SELECT users.id,concat('$path',users.image) as user_image,users.first_name,users.gender,users.city,users.country,users.last_name,users.email,
connect.kid_id,connect.status as is_connected,
(SELECT ifnull((SELECT grownup_alias.access from grownup_alias WHERE `user_id` = users.id AND `kid_id` = connect.kid_id),'0')) as kid_access from connect
 JOIN users on users.id=connect.user_id2 where connect.user_id1=:uid";
$sth=$conn->prepare($sql);
$sth->bindValue('uid',$user_id);
try{$sth->execute();}
catch(Exception $e){}
$connect_data=$sth->fetchAll(PDO::FETCH_ASSOC);
$data['connect']=$connect_data;
return $connect_data;

}


public static function get_kid_connect_data($user_id,$kid){

global $conn;
//$path=BASE_PATH."timthumb.php?src=uploads/";
$path=BASE_PATH."uploads/";

//select users.id,concat('$path',users.image) as user_image,users.first_name,users.gender,users.city,users.country,users.last_name,users.email from connect join users on users.id=connect.user_id1 where connect.user_id2=:uid and connect.status=1 UNION
$sql="select users.id,concat('$path',users.image) as user_image,users.first_name,users.gender,users.city,users.country,users.last_name,users.email,
connect.status as is_connected from connect join users on users.id=connect.user_id2 where connect.user_id1=:uid and connect.kid_id=:kid_id group by users.id";
$sth=$conn->prepare($sql);
$sth->bindValue('uid',$user_id);
$sth->bindValue('kid_id',$kid);
try{$sth->execute();}
catch(Exception $e){}
$connect_data=$sth->fetchAll(PDO::FETCH_ASSOC);
$data['connect']=$connect_data;
return $connect_data;
}


//fetching complete dashboard data
public static function get_dashboard_data_1($user_id){

global $conn;
$sql="select users.*,users.id as uid,users.city as u_city,users.country as u_country,users.gender as u_gender,users.image as u_image,jobs.*,
jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,kids.id as kid,kids.password as k_password, kids.image as k_image,
(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,user_setting.*,friends.*,`connect`.*,`connect`.id as cid,grownup_alias.*,comments.*,
likes.*,comments.id as coid, likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,(select count(comments.id) from comments
WHERE comments.job_id=jobs.id) as comment_count from users left join kids as k on k.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id left join user_setting on user_setting.user_id=users.id 
left join kids on  kids.user_id=users.id left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join friends on friends.kid_id1=kids.id left join `connect` on `connect`.user_id1=users.id or `connect`.`user_id2`=users.id 
left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

	if(count($res)){

	foreach($res as $key=>$value){
		if(!ISSET($final[$value['uid']])){
			$final[$value['uid']]=array(
					"user_id"=> $value['uid'],
		            "first_name"=> $value['first_name']?$value['first_name']:"",
		            "last_name"=> $value['last_name']?$value['last_name']:"",
		            "email"=> $value['email'],
		            "city"=> $value['u_city']?$value['u_city']:"",
		            "country"=> $value['u_country']?$value['u_country']:"",
		            "gender"=> $value['u_gender']?$value['u_gender']:"",
		            "image"=> $value['u_image']?BASE_PATH."timthumb.php?src=uploads/".$value['u_image']:"",
		            "daily_progress_email"=>$value['daily_progress_email']?$value['daily_progress_email']:0,
		            "weekly_progress_email"=>$value['weekly_progress_email']?$value['weekly_progress_email']:0,
		            "weekly_raffle_email"=>$value['weekly_raffle_email']?$value['weekly_raffle_email']:0,
		            "pending_job_notification"=>$value['pending_job_notification']?$value['pending_job_notification']:0,
		            "pending_job_email"=>$value['pending_job_email']?$value['pending_job_email']:0,
		            "buddy_email"=>$value['buddy_email']?$value['buddy_email']:0,
		            "connection_email"=>$value['connection_email']?$value['connection_email']:0,
		            "kids"=>array()  
			);	
		}
		
		if(!ISSET($final[$value['uid']]['kids'][$value['kid']])){
			if($value['kid']){
				$final[$value['uid']]['kids'][$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:"0",
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:"0",
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"access"=> $value['access']?$value['access']:"",
					"jobs"=>array()
				);
			}
		}
			
		if(!ISSET($final[$value['uid']]['kids'][$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				$final[$value['uid']]['kids'][$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"days"=>$value['days']?$value['days']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					"is_completed"=>$value['is_completed']?$value['is_completed']:0,
					"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"comments"=>array(),
					"likes"=>array()		            	
				);
			}
		}	
			
		if(!ISSET($final[$value['uid']]['kids'][$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['coid']){
				$final[$value['uid']]['kids'][$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
					"comment_id"=>$value['coid']?$value['coid']:"",
					"comment"=>$value['comment']?$value['comment']:"",
					"name"=>$value['c_name']?$value['c_name']:"",
					"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
					"comment_user_id"=>$value['udid']?$value['udid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['uid']]['kids'][$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['lid']){
				$final[$value['uid']]['kids'][$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
					"like_id"=>$value['lid']?$value['lid']:"",
					"name"=>$value['l_name']?$value['l_name']:"",
					"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
					"like_user_id"=>$value['ulid']?$value['ulid']:""
				);
			}
		}	
		}
		
		foreach($final as $key=>$value){
			$data2=array();
			foreach($value['kids'] as $value2){
				$tmp=array();	
				foreach($value2['jobs'] as $v2){
					$tmp1=array();
					$tmp2=array();
				foreach($v2['comments'] as $v3){
					$tmp1[]=$v3;
					}
				foreach($v2['likes'] as $v4){
					$tmp2[]=$v4;
					}
					$v2['likes']=$tmp2;
					$v2['comments']=$tmp1;	
					$tmp[]=$v2;
					}
				$value2['jobs']=$tmp;
				$data2[]=$value2;
				}
		$value['kids']=$data2;
					
			$data[]=$value;
			}	
	}
return $data;
}


//fetching dashboard details
public static function get_dashboard_data($user_id){

global $conn;
$sql="select users.*,users.id as uid,users.city as u_city,users.password as u_password,users.country as u_country,users.gender as u_gender,
users.image as u_image,kids.*,kids.id as kid, kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,
(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,user_setting.*, grownup_alias.* from users 
left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
left join user_setting on user_setting.user_id=users.id where users.id=:user_id
UNION
select users.*,users.id as uid,users.city as u_city,users.password as u_password,users.country as u_country,users.gender as u_gender,
users.image as u_image,kids.*,kids.id as kid, kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1, 
(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,user_setting.*, grownup_alias.* from users 
left join `connect` on `connect`.`user_id2`=users.id left join kids on kids.id=`connect`.`kid_id` 
left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
left join user_setting on user_setting.user_id=users.id where users.id=:user_id and connect.status=1";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

if(count($res)){

	foreach($res as $key=>$value){
		if(!ISSET($final[$value['uid']])){
			$final[$value['uid']]=array(
					"user_id"=> $value['uid'],
		            "first_name"=> $value['first_name']?$value['first_name']:"",
		            "last_name"=> $value['last_name']?$value['last_name']:"",
		            "email"=> $value['email'],
					"password"=> $value['u_password'],
		            "city"=> $value['u_city']?$value['u_city']:"",
		            "country"=> $value['u_country']?$value['u_country']:"",
		            "gender"=> $value['u_gender']?$value['u_gender']:"",
					"invitation_count"=>self::getConnectionInvitationCount($value['uid']),
					"all_approved_status"=>self::getAllApproved($value['uid']),
		            //"image"=> $value['u_image']?BASE_PATH."timthumb.php?src=uploads/".$value['u_image']:"",
		            "image"=> $value['u_image']?BASE_PATH."uploads/".$value['u_image']:"",
		            "daily_progress_email"=>$value['daily_progress_email']?$value['daily_progress_email']:"0",
		            "weekly_progress_email"=>$value['weekly_progress_email']?$value['weekly_progress_email']:"0",
		            "weekly_raffle_email"=>$value['weekly_raffle_email']?$value['weekly_raffle_email']:"0",
		            "pending_job_notification"=>$value['pending_job_notification']?$value['pending_job_notification']:"0",
		            "pending_job_email"=>$value['pending_job_email']?$value['pending_job_email']:"0",
		            "buddy_email"=>$value['buddy_email']?$value['buddy_email']:"0",
		            "connection_email"=>$value['connection_email']?$value['connection_email']:"0",
					"kids"=>array()  
			);	
		}
		
		if(!ISSET($final[$value['uid']]['kids'][$value['kid']])){
			if($value['kid']){
				$final[$value['uid']]['kids'][$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:0,
					"is_connected"=>self::getUserRights($user_id,$value['kid']),
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:"0",
					"completed_jobs"=>self::getCompletedJobs($value['kid']),
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"access"=> $value['access']?$value['access']:"0",
					"extra_counts"=>self::get_bonus_counts($value['kid'],2)
				
				);
			}
		}
	}
	
		foreach($final as $key=>$value){
			$data2=array();
		foreach($value['kids'] as $value2){
			$data2[]=$value2;
			}
		$value['kids']=$data2;
					
			$data[]=$value;
		}	
	}
return $data;
}


public static function getConnectionInvitationCount($user_id){

global $conn;

$sql="SELECT count(connect.id) as connect_count from connect where connect.user_id2=:user_id and status=0";
$sth=$conn->prepare($sql);
$sth->bindValue('user_id',$user_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);

$c=$result[0]['connect_count'];
$connect_count=$c?$c:'0';

return $connect_count;
}

public static function getCompletedJobs($kid_id){

global $conn;

$sql="SELECT count(job_status.id) as complete_count FROM `job_status` WHERE job_status.kid_id=:kid_id AND job_status.is_completed=1 AND job_status.is_approved=0
	AND DATE(job_status.created_on) BETWEEN (CURDATE() - INTERVAL 1 DAY) AND (CURDATE()) ";
$sth=$conn->prepare($sql);
$sth->bindValue('kid_id',$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);

$c=$result[0]['complete_count'];
$complete_count=$c?$c:'0';

return $complete_count;
}


//get kids jobs with related details(comments and likes)
public static function get_kids_jobs($kid_id){

global $conn;
$sql="select jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,kids.id as kid,
kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,comments.*,likes.*,
comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,(select count(comments.id) from comments 
WHERE comments.job_id=jobs.id) as comment_count from users left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where kids.id=:kid_id";
$sth=$conn->prepare($sql);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

if(count($res)){
	foreach($res as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:0,
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:0,
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"jobs"=>array()
				);
			}
			}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				$final[$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					//"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
					"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"comments"=>array(),
					"likes"=>array(),
					"days"=>array()		            	
				);
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
						"comment_id"=>$value['coid']?$value['coid']:"",
						"comment"=>$value['comment']?$value['comment']:"",
						"name"=>$value['c_name']?$value['c_name']:"",
						//"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
						"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
						"comment_user_id"=>$value['udid']?$value['udid']:""
				);
			}
			}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
					"like_id"=>$value['lid']?$value['lid']:"",
					"name"=>$value['l_name']?$value['l_name']:"",
					//"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
					"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
					"like_user_id"=>$value['ulid']?$value['ulid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
					"day"=>$value['day']?$value['day']:"",
					"is_completed"=>$value['is_completed']?$value['is_completed']:0,
					"is_approved"=>$value['is_approved']?$value['is_approved']:0
				);
			}
		}					
	}
		
		//indexing of arrays
		foreach($final as $key=>$value){
			$tmp=array();	
			foreach($value['jobs'] as $v2){
				$tmp1=array();
				$tmp2=array();
				$tmp3=array();
				foreach($v2['comments'] as $v3){
					$tmp1[]=$v3;
					}
				foreach($v2['likes'] as $v4){
					$tmp2[]=$v4;
					}
				foreach($v2['days'] as $v5){
				$tmp3[]=$v5;
				}
				$v2['likes']=$tmp2;
				$v2['comments']=$tmp1;	
				$v2['days']=$tmp3;	
				$tmp[]=$v2;
			}
			$value['jobs']=$tmp;
			$data[]=$value;
		}
		
	}
return $data;
}


public static function get_day_jobs($user_id,$flag){

global $conn;
//flag values 1-prev day  2- current day  3- next day

if($flag==1){
$WhereClause="and DATE(job_status.created_on)=CURDATE()- INTERVAL 1 DAY ";
$where="and job_status.day=DAYNAME(CURDATE()-1)";
}

elseif($flag==3){
$WhereClause="and DATE(job_status.created_on)=CURDATE()+ INTERVAL 1 DAY ";
$where="and job_status.day=DAYNAME(CURDATE()+1)";
}
else{
$WhereClause="and DATE(job_status.created_on)=CURDATE() ";
$where="and job_status.day=DAYNAME(CURDATE())";
}


//fetching jobs in the desired week(between start_date and end_date interval)
$sql="SELECT temp.* from (select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,
	job_status.is_completed,job_status.is_approved, job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,
	kids.gender,kids.dob,kids.city,kids.country,kids.plan, kids.id as kid,kids.password as k_password, kids.image as k_image,
	(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,comments.comment, comments.id as coid,likes.id as lid,
	 U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
	 ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
	 ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
	(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
	(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
	left join kids on kids.user_id=users.id 
	left join grownup_alias on grownup_alias.user_id=users.id  
	left join jobs on jobs.kid_id=kids.id 
	left join job_status on job_status.job_id=jobs.id $WhereClause
	left join comments on comments.job_id=jobs.id 
	left join likes on likes.job_id=jobs.id
	left join users as U on U.id=comments.user_id
	left join users as U1 on U1.id=likes.user_id
	where users.id=:user_id 
	UNION
	select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,job_status.is_completed, 
	job_status.is_approved, job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,kids.gender,kids.dob,
	kids.city,kids.country,kids.plan, kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,
	grownup_alias.alias,grownup_alias.access,comments.comment, comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,
	U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
	ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
	ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
	(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
	(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
	left join kids on kids.user_id=users.id 
	left join grownup_alias on grownup_alias.user_id=users.id  
	left join jobs on jobs.kid_id=kids.id 
	left join job_status on job_status.job_id=jobs.id $where
	left join comments on comments.job_id=jobs.id 
	left join likes on likes.job_id=jobs.id
	left join users as U on U.id=comments.user_id
	left join users as U1 on U1.id=likes.user_id
	where users.id=:user_id and jobs.repeat_job=1
	UNION
	select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,job_status.is_completed,
	job_status.is_approved, job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,kids.gender,kids.dob,
	kids.city,kids.country,kids.plan, kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,
	grownup_alias.alias,grownup_alias.access,comments.comment, comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,
	U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
	ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
	ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
	(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
	(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
	join `connect` on `connect`.`user_id2`=users.id and `connect`.status=1
	left join kids on kids.id=`connect`.`kid_id`
	left join grownup_alias on grownup_alias.user_id=users.id  
	left join jobs on jobs.kid_id=kids.id 
	left join job_status on job_status.job_id=jobs.id $WhereClause
	left join comments on comments.job_id=jobs.id 
	left join likes on likes.job_id=jobs.id
	left join users as U on U.id=comments.user_id
	left join users as U1 on U1.id=likes.user_id
	where users.id=:user_id 
	UNION
	select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,job_status.is_completed,
	job_status.is_approved, job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,kids.gender,kids.dob,
	kids.city,kids.country,kids.plan, kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,
	grownup_alias.alias,grownup_alias.access,comments.comment,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,
	U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
	ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
	ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
	(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
	(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
	join `connect` on `connect`.`user_id2`=users.id and `connect`.status=1
	left join kids on kids.id=`connect`.`kid_id`
	left join grownup_alias on grownup_alias.user_id=users.id  
	left join jobs on jobs.kid_id=kids.id 
	left join job_status on job_status.job_id=jobs.id $where
	left join comments on comments.job_id=jobs.id 
	left join likes on likes.job_id=jobs.id
	left join users as U on U.id=comments.user_id
	left join users as U1 on U1.id=likes.user_id
	WHERE users.id=:user_id and jobs.repeat_job=1 ) as temp ORDER BY temp.job_date DESC";

$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);

if(count($result)){
	foreach($result as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
				"kid_id"=>$value['kid']?$value['kid']:"",
				"password"=>$value['k_password']?$value['k_password']:"",
				"name"=>$value['name']?$value['name']:"",
				"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
				"age"=>$value['age']?$value['age']:"0",
				"is_connected"=>self::getUserRights($user_id,$value['kid']),
				"gender"=>$value['gender']?$value['gender']:"",
				"city"=>$value['city']?$value['city']:"",
				"country"=>$value['country']?$value['country']:"",
				"plan"=>$value['plan']?$value['plan']:0,
				"image"=> $value['k_image']?$value['k_image']:"",
				"alias"=> $value['alias']?$value['alias']:"",
				"access"=> $value['access']?$value['access']:"",
				"job_approved_status"=> self::getKidApproved($value['kid']),
				"jobs"=>array(),
				"extra_counts"=>self::get_bonus_counts($value['kid'],$flag)
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
			if($value['day']){
				$final[$value['kid']]['jobs'][$value['jid']]=array(
				"job_id"=>$value['jid']?$value['jid']:"",
				"title"=>$value['title']?$value['title']:"",
				"description"=>$value['description']?$value['description']:"",
				"job_date"=>$value['job_date']?$value['job_date']:"",
				"generic"=>$value['generic']?$value['generic']:"",
				"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
				"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
				//"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
				"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
				"comments_count"=>$value['comment_count']?$value['comment_count']:0,
				"likes_count"=>$value['likes_count']?$value['likes_count']:0,
				"comments"=>array(),
				"likes"=>array(),
				"days"=>array()		            	
				);
			}
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['jid'] && $value['day']){
				if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
					"comment_id"=>$value['coid']?$value['coid']:"",
					"comment"=>$value['comment']?$value['comment']:"",
					"name"=>$value['c_name']?$value['c_name']:"",
					//"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
					"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
					"alias"=>$value['c_alias']?$value['c_alias']:"",
					"comment_user_id"=>$value['udid']?$value['udid']:""
					);
					}
				}
			}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['jid'] && $value['day']){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
					"like_id"=>$value['lid']?$value['lid']:"",
					"name"=>$value['l_name']?$value['l_name']:"",
					"alias"=>$value['l_alias']?$value['l_alias']:"",
					//"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
					"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
					"like_user_id"=>$value['ulid']?$value['ulid']:""
					);
					}
				}
			}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
					"day"=>$value['day']?$value['day']:"",
					"is_completed"=>$value['is_completed']?$value['is_completed']:'0',
					"is_approved"=>$value['is_approved']?$value['is_approved']:'0',
					"is_expired"=>self::getExpiryStatus($value['js_id'])
					
					);
				}
			}				
	}
		
	if(is_array($final)){
		foreach($final as $key=>$value){
			$tmp=array();	
			foreach($value['jobs'] as $v2){
				$tmp1=array();
				$tmp2=array();
				$tmp3=array();
				if(is_array($v2['comments'])){
				foreach($v2['comments'] as $v3){
					$tmp1[]=$v3;
					}
					}
				if(is_array($v2['likes'])){	
				foreach($v2['likes'] as $v4){
					$tmp2[]=$v4;
					}
					}
				if(is_array($v2['days'])){
				foreach($v2['days'] as $v5){
				$tmp3[]=$v5;
				}
				}
				
				$v2['likes']=$tmp2;
				$v2['comments']=$tmp1;	
				$v2['days']=$tmp3;	
				$tmp[]=$v2;
				}
			$value['jobs']=$tmp;
			$data[]=$value;
			}
		}
	}
return $data;

}


//get kids week jobs with related details(comments and likes)
public static function get_kids_week_jobs($kid_id,$flag){

global $conn;

//flag values 1-prev week  2- current week  3- next week

if($flag==1){
$WhereClause="BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY - INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) - INTERVAL 7 DAY) ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY - INTERVAL 7 DAY)";
}
elseif($flag==3){
$WhereClause="BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY + INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) + INTERVAL 7 DAY) ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY + INTERVAL 7 DAY)";
}
else{
$WhereClause="BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY)) ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY)";
}

$sql="select jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,kids.id as kid,
kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,comments.*,likes.*,comments.id as coid,
likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,(select count(comments.id) from comments 
WHERE comments.job_id=jobs.id) as comment_count from users left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id and grownup_alias.kid_id=kids.id 
left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where kids.id=:kid_id AND DATE(job_status.created_on) $WhereClause";
$sth=$conn->prepare($sql);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$res1=$sth->fetchAll();


$sql="select jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,
kids.*,kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id AND grownup_alias.kid_id=kids.id 
left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where kids.id=:kid_id AND jobs.repeat_job=1 $where";
$sth=$conn->prepare($sql);
$sth->bindValue("kid_id",$kid_id);
try{$sth->execute();}
catch(Exception $e){}
$res2=$sth->fetchAll();

if(empty($res1)) $res1=array();
if(empty($res2)) $res2=array();

$result=array_merge_recursive($res1,$res2);

if(count($result)){
	foreach($result as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:"0",
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:"0",
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"jobs"=>array()
				);
			}
			}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				$final[$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					//"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
					"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"comments"=>array(),
					"likes"=>array(),
					"days"=>array()		            	
				);
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
						"comment_id"=>$value['coid']?$value['coid']:"",
						"comment"=>$value['comment']?$value['comment']:"",
						"name"=>$value['c_name']?$value['c_name']:"",
						//"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
						"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
						"comment_user_id"=>$value['udid']?$value['udid']:""
				);
			}
			}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
					"like_id"=>$value['lid']?$value['lid']:"",
					"name"=>$value['l_name']?$value['l_name']:"",
					//"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
					"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
					"like_user_id"=>$value['ulid']?$value['ulid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
					"day"=>$value['day']?$value['day']:"",
					"is_completed"=>$value['is_completed']?$value['is_completed']:"0",
					"is_approved"=>$value['is_approved']?$value['is_approved']:"0"
				);
			}
		}					
	}
		
		//indexing of arrays
		foreach($final as $key=>$value){
			$tmp=array();	
			foreach($value['jobs'] as $v2){
				$tmp1=array();
				$tmp2=array();
				$tmp3=array();
				foreach($v2['comments'] as $v3){
					$tmp1[]=$v3;
					}
				foreach($v2['likes'] as $v4){
					$tmp2[]=$v4;
					}
				foreach($v2['days'] as $v5){
				$tmp3[]=$v5;
				}
				$v2['likes']=$tmp2;
				$v2['comments']=$tmp1;	
				$v2['days']=$tmp3;	
				$tmp[]=$v2;
			}
			$value['jobs']=$tmp;
			$data[]=$value;
		}
		
	}
return $data;
}


public static function get_week_jobs($user_id,$flag){

global $conn;
//flag values 1-prev week  2- current week  3- next week
//4-this month 5- this year 6-all time

//date parameters
$cur_date=gmdate('Y-m-d');  $mon=gmdate("m");  $yr=gmdate("Y");  $d2=$yr.'-'.$mon.'-01'; $d3=$yr.'-'.$mon.'-31';   $d1=$yr.'-01-01'; $d4=$yr.'-12-31';

if($flag==1){
$WhereClause="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY - INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) - INTERVAL 7 DAY) ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY - INTERVAL 7 DAY)";
}
elseif($flag==2){
$WhereClause="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY)) ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY)";
}
elseif($flag==3){
$WhereClause="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY + INTERVAL 7 DAY) AND (SELECT DATE_ADD((SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1)DAY),INTERVAL 7 DAY) + INTERVAL 7 DAY) ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< (SELECT CURDATE() - INTERVAL (WEEKDAY(CURDATE())+1) DAY + INTERVAL 7 DAY)";
}
elseif($flag==4){
$WhereClause="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN '$d2' AND '$d3' ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< '$d2' ";
}
elseif($flag==5){
$WhereClause="AND Date_format(job_status.created_on,'%Y-%m-%d') BETWEEN '$d1' AND '$d4' ";
$where="AND Date_format(job_status.created_on,'%Y-%m-%d')< '$d1' ";
}
else{}


//fetching jobs in the desired week(between start_date and end_date interval)
$sql="SELECT temp.* from  (select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,
job_status.is_completed,job_status.is_approved,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,
kids.gender,kids.dob,kids.city,kids.country,kids.plan,kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,
grownup_alias.alias,grownup_alias.access,comments.comment,comments.id as coid, likes.id as lid,U.first_name as c_name, U.image as c_image,
U.id as udid, U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
left join kids on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id 
left join jobs on jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id $WhereClause 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id 
UNION 
select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,job_status.is_completed,job_status.is_approved,
job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,kids.gender,kids.dob,kids.city,kids.country,kids.plan,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,comments.comment,
comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id  
left join jobs on jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id $where 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id  AND jobs.repeat_job=1
UNION
select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,job_status.is_completed,job_status.is_approved,
job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,kids.gender,kids.dob,kids.city,kids.country,kids.plan,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,comments.comment,
comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
join `connect` on `connect`.`user_id2`=users.id 
left join kids on kids.id=`connect`.`kid_id`
left join grownup_alias on grownup_alias.user_id=users.id  
left join jobs on jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id $WhereClause 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id 
UNION 
select users.id as uid,jobs.title,jobs.description,jobs.generic,jobs.bonus_job,jobs.repeat_job,job_status.day,job_status.is_completed,job_status.is_approved,
job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.name,kids.gender,kids.dob,kids.city,kids.country,kids.plan,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,comments.comment,
comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,U1.image as l_image,U1.id as ulid,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = likes.job_id AND grownup_alias.user_id = likes.user_id ),'') AS l_alias,
ifnull((SELECT grownup_alias.alias FROM grownup_alias JOIN jobs ON jobs.kid_id = grownup_alias.kid_id WHERE jobs.id = comments.job_id AND grownup_alias.user_id = comments.user_id ),'') AS c_alias,
(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
join `connect` on `connect`.`user_id2`=users.id 
left join kids on kids.id=`connect`.`kid_id`
left join grownup_alias on grownup_alias.user_id=users.id  
left join jobs on jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id $where 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id  AND jobs.repeat_job=1 ) as temp order by temp.job_date DESC";

$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);


if(count($result)){
	foreach($result as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
				"kid_id"=>$value['kid']?$value['kid']:"",
				"password"=>$value['k_password']?$value['k_password']:"",
				"name"=>$value['name']?$value['name']:"",
				"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
				"age"=>$value['age']?$value['age']:"0",
				"is_connected"=>self::getUserRights($user_id,$value['kid']),
				"gender"=>$value['gender']?$value['gender']:"",
				"city"=>$value['city']?$value['city']:"",
				"country"=>$value['country']?$value['country']:"",
				"plan"=>$value['plan']?$value['plan']:0,
				"image"=> $value['k_image']?$value['k_image']:"",
				"alias"=> $value['alias']?$value['alias']:"",
				"access"=> $value['access']?$value['access']:"",
				"job_approved_status"=> self::getKidApproved($value['kid']),
				"jobs"=>array(),
				"extra_counts"=>self::get_bonus_counts($value['kid'],$flag)
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				if($value['day']){
					$final[$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					//"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
					"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"job_approved"=>self::getIsApproved($value['jid']),
					"job_approved_count"=>self::getApprovedCount($value['jid']),
					"comments"=>array(),
					"likes"=>array(),
					"days"=>array()		            	
					);
				}
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['jid'] && $value['day']){
			if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
					"comment_id"=>$value['coid']?$value['coid']:"",
					"comment"=>$value['comment']?$value['comment']:"",
					"name"=>$value['c_name']?$value['c_name']:"",
					"alias"=>$value['c_alias']?$value['c_alias']:"",
					//"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
					"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
					"comment_user_id"=>$value['udid']?$value['udid']:""
					);
				}
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['jid'] && $value['day']){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
					"like_id"=>$value['lid']?$value['lid']:"",
					"name"=>$value['l_name']?$value['l_name']:"",
					"alias"=>$value['l_alias']?$value['l_alias']:"",
					//"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
					"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
					"like_user_id"=>$value['ulid']?$value['ulid']:""
					);
				}
			}
		}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
					"day"=>$value['day']?$value['day']:"",
					"is_completed"=>$value['is_completed']?$value['is_completed']:'0',
					"is_approved"=>$value['is_approved']?$value['is_approved']:'0',
					"is_expired"=>self::getExpiryStatus($value['js_id'])
					);
				}
			}		
	}
		
	if(is_array($final)){
		foreach($final as $key=>$value){
			$tmp=array();	
			foreach($value['jobs'] as $v2){
				$tmp1=array();
				$tmp2=array();
				$tmp3=array();
				foreach($v2['comments'] as $v3){
					$tmp1[]=$v3;
					}
				foreach($v2['likes'] as $v4){
					$tmp2[]=$v4;
					}
				foreach($v2['days'] as $v5){
				$tmp3[]=$v5;
				}
				
				$v2['likes']=$tmp2;
				$v2['comments']=$tmp1;	
				$v2['days']=$tmp3;	
				$tmp[]=$v2;
				}
			$value['jobs']=$tmp;
			$data[]=$value;
			}
		}
	}
	
return $data;

}

public static function getExpiryStatus($job_status_id){

global $conn;

$sql="SELECT count(job_status.id) as j_count FROM `job_status` WHERE job_status.id=:job_status_id and DATE(job_status.created_on) < (UTC_DATE() + INTERVAL 2 DAYS)";
$sth=$conn->prepare($sql);
$sth->bindValue("job_status_id",$job_status_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();
$stats=$res[0]['j_count'];
$status=$stats?'1':'0';

return $status;
}


//fetching whether all jobs are approved or not
public static function getIsApproved($job_id){

global $conn;
$sql="SELECT count(job_status.is_approved) as is_approved from job_status WHERE job_status.job_id=:job_id and job_status.is_approved=1";
$sth=$conn->prepare($sql);
$sth->bindValue("job_id",$job_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();
$stats=$res[0]['is_approved'];
$status=$stats?'1':'0';

return $status;
}


public static function getApprovedCount($job_id){

global $conn;
$sql="SELECT count(job_status.is_approved) as is_approved from job_status WHERE job_status.job_id=:job_id and job_status.is_approved=1";
$sth=$conn->prepare($sql);
$sth->bindValue("job_id",$job_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();
$stats=$res[0]['is_approved'];

return $stats;
}


//fetching saved jobs data by user id
public static function get_saved_jobs($user_id){

global $conn;
$sql="select users.id as uid,jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id  left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id and jobs.generic=1
UNION
select users.id as uid,jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
join `connect` on `connect`.`user_id2`=users.id 
left join kids on kids.id=`connect`.`kid_id`
left join grownup_alias on grownup_alias.user_id=users.id  
left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id and jobs.generic=1";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

if(count($res)){
	foreach($res as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:0,
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:0,
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"access"=> $value['access']?$value['access']:"",
					"jobs"=>array()
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				$final[$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"0",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					//"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
					"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"comments"=>array(),
					"likes"=>array(),
					"days"=>array()		            	
				);
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
				"comment_id"=>$value['coid']?$value['coid']:"",
				"comment"=>$value['comment']?$value['comment']:"",
				"name"=>$value['c_name']?$value['c_name']:"",
				//"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
				"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
				"comment_user_id"=>$value['udid']?$value['udid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
				"like_id"=>$value['lid']?$value['lid']:"",
				"name"=>$value['l_name']?$value['l_name']:"",
				//"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
				"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
				"like_user_id"=>$value['ulid']?$value['ulid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
				"day"=>$value['day']?$value['day']:"",
				"is_completed"=>$value['is_completed']?$value['is_completed']:'0',
				"is_approved"=>$value['is_approved']?$value['is_approved']:'0'
				
				);
			}
		}			
	  		
			
		}
		
	if(is_array($final)){
	foreach($final as $key=>$value){
		$tmp=array();	
	foreach($value['jobs'] as $v2){
		$tmp1=array();
		$tmp2=array();
		$tmp3=array();
	foreach($v2['comments'] as $v3){
		$tmp1[]=$v3;
		}
	foreach($v2['likes'] as $v4){
		$tmp2[]=$v4;
		}
	foreach($v2['days'] as $v5){
	$tmp3[]=$v5;
	}
		$v2['likes']=$tmp2;
		$v2['comments']=$tmp1;	
		$v2['days']=$tmp3;	
		$tmp[]=$v2;
		}
		$value['jobs']=$tmp;
		$data[]=$value;
		}
		}
	}
return $data;
}


//fetching history jobs data by user id
public static function get_history_jobs($user_id){

global $conn;
$sql="SELECT users.id as uid,jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id  left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id and jobs.history_job=1
UNION
SELECT users.id as uid,jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
join `connect` on `connect`.`user_id2`=users.id 
left join kids on kids.id=`connect`.`kid_id`
left join grownup_alias on grownup_alias.user_id=users.id  
left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id and jobs.history_job=1";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

if(count($res)){
	foreach($res as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:0,
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:0,
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"access"=> $value['access']?$value['access']:"",
					"jobs"=>array()
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				$final[$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"0",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					//"image"=> $value['j_image']?BASE_PATH."timthumb.php?src=uploads/".$value['j_image']:"",
					"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"comments"=>array(),
					"likes"=>array(),
					"days"=>array()		            	
				);
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
				"comment_id"=>$value['coid']?$value['coid']:"",
				"comment"=>$value['comment']?$value['comment']:"",
				"name"=>$value['c_name']?$value['c_name']:"",
				//"image"=>$value['c_image']?BASE_PATH."timthumb.php?src=uploads/".$value['c_image']:"",
				"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
				"comment_user_id"=>$value['udid']?$value['udid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
				"like_id"=>$value['lid']?$value['lid']:"",
				"name"=>$value['l_name']?$value['l_name']:"",
				//"image"=>$value['l_image']?BASE_PATH."timthumb.php?src=uploads/".$value['l_image']:"",
				"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
				"like_user_id"=>$value['ulid']?$value['ulid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
				"day"=>$value['day']?$value['day']:"",
				"is_completed"=>$value['is_completed']?$value['is_completed']:'0',
				"is_approved"=>$value['is_approved']?$value['is_approved']:'0'
				
				);
			}
		}			
	  		
			
		}
		
	if(is_array($final)){
	foreach($final as $key=>$value){
		$tmp=array();	
	foreach($value['jobs'] as $v2){
		$tmp1=array();
		$tmp2=array();
		$tmp3=array();
	foreach($v2['comments'] as $v3){
		$tmp1[]=$v3;
		}
	foreach($v2['likes'] as $v4){
		$tmp2[]=$v4;
		}
	foreach($v2['days'] as $v5){
	$tmp3[]=$v5;
	}
		$v2['likes']=$tmp2;
		$v2['comments']=$tmp1;	
		$v2['days']=$tmp3;	
		$tmp[]=$v2;
		}
		$value['jobs']=$tmp;
		$data[]=$value;
		}
		}
	}
return $data;
}





//fetching job data by user id
public static function get_jobs_data($user_id){

global $conn;
$sql="select users.id as uid,jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users left join kids  on kids.user_id=users.id 
left join grownup_alias on grownup_alias.user_id=users.id  left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id
UNION
select users.id as uid,jobs.*,job_status.*,job_status.id as js_id,jobs.created_on as job_date,jobs.id as jid,jobs.image as j_image,kids.*,
kids.id as kid,kids.password as k_password, kids.image as k_image,(YEAR(NOW())-YEAR(kids.dob)) AS age1,(SELECT FLOOR(DATEDIFF( NOW(),kids.dob) / 365.25)) as age,grownup_alias.alias,grownup_alias.access,
comments.*,likes.*,comments.id as coid,likes.id as lid,U.first_name as c_name,U.image as c_image,U.id as udid,U1.first_name as l_name,
U1.image as l_image,U1.id as ulid,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,
(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users 
join `connect` on `connect`.`user_id2`=users.id 
left join kids on kids.id=`connect`.`kid_id`
left join grownup_alias on grownup_alias.user_id=users.id  
left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id 
left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id 
left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=:user_id";
$sth=$conn->prepare($sql);
$sth->bindValue("user_id",$user_id);
try{$sth->execute();}
catch(Exception $e){}
$res=$sth->fetchAll();

if(count($res)){
	foreach($res as $key=>$value){		
		if(!ISSET($final[$value['kid']])){
			if($value['kid']){
				$final[$value['kid']]=array(
					"kid_id"=>$value['kid']?$value['kid']:"",
					"password"=>$value['k_password']?$value['k_password']:"",
					"name"=>$value['name']?$value['name']:"",
					"dob"=> $value['dob']?date_format(date_create($value['dob']),'d-m-Y'):"",
					"age"=>$value['age']?$value['age']:0,
					"gender"=>$value['gender']?$value['gender']:"",
					"city"=>$value['city']?$value['city']:"",
					"country"=>$value['country']?$value['country']:"",
					"plan"=>$value['plan']?$value['plan']:0,
					"image"=> $value['k_image']?$value['k_image']:"",
					"alias"=> $value['alias']?$value['alias']:"",
					"access"=> $value['access']?$value['access']:"",
					"jobs"=>array()
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']])){
			if($value['jid']){
				$final[$value['kid']]['jobs'][$value['jid']]=array(
					"job_id"=>$value['jid']?$value['jid']:"",
					"title"=>$value['title']?$value['title']:"",
					"description"=>$value['description']?$value['description']:"",
					"job_date"=>$value['job_date']?$value['job_date']:"",
					"generic"=>$value['generic']?$value['generic']:"",
					"bonus_job"=>$value['bonus_job']?$value['bonus_job']:"0",
					"repeat_job"=>$value['repeat_job']?$value['repeat_job']:"0",
					"image"=> $value['j_image']?BASE_PATH."uploads/".$value['j_image']:"",
					"comments_count"=>$value['comment_count']?$value['comment_count']:0,
					"likes_count"=>$value['likes_count']?$value['likes_count']:0,
					"comments"=>array(),
					"likes"=>array(),
					"days"=>array()		            	
				);
			}
		}	
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']])){
			if($value['coid']){
				$final[$value['kid']]['jobs'][$value['jid']]['comments'][$value['coid']]=array(
				"comment_id"=>$value['coid']?$value['coid']:"",
				"comment"=>$value['comment']?$value['comment']:"",
				"name"=>$value['c_name']?$value['c_name']:"",
				"image"=>$value['c_image']?BASE_PATH."uploads/".$value['c_image']:"",
				"comment_user_id"=>$value['udid']?$value['udid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']])){
			if($value['lid']){
				$final[$value['kid']]['jobs'][$value['jid']]['likes'][$value['lid']]=array(
				"like_id"=>$value['lid']?$value['lid']:"",
				"name"=>$value['l_name']?$value['l_name']:"",
				"image"=>$value['l_image']?BASE_PATH."uploads/".$value['l_image']:"",
				"like_user_id"=>$value['ulid']?$value['ulid']:""
				);
			}
		}
			
		if(!ISSET($final[$value['kid']]['days'][$value['js_id']])){
			if($value['js_id']){
				$final[$value['kid']]['jobs'][$value['jid']]['days'][$value['js_id']]=array(
				"day"=>$value['day']?$value['day']:"",
				"is_completed"=>$value['is_completed']?$value['is_completed']:'0',
				"is_approved"=>$value['is_approved']?$value['is_approved']:'0'
				
				);
			}
		}			
	  		
			
		}
		
	if(is_array($final)){
	foreach($final as $key=>$value){
		$tmp=array();	
	foreach($value['jobs'] as $v2){
		$tmp1=array();
		$tmp2=array();
		$tmp3=array();
	foreach($v2['comments'] as $v3){
		$tmp1[]=$v3;
		}
	foreach($v2['likes'] as $v4){
		$tmp2[]=$v4;
		}
	foreach($v2['days'] as $v5){
	$tmp3[]=$v5;
	}
		$v2['likes']=$tmp2;
		$v2['comments']=$tmp1;	
		$v2['days']=$tmp3;	
		$tmp[]=$v2;
		}
		$value['jobs']=$tmp;
		$data[]=$value;
		}
		}
	}
return $data;
}


public static function sendEmail($email,$subjectMail,$bodyMail,$email_back){

	$mail = new PHPMailer(true); 
	$mail->IsSMTP(); // telling the class to use SMTP
	try {
	  //$mail->Host       = SMTP_HOST; // SMTP server
	  $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
	  $mail->SMTPAuth   = true;                  // enable SMTP authentication
	  //$mail->SMTPSecure = 'ssl';
	  $mail->Host       = SMTP_HOST; // sets the SMTP server
	  $mail->Port       = SMTP_PORT;                    // set the SMTP port for the GMAIL server
	  $mail->Username   = SMTP_USER; // SMTP account username
	  $mail->Password   = SMTP_PASSWORD;        // SMTP account password
	  $mail->AddAddress($email, '');     // SMTP account password
	  $mail->SetFrom(SMTP_EMAIL, SMTP_NAME);
	  $mail->AddReplyTo($email_back, SMTP_NAME);
	  $mail->Subject = $subjectMail;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';  // optional - MsgHTML will create an alternate automaticall//y
	  $mail->MsgHTML($bodyMail) ;
	  if(!$mail->Send()){
			$success='0';
			$msg="Error in sending mail";
	  }else{
			$success='1';
	  }
	} catch (phpmailerException $e) {
	  $msg=$e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  $msg=$e->getMessage(); //Boring error messages from anything else!
	}
	
	//echo $msg;
}

}	
	
?>	
