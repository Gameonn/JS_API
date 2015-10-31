  <?php

class ShoppingCartController extends Controller
{
		 public $layout='/layouts/main';

	
		 	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','update','delete'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','update','delete','checkout','billing','shipping','thank'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionView($id='')
	{
		
		$model = new Reward();
		$taxModel = new Tax();
		$cart = '';
		//create cart session:- //https://www.codeofaninja.com/2013/04/shopping-cart-in-php.html
		$id = isset($_GET['id']) ? $_GET['id'] : "";
		//get All values from DB
		  if($id!='') {
			  $listReward = Yii::app()->db->createCommand("SELECT a.*,b.image FROM  reward a 
	          LEFT JOIN rewardimages b ON a.id=b.reward_id WHERE a.id IN ({$id}) AND b.defaultimage='1' AND a.status='1'"
	          )->queryRow();

			//echo "<pre>";
			//print_r($listReward);
			$quantity = '1';
			$name = $listReward['title'];
			$price = $listReward['price'];
			$img = $listReward['image'];
		}
		/*
		 * check if the 'cart' session array was created
		 * if it is NOT, create the 'cart' session array
		 */
		if(!isset($_SESSION['items'])){
			//echo "1111";
		     $_SESSION['items'] = array();
		}
		// check if the item is in the array, if it is, do not add
		if(array_key_exists($id, $_SESSION['items'])){
		    // redirect to product list and tell the user it was added to cart
		    Yii::app()->user->setFlash('msg','Product has already added.');
		    //$this->redirect('index.php');
		}
		else{ // else, add the item to the array
			//echo "333";
			if($id!='') {
		    	//$_SESSION['cart_items'][$id]=$quantity;
		    	$_SESSION['items'][$id] = array('id'=>$id,'quantity' => $quantity, 'title' => $name, 'price' => $price,'image' => $img);
			}
		 
		    // redirect to product list and tell the user it was added to cart
		   // Yii::app()->user->setFlash('showMsg','Product has added successfully.');

		}
		$this->render('view',array(
						'model'=>$model,'taxModel'=>$taxModel
						));
	}


