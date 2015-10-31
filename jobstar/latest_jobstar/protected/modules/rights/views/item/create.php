<?php
/* @var $this DocsController */
/* @var $model Docs */

/*
$this->breadcrumbs=array(
    'Dashboard'=>array('index'),
    'Create Document',
);
*/

$this->menu=array(
    array('label'=>'List Docs', 'url'=>array('index')),
    array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>




<?php $this->renderPartial('_form', array('model'=>$model)); ?>