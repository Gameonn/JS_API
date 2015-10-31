<?php

class OrderController extends Controller
{
		 public $layout='/layouts/main';

	
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
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','detail','raffle'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','premium','detail','admin'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','detail'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

  public function loadModel($id)
    {
        $model=Reward::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }



  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='shopping cart-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
	public function actionView($id='')
	{
		
		  $model = new Order();

		 
		  //Yii::app()->user->setFlash('showMsg','Product has added successfully.');

		  $this->render('view',array(
						'model'=>$model
						));
	}
		public function actionAdmin($id='')
	{
		
		  $model = new Order();
		  $taxModel = new Tax();
		  $listBillAdd = '';
        $user_id = Yii::app()->user->id;
        if($user_id!='') {
        
         $listBillAdd = Yii::app()->db->createCommand("SELECT * FROM  shop_billing_address 
            WHERE user_id=$user_id"
            )->queryRow();
         $listShipAdd = Yii::app()->db->createCommand("SELECT * FROM  shop_shipping_address 
            WHERE user_id=$user_id"
            )->queryRow();
         //echo "<pre>";
         //print_r($listBillAdd);
         //echo "aaaa";
       }
		  //Yii::app()->user->setFlash('showMsg','Product has added successfully.');
		   $this->render('admin',array(
            'model'=>$model,'taxModel'=>$taxModel,'listBillAdd'=>$listBillAdd,'listShipAdd'=>$listShipAdd
            ));
	}

  public function actionPremium(){
      $model = new Order();

      $this->render('premium',array(
            'model'=>$model
            ));


  }

    public function actionRaffle(){
      $model = new Order();

      $this->render('raffle',array(
            'model'=>$model
            ));


  }

  	public function actionDetail($id='')
	{
		
		  $model = new OrderDetail();

		 

		  $this->render('detail',array(
						'model'=>$model
						));
	}




}
