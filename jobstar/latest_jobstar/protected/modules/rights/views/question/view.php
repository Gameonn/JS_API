

<?php
/* @var $this DocController */
/* @var $model Doc */

$this->breadcrumbs=array(
    'Question'=>array('admin'),
    $model->qustext,
);

$this->menu=array(
    array('label'=>'List Question', 'url'=>array('index')),
    array('label'=>'Delete Question', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this question?')),
    array('label'=>'Manage Question', 'url'=>array('admin')),
);
?>

<h1>View Question #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'qustext',                
        array(              
            'label'=>'First Option',
            'type'=>'raw',
            'value'=>Question::showImage($model->id,option1)
        ), 
        array(              
            'label'=>'Second Option',
            'type'=>'raw',
            'value'=>Question::showImage($model->id,option2)
        ), 
        array(              
            'label'=>'Third Option',
            'type'=>'raw',
            'value'=>Question::showImage($model->id,option3)
        ), 
        array(              
            'label'=>'Fourth Option',
            'type'=>'raw',
            'value'=>Question::showImage($model->id,option4)
        ), 
        array(              
            'label'=>'Answer',
            'type'=>'raw',
            'value'=>Question::showImage($model->id,$model->answer)
        ),      



    ),
)); ?>
