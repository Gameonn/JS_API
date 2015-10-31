<?php
Yii::import('application.modules.rights.extensions.ThumbnailImages.thumbnail_images');
class RewardController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='/layouts/column1';
	private $_authorizer;
	public function init()
	{
		$this->_authorizer = $this->module->getAuthorizer();
		$this->layout = $this->module->layout;
		$this->defaultAction = 'admin';

		// Register the scripts
		$this->module->registerScripts();
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$userId = Yii::app()->user->id;
        if($userId) {
        return array(
			array('allow', // Allow superusers to access Rights
				'actions'=>array(
					'view',
                    'admin',
                    'delete',
                    'update',
                    'imagedel',
				            'create',
                    'approve',
                    'deactivate',
                    'savequantity',
                    'search',
					'user',
                    'superdelete',
					'revoke',
				),
				'users'=>$this->_authorizer->getSuperusers(),
			),
			array('allow', // Deny all users
				'users'=>array('*'),
			),
		);
        }else{
            //$this->redirect(array('backend.php?r=site/login'));
            $this->redirect('backend.php?r=site/login');
        }
	}




		/**
	* Displays an overview of the users and their assignments.
	*/
	public function actionView($id)
	{
		if(isset($_POST['defaultImageButton']) && $_POST['setDefaultImage']!=''){
            //make the default image field to zero, which is set by developer while adding image.
            //get default image id where 1, set it to 0
            if($_POST['reward_id']!='') {
                $rewardId = $_POST['reward_id'];
                $sql    = "SELECT id from rewardimages where reward_id = $rewardId AND defaultimage='1'";    
                $getSql = Yii::app()->db->createCommand($sql)->queryRow();
                if($getSql) {
                    $getDefaultImageId = $getSql['id'];
                    if($getDefaultImageId!=''){
                        //update the tabele
                        $update1 = Yii::app()->db->createCommand()->update('rewardimages', array('defaultimage'=>'0',),'id=:id',array(':id'=>$getDefaultImageId));

                    }
                }
            }
            $imageId = $_POST['setDefaultImage'];
            //set the default image in images table and redirect it to admin view page
            $update1 = Yii::app()->db->createCommand()->update('rewardimages', array('defaultimage'=>'1',),'id=:id',array(':id'=>$imageId));

            if($update1){
                $this->redirect(array('admin'));
            }
        }

        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
	}
	




	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Reward');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

			
			$model=new Reward('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Reward']))
			$model->attributes=$_GET['Reward'];

			$this->render('admin',array(
			'model'=>$model,
			));
		
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Reward::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	 public function getStatus($data)
    {
     
     $output = '';
     if($data->status==1)
      {
        $showactive='style="display:block"';
        $showdeactive='style="display:none"';
      }
      else
      {
         $showactive='style="display:none"';
         $showdeactive='style="display:block"';
      }
       $output .='<div id="divstatusA"><div id="activebutton_'.$data->id.'" '.$showactive.'>Active</br>'; 
       $output.='<input type="button" name="deactivate" class="deactivate btn btn-primary" data-id="'.$data->id.'" value="Deactivate Reward">';
       $output .='</div></div>';
      
       $output .='<div id="divstatusD"><div id="deactivebutton_'.$data->id.'" '.$showdeactive.'>Inactive<br/>';
       $output.='<input type="button" name="activate" class="activate btn btn-primary" data-id="'.$data->id.'" value="Activate Reward">';     
       $output.='</div></div>';
      echo $output;
    }



public function actionCreate()
	{
		$this->pageTitle = 'Jobstar - Create Reward';  

        error_reporting(E_ALL & ~E_NOTICE);
        $model = new Reward;
        $rewardImagesModel = new RewardImages;
        $Tags = new Tags;
        if(isset($_POST['Reward']) && !empty($_POST['Reward'])) {
            //check product name duplicacy
            $chkTitle = $_POST['Reward']['title'];
            $chkPrice = $_POST['Reward']['price'];
            $chkCategory = $_POST['Reward']['category_id'];
            $chkRewardExist = Reward::model()->find('title = :title', array(':title' => $chkTitle));
            if(!empty($chkRewardExist) )
            {
                Yii::app()->user->setFlash('errDupTitle','Reward name exist in database, please enter another.');
                $this->redirect(array('create', 'titleSet' => $chkTitle, 'priceSet' => $chkPrice, 'categorySet'=>$chkCategory));
            }

            //end checking

            //check image is uploaded or not
            $chkImageUploaded = CUploadedFile::getInstancesByName('images');
            if(isset($chkImageUploaded) && count($chkImageUploaded) > 0) {
              //do nothing.... this is done only for validation
            } // if image is uploaded by admin
            else{
                //please select image
                Yii::app()->user->setFlash('imageErrMsg','Please upload image.');
                $model->attributes  = $_POST['Reward'];
                //$this->redirect(array('create'));
                $this->redirect(array('create', 'titleSet' => $chkTitle, 'priceSet' => $chkPrice, 'categorySet'=>$chkCategory));

            }
            // end validation
            $model->attributes  = $_POST['Reward'];
            $model->category_id = $_POST['Reward']['category_id'];
            $model->status = '1';
            $model->description = $_POST['Reward']['description'];
            //echo "<pre>";
            //print_r($model->attributes);
            if($model->save()) {
                //echo "----";
                    $useridFolder = $model->id;
                    if(isset($_POST['tag']) && $_POST['tag']!=''){
                        $getTags = $_POST['tag'];
                        $mystring = $getTags;
                        $findme   = ',';
                        $pos = strpos($mystring, $findme);
                        if($pos === false) {
                            //echo "The string '$findme' was not found in the string '$mystring'";
                            //if that tag name already exist, skip that tag
                            $chkTagExist = Tags::model()->find('name = :name', array(':name' => $getTags));
                            if(empty($chkTagExist) )
                            {
                             // echo "Tag not exist";
                                 $insertMultiDoc=Yii::app()->db->createCommand()->insert('tags',array(
                                'reward_id'=>$model->id,
                                'name'=>$getTags,
                                 ));
                            }
                            else
                            {
                              //echo "dont save the record";
                            }
                           
                        } else {
                            //echo "The string '$findme' was found in the string '$mystring'";
                            //echo " and exists at position $pos";
                            //explode with comma
                             $getTagArray = explode(',',$getTags);
                             //insert into tags table with reward model id.
                             foreach ($getTagArray as $tagVal) {
                                //if that tag name already exist, skip that tag
                                $chkTagExist = Tags::model()->find('name = :name', array(':name' => $tagVal));
                                if( empty($chkTagExist) )
                                {
                                 // echo "Tag not exist";
                                    $insertMultiDoc=Yii::app()->db->createCommand()->insert('tags',array(
                                    'reward_id'=>$model->id,
                                    'name'=>$tagVal,
                                 ));
                                }
                                else
                                {
                                  //echo "dont save the record";
                                }
                                
                            }
                        }
                    } //end tags
                    $images = CUploadedFile::getInstancesByName('images');
                    //echo "<pre>";
                    //print_r($images);
                    // proceed if the images have been set
                    if(isset($images) && count($images) > 0) {
                        //upload multiple images, first create folder with reward id and store all related images in that folder.
                        // create folder
                        if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
                             $setDir =  $_SERVER['DOCUMENT_ROOT']."/images/reward/".$useridFolder;

                         }else{
                            $setDir =  $_SERVER['DOCUMENT_ROOT']."/jobstar/images/reward/".$useridFolder;
                        }
                        if(is_dir($setDir)==false){
                            mkdir("$setDir", 0777,true);
                            chmod("$setDir", 0777);
                        }else{
                            chmod("$setDir", 0777);
                        }
                        // go through each uploaded image
                        $i=0;
                        foreach ($images as $thefile) {
                            if($i == '0'){ //first image will be default
                                $setDefaultStatus = '1';
                            }else{
                                $setDefaultStatus = '0';
                            }
                            //echo $pic->name.'<br />'  error, size;
                           //$finalName = $setDir."/".$pic->name;
                           //echo "<br>";
                           $imagetime = time();
                           $image=$thefile;
                           //$type      = $pic->type;
                           $type=$thefile->extensionName;
                           $destination = $_SERVER['DOCUMENT_ROOT']."/jobstar/images/reward/".$useridFolder;
                           
                           $finalName = $setDir."/".$imagetime."_".$i;
                           $thumbnailImage = $setDir."/".$imagetime."_".$i;
                            if($image->saveAs($finalName.'.'.$type)) {   
                                $dbFileName = $imagetime."_".$i.'.'.$type;
                                $chkThumb = $this->createimage(270,200,$thumbnailImage,'m',$type);
                                if($chkThumb == 0) { 
                                 // add it to the main model now
                                $insertMultiDoc=Yii::app()->db->createCommand()->insert('rewardimages',array(
                                'reward_id'=>$model->id,
                                'image'=>$dbFileName,
                                'defaultImage'=>$setDefaultStatus,
                                 ));
                                 $chkThumb = $this->createimage(270,200,$thumbnailImage,'m',$type);
                               } else{

                                  $this->redirect(array('create'));
                               }
                            }
                            else{
                                // handle the errors here, if you want
                            }
         
                       $i++;  }//end foreach
                       

                    } // if image is uploaded by admin
                    else{
                        //please select image
                        Yii::app()->user->setFlash('imageErrMsg','Please upload image.');

                    }
            }
        	//die('end');
            $this->redirect(array('admin', 'id' => $model->id));

        } //end post method


		 $this->render('create',array(
            'model'=>$model,
        ));
	}


	        /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionSuperdelete($id,$rewardId)
    {
        //delete single image from update page
        $command = Yii::app()->db->createCommand();
        $sql='DELETE FROM rewardimages WHERE id=:id';
        $params = array(
            "id" => $id,
            
        );
        $command->setText($sql)->execute($params);
        //echo "Image deleted.";
        if($rewardId) {
            $list1 = '';
            $sql  = "SELECT image,id from rewardimages where reward_id = $rewardId";  
            $list = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($list as $key => $value) {
                $imageId = $value['id'];
                $setImage = Yii::app()->baseUrl."/images/reward/".$id."/".$value['image'];
                //$list1 .= ",".$value['image'];
                echo '<div id="Div_'.$imageId.'"><img src="'.$setImage.'"  width="150px"  /><button class="btn btn-primary btn-Margin"  type="button" onclick="loadXMLDoc('.$rewardId.','.$imageId.',\'Div_'.$imageId.'\')">Delete</button></div>
';
            }
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        //delete all its related images
        $command = Yii::app()->db->createCommand();

        $sql='DELETE FROM rewardimages WHERE reward_id=:reward_id';
        $params = array(
            "reward_id" => $id,
            
        );
        $command->setText($sql)->execute($params);


        $sqlTag='DELETE FROM tags WHERE reward_id=:reward_id';
        $params = array(
            "reward_id" => $id,
            
        );
        $command->setText($sqlTag)->execute($params);


        //end del
        //delete main reward
        $this->loadModel($id)->delete();

        //die;
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

        public function actionApprove()
    {
      //echo "yyy";
        $id=$_REQUEST['id'];
     if(!empty($id))
     {
       $sql    = "Update reward set status='1' where id = $id";  
       
       $update = Yii::app()->db->createCommand($sql)->execute();
       $this->redirect(array('admin', 'id' => $id));
       if($update)
       {

            echo "Reward activated by admin.";

            //echo "Reward deactivated by admin.";
           /*$output ='<div id="activebutton_'.$id.'">Active</br>'; 
           $output.='<input type="button" name="deactivate" class="deactivate btn btn-primary" data-id="'.$id.'" value="Deactivate Reward">';
           $output .='</div>';
            echo $output;*/
             

        }
        }
    }


    public function actionDeactivate()
    {   
    $id=$_REQUEST['id'];
    
     if(!empty($id))
     {
       
       $update = Yii::app()->db->createCommand()
        ->update('reward', 
        array(
            'status'=>'0',
        ),
        'id=:id',
        array(':id'=>$id)
        );
       
       if($update)
       {
            echo "Reward deactivated by admin.";

            //$this->redirect(array('admin', 'id' => $id));

            /*$output ='<div id="divstatusD"><div id="deactivebutton_'.$id.'">Inactive<br/>';
             $output.='<input type="button" name="activate" class="activate btn btn-primary" data-id="'.$id.'" value="Approve Reward">';     
             $output.='</div></div>';
              echo $output;*/
        }
       
    
        }
    }

    public function actionUpdate($id){

        $this->pageTitle = 'Jobstar - Update Reward';  
        //die('ssss');
        $model=$this->loadModel($id);
        $Category=new Category;
        $Tags=new Tags;
        $RewardImages = new RewardImages;
        if(isset($_POST['Reward']) && !empty($_POST['Reward'])) {
          //echo "<pre>";
          //print_r($_POST);
          //echo "<pre>";
          //print_r($_FILES);
          //die;
            $model->attributes  = $_POST['Reward'];
            $model->category_id = $_POST['Reward']['category_id'];
            $model->description = $_POST['Reward']['description'];
            //echo "<pre>";
            //print_r($model->attributes);
            if($model->save()) {
                //echo "----";
                    $useridFolder = $model->id;
                    if(isset($_POST['tags'])){
                        $getTags = $_POST['tags'];
                        $mystring = $getTags;
                        $findme   = ',';
                        $pos = strpos($mystring, $findme);
                        if($pos === false) {

                            //echo "The string '$findme' was not found in the string '$mystring'";
                            //if that tag name already exist, skip that tag
                            $chkTagExist = Tags::model()->find('name = :name', array(':name' => $getTags));
                            if( empty($chkTagExist) )
                            {
                             // echo "Tag not exist";
                                 $insertMultiDoc=Yii::app()->db->createCommand()->insert('tags',array(
                                'reward_id'=>$model->id,
                                'name'=>$getTags,
                                 ));
                            }
                            else
                            {
                              //echo "dont save the Tag record";
                            }
                           
                        } else {
                            //echo "The string '$findme' was found in the string '$mystring'";
                            //echo " and exists at position $pos";
                            //explode with comma
                             $getTagArray = explode(',',$getTags);
                             //insert into tags table with reward model id.
                             foreach ($getTagArray as $tagVal) {
                                //if that tag name already exist, skip that tag
                                $chkTagExist = Tags::model()->find('name = :name', array(':name' => $tagVal));
                                if( empty($chkTagExist) )
                                {
                                 // echo "Tag not exist";
                                    $insertMultiDoc=Yii::app()->db->createCommand()->insert('tags',array(
                                    'reward_id'=>$model->id,
                                    'name'=>$tagVal,
                                 ));
                                }
                                else
                                {
                                  //echo "dont save the Tag record";
                                }
                                
                            }
                        }
                    } //end tags
                    $images = CUploadedFile::getInstancesByName('images');
                    //echo "<pre>";
                    //print_r($images);
                    // proceed if the images have been set
                    if (isset($images) && count($images) > 0) {
                        //upload multiple images, first create folder with reward id and store all related images in that folder.
                        // create folder
                        if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
                             $setDir =  $_SERVER['DOCUMENT_ROOT']."/images/reward/".$useridFolder;

                         }else{
                            $setDir =  $_SERVER['DOCUMENT_ROOT']."/jobstar/images/reward/".$useridFolder;
                        }
                        if(is_dir($setDir)==false){
                            mkdir("$setDir", 0777,true);
                            chmod("$setDir", 0777);
                        }else{
                            chmod("$setDir", 0777);
                        }
                        //end
                       //echo  $setDir;
                       //die;
                        // go through each uploaded image
                        $i=0;
                        //foreach ($images as $image => $pic) {
                        foreach ($images as $thefile) {
                          //echo "i am in images";
                        
                            if($i == '0'){ //first image will be default
                                $setDefaultStatus = '1';
                            }else{
                                $setDefaultStatus = '0';
                            }
                                                        //echo $pic->name.'<br />'  error, size;
                           //$finalName = $setDir."/".$pic->name;
                           //echo "<br>";
                           $imagetime = time();
                           $image=$thefile;
                           //$type      = $pic->type;
                           $type=$thefile->extensionName;
                           $destination = $_SERVER['DOCUMENT_ROOT']."/jobstar/images/reward/".$useridFolder;
                           
                           $finalName = $setDir."/".$imagetime."_".$i;
                           $thumbnailImage = $setDir."/".$imagetime."_".$i;
                            if($image->saveAs($finalName.'.'.$type)) {   
                                $dbFileName = $imagetime."_".$i.'.'.$type;
                                $chkThumb = $this->createimage(270,200,$thumbnailImage,'m',$type);
                                if($chkThumb == 0) { 
                                 // add it to the main model now
                                $insertMultiDoc=Yii::app()->db->createCommand()->insert('rewardimages',array(
                                'reward_id'=>$model->id,
                                'image'=>$dbFileName,
                                'defaultImage'=>$setDefaultStatus,
                                 ));
                                 $chkThumb = $this->createimage(270,200,$thumbnailImage,'m',$type);
                               } else{

                                  $this->redirect(array('update'));
                               }
                            }
                            else{
                                // handle the errors here, if you want
                            }
                       $i++;  }//end foreach
                    } // if image is uploaded by admin
                    /*else{
                        //please select image
                        Yii::app()->user->setFlash('imageErrMsg','Please upload image.');

                    }*/
            }
            //die('end');
            $this->redirect(array('admin', 'id' => $model->id));

        } //end post method


        $this->render('update',array(
      'model'=>$model,'Category'=>$Category,'Tags'=>$Tags,'RewardImages'=>$RewardImages
      ));


    }


    public function createimage($width, $height, $destination, $thumb, $type) {
               //echo $type;
               //echo "<br>";
                $obj_imgl = new thumbnail_images;
                $obj_imgl->SetImageType = $type;
                $obj_imgl->PathImgOld = $destination.".".$type;
                $obj_imgl->PathImgNew = $destination."$thumb.".$type;
                $obj_imgl->NewWidth = $width;
                if($height!=''){
                        $obj_imgl->NewHeight = $height;
                }
                //echo "<pre>";
                //print_r($obj_imgl);
                //die;
                if(!$obj_imgl->create_thumbnail_images()){
                    //echo "error";
                    //die('not uploaded');
                    return 1;
                } else{
                  return 0;
                }
                        
        }


      public function tagAutoSuggest(){
        $q=$_GET['q'];
        $my_data=mysql_real_escape_string($q);
        $sql="SELECT name FROM tags WHERE name LIKE '%$my_data%' ORDER BY name";
        //$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        if($list)
        {
            //while($row=mysqli_fetch_array($result))
            foreach ($list as $key => $value) {
            
                echo $value['name']."\n";
            }
        }


      }  

      public function actionSavequantity($id){
        $model=new Reward();
        //update the quantity field and add in new table
        if(!empty($id))
         {
            if(isset($_POST['qty'])) {
              $postedQty = $_POST['qty'];
            }else{
              $postedQty = '1';
            }
           //get the existing quantity from db and add the posted quanity in it
           $getQuantity = "SELECT instock from reward where id = $id";    
           $getQtyValue = Yii::app()->db->createCommand($getQuantity)->queryRow();
           $setQuantity = ($getQtyValue['instock']+$postedQty);
           
           $sql    = "Update reward set instock=$setQuantity where id = $id";  
           $update = Yii::app()->db->createCommand($sql)->execute();
           if($update)
           {
                echo "Quantity updated by admin.";
                $insertNewQuantity=Yii::app()->db->createCommand()->insert('quantityrecord',array(
                                'reward_id'=>$id,
                                'quantity'=>$setQuantity,
                                 ));

            }
              //$this->redirect(array('admin', 'id' => $id));

          }

      }

	

}
