<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Question'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
$id=Yii::app()->user->id;

if($id==1)
{
$this->menu=array(
	//array('label'=>'List User', 'url'=>array('index')),
	//array('label'=>'Create User', 'url'=>array('create')),
	//array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Question', 'url'=>array('admin')),
);
}
?>

<h1>Update Question : #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array(
			'model'=>$model)); ?>
			