<?php
//error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(E_ALL);
//$yii = dirname(__FILE__).'/yii-1.1.14/yii.php';
//$config = dirname(__FILE__).'/protected/config/front.php';
  
 $yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

  
// Remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
  
require_once($yii);
//Yii::createWebApplication($config)->runEnd('front');
Yii::createWebApplication($config)->run();
