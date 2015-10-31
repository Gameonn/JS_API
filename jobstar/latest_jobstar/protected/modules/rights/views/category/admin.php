<?php
/* @var $this UserController */
/* @var $model User */

?>
<?php
/* @var $this UserdocsController */
/* @var $model Userdoc */

$this->breadcrumbs=array(
	'Category'=>array('admin'),
	'Manage',
);

$this->widget('zii.widgets.CMenu', array(
    'firstItemCssClass'=>'first',
    'lastItemCssClass'=>'last',
    'htmlOptions'=>array('class'=>'actions'),
    'items'=>array(
        array(
            'label'=>Rights::t('core', 'Create'),
            'url'=>array('/rights/category/create'),
            'itemOptions'=>array('class'=>'item-assignments'),
        ),


    )
)); 

$this->menu=array(
	array('label'=>'List Category', 'url'=>array('index')),
); 

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#category-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Category</h1>

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
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(  'name' => 'filepath',
       'header' => 'Image',
			      'type' => 'html',
				  'value' => 'Category::showImage($data->id)',
				   'filter'=>false
        ),
        name,
		array(          
            'name'=>'status',
			'type'=>'html',
			'value'=>array($this,'getStatus'), 
			'filter'=>false,
        ),
        /*array(          
            'name'=>'delete',
			'type'=>'html',
			'value'=>array($this,'deleteCategory'), 
			'filter'=>false,
        ),*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}'
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
				  url: 'backend.php?r=rights/category/approve',
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
			  url: 'backend.php?r=rights/category/deactivate',
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



