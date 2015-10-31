
<?php if($model->isNewRecord!='1' || !empty($model->option1)){ 
    $this->breadcrumbs=array(
    'Question'=>array('admin'),
    'Update question',
);

}else{
    $this->breadcrumbs=array(
    'Question'=>array('admin'),
    
);


} ?>
<?php if($model->isNewRecord =='1'){ ?>

<h1>Create Question</h1>
<?php } ?>
<div class="container">
    

<div class="row form-group">




<div class="tab-content">

 <div id="materials" class="tab-pane mine active"> 




<div class="form addfileform">

<?php 
    $form=$this->beginWidget('CActiveForm', array(
    'id'=>'question-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
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
        <?php echo $form->labelEx($model,'qustext'); ?>
        <?php echo $form->textField($model,'qustext',array('size'=>50,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'qustext'); ?>
    </div>


    
<div class="update-question-secton">

<!-- show all images in the existing table -->
<?php if($model->isNewRecord!='1' || !empty($model->option1)){ 
        if($model->id!=''){
            echo $allImages = Question::model()->showImage($model->id,option1);
        }

 }  ?>



<div class="row form-group"> 
        <?php echo $form->labelEx($model,'option1'); ?>
        <?php echo $form->fileField($model,'option1'); ?>
        <?php echo $form->error($model,'option1'); ?>

</div>
</div>

<div class="update-question-secton">
<?php if($model->isNewRecord!='1' || !empty($model->option2)){ 
        if($model->id!=''){
            echo $allImages = Question::model()->showImage($model->id,option2);
        }

 }  ?>
 <div class="row form-group">

        <?php echo $form->labelEx($model,'option2'); ?>
        <?php echo $form->fileField($model,'option2'); ?>
        <?php echo $form->error($model,'option2'); ?>
</div>
 </div>

<div class="update-question-secton">
<?php if($model->isNewRecord!='1' || !empty($model->option3)){ 
        if($model->id!=''){
            echo $allImages = Question::model()->showImage($model->id,option3);
        }

 }  ?>
 

 <div class="row form-group">

        <?php echo $form->labelEx($model,'option3'); ?>
        <?php echo $form->fileField($model,'option3'); ?>
        <?php echo $form->error($model,'option3'); ?>
</div></div>


<div class="update-question-secton">
<?php if($model->isNewRecord!='1' || !empty($model->option4)){ 
        if($model->id!=''){
            echo $allImages = Question::model()->showImage($model->id,option4);
        }

 }  ?>
 <div class="row form-group">

        <?php echo $form->labelEx($model,'option4'); ?>
        <?php echo $form->fileField($model,'option4'); ?>
        <?php echo $form->error($model,'option4'); ?>
        
    </div>
</div>


<div class="row form-group question-answer-block"> 
        <?php echo $form->labelEx($model,'answer'); ?>
        <?php
                echo $form->radioButtonList($model, 'answer',
                    array(  'option1' => 'First Option',
                            'option2' => 'Second Option',
                            'option3' => 'Third Option',
                            'option4' => 'Fourth Option' ) );
            ?>         <?php echo $form->error($model,'answer'); ?>
</div>
<div style="clear:both; width:100%; display:block;"></div>
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











