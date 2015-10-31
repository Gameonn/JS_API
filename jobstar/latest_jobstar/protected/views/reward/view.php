
<div id="maincontainer">
  <section id="product">
    <div class="container">      
   
      <!-- Product Details-->
   <?php   $this->breadcrumbs=array(
  'Reward'=>array('admin'),
  'Manage',
); ?>
      <div class="row">
       <!-- Left Image-->
        <div class="span5">
          <ul class="thumbnails mainimage">
           

           <?php echo $model->showAllImagesWithZoom($model->id); ?>
            <!-- 
            <li class="span5">
              <a  rel="position: 'inside' , showTitle: false, adjustX:-4, adjustY:-4" class="thumbnail cloud-zoom" href="img/product1big.jpg">
                <img src="img/product1big.jpg" alt="" title="">
              </a>
            </li>
            <li class="span5">
              <a  rel="position: 'inside' , showTitle: false, adjustX:-4, adjustY:-4" class="thumbnail cloud-zoom" href="img/product2big.jpg">
                <img  src="img/product2big.jpg" alt="" title="">
              </a>
            </li>
            <li class="span5">
              <a  rel="position: 'inside' , showTitle: false, adjustX:-4, adjustY:-4" class="thumbnail cloud-zoom" href="img/product1big.jpg">
                <img src="img/product1big.jpg" alt="" title="">
              </a>
            </li>
            <li class="span5">
              <a  rel="position: 'inside' , showTitle: false, adjustX:-4, adjustY:-4" class="thumbnail cloud-zoom" href="img/product2big.jpg">
                <img  src="img/product2big.jpg" alt="" title="">
              </a>
            </li>
      -->

          </ul>
          <span>Mouse move on Image to zoom</span>
          <ul class="thumbnails mainimage">
            
            
            <?php echo $model->showAllImagesWithLink($model->id); ?>

           <!--  <li class="producthtumb">
              <a class="thumbnail" >
                <img  src="img/product1.jpg" alt="" title="">
              </a>
            </li>
            <li class="producthtumb">
              <a class="thumbnail" >
                <img  src="img/product2.jpg" alt="" title="">
              </a>
            </li>
            <li class="producthtumb">
              <a class="thumbnail" >
                <img  src="img/product1.jpg" alt="" title="">
              </a>
            </li>
            <li class="producthtumb">
              <a class="thumbnail" >
                <img  src="img/product2.jpg" alt="" title="">
              </a>
            </li> -->


          </ul>
        </div>
         <!-- Right Details-->
        <div class="span7">
          <div class="row">
            <div class="span7">
          <h1 class="heading1"><span class="maintext"><?php echo $model->title; ?></span></h1>
              <div class="productprice">
                <div class="productpageprice">
                  <span class="spiral"></span><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $model->price; ?></div>
                <!-- <div class="productpageoldprice">Old price : $345.00</div> -->
               <br/>
              </div>
              
              <ul class="productpagecart">
                <li><a class="cart" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=shoppingCart/view&id=<?php echo $model->id; ?>">Add to Cart</a>
                </li>
                
              </ul>
              <p> Categories: <?php echo $model->searchCat($model->category_id); ?> &nbsp; Tag: <?php echo $model->searchTag($model->id); ?> </p>
         <!-- Product Description tab & comments-->
         <div class="productdesc">
          <?php if($model->description!='') { ?>
         <h3>Detail Description</h3>
          <p><?php echo $model->description; ?></p>     
          <?php } ?>           
                  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--  Related Products-->
  
  <!-- Popular Brands-->
  
</div>