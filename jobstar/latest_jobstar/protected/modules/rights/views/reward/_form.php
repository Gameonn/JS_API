
       
      <!--   <link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-lightness.css">
        <link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/demo.css">
        <link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/jquery.checkboxtree.min.css">
        <script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/jquery.checkboxtree.min.js"></script>


        <script type="text/javascript">

           /* var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-10814140-5']);
            _gaq.push(['_trackPageview']);

            (function() {
             var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
             ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
             var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
             })(); */

         </script>  
--> 





<?php if($model->isNewRecord!='1'){ 
    $this->breadcrumbs=array(
    'Reward'=>array('admin'),
    'Update reward',
);

}else{
   $this->breadcrumbs=array(
    'Reward'=>array('admin'),
    'Create reward',

);

} 

?>
<!-- <h1>Create Reward</h1> -->
<div class="container">
    

<div class="row form-group">



<style type="text/css">
.form{
    width: 100%;
    float: left;
}
.form-horizontal .form-group{
    margin-left: 0;
   
}

</style>

<div class="tab-content">

 <div id="materials" class="tab-pane mine active"> 




<div class="form addfileform reward-form-section">

<?php 
    $form=$this->beginWidget('CActiveForm', array(
    'id'=>'reward-form',
    //'enableClientValidation'=>true,
    'clientOptions'=>array(
    //'validateOnSubmit'=>true,
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

    <div id="errorForm" class="errorForm"></div>

    <?php $form->errorSummary($model); ?>


    <?php echo $form->hiddenField($model,'userrole',array('value'=>Yii::app()->user->getId())); ?>  
    <?php 
      if(isset($_GET['titleSet']) && $_GET['titleSet']!='') {
          $getTitle = $_GET['titleSet'];
      }
      if(isset($_GET['priceSet']) && $_GET['priceSet']!='') {
          $getPrice = $_GET['priceSet'];
      }

    ?>
    <div class="row form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('value'=>$getTitle,'size'=>50,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'title'); ?>
        <div id="errorTitle" class="errorTitle"></div>
         <div style="color:red;" id="dupTitle">
         <?php echo Yii::app()->user->getFlash('errDupTitle'); ?>
         </div> 
    </div>

<?php 
$categories[0]='--Select--';
//$nodes = Category::model()->nodetree(); // tree vieww for category
//echo "<pre>";
//print_r($nodes);

$categories=CHtml::listData(Category::model()->findAll(), 'id', 'name');

$tableTags = '';
if($model->id!=''){
    $tableTags = Reward::model()->searchTag($model->id);
}
?>


<!-- showing tree view checkboxes 
    <div class="row form-group">
        <?php //echo $form->labelEx($model,'category_id'); ?>
    <?php //echo $form->dropDownList($model, 'category_id', $categories);?>
        <?php //echo $form->error($model,'category_id'); ?>
        <div id="tabs-1"> -->
<?php //$this->beginWidget('ext.ECheckBoxTree') ?>
    <?php 
       /* foreach ($nodes as $key=>$value) {
            if ($data['parent_category_id'] == 0) { ?>
                <ul>
                    <li><input type="checkbox" name="cat[]" id="<?php echo $key;?>" value="<?php echo $key;?>" /><?php echo $value['name'];?></li>
                </ul>
          <?php  } else { ?>
          <?php  }  
          if(is_array($value)) {
              // echo "111"; 
            foreach ($value as $key=>$val) { 
                if($key != 'name' &&  $key !='parent_category_id') {
                    foreach($val as $keychild=>$lastVal) {
                ?>
                 <ul style="padding-left:50px;">
                    <li><input type="checkbox" name="subcat[]" id="<?php echo $keychild;?>" value="<?php echo $keychild;?>" />
                        <?php 
                       // echo "key: ".$key;
                        //echo "<pre>";
                        //print_r($val) 
                            echo $lastVal['name']; ?>
                    </li>
                </ul>
          <?php } } } } else{
                //echo "....";
          }

        } */    
    ?>

<?php //$this->endWidget() ?>
<?php //echo $form->hiddenField($model, 'category_id'); ?>


<!-- </div>
    </div> end tree view  -->

    <div class="row form-group">
        <?php echo $form->labelEx($model,'category_id'); ?>
        <?php echo $form->dropDownList($model, 'category_id', $categories);?>
        <?php echo $form->error($model,'category_id'); ?>
    </div>


 <div class="row form-group">
        <?php echo $form->labelEx($model,'price'); ?>
        <?php echo $form->textField($model,'price',array('value'=>$getPrice,'size'=>50,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'price'); ?>
        <div id="errorPrice" class="errorPrice"></div>
    </div>

<?php if($model->isNewRecord!='1' || !empty($model->filepath)){ ?>
        <div class="row form-group">
   <label for="Reward_title" class="required">Tags <span class="required">*</span></label>
    <input type="text" id="tags" name="tags"  size="50" maxlength="128" value="<?php echo $tableTags; ?>" />
    <span class="required reward-form-error">Add tags with comma seperated values.</span>
</div>
<?php } else { ?>

    <div class="row form-group">
   <label for="Reward_title" class="required">Tags <!-- <span class="required">*</span>--></label>
    <input type="text" id="tag" name="tag"  size="50" maxlength="128" />
    <span class="required reward-form-error	">Add tags with comma seperated values.</span>
</div>

<?php } ?>
    
    <div class="row form-group">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>
    


<!-- http://www.fyneworks.com/jquery/multiple-file-upload/ 
http://www.bsourcecode.com/2013/03/yii-cmultifileupload/
-->
<!-- <input type="file" class="multi" name="images" accept="gif|jpg"/> -->
<?php //echo $form->hiddenField($model, 'image'); ?>


<!-- show all images in the existing table -->
<?php if($model->isNewRecord!='1' || !empty($model->filepath)){ 
        if($model->id!=''){
            echo $allImages = Reward::model()->showAllImagesWithDelIcon($model->id);
        }

 }  
                   
        $this->widget('CMultiFileUpload', array(
                        'name' => 'images',
                        //'required'=>'required',
                        'max'=>6,
                        'accept' => 'jpeg|jpg|gif', // useful for verifying files
                        'duplicate' => 'Duplicate file!', // useful, i think
                        'denied' => 'Invalid file type', // useful, i think
                    ));
        
 echo $form->error($model,'image'); ?>
 <div style="color:red;">
 <?php echo Yii::app()->user->getFlash('imageErrMsg'); ?>
 </div>   







<?php

 /* $this->widget('CMultiFileUpload', array(
     'model'=>$model,
     'attribute'=>'files',
     'name'=>'yimages',
     'accept'=>'jpg|gif',
     'options'=>array(
        'onFileSelect'=>'function(e, v, m){ alert("onFileSelect - "+v) }',
        'afterFileSelect'=>'function(e, v, m){ alert("afterFileSelect - "+v) }',
        'onFileAppend'=>'function(e, v, m){ alert("onFileAppend - "+v) }',
        'afterFileAppend'=>'function(e, v, m){ alert("afterFileAppend - "+v) }',
        'onFileRemove'=>'function(e, v, m){ alert("onFileRemove - "+v) }',
        'afterFileRemove'=>'function(e, v, m){ alert("afterFileRemove - "+v) }',
     ),
  )); */
?>






    <div class="row form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->


</div></div>
</div></div></div>
<style>
.errorTitle, .errorPrice, .errorForm{
  color:red;
}
</style>

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
                    $('#'+Div_id).html('');
                    alert('Image deleted successfully.');
                });
            } else {
            return false;
        }

        }
