<?php
/* @var $this UserController */
/* @var $model User */

?>
<?php
/* @var $this UserdocsController */
/* @var $model Userdoc */

$this->breadcrumbs=array(
	'Users'=>array('admin'),
	'Jobs',
);

$this->widget('zii.widgets.CMenu', array(
    'firstItemCssClass'=>'first',
    'lastItemCssClass'=>'last',
    'htmlOptions'=>array('class'=>'actions'),
    'items'=>array(
        array(
            //'label'=>Rights::t('core', 'Go to Kids listings'),
            //'url'=>array('/rights/kid/admin'),
            //'itemOptions'=>array('class'=>'item-assignments'),
        ),


    )
)); 


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#job-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Jobs</h1>


<?php if(Yii::app()->user->hasFlash('admin')): ?>
<center>
<div class="flash-success" style="color:green">
	<?php echo Yii::app()->user->getFlash('admin'); ?>
</div>

<?php endif; ?>
<?php //echo CHtml::resetButton('Reset Search!', array('id'=>'form-reset-button')); ?>
<?php //echo CHtml::resetButton('Search', array('id'=>'form-search')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'page-form',
    'enableAjaxValidation'=>true,
)); ?>
 
<b>From :</b>
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'from_date',  // name of post parameter
    'value'=>Yii::app()->request->cookies['from_date']->value,  // value comes from cookie after submittion
     'options'=>array(
        'showAnim'=>'fold',
        'dateFormat'=>'yy-mm-dd',
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
));
?>
<b>To :</b>
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'to_date',
    'value'=>Yii::app()->request->cookies['to_date']->value,
     'options'=>array(
        'showAnim'=>'fold',
        'dateFormat'=>'yy-mm-dd',
 
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
));
?>
<?php echo CHtml::submitButton('Go'); ?>
<?php $this->endWidget(); ?>
 

<?php
		
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'job-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(  'name' => 'image',
       'header' => 'Image',
			      'type' => 'html',
				  'value' => 'Job::showImage($data->id)',
				   'filter'=>false
        ),
		title,
		description,
       array(  'name' => 'user_id',
		       'header' => 'Posted By (Grown Up)',
					      'type' => 'html',
						  'value' => 'Job::searchUser($data->user_id)',
						  //'filter'=>CHtml::listData(Tags::model()->findAll(), 'id', 'name')
						  //'filter'=>false,
						  'filter'=>CHtml::listData(User::getAllUserExceptAdmin(1),'id','name')
            ),        
       array(  'name' => 'kid_id',
		       'header' => 'Assigned To (Kid)',
					      'type' => 'html',
						  'value' => 'Job::searchKid($data->kid_id)',
						  //'filter'=>CHtml::listData(Tags::model()->findAll(), 'id', 'name')
						  //'filter'=>false,
						  'filter'=>CHtml::listData(Kid::getAllKidsInDropDown(1),'id','name')
            ),
         array(  'name' => 'created_on',
		       'header' => 'Date Posted',
					      'type' => 'html',
						  'filter'=>false,
            ),  
         array(  'name' => 'status',
		       'header' => 'status',
					      'type' => 'html',
					      'value' => 'Job::getJobStatus($data->id)',
						  'filter'=>false,
            ),          
		/*array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}{view}'
		),*/
	),
));
	
?>


<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {

   jQuery(".activate").on('click',function(){
   
			   jQuery.ajax({
			      type:'POST',
				  url: 'backend.php?r=rights/user/approve',
				  data : {userid : $(this).attr('data-id')  }
			    }).done(function(response){
		     alert(response);
			 jQuery("#activebutton_"+jQuery(this).attr('data-id')).hide();			 
			 jQuery("#deactivebutton_"+jQuery(this).attr('data-id')).show();
		   });
   
   })
   
  
   
   jQuery(".deactivate").on('click',function(){
   
		    jQuery.ajax({
		      type:'POST',
			  url: 'backend.php?r=rights/user/deactivate',
			  data : {userid : $(this).attr('data-id')}
		   }).done(function(response){
		     alert(response);
			 jQuery("#deactivebutton_"+$(this).attr('data-id')).hide();
			 jQuery("#activebutton_"+$(this).attr('data-id')).show();
		   });
   
   });



});
/*]]>*/
</script>



