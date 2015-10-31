
<?php //Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<section class="banner">
<img src="image/slide2.jpg" alt="Banner">
</section>

<div id="maincontainer">
  <section id="contact">
    <div class="container">

     <h1 class="heading1"><span class="maintext"> Contact</span></h1>
     <?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>
<?php endif; ?>
</br>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>						<div class="name-field contact-field">
                       <!--  <input type="text" name="ContactForm[name]" placeholder="Name" class="field-control"> 
					,'required'=>'required'
                   -->
                        <?php //echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('placeholder'=>'Name','class'=>'field-control')); ?>
		<?php echo $form->error($model,'name'); ?>
						</div>
						<div class="email-field contact-field">
								<!-- <input type="email" name="ContactForm[email]" placeholder="Email Address" class="field-control" > -->
						<?php echo $form->textField($model,'email',array('placeholder'=>'Email','class'=>'field-control')); ?>
						<?php echo $form->error($model,'email'); ?>
						</div> <!-- .et_pb_contact_left -->

					
						<div class="message-field">
							
							<!-- <textarea class="field-control" name="ContactForm[body]" placeholder="Message" ></textarea>  array('rows'=>15, 'cols'=>75) -->
							<?php echo $form->textArea($model,'message',array('placeholder'=>'Message','class'=>'field-control')); ?>
						<?php echo $form->error($model,'message'); ?>
						</div>
	<div class="submit-captcha">
      <?php if(CCaptcha::checkRequirements()): ?>
  <div class="row">
    <?php echo $form->labelEx($model,'verifyCode'); ?>
    <div>
    <?php $this->widget('CCaptcha'); ?>
    <?php echo $form->textField($model,'verifyCode'); ?>
    </div>
    <div class="hint">Please enter the letters as they are shown in the image above.
    <br/>Letters are not case-sensitive.</div>
    <?php echo $form->error($model,'verifyCode'); ?>
  </div>
  <?php endif; ?>
				
<!-- <div class="g-recaptcha" data-sitekey="6LeGWA8TAAAAAJ2DBpSXem8Rzyt6gjrjkeZ15t_P"></div> -->


			</div> 
						<?php echo CHtml::submitButton('Submit',array('class'=>'submit')); ?>

		
<!-- https://www.google.com/recaptcha/admin#site/319772806?setup -->
				<?php $this->endWidget(); ?>

</div>



    </div>
  </section>
</div>


<script type="text/javascript">

  /*$(document).on("click",".submit",function(){
  	alert('aaa');
    var element = $(this);
    //var Id = element.attr("id");
    var response = g-recaptcha
    var key = '6LeGWA8TAAAAAJ0BmLkMVQ9-K8MbxFWaQ-ndMrUz';
    var address = 'http://52.19.123.85/index.php?r=site/contact';
    var dataString = 'secret='+ key + '&response=' + Id + '&remoteip=' + address;
        
        //$("#flash"+Id).show();
        //$("#flash"+Id).fadeIn(200).html('<img src="http://localhost/jobstar/images/ajax-loader.gif" align="absmiddle"> loading.....');
          $.ajax({
            type: "POST",
            url: "https://www.google.com/recaptcha/api/siteverify",
            data: dataString,
            success: function(data){
              alert(data);
              //$('#cartTable').html(data);
              
            }
          });
        
  //return false;
  });*/

</script>



