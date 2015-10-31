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
<div id='AjFlash' class="flash-success" style="display:none"></div>
<!-- search-form -->

<?php //echo CHtml::resetButton('Reset Search!', array('id'=>'form-reset-button')); ?>
<?php //echo CHtml::resetButton('Search', array('id'=>'form-search')); ?>
<form id="form-id" method="POST">
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
						  //'filter'=>CHtml::listData(Tags::model()->findAll(), 'id', 'name')
						  'filter'=>false,
                ),        			
		
		/*array(          
            'name'=>'Posted Date',
			'type'=>'html',
			'value'=>'$data["createTime"]', 
			'filter'=>false,
        ),*/
        array(          
            'name'=>'Status',
            'type'=>'html',
            'value'=>array($this,'getStatus'), 
            'filter'=>false,
        ),
        array(          
            'name'=>'In Stock',
            'type'=>'html',
            'value'=>'$data["instock"]', 
            'filter'=>false,
        ),
        array(
            'header' => 'Quantity',
            'value' => 'CHtml::textField("Reward[instock]", "",array("id"=>"data-id".$data["instock"],"size"=>5,"maxlength"=>5))',
            'type' => 'raw',
        ),
       /* 
        $data["instock"]
       array(
         'name'=>'status',
         'value'=>'$data["status"]==1?"Active":"Inactive"',
     ),*/
        		
		array(
			'class'=>'CButtonColumn',
			 'template'=>'{Update Quantity}{update}{view}{delete}',
			/* 'buttons' => array(
                'Update Quantity' => array(
                    'options' => array('class' => 'save-ajax-button'),
                    'url' => 'Yii::app()->createUrl("rights/reward/savequantity", array("id"=>$data["id"]))',
                ),
                'view',
                'delete',
            ), */
			'buttons'=>array
    (
        'Update Quantity' => array
        (
            'label'=>'Update quantity',
            'imageUrl'=>Yii::app()->request->baseUrl.'/img/update-qty.png',
            'click'=>"function(){
                                var getquantity =jQuery(this).parents('tr').find('input[type=text]').val();
                                    if(getquantity == ''){
                                        alert('Please enter quantity.');
                                        return false;
                                    }else{
                                        var dataString = 'qty=' + getquantity;
                                        jQuery.fn.yiiGridView.update('reward-grid', {
                                        type:'POST',
                                        url:jQuery(this).attr('href'),
                                        data : dataString,
                                        success:function(data) {
                                              jQuery('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              jQuery.fn.yiiGridView.update('reward-grid');
                                        }
                                    })
                                    return false;

                                    }
                                    return false;
                              }
                     ",
            'url'=>'Yii::app()->controller->createUrl("savequantity",array("id"=>$data->primaryKey))',
        ),
    )

		),
	),
) 
); ?>
</form>

<script type="text/javascript">
/*<![CDATA[*/
/*  
http://www.yiiframework.com/wiki/658/update-cgridview-row-separately-using-ajax-request/
*/
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

    jQuery('#grid-view-id a.save-ajax-button').on('click', function(e)
    {
        alert('ssss');
        var row = jQuery(this).parent().parent();
 
        var data = jQuery('input', row).serializeObject();
 		alert(data);
        jQuery.ajax({
            type: 'POST',
            data: data,
            url: jQuery(this).attr('href'),
            success: function(data, textStatus, jqXHR) {
                console.log(data);
                console.log(textStatus);
                console.log(jqXHR);
            },
            error: function(textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
        return false;
    });
 
 
    jQuery.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        jQuery.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

/*function Approve_clicked() {
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
    }*/



/*]]>*/
</script>



