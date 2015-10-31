

<?php
/* @var $this DocController */
/* @var $model Doc */

$this->breadcrumbs=array(
    'Reward'=>array('index'),
    $model->title,
);

$this->menu=array(
    array('label'=>'List Reward', 'url'=>array('index')),
    array('label'=>'Delete Reward', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Reward', 'url'=>array('admin')),
);
?>

<h1>View Reward #<?php echo $model->id; ?></h1>
<div class="rewardview">
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        //'id',
        'title',                
        'description',
        'createTime',
        array(              
            'label'=>'Category Type',
            'type'=>'raw',
            'value'=>Reward::searchCat($model->category_id)
        ),
        array(              
            'label'=>'Tags',
            'type'=>'raw',
            'value'=>Reward::searchTag($model->id)
        ),
        array(              
            'label'=>'Attachment',
            'type'=>'raw',
            'value'=>Reward::showAllImages($model->id)
        ),      



    ),
)); ?>
</div>