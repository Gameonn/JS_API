<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

 <!-- <h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>
View file: /Applications/XAMPP/xamppfiles/htdocs/jobstar/themes/default/views/site/index.php
Layout file: /Applications/XAMPP/xamppfiles/htdocs/jobstar/themes/default/views/layouts/main.php
<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>  -->


<div id="maincontainer">
  <section id="product">
    <div class="container">
     <h1 class="heading1"><span class="maintext"> Rewards</span></h1>
     
      <div class="row">        
        <!-- Sidebar Start-->
        
        <!-- Sidebar End-->
        <!-- Category-->
        <div class="span12">          
          <!-- Category Products-->
          <section id="category">
            <div class="row">
              <div class="span12">
      <?php          
//$datas=$model->search()->getData(); 

//echo $pages111=$model->search()->getPagination(); 

//$count=$model->search()->getTotalItemCount();

        if(isset($_POST['sort']) && $_POST['sort']!='') {
          $sort = $_POST['sort'];
        }else{
          $sort = 'id';
        }
        $setSortTitle = ''; $setSortPrice = ''; $setSortDefault = '';
        if(isset($_POST['sort']) && $_POST['sort'] == 'title') {
          $setSortTitle = 'selected';
        }elseif(isset($_POST['sort']) && $_POST['sort'] == 'price'){
          $setSortPrice = 'selected';
        }else{
          $setSortDefault = 'selected';
        }
        if(isset($_POST['setPerPage']) && $_POST['setPerPage']!='') {
          $setPerPage = $_POST['setPerPage'];
        }else{
          $setPerPage = '10';
        }

       $setPage1 = ''; $setPage2 = ''; $setPage3 = '';$setPage4 = '';$setPage5 = ''; $setPageDefault = '';
        if(isset($_POST['setPerPage']) && $_POST['setPerPage'] == '10') {
          $setPage1 = 'selected';
        }elseif(isset($_POST['setPerPage']) && $_POST['setPerPage'] == '15'){
          $setPage2 = 'selected';
        }elseif(isset($_POST['setPerPage']) && $_POST['setPerPage'] == '20'){
          $setPage3 = 'selected';
        }elseif(isset($_POST['setPerPage']) && $_POST['setPerPage'] == '25'){
          $setPage4 = 'selected';
        }elseif(isset($_POST['setPerPage']) && $_POST['setPerPage'] == '30'){
          $setPage5 = 'selected';
        }else{
          $setPage1 = 'selected';
        }

