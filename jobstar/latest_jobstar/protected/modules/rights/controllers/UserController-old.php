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
		//echo $userId = Yii::app()->user->id;
		//die;
       // if($userId) {
			return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','kids','admin'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','kids','admin'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','kids','admin'),
				'users'=>array('admin'),
			),
			
		); 

			/*}else{
            //$this->redirect(array('backend.php?r=site/login'));
            $this->redirect('backend.php?r=site/login');
        }*/
		
		

    }
     public function actionIndex()
    {
        if (!Yii::app()->user->isGuest)
            $this->render('admin-dashboard');
        else
            $this->redirect(array('login'));
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

	public function getStatslink($data)
	{	
	echo '<a href="index.php?r=admin/dochistory/admin&userid='.$data->id.'">Stats</a>';
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
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;
		$UserExtend=new UserExtend;
		$Usertypes=new Usertypes;

			// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		
		if(isset($_POST['User']))
		{

			$model->attributes=$_POST['User'];
			$model->username=$_POST['User']['email'];
			$model->password=md5($_POST['User']['password']);
			
			if(sizeof($_POST['User']['user_assigned_entities'])>0)
			{
				
				foreach($_POST['User']['user_assigned_entities'] as $key)
				{
					$roles .=$key.",";
				}
			$roles=substr($roles,0,-1);

			$_POST['User']['user_assigned_entities']=$roles;
			$model->user_assigned_entities=$roles;
			}

			$UserExtend->attributes=$_POST['UserExtend'];
			$UserExtend->published=1;
			if($model->save())
			{
				$UserExtend->userid=$model->id;

				$UserExtend->updateuserflds($_POST['UserExtend'],$model->id);
				
				$Usertypes->createerole($model->id,$_POST['Usertypes']['usertype']);
				$UserExtendvals=$UserExtend->getuservalues($model->id);


				Yii::app()->user->setFlash('admin','User has been created Successfully.');
				$this->redirect(Yii::app()->request->baseUrl.'/index.php?r=admin/user/admin');
			}
		}
	$UserExtendvals=array('first_name'=>'','middle_name'=>'','last_name'=>'','city'=>'','state'=>'','country'=>'','zipcode'=>'','website'=>'','address'=>'','address2'=>'','phone'=>'','ext1'=>'','phone2'=>'','ext2'=>'','fax'=>'');


		$this->render('create',array(
			'model'=>$model,'UserExtendvals'=>$UserExtendvals,'UserExtend'=>$UserExtend,'Usertypes'=>$Usertypes
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$UserExtend=new UserExtend;

		$Usertypes=new Usertypes;

	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
		
		if($_POST['User']['password']===$model->password)
			{
			  $model->password=$_POST['User']['password'];
			 
			}
			else
			{
			$model->password=md5($_POST['User']['password']);
						}		
unset($_POST['User']['password']);			

    		$model->attributes=$_POST['User'];
			
			
			if(sizeof($_POST['User']['user_assigned_entities'])>0)
			{
				
				foreach($_POST['User']['user_assigned_entities'] as $key)
				{
					$roles .=$key.",";
				}
			$roles=substr($roles,0,-1);

			$_POST['User']['user_assigned_entities']=$roles;
			$model->user_assigned_entities=$roles;
			}
			
			
			if($model->save() && $model->validate())
			{
			
				$UserExtend->userid=$model->id;

				$UserExtend->updateuserflds($_POST['UserExtend'],$model->id);
				
				$Usertypes->updaterole($model->id,$_POST['Usertypes']['usertype']);
				Yii::app()->user->setFlash('admin','User has been updated Successfully.');
				
				if(isset($_POST['saveandstay']))
				{
				$this->redirect(Yii::app()->request->urlReferrer);
				}
					$this->redirect(Yii::app()->request->baseUrl.'/index.php?r=admin/user/admin');
			}
		}
		$UserExtendvals=$UserExtend->getuservalues($model->id);
		
		$this->render('update',array(
			'model'=>$model,'UserExtendvals'=>$UserExtendvals,'UserExtend'=>$UserExtend,'Usertypes'=>$Usertypes
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
	
		$query = "delete from `tbl_user_extend` where `userid`= :userid";
		$command = Yii::app()->db->createCommand($query);
		$command->execute(array('userid' => $id));
		
		$query = "delete from `tbl_user_usertypes` where `userid`= :userid";
		$command = Yii::app()->db->createCommand($query);
		$command->execute(array('userid' => $id));
	
		$this->loadModel($id)->delete();
		//$model=$this->loadModel($id);
		//$model->publishUser($id);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		/*$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		//echo "<pre>";
		//print_r($_POST);
		echo "asassa";
		die;
		if(isset($_POST) && $_POST['userType'] == 'K') {
			$model=new User('search');
			//$model->unsetAttributes();  // clear any default values
			//if(isset($_GET['User']))
			//$model->attributes=$_GET['User'];
			//$model = new Kid();
			$this->render('kids',array(
			'model'=>$model,
			));
		}else{ //else load kids
			
			$model=new User('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

			$this->render('admin',array(
			'model'=>$model,
			));
		}
		
	}
		public function actionKids()
	{
		//echo "<pre>";
		//print_r($_POST);
		
		if(isset($_POST) && $_POST['userType'] == 'G') {
			$model=new User('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

			$this->render('admin',array(
			'model'=>$model,
			));
		}else{ //else load kids
			$model=new Kid('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

			$this->render('kids',array(
			'model'=>$model,
			));
		}
		
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

	public function actionLogout()
	{
	
		Yii::app()->user->logout();
		$this->redirect('index.php?r=admin');
	}


	function get_userrole($data)
	{
		$model=new User;

		return $model->getuserrole($data['id']);

	}
	
	function get_userroles($data)
	{
		
		$user = Yii::app()->db->createCommand()
				->select('*')
				->from('usertypes')
				->queryRow();
		// print_r($user);
		return $user['userrole'];

	}

	function actionSearchuser()
	{
		$model=new User;
		$keyword=$_REQUEST['usersearch'];
		$results=$model->seachthisuser($keyword);
		
		if(!empty($results) or sizeof($results)>0)
		{
		$this->renderPartial('searchedusers',array(
			'results'=>$results,
		));
		}else
		{
			echo 'No Customer Found.';
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
	
	
	

	public function actionGetuserinformation()
	{
		$UserExtend=new UserExtend;
		
		if(empty($_REQUEST['userid']))
		{
					$userid=Yii::app()->user->getState("userid");
				
		}
		else
		{
					$userid=$_REQUEST['userid'];
		}
		Yii::app()->user->setState("userid",$userid);

		$UserExtendvals=$UserExtend->getuservalues($userid);

		if(!empty($UserExtendvals) or sizeof($UserExtendvals)>0)
		{
			$model=$this->loadModel($UserExtendvals['userid']);
			$email=$model->email;
		$this->renderPartial('searchedusers',array('UserExtendvals'=>$UserExtendvals,'email'=>$email));
		}else
		{
			echo 'No Customer information available.';
		}
	}
	
	public function encrypting($string="") {
		$hash = Yii::app()->getModule('user')->hash;
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
	
	public function RandomPass($numchar) 
	{ 
		$word = "a,b,c,d,e,f,g,h,i,j,k,l,m,1,2,3,4,5,6,7,8,9,0"; 
		$array=explode(",",$word); 
		shuffle($array); 
		$newstring = implode($array,""); 
		return substr($newstring, 0, $numchar); 
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
