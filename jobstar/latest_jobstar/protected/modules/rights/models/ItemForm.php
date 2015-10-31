<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $name
 * @property string $parent_category_id
 */
class Reward extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, category_id', 'required'),
			array('name', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, category_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
    return array(
        'category' => array(self::BELONGS_TO, 'category', 'category_Id'),
    );
}
 
 
 public static function getTree() {
        if (empty(self::$asTree)) {
            $rows = self::model()->findAll('parent_category_id IS NULL');
            foreach ($rows as $item) {
                self::$asTree[] = self::getTreeItems($item);
            }
        }
 
        return self::$asTree;
    }
 
    private static function getTreeItems($modelRow) {
 
        if (!$modelRow)
            return;
 
        if (isset($modelRow->subcategories)) {
            $chump = self::getTreeItems($modelRow->subcategories);
            if ($chump != null)
                $res = array('children' => $chump, 'text' => CHtml::link($modelRow->Name, '#', array('id' => $modelRow->id)));
            else
                $res = array('text' => CHtml::link($modelRow->Name, '#', array('id' => $modelRow->id)));
            return $res;
        } else {
            if (is_array($modelRow)) {
                $arr = array();
                foreach ($modelRow as $leaves) {
                    $arr[] = self::getTreeItems($leaves);
                }
                return $arr;
            } else {
                return array('text' => CHtml::link($modelRow->Name, '#', array('id' => $modelRow->id)));
            }
        }
    }
}
