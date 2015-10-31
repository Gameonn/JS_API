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
class Kid extends CActiveRecord
{  


    public $name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'kids';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name, password', 'length', 'max'=>128),
			//array('profile', 'safe'),
			//array('username', 'unique', 'message' => "This user's name already exists."),
			//array('email', 'unique', 'message' => "This user's email address already exists."),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, password', 'safe', 'on'=>'search'),
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


		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'password' => 'Password',
			//'email' => 'Email',
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
		
		//$criteria->condition='userrole IS NOT NULL'; // added by ranjita

		$criteria->compare('id',$this->id);
		//$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		//$criteria->compare('last_name',$this->last_name,true);

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

	public function getAllKidsInDropDown($usertype)
	{
	$userlist= Yii::app()->db->createCommand("SELECT a.id,a.name FROM kids a WHERE a.is_deleted=0")->queryALl();
	$i=1;
	foreach($userlist as $users)
	{
	  $name = $users['name'];
	  $output[$i]['id']	 = $users['id'];
	  $output[$i]['name']= $name;
	  $i++;
	}
	
	return $output;
	}





	
	

}
