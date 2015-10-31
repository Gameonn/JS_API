
<?php if($model->isNewRecord!='1' || !empty($model->filepath)){ 
    $this->breadcrumbs=array(
    'Category'=>array('admin'),
    'Update category',
);

}else{
    $this->breadcrumbs=array(
    'Category'=>array('admin'),
    
);


} ?>
<?php if($model->isNewRecord =='1'){ ?>

<h1>Create Category</h1>
<?php } ?>
<div class="container">
    

<div class="row form-group">




<div class="tab-content">

 <div id="materials" class="tab-pane mine active"> 




<div class="form addfileform">

<?php 
    $form=$this->beginWidget('CActiveForm', array(
    'id'=>'category-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'htmlOptions' => array(
    'enctype' => 'multipart/form-data',
    'class'=>'form-horizontal'
    ),
)); ?>



    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php $form->errorSummary($model); ?>



    <div class="row form-group">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>128, 'required'=>'required')); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>


    
<div class="update-question-secton">

<!-- show all images in the existing table -->
<?php if($model->isNewRecord!='1' || !empty($model->filepath)){ 
        if($model->id!=''){
            echo $allImages = Category::model()->showImage($model->id);
        }

 }  ?>
                   
        <?php echo $form->labelEx($model,'filepath'); ?>
        <?php echo $form->fileField($model,'filepath'); ?>
        <?php echo $form->error($model,'filepath'); ?>
        
</div>
<div class="clearfix"></div>
    <div class="row form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->


</div></div>
</div></div></div>
<script type="text/javascript">
     function loadXMLDoc(rewardId,dcId,Div_id)
        {
            var chk = confirm('Are you sure you want to delete this image.?');
            var dataString = 'id='+ dcId + '&rewardId=' + rewardId;
           // var dataString = 'chat='+ test + '&com_msgid=' + Id + '&id=' + Id + '&type=' + type;
            if(chk == true) {
                    $.ajax({
                  type:'GET',
                  url: 'backend.php?r=rights/reward/superdelete',
                  data : dataString,
                }).done(function(data){
                    //alert(data);
                    $('#'+Div_id).html(data);
                    alert('Image deleted successfully.');
                });
            } else {
            return false;
        }

        }

</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.MultiFile.js" type="text/javascript" language="javascript"></script>











