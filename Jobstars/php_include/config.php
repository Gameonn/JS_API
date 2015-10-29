<?php
//error_reporting(0);
$servername = $_SERVER['HTTP_HOST'];
$pathimg=$servername."/";
define("ROOT_PATH",$_SERVER['DOCUMENT_ROOT']);
define("UPLOAD_PATH","http://52.19.123.85/");
define("BASE_PATH","http://52.19.123.85/");

define("SERVER_OFFSET","21600");
$DB_HOST = 'localhost';
$DB_DATABASE = 'codebrew_jobstar';
$DB_USER = 'root';
$DB_PASSWORD = 'core2duo';


/*define('SMTP_USER','app@ucreate.co.in');
define('SMTP_EMAIL','app@ucreate.co.in');
define('SMTP_PASSWORD','1jLKTc5AazQwrL4QWKRq');
define('SMTP_NAME','Jobstars');
define('SMTP_HOST','gator3035.hostgator.com');
define('SMTP_PORT','465');*/


define('SMTP_USER','pargat@code-brew.com');
define('SMTP_EMAIL','pargat@code-brew.com');
define('SMTP_PASSWORD','core2duo');
define('SMTP_NAME','Jobstars');
define('SMTP_HOST','mail.code-brew.com');
define('SMTP_PORT','25');
