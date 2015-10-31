<div id="maincontainer">
  <section id="product">
    <div class="container" id="cartTable">
     <!--  breadcrumb --> 
            
      <h1 class="heading1"><span class="maintext"> Shopping Cart</span><span class="subtext"> All items in your  Shopping Cart</span></h1>
      <!-- Cart-->
      <div id='AjFlash' class="flash-success" style="display:none"></div>

      <div class="success successDoc">
    <?php echo Yii::app()->user->getFlash('showMsg'); ?>
    </div>
      <div class="cart-info" >
        <table class="table table-striped table-bordered" >
          <tr>
            <th class="image">Image</th>
            <th class="name">Product Name</th>
            <th class="quantity">Qty</th>
          
            <th class="price">Unit Price</th>
            <th class="total">Total</th>
           
          </tr>
          <?php 
         if(count($_SESSION['items'])>0){ 
          // get the product ids
            $ids = "";

           // echo "<pre>";
            //print_r($_SESSION['items']);
           
           /* foreach($_SESSION['cart_items'] as $id=>$value){
                $ids = $ids . $id . ",";
            }
            // remove the last comma
            $ids = rtrim($ids, ',');

            //$query = "SELECT id, title, price FROM rewad WHERE id IN ({$ids}) ORDER BY title";
             $listReward = Yii::app()->db->createCommand("SELECT a.*,b.image FROM  reward a 
          LEFT JOIN rewardimages b ON a.id=b.reward_id WHERE a.id IN ({$ids}) AND b.defaultimage='1' AND a.status='1' ORDER BY title DESC"
          )->queryAll();
             //echo "<pre>";
             //print_r($listReward); */
             
             foreach($_SESSION['items'] as $key=>$data){
              //echo $data['price']*$data['quantity'];
              $total_price=($data['price']*$data['quantity']);
              $subTotal += $total_price;
              $total_price_final= number_format((float)$total_price, 2, '.', '');

            ?>
          <tr>
            <td class="image"><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=reward/view&id=<?php echo $data['id']; ?>">
              <img title="product" alt="product" src="<?php echo Yii::app()->request->baseUrl; ?>/images/reward/<?php echo $data['id']; ?>/<?php echo $data['image']; ?>" height="50" width="50">
            </a>
            </td>
            <td  class="name"><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=reward/view&id=<?php echo $data['id']; ?>"><?php echo $data['title']; ?></a></td>
            <td class="quantity">
              <div id="flash<?php echo $data['id']; ?>"></div>
              <input type="text" size="1" value="<?php echo $data['quantity']; ?>" name="quantity<?php echo $data['id']; ?>" id="quantity<?php echo $data['id']; ?>" class="span1"><br/>
              <input type="submit" value="Update" class="quantity_submit" id="<?php echo $data['id']; ?>">
               <!--<a href="#"><small>Update</small></a>--> | <!-- <a href="#"><small>Delete</small></a> -->
               <input type="submit" value="Delete" class="delete_submit" id="<?php echo $data['id']; ?>">

              <?php 
                Yii::app()->clientScript->registerScript('amount_'.$position,"
          $('.amount_".$position."').keyup(function() {
            $.ajax({
             // url:'".$this->createUrl('/shoppingCart/updateAmount')."',
              data: $('#amount_".$position."'),
              success: function(result) {
              //$('.amount_".$position."').css('background-color', 'lightgreen');
              //$('.widget_amount_".$position."').css('background-color', 'lightgreen');
              //$('.widget_amount_".$position."').html($('.amount_".$position."').val());
              //$('.price_".$position."').html(result); 
              //$('.price_total').load('".$this->createUrl('//shop/shoppingCart/getPriceTotal')."');
              },
              error: function() {
              //$('#amount_".$position."').css('background-color', 'red');
              },

              });
        });
          ");
          ?>


              
             
             </td> <td class="price"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $data['price']; ?></td>
            <td class="total"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo  $total_price_final; ?></td>
             
          </tr>
          
          
          <?php } } //}

          else { ?>
                    <tr><td colspan="5" ><p align="center">Shopping cart is empty.</p></td></tr>
                  <?php } ?>
             

        </table>
      </div>

<?php if(count($_SESSION['items'])>0){ 

              $subTotalFinal = number_format((float)$subTotal, 2, '.', '');
              
              /*$tax = $taxModel->searchTax();
              $findTaxAmt = ($subTotalFinal*($tax/100));
              $findTaxAmtFinal = number_format((float)$findTaxAmt, 2, '.', '');
              
              $vat = $taxModel->searchVat();
              $findVatAmt = ($subTotalFinal*($vat/100));
              $findVatAmtFinal =number_format((float)$findVatAmt, 2, '.', '');*/

              $grandTotal = ($subTotalFinal);
              $grandTotalFinal = number_format((float)$grandTotal, 2, '.', '');
            
              $_SESSION['grandTotalFinal'] = $grandTotalFinal;
  ?>
      <div class="container">
      <div class="pull-right">
          <div class="span4 pull-right">
            <table class="table table-striped table-bordered ">
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
            </table>


            <input type="submit" onclick="window.location.href='index.php?r=shoppingCart/checkout'" value="Checkout" class="btn btn-orange pull-right" data-toggle="modal" data-target="#myModal">
            

            <input type="submit" onclick="window.location.href='index.php'" value="Continue Shopping" class="btn btn-orange pull-right mr10">
          </div>
        </div>
        </div>
<?php } ?>

    </div>
  </section>
</div>



<script type="text/javascript">
$(function() {
  $(document).on("click",".quantity_submit",function(){
    var element = $(this);
    var Id = element.attr("id");
    var pageId = 'checkout';
    var quantityVal = $("#quantity"+Id).val();
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
              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
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