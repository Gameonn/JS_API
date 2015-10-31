<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
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
				
					<!-- <span class="captcha-question">2 + 8 = </span><input type="text" name="captcha" value="" class="contact-captcha field-control" size="2">-->
			<?php if(CCaptcha::checkRequirements()): ?>
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php //$this->widget('CCaptcha'); ?>
		<?php $this->widget('CCaptcha', array('captchaAction' => 'site/captcha')); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	<?php endif; ?>	
			</div> 
						<?php echo CHtml::submitButton('Submit',array('class'=>'submit')); ?>

						
		

				<?php $this->endWidget(); ?>






    </div>
  </section>
</div>