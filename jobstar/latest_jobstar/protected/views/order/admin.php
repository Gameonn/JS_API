<div id="maincontainer">
  <section id="product">
    <div class="container" id="cartTable">
     <!--  breadcrumb --> 

           <div class="row">        
        <!-- Account Login-->
        <div class="span12">
         
          
         
           <div class="success successDoc">
            <?php echo Yii::app()->user->getFlash('showMsg'); ?>
            </div>

            <div class="billingdetails" id="cartBilling">
             <h1 class="heading1"><span class="maintext"> Billing Details</span>
        <!-- <span class="subtext"> All items in your  Shopping Cart</span> -->
      </h1>
          
                  <form class="form-horizontal" method="post" name="form-billing" id="form-billing">
                <fieldset>
                
                    <div class="control-group">
                      <label class="control-label" >First Name<span class="red">*</span></label>
                      <div class="controls">
                        <?php echo $listBillAdd['firstname']; ?>
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Last Name<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listBillAdd['lastname']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >E-Mail<span class="red">*</span></label>
                      <div class="controls">
                     <?php echo $listBillAdd['email']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Telephone<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listBillAdd['phone']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 1<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listBillAdd['address1']; ?>
                      </div>
                    </div>
                    <?php if($listBillAdd['address2']!='') { ?>
                    <div class="control-group">
                      <label class="control-label" >Address 2</label>
                      <div class="controls">
                      <?php echo $listBillAdd['address2']; ?>
                                  </div>
                    </div>
                    <?php } ?>
                    <div class="control-group">
                      <label class="control-label" >City<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listBillAdd['city']; ?>
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Post Code<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listBillAdd['zipcode']; ?>
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Country<span class="red">*</span></label>
                      <div class="controls">
                       <?php echo $listBillAdd['country']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Region / State<span class="red">*</span></label>
                      <div class="controls">
                          <?php echo $listBillAdd['state']; ?>
                        </div>
                    </div>
                  
                </fieldset>
              </form>
            </div>
                        <div class="shippingdetails">
                         <h1 class="heading1"><span class="maintext"> Shipping Details</span>
        <!-- <span class="subtext"> All items in your  Shopping Cart</span> -->
      </h1>
                       
                  <form class="form-horizontal" method="post" name="form-shipping" id="form-shipping">
                <fieldset>
                 
                    <div class="control-group">
                      <label class="control-label" >First Name<span class="red">*</span></label>
                      <div class="controls">
                        <?php echo $listShipAdd['firstname']; ?>
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Last Name<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listShipAdd['lastname']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >E-Mail<span class="red">*</span></label>
                      <div class="controls">
                     <?php echo $listShipAdd['email']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Telephone<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listShipAdd['phone']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 1<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listShipAdd['address1']; ?>
                      </div>
                    </div>
                    <?php if($listShipAdd['address2']!='') { ?>
                    <div class="control-group">
                      <label class="control-label" >Address 2</label>
                      <div class="controls">
                      <?php echo $listShipAdd['address2']; ?>
                                  </div>
                    </div>
                    <?php } ?>
                    <div class="control-group">
                      <label class="control-label" >City<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listShipAdd['city']; ?>
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Post Code<span class="red">*</span></label>
                      <div class="controls">
                      <?php echo $listShipAdd['zipcode']; ?>
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Country<span class="red">*</span></label>
                      <div class="controls">
                       <?php echo $listShipAdd['country']; ?>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Region / State<span class="red">*</span></label>
                      <div class="controls">
                          <?php echo $listShipAdd['state']; ?>
                        </div>
                    </div>
                    
                </fieldset>
              </form>
            </div>
            
      <h1 class="heading1"><span class="maintext"> View Order Detail</span>
        <!-- <span class="subtext"> All items in your  Shopping Cart</span> -->
      </h1>
      <!-- Cart-->
      <div class="success successDoc">
    <?php echo Yii::app()->user->getFlash('showMsg'); ?>
    </div>
        
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
                //echo "<pre>";
                //print_r($listBillAdd);
                //echo "<pre>";
                //print_r($listShipAdd);
                $order_id = $_REQUEST['id'];

             
                $listReward = Yii::app()->db->createCommand("SELECT a.*, b.title, b.price FROM shop_order_detail a
                    LEFT JOIN reward b ON a.reward_id = b.id 
                    WHERE a.order_id =  $order_id  ORDER BY a.id"
          )->queryAll();
         if(count($listReward)>0){  
          // get the product ids
             foreach($listReward as $key=>$data){
                //find out image
                $rewardId = $data['reward_id'];
                //$setImage = '';
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
             
             /* $tax = $taxModel->searchTax();
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
                <div class="span4 pull-right">
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
              </div>
            </div>
        <?php } ?>    
          </div> <!-- created by developer for ajaxs -->



