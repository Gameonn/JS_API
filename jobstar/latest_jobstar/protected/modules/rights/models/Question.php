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
class Question extends CActiveRecord
{  


    public $name;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'questions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('qustext,option1,option2,option3,option4,answer', 'required','on'=>'insert'),
            array('qustext,answer', 'required','on'=>'update'),

            //array('username, password, email', 'length', 'max'=>128),
            array('qustext', 'unique', 'message' => "This question already exists."),
            array('option1', 'file','types'=>'jpg,gif,jpeg','maxSize'=>'15972043', 'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.', 'allowEmpty'=>true, 'on'=>'insert'),

            array('option1', 'file','types'=>'jpg,gif,jpeg','maxSize'=>'15972043', 'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.','allowEmpty'=>true,  'on'=>'update'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, qustext', 'safe', 'on'=>'search'),
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
            'qustext' => 'Question',
            'option1'=>'First Option',
            'option2'=>'Second Option',
            'option3'=>'Third Option',
            'option4'=>'Fourth Option',
            'answer' => 'Answer',
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
        $criteria->compare('qustext',$this->qustext,true);
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



    public function showImage($id,$field)
    {
   
        //get all images of selected reward
        if($id) {
            $sql  = "SELECT $field from questions where id = $id";    
            $list = Yii::app()->db->createCommand($sql)->queryRow();
            $filepath = $list[''.$field.''];
           $setImage = Yii::app()->baseUrl."/images/question/".$filepath;
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
