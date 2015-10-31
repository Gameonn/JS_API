<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $name
 * @property string $parent_category_id
 */
class RewardImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rewardimages';
	}


 
 /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DocCategory the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
 public  function mytree() {
            
    }


     public  function getTree() {
        if (empty(self::$asTree)) {
            $rows = self::model()->findAll('parent_category_id IS NULL');
            foreach ($rows as $item) {
                self::$asTree[] = self::getTreeItems($item);
            }
        }
 
        return self::$asTree;
    }
 
    private  function getTreeItems($modelRow) {
 
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


    public static function nodetree($param=array()) {
    $refs = array();
    $list = array();

    $nodes = Yii::app()->db->createCommand('select * from category')->queryAll();

    foreach ($nodes as $data) {
        $thisref = &$refs[ $data['id'] ];
        $thisref['parent_category_id'] = $data['parent_category_id'];
        $thisref['name'] = $data['name'];
        if ($data['parent_category_id'] == 0) {
            $list[ $data['id'] ] = &$thisref;
        } else {
            $refs[ $data['parent_category_id'] ]['children'][ $data['id'] ] = &$thisref;
        }   
    }           
    return $list;
}









}