	public function actionUpdate() {

		$taxModel = new Tax();
		$id = $_POST['id'];//doc id
    $chkPage = $_POST['pagechk'];
		$quantity = $_POST['quantity'];//doc id
    $getCurrency = Yii::app()->params['adminCurrency'];
    $output = '';
    //check the existing quantity from db, if that quanityt is avalibale which is posted by user
    $getQuantity = "SELECT instock from reward where id = $id";    
    $getQtyValueDB = Yii::app()->db->createCommand($getQuantity)->queryRow();
    if($getQtyValueDB['instock'] < $quantity) {
        $output .= '<div id="AjFlash" class="flash-success" style="color:red">You cannot order more than instock.</div>';
    }

              		if(array_key_exists($id, $_SESSION['items'])){
              			//update the quantity
              			$_SESSION['items'][$id]['quantity']=$quantity;
              		}
              		else{
              			if($id!='') {
              				//add
              		    	//$_SESSION['cart_quantity'][$id]=$quantity;
              		    	$_SESSION['items'][$id]['quantity']=$quantity;
              			}
              		}
                   if($chkPage == 'checkout') {
                        $output .= '<h3>Confirm Order</h3>
                      
                          <div class="cart-info">';
                  }else{
                        $output .= '<h1 class="heading1"><span class="maintext"> Shopping Cart</span><span class="subtext"> All items in your  Shopping Cart</span></h1>
                    
                        <div class="cart-info" >';
                  }

              $output .= '
                     <table class="table table-striped table-bordered" >
                        <tr>
                          <th class="image">Image</th>
                          <th class="name">Product Name</th>
                          <th class="quantity">Qty</th>
                        
                          <th class="price">Unit Price</th>
                          <th class="total">Total</th>
                         
                        </tr>';
                    if(count($_SESSION['items'])>0){ 
                        // get the product ids
                          $ids = "";
                           foreach($_SESSION['items'] as $key=>$data){
                            $price = $data['price'];
                            $id = $data['id'];
                            $title = $data['title'];
                            $img = $data['image'];
                            $quantity= $data['quantity'];
                              $total_price=($data['price']*$data['quantity']);
                              $subTotal += $total_price;
                              $total_price_final= number_format((float)$total_price, 2, '.', '');

                      $output .= '<tr>
                          <td class="image"><a href="'.Yii::app()->request->baseUrl.'/index.php?r=reward/view&id='.$id.'"><img title="product" alt="product" src="'.Yii::app()->request->baseUrl.'/images/reward/'.$id.'/'.$img.'" height="50" width="50"></a></td>
                          <td  class="name"><a href="'.Yii::app()->request->baseUrl.'/index.php?r=reward/view&id='.$id.'">'.$title.'</a></td>
                          <td class="quantity">
                            <div id="flash'.$id.'"></div>
                            <input type="text" size="1" value="'.$quantity.'" name="quantity'.$id.'" id="quantity'.$id.'" class="span1"><br/>
                            <input type="submit" value="Update" class="quantity_submit" id="'.$id.'"> | <input type="submit" value="Delete" class="delete_submit" id="'.$id.'">
                            
                           
                           </td> <td class="price">'.$getCurrency.''.$price.'</td>
                           <td class="total">'.$getCurrency.''.$total_price_final.'</td>
                        </tr>';
                        } } 

                        else {
                            $output .= '<tr><td colspan="5" ><p align="center">Shopping cart is empty.</p></td></tr>';
                        }
                       $output .= '</table>';
                       $output .= '</div>';

               if(count($_SESSION['items'])>0){ 

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
               
                     if($chkPage == 'checkout') {
                          $output .= '<div class="row">';
                    }else{
                          $output .= '<div class="container">';
                    }
                    $output .= '<div class="pull-right">
                        <div class="span4 pull-right">
                          <table class="table table-striped table-bordered ">
                            <tr>
                              <td><span class="extra bold">Sub-Total :</span></td>
                              <td><span class="bold">'.$getCurrency.''.$subTotalFinal.'</span></td>
                            </tr>
                           
                            <!-- <tr>
                              <td><span class="extra bold">Eco Tax ('.$taxModel->searchTax().'%) :</span></td>
                              <td><span class="bold">'.$getCurrency.''.$findTaxAmtFinal.'</span></td>
                            </tr>
                            <tr>
                              <td><span class="extra bold">VAT ('.$taxModel->searchVat().'%) :</span></td>
                              <td><span class="bold">'.$getCurrency.''.$findVatAmtFinal.'</span></td>
                            </tr> -->

                            <tr>
                              <td><span class="extra bold totalamout">Total :</span></td>
                              <td><span class="bold totalamout">'.$getCurrency.''.$grandTotalFinal.'</span></td>
                            </tr>
                          </table>';
                          if($chkPage == 'checkout') {
                            $output .= ' <input type="submit"  onclick="window.location.href=\'index.php?r=shoppingCart/thank\'"  value="Place Order" class="btn btn-orange pull-right">';
                         }else{
                            $output .= '            <input type="submit" onclick="window.location.href=\'index.php?r=shoppingCart/checkout\'" value="Checkout" class="btn btn-orange pull-right" data-toggle="modal" data-target="#myModal">
              ';
                         }

                         $output .= ' <input type="submit"  value="Continue Shopping" class="btn btn-orange pull-right mr10">
                        </div>
                      </div>
                      </div>';
               } 
                        echo $output;



	}
		public function actionDelete() {

		$taxModel = new Tax();
    $id = $_POST['id'];//doc id
    $chkPage = $_POST['pagechk'];
		//unset the id from cart session
		unset($_SESSION['items'][$id]);
		$getCurrency = Yii::app()->params['adminCurrency'];
    if($chkPage == 'checkout') {
          $output = '<h3>Confirm Order</h3>
        
            <div class="cart-info">';
    }else{
          $output = '<h1 class="heading1"><span class="maintext"> Shopping Cart</span><span class="subtext"> All items in your  Shopping Cart</span></h1>
      
          <div class="cart-info" >';
    }

$output .= '

		   <table class="table table-striped table-bordered" >
          <tr>
            <th class="image">Image</th>
            <th class="name">Product Name</th>
            <th class="quantity">Qty</th>
          
            <th class="price">Unit Price</th>
            <th class="total">Total</th>
           
          </tr>';
			if(count($_SESSION['items'])>0){ 
          // get the product ids
            $ids = "";
             foreach($_SESSION['items'] as $key=>$data){
             	$price = $data['price'];
             	$id = $data['id'];
             	$title = $data['title'];
             	$img = $data['image'];
             	$quantity= $data['quantity'];
                $total_price=($data['price']*$data['quantity']);
              	$subTotal += $total_price;
              	$total_price_final= number_format((float)$total_price, 2, '.', '');

		    $output .= '<tr>
            <td class="image"><a href="'.Yii::app()->request->baseUrl.'/index.php?r=reward/view&id='.$id.'"><img title="product" alt="product" src="'.Yii::app()->request->baseUrl.'/images/reward/'.$id.'/'.$img.'" height="50" width="50"></a></td>
            <td  class="name"><a href="'.Yii::app()->request->baseUrl.'/index.php?r=reward/view&id='.$id.'">'.$title.'</a></td>
            <td class="quantity">
              <div id="flash'.$id.'"></div>
              <input type="text" size="1" value="'.$quantity.'" name="quantity'.$id.'" id="quantity'.$id.'" class="span1"><br/>
              <input type="submit" value="Update" class="quantity_submit" id="'.$id.'"> | <input type="submit" value="Delete" class="delete_submit" id="'.$id.'">
              
             
             </td> <td class="price">'.$getCurrency.''.$price.'</td>
             <td class="total">'.$getCurrency.''.$total_price_final.'</td>
          </tr>';
          } } 

          else {
          		$output .= '<tr><td colspan="5" ><p align="center">Shopping cart is empty.</p></td></tr>';
          }
         $output .= '</table>';
         $output .= '</div>';

 if(count($_SESSION['items'])>0){ 

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
 
       if($chkPage == 'checkout') {
            $output .= '<div class="row">';
      }else{
            $output .= '<div class="container">';
      }

      $output .= '<div class="pull-right">
          <div class="span4 pull-right">
            <table class="table table-striped table-bordered ">
              <tr>
                <td><span class="extra bold">Sub-Total :</span></td>
                <td><span class="bold">'.$getCurrency.''.$subTotalFinal.'</span></td>
              </tr>

              <tr>
                <td><span class="extra bold totalamout">Total :</span></td>
                <td><span class="bold totalamout">'.$getCurrency.''.$grandTotalFinal.'</span></td>
              </tr>
            </table>';
            if($chkPage == 'checkout') {
              $output .= ' <input type="submit"  onclick="window.location.href=\'index.php?r=shoppingCart/thank\'"  value="Place Order" class="btn btn-orange pull-right">';
           }else{
              $output .= '<input type="submit"  onclick="window.location.href=\'index.php?r=shoppingCart/thank\'"  value="Place Order" class="btn btn-orange pull-right">';
           }

           $output .= ' <input type="submit"  value="Continue Shopping" class="btn btn-orange pull-right mr10">
          </div>
        </div>
        </div>';
 } 
          echo $output;


	}






	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=ShoppingCart::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='shopping cart-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


