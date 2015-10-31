<?php

class SiteController extends Controller
{
	

	/**
	 * Declares class-based actions.
	 */

	 public $layout='/layouts/main';

	public function __construct()
	{
	 
	 }

	public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('create', 'edit','login','captcha','contact'),
                //'actions'=>array('*'),
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('*'),
                'roles'=>array('admin'),
            ),
            array('allow',
                'actions'=>array('*'),
                'users'=>array('*'),
            ),
        );
    }

	public function actions()
	{

		return array(
			/*'captcha'=>array(
				'class'=>'CaptchaExtendedAction',
			),*/
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,

			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		//echo "hello";
		//die;
		 //$admin = Yii::app()->user->isAdmin;
		/*if(isset($_SESSION['cart_items'])){
			echo "dsds";
			unset($_SESSION['cart_items']);
		}*/
		
		$model = new Reward('search');

         $this->render('site/index',array(
            'model'=>$model,
        ));
	}

public function catdetails($catid)
	{
	
		$model=new Content;
		$results=$model->getcatdetails($catid);
		return $results;
	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('site/error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */

	/**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if(isset($_POST['ContactForm']))
        {
            $model->attributes = $_POST['ContactForm'];
            $chk = $model->validate();
            if($model->validate())
            {
                $name = $model->name;
                $email = $model->email;
                $textarea = $model->message;
					$to = 'ranjita706@gmail.com';
					$from = Yii::app()->params['adminEmail'];
					$subject = 'Mail From JobStars Website:Contact Form '.ucwords($name);
					$mailBody = "
					  <table dir='ltr'>
					<tbody>
						<tr>
							<td style='padding:0;font-family:Segoe UI Semibold,Segoe UI Bold,Segoe UI,Helvetica Neue Medium,Arial,sans-serif;font-size:17px;color:#707070'>JobStar</td>
					</tr>
					<tr>
						<td style='padding:0;padding-top:6px;font-family:Segoe UI,Tahoma,Verdana,Arial,sans-serif;font-size:14px;color:#2a2a2a'>
						<ol>			
							<li>Name :  ".ucwords($name)." </li>
							<li>Email Address :  $email </li>
							<li>Mesaage :  $textarea </li>
						</ol>
						</td>
					</tr>

					<tr><td style='padding:0;padding-top:25px;font-family:Segoe UI,Tahoma,Verdana,Arial,sans-serif;font-size:14px;color:#2a2a2a'>Thanks,</td></tr>
					<tr><td style='padding:0;font-family:Segoe UI,Tahoma,Verdana,Arial,sans-serif;font-size:14px;color:#2a2a2a'>JobStar Team</td></tr>
					</tbody>
					</table>";
					//$headers  = 'MIME-Version: 1.0' . "\r\n";
					//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					// More headers
					//$headers .= 'From: <'.$to.'>' . "\r\n";
					//$headers .= 'Cc: ranjita706@gmail.com' . "\r\n";
					//$chk = mail($to, $subject, $setText, $headers);
					$this->mailsend($to,$from,$subject,$mailBody);
                //die('hhh');
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('site/contact', array('model' => $model));
    }

	/*public function actionContact()
	{
		
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			//echo "<pre>";
			//print_r($_POST);
			//die;
			$model->attributes=$_POST['ContactForm'];
			//if($model->validate())
			//{
				//$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$name = $_POST['name'];
				$subject='Contact us Request';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$messagebody='A new contact us request has been recieved<br> Below are the details:<br>';
				$messagebody .='Name:'.$model->name.'<br>';
				$messagebody .='Email Address:'.$model->email.'<br>';
				$messagebody .='Message:'.$model->body.'<br>';
				mail(Yii::app()->params['adminEmail'],$subject,$messagebody,$headers);



				//$name='=Geraldinemiskin';
				$subject1='You have send contact us request on jobstar';
				$headers="From: $name<{Yii::app()->params['adminEmail']}>\r\n".
					"Reply-To: {Yii::app()->params['adminEmail']}\r\n".
					"MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$messagebody='Thank you for contacting us. We will respond to you as soon as possible.<br> Below message you have sent to us :<br>';
				$messagebody .='Subject:'.$subject1.'<br>';
				$messagebody .='Message:'.$model->body.'<br>';
				mail($model->email,$subject1,$messagebody,$headers);


				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			//}
		}
		$this->render('site/contact',array('model'=>$model));
	}*/
	

public function actionSubscribe()
{
   
	$model=new Subscriber;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Subscriber']))
		{
			$model->attributes=$_POST['Subscriber'];
			if($model->save())
			{
				$from=$_POST['Subscriber']['email'];
				$name=$_POST['Subscriber']['name'];
						$subject='Geralmiskin:New subscriber Request';
						$headers="From: $name <{$model->email}>\r\n".
							"Reply-To: {$model->email}\r\n".
							"MIME-Version: 1.0\r\n".
							"Content-Type: text/plain; charset=UTF-8";
						$body='A new subscription has been recieved from '.$_POST['Subscriber']['email'];
						
						mail(Yii::app()->params['adminEmail'],$subject,$body,$headers);
						Yii::app()->user->setFlash('subscribe','Thank you for subscribing with us. We will respond to you as soon as possible.');
			}
				
		}

		
	$this->layout='contentdetails';
    $this->render('site/subscribe',array('model'=>$model));
}




	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (!Yii::app()->user->isGuest)
            $this->redirect('index.php?r=site/index');
			$model=new LoginForm;

		// if it is ajax validation request
		 if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			//echo "ssss";
			$currenturl="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->url;
			//die('fddd');
			$model->attributes=$_POST['LoginForm'];
			$model->superuser = 0;
			$model->usertype = 'Front';
			// validate user input and redirect to the previous page if valid

			//if($model->validate() && $model->login()) {
			//if($model->login()) {
			 if ($model->validate(array('username', 'password', 'superuser')) && $model->login()){
				//die('hhhhh');
				//$this->redirect(Yii::app()->user->returnUrl);
				//$this->redirect($currenturl);
				$this->redirect('index.php?r=site/index');
			}
		} 
		// display the login form
		$this->render('site/login',array('model'=>$model));
	}



	/*public function actionLoginajax()
	{
		$model=new LoginForm;
echo "sdsdsd";
die;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			//echo str_replace("]","",str_replace("[","",CActiveForm::validate($model)));
			//Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			echo $currenturl="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->url;

			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				//$this->redirect(Yii::app()->user->returnUrl);
			$this->redirect($currenturl);
		}
		// display the login form
		$this->render('site/login',array('model'=>$model));
	}*/

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		
		if(isset($_SESSION['items'])){
			unset($_SESSION['items']);
			unset($_SESSION['grandTotalFinal']);
		}
		Yii::app()->user->logout();
		$this->redirect('index.php');
		//$this->redirect(Yii::app()->params['siteurl']);
	}



	public function getcombinedcategory($catid)
	{
	 $model=new Categories;
	
		return $contentcats=$model->getcombinedcat($catid);
		

	}



	public function actionCart()
	{
		$this->render('site/cart');
	}




}

