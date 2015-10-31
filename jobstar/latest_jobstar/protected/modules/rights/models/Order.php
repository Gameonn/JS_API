<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Reward[] $reward
 */
class Order extends CActiveRecord
{  


    public $name;
    public $from_date;
    public $to_date;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
           // array('name', 'required'),
            //array('username, password, email', 'length', 'max'=>128),
            //array('profile', 'safe'),
            //array('name', 'unique', 'message' => "This category name already exists."),
            //array('email', 'unique', 'message' => "This user's email address already exists."),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id,from_date,to_date,grandtotal', 'safe', 'on'=>'search'),
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
            'orderdetail'=>array(self::HAS_MANY, 'OrderDetail', 'order_id'),
            'reward'=>array(self::HAS_ONE, 'Reward', 'user_id'),
            //'orders' => array(self::HAS_MANY, 'Orders', 'userid'),
            //'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
            //'userExtends' => array(self::HAS_MANY, 'UserExtend', 'userid'),
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
            'name' => 'Name',
           // 'filepath'=>'Image',
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

        if(!empty($this->from_date) && empty($this->to_date))
        {
            $criteria->condition = "ordering_date >= '$this->from_date'";  // date is database date column field
        }elseif(!empty($this->to_date) && empty($this->from_date))
        {
            $criteria->condition = "ordering_date <= '$this->to_date'";
        }elseif(!empty($this->to_date) && !empty($this->from_date))
        {
            //$criteria->condition = "created_on  >= '$this->from_date' and created_on <= '$this->to_date'";
            $criteria->condition="ordering_date BETWEEN DATE('$this->from_date') AND DATE('$this->to_date')";

        }
        

        $criteria->compare('id',$this->id);
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

    public function searchUser($id)
    {

        $sql  = "SELECT first_name from users where id = $id"; 
        $list = Yii::app()->db->createCommand($sql)->queryRow();
        return $list['first_name'];
        
      
    }

    public function searchProduct($id,$userId)
    {
        $sql  = 'SELECT reward_id from shop_order_detail where order_id in ('.$id.')';   
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $list1 = '';
        foreach ($list as $key => $value) {
            $list1 .= ",".$value['reward_id'];
        }
        $getAllRewardIds =  substr($list1,1);
        //get all reward id names
        if($getAllRewardIds!='') {
        $sqlName  = 'SELECT title from reward where id in ('.$getAllRewardIds.')';   
        $listName = Yii::app()->db->createCommand($sqlName)->queryAll();
        $getNameList = '';
        foreach ($listName as $key1 => $value1) {
            $getNameList .= ",".$value1['title'];
        }
        return substr($getNameList,1);
        }
    }







}
