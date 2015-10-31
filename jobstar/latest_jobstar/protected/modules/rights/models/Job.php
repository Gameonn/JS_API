<?php

/**
 * This is the model class for table "jobs".
 *
 * The followings are the available columns in table 'jobs':
 * @property integer $id
 * @property string $user_id
 * @property string $kid_id
 * @property string $image
 * @property string $title
 *
 * The followings are the available model relations:
 * @property Jobstatus[] $jobstatus
 */
class Job extends CActiveRecord
{  


    public $name;
    public $time_up_from;
	public $time_up_to;
	public $from_date;
	public $to_date;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'jobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description', 'required'),
			array('title, description', 'length', 'max'=>128),
			//array('profile', 'safe'),
			//array('username', 'unique', 'message' => "This user's name already exists."),
			//array('email', 'unique', 'message' => "This user's email address already exists."),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,title,description,user_id,kid_id,created_on,from_date, to_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'orders' => array(self::HAS_MANY, 'Orders', 'userid'),
			'user' => array(self::HAS_MANY, 'User', 'id'),
			'kids' => array(self::HAS_MANY, 'Kid', 'id'),
			//'usertype' => array(self::HAS_MANY, 'Usertypes', 'userid'),
			//'CustomerName' => array(self::HAS_MANY, 'UserExtend', 'userid'),
			//'kids' => array(self::HAS_MANY, 'Kid', 'user_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'created_on' => 'Date Posted',
			'user_id' => 'Grown Up Name',
			'kid_id' => 'Kid Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;	
		
		//$criteria->alias='b';
		
		
		/*if(!empty($_REQUEST['User']['name']))
		{
			$criteria->join = "LEFT JOIN users ON b.user_id=users.id"; 						
			$criteria->condition = "users.first_name like '".$_REQUEST['User']['name']."%' or users.last_name like '".$_REQUEST['User']['name']."%'"; 	
		}
		
		
		if(!empty($_REQUEST['User']['usertype']) || $_REQUEST['User']['usertype']!=0)
		{
			
			$criteria->join = "LEFT JOIN tbl_user_usertypes ON b.id=tbl_user_usertypes.userid LEFT JOIN tbl_usertypes ON tbl_usertypes.id=tbl_user_usertypes.usertype"; 						
			$criteria->condition = "tbl_user_usertypes.usertype='".$_REQUEST['User']['usertype']."'"; 			
		}*/
		/*if(!empty($_REQUEST['Job']['created_on']))
		{
			$dateSearch = $_REQUEST['Job']['created_on'];
			//$criteria->join = " "; 						
			//$criteria->condition = " created_on = DATE($dateSearch)"; 	
		}*/

		//$criteria->condition='superuser = 0'; // added by ranjita, dont show admin user

		/*if(!empty($this->time_up_from) && !empty($this->time_up_to)){
      		$criteria->condition="created_on BETWEEN UNIX_TIMESTAMP('$this->time_up_from') AND UNIX_TIMESTAMP('$this->time_up_to')";
   		}*/


   		if(!empty($this->from_date) && empty($this->to_date))
        {
            $criteria->condition = "created_on >= '$this->from_date'";  // date is database date column field
        }elseif(!empty($this->to_date) && empty($this->from_date))
        {
            $criteria->condition = "created_on <= '$this->to_date'";
        }elseif(!empty($this->to_date) && !empty($this->from_date))
        {
            //$criteria->condition = "created_on  >= '$this->from_date' and created_on <= '$this->to_date'";
            $criteria->condition="created_on BETWEEN DATE('$this->from_date') AND DATE('$this->to_date')";

        }

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('kid_id',$this->kid_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		//$criteria->compare('created_on',$this->created_on,true); //date search

		//echo "<pre>";
		//print_r($criteria);
		//die;
		//$criteria->order = "id DESC";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function validatePassword($password)
    {
		
		if(md5($password)==$this->password)
		{
			return true;
		}
		else
		{
			return false;
		}
	
       // return CPasswordHelper::verifyPassword($password,$this->password);
    }
 public function changepassword($data)
	{
	 		$command = Yii::app()->db->createCommand();
		$id=Yii::app()->user->id;

		$command->update('tbl_user', array('password'=>md5($data['newpass'])),'id='.$id);
	}


	 public function publishUser($userid)
	{
	 		$command = Yii::app()->db->createCommand();

		$command->update('tbl_user', array('published'=>0),'id='.$userid);

	}

	 public function userdetails($userid)
	{

	 		$user = Yii::app()->db->createCommand()
				->select('u.*,ue.*')
				->from('tbl_user_extend ue')
				->join('tbl_user u', 'ue.userid=u.id')
				->where('u.id=:id', array(':id'=>$userid))
				->queryRow();
		
		return $user;

	}

	public function getuseraccess($userid)
	{

	$userrole = Yii::app()->db->createCommand(array(
					'select' => array('user_assigned_entities'),
					'from' => 'tbl_user',
					'where' =>	'id='.$userid
				
				))->queryRow();
				

		
		return $userrole['user_assigned_entities'];
	}



     public function getuserrole($userid)
	{

	
		$user = Yii::app()->db->createCommand()
				->select('ue.*')
				->from('tbl_usertypes ue')
				->join('tbl_user_usertypes u', 'ue.id=u.usertype')
				->where('u.userid=:id', array(':id'=>$userid))
				->queryRow();
		// print_r($user);
		return $user['id'];
	}
	
	
	 public function seachthisuser($search)
	{

	$sql="SELECT u.*,ue.* from tbl_user as u LEFT JOIN tbl_user_usertypes  as ut on u.id=ut.userid LEFT JOIN tbl_user_extend ue on ue.userid=ut.userid WHERE u.id=ue.userid and ut.usertype='Customer' and (ue.first_name LIKE '%".$search."%' or ue.last_name LIKE '%".$search."%')";
	
        $command=Yii::app()->db->createCommand($sql);
		$users=$command->queryAll();
		return $users;
	}

	public function showImage($id)
    {
   
        //get all images of selected reward
        if($id) {
            $sql  = "SELECT image from jobs where id = $id";    
            $list = Yii::app()->db->createCommand($sql)->queryRow();
            $filepath = $list['image'];
           //$img=CHtml::image(Yii::app()->baseUrl."/images/reward/$id/".$filepath);

           $setImage = Yii::app()->baseUrl."/uploads/".$filepath;
           if($filepath!='') {
                $img = '<img src="'.$setImage.'"  width="150px"  />';
            }else{
                $setImage = 'IMG_2510.JPG';
                $img = '<img src="'.$setImage.'"  width="150px"  />';
            }
        }
    return $img;
    }


    public function searchUser($id)
    {

        $sql  = "SELECT first_name from users where id = $id"; 
        $list = Yii::app()->db->createCommand($sql)->queryRow();
        return $list['first_name'];
        
      
    }

    public function searchKid($id)
    {

        $sql  = "SELECT id,name from kids where id = $id"; 
        $list = Yii::app()->db->createCommand($sql)->queryRow();
        if($list['name']) {
        	return $list['name'];
        }else{
        	return $list['id'];
    	}
        
      
    }

    public function getJobStatus($jobId){
    	if($jobId){
    		$sql  = "SELECT is_completed, is_approved from job_status where job_id = $jobId";  
    		$list = Yii::app()->db->createCommand($sql)->queryRow();
            $chkCompleted = $list['is_completed'];
            $chkApproved = $list['is_approved'];
            
            $setImage = Yii::app()->baseUrl."/img/";

            if($chkCompleted == '0' && $chkApproved=='0'){
            	$img = '<img src="'.$setImage.'star_empty.png"  width="150px"  />';
            }elseif($chkCompleted == '1' && $chkApproved=='0'){
            	$img = '<img src="'.$setImage.'star_completed.png"  width="150px"  />';
            }elseif($chkCompleted == '1' && $chkApproved=='1'){
            	$img = '<img src="'.$setImage.'star_completed_approved.png"  width="150px"  />';
            }else{
            	$img = '';
            }

    	}
    	return $img;

    }	



}
