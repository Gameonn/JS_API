<?php


$this->menu=array(
    array('label'=>'Manage Rewards', 'url'=>array('admin')),
);
?>


<h1>Create Reward</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>