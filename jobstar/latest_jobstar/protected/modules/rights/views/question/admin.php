<?php
/* @var $this UserController */
/* @var $model User */

?>
<?php
/* @var $this UserdocsController */
/* @var $model Userdoc */

$this->breadcrumbs=array(
	'Question'=>array('admin'),
	'Manage',
);

$this->widget('zii.widgets.CMenu', array(
    'firstItemCssClass'=>'first',
    'lastItemCssClass'=>'last',
    'htmlOptions'=>array('class'=>'actions'),
    'items'=>array(
        array(
            'label'=>Rights::t('core', 'Create'),
            'url'=>array('/rights/question/create'),
            'itemOptions'=>array('class'=>'item-assignments'),
        ),


    )
)); 

$this->menu=array(
	array('label'=>'List Question', 'url'=>array('index')),
); 

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#question-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Question</h1>

    	<div class="success successDoc">
		<?php echo Yii::app()->user->getFlash('msg'); ?>
		</div>


<?php if(Yii::app()->user->hasFlash('admin')): ?>
<center>
<div class="flash-success" style="color:green">
	<?php echo Yii::app()->user->getFlash('admin'); ?>
</div>
</center>

<?php endif; ?>
<?php //echo CHtml::resetButton('Reset Search!', array('id'=>'form-reset-button')); ?>
<?php //echo CHtml::resetButton('Search', array('id'=>'form-search')); ?>



<?php
		
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'question-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		qustext,

		array(  'name' => 'option1',
       'header' => 'First Option',
			      'type' => 'html',
				  'value' => 'Question::showImage($data->id,option1)',
				   'filter'=>false
        ),
       array(  'name' => 'option2',
       'header' => 'Second Option',
			      'type' => 'html',
				  'value' => 'Question::showImage($data->id,option2)',
				   'filter'=>false
        ),
        array(  'name' => 'option3',
       'header' => 'Third Option',
			      'type' => 'html',
				  'value' => 'Question::showImage($data->id,option3)',
				   'filter'=>false
        ),
        array(  'name' => 'option4',
       'header' => 'Fourth Option',
			      'type' => 'html',
				  'value' => 'Question::showImage($data->id,option4)',
				   'filter'=>false
        ),
		array(          
            'name'=>'status',
			'type'=>'html',
			'value'=>array($this,'getStatus'), 
			'filter'=>false,
        ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{view}{delete}'
		),
	),
));
	
?>


<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {

   jQuery(".activate").on('click',function(){
   
			   jQuery.ajax({
			      type:'POST',
				  url: 'backend.php?r=rights/question/approve',
				  data : {userid : $(this).attr('data-id')  }
			    }).done(function(response){
		     alert(response);
			 //jQuery("#activebutton_"+jQuery(this).attr('data-id')).hide();			 
			 //jQuery("#deactivebutton_"+jQuery(this).attr('data-id')).show();
		   });
   
   })
   
  
   
   jQuery(".deactivate").on('click',function(){
   
		    jQuery.ajax({
		      type:'POST',
			  url: 'backend.php?r=rights/question/deactivate',
			  data : {userid : $(this).attr('data-id')}
		   }).done(function(response){
		     alert(response);
			 //jQuery("#deactivebutton_"+$(this).attr('data-id')).hide();
			 //jQuery("#activebutton_"+$(this).attr('data-id')).show();
		   });
   
   });



});
/*]]>*/
</script>



