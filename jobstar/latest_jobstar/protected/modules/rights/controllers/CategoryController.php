<?php

class CategoryController extends Controller
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
		$model=new Category;
		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/images/category";
	            }
	            else{
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/jobstar/images/category/";
	            }
            $model->filepath = CUploadedFile::getInstance($model,'filepath');
            $newFilename = time()."+".$model->filepath;
            $newFilename = preg_replace('/\s+/', '', $newFilename);//remove all spaces
			if($model->save())
			{
				if(!empty($model->filepath))  // check if uploaded file is set or not
                {
                    $model->filepath->saveAs($setDir.'/'.$newFilename);
                    $update1 = Yii::app()->db->createCommand()->update('category',array('filepath'=>$newFilename),'id='.$model->id);

				}
				Yii::app()->user->setFlash('msg','Category has been created Successfully.');
				//$this->redirect(Yii::app()->request->baseUrl.'/backend.php?r=rights/category/admin');  
				$this->redirect(array('admin', 'id' => $model->id));

			}

		}


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
		$this->pageTitle = 'jobstar - Update Category';  
		//echo $_SERVER['DOCUMENT_ROOT'];
		$model=$this->loadModel($id);
		    if(isset($_POST['Category']) && !empty($_POST['Category'])) {
		    	$model->filepath=$model->filepath;
		    	$model->attributes  = $_POST['Category'];
		    	if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/images/category";
	            }
	            else{
	                $setDir =  $_SERVER['DOCUMENT_ROOT']."/jobstar/images/category/";
	            }
		    	if(!empty($_FILES['Category']['name']['filepath'])){
	              $model->filepath=CUploadedFile::getInstance($model,'filepath');
	              $newFilename = time()."+".$model->filepath;
	              $newFilename = preg_replace('/\s+/', '', $newFilename);//remove all spaces
	             }else{
	              $newFilename = $model->filepath;

	             }
		    	if($model->save()) {
		    		if(!empty($_FILES['Category']['name']['filepath']))
	                {
	                    $model->filepath->saveAs($setDir.'/'.$newFilename);
	                    //update table for filename
	                    $update1 = Yii::app()->db->createCommand()->update('category',array('filepath'=>$newFilename),'id='.$model->id);
					}
					Yii::app()->user->setFlash('msg','Category has been updated Successfully.');
					//$this->redirect(Yii::app()->request->baseUrl.'/backend.php?r=rights/category/admin');            
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
		//echo $id;
		//die('hello');
		$model = new Reward();
		//first check that which product present in category.
		$sql  = "SELECT id from reward where category_id = $id";	
      	$list = Yii::app()->db->createCommand($sql)->queryAll();
	 	$list1 = '';
	 	if($list){
		 	foreach ($list as $key => $value) {
		 		$list1 .= ",".$value['id'];
		 	}
		 	$status = '0';
		  	$getAllRewardIds = substr($list1,1);

		  	//find order id from order detail table
		  	//$getOrderSql  = "SELECT order_id from shop_order_detail where reward_id in ($getAllRewardIds)";

		  	//delete its coresponding orders
		  	//$delOrders = "Delete from shop_order where order_id in ($order_id)";
		  	//$rowOrder = Yii::app()->db->createCommand($delOrders)->execute();

		  	//delete its tags
		  	$delTags = "Delete from tags where reward_id in ($getAllRewardIds)";
		  	$rowTags = Yii::app()->db->createCommand($delTags)->execute();
		  	
		  	//make all reward product inactive
		  	$delReward = "Delete from reward where id in ($getAllRewardIds)";
		  	$rowCount = Yii::app()->db->createCommand($delReward)->execute();
	  		
	  		/*$update=Reward::model()->updateAll(array(
                                        'status'=>$status
                                    ),
                                    "id in ($getAllRewardIds)"
                                );*/
	  	}
	  	//end
	  	//if($rowCount){
			$this->loadModel($id)->delete();
		//}
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

			
			$model=new Category('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Category']))
			$model->attributes=$_GET['Category'];

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
		$model=Category::model()->findByPk($id);
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
	   $output.='<input type="button" name="deactivate" class="deactivate" data-id="'.$data->id.'" value="Deactivate Category">';
	   $output .='</div>';
	  
	  $output .='<div id="deactivebutton_'.$data->id.'" '.$showdeactive.'>Inactive<br/>';
	  $output.='<input type="button" name="activate" class="activate" data-id="'.$data->id.'" value="Activate Category">';	  
	   $output.='</div>';
	  
	  
	  echo $output.'<br/><div id="response_status_'.$data->id.'"></div>';
	}

	public function actionApprove()
	{
	
	 $id=$_REQUEST['userid'];
	
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('category', 
        array(
            'status'=>'1',
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);
	     echo "Category Activated.";
	   }
	   
	 }
	
	}
	
	


	

	

	public function actionDeactivate()
	{	
	$id=$_REQUEST['userid'];
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('category', 
        array(
            'status'=>'0',
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);  
	   

	     echo "Category is deactivated.";
	   }
	   
	 }
	   
	
	}
	

}
