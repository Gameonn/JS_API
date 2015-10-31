<?php
/* @var $this UserController */
/* @var $model User */

?>
<?php
/* @var $this UserdocsController */
/* @var $model Userdoc */

$this->breadcrumbs=array(
	'Users'=>array('admin'),
	'Manage',
);

$this->widget('zii.widgets.CMenu', array(
    'firstItemCssClass'=>'first',
    'lastItemCssClass'=>'last',
    'htmlOptions'=>array('class'=>'actions'),
    'items'=>array(
        array(
            'label'=>Rights::t('core', 'Go to Users listings'),
            'url'=>array('/rights/user/admin'),
            'itemOptions'=>array('class'=>'item-assignments'),
        ),


    )
)); 
/*
$this->menu=array(
	array('label'=>'List Rewards', 'url'=>array('index')),
); */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#kid-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Kids</h1>
<!-- 
<form action="" method="POST" class="float:left;">
<select name="userType" onchange="this.form.submit()">
<?php
$selectedMD = '';
$selectedOD = '';

if($_REQUEST['userType']!=''){
	if($_REQUEST['userType']=='G' || $_SESSION['userType']=='G'){
		$selectedG = 'selected=selected';
	}else{
		$selectedK = 'selected=selected';
	}
}else {
	if($_SESSION['userType']=='G') {
		$selectedG = 'selected=selected';

	}elseif($_SESSION['userType']=='K'){
		$selectedK = 'selected=selected';
	}
}

?> 

<option value="G" <?php echo $selectedMD;?>>Grown Up</option>
<option value="K" <?php echo $selectedOD;?>>Kids</option>
</select> 
</form>
-->

<?php if(Yii::app()->user->hasFlash('admin')): ?>
<center>
<div class="flash-success" style="color:green">
	<?php echo Yii::app()->user->getFlash('admin'); ?>
</div>

<?php endif; ?>
<?php //echo CHtml::resetButton('Reset Search!', array('id'=>'form-reset-button')); ?>
<?php //echo CHtml::resetButton('Search', array('id'=>'form-search')); ?>



<?php
		
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kid-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		name,
		array(          
            'name'=>'Address',
			'type'=>'html',
            'value'=>array($this,'getuseraddress'), 
			'filter'=>false,
        ),
        array(          
            'name'=>'Grown Up',
			'type'=>'html',
            'value'=>array($this,'showParentName'), 
			'filter'=>false,
        ),
		array(          
            'name'=>'status',
			'type'=>'html',
			'value'=>array($this,'getStatus'), 
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
				  url: 'backend.php?r=rights/kid/approve',
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
			  url: 'backend.php?r=rights/kid/deactivate',
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



