
<div id="maincontainer">
  <section id="checkout">
    <div class="container">
   
      
      <div class="row">        
        <!-- Account Login-->
        <div class="span12">
          <h1 class="heading1"><span class="maintext">Checkout</span><span class="subtext"> Checkout</span></h1>
          <h4 style="color:red;align:center;"><?php echo Yii::app()->user->getFlash('showMsg'); ?></h4>
          <h3>Billing Details</h3>
           <div class="success successDoc">
            <?php echo Yii::app()->user->getFlash('showMsg'); ?>
            </div>

            <div class="row" id="cartBilling" class="cartBilling">
              <form class="form-horizontal" method="post" name="form-billing" id="form-billing">
                <fieldset>
                  <div class="span12">
                    <div class="control-group">
                      <label class="control-label" >First Name<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listBillAdd['firstname'];?>" name="firstname"  id="firstname" required>
                        <span class="error">This field is required</span>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Last Name<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listBillAdd['lastname'];?>" name="lastname"  id="lastname" required>
                        <span class="error">This field is required</span>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >E-Mail<span class="red">*</span></label>
                      <div class="controls">
                        <input type="email" class=""  value="<?php echo $listBillAdd['email'];?>" name="email"  id="email" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Telephone<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text"  pattern="[0-9]{10}" class=""  value="<?php echo $listBillAdd['phone'];?>" name="phone"  id="phone" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 1<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listBillAdd['address1'];?>" name="address1"  id="address1" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 2</label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listBillAdd['address2'];?>" name="address2"  id="address2">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >City<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listBillAdd['city'];?>" name="city"  id="city" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Post Code<span class="red">*</span></label>
                      <div class="controls">
                        <input type="number" class=""  value="<?php echo $listBillAdd['zipcode'];?>" name="zipcode"  id="zipcode" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Country<span class="red">*</span></label>
                      <div class="controls">
                        <!-- <select >
                          <option>Please Select</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select> -->
                        <input type="text" class=""  value="<?php echo $listBillAdd['country'];?>" name="country"  id="country" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Region / State<span class="red">*</span></label>
                      <div class="controls">
                       <!--  <select >
                          <option>Please Select</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select> -->
                        <input type="text" class=""  value="<?php echo $listBillAdd['state'];?>" name="state"  id="state" required>
                      </div>
                    </div>
                    <div class="control-group check-row" id="showCheckbox">
                     <label class="control-label" >Shipping Address</label>
                      <div class="controls"> <span class="checkpanel">
                        <input name="chkshipping" id="chkshipping" type="checkbox" value="1" > Use Same as Billing address</span></div>
                      
                    </div>
                  </div>
                  <div id="showBillSubmitBut">
                  <input type="submit" value="Continue" class="billing_submit btn btn-orange pull-right" id="<?php echo Yii::app()->user->id; ?>">
                  </div>
                </fieldset>
              </form>
            </div>



                        <div class="row" id="cartShipping" class="cartShipping">
              <form class="form-horizontal" method="post" name="form-shipping" id="form-shipping">
                <fieldset>
                  <div class="span12">
                    <div class="control-group">
                      <label class="control-label" >First Name<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listShipAdd['firstname'];?>" name="firstname"  id="firstname" required>
                        <span class="error">This field is required</span>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Last Name<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listShipAdd['lastname'];?>" name="lastname"  id="lastname" required>
                        <span class="error">This field is required</span>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >E-Mail<span class="red">*</span></label>
                      <div class="controls">
                        <input type="email" class=""  value="<?php echo $listShipAdd['email'];?>" name="email"  id="email" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Telephone<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text"  pattern="[0-9]{10}" class=""  value="<?php echo $listShipAdd['phone'];?>" name="phone"  id="phone" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 1<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listShipAdd['address1'];?>" name="address1"  id="address1" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 2</label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listShipAdd['address2'];?>" name="address2"  id="address2">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >City<span class="red">*</span></label>
                      <div class="controls">
                        <input type="text" class=""  value="<?php echo $listShipAdd['city'];?>" name="city"  id="city" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Post Code<span class="red">*</span></label>
                      <div class="controls">
                        <input type="number" class=""  value="<?php echo $listShipAdd['zipcode'];?>" name="zipcode"  id="zipcode" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Country<span class="red">*</span></label>
                      <div class="controls">
                        <!-- <select >
                          <option>Please Select</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select> -->
                        <input type="text" class=""  value="<?php echo $listShipAdd['country'];?>" name="country"  id="country" required>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Region / State<span class="red">*</span></label>
                      <div class="controls">
                       <!--  <select >
                          <option>Please Select</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select> -->
                        <input type="text" class=""  value="<?php echo $listShipAdd['state'];?>" name="state"  id="state" required>
                      </div>
                    </div>
                  </div>
                  <input type="submit" value="Continue" class="shipping_submit btn btn-orange pull-right" id="<?php echo Yii::app()->user->id; ?>">
                </fieldset>
              </form>
            </div>



            
          
 
          <div id="cartTable"> <!-- created by developer for ajaxs -->  
          <h3>Confirm Order</h3>
        
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
         if(count($_SESSION['items'])>0){ 
          // get the product ids
             foreach($_SESSION['items'] as $key=>$data){
              //echo $data['price']*$data['quantity'];
              $total_price=($data['price']*$data['quantity']);
              $subTotal += $total_price;
              $total_price_final= number_format((float)$total_price, 2, '.', '');

            ?>
                <tr>
            <td class="image"><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=reward/view&id=<?php echo $data['id']; ?>"><img title="product" alt="product" src="<?php echo Yii::app()->request->baseUrl; ?>/images/reward/<?php echo $data['id']; ?>/<?php echo $data['image']; ?>" height="50" width="50"></a></td>
            <td  class="name"><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=reward/view&id=<?php echo $data['id']; ?>"><?php echo $data['title']; ?></a></td>
            <td class="quantity">
              <div id="flash<?php echo $data['id']; ?>"></div>
              <input type="text" size="1" value="<?php echo $data['quantity']; ?>" name="quantity<?php echo $data['id']; ?>" id="quantity<?php echo $data['id']; ?>" class="span1"><br/>
              <input type="submit" value="Update" class="quantity_submit" id="<?php echo $data['id']; ?>">
               <!--<a href="#"><small>Update</small></a>--> | <!-- <a href="#"><small>Delete</small></a> -->
               <input type="submit" value="Delete" class="delete_submit" id="<?php echo $data['id']; ?>">
             </td> <td class="price"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $data['price']; ?></td>
            <td class="total"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo  $total_price_final; ?></td>
             
          </tr>
                <?php } } 

          else { ?>
                    <tr><td colspan="5" ><p align="center">Shopping cart is empty.</p></td></tr>
                  <?php } ?>
              </table>
            </div>
  
  <?php if(count($_SESSION['items'])>0){ 

              $subTotalFinal = number_format((float)$subTotal, 2, '.', '');
              
              //$tax = $taxModel->searchTax();
              //$findTaxAmt = ($subTotalFinal*($tax/100));
              //$findTaxAmtFinal = number_format((float)$findTaxAmt, 2, '.', '');
              
              //$vat = $taxModel->searchVat();
              //$findVatAmt = ($subTotalFinal*($vat/100));
              //$findVatAmtFinal =number_format((float)$findVatAmt, 2, '.', '');

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
                      <td><span class="bold">$<?php echo $subTotalFinal; ?></span></td>
                    </tr>
                   <!--  
                    <tr>
                      <td><span class="extra bold">Eco Tax (<?php echo $taxModel->searchTax(); ?>%) :</span></td>
                      <td><span class="bold"><?php //echo Yii::app()->params['adminCurrency']; ?><?php //echo $findTaxAmtFinal; ?></span></td>
                    </tr>
                    <tr>
                      <td><span class="extra bold">VAT (<?php echo $taxModel->searchVat(); ?>%) :</span></td>
                      <td><span class="bold"><?php //echo Yii::app()->params['adminCurrency']; ?><?php //echo $findVatAmtFinal; ?></span></td>
                    </tr>
                    -->

                    <tr>
                      <td><span class="extra bold totalamout">Total :</span></td>
                      <td><span class="bold totalamout"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $grandTotalFinal; ?></span></td>
                    </tr>
                    </tbody>
                  </table>


            <?php 
            if(Yii::app()->user->id!='') {
              $user_id = Yii::app()->user->id;
              $chkBillExist = Billing::model()->find('user_id = :user_id', array(':user_id' => $user_id));
                if(empty($chkBillExist))
                {
                  //dont show the place order button, flash msg to add billing address

                } else { ?>

        <?php  } }
                  ?>
                  
           
           <input type="submit"  onclick="window.location.href='index.php?r=shoppingCart/thank'"  value="Place Order" class="btn btn-orange pull-right">

            <input type="submit" onclick="window.location.href='index.php'" value="Continue Shopping" class="btn btn-orange pull-right mr10">
                  
                </div>
              </div>
            </div>
        <?php } ?>    
          </div> <!-- created by developer for ajaxs -->

         
        </div>        
        <!-- Sidebar Start-->
        
        <!-- Sidebar End-->
      </div>
    </div>
  </section>