?>

               <!-- Sorting-->
                <div class="sorting well">
                  <form class=" form-inline pull-left" name="reward-list" method="POST">
                    Sort By :
                    <select name="sort" class="span2" onchange="this.form.submit();">
                      <option value="id" <?php echo $setSortDefault; ?>>Default</option>
                      <option value="title" <?php echo $setSortTitle; ?> >Name</option>
                      <option value="price" <?php echo $setSortPrice; ?> >Price</option>
                      <!-- <option >Rating </option>
                      <option>Color</option> -->
                    </select>
                    &nbsp;&nbsp;
                    Show:
                    <select name="setPerPage" class="span1" onchange="this.form.submit();">
                      <option value="10" <?php echo $setPage1; ?> >10</option>
                      <option value="15" <?php echo $setPage2; ?>>15</option>
                      <option value="20" <?php echo $setPage3; ?>>20</option>
                      <option value="25" <?php echo $setPage4; ?>>25</option>
                      <option value="30" <?php echo $setPage5; ?>>30</option>
                    </select>
                  </form>

                  <div class="btn-group pull-right">
                    <button class="btn" id="list"><i class="icon-th-list"></i>
                    </button>
                    <button class="btn btn-orange" id="grid"><i class="icon-th icon-white"></i></button>
                  </div>
                </div>


               <!-- Category-->
                <section id="categorygrid">
                  
                  <ul class="thumbnails grid">
                   <?php  
  $pages = $setPerPage;
  $sqlCount =  "select count(*) FROM reward where status='1' ";
  $count = Yii::app()->db->createCommand($sqlCount)->queryScalar();

        //echo "SELECT a.*,b.image FROM  reward a 
          //LEFT JOIN rewardimages b ON a.id=b.reward_id WHERE b.defaultimage='1' ORDER BY a.$sort DESC";

        $listReward = Yii::app()->db->createCommand("SELECT a.*,b.image FROM  reward a 
          LEFT JOIN rewardimages b ON a.id=b.reward_id WHERE b.defaultimage='1' AND a.status='1' ORDER BY a.$sort DESC"
          )->queryAll();
        //echo "<pre>";
        //print_r($listReward);
                      foreach($listReward as $data)
{ ?>


                    <li class="span3"> <a class="prdocutname" href="product.html"><?php echo $data['title']; ?></a>
                      <div class="thumbnail">
                        
                        <a href="<?php echo Yii::app()->request->baseUrl; ?>product.html">
                          <img alt="image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/reward/<?php echo $data['id']; ?>/<?php echo $data['image']; ?>"></a>
                        <div class="pricetag">
                          <span class="spiral"></span><a href="<?php echo Yii::app()->request->baseUrl; ?>/shop/index" class="productcart">ADD TO CART</a>
                          <div class="price">
                            <div class="pricenew">$<?php echo $data['price']; ?></div>
                           <!--  <div class="priceold">$5000.00</div> -->
                          </div>
                        </div>
                      </div>
                    </li>

      <?php      } ?>
                  </ul>


                  <ul class="thumbnails list row">
                   


                   <?php foreach($listReward as $data) { ?> 

                    <li>
                      <div class="thumbnail">
                        <div class="row">
                          <div class="span2">
                            <a href="<?php echo Yii::app()->request->baseUrl; ?>/shop/"><img alt="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/reward/<?php echo $data['id']; ?>/<?php echo $data['image']; ?>"></a>
                          </div>
                          <div class="span10"> <a class="prdocutname" href="product.html"><?php echo $data['title']; ?></a>
                            <div class="productdiscrption"> 
                              <?php echo $data['description']; ?> </div>
                            <div class="pricetag">
                              <span class="spiral"></span><a href="shopping-cart.html" class="productcart">ADD TO CART</a>
                              <div class="price">
                                <div class="pricenew">$<?php echo $data['price']; ?></div>
                                <!-- <div class="priceold">$5000.00</div> -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    
                    <?php } ?>




                  </ul>


<?php 
$setPerPage = '2';
if($count>0){
  $perpage=$setPerPage;
  $limit=(isset($_GET['page']) ? $_GET['page'] : 1)*$perpage;
  $start=$limit - $perpage;
  if($start!=0){
    $start=$start+1;
  }
$noofpages=round($count/$perpage);
$lastpage=$noofpages+1;
}

if($count>$perpage){

$nextpage=($_GET['page']<$lastpage) ? $_GET['page'] : ($_GET['page']+1);

$previouspage=($_GET['page']==1)? 1 : ($_GET['page']-1);

  ?>

  <li><a href="<?php echo Yii::app()->request->baseUrl; ?><?php echo $id;?>/<?php echo $previouspage;?>/<?php echo $sotby;?>"><</a></li>

  <?php

  for($i=0;$i<$noofpages;$i++){ 
    $class="";
    if($i+1==$_REQUEST['page']){ $class="active";}else if(empty($_REQUEST['page']) && $i==0){$class="active";}
      ?>
     <li><a class="<?php echo $class;?>" href="<?php echo Yii::app()->request->baseUrl; ?><?php echo $id;?>/<?php echo $i+1;?>/<?php echo $sotby;?>"><?php echo $i+1;?></a></li>
    <?php
  }
  ?>
  <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo $id;?><?php echo $nextpage;?>/<?php echo $sotby;?>">></a></li>
  <?php

}

?>

                  <div class="pagination pull-right">
                    <ul>
                      <li><a href="#">Prev</a>
                      </li>
                      <li class="active">
                        <a href="#">1</a>
                      </li>
                      <li><a href="#">2</a>
                      </li>
                      <li><a href="#">3</a>
                      </li>
                      <li><a href="#">4</a>
                      </li>
                      <li><a href="#">Next</a>
                      </li>
                    </ul>
                  </div>


                </section>

              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </section>
</div>
