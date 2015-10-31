<?php
/**
* Rights assignment controller class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.1
*/
class RewardController extends RController
{
	/**
	* @property RAuthorizer
	*/
	private $_authorizer;

	/**
	* Initializes the controller.
	*/
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
		return array('accessControl');
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
     public function actionIndex()
    {
        if (!Yii::app()->user->isGuest)
            $this->render('admin-dashboard');
        else
            $this->redirect(array('login'));
    }
	/**
	* Displays an overview of the users and their assignments.
	*/
	public function actionView($id)
	{
		$this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
	}
    



    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Doc the loaded model
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
     * Manages all models.
     */
    public function actionAdmin()
    {
       

        $model=new Reward('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['reward']))
            $model->attributes=$_GET['reward'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }


    public function actionCreate()
	{
		$this->pageTitle = 'Jobstar - Create Reward';  

        error_reporting(E_ALL & ~E_NOTICE);
        $model = new Reward;
        $rewardImagesModel = new RewardImages;
        $Tags = new Tags;
        if(isset($_POST['Reward']) && !empty($_POST['Reward'])) {
            $model->attributes  = $_POST['Reward'];
            $model->category_id = $_POST['Reward']['category_id'];
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
                        }
                        //end
                       //echo  $setDir;
                       //die;
                        // go through each uploaded image
                        $i=0;
                        foreach ($images as $image => $pic) {
                        
                            if($i == '0'){ //first image will be default
                                $setDefaultStatus = '1';
                            }else{
                                $setDefaultStatus = '0';
                            }
                            //echo $pic->name.'<br />';
                           $finalName = $setDir."/".$pic->name;
                           //echo "<br>";
                            if($pic->saveAs($finalName)) {                        // add it to the main model now
                                //$img_add = new Picture();
                                //$img_add->filename = $pic->name; //it might be $img_add->name for you, filename is just what I chose to call it in my model
                                //$img_add->topic_id = $model->id; // this links your picture model to the main model (like your user, or profile model)
                                $insertMultiDoc=Yii::app()->db->createCommand()->insert('rewardimages',array(
                                'reward_id'=>$model->id,
                                'image'=>$pic->name,
                                'defaultImage'=>$setDefaultStatus,
                                 ));
                                //$img_add->save(); // DONE
                            }
                            else{
                                // handle the errors here, if you want
                            }
         
                       $i++;  }//end foreach
                    } // if image is uploaded by admin
            }
        	//die('end');
            $this->redirect(array('admin', 'id' => $model->id));

        } //end post method


		 $this->render('create',array(
            'model'=>$model,
        ));
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

    /**
     * Performs the AJAX validation.
     * @param Doc $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='reward-form')
        {
            CActiveForm::validate($model);
            Yii::app()->end();
        }
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
                        }
                        //end
                       //echo  $setDir;
                       //die;
                        // go through each uploaded image
                        $i=0;
                        foreach ($images as $image => $pic) {
                          //echo "i am in images";
                        
                            if($i == '0'){ //first image will be default
                                $setDefaultStatus = '1';
                            }else{
                                $setDefaultStatus = '0';
                            }
                            //echo $pic->name.'<br />';
                           $finalName = $setDir."/".$pic->name;
                           //echo "<br>";
                            if($pic->saveAs($finalName)) { 
                                $chkImageExist = RewardImages::model()->find('image = :image', array(':image' => $pic->name));
                                if( empty($chkImageExist) )
                                {
                                 // echo "Image not exist";
                                    // add it to the main model now
                                    $insertMultiDoc=Yii::app()->db->createCommand()->insert('rewardimages',array(
                                    'reward_id'=>$model->id,
                                    'image'=>$pic->name,
                                    'defaultImage'=>$setDefaultStatus,
                                     ));
                                }
                                else
                                {
                                  //echo "dont save the record";
                                }

                                
                            }
                            else{
                                //echo " handle the errors here, if you want";
                            }
                       $i++;  }//end foreach
                    } // if image is uploaded by admin
            }
            //die('end');
            $this->redirect(array('admin', 'id' => $model->id));

        } //end post method


        $this->render('update',array(
      'model'=>$model,'Category'=>$Category,'Tags'=>$Tags,'RewardImages'=>$RewardImages
      ));


    }







}
