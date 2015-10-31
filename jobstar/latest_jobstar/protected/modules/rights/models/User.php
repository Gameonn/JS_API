<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $profile
 *
 * The followings are the available model relations:
 * @property Orders[] $orders
 * @property Post[] $posts
 * @property UserExtend[] $userExtends
 */
class User extends CActiveRecord
{  


    public $name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('password, email', 'required'),
			array('username, password, email', 'length', 'max'=>128),
			//array('profile', 'safe'),
			array('username', 'unique', 'message' => "This user's name already exists."),
			array('email', 'unique', 'message' => "This user's email address already exists."),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, email', 'safe', 'on'=>'search'),
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
			//'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
			//'userExtends' => array(self::HAS_MANY, 'UserExtend', 'userid'),
			//'usertype' => array(self::HAS_MANY, 'Usertypes', 'userid'),
			//'CustomerName' => array(self::HAS_MANY, 'UserExtend', 'userid'),
			'kids' => array(self::HAS_MANY, 'Kid', 'user_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			//'profile' => 'Profile',
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
			$criteria->join = "LEFT JOIN tbl_user_extend ON b.id=tbl_user_extend.userid"; 						
			$criteria->condition = "tbl_user_extend.first_name like '%".$_REQUEST['User']['name']."%' or tbl_user_extend.last_name like '%".$_REQUEST['User']['name']."%'"; 	
		}
		
		
		if(!empty($_REQUEST['User']['usertype']) || $_REQUEST['User']['usertype']!=0)
		{
			
			$criteria->join = "LEFT JOIN tbl_user_usertypes ON b.id=tbl_user_usertypes.userid LEFT JOIN tbl_usertypes ON tbl_usertypes.id=tbl_user_usertypes.usertype"; 						
			$criteria->condition = "tbl_user_usertypes.usertype='".$_REQUEST['User']['usertype']."'"; 			
		}*/
		
		$criteria->condition='superuser = 0'; // added by ranjita, dont show admin user

		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);

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
            $sql  = "SELECT image from users where id = $id";    
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

   public function getAllUserExceptAdmin($usertype)
	{
	$userlist= Yii::app()->db->createCommand("SELECT a.id,a.first_name,a.last_name FROM users a WHERE a.is_deleted=0 AND a.superuser='0' ")->queryALl();
	$i=1;
	foreach($userlist as $users)
	{
	  $name=$users['first_name'].' '.$users['last_name'];
	  $output[$i]['id']	 = $users['id'];
	  $output[$i]['name']= $name;
	  $i++;
	}
	
	return $output;
	}




}
