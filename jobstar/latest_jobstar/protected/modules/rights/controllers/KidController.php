<?php

class KidController extends Controller
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
	public function accessRules__()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
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
		);
	}


	public function accessRules()
    {
		/*if($_REQUEST['dologin']==1)
		{
			$model=new LoginForm;

			$_POST['LoginForm']['username']='admin';
			$_POST['LoginForm']['password']='admin';
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			$model->validate() ;
			$model->login();

		}*/
       // $id=Yii::app()->user->id;

		//$userroles=User::model()->getuseraccess($id);
		
		//$roles=explode(',',$userroles);

			return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','admin'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			
		);
		
		

    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	
	//get download history
	
$userdownload = Yii::app()->db->createCommand()
    ->select('p.title as doc,c.title as cat,a.*')
    ->from('tbl_downloads a')
    ->join('tbl_doc p', 'a.file_id=p.id')
    ->join('tbl_doc_category c', 'c.id=p.category_id')
    ->where('userid=:id', array(':id'=>$id))
    ->queryAll();
				
	
	
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'userdownload'=>$userdownload
		));
	}
	

	public function getuserotherinfo($id)
	{
		$UserExtend=new UserExtend;
		return $UserExtendvals=$UserExtend->getuservalues($id);
	}
	

	



	public function getuseraddress($data)
	{
		$UserExtend=new Kid;
		
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
	   $output.='<input type="button" name="deactivate" class="deactivate" data-id="'.$data->id.'" value="Deactivate Kid">';
	   $output .='</div>';
	  
	  $output .='<div id="deactivebutton_'.$data->id.'" '.$showdeactive.'>Inactive<br/>';
	  $output.='<input type="button" name="activate" class="activate" data-id="'.$data->id.'" value="Activate Kid">';	  
	   $output.='</div>';
	  
	  
	  echo $output.'<br/><div id="response_status_'.$data->id.'"></div>';
	}
	






	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

			
			$model=new Kid('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Kid']))
			$model->attributes=$_GET['Kid'];

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
		$model=Kid::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='kid-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	
	/**
	 * Send mail method
	 */
	public static function sendMail($email,$subject,$message) {
    	$adminEmail = Yii::app()->params['adminEmail'];
	    $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
	    $message = wordwrap($message, 70);
	    $message = str_replace("\n.", "\n..", $message);
	    return mail($email,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$headers);
	}
	
	
	

 
	
	public function actionApprove()
	{
	
	 $id=$_REQUEST['userid'];
	
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('kids', 
        array(
            'is_deleted'=>1,
        ),
        'id=:id',
        array(':id'=>$id)
        );
	   
	   if($update)
	   {
	   
	   $model=$this->loadModel($id);
	   

								
	     echo "Kid Activated.";
	   }
	   
	 }
	
	}
	
	public function actionDeactivate()
	{	
	$id=$_REQUEST['userid'];
	
	 if(!empty($id))
	 {
	   
	   $update = Yii::app()->db->createCommand()
        ->update('kids', 
        array(
            'is_deleted'=>0,
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
	     echo "Kid is deactivated.";
	   }
	   
	 }
	   
	
	}
	
		public function showParentName($data)
	{

    	//echo $id;
    	$id = $data['user_id'];
    	 $sql  = "SELECT first_name from users where id = $id";	
      	$list = Yii::app()->db->createCommand($sql)->queryRow();
	 	return $list['first_name'];
		
	  
	}





}
