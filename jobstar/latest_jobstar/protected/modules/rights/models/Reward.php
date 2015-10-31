<?php

/**
 * This is the model class for table "reward".
 *
 * The followings are the available columns in table 'reward':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property decimal $price
 * @property string $createTime
 * @property int $status

 */
class Reward extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reward';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, price', 'required'),
			array('title', 'length', 'max'=>50),
			//array('price', 'numerical', 'integerOnly'=>true, 'on' => 'insert'),
			array('price', 'length', 'min'=>1, 'max'=>5, 'on' => 'insert'),
			//array('tags', 'normalizeTags'),
			//array('images', 'file','types'=>'jpg,gif,jpeg','maxSize'=>'15972043', 'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.', 'allowEmpty'=>true, 'on'=>'insert'),
			//array('images', 'file', 'types'=>'jpg,gif,png', 'maxSize'=>'204800', 'allowEmpty'=>false, 'maxFiles'=>1),
			//array('images', 'files', 'types'=>'jpg,gif,jpeg'),
			//array('images', 'file','safe'=>true, 'allowEmpty' => false, 'types'=>'jpeg,jpg,gif,png', 'on' => 'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title,category_id,price,description,status,tags', 'safe', 'on'=>'search'),
		);
	}

public function files($attribute,$params)
{
    $validator= CValidator::createValidator('file', $this, $attribute, $params);
    foreach(CUploadedFile::getInstances($this,$attribute) as $file)
    {
        $this->$attribute= $file;
        $validator->validate($this, $attribute);
    }
}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tags'=>array(self::HAS_MANY, 'Tags', 'reward_id'),
			'rewardimages'=>array(self::HAS_MANY, 'RewardImages', 'reward_id'),
            'category'=>array(self::HAS_ONE, 'Category', 'reward_id'),
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
			'category_id' => 'Category',
			'description' => 'Description',
			'price' => 'Price',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('status',$this->status,true);
		//$criteria->order = "id DESC";
		//echo "<pre>";
		//print_r($criteria);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchCat($id)
	{
		//$sql  = 'SELECT name from category where id in ('.$id.')';	
      	//$list = Yii::app()->db->createCommand($sql)->queryRow();
	 	//return $list['name'];
	 	//echo "<pre>";
	 	//print_r($list['name']);
	 	//foreach ($list as $key => $value) {
        //	$list .= $value;
    	//}	
    	$sql  = "SELECT name from category where id = $id";	
      	$list = Yii::app()->db->createCommand($sql)->queryRow();
	 	return $list['name'];
		
	  
	}
	public function searchTag($id)
	{
		$sql  = 'SELECT name from tags where reward_id in ('.$id.')';	
      	$list = Yii::app()->db->createCommand($sql)->queryAll();
	 	//return $list['name'];
	 	$list1 = '';
	 	foreach ($list as $key => $value) {
	 		$list1 .= ",".$value['name'];
	 	}
	  	return substr($list1,1);
	}




	public function showImage($id)
	{
   
		//get all images of selected reward
		if($id) {
			$sql  = "SELECT image from rewardimages where reward_id = $id AND defaultimage='1'";	
      		$list = Yii::app()->db->createCommand($sql)->queryRow();
	 		//return $list['name'];
	 		//$filepath = 'IMG_2510.JPG';
	 		$filepath = $list['image'];
		   //$img=CHtml::image(Yii::app()->baseUrl."/images/reward/$id/".$filepath);
		   $setImage = Yii::app()->baseUrl."/images/reward/".$id."/".$filepath;
		   if($filepath!='') {
		   		$img = '<img src="'.$setImage.'"  width="150px"  />';
			}else{
				$setImage = 'IMG_2510.JPG';
				$img = '<img src="'.$setImage.'"  width="150px"  />';
			}
		}
	return $img;
	}




	public function showAllImages($id)
	{
   
		//get all images of selected reward
		if($id) {
			$list1 = '';
			$sql  = "SELECT image,id,defaultimage from rewardimages where reward_id = $id";	
      		$list = Yii::app()->db->createCommand($sql)->queryAll();
      		$list1 .=' <form method="post" name="default-image-form" id="default-image-form" action="backend.php?r=rights/reward/view&id='.$id.'">';
      		foreach ($list as $key => $value) {
      			$dbDefaultImage = $value['defaultimage'];
      			if($dbDefaultImage == '1') {
      				$selected = 'checked="checked"';
      			}else{
      				$selected = '';
      			}
      			$imageId = $value['id'];
      			$setImage = Yii::app()->baseUrl."/images/reward/".$id."/".$value['image'];
	 			//$list1 .= ",".$value['image'];
	 			$list1 .= " <br/>".'<img src="'.$setImage.'"  width="150px"  /><input type="radio" name="setDefaultImage" value="'.$imageId.'" '.$selected.' />';
	 		}
	 		$list1 .='<br/><input type="hidden" name="reward_id" value="'.$id.'"><input type="submit" name="defaultImageButton" id="defaultImageButton" /></form>';
		}
		return substr($list1,1);
	}

	public function showAllImagesWithDelIcon($rewardId)
	{
   
		//get all images of selected reward
		if($rewardId) {
			$list1 = '';
			$sql  = "SELECT image,id from rewardimages where reward_id = $rewardId";	
      		$list = Yii::app()->db->createCommand($sql)->queryAll();
      		foreach ($list as $key => $value) {
      			$imageId = $value['id'];
      			$setImage = Yii::app()->baseUrl."/images/reward/".$rewardId."/".$value['image'];
	 			//$list1 .= ",".$value['image'];
	 			$list1 .= " ".'<div id="Div_'.$imageId.'" class="manageUpdateImage"><img src="'.$setImage.'"  width="150px"  /><button class="btn btn-primary btn-Margin"  type="button" onclick="loadXMLDoc('.$rewardId.','.$imageId.',\'Div_'.$imageId.'\')">Delete</button></div>
';
	 		}
		}
		return substr($list1,1);
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
}
