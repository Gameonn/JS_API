<?php

class UserController extends Controller
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{

			
			$model=new User('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

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
		$model=User::model()->findByPk($id);
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

		public function getuserfullname($data)
	{
		$UserExtend=new User;

		//$orders=new Orders;

		//$UserExtendvals=$UserExtend->getuservalues($data['id']);
		 //$countorders=$orders->countusers($data['id']);
		$UserExtendvals=$this->loadModel($data['id']);
	$fullname=$UserExtendvals['first_name']." ".$UserExtendvals['last_name'];
		return str_replace('  ','',$fullname);
	}

		public function showAllKidsToSingleParent($data)
	{

		//$KidData=new Kid;		
		//$kidvals=$this->loadModel($data['id']);
		$id = $data['id'];
		$sql  = 'SELECT name from kids where user_id in ('.$id.')';	
      	$list = Yii::app()->db->createCommand($sql)->queryAll();
	 	$list1 = '';
	 	foreach ($list as $key => $value) {
	 		$list1 .= ",".$value['name'];
	 	}
	  	return substr($list1,1);

	}
		public function getuseraddress($data)
	{
		$UserExtend=new User;
		
		$UserExtendvals=$this->loadModel($data['id']);
	
		//$UserExtendvals=$User->getuservalues($data['id']);
		$address='';
		/*if(!empty($UserExtendvals['address']))
		{
			$address .=$UserExtendvals['address']."<br>";

		}
		if(!empty($UserExtendvals['address2']))
		{
			$address .=$UserExtendvals['address2']."<br>";
		}*/
		if(!empty($UserExtendvals['city']))
		{
			$address .=$UserExtendvals['city']."<br>";
		}
		/*if(!empty($UserExtendvals['state']))
		{
			$address .=$UserExtendvals['state']."<br>";
		}
		if(!empty($UserExtendvals['zipcode']))
		{
			$address .=$UserExtendvals['zipcode']."<br>";
		}*/
		if(!empty($UserExtendvals['country']))
		{
			$address .=$UserExtendvals['country']."<br>";
		}
		/*if(!empty($UserExtendvals['phone']))
		{
			$address .="Contact : ".$UserExtendvals['phone']."<br>";
		}*/

		$address .=$model->email;

		
		return $address;
	}
		public function getStatus($data)
	{
    //if($data->superuser==1){ return 'Super Admin';}	 
	  
	  if($data->is_deleted==1)
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
	   $output.='<input type="button" name="deactivate" class="deactivate" data-id="'.$data->id.'" value="Deactivate User">';
	   $output .='</div>';
	  
	  $output .='<div id="deactivebutton_'.$data->id.'" '.$showdeactive.'>Inactive<br/>';
	  $output.='<input type="button" name="activate" class="activate" data-id="'.$data->id.'" value="Activate User">';	  
	   $output.='</div>';
	  
	  
	  echo $output.'<br/><div id="response_status_'.$data->id.'"></div>';
	}

		public function actionApprove()
	{
	
	 $id=$_REQUEST['userid'];
	
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('users', 
        array(
            'is_deleted'=>1,
            'deleted_on'=>date('Y-m-d H:i:s')
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);
	   
	   /*$UserExtend=new UserExtend;
	   
	   $uservals=$UserExtend->getuservalues($id);
	   
	   $soucePassword = $this->RandomPass(8);		
	   
	   $cpass=$this->encrypting($soucePassword);
	   	   
	    Yii::app()->db->createCommand()
        ->update('tbl_user', 
        array(
            'password'=>$cpass
        ),
        'id=:id',
        array(':id'=>$id)
        );*/
	   
	  	/*$mailcontent= Yii::app()->db->createCommand()
				->select('*')
				->from('tbl_email_templates')
				->where('template_type=3')
				->queryRow();  
	   
	
	   
	  $customerMessage=UserModule::t($mailcontent['email_content'],array('{first_name}'=>$uservals['first_name'],'{middle_name}'=>$uservals['middle_name'],'{last_name}'=>$uservals['last_name'],'{user_email}'=>$model->email,'{access_code}'=>$soucePassword,)).'<br/><br/>'.$mailcontent['signature'];
	   
	   $this->sendMail($model->email,$mailcontent['email_subject'],$customerMessage);*/
								
	     echo "User Activated and Mail sent to user.";
	   }
	   
	 }
	
	}
	
	public function actionDeactivate()
	{	
	$id=$_REQUEST['userid'];
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('users', 
        array(
            'is_deleted'=>0,
            'deleted_on'=>date('Y-m-d H:i:s')
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);  
	   
	   /*$mailcontent= Yii::app()->db->createCommand()
				->select('*')
				->from('tbl_emails')
				->where('emailfor=2 and publish=1')
				->queryRow(); 
	   if(!empty($mailcontent['subject']))
	   {
	   	
	   $message.=$mailcontent['content'];
	   
	    $this->sendMail($model->email,$mailcontent['subject'],$message);
								
	  
		
	   }*/
	     echo "User is deactivated.";
	   }
	   
	 }
	   
	
	}





}
