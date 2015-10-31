<?php
/* @var $this UserdocsController */
/* @var $model Userdoc */

$this->breadcrumbs=array(
	'Reward'=>array('admin'),
	'Manage',
);

$this->widget('zii.widgets.CMenu', array(
    'firstItemCssClass'=>'first',
    'lastItemCssClass'=>'last',
    'htmlOptions'=>array('class'=>'actions'),
    'items'=>array(
        array(
            'label'=>Rights::t('core', 'Create'),
            'url'=>array('/rights/reward/create'),
            'itemOptions'=>array('class'=>'item-assignments'),
        ),


    )
)); 

$this->menu=array(
	array('label'=>'List Rewards', 'url'=>array('index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#reward-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Rewards</h1>


<!--
<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?> -->

<div class="search-form" style="display:none">

<?php //$this->renderPartial('_search',array('model'=>$model,)); ?>

</div>

<!-- search-form -->

<?php //echo CHtml::resetButton('Reset Search!', array('id'=>'form-reset-button')); ?>
<?php //echo CHtml::resetButton('Search', array('id'=>'form-search')); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'reward-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(  'name' => 'image',
		       'header' => 'Image',
					      'type' => 'html',
						  'value' => 'Reward::showImage($data->id)',
						   'filter'=>false
                ),		
		'title',
		array(  'name' => 'category_id',
		       'header' => 'Category',
					      'type' => 'html',
						  'value' => 'Reward::searchCat($data->category_id)',
						  'filter'=>CHtml::listData(Category::model()->findAll(), 'id', 'name')
                ),	
		array(  'name' => 'tags',
		       'header' => 'Tags',
					      'type' => 'html',
						  'value' => 'Reward::searchTag($data->id)',
						  'filter'=>CHtml::listData(Tags::model()->findAll(), 'id', 'name')
                ),        			
		
		array(          
            'name'=>'Posted Date',
			'type'=>'html',
			'value'=>'$data["createTime"]', 
			'filter'=>false,
        ),
		array(          
            'name'=>'Status',
			'type'=>'html',
			'value'=>array($this,'getStatus'), 
			'filter'=>false,
        ),
       /* array(
         'name'=>'status',
         'value'=>'$data["status"]==1?"Active":"Inactive"',
     ),*/
        		
		array(
			'class'=>'CButtonColumn',
			 'template'=>'{update}{view}{delete}{status}',
			/* 'buttons'=>array
			    (
			        'status' => array
			        (
			            'label'=>'Activate',
			            'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
			            'url'=>'Yii::app()->createUrl("rights/reward/approve", array("id"=>$data->id))',
			            'visible'=>'$data->status=="O"?TRUE:FALSE',
			            'options'=>array(
                              'class'=>'activatestatus',
                              'id'=>'user',
                              'title'=>'Click Here To Activate the User Status',
                        ),
			        ),

			    ), */

		),
	),
) 
); ?>


<script type="text/javascript">
/*<![CDATA[*/
function Show_Div(Div_id) {
            if (false == $(Div_id).is(':visible')) {
                $(Div_id).fadeIn();
            }
            else {
                $(Div_id).fadeOut();
            }
        }
jQuery(function($) {

   jQuery(".activate").on('click',function(){
   
			   jQuery.ajax({
			      type:'POST',
				  url: 'backend.php?r=rights/reward/approve',
				  data : {id : $(this).attr('data-id')  }
			    }).done(function(data){
		     	//alert(data);
			 //jQuery("#activebutton_"+$(this).attr('data-id')).hide();			 
			 //jQuery("#deactivebutton_"+$(this).attr('data-id')).show();
			 //jQuery('#divstatusD').html(response);
             alert('Reward activated successfully.');
		   });
   
   })
   
  
   
   jQuery(".deactivate").on('click',function(){
   		
		    jQuery.ajax({
		      type:'POST',
			  url: 'backend.php?r=rights/reward/deactivate',
			  data : {id : $(this).attr('data-id')}
		   }).done(function(data){
		     //alert(data);
			 //jQuery("#deactivebutton_"+jQuery(this).attr('data-id')).hide();
			 //jQuery("#activebutton_"+jQuery(this).attr('data-id')).show();
			  //jQuery('#divstatusA').html(response);
             alert('Reward deactivated successfully.');
		   });
   
   });



});

function Approve_clicked() {
         //alert('OI!'); 
         jQuery.ajax({
			      type:'POST',
				  url: 'backend.php?r=rights/reward/approve',
				  data : {id : $(this).attr('data-id')  }
			    }).done(function(response){
		     //alert($(this).attr('data-id'));
		     alert(response);
			 jQuery("#activebutton_"+$(this).attr('data-id')).hide();			 
			 jQuery("#deactivebutton_"+$(this).attr('data-id')).show();
		   });       
    }



/*]]>*/
</script>



