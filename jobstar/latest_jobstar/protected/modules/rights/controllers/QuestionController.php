<?php

class QuestionController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','approve','deactivate','update','delete','admin','create','search'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);}else{
            //$this->redirect(array('backend.php?r=site/login'));
            $this->redirect('backend.php?r=site/login');
        }
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Question;
		if(isset($_POST['Question']))
		{
			//echo "<pre>";
			//print_r($_POST['Question']);
			//die;
			$model->attributes=$_POST['Question'];
			if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/images/question";
	            }
	            else{
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/jobstar/images/question/";
	            }
	        $randText = rand();    
            $model->option1 = CUploadedFile::getInstance($model,'option1');
            $newFilename = time()."+".$model->option1;
            $newFilename = preg_replace('/\s+/', '', $newFilename);//remove all spaces
            
            //second image
            $model->option2 = CUploadedFile::getInstance($model,'option2');
            $newFilename2 = $randText."+".time()."+".$model->option2;
            $newFilename2 = preg_replace('/\s+/', '', $newFilename2);//remove all spaces
            
            //third image
            $model->option3 = CUploadedFile::getInstance($model,'option3');
            $newFilename3 = $randText."+".time()."+".$model->option3;
            $newFilename3 = preg_replace('/\s+/', '', $newFilename3);//remove all spaces
            
            //fourth image
            $model->option4 = CUploadedFile::getInstance($model,'option4');
            $newFilename4 = $randText."+".time()."+".$model->option4;
            $newFilename4 = preg_replace('/\s+/', '', $newFilename4);//remove all spaces
            //end images
            
            //$getAnswer = $_POST['Question']['answer']; 
            //$model->answer = $getAnswer;
			if($model->save())
			{
				if(!empty($model->option1))  // check if uploaded file is set or not
                {
                    $model->option1->saveAs($setDir.'/'.$newFilename);
                    $update1 = Yii::app()->db->createCommand()->update('questions',array('option1'=>$newFilename),'id='.$model->id);

				}
				if(!empty($model->option2))  // check if uploaded file is set or not
                {
                    $model->option2->saveAs($setDir.'/'.$newFilename2);
                    $update2 = Yii::app()->db->createCommand()->update('questions',array('option2'=>$newFilename2),'id='.$model->id);

				}
				if(!empty($model->option3))  // check if uploaded file is set or not
                {
                    $model->option3->saveAs($setDir.'/'.$newFilename3);
                    $update3 = Yii::app()->db->createCommand()->update('questions',array('option3'=>$newFilename3),'id='.$model->id);

				}
				if(!empty($model->option4))  // check if uploaded file is set or not
                {
                    $model->option4->saveAs($setDir.'/'.$newFilename4);
                    $update4 = Yii::app()->db->createCommand()->update('questions',array('option4'=>$newFilename4),'id='.$model->id);

				}
				Yii::app()->user->setFlash('msg','Question has been created Successfully.');
				//$this->redirect(Yii::app()->request->baseUrl.'/backend.php?r=rights/category/admin');  
				$this->redirect(array('admin', 'id' => $model->id));

			}//end model save

		}//end post


		$this->render('create',array(
			'model'=>$model
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->pageTitle = 'jobstar - Update Question';  
		//echo $_SERVER['DOCUMENT_ROOT'];
		$model=$this->loadModel($id);
		    if(isset($_POST['Question']) && !empty($_POST['Question'])) {
		    	$model->option1=$model->option1;
		    	$model->option2=$model->option2;
		    	$model->option3=$model->option3;
		    	$model->option4=$model->option4;
		    	$model->attributes  = $_POST['Question'];
		    	if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/images/question";
	            }
	            else{
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/jobstar/images/question/";
	            }
		    	if(!empty($_FILES['Question']['name']['option1'])){
	              $model->option1=CUploadedFile::getInstance($model,'option1');
	              $newFilename = time()."+".$model->option1;
	              $newFilename = preg_replace('/\s+/', '', $newFilename);//remove all spaces
	             }else{
	              $newFilename = $model->option1;
	             }

	             if(!empty($_FILES['Question']['name']['option2'])){
	              $model->option2	= CUploadedFile::getInstance($model,'option2');
	              $newFilename2 = time()."+".$model->option2;
	              $newFilename2 = preg_replace('/\s+/', '', $newFilename2);//remove all spaces
	             }else{
	              $newFilename2 = $model->option2;
	             }

	             if(!empty($_FILES['Question']['name']['option3'])){
	              $model->option3	=	CUploadedFile::getInstance($model,'option3');
	              $newFilename3 = time()."+".$model->option3;
	              $newFilename3 = preg_replace('/\s+/', '', $newFilename3);//remove all spaces
	             }else{
	              $newFilename3 = $model->option3;
	             }

	             if(!empty($_FILES['Question']['name']['option4'])){
	              $model->option4	=	CUploadedFile::getInstance($model,'option4');
	              $newFilename4 = time()."+".$model->option4;
	              $newFilename4 = preg_replace('/\s+/', '', $newFilename4);//remove all spaces
	             }else{
	              $newFilename4 = $model->option4;
	             }
		    	if($model->save()) {
		    		if(!empty($_FILES['Question']['name']['option1']))
	                {
	                    $model->option1->saveAs($setDir.'/'.$newFilename);
	                    //update table for filename
	                    $update1 = Yii::app()->db->createCommand()->update('questions',array('option1'=>$newFilename),'id='.$model->id);
					}
					if(!empty($_FILES['Question']['name']['option2']))
	                {
	                    $model->option2->saveAs($setDir.'/'.$newFilename2);
	                    //update table for filename
	                    $update2 = Yii::app()->db->createCommand()->update('questions',array('option2'=>$newFilename2),'id='.$model->id);
					}
					if(!empty($_FILES['Question']['name']['option3']))
	                {
	                    $model->option3->saveAs($setDir.'/'.$newFilename3);
	                    //update table for filename
	                    $update3 = Yii::app()->db->createCommand()->update('questions',array('option3'=>$newFilename3),'id='.$model->id);
					}
					if(!empty($_FILES['Question']['name']['option4']))
	                {
	                    $model->option4->saveAs($setDir.'/'.$newFilename4);
	                    //update table for filename
	                    $update4 = Yii::app()->db->createCommand()->update('questions',array('option4'=>$newFilename4),'id='.$model->id);
					}
					Yii::app()->user->setFlash('msg','Question has been updated Successfully.');
					$this->redirect(array('admin', 'id' => $model->id));


	            }
		    }
			
		$this->render('update',array('model'=>$model));
	
		
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = new Question();
		$this->loadModel($id)->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

			
			$model=new Question('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Question']))
			$model->attributes=$_GET['Question'];

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
		$model=Question::model()->findByPk($id);
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
	   $output .='<div id="activebutton_'.$data->id.'" '.$showactive.'>Active</br>'; 
	   $output.='<input type="button" name="deactivate" class="deactivate" data-id="'.$data->id.'" value="Deactivate Question">';
	   $output .='</div>';
	  
	  $output .='<div id="deactivebutton_'.$data->id.'" '.$showdeactive.'>Inactive<br/>';
	  $output.='<input type="button" name="activate" class="activate" data-id="'.$data->id.'" value="Activate Question">';	  
	   $output.='</div>';
	  
	  
	  echo $output.'<br/><div id="response_status_'.$data->id.'"></div>';
	}

	public function actionApprove()
	{
	
	 $id=$_REQUEST['userid'];
	
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('questions', 
        array(
            'status'=>'1',
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);
	     echo "Question Activated.";
	   }
	   
	 }
	
	}
	
	


	

	

	public function actionDeactivate()
	{	
	$id=$_REQUEST['userid'];
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('questions', 
        array(
            'status'=>'0',
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);  
	   

	     echo "Question deactivated.";
	   }
	   
	 }
	   
	
	}
	

}
