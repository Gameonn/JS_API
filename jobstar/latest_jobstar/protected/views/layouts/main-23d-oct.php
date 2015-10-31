<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<title>JobStars</title>

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
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" rel="stylesheet" type="text/css"  />
<?php

if($_REQUEST[r] != 'site/login' && $_REQUEST[r] != 'site/contact') {
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<?php } ?>



<?php //Yii::app()->clientScript->registerCoreScript('jquery'); ?>
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
          <a href="index.php" class="logo"><img src="img/logo.png" alt="JobStar" title="JobStar"></a> 
          <div class="pull-right search-right-section">
          <ul class="right-navigation">
          <?php if(Yii::app()->user->id !='') { ?> 
          <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=order/view">My Orders</a>
          </li>
          <?php } ?>
          
          <?php if(!Yii::app()->user->isGuest) { ?> 
           <li>Hello <?php echo Yii::app()->user->name; ?><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=site/logout">Logout</a>
          </li>
          <?php } else { ?>
          <li>Hello Guest<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=site/login">Login</a>
          </li>
          <?php } ?>
          </ul>
            <form class="form-search top-search" method="POST" name="search-form"  action="index.php">
              <input type="text" class="input-medium search-query" placeholder="Search Here…" name="search">
              <!-- <input type="submit" class="searchbtn" value="go" /> -->
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
				array('label'=>'Raffle', 'url'=>array('order/raffle')),
				array('label'=>'GoPremium', 'url'=>array('order/premium')),
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
      <?php 
      if($_REQUEST[r] == '' || $_REQUEST[r] == 'reward/view') {
?>
      <div class="span6 pull-left">
        <ul class="nav topcart pull-right">
          <li class="dropdown hover carticon ">
            <?php 
if(count($_SESSION['items'])>0){  ?>
            <a href="#" class="dropdown-toggle" > Shopping Cart <span class="label label-orange font14"><?php echo count($_SESSION['items']); ?> item(s)</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b class="caret"></b></a>
            <ul class="dropdown-menu topcartopen ">
              <li>
                <table>
                  <tbody>
<?php
              foreach($_SESSION['items'] as $key=>$data){
              $total_price=($data['price']*$data['quantity']);
              $subTotal += $total_price;
              $total_price_final= number_format((float)$total_price, 2, '.', '');

            ?>
                    <tr>
                      <td class="image">
                      <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=reward/view&id=<?php echo $data['id']; ?>">
                      <img title="product" alt="product" src="<?php echo Yii::app()->request->baseUrl; ?>/images/reward/<?php echo $data['id']; ?>/<?php echo $data['image']; ?>" height="50" width="50">

                      </a>
                      </td>
                      <td class="name"><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=reward/view&id=<?php echo $data['id']; ?>"><?php echo $data['title']; ?></a></td>
                      <td class="quantity"><?php echo $data['quantity']; ?></td>
                      <td class="total"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $total_price_final; ?></td>
                    </tr>
               <?php } ?>     
                  </tbody>
                </table>
                <table>
                  <tbody>
                    <?php 
              
              $subTotalFinal = number_format((float)$subTotal, 2, '.', '');
              
             /* $tax = Tax::model()->searchTax();
              $findTaxAmt = ($subTotalFinal*($tax/100));
              $findTaxAmtFinal = number_format((float)$findTaxAmt, 2, '.', '');
              
              $vat = Tax::model()->searchVat();
              $findVatAmt = ($subTotalFinal*($vat/100));
              $findVatAmtFinal =number_format((float)$findVatAmt, 2, '.', '');*/

              $grandTotal = ($subTotalFinal);
              $grandTotalFinal = number_format((float)$grandTotal, 2, '.', '');
            
              $_SESSION['grandTotalFinal'] = $grandTotalFinal;

                    ?>
                    <tr>
                      <td class="textright"><b>Sub-Total:</b></td>
                      <td class="textright"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $subTotalFinal; ?></td>
                    </tr>
                    <!-- 
                    <tr>
                      <td class="textright"><b>Eco Tax (<?php echo $tax; ?>%):</b></td>
                      <td class="textright"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $findTaxAmtFinal; ?></td>
                    </tr>
                    <tr>
                      <td class="textright"><b>VAT (<?php echo $vat; ?>%):</b></td>
                      <td class="textright"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $findVatAmtFinal; ?></td>
                    </tr>
                  -->
                    <tr>
                      <td class="textright"><b>Total:</b></td>
                      <td class="textright"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $grandTotalFinal; ?></td>
                    </tr>
                  </tbody>
                </table>

                <div class="well pull-right buttonwrap">
                  <a class="btn btn-orange" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=shoppingCart/view">View Cart</a>
                <a class="btn btn-orange" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=shoppingCart/checkout">Checkout</a>
                </div>



              </li>
            </ul>

<?php } else{ echo "Shopping cart empty"; } ?>
          </li>
        </ul>
      </div>
  <?php } ?>


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
  <!-- <a id="gotop" href="#">Back to top</a> -->
</footer>
<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php 
//echo "<pre>";
//print_r($_REQUEST);
//$_REQUEST[r] != 'site/contact'
//if($_REQUEST[r] != 'site/login') {
?>

<!--  <script src="js/jquery.js"></script> -->
 
 <script src="js/bootstrap.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/drop_down_menu.js"></script>
<script src="js/application.js"></script>
<script src="js/bootstrap-tooltip.js"></script> 
<!-- <script defer src="js/jquery.fancybox.js"></script>-->
<script defer src="js/jquery.flexslider.js"></script>
<script type="text/javascript" src="js/jquery.tweet.js"></script>
<script  src="js/cloud-zoom.1.0.2.js"></script>
 <script  type="text/javascript" src="js/jquery.validate.js"></script>
<?php if($_REQUEST[r] == '' || $_REQUEST[r] == 'reward/view') { ?>

<script type="text/javascript"  src="js/jquery.carouFredSel-6.1.0-packed.js"></script>
 <script defer src="js/custom.js"></script> 

<?php } ?>

<script type="text/javascript"  src="js/jquery.mousewheel.min.js"></script>
<script type="text/javascript"  src="js/jquery.touchSwipe.min.js"></script>
<script type="text/javascript"  src="js/jquery.ba-throttle-debounce.min.js"></script>
<?php //} ?>
</body>
</html>