</div>

<?php 
        
Yii::app()->clientScript->registerScript('1','2');


 ?>
<style type="text/css">


.error{
  display: none;
  margin-left: 0px;
}   

.error_show{
  color: red;
  margin-left: 10px;
}
input.invalid, textarea.invalid{
  border: 2px solid red;
}

input.valid, textarea.valid{
  border: 2px solid green;
}


</style>
<script type="text/javascript">
jQuery(function() {

      $('#chkshipping').on('click', function () {
        if (!$(this).is(':checked')) {
            jQuery("#cartShipping").show();
            //$('#in1, #in2').val('');
            //alert('111');
       }else{
            //alert('2222'); empty the shipping form
            jQuery("#cartShipping").hide();
       }
    });


      jQuery("#form-billing").validate({
        rules: {
      firstname: {
        required: true
      },
      lastname: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      address1: {
        required: true
      },
      city: {
        required: true
      },
      zipcode: {
        required: true
      },
      

    },
    messages: {   
      firstname: "Please enter firstname", 
      lastname: "Please enter lastname",         
      email: "Please enter valid email",
      phone: "Please enter phone",
      city: "Please enter city",
      zipcode: "Please enter zipcode",

    },
    submitHandler : function () {
            jQuery.ajax({
                type : "POST",
                url : "index.php?r=shoppingcart/billing",
                data : jQuery('#form-billing').serialize(),
                success : function (data){
                  //alert(data);
                  //jQuery(".info").html(data);
                  //jQuery(".cartBilling").html(data);
                  jQuery('#cartBilling').html(data);
                  //jQuery(".info").fadeIn(3000);
          
        }
            });
        }
    });

    jQuery("#form-shipping").validate({
        rules: {
      firstname: {
        required: true
      },
      lastname: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      address1: {
        required: true
      },
      city: {
        required: true
      },
      zipcode: {
        required: true
      },
      

    },
    messages: {   
      firstname: "Please enter firstname", 
      lastname: "Please enter lastname",         
      email: "Please enter valid email",
      phone: "Please enter phone",
      city: "Please enter city",
      zipcode: "Please enter zipcode",

    },
    submitHandler : function () {
            jQuery.ajax({
                type : "POST",
                url : "index.php?r=shoppingcart/shipping",
                data : jQuery('#form-shipping').serialize(),
                success : function (data){
                  //alert(data);
                  jQuery('#cartShipping').html(data);
        }
            });
        }
    });
  



  $(document).on("click",".quantity_submit",function(){
    var element = $(this);
    var Id = element.attr("id");
    var quantityVal = $("#quantity"+Id).val();
    var pageId = 'checkout';
    var dataString = 'quantity='+ quantityVal + '&id=' + Id + '&pagechk=' + pageId;
      if(quantityVal=='')
        {
          alert("Please enter quantity");
        }
        else
        {
        //$("#flash"+Id).show();
        //$("#flash"+Id).fadeIn(200).html('<img src="http://localhost/jobstar/images/ajax-loader.gif" align="absmiddle"> loading.....');
          $.ajax({
            type: "POST",
            url: "index.php?r=shoppingCart/update",
            data: dataString,
            success: function(data){
              //alert(data);
              $('#cartTable').html(data);
              
            }
          });
        }
  //return false;
  });

    $(document).on("click",".delete_submit",function(){
    var element = $(this);
    var Id = element.attr("id");
    var pageId = 'checkout';
    var dataString = 'id=' + Id + '&pagechk=' + pageId;
      var chk = confirm('Are you sure you want to delete this cart product.?');
      if(chk == true) {
        //$("#flash"+Id).show();
        //$("#flash"+Id).fadeIn(200).html('<img src="http://localhost/jobstar/images/ajax-loader.gif" align="absmiddle"> loading.....');
          $.ajax({
            type: "POST",
            url: "index.php?r=shoppingCart/delete",
            data: dataString,
            success: function(data){
              //alert(html);
              $('#cartTable').html(data);
              
            }
          });
        }else{
          return false;
        } 
  //return false;
  });
  



});
</script>