

<?php
/* @var $this DocController */
/* @var $model Doc */

$this->breadcrumbs=array(
    'Order'=>array('admin'),
    $model->name,
);

$this->menu=array(
    array('label'=>'List Order', 'url'=>array('admin')),
    array('label'=>'Delete Order', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this order?')),
    array('label'=>'Manage Order', 'url'=>array('admin')),
);
?>

<h1>View Order Detail #<?php echo $model->id; ?></h1>
          <?php 
               $order_id = $_REQUEST['id'];
                $listReward = Yii::app()->db->createCommand("SELECT a.*, b.title, b.price, c.user_id
                    FROM shop_order_detail a
                    LEFT JOIN reward b ON a.reward_id = b.id 
                    LEFT JOIN shop_order c ON a.order_id = c.id 
                    WHERE a.order_id =  $order_id"
          )->queryAll();
          $z=0;
         if(count($listReward)>0){ 
          // get the product ids
             foreach($listReward as $key=>$data){
                
                if($z == '0') { 
                 $user_id = $data['user_id'];
                  //show billing/shipping
                 $listBillAdd = Yii::app()->db->createCommand("SELECT * FROM  shop_billing_address 
                    WHERE user_id=$user_id"
                    )->queryRow();
                 $listShipAdd = Yii::app()->db->createCommand("SELECT * FROM  shop_shipping_address 
                    WHERE user_id=$user_id"
                    )->queryRow();
                // echo "<pre>";
                // print_r($listBillAdd);
                 //echo "aaaa";
       
       ?>
             <div class="row billing-details-section" id="cartBilling">
             <h4>Billing Details</h4>
                  <form class="form-horizontal" method="post" name="form-billing" id="form-billing">
                <fieldset>
                
                    <div class="control-group">
<!--                      <label class="control-label" >First Name<span class="red">*</span></label>-->
                      <div class="controls">
                        <?php echo $listBillAdd['firstname']; ?>
                        </div>
            
                      <!--<label class="control-label" >Last Name<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listBillAdd['lastname']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                     <!-- <label class="control-label" >E-Mail<span class="red">*</span></label>-->
                      <div class="controls">
                     <?php echo $listBillAdd['email']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                     <!-- <label class="control-label" >Telephone<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listBillAdd['phone']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                 <!--     <label class="control-label" >Address 1<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listBillAdd['address1']; ?>
                      </div>
                    </div>
                    <?php if($listBillAdd['address2']!='') { ?>
                    <div class="control-group">
                   <!--   <label class="control-label" >Address 2</label>-->
                      <div class="controls">
                      <?php echo $listBillAdd['address2']; ?>
                                  </div>
                    </div>
                    <?php } ?>
                    <div class="control-group">
          <!--            <label class="control-label" >City<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listBillAdd['city']; ?>
                        </div>

                    <!--  <label class="control-label" >Post Code<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listBillAdd['zipcode']; ?>
                        </div>
                
                      <!--<label class="control-label" >Country<span class="red">*</span></label>-->
                      <div class="controls">
                       <?php echo $listBillAdd['state']; ?>
                      </div>
             
                  <!--    <label class="control-label" >Region / State<span class="red">*</span></label>-->
                      <div class="controls">
                          <?php echo $listBillAdd['country']; ?>
                        </div>
                    </div>
                </fieldset>
              </form>
            </div>


           <div class="row admin-shipping-details" style="float:right;">
                        <h4>Shipping Details</h4>
                  <form class="form-horizontal" method="post" name="form-shipping" id="form-shipping">
                <fieldset>
                 
                    <div class="control-group">
                    <!--  <label class="control-label" >First Name<span class="red">*</span></label>-->
                      <div class="controls">
                        <?php echo $listShipAdd['firstname']; ?>
                        </div>
             
                   <!--   <label class="control-label" >Last Name<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listShipAdd['lastname']; ?>
                      </div>
                    </div>
                    <div class="control-group">
           <!--           <label class="control-label" >E-Mail<span class="red">*</span></label>-->
                      <div class="controls">
                     <?php echo $listShipAdd['email']; ?>
                      </div>
                    </div>
                    <div class="control-group">
             <!--         <label class="control-label" >Telephone<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listShipAdd['phone']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                     <!-- <label class="control-label" >Address 1<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listShipAdd['address1']; ?>
                      </div>
                    </div>
                    <?php if($listShipAdd['address2']!='') { ?>
                    <div class="control-group">
                     <!-- <label class="control-label" >Address 2</label>-->
                      <div class="controls">
                      <?php echo $listShipAdd['address2']; ?>
                                  </div>
                    </div>
                    <?php } ?>
                    <div class="control-group">
                <!--      <label class="control-label" >City<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listShipAdd['city']; ?>
                        </div>
        
           <!--           <label class="control-label" >Post Code<span class="red">*</span></label>-->
                      <div class="controls">
                      <?php echo $listShipAdd['zipcode']; ?>
                        </div>
             
                     <!-- <label class="control-label" >Country<span class="red">*</span></label>-->
                      <div class="controls">
                       <?php echo $listShipAdd['country']; ?>
                      </div>
              
                     <!-- <label class="control-label" >Region / State<span class="red">*</span></label>-->
                      <div class="controls">
                          <?php echo $listShipAdd['state']; ?>
                        </div>
                    </div>
                </fieldset>
              </form>
            </div>

      <?php }

           $z++;  }
      } 

          ?>

          <div id="cartTable"> <!-- created by developer for ajaxs -->  
        
            <div class="cart-info">
              <table class="table table-striped table-bordered">
                <tr>
                  <th class="image">Image</th>
                  <th class="name">Product Name</th>
                  <th class="quantity">Quantity</th>
                  <th class="price">Unit Price</th>
                  <th class="total">Total</th>
                </tr>

                <?php 
                $order_id = $_REQUEST['id'];

             
             $listReward = Yii::app()->db->createCommand("SELECT a.*, b.title, b.price
                    FROM shop_order_detail a
                    LEFT JOIN reward b ON a.reward_id = b.id 
                    WHERE a.order_id =  $order_id  ORDER BY a.id"
          )->queryAll();
         if(count($listReward)>0){ 
          // get the product ids
             foreach($listReward as $key=>$data){


                //find out image
                $rewardId = $data['reward_id'];
                $setImage = '';
                $getDefaultImage = Yii::app()->db->createCommand("SELECT image FROM rewardimages 
                    WHERE reward_id =  $rewardId  AND defaultImage = '1'")->queryRow();
                //echo $getDefaultImage['image']."<br>";
              $setImage = $getDefaultImage['image'];
              $total_price=($data['price']*$data['quantity']);
              $subTotal += $total_price;
              $total_price_final= number_format((float)$total_price, 2, '.', '');

            ?>
                <tr>
            <td class="image">
                <img title="product" alt="product" src="<?php echo Yii::app()->request->baseUrl; ?>/images/reward/<?php echo $data['reward_id']; ?>/<?php echo $setImage; ?>" height="50" width="50">
            </td>
            <td  class="name">
                <?php echo $data['title']; ?>
                </td>
            <td class="quantity">
             <?php echo $data['quantity']; ?>
             </td> 
             <td class="price"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $data['price']; ?></td>
            <td class="total"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo  $total_price_final; ?></td>
             
          </tr>
                <?php } } 

          else { ?>
                    <tr><td colspan="5" ><p align="center">Record not found.</p></td></tr>
                  <?php } ?>
              </table>
            </div>
  
  <?php if(count($listReward)>0){ 

              $subTotalFinal = number_format((float)$subTotal, 2, '.', '');
              
              /*$tax = $taxModel->searchTax();
              $findTaxAmt = ($subTotalFinal*($tax/100));
              $findTaxAmtFinal = number_format((float)$findTaxAmt, 2, '.', '');
              
              $vat = $taxModel->searchVat();
              $findVatAmt = ($subTotalFinal*($vat/100));
              $findVatAmtFinal =number_format((float)$findVatAmt, 2, '.', '');*/

              $grandTotal = ($subTotalFinal);
              $grandTotalFinal = number_format((float)$grandTotal, 2, '.', '');
  ?>

            <div class="row">
              <div class="pull-right">
                <div class="span4 pull-right order-detail-total">
                  <table class="table table-striped table-bordered ">
                    

                    <tbody>
                     <tr>
                      <td><span class="extra bold">Sub-Total :</span></td>
                      <td><span class="bold"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $subTotalFinal; ?></span></td>
                    </tr>
                    <!-- 
                    <tr>
                      <td><span class="extra bold">Eco Tax (<?php echo $taxModel->searchTax(); ?>%) :</span></td>
                      <td><span class="bold"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $findTaxAmtFinal; ?></span></td>
                    </tr>
                    <tr>
                      <td><span class="extra bold">VAT (<?php echo $taxModel->searchVat(); ?>%) :</span></td>
                      <td><span class="bold"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $findVatAmtFinal; ?></span></td>
                    </tr>
                  -->
                    <tr>
                      <td><span class="extra bold totalamout">Total :</span></td>
                      <td><span class="bold totalamout"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $grandTotalFinal; ?></span></td>
                    </tr>
                    </tbody>
                  </table>



                  
           

                  
                </div>
              </div>
            </div>
        <?php } ?>    
          </div> <!-- created by developer for ajaxs -->

