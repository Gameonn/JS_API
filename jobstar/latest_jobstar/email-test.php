<?php 
//error_reporting(0);
//api/DataClass.php

/*
$foo = mail('ranjita706@gmail.com', 'testing emails', 'message');
if ( $foo == false )
{
echo "no mail server";
}
					$to = 'ranjita706@gmail.com';
					$subject = 'Mail From JobStars Website:Contact Form '.ucwords($name);
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					// More headers
					$headers .= 'From: <'.$to.'>' . "\r\n";
					$headers .= 'Cc: ranjita706@gmail.com' . "\r\n";
					echo $chk = mail($to, $subject, $setText, $headers);
//$to      = 'nobody@example.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: ranjita@ucreate.co.in' . "\r\n" .
    'Reply-To: ranjita@ucreate.co.in' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

//mail($to, $subject, $message, $headers);
if(@mail($to, $subject, $message, $headers))
{
  echo "Mail Sent Successfully";
}else{
  echo "Mail Not Sent";
}
*/
require_once('PHPMailer_5.2.4/class.phpmailer.php');


define('SMTP_USER','pargat@code-brew.com');
define('SMTP_EMAIL','pargat@code-brew.com');
define('SMTP_PASSWORD','core2duo');
define('SMTP_NAME','Jobstars');
define('SMTP_HOST','mail.code-brew.com');
define('SMTP_PORT','25');


$to = 'ranjita706@gmail.com';
 echo $chk = sendEmail($to,"Jobstars -  Testing",
						"<div style='font-size:16x;line-height:1.4;'>
							<p>Dear Admin,</p>
							<br>
							<p>I am ranjita dhiman from ucreate testing email functionality.</p><br>
							<p> ------------------------------------------------------------------------ </p>
							</div>"
					,SMTP_EMAIL);

 function sendEmail($email,$subjectMail,$bodyMail,$email_back){

	$mail = new PHPMailer(true); 
	$mail->IsSMTP(); // telling the class to use SMTP
	try {
	  //$mail->Host       = SMTP_HOST; // SMTP server
	  $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
	  $mail->SMTPAuth   = true;                  // enable SMTP authentication
	  //$mail->SMTPSecure = 'ssl';
	  $mail->Host       = SMTP_HOST; // sets the SMTP server
	  $mail->Port       = SMTP_PORT;                    // set the SMTP port for the GMAIL server
	  $mail->Username   = SMTP_USER; // SMTP account username
	  $mail->Password   = SMTP_PASSWORD;        // SMTP account password
	  $mail->AddAddress($email, '');     // SMTP account password
	  $mail->SetFrom(SMTP_EMAIL, SMTP_NAME);
	  $mail->AddReplyTo($email_back, SMTP_NAME);
	  $mail->Subject = $subjectMail;
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';  // optional - MsgHTML will create an alternate automaticall//y
	  $mail->MsgHTML($bodyMail) ;
	  if(!$mail->Send()){
			$success='0';
			$msg="Error in sending mail";
	  }else{
			$success='1';
	  }
	} catch (phpmailerException $e) {
	  $msg=$e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  $msg=$e->getMessage(); //Boring error messages from anything else!
	}
	
	//echo $msg;
}

die('hello');

?>