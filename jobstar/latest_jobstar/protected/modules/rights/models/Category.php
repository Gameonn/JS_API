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
class Category extends CActiveRecord
{  


    public $name;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'category';
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
            //array('username, password, email', 'length', 'max'=>128),
            //array('profile', 'safe'),
            //array('name', 'unique', 'message' => "This category name already exists."),
            //array('email', 'unique', 'message' => "This user's email address already exists."),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on'=>'search'),
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
            'filepath'=>'Image',
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
        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        //$criteria->order = "id DESC"; //it will break the sorting
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



        public function showImage($id)
    {
   
        //get all images of selected reward
        if($id) {
            $sql  = "SELECT filepath from category where id = $id";    
            $list = Yii::app()->db->createCommand($sql)->queryRow();
            $filepath = $list['filepath'];
           //$img=CHtml::image(Yii::app()->baseUrl."/images/reward/$id/".$filepath);
           $setImage = Yii::app()->baseUrl."/images/category/".$filepath;
           if($filepath!='') {
                $img = '<img src="'.$setImage.'"  width="150px"  />';
            }else{
                $setImage = 'IMG_2510.JPG';
                $img = '<img src="'.$setImage.'"  width="150px"  />';
            }
        }
    return $img;
    }







}
