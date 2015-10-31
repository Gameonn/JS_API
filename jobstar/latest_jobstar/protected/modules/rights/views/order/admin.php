<?php
/* @var $this UserController */
/* @var $model User */

?>
<?php
/* @var $this UserdocsController */
/* @var $model Userdoc */

$this->breadcrumbs=array(
	'Order'=>array('admin'),
	'Manage',
);

 

$this->menu=array(
	array('label'=>'List Oder', 'url'=>array('admin')),
); 

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#order-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Orders</h1>

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
	'id'=>'order-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		id,
        //product,
       array(  'name' => 'User',
		       'header' => 'User',
					      'type' => 'html',
						  'value' => 'Order::searchUser($data->user_id)',
						  //'filter'=>CHtml::listData(Tags::model()->findAll(), 'id', 'name')
						  'filter'=>false,
            ), 
       array(  'name' => 'Product',
		       'header' => 'Product',
					      'type' => 'html',
						  'value' => 'Order::searchProduct($data->id,$data->user_id)',
						  //'filter'=>CHtml::listData(Tags::model()->findAll(), 'id', 'name')
						  'filter'=>false,
            ), 
        array(          
            'name'=>'ordering_date',
			'type'=>'html',
			//'value'=>array(), 
			'filter'=>false,
        ),
        array(          
            'name'=>'grandtotal',
			'type'=>'html',
			//'value'=>array(), 
			'filter'=>false,
        ),
		array(          
            'name'=>'status',
			'type'=>'html',
			'value'=>array($this,'getStatus'), 
			'filter'=>false,
        ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}'
		),
	),
));
	
?>


<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {

   jQuery(".activate").on('click',function(){
   			//alert('1111');
			   jQuery.ajax({
			      type:'POST',
				  url: 'backend.php?r=rights/order/approve',
				  data : {userid : $(this).attr('data-id')  }
			    }).done(function(response){
		     alert(response);
			 //jQuery("#activebutton_"+jQuery(this).attr('data-id')).hide();			 
			 //jQuery("#deactivebutton_"+jQuery(this).attr('data-id')).show();
		   });
   
   })
   
  
   
   jQuery(".deactivate").on('click',function(){
   			//alert('2222');
		    jQuery.ajax({
		      type:'POST',
			  url: 'backend.php?r=rights/order/deactivate',
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



