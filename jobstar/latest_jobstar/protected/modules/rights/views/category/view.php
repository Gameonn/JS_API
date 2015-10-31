

<?php
/* @var $this DocController */
/* @var $model Doc */

$this->breadcrumbs=array(
    'Category'=>array('admin'),
    $model->name,
);

$this->menu=array(
    array('label'=>'List Category', 'url'=>array('index')),
    array('label'=>'Delete Category', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this category?')),
    array('label'=>'Manage Category', 'url'=>array('admin')),
);
?>

<h1>View Reward #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'name',                
        array(              
            'label'=>'Attachment',
            'type'=>'raw',
            'value'=>Category::showImage($model->id)
        ),      



    ),
)); ?>
