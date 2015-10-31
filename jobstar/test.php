<?php
//this is an api to add posts

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
//require_once("../php_include/db_connection.php");
//require_once("../classes/AllClasses.php");

//random file name generator
function randomFileNameGenerator($prefix){
	$r=substr(str_replace(".","",uniqid($prefix,true)),0,20);
	if(file_exists("../uploads/$r")) randomFileNameGenerator($prefix);
	else return $r;
}

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$image[]=$_FILES['post_image'];


// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+


		for($index=0;$index<count($_FILES["post_image"]["name"]);$index++){			
			if(!empty($_FILES["post_image"]["name"][$index])){
				if($_FILES["post_image"]["error"][$index] == 0){
				
						$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$image['name']));
						$target_dir = "uploads/".$randomFileName;
					if(move_uploaded_file($_FILES["post_image"]["tmp_name"][$index], $target_dir)){
						$tmu[]=$randomFileName;
						
					}
				}					
			}
		}
	//print_r($tmu);
	$jc= json_encode($tmu);
	 
	 $rm=json_decode($jc,true);
	
	 
	 $data[]=array(
	 
	 
	 "user_id"=>'1',
	 "images"=>json_encode($rm)
	 );
	 
	 
	 
// +-----------------------------------+
// + STEP 4: send json data		    +
// +-----------------------------------+

echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));

?>