$('#Reward_title').keyup(function() {
  $('#errorTitle').html('');
  $('#errorForm').html('');
  $('#dupTitle').html('');

});
  
$('#Reward_price').keyup(function() {
  $('#errorPrice').html('');
  $('#errorPrice').html('');
});    

  $('#reward-form').on('submit', function() {
        var title=document.getElementById('Reward_title').value;
        var price=document.getElementById('Reward_price').value;
        var regexp = /^[0-9]+([.][0-9]+)?$/g;
        var result = regexp.test(price);
       // alert(result);

        ///^\d+(\.\d+)?$/
        if(title=='' && price=='')
        {
          document.getElementById('errorForm').innerHTML='Please enter form values.';
          document.getElementById('Reward_title').focus();
          return false;
        }     
        else if(title=='')
        {
          document.getElementById('errorTitle').innerHTML='Please enter title.';
          document.getElementById('Reward_title').focus();
          return false;
        }       
         else if(price=='')
        {
          document.getElementById('errorPrice').innerHTML='Please enter price.';
          document.getElementById('Reward_price').focus();
          return false;
          
        }else if(result == false) {
              document.getElementById('errorPrice').innerHTML='Please enter correct price.';
              document.getElementById('Reward_price').focus();
              return false;
      }else{
            return true;
        }

  });  




</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.MultiFile.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jQuery.validate.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.autocomplete.css" />
<!-- <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script> -->
<!-- <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.autocomplete.js"></script>-->
<script>
/*
$(document).ready(function(){
 $("#tag").autocomplete("autocomplete.php", {
    selectFirst: true
  });
});*/
</script>