  public function actionCheckout(){
        $model = new Reward();
        $taxModel = new Tax();
        $billingModel = new Billing();
        $listBillAdd = '';
        $user_id = Yii::app()->user->id;
      if($user_id!='') {
         $listBillAdd = Yii::app()->db->createCommand("SELECT * FROM  shop_billing_address 
            WHERE user_id=$user_id"
            )->queryRow();

         $listShipAdd = Yii::app()->db->createCommand("SELECT * FROM  shop_shipping_address 
            WHERE user_id=$user_id"
            )->queryRow();

       }
        $this->render('checkout',array(
            'model'=>$model,'taxModel'=>$taxModel,'listBillAdd'=>$listBillAdd,'listShipAdd'=>$listShipAdd
            ));


  }

    public function actionBilling(){
        $modelB = new Billing();
        $modelS = new Shipping();
        $user_id = Yii::app()->user->id;
        if(isset($_POST) && $_POST['firstname']!='' && $_POST['lastname']!='' && $_POST['email']!='' && $_POST['phone']!='' && $_POST['address1']!='' && $_POST['city']!='' && $_POST['state']!='' && $_POST['country']!='') {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address1 = $_POST['address1'];
            $address2 = $_POST['address2'];
            $city = $_POST['city'];
            $zipcode = $_POST['zipcode'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            //check the record exist in DB
                $chkBillExist = Billing::model()->find('user_id = :user_id', array(':user_id' => $user_id));
                if(empty($chkBillExist))
                {
                      $insertBilling=Yii::app()->db->createCommand()->insert('shop_billing_address',array(
                                'user_id'=>$user_id,
                                'firstname'=>$_POST['firstname'],
                                'lastname'=>$_POST['lastname'],
                                 'email'=>$_POST['email'],
                                 'phone'=>$_POST['phone'],
                                 'address1'=>$_POST['address1'],
                                 'address2'=>$_POST['address2'],
                                 'city'=>$_POST['city'],
                                 'state'=>$_POST['state'],
                                 'zipcode'=>$_POST['zipcode'],
                                 'country'=>$_POST['country'],
                                 )); 
                          $last_bill_id = Yii::app()->db->getLastInsertID();
                 }else{
                      //update query
                      $updateBill = Yii::app()->db->createCommand()->update('shop_billing_address',
                          array(
                                'firstname'=>$_POST['firstname'],
                                'lastname'=>$_POST['lastname'],
                                 'email'=>$_POST['email'],
                                 'phone'=>$_POST['phone'],
                                 'address1'=>$_POST['address1'],
                                 'address2'=>$_POST['address2'],
                                 'city'=>$_POST['city'],
                                 'state'=>$_POST['state'],
                                 'zipcode'=>$_POST['zipcode'],
                                 'country'=>$_POST['country'],
                            ),
                          'user_id='.$user_id);
                 }
                 if($_POST['chkshipping'] == '1'){
                        $chkShipExist = Shipping::model()->find('user_id = :user_id', array(':user_id' => $user_id));
                          if(empty($chkShipExist))
                          {
                              //insert the same data in shipping
                               $insertShipping=Yii::app()->db->createCommand()->insert('shop_shipping_address',array(
                                'user_id'=>$user_id,
                                'firstname'=>$_POST['firstname'],
                                'lastname'=>$_POST['lastname'],
                                 'email'=>$_POST['email'],
                                 'phone'=>$_POST['phone'],
                                 'address1'=>$_POST['address1'],
                                 'address2'=>$_POST['address2'],
                                 'city'=>$_POST['city'],
                                 'state'=>$_POST['state'],
                                 'zipcode'=>$_POST['zipcode'],
                                 'country'=>$_POST['country'],
                                 )); 
                               $last_ship_id = Yii::app()->db->getLastInsertID();
                          }else{
                                $updateShip = Yii::app()->db->createCommand()->update('shop_shipping_address',
                                array(
                                'firstname'=>$_POST['firstname'],
                                'lastname'=>$_POST['lastname'],
                                 'email'=>$_POST['email'],
                                 'phone'=>$_POST['phone'],
                                 'address1'=>$_POST['address1'],
                                 'address2'=>$_POST['address2'],
                                 'city'=>$_POST['city'],
                                 'state'=>$_POST['state'],
                                 'zipcode'=>$_POST['zipcode'],
                                 'country'=>$_POST['country'],
                                ),
                                'user_id='.$user_id);

                          }
                        
                  } //end ship address
               //echo "Address saved successfully.";
                echo '              <form class="form-horizontal" method="post" name="form-billing" id="form-billing">
                <fieldset>
                  <div class="span12">
                    <div class="control-group">
                      <label class="control-label" >First Name<span class="red">*</span></label>
                      <div class="controls">
                        '.$firstname.'
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Last Name<span class="red">*</span></label>
                      <div class="controls">
                      '.$lastname.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >E-Mail<span class="red">*</span></label>
                      <div class="controls">
                      '.$email.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Telephone<span class="red">*</span></label>
                      <div class="controls">
                      '.$phone.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 1<span class="red">*</span></label>
                      <div class="controls">
                      '.$address1.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 2</label>
                      <div class="controls">
                      '.$address2.'
                                  </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >City<span class="red">*</span></label>
                      <div class="controls">
                      '.$city.'
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Post Code<span class="red">*</span></label>
                      <div class="controls">
                      '.$zipcode.'
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Country<span class="red">*</span></label>
                      <div class="controls">
                       '.$country.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Region / State<span class="red">*</span></label>
                      <div class="controls">
                          '.$state.'
                        </div>
                    </div>
                    <div class="control-group check-row">
                     <label class="control-label" >Shipping Address</label>
                      <div class="controls"> <span class="checkpanel">
                          : Same as billing address                      
                    </div>
                  </div>
                </fieldset>
              </form>';
          } else{
              echo "Please fill the form properly.";
          }


        


  }
      public function actionShipping(){
        $modelS = new Shipping();
        $user_id = Yii::app()->user->id;
        if(isset($_POST) && $_POST['firstname']!='' && $_POST['lastname']!='' && $_POST['email']!='' && $_POST['phone']!='' && $_POST['address1']!='' && $_POST['city']!='' && $_POST['state']!='' && $_POST['country']!='') {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address1 = $_POST['address1'];
            $address2 = $_POST['address2'];
            $city = $_POST['city'];
            $zipcode = $_POST['zipcode'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            //check the record exist in DB

                        $chkShipExist = Shipping::model()->find('user_id = :user_id', array(':user_id' => $user_id));
                          if(empty($chkShipExist))
                          {
                              //insert the same data in shipping
                               $insertShipping=Yii::app()->db->createCommand()->insert('shop_shipping_address',array(
                                'user_id'=>$user_id,
                                'firstname'=>$_POST['firstname'],
                                'lastname'=>$_POST['lastname'],
                                 'email'=>$_POST['email'],
                                 'phone'=>$_POST['phone'],
                                 'address1'=>$_POST['address1'],
                                 'address2'=>$_POST['address2'],
                                 'city'=>$_POST['city'],
                                 'state'=>$_POST['state'],
                                 'zipcode'=>$_POST['zipcode'],
                                 'country'=>$_POST['country'],
                                 )); 
                               $last_ship_id = Yii::app()->db->getLastInsertID();
                          }else{
                                $updateShip = Yii::app()->db->createCommand()->update('shop_shipping_address',
                                array(
                                'firstname'=>$_POST['firstname'],
                                'lastname'=>$_POST['lastname'],
                                 'email'=>$_POST['email'],
                                 'phone'=>$_POST['phone'],
                                 'address1'=>$_POST['address1'],
                                 'address2'=>$_POST['address2'],
                                 'city'=>$_POST['city'],
                                 'state'=>$_POST['state'],
                                 'zipcode'=>$_POST['zipcode'],
                                 'country'=>$_POST['country'],
                                ),
                                'user_id='.$user_id);

                          }
                        
               //echo "Address saved successfully.";
                echo '              <form class="form-horizontal" method="post" name="form-billing" id="form-billing">
                <fieldset>
                  <div class="span12">
                    <div class="control-group">
                      <label class="control-label" >First Name<span class="red">*</span></label>
                      <div class="controls">
                        '.$firstname.'
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Last Name<span class="red">*</span></label>
                      <div class="controls">
                      '.$lastname.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >E-Mail<span class="red">*</span></label>
                      <div class="controls">
                      '.$email.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Telephone<span class="red">*</span></label>
                      <div class="controls">
                      '.$phone.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 1<span class="red">*</span></label>
                      <div class="controls">
                      '.$address1.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Address 2</label>
                      <div class="controls">
                      '.$address2.'
                                  </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >City<span class="red">*</span></label>
                      <div class="controls">
                      '.$city.'
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Post Code<span class="red">*</span></label>
                      <div class="controls">
                      '.$zipcode.'
                        </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Country<span class="red">*</span></label>
                      <div class="controls">
                       '.$country.'
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" >Region / State<span class="red">*</span></label>
                      <div class="controls">
                          '.$state.'
                        </div>
                    </div>
                    
                </fieldset>
              </form>';
          } else{
              echo "Please fill the shipping form properly.";
          }
  }

  public function actionThank(){

         $user_id = Yii::app()->user->id;
         //check the billing / shipping values coresponding to this user
          $chkBillExist = Billing::model()->find('user_id = :user_id', array(':user_id' => $user_id));
          //echo "<pre>";
          //print_r($chkBillExist);
          if(empty($chkBillExist))
          {
            //redirect back to checkout page

            Yii::app()->user->setFlash('showMsg','Your billing details are empty.');
            $this->redirect(array('shoppingCart/checkout')); 
          }
          $chkShipExist = Shipping::model()->find('user_id = :user_id', array(':user_id' => $user_id));
              if(empty($chkShipExist))
                {
                  Yii::app()->user->setFlash('showMsg','Please fill your shipping details.');
                  $this->redirect(array('shoppingCart/checkout')); 
                }
        //end

          $model = new Reward();
          $orderModel = new Order();
          
          //check the session exist

          //check billing/ shipping session exist

          //finally placed the order
          //insert the cart in order table
          if(count($_SESSION['items'])>0){ 
            //get billing/shipping ids of login user
            $chkBillExist = Billing::model()->find('user_id = :user_id', array(':user_id' => $user_id));
                if(empty($chkBillExist))
                {
                }else{
                  $billAddId = $chkBillExist['id'];
                }
            //get shipping ids of login user
            $chkShipExist = Shipping::model()->find('user_id = :user_id', array(':user_id' => $user_id));
                if(empty($chkShipExist))
                {
                }else{
                  $shipAddId = $chkShipExist['id'];
                }    

             $insertOrders = Yii::app()->db->createCommand()->insert('shop_order',array(
              'user_id'=>$user_id,
              'shipping_address_id'=>$billAddId,
               'billing_address_id'=>$shipAddId,
               'ordering_done'=>1,
               'grandtotal'=>$_SESSION['grandTotalFinal'],
               )); 
              $order_id = Yii::app()->db->getLastInsertID();
             
             foreach($_SESSION['items'] as $key=>$data){
               //insert into order detail table
                $insertOrders = Yii::app()->db->createCommand()->insert('shop_order_detail',array(
              'order_id'=>$order_id,
              'reward_id'=>$data['id'],
              'quantity'=>$data['quantity'],
               )); 
                $productRewardId = $data['id']; 
                //get the existing quantity from db and add the posted quanity in it
                $getQuantity = "SELECT instock from reward where id = $productRewardId";    
                $getQtyValue = Yii::app()->db->createCommand($getQuantity)->queryRow();

                $finalQuantity = ($getQtyValue['instock']- $data['quantity']);
                 //update the quantity of each product shop by user 
                $getResult = Yii::app()->db->createCommand()->update('reward',array('instock'=>$finalQuantity),'id='.$data['id']);


             }
              //send email to admin and user
             
             $this->sendEmailToAll($order_id,$user_id);

             //end
             if(isset($_SESSION['items'])){
              unset($_SESSION['items']);
              unset($_SESSION['grandTotalFinal']);
            }
           }else{
                Yii::app()->user->setFlash('showMsg','Shopping cart is empty.');
                $this->redirect(array('shoppingCart/view')); 
           }
          //echo  Yii::app()->db->lastInsertID;
          //echo "----";
           //echo $last_id = Yii::app()->db->getLastInsertID();

          $this->render('thank',array(
            'model'=>$orderModel
            ));

  }


    public function sendEmailToAll($order_id,$user_id) {

          
          $userName = Yii::app()->user->name;
          $getCurrency = Yii::app()->params['adminCurrency'];
          $message = '<h5>User: '.$userName.'</h5><br/><h3>Order Detail Summary</h3> <div class="cart-info">';
          $message .= '

       <table class="table table-striped table-bordered" >
          <tr>
            <th class="image">Image</th>
            <th class="name">Product Name</th>
            <th class="quantity">Qty</th>
            <th class="price">Unit Price</th>
            <th class="total">Total</th>
          </tr>';

      $listOrders = Yii::app()->db->createCommand("SELECT a.*, b.title, b.price, c.user_id
                    FROM shop_order_detail a
                    LEFT JOIN reward b ON a.reward_id = b.id 
                    LEFT JOIN shop_order c ON a.order_id = c.id 
                    WHERE a.order_id =  $order_id"
          )->queryAll();

      if(count($listOrders)>0){ 
             foreach($listOrders as $key=>$data){
              $price = $data['price'];
              $id = $data['id'];
              $title = $data['title'];
              //$img = $data['image'];
              //find out image
                $rewardId = $data['reward_id'];
                $setImage = '';
                $getDefaultImage = Yii::app()->db->createCommand("SELECT image FROM rewardimages 
                    WHERE reward_id =  $rewardId  AND defaultImage = '1'")->queryRow();
                //echo $getDefaultImage['image']."<br>";
              $setImage = $getDefaultImage['image'];
              $quantity= $data['quantity'];
                $total_price=($data['price']*$data['quantity']);
                $subTotal += $total_price;
                $total_price_final= number_format((float)$total_price, 2, '.', '');

        $message .= '<tr>
            <td class="image"><img title="product" alt="product" src="'.Yii::app()->request->baseUrl.'/images/reward/'.$rewardId.'/'.$setImage.'" height="50" width="50"></td>
            <td  class="name">'.$title.'</td>
            <td class="quantity">
              '.$quantity.'
             </td> <td class="price">'.$getCurrency.''.$price.'</td>
             <td class="total">'.$getCurrency.''.$total_price_final.'</td>
          </tr>';
          } } 
         $message .= '</table>';
         $message .= '</div>';

          if(count($listOrders)>0){ 
              $subTotalFinal = number_format((float)$subTotal, 2, '.', '');
              $grandTotal = ($subTotalFinal);
              $grandTotalFinal = number_format((float)$grandTotal, 2, '.', '');
              //$_SESSION['grandTotalFinal'] = $grandTotalFinal;
 
      $message .= '<div class="container"><div class="pull-right">
          <div class="span4 pull-right">
            <table class="table table-striped table-bordered ">
              <tr>
                <td><span class="extra bold">Sub-Total :</span></td>
                <td><span class="bold">'.$getCurrency.''.$subTotalFinal.'</span></td>
              </tr>
              <tr>
                <td><span class="extra bold totalamout">Total :</span></td>
                <td><span class="bold totalamout">'.$getCurrency.''.$grandTotalFinal.'</span></td>
              </tr>
            </table></div>
        </div>
        </div>';
 } 



          /*$message .= "
            <table dir='ltr'>
          <tbody>


          <tr><td style='padding:0;padding-top:25px;font-family:Segoe UI,Tahoma,Verdana,Arial,sans-serif;font-size:14px;color:#2a2a2a'>Thanks,</td></tr>
          <tr><td style='padding:0;font-family:Segoe UI,Tahoma,Verdana,Arial,sans-serif;font-size:14px;color:#2a2a2a'>JobStar Team</td></tr>
          </tbody>
          </table>";
          */
          if($user_id!='') {
          $chkUserRecord = User::model()->find('id = :id', array(':id' => $user_id));
            if(empty($chkUserRecord) ){
              $toUser     = $chkUserRecord['email'];
            }
          }
          $from = Yii::app()->params['adminEmail'];
          $adminEmail = Yii::app()->params['adminEmail'];
          $subject    = 'Mail From JobStars Website:Order Placed By '.ucwords($name);
          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          // More headers
          $headers .= 'From: <'.$adminEmail.'>' . "\r\n";
          $headers .= 'Cc: ranjita706@gmail.com' . "\r\n";
          //$chk = mail($adminEmail, $subject, $message, $headers); //to admin
          $getMsg = $this->mailsend($adminEmail,$from,$subject,$message);

          //send email to user
          //$chk = mail($toUser, $subject, $message, $headers);
          $getMsg1 = $this->mailsend($toUser,$from,$subject,$message);





    }



}
