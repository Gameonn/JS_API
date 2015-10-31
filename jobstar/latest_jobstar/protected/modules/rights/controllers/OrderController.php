<?php

class OrderController extends Controller
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
				'actions'=>array('index','view','approve','deactivate','delete','admin','search'),
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
		$taxModel = new Tax();
		
		$this->render('view',array(
            'model'=>$this->loadModel($id),'taxModel'=>$taxModel
        ));
	}
	




	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		//first delete order detail products and than order table id
		$command = Yii::app()->db->createCommand();

        $sql='DELETE FROM shop_order_detail WHERE order_id=:order_id';
        $params = array(
            "order_id" => $id,
            
        );
        $command->setText($sql)->execute($params);

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
		$dataProvider=new CActiveDataProvider('Order');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
			unset(Yii::app()->request->cookies['from_date']);  // first unset cookie for dates
			unset(Yii::app()->request->cookies['to_date']);	
			
			$model=new Order('search');
			$model->unsetAttributes();  // clear any default values
			if(!empty($_POST))
			{
		    Yii::app()->request->cookies['from_date'] = new CHttpCookie('from_date', $_POST['from_date']);  // define cookie for from_date
		    Yii::app()->request->cookies['to_date'] = new CHttpCookie('to_date', $_POST['to_date']);
		    $model->from_date = $_POST['from_date'];
		    $model->to_date = $_POST['to_date'];
			}
			if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

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
		$model=Order::model()->findByPk($id);
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
	  
	  if($data->ordering_confirmed==1)
	  {
	   $showactive='style="display:block"';
	   $showdeactive='style="display:none"';
	  }
	  else
	  {
	  $showactive='style="display:none"';
	  $showdeactive='style="display:block"';
	  }
	   $output .='<div id="activebutton_'.$data->id.'" '.$showactive.'>Order Confirmed</br>'; 
	   $output.='<input type="button" name="deactivate" class="deactivate" data-id="'.$data->id.'" value="Make Order Pending">';
	   $output .='</div>';
	  
	  $output .='<div id="deactivebutton_'.$data->id.'" '.$showdeactive.'>Pending<br/>';
	  $output.='<input type="button" name="activate" class="activate" data-id="'.$data->id.'" value="Make Order Confirmed">';	  
	   $output.='</div>';
	  
	  
	  echo $output.'<br/><div id="response_status_'.$data->id.'"></div>';
	}

	public function actionApprove()
	{
	
	 $id=$_REQUEST['userid'];
	
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('shop_order', 
        array(
            'ordering_confirmed'=>'1',
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   	//send mail to user
	   
	   $model=$this->loadModel($id);
	     echo "Order is Confirmed.";
	   }
	   
	 }
	
	}
	
	


	

	

	public function actionDeactivate()
	{	
	$id=$_REQUEST['userid'];
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('shop_order', 
        array(
            'ordering_confirmed'=>'0',
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);  
	   

	     echo "Order is Pending.";
	   }
	   
	 }
	   
	
	}
	
	


	

	


	

}
