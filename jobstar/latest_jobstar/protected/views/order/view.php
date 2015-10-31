<div id="maincontainer">
  <section id="product">
    <div class="container">
     <!--  breadcrumb --> 
            
      <h1 class="heading1"><span class="maintext"> My Orders</span></h1>
      <!-- Cart-->
      <div class="cart-info">
        <table class="table table-striped table-bordered">
          <tr>
            <th class="name">Order Id</th>
            <th class="name">Products</th>
            <th class="name">Date</th>
            <th class="name">Status</th>
            <th class="total">Total</th>
          </tr>

          <?php 
          if(Yii::app()->user->id!='') {
          $userId = Yii::app()->user->id;
         // echo "SELECT * FROM  shop_order WHERE user_id=$userId AND ordering_done=1 ORDER BY id DESC";
          $listOrders = Yii::app()->db->createCommand("SELECT * FROM  shop_order WHERE user_id=$userId AND ordering_done='1' ORDER BY id DESC"  
          )->queryAll();
          //echo "<pre>";
          //print_r($listOrders);
          
          if(count($listOrders)>0){ 

          foreach ($listOrders as $key => $value) {
            $order_id = $value['id'];
            //get all reward title name:- means product purchased by user from order detail table
          $listAllRewardIds = Yii::app()->db->createCommand("SELECT reward_id FROM  shop_order_detail WHERE order_id=$order_id"  
          )->queryAll();
          //echo "<pre>";
         // print_r($listAllRewardIds);
          //collect all reward ids in variable
            foreach($listAllRewardIds as $rewId=>$RewValue){
                //echo $RewValue['reward_id'];
                $rewIds = $rewIds . $RewValue['reward_id'] . ",";
            }
            // remove the last comma
            $rewIds = rtrim($rewIds, ',');
            //extract reward name:-
            $getAllRewardName = Yii::app()->db->createCommand("SELECT title FROM  reward WHERE id IN ({$rewIds})"  
          )->queryAll();
            //echo "<pre>";
            //print_r($getAllRewardName);
            foreach($getAllRewardName as $rewName=>$RewNameValue){
                $rewName1 = $rewName1 . $RewNameValue['title'] . ",";
            }
            // remove the last comma
            $rewName1 = rtrim($rewName1, ',');
            $productdate = strtotime($value['ordering_date']);
          ?>
          <tr>
            <td  class="name">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=order/admin&id=<?php echo $value['id']; ?>">
              #<?php echo $value['id']; ?></a></td>
             <td  class="name">
                <?php echo $rewName1; ?>
             </td>
             <td  class="name"><?php echo date("j F Y", $productdate); ?></td>
             <td  class="name">
              <?php 
                if($value['ordering_confirmed'] == '1'){
                    echo "Confirmed";
                }else{
                    echo "Pending";
                }
              ?>
             </td>
            <td class="total"><?php echo Yii::app()->params['adminCurrency']; ?><?php echo $value['grandtotal']; ?></td>
          </tr>

          <?php } } else { 
                  echo "<tr><td colspan='5'><span align='center'>No record found.</span></td></tr>"; 
                }


        } ?>
         
        </table>
      </div>
      
      
    </div>
  </section>
</div>