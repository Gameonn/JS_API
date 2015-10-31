<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Job Star</title>
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="">
<meta name="author" content="">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'><link href="css/bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/flexslider.css" type="text/css" media="screen" rel="stylesheet"  />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/cloud-zoom.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/collapse_menu.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.css" rel="stylesheet"><!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!-- fav -->
<link rel="shortcut icon" href="assets/ico/favicon.html">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>
<!-- Header Start -->
<header>
  <div class="headerstrip">
    <div class="container">
    
      <div class="row">
        <div class="span12">
          <a href="index.html" class="logo"><img src="img/logo.png" alt="JobStar" title="JobStar"></a> 
          <div class="pull-right search-right-section">
          <ul class="right-navigation">
           <li><a href="my-orders.html">My Orders</a>
          </li>
           <li>Hello User<a href="#">Logout</a>
          </li>
          </ul>
            <form class="form-search top-search">
              <input type="text" class="input-medium search-query" placeholder="Search Here…">
            </form>
          </div>
          <!-- Top Nav Start -->
          <div class="pull-left top-navigation">
        <nav id="nav" role="navigation">
          <div class="topnav"> <a href="#" id="pull"> <span></span> <span></span> <span></span> </a>
             <ul class="main-navigation menubox">
             
             <!-- <li><a  href="index.html">Home</a>
          </li>
          <li><a class="active" href="index.html">Rewards</a>
          </li>
          <li><a  href="raffle.html">Raffle</a>
          </li>
          <li><a href="gopremium.html">GoPremium</a>
          </li>
          
          <li><a href="contact.html">Contact</a>
          </li> -->

          <?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/')),
				array('label'=>'Rewards', 'url'=>array('/')),
				array('label'=>'Raffle', 'url'=>array('/')),
				array('label'=>'GoPremium', 'url'=>array('/')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				//array('label'=>'Login', 'url'=>Yii::app()->request->baseUrl.'/backend.php?r=site/login', 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>      
           

            </ul>
          </div>
        </nav>
          </div>
          
          <!-- Top Nav End -->
         
        </div>
      </div>
    </div>
  </div>


  <div class="container">
   <div class="headerdetails">
    <div class="row">
    <div class="span6 pull-left">
    
    
    </div>
      <div class="span6 pull-left">
        <ul class="nav topcart pull-right">
          <li class="dropdown hover carticon ">
            <a href="#" class="dropdown-toggle" > Shopping Cart <span class="label label-orange font14">1 item(s)</span> - $589.50 <b class="caret"></b></a>
            <ul class="dropdown-menu topcartopen ">
              <li>
                <table>
                  <tbody>
                    <tr>
                      <td class="image"><a href="product.html"><img width="50" height="50" src="img/prodcut-40x40.jpg" alt="product" title="product"></a></td>
                      <td class="name"><a href="product.html">MacBook</a></td>
                      <td class="quantity">x&nbsp;1</td>
                      <td class="total">$589.50</td>
                    </tr>
                    <tr>
                      <td class="image"><a href="product.html"><img width="50" height="50" src="img/prodcut-40x40.jpg" alt="product" title="product"></a></td>
                      <td class="name"><a href="product.html">MacBook</a></td>
                      <td class="quantity">x&nbsp;1</td>
                      <td class="total">$589.50</td>
                    </tr>
                  </tbody>
                </table>
                <table>
                  <tbody>
                    <tr>
                      <td class="textright"><b>Sub-Total:</b></td>
                      <td class="textright">$500.00</td>
                    </tr>
                    <tr>
                      <td class="textright"><b>Eco Tax (-2.00):</b></td>
                      <td class="textright">$2.00</td>
                    </tr>
                    <tr>
                      <td class="textright"><b>VAT (17.5%):</b></td>
                      <td class="textright">$87.50</td>
                    </tr>
                    <tr>
                      <td class="textright"><b>Total:</b></td>
                      <td class="textright">$589.50</td>
                    </tr>
                  </tbody>
                </table>

                <div class="well pull-right buttonwrap">
                  <a class="btn btn-orange" href="shopping-cart.html">View Cart</a>
                <a class="btn btn-orange" href="checkout.html ">Checkout</a>
                </div>



              </li>
            </ul>
          </li>
        </ul>
      </div>
      </div>
    </div>
    
  </div>
</header>
<!-- Header End -->

<?php echo $content; ?>

	<div class="clear"></div>

<!-- Footer -->
<footer id="footer">

  <section class="copyrightbottom">
    <div class="container">
      <div class="row">
        <div class="span6 copyright">
Copyright 2015 JobStar.com | All Rights Reserved.
 </div>
        <div class="span6 textright"> <div id="footersocial">
        <a href="#" title="Facebook" class="facebook"></a>
        <a href="#" title="Twitter" class="twitter"></a> <a href="http://gaddmasterproductions.com/feed" title="rss" class="rss" target="_blank"></a>
        <a href="#" title="Googleplus" class="googleplus"></a>
      </div></div>
      </div>
    </div>
  </section>
  <a id="gotop" href="#">Back to top</a>
</footer>
<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/drop_down_menu.js"></script>
<script src="js/application.js"></script>
<script src="js/bootstrap-tooltip.js"></script>
<script defer src="js/jquery.fancybox.js"></script>
<script defer src="js/jquery.flexslider.js"></script>
<script type="text/javascript" src="js/jquery.tweet.js"></script>
<script  src="js/cloud-zoom.1.0.2.js"></script>
<script  type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript"  src="js/jquery.carouFredSel-6.1.0-packed.js"></script>
<script type="text/javascript"  src="js/jquery.mousewheel.min.js"></script>
<script type="text/javascript"  src="js/jquery.touchSwipe.min.js"></script>
<script type="text/javascript"  src="js/jquery.ba-throttle-debounce.min.js"></script>
<script defer src="js/custom.js"></script>
</body>
</html>
