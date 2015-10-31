<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'JobStars',
    //'theme' => 'default',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'application.modules.rights.extensions.*',
        'ext.ECheckBoxTree.*',
        'ext.captchaExtended.*',
    ),
    'behaviors' => array(
        'runEnd' => array(
            'class' => 'application.components.WebApplicationEndBehavior',
        ),
    ),
    'modules' => array(
        'rights' => array(
            'superuserName' => 'Super Admin', // Name of the role with super user privileges.
            'authenticatedName' => 'Authenticated', // Name of the authenticated user role.
            'userIdColumn' => 'id', // Name of the user id column in the database.
            'userNameColumn' => 'first_name', // Name of the user name column in the database.
            'enableBizRule' => true, // Whether to enable authorization item business rules.
            'enableBizRuleData' => false, // Whether to enable data for business rules.
            'displayDescription' => true, // Whether to use item description instead of name.
            'flashSuccessKey' => 'RightsSuccess', // Key to use for setting success flash messages.
            'flashErrorKey' => 'RightsError', // Key to use for setting error flash messages.
            'baseUrl' => '/rights', // Base URL for Rights. Change if module is nested.
            'layout' => 'rights.views.layouts.main', // Layout to use for displaying Rights.
            'appLayout' => 'webroot.themes.admin.views.layouts.main',
            'install' => false, // Whether to install rights.
            'debug' => false,
        ),
    // uncomment the following to enable the Gii tool
   
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'admin',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),

   
    ),

    // application components
    'components' => array(
        'Smtpmail'=>array(
            'class'=>'application.extensions.smtpmail.PHPMailer',
            'Host'=>"mail.code-brew.com",
            'Username'=>'pargat@code-brew.com',
            'Password'=>'core2duo',
            'Mailer'=>'smtp',
            'Port'=>25,
            'SMTPAuth'=>true, 
        ),
        'user' => array(
            'class' => 'RWebUser', // Allows super users access implicitly.
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'authManager' => array(
            'assignmentTable' => 'authassignment',
            'itemTable' => 'authitem',
            'rightsTable' => 'rights',
            'itemChildTable' => 'authitemchild',
            'class' => 'RDbAuthManager',
            'connectionID' => 'db',
            'defaultRoles' => array('Guest'),
        ),
        'clientScript'=>array(
            'packages'=>array(
                'jquery'=>array(
                    'baseUrl'=>'//ajax.googleapis.com/ajax/libs/jquery/1/',
                    'js'=>array('jquery.min.js'),
                )
            ),
        ),
        // uncomment the following to enable URLs in path-format
        
      /*    'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ), */
         
//        'db' => array(
//            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
//        ),
        // uncomment the following to use a MySQL database  //yiiadmin_with_rights
        
       /*  'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=yii',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          ), */
          

 'db'=>array(
      'connectionString' => 'mysql:host=localhost;dbname=codebrew_jobstar',
      //'emulatePrepare' => true,
      'username' => 'root',
      'password' => 'core2duo',
      //'charset' => 'utf8',
    ), 
         
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'ranjita706@gmail.com',
        'adminCurrency' => '&pound;',
    ),
);