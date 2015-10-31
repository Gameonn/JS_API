<?php

class RewardController extends Controller
{
		 //public $_model;
		 public $layout='/layouts/main';

	/**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }
public function accessRules()
    {

       // echo $id=Yii::app()->user->id;

        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','login','registration','captcha','error'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update','view'),
                'users'=>array('*'),
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

	public function actionView($id)
	{
		$this->pageTitle = 'Jobstar - Product Description';  
		$this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
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
}
