<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
use Phalcon\Validation\Validator\PresenceOf;
class NcproductController extends \Phalcon\Mvc\Controller {
	//CONST UniqId = 'ncp012gb';
	public function index() {
	} 
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
			'NCProduct.id as id',
			'NCProduct.product_name as product_name',
			'NCProduct.product_type as product_type',
			'NCProduct.product_des as product_des',
			'NCProduct.product_img as product_img',
		))->from ('NCProduct')
		->inwhere("NCProduct.product_status",array(1))
		->getQuery ()->execute ();
		$productarray = array();
		foreach( $ncproduct as $value ){
			$ncproduct_price = NCProductPricing::findByproduct_id($value -> id);
			$ncproduct_price_array = array();
			foreach($ncproduct_price as $product_data){
				$value_data['type_id'] = $product_data -> id;
				$value_data['productPrice'] = $product_data -> product_price;
				$value_data['productAgeStage'] = $product_data -> product_type;
				$ncproduct_price_array[] = $value_data;
			}
			$product_value['id'] = $value->id;
			$product_value['productName'] = $value->product_name;
			$product_value['genderType'] = $value->product_type;
			$product_value['productDescription'] = $value->product_des;
			$product_value['imageUpload'] = $value->product_img;
			$product_value['productPriceingQty'] = $ncproduct_price_array;
			$productarray[] = $product_value;
		}
		
		$chunked_array = array_chunk ( $productarray, 15 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			$product ['product'] = $keyed_array;
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $product
			] ); 
	}
	
	public function getProductInfo(){
		$input_data = $this->request->getJsonRawBody ();
		$gender = isset ($input_data->gender) ? $input_data->gender : '';
		if(empty ($gender)){
			return $this->response->setJsonContent ([ 
				'status' => false,
				'message' =>'Please give gender'
			]);
		}
		else {
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
				'NCProduct.id as id',
				'NCProduct.product_name as product_name',
				'NCProduct.product_type as product_type',
				'NCProduct.product_des as product_des',
				'NCProduct.product_img as product_img',
			))->from ('NCProduct')
			->inwhere("NCProduct.product_status",array(1))
			->inwhere("NCProduct.product_type",array($gender))
			->getQuery ()->execute ();
			$productarray = array();
			foreach( $ncproduct as $value ){
				$paymentvalue = CoreWebsitePaymentGateway::findFirstByapiurl($input_data -> api);
				$ncproduct_price = NCProductPricing::findByproduct_id($value -> id);
				$ncproduct_price_array = array();
				foreach($ncproduct_price as $product_data){
					$value_data['type_id'] = $product_data -> id;
					$value_data['productPrice'] = $product_data -> product_price + ($product_data -> product_price*18/100);
					$value_data['productAgeStage'] = $product_data -> product_type;
					$ncproduct_price_array[] = $value_data;
				}
				$product_value['id'] = $value->id;
				$product_value['productName'] = $value->product_name;
				$product_value['genderType'] = $value->product_type;
				$product_value['productDescription'] = $value->product_des;
				$product_value['imageUpload'] = $value->product_img;
				$product_value['productPriceingQty'] = $ncproduct_price_array;
				$productarray[] = $product_value;
			}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $productarray,
				'payment' => $paymentvalue
			] );
		}
		
		
	}
	
	public function save(){
		
			$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please give the token"
				] );
			}
			
			$input_data = $this->request->getJsonRawBody ();
			if (empty ( $input_data )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the input datas" 
				] );
			}
			$validation = new Validation ();
			$validation->add ( 'productName', new PresenceOf ( [ 
					'message' => 'Product name is required' 
			] ) );
			$validation->add ( 'genderType', new PresenceOf ( [ 
					'message' => 'Product Type is required' 
			] ) );
			$validation->add ( 'productDescription', new PresenceOf ( [ 
					'message' => 'Product description is required' 
			] ) );
			$validation->add ( 'imageUpload', new PresenceOf ( [ 
					'message' => 'Product image url is required' 
			] ) );
			
			$product_id = isset ($input_data->id) ? $input_data->id : '';
			
			$product_qty  = $input_data->productPriceingQty;
			
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}

				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
			}
			else {
				$collection = NCProduct::findFirstByid($product_id);
				if(!$collection){
					$collection = new NCProduct();
					$collection-> id = $this->ncproductidgen->getNewId ( "New Product" );
				}
				$collection-> product_name = $input_data -> productName;
				$collection-> product_type = $input_data -> genderType;
				$collection-> product_des = $input_data -> productDescription;
				$collection-> product_img = $input_data -> imageUpload;
				$collection-> created_date = date('Y-m-d H:i:s');
				$collection-> product_status = 1;
				$collection -> save();
				foreach($product_qty as $value){
					$collection_price = NCProductPricing::findFirstByid($value -> id);
					if(!$collection_price){
						$collection_price = new NCProductPricing ();
						$collection_price -> id = $this->ncproductidgen->getNewId ( "Product Price" );
						$collection_price -> created_date = date('Y-m-d H:i:s');
						$collection_price -> product_id = $collection-> id ;
						$collection_price -> product_main_id = ('ncp'. $collection_price -> id .'-'. $input_data -> genderType .'');
					}
					$collection_price -> product_price = $value -> productPrice;
					$collection_price -> product_type = $value -> productAgeStage;
					$collection_price -> modify_date = date('Y-m-d H:i:s');
					if(!$collection_price->save()){
						return $this->response->setJsonContent ([ 
								'status' => false,
								'message' =>'Faield'
						]);
					}
				}
			}
			return $this->response->setJsonContent ([ 
				'status' => true,
				'message' =>'successfully'
			]);
		
	}
	
	
	public function getbyid() {
		$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please give the token"
				] );
			}
		$input_data = $this->request->getJsonRawBody ();
		$product_id = isset ($input_data->id) ? $input_data->id : '';
		$ncproduct = NCProduct::findByid ($product_id);
		$productarray = array();
		foreach( $ncproduct as $value ){
			$ncproduct_price = NCProductPricing::findByproduct_id($value -> id);
			$ncproduct_price_array = array();
			foreach($ncproduct_price as $product_data){
				$value_data['id'] = $product_data -> id;
				$value_data['productPrice'] = $product_data -> product_price;
				$value_data['productAgeStage'] = $product_data -> product_type;
				$ncproduct_price_array[] = $value_data;
			}
			$product_value['id'] = $value->id;
			$product_value['productName'] = $value->product_name;
			$product_value['genderType'] = $value->product_type;
			$product_value['productDescription'] = $value->product_des;
			$product_value['imageUpload'] = $value->product_img;
			$product_value['productPriceingQty'] = $ncproduct_price_array;
			$productarray[] = $product_value;
		}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $productarray
			] ); 
	}
	
	public function getProductByTypeId(){
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset($input_data->user_id) ? $input_data->user_id : '';
		$session_id = isset ($input_data->session_id) ? $input_data->session_id : '';
		if(empty($user_id)){
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					'SUM (NCProductPricing.product_price) as subtotal',
					'COUNT (NCProductCart.product_id) as item',
					'COUNT (NCProductCart.id) as qty',
				))->from("NCProductPricing")
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->leftjoin('NCProductCart','NCProductCart.product_id = NCProductPricing.id')
				->inwhere("NCProductCart.session_id",array($session_id))
				->groupBy("NCProductCart.product_id")
				->getQuery ()->execute ();
				$productArray = array();
				foreach($ncproduct as $value){
					$product_data['id'] = $value->ids; 
					$product_data['item'] = $value->item; 
					$product_data['subtotal'] = $value->subtotal; 
					$product_data['product_type'] = $value->product_type; 
					$product_data['product_name'] = $value->product_name; 
					$product_data['product_img'] = $value->product_img; 
					$product_data['product_price'] = $value->product_price; 
					$product_data['qty'] += $value->qty; 
					$product_data['totale'] += ($value->subtotal);
					$product_data['tax'] = (($product_data['totale']*18/100));
					$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
					$productArray[] = $product_data;
				}
				
				
				return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $productArray
				] ); 
		}
		else{
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCOrderProductList.order_id as order_id',
					'NCProductPricing.product_type as product_type',
					'NCOrderProductList.id as order_product',
					'NCOrderProductList.product_id as product_id',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
				))->from("NCOrderList")
				->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->leftjoin('NCCheckoutOrder','NCCheckoutOrder.product_id = NCProductPricing.id')
				->inwhere("NCOrderList.user_id",array($user_id))
				->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure','Free Trial'))
				->groupBy("NCOrderProductList.id")
				->getQuery ()->execute ();
				$productArray = array();
				/* return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $ncproduct,
				] ); */
				foreach($ncproduct as $value){
					if($value->product_type){
						$freetrial = NcProductFreetrail::findFirstByorder_id($value->order_product);
						if(!$freetrial){
							$getcheckout = $this->modelsManager->createBuilder ()->columns ( array (
								'NCCheckoutOrder.id as checkout_id'
							))->from("NCCheckoutOrder")
							->inwhere("NCCheckoutOrder.order_id",array($value-> order_id))
							->inwhere("NCCheckoutOrder.product_id",array($value -> ids))
							->inwhere("NCCheckoutOrder.product_order_id",array($value -> order_product))
							->getQuery ()->execute ();
							/* return $this->response->setJsonContent ( [
								'status' => true,
								'data' => $getcheckout,
							] ); */
							if(count($getcheckout) > 0){
								foreach($getcheckout as $checkoutvalue){
									
								}
								$product_data['checkout_id'] = $checkoutvalue->checkout_id;
							} else {
								$product_data['checkout_id'] = '';
							}
							$product_data['freetrail'] = '0';
							$product_data['product_price'] = $value->product_price;
							$product_data['slect_product'] = true;
							$product_data['slect_product_freetrial'] = false;
							$product_data['order_id'] = $value-> order_id;
							$product_data['totale'] += ($value->product_price);
							$product_data['tax'] = (($product_data['totale']*18/100));
							$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
						} else {
							$kidprofile = NidaraKidProfile::findFirstByid($freetrial -> kid_id);
							if($kidprofile -> status <= 2){
								$getcheckout = $this->modelsManager->createBuilder ()->columns ( array (
									'NCCheckoutOrder.id as checkout_id'
								))->from("NCCheckoutOrder")
								->inwhere("NCCheckoutOrder.order_id",array($value-> order_id))
								->inwhere("NCCheckoutOrder.product_id",array($value -> ids))
								->inwhere("NCCheckoutOrder.product_order_id",array($value -> order_product))
								->getQuery ()->execute ();
								if(count($getcheckout) > 0){
									foreach($getcheckout as $checkoutvalue){
										
									}
									$product_data['checkout_id'] = $checkoutvalue->checkout_id;
								} else {
									$product_data['checkout_id'] = '';
								}
								$product_data['freetrail'] = '0';
								$product_data['product_price'] = $value->product_price;
								$product_data['slect_product'] = true;
								$product_data['slect_product_freetrial'] = false;
								$product_data['totale'] += ($value->product_price);
								$product_data['tax'] = (($product_data['totale']*18/100));
								$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
							} else {
								$product_data['freetrail'] = '1';
								$product_data['totale'] = 0;
								$product_data['product_price'] = 0;
								$product_data['tax'] = (($product_data['totale']*18/100));
								$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
								$product_data['slect_product'] = false;
								$product_data['slect_product_freetrial'] = true;
								
							}
						}
						
						$product_data['id'] = $value->ids;
						$product_data['qty'] += 1;
						$product_data['order_product'] = $value->order_product; 
						$product_data['product_type'] = $value->product_type;
						$product_data['product_id'] = $value->product_id;
						$product_data['product_name'] = $value->product_name; 
						$product_data['product_img'] = $value->product_img; 
						$product_data['item'] = 1;						
						$productArray[] = $product_data;
					}
				}
				
				
				return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $productArray,
					'order_id' => $product_data['order_id'],
					'totale' => $product_data['totale'],
					'tax' => $product_data['tax'],
					'main_tolale' => $product_data['main_tolale']
				] );
		}
		
	}
	
	public function getproductbycheckout(){
			$input_data = $this->request->getJsonRawBody ();
			
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCOrderProductList.order_id as order_id',
					'NCProductPricing.product_type as product_type',
					'NCOrderProductList.id as order_product',
					'NCOrderProductList.product_id as product_id',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
					'NCCheckoutOrder.id as checkout_id',
				))->from("NCOrderList")
				->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
				->leftjoin('NCCheckoutOrder','NCCheckoutOrder.product_order_id = NCOrderProductList.id')
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->inwhere("NCOrderList.user_id",array($input_data -> user_id))
				->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure','Free Trial'))
				->groupBy("NCOrderProductList.id")
				->orderBy("NCOrderProductList.id")
				->getQuery ()->execute ();
				$productArray = array();
				foreach($ncproduct as $value){
					if($value->product_type){
						$freetrial = NcProductFreetrail::findFirstByorder_id($value->order_product);
						if(!$freetrial){
							$product_data['freetrail'] = '0';
							$product_data['product_price'] = $value->product_price;
							$ncproduct_item = $this->modelsManager->createBuilder ()->columns ( array (
								'NCCheckoutOrder.id as id',
							))->from("NCCheckoutOrder")
							->inwhere("NCCheckoutOrder.order_id",array($input_data -> order_id))
							->inwhere("NCCheckoutOrder.product_order_id",array($value -> order_product))
							->inwhere("NCCheckoutOrder.product_id",array($value-> product_id))
							->getQuery ()->execute ();
							if(count($ncproduct_item) > 0){
								foreach($ncproduct_item as $item){
								}
								$product_data['checkout_id'] = $item->id;
								$product_data['slect_product'] = true;
								$product_data['order_id'] = $value-> order_id;
								$product_data['totale'] += ($value->product_price);
								$product_data['tax'] = (($product_data['totale']*18/100));
								$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
							} else {
								$product_data['checkout_id'] = '';
								$product_data['slect_product'] = false;
								$product_data['order_id'] = $value-> order_id;
							}
							
						} else {
							$kidprofile = NidaraKidProfile::findFirstByid($freetrial -> kid_id);
							if($kidprofile -> status <= 2){
								$product_data['freetrail'] = '0';
								$product_data['product_price'] = $value->product_price;
							$ncproduct_item = $this->modelsManager->createBuilder ()->columns ( array (
								'NCCheckoutOrder.id as id',
							))->from("NCCheckoutOrder")
							->inwhere("NCCheckoutOrder.order_id",array($input_data -> order_id))
							->inwhere("NCCheckoutOrder.product_order_id",array($value -> order_product))
							->inwhere("NCCheckoutOrder.product_id",array($value-> product_id))
							->getQuery ()->execute ();
							if(count($ncproduct_item) > 0){
								foreach($ncproduct_item as $item){
								}
								$product_data['checkout_id'] =$item->id;
								$product_data['slect_product'] = true;
								// $product_data['order_id'] = $value-> order_id;
								$product_data['totale'] += ($value->product_price);
								$product_data['tax'] = (($product_data['totale']*18/100));
								$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
							} else {
								$product_data['checkout_id'] = '';
								$product_data['slect_product'] = false;
								// $product_data['order_id'] = $value-> order_id;
							}
							} else {
								$product_data['freetrail'] = '1';
								$product_data['product_price'] = 0;
								$product_data['totale'] += 0;
								$product_data['tax'] = (($product_data['totale']*18/100));
								$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
								$product_data['slect_product'] = false;
								$product_data['slect_product_freetrial'] = true;
							}
						}
						$product_data['order_id'] = $value-> order_id;
						$product_data['id'] = $value->ids; 
						$product_data['order_product'] = $value->order_product; 
						$product_data['subtotal'] += $value->product_price; 
						$product_data['product_type'] = $value->product_type;
						$product_data['product_id'] = $value->product_id;
						$product_data['product_name'] = $value->product_name; 
						$product_data['product_img'] = $value->product_img; 
						$product_data['item'] = 1;
						$product_data['qty'] += 1; 
						
						$productArray[] = $product_data;
					}
				}
				return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $productArray,
					'order_id' => $product_data['order_id'],
					'totale' => $product_data['totale'],
					'tax' => $product_data['tax'],
					'main_tolale' => $product_data['main_tolale']
				] );
		}
		
		public function addconvertpaid(){
			$input_data = $this->request->getJsonRawBody ();
			$freetrial = NcProductFreetrail::findFirstBykid_id($input_data -> kid_id);
			$getorderid = NCOrderProductList::findFirstByid($freetrial -> order_id);
			$order_id = $getorderid -> order_id;
			$product_id = $freetrial -> order_id;
			if(!$freetrial -> delete()){
				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>'Free trial not deleted',
				]);
			} else {
				$createnew = new NCCheckoutOrder();
				$createnew -> order_id = $getorderid -> order_id;
				$createnew -> product_id = $getorderid -> product_id;
				$createnew -> product_order_id = $product_id;
				$createnew -> create_at = date('Y-m-d');
				if(!$createnew -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' =>'product not be seved',
						'data' => $createnew
					]);
				} else {
					return $this->response->setJsonContent ([ 
						'status' => true,
						'message' =>'successfully',
						'order_id' => $order_id
					]);
				}
			}
			
		}
			
			/* return $this->response->setJsonContent ([ 
						'status' => true,
						'message' =>$input_data,
						'order_id' => $order_id
					]);
			
			$getorderid = NCOrderProductList::findFirstByid($freetrial -> order_id);
			$order_id = $getorderid -> order_id;
			$product_id = $freetrial -> order_id;
			if(!$freetrial -> delete()){
				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>'Free trial not deleted',
				]);
			} else {
				$createnew = new NCCheckoutOrder();
				$createnew -> order_id = $getorderid -> order_id;
				$createnew -> product_id = $getorderid -> product_id;
				$createnew -> product_order_id = $product_id;
				$createnew -> create_at = date('Y-m-d');
				if(!$createnew -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' =>'product not be seved',
						'data' => $createnew
					]);
				} else {
					return $this->response->setJsonContent ([ 
						'status' => true,
						'message' =>'successfully',
						'order_id' => $order_id
					]);
				}
			}
		} */
		
		 public function addconvertproduct(){
			$input_data = $this->request->getJsonRawBody ();
			$order_id;
			foreach($input_data -> products as $value){
				if(empty($input_data -> order_id)){
					$getorderid = NCOrderProductList::findFirstByid($value -> order_product);
					$order_id = $getorderid -> order_id;
					$freetrial = NcProductFreetrail::findFirstByorder_id($value->order_product);
					if(!$freetrial -> delete()){
						return $this->response->setJsonContent ([ 
							'status' => false,
							'message' =>'Free trial not deleted',
						]);
					} else {
						$createnew = new NCCheckoutOrder();
						$createnew -> order_id = $getorderid -> order_id;
						$createnew -> product_id = $value -> product_id;
						$createnew -> product_order_id = $value -> order_product;
						$createnew -> create_at = date('Y-m-d');
						if(!$createnew -> save()){
							return $this->response->setJsonContent ([ 
								'status' => false,
								'message' =>'product not be seved',
								'data' => $createnew
							]);
						}
					}
				} else {
					$order_id = $input_data -> order_id;
					$freetrial = NcProductFreetrail::findFirstByorder_id($value->order_product);
					if(!$freetrial -> delete()){
						return $this->response->setJsonContent ([ 
							'status' => false,
							'message' =>'Free trial not deleted',
						]);
					} else {
						$createnew = new NCCheckoutOrder();
						$createnew -> order_id = $input_data -> order_id;
						$createnew -> product_id = $value -> product_id;
						$createnew -> product_order_id = $value -> order_product;
						$createnew -> create_at = date('Y-m-d');
						if(!$createnew -> save()){
							return $this->response->setJsonContent ([ 
								'status' => false,
								'message' =>'product not be seved',
								'data' => $createnew
							]);
						}
					}
				}
			}
			return $this->response->setJsonContent ([ 
				'status' => true,
				'message' =>'successfully',
				'order_id' => $order_id
			]);
		} 
		
		public function addcheckout(){
			$input_data = $this->request->getJsonRawBody ();
			foreach($input_data -> products as $value){
				if($value -> slect_product){
					if($value ->freetrail === '0'){
						$ncproduct_item = $this->modelsManager->createBuilder ()->columns ( array (
							'NCCheckoutOrder.id as id',
						))->from("NCCheckoutOrder")
						->inwhere("NCCheckoutOrder.order_id",array($input_data -> order_id))
						->inwhere("NCCheckoutOrder.product_id",array($value-> product_id))
						->inwhere("NCCheckoutOrder.product_order_id",array($value-> order_product))
						->getQuery ()->execute ();
						if(count($ncproduct_item) > 0){
							foreach($ncproduct_item as $item){
									}
							$createnew = NCCheckoutOrder::findFirstByid($item -> id);
							if(!$createnew){
								$createnew = new NCCheckoutOrder();
							}
						} else {
							$createnew = new NCCheckoutOrder();
						}
						$createnew -> order_id = $input_data -> order_id;
						$createnew -> product_id = $value -> product_id;
						$createnew -> product_order_id = $value -> order_product;
						$createnew -> create_at = date('Y-m-d');
						if(!$createnew -> save()){
							return $this->response->setJsonContent ([ 
								'status' => false,
								'message' =>'product not be seved',
								'data' => $createnew
							]);
						}
					}
				}
				else {
					$createnew = NCCheckoutOrder::findFirstByid($value -> checkout_id);
					if(!$createnew){}
					else{
						if(!$createnew -> delete()){
							return $this->response->setJsonContent ([ 
								'status' => false,
								'message' =>'product not be removed'
							]);
						}
					}
				}
			}
			return $this->response->setJsonContent ([ 
				'status' => true,
				'message' =>'successfully'
			]);
			
		}
	
		public function getProductByTypeIdVelue(){
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset($input_data->user_id) ? $input_data->user_id : '';
		$session_id = isset ($input_data->session_id) ? $input_data->session_id : '';

		$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
			'NCProductPricing.id as ids',
			'NCProductPricing.product_type as product_type',
			'NCOrderProductList.id as order_product',
			'NCProduct.product_name as product_name',
			'NCProduct.product_img as product_img',
			'NCProductPricing.product_price as product_price',
		))->from("NCCheckoutOrder")
		->leftjoin('NCOrderList','NCCheckoutOrder.order_id = NCOrderList.id')
		->leftjoin('NCOrderProductList','NCOrderProductList.id = NCCheckoutOrder.product_order_id')
		->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
		->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
		->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
		->inwhere("NCOrderList.user_id",array($user_id))
		->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure','Free Trial'))
		->groupBy("NCCheckoutOrder.id")
		->getQuery ()->execute ();
		$productArray = array();
		foreach($ncproduct as $value){
					$product_data['id'] = $value->ids; 
					$product_data['order_product'] = $value->order_product; 
					$product_data['item'] = $value->item; 
					$product_data['subtotal'] = $value->subtotal; 
					$product_data['product_type'] = $value->product_type; 
					$product_data['product_name'] = $value->product_name; 
					$product_data['product_img'] = $value->product_img; 
					$product_data['product_price'] = $value->product_price; 
					$product_data['qty'] += 1; 
					$product_data['totale'] += ($value->product_price);
					$product_data['tax'] = (($product_data['totale']*18/100));
					$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
					$productArray[] = $product_data;
			/* if($value->product_type){
				$freetrial = NcProductFreetrail::findFirstByorder_id($value->order_product);
				if(!$freetrial){
					$product_data['freetrail'] = '0';
					
				} else {
					$kidprofile = NidaraKidProfile::findFirstByid($freetrial -> kid_id);
					if($kidprofile -> status <= 2){
						$product_data['freetrail'] = '1';
						$product_data['id'] = $value->ids; 
						$product_data['order_product'] = $value->order_product; 
						$product_data['item'] = $value->item; 
						$product_data['subtotal'] = $value->subtotal; 
						$product_data['product_type'] = $value->product_type; 
						$product_data['product_name'] = $value->product_name; 
						$product_data['product_img'] = $value->product_img; 
						$product_data['product_price'] = $value->product_price; 
						$product_data['qty'] += $value->qty; 
						$product_data['totale'] += ($value->subtotal);
						$product_data['tax'] = (($product_data['totale']*18/100));
						$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
						$productArray[] = $product_data;
					} else { 
						$dailyattendance = DailyRoutineAttendance::findBynidara_kid_profile_id($freetrial -> kid_id);
						if(count($dailyattendance) > 5){
							$product_data['freetrail'] = '1';
							$product_data['id'] = $value->ids; 
							$product_data['order_product'] = $value->order_product; 
							$product_data['item'] = $value->item; 
							$product_data['subtotal'] = $value->subtotal; 
							$product_data['product_type'] = $value->product_type; 
							$product_data['product_name'] = $value->product_name; 
							$product_data['product_img'] = $value->product_img; 
							$product_data['product_price'] = $value->product_price; 
							$product_data['qty'] += $value->qty; 
							$product_data['totale'] += ($value->subtotal);
							$product_data['tax'] = (($product_data['totale']*18/100));
							$product_data['main_tolale'] = ($product_data['totale']+($product_data['totale']*18/100));
							$productArray[] = $product_data;
						}
					}
					
				}
				
			} */
		}		
		return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $productArray
		] );
	}
	
	
	public function getByTypeId(){
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset($input_data->user_id) ? $input_data->user_id : '';
		$order_id = isset ($input_data->order_id) ? $input_data->order_id : '';
		if(empty($order_id)){
			
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProduct.product_type as gender_type',
					'NCOrderList.order_id as order_id',
					'NCProductPricing.product_price as product_price',
				))->from("NCOrderList")
				->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->inwhere("NCOrderList.user_id",array($user_id))
				->inwhere("NCOrderStatus.status",array('Free Trial'))
				->groupBy("NCOrderProductList.product_id")
				->getQuery ()->execute ();
				$productArray = array();
				foreach($ncproduct as $value){
					if($value->product_type){
						$product_data['order_id'] = $value->order_id;
						$product_data['product_type'] = $value->product_type; 
						$product_data['gender_type'] = $value->gender_type; 
						$productArray[] = $product_data;
					}
				}
				return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $productArray
				] );
			
		}
		else{
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProduct.product_type as gender_type',
					'NCProductPricing.product_price as product_price',
				))->from("NCOrderList")
				->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->inwhere("NCOrderList.user_id",array($user_id))
				->inwhere("NCOrderList.order_id",array($order_id))
				->inwhere("NCOrderStatus.status",array('Success'))
				->groupBy("NCOrderProductList.product_id")
				->getQuery ()->execute ();
				$productArray = array();
				foreach($ncproduct as $value){
					if($value->product_type){
						$product_data['product_type'] = $value->product_type; 
						$product_data['gender_type'] = $value->gender_type; 
						$productArray[] = $product_data;
					}
				}
				return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $productArray
				] );
			}
		}
	
	/*
addProductInCart : this service used to add to bag selected prooduct 
Input : 
		{
		"user_id":2434,
		"status":1,
		"user_type":"parent",
		"productItem":89
		 }

Tables:
1.NCOrderList
2.NCOrderStatus
3.NCOrderProductList

*/
	
	public function addProductInCart(){
		$input_data = $this->request->getJsonRawBody ();
		if(empty($input_data->user_id)){
			$collection = new NCProductCart();
			$collection->session_id = $input_data->session_id;
			$collection->product_id = $input_data->productItem;
			if(!$collection->save()){
				return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Faield"
				] );
			}
			else{
				return $this->response->setJsonContent ([ 
					'status' => true,
					'message' =>'successfully delete'
				]);
			}
		}
		else{
			$collection = $this->modelsManager->createBuilder()->columns ( array (
				'NCOrderList.id as id'
			))->from('NCOrderList')
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->inwhere('NCOrderList.user_id',array($input_data->user_id))
			->inwhere('NCOrderStatus.status',array($input_data->status))
			->getQuery ()->execute ();
			if(count($collection) == 0){
				$collection_1 = new NCOrderList();
				$collection_1 ->id = $this->ncproductidgen->getNewId ( "Order" );
				$collection_1 ->order_id = ('NIDARA-ORDER-'. $collection_1 ->id .'');
				$collection_1 ->user_id = $input_data->user_id;
				$collection_1 ->bysource = $input_data->user_type;
				$collection_1 ->save();
				$collection_2 = new NCOrderStatus();
				$collection_2 ->id = $this->ncproductidgen->getNewId ( "Order Status" );
				$collection_2 ->order_id = $collection_1 ->id;
				$collection_2 ->status = $input_data->status;
				$collection_2 ->save();
				$order_save = new NCOrderProductList();
				$order_save ->id = $this->ncproductidgen->getNewId ( "Order Product" );
				$order_save ->order_id = $collection_1 ->id;
				$order_save ->product_id = $input_data->productItem;
				if(!$order_save->save()){
					return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Filde"
					] );
				}
				else{
					if($input_data -> kid_id){
						if($input_data -> freetrial === '1'){
							$childifo = NidaraKidProfile::findFirstByid($input_data -> kid_id);
							$childifo -> status = 3;
							$childifo -> save();
							$freetrial = NcProductFreetrail::findFirstBykid_id($input_data -> kid_id);
							if(!$freetrial){
								$freetrial = new NcProductFreetrail();
							}
							$freetrial -> kid_id = $input_data->kid_id;
							$freetrial -> order_id = $order_save -> id;
							$freetrial -> status = 1;
							$freetrial -> save();
						} else {
							$childifo = NidaraKidProfile::findFirstByid($input_data -> kid_id);
							$childifo -> status = 4;
							$childifo -> save();
						}
						$program = ProductSelectProgram::findFirstBykid_id($input_data -> kid_id);
						if(!$program){
							$program = new ProductSelectProgram();
						}
						$program -> users_id = $input_data->user_id;
						$program -> kid_id = $input_data->kid_id;
						$program -> product_id = $input_data->productItem;
						$program -> select_program = $input_data->program;
						$program -> product_order_id = $order_save -> id;
						$program -> save();
						
					}
					return $this->response->setJsonContent ([ 
						'status' => true,
						'message' =>'successfully'
					]); 
				}
				
			}
			else{
				foreach($collection as $order_value){
					
					$order_save = new NCOrderProductList();
					$order_save ->id = $this->ncproductidgen->getNewId ( "Order Product" );
					$order_save ->order_id = $order_value ->id;
					$order_save ->product_id = $input_data->productItem;
					if(!$order_save->save()){
						return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Filde"
						] );
					}
					else{
						if($input_data -> kid_id){
							if($input_data -> freetrial === '1'){
								$childifo = NidaraKidProfile::findFirstByid($input_data -> kid_id);
								$childifo -> status = 3;
								$childifo -> save();
								$freetrial = NcProductFreetrail::findFirstBykid_id($input_data -> kid_id);
								if(!$freetrial){
									$freetrial = new NcProductFreetrail();
								}
								$freetrial -> kid_id = $input_data->kid_id;
								$freetrial -> order_id = $order_save -> id;
								$freetrial -> status = 1;
								$freetrial -> save();
							} else {
								$childifo = NidaraKidProfile::findFirstByid($input_data -> kid_id);
								$childifo -> status = 4;
								$childifo -> save();
							}
							$program = ProductSelectProgram::findFirstBykid_id($input_data -> kid_id);
							if(!$program){
								$program = new ProductSelectProgram();
							}
							$program -> users_id = $input_data->user_id;
							$program -> kid_id = $input_data->kid_id;
							$program -> product_id = $input_data->productItem;
							$program -> select_program = $input_data->program;
							$program -> product_order_id = $order_save -> id;
							$program -> save();
							
						}
						return $this->response->setJsonContent ([ 
							'status' => true,
							'message' =>'successfully'
						]);
					}
				}
			}
		}
		
	}
	
	 /*
cartDelete : this service used to delete from bag selected prooduct 
Input : 
		{
		"user_id":2434,
		"product_id":89
		 }

Tables:
1.NCOrderList
2.NCOrderStatus
3.NCOrderProductList

*/
	
	public function deletebyid(){
		$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please give the token"
				] );
			}
		$input_data = $this->request->getJsonRawBody ();
		$product_id = isset ($input_data->id) ? $input_data->id : '';
		$collection = NCProduct::findFirstByid($product_id);
		if(!$collection){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Faield"
			] );
		}
		$collection-> product_status = 2;
		$collection -> save();
		return $this->response->setJsonContent ([ 
			'status' => true,
			'message' =>'successfully delete'
		]);
	}
	
	public function cartDelete(){
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset ($input_data->user_id) ? $input_data->user_id : '';
		$session_id = isset ($input_data->session_id) ? $input_data->session_id : '';
		$product_id = isset ($input_data->id) ? $input_data->id : '';
		if(empty($user_id)){
			$collection = NCProductCart::findBysession_id($session_id);
			if($collection){
				$collection = NCProductCart::findByproduct_id($product_id);
				if($collection){
					if ($collection->delete ()){
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'Message' => 'Record has been deleted succefully ' 
					] );
					}
					else{
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Data could not be deleted in Cart' 
						] );
					}
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data could not be deleted in Cart' 
					] );
					
				}
			}
			else{
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data could not find session id' 
				] );
			}
		}
		else{
			$collection = $this->modelsManager->createBuilder()->columns ( array (
				'NCOrderProductList.id as id'
			))->from('NCOrderList')
			->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->inwhere('NCOrderList.user_id',array($user_id))
			->inwhere('NCOrderProductList.id',array($input_data->order_product))
			->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure','Free Trial'))
			->getQuery ()->execute ();
			foreach($collection as $value){
				$program = $program = ProductSelectProgram::findFirstByproduct_order_id($value->id);
					if(!$program){
						
					} else {
						$childifo = NidaraKidProfile::findFirstByid($program -> kid_id);
						$childifo -> status = 5;
						if(!$childifo -> save()){
							return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Kid status not change' 
							] );
						} else {
							if(!$program -> delete()){
								return $this->response->setJsonContent ( [ 
									'status' => false,
									'Message' => 'Program data could not be deleted' 
								] );
							}
						}
						$freetrial = NcProductFreetrail::findFirstBykid_id($program -> kid_id);
						if(!$freetrial -> delete()){
							return $this->response->setJsonContent ( [ 
								'status' => false,
								'Message' => 'Program data could not be deleted' 
							] );
						}
					}
				$collection2 = NCOrderProductList::findByid($value->id);
				if(!$collection2->delete ()){
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Data could not be deleted' 
						] );
				}
				$ncproduct = NCCheckoutOrder::findFirstByid($input_data -> checkout_id);
				if(!$ncproduct -> delete()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Check out not deleted' 
					] );
				}
			}
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'Message' => $collection2
			] );
		}
	}
	
	public function payment(){
		/* return $this->response->setJsonContent ( [ 
				'status' => true,
				'Message' => $this->config->weburl
			] ); */
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset ($input_data->user_id) ? $input_data->user_id : '';
		$collection = $this->modelsManager->createBuilder()->columns ( array (
				'Users.first_name as first_name',
				'Users.last_name as last_name',
				'Users.email as email',
				'Users.mobile as mobile',	
				'UsersAddress.address_1 as address_1',	
				'UsersAddress.address_2 as address_2',	
				'UsersAddress.city as city',	
				'UsersAddress.state as state',	
				'UsersAddress.country as country',	
				'UsersAddress.post_code as post_code',	
		))->from('Users')
		->leftjoin('UsersAddress','UsersAddress.user_id = Users.id')
		->inwhere('Users.id',array($user_id))
		->getQuery ()->execute ();
		foreach($collection as $user_value){
			
		}
		
		$merchant = $input_data -> merchant_data;
		$working_key = $input_data -> working_key;
		$access_code = $input_data -> access_code;
		
		$merchant_data ='merchant_id='. $merchant -> merchant_id .'&order_id='. $merchant->order_id .'&currency='. $merchant->currency  .'&amount='. $merchant->amount  .'&redirect_url='. 		$merchant->redirect_url  .'&cancel_url=' . $merchant->cancel_url .'&integration_type=iframe_normal&coupon_code='. $merchant->coupon_code .'
&billing_name=' . $user_value->first_name .' '. $user_value->last_name .'&billing_address='. $user_value->address_1 .''. $user_value->address_2 .'
&billing_city=' . $user_value->city . '&billing_state=' . $user_value->state . '&billing_zip=' . $user_value->post_code . '&billing_country=' . $user_value->country . '
&billing_tel=' . $user_value->city . '&billing_tel=' . $user_value->mobile . '&billing_email=' . $user_value->email . '';

		$encrypted_data = $this -> encrypt($merchant_data,$working_key);
		
		return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $encrypted_data
		] );	
		
	}
	
	
		function encrypt($plainText,$key)
		{
    			$secretKey = hextobin(md5($key));
    			$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    			$encryptedText = openssl_encrypt($plainText, "AES-128-CBC", $secretKey, OPENSSL_RAW_DATA, $initVector);
    			$encryptedText = bin2hex($encryptedText);
    			return $encryptedText;
		}
		
		function decrypt($encryptedText,$key)
			{
			    $secretKey         = hextobin(md5($key));
			    $initVector         =  pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
			    $encryptedText      = hextobin($encryptedText);
			    $decryptedText         =  openssl_decrypt($encryptedText,"AES-128-CBC", $secretKey, OPENSSL_RAW_DATA, $initVector);
			    return $decryptedText;
			}
	
	public function paymentresponsive(){
		error_reporting(0);
	//	$workingKey='21F05B7B25BA3BEF8A7D7507C1DBD21F';		//Working Key of www.nidarachildren should be provided here.
		$workingKey= $this->config->working_key;		//Working Key of newdev.nidarachildren should be provided here.
		$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
		$weburl = $this->config->weburl;
		$rcvdString= $this -> decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
		$order_status="";
		$order_id = "";
		$tracking_id = "";
		$bank_ref = "";
		$failure = "";
		$paymentmode ="";
		$card_name ="";
		$status_code ="";
		$status_message ="";
		$currency ="";
		$amount ="";
		$billing_name="";
		$result = "";
		$decryptValues=explode('&', $rcvdString);
		$dataSize=sizeof($decryptValues);
		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]);
			if($i==0)	$order_id = $information[1];
			if($i==1)	$tracking_id = $information[1];
			if($i==2)	$bank_ref = $information[1];
			if($i==3)	$order_status=$information[1];
			if($i==4)	$failure = $information[1];
			if($i==5)	$paymentmode = $information[1];	
			if($i==6)	$card_name = $information[1];	
			if($i==7)	$status_code = $information[1];	
			if($i==8)	$status_message = $information[1];	
			if($i==9)	$currency = $information[1];	
			if($i==10)	$amount = $information[1];
			if($i==11)	$billing_name = $information[1];			
			
		}
		$result = $order_id . ' 2 ' . $tracking_id . ' 3 ' . $bank_ref . ' 4 ' . $order_status . ' 5 ' . $failure . ' 6 ' . $paymentmode;
	/* return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $encResponse
		] );  */
		if($order_status==="Success")
		{
			$order = new NCOrderPaymentStatus();
			$order->order_id = $order_id;
			$order->tracking_id = $tracking_id;
			$order->bank_ref_no = $bank_ref;
			$order->order_status = $order_status;
			$order->failure_message = $failure;
			$order->payment_mode = $paymentmode;
			$order->card_name = $card_name;
			$order->status_code = $status_code;
			$order->status_message = $status_message;
			$order->currency = $currency;
			$order->amount = $amount;
			if($order->save()){
				$ordercheck = $this->modelsManager->createBuilder ()->columns ( array (
					'NCOrderStatus.id as id',
				))->from("NCOrderList")
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->inwhere("NCOrderList.order_id", array($order_id))
				->getQuery ()->execute ();
				foreach($ordercheck as $ordercheckvalue){
				}
				
				$orderstatus = NCOrderStatus::findFirstByid($ordercheckvalue ->id);
				$orderstatus->status = $order_status;
				if($orderstatus->save()){
					$collection = $this->modelsManager->createBuilder ()->columns ( array (
						'NCOrderList.id as id',
						'NCOrderList.order_id as order_ids',
						'NCOrderList.user_id as user_id',
					))->from("NCOrderList")
					->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
					->inwhere("NCOrderList.order_id", array($order_id))
					->getQuery ()->execute ();
					
					foreach($collection as $value){
							$productorderlist = $this->modelsManager->createBuilder()->columns ( array (
								'NCOrderProductList.id as id'
							))->from('NCOrderList')
							->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
							->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
							->inwhere('NCOrderList.user_id',array($value -> user_id))
							->inwhere("NCOrderList.order_id", array($order_id))
							->getQuery ()->execute ();
							foreach($productorderlist as $productorderlistvalue){
								$freetrial = NcProductFreetrail::findFirstByorder_id($productorderlistvalue->id);
								if(!$freetrial){
								
								}else {
									$freetrial -> delete();
								}
								$program = $program = ProductSelectProgram::findFirstByproduct_order_id($productorderlistvalue->id);
								if(!$program){
									return $this->response->setJsonContent ( [ 
										'status' => false,
										'Message' => 'No Programe select' 
									] );
								} else {
									$childifo = NidaraKidProfile::findFirstByid($program -> kid_id);
									$childifo -> status = 3;
									if(!$childifo -> save()){
										return $this->response->setJsonContent ( [ 
										'status' => false,
										'Message' => 'Kid status not change' 
										] );
									} 
								}
							}
							$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
								'NCProductPricing.id as ids',
								'NCProductPricing.product_type as product_type',
								'NCProduct.product_name as product_name',
								'NCProduct.product_img as product_img',
								'NCProductPricing.product_price as product_price',
							))->from("NCOrderList")
							->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
							->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
							->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
							->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
							->inwhere("NCOrderList.id",array($value->id))
							->inwhere("NCOrderStatus.status",array('Success'))
							->groupBy("NCOrderProductList.id")
							->getQuery ()->execute ();
							$productArray = array();
							$mailcontant = '';
							foreach($ncproduct as $ncproductvalue){
							$mailcontant .= ' <div class="product-section">';
							$mailcontant .= '<div class="col-mil-3">
								<img src="' . $ncproductvalue->product_img .'" style="width:100%" />
								</div> ';
							$mailcontant .= '
								<div class="col-mil-6">
								<h3> ' . $ncproductvalue->product_name . '</h3>
								<p>Rs. ' . $ncproductvalue->product_price . ' / month for 12 months</p>
							</div>';
							$mailcontant .= '
								<div class="col-mil-3">
									<p class="bottome">Total:  Rs. ' . ( $ncproductvalue->product_price +( $ncproductvalue->product_price *18/100) ) . ' </p>
								</div>
							</div>';
							}
							$mailcontant .='';
							$user = Users::findFirstByid($value->user_id);
					 		$user -> status = 3;
					 		$user -> save();
					
							$mail = new PHPMailer;

							//$mail->SMTPDebug = 3;                               // Enable verbose debug output

							$mail->isSMTP();                                      // Set mailer to use SMTP
							$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;                               // Enable SMTP authentication
							$mail->Username = 'contact@haselfre.com';                 // SMTP username
							$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
							$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
							$mail->Port = 587;                                    // TCP port to connect to

							$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
							$mail->addAddress($user -> email, '');     // Add a recipient
																					// Name is optional
							$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

							//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
							//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
							$mail->isHTML(true);                                  // Set email format to HTML

							$mail->Subject = 'Payment Successful: Thank You For Your Payment At Nidara-Children...';
							$mail->Body    = '
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
								<html>
								<head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8" />
								<style type="text/css">
								.ReadMsgBody {width: 100%;}
								.ExternalClass {width: 100%;}

								body {
									color: #666666;
								}
										.page-title {
											text-align: center;
											width: 100%;
											float: left;
											color: #8bbdcb;
										}
									   body,td{
									    font-family:verdana,geneva;
									    font-size:12px;
									   }
									   body{
									    background:#fff;
									    padding:20px;
									   }
									   .top-img{
									    width:100%;
									    text-align:center;
									    padding-bottom:0;
									    font-size:10px;
									   }
									   .sub-mail-cont{
									    width:100%;
									   }
									   .sub-mail-vr{
									    width:580px;
									    margin:auto;
									    float:none;
									   }
									   .main-page-mail{
									    width:100%;
									    float:left;
									    padding:20px;
									    border:1px solid #999;
									   }
									   .sub-mail-but{
									    width:100%;
									    text-align:center;
									    padding-top:30px;
									    float:left;
									   }
									   a.sub-but{
									    text-decoration:none;
									    color:#333;
									    padding:10px 50px;
									    border:1px solid;
									   }
									   .sub-but-cont{
									    width:100%;
									    padding-top:20px;
									    float:left;
									   }
									   .footer{
									    width:100%;
									    text-align:center;
									    font-size:10px;
									    padding-top:20px;
									    float:left;
									   }
									   .footer ul{
									    list-style:none;
									    float:left;
									    margin:15px 10px;
									    width:100%;
									    padding:0;
									   }
									   .footer ul li{
									    display:inline-flex;
									    padding-left:5px;
									   }
									   p{
									    line-height:18px;
									   }
									   .small{
									    font-size:11px;
									   }
									   .main-title{
									    text-align:center;
									    color:#aed7d3;
									    float:left;
									    width:100%;
									   }
									   .main-title h3{
									    font-weight:500;
									   }
									   .first-name{
									    text-transform:capitalize;
									   }
									   .product-img{
									    width:20%;
									    float:left;
									    padding-right:20px;
									   }
									   .product-img img{
									    width:100%;
									   }
									   .product-cont{
									    width:75%;
									    float:left;
									   }
									   .product-details{
									    width:100%;
									    float:left;
									   }
										   .page-content {
												width: 100%;
												float: left;
												color: #666666;
											}
											.click-but {
												width: 100%;
												float: left;
												text-align: center;
											}
											.click-but .but{
												width:250px;
												display:block;
												margin:auto;
											}
											.click-but .but a {
												text-decoration: none;
												padding: 10px;
												color: #fff;
											}
											.click-but .but {
												display: block;
												margin: auto;
												width: 300px;
												padding: 20px;
												background: #333333;
												font-size: 18px;
											}
											.click-but .but:hover{
												background: #8bbdcb;
											}
											
											.product-section {
											    width: 100%;
											    float: left;
											    padding: 15px;
											     position: relative;
											}
											.col-mil-3 {
											    width: 25%;
											    float: left;
											    padding: 10px;
											}
											
											.col-mil-6 {
											    width: 30%;
											    float: left;
											    padding: 10px;
											}
											.bottome {
												position: absolute;
												 bottom: 10%;
											}
									 
								span.yshortcuts { color:#000; background-color:none; border:none;}
								span.yshortcuts:hover,
								span.yshortcuts:active,
								span.yshortcuts:focus {color:#000; background-color:none; border:none;}
								</style>
								</head>
								<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
								,geneva; font-size: 12px; background: #fff; padding: 20px;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

									  
									  <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
									    <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
									   <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
									     <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
									   </div>
										   <div class="page-title">
											<h3>
												Payment Successful: Thank You For Your Payment At Nidara-Children...
											</h3>
										   </div>
											<div class="page-content">
												
												<p>Dear ' . $user -> first_name . ',</p> 

												<p>Thank you for making payment the Nidara-Children Early Childhood Care and Education system for your little child.  A copy of your purchase is below for your records:
								</p>

											</div>
											' . $mailcontant . '
											<div class="click-but">
												<div class="but">
													<a href="' . $weburl .'/childprofile"> <span>START USING NIDARA-CHILDREN</span> </a>
												</div>
											</div>
									    
									    <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
									   <p style="line-height: 18px;">Best regards,</p>
									   <p style="line-height: 18px;">
									    </p>
									    <p style="line-height: 18px;">Nidara-Children</p>
									  </div>
									  <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
									    <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
												<li style="display: inline-flex; padding-left: 5px;">
												 <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
											   </li>
											   <li style="display: inline-flex; padding-left: 5px;">
												 <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
											   </li>
											   <li style="display: inline-flex; padding-left: 5px;">
												 <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
											   </li>
											   <li style="display: inline-flex; padding-left: 5px;">
												 <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
											   </li>
									   </ul>
											<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
												<br /><span>You are receiving this email because you opted in at our website.
									    </span>
									    <br /><span>
											<a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
											</span>
									  </div>
									   </div>
									 </div>
									 
								</td></tr></table>
								</body>
								</html>
								';
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						if(!$mail->send()) {
							echo 'Message could not be sent.';
							echo 'Mailer Error: ' . $mail->ErrorInfo;
						} else {
							echo 'Message has been sent';
						}
						 echo "<script>window.location='$weburl/ncparentresetpassword/successful-bay';</script>";
					}
				}
				
			echo "<script>window.location='$weburl/ncparentresetpassword/successful-bay';</script>";
			
			}
		}
		else if($order_status==="Aborted")
		{
			$order = new NCOrderPaymentStatus();
			$order->order_id = $order_id;
			$order->tracking_id = $tracking_id;
			$order->bank_ref_no = $bank_ref;
			$order->order_status = $order_status;
			$order->failure_message = $failure;
			$order->payment_mode = $paymentmode;
			$order->card_name = $card_name;
			$order->status_code = $status_code;
			$order->status_message = $status_message;
			$order->currency = $currency;
			$order->amount = $amount;
			$order->save(); 
			$collection = $this->modelsManager->createBuilder ()->columns ( array (
				'NCOrderList.id as id',
				'NCOrderList.order_id as order_ids',
				'NCOrderList.user_id as user_id',
			))->from("NCOrderList")
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->inwhere("NCOrderList.order_id",array($order_id))
			->getQuery ()->execute ();
			foreach($collection as $value){
				$orderstatus = NCOrderStatus::findFirstByorder_id($value->id);
				$orderstatus->status = $order_status;
				if(!$orderstatus -> save()){
					echo "<script>window.location='$weburl/ncparentresetpassword/payment-cancel';</script>";
				}
				else{
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
				))->from("NCOrderList")
				->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->inwhere("NCOrderList.id",array($value->id))
				->groupBy("NCOrderProductList.product_id")
				->getQuery ()->execute ();
				$productArray = array();
				$mailcontant = '';
				foreach($ncproduct as $ncproductvalue){
				$mailcontant .= ' <div class="product-section">';
				$mailcontant .= '<div class="col-mil-3">
					<img src="' . $ncproductvalue->product_img .'" alt="170x150_logo.jpg" style="width:100%" />
				</div> ';
				$mailcontant .= '
				<div class="col-mil-6">
					<h3> ' . $ncproductvalue->product_name . '</h3>
					<p>Rs. ' . $ncproductvalue->product_price . ' / month for 12 months</p>
				</div>';
				$mailcontant .= '
					<div class="col-mil-3">
						<p class="bottome">Total:  Rs. ' . ( $ncproductvalue->product_price +( $ncproductvalue->product_price *18/100) ) . ' </p>
					</div>
				</div>';
				}
				$mailcontant .='';
					$user = Users::findFirstByid($value->user_id);
					$managerinfo = $this->modelsManager->createBuilder ()->columns ( array (
							'Users.id as id',
							'Users.email as email',
							'Users.mobile as mobile',
							'Users.first_name as first_name',
							'Users.last_name as last_name',
						))->from("SchoolUserMap")
						->leftjoin('Schools','SchoolUserMap.schools_id = Schools.id')
						->leftjoin('SchoolParentMap','SchoolParentMap.school_id = Schools.id')
						->leftjoin('Users','SchoolUserMap.users_id = Users.id')
						->inwhere('SchoolParentMap.user_id',array($value->user_id))
						->getQuery ()->execute ();
						foreach($managerinfo as $userinfo){
							
						}
					$maincontant = ' <div class="page-title">';
					$maincontant .= '<h3>Payment Unsuccessful: Please Revise Your Payment To Complete Your Purchase at Nidara-Children...</h3>';
					$maincontant .= ' </div>';
					$maincontant .= ' <div class="page-content">';
					$maincontant .= '<p>Dear ' . $user -> first_name . ',</p>';
					$maincontant .= '<p>We re writing to let you know that the payment for the items listed below has been declined. Please revise your payment details to complete registration for the early childhood program.</p>
				<p> The issuing bank may have declined the charge if the name or account details entered do not match the bank’s records.   </p>';
					$maincontant .= ' </div>';
					$maincontant .= $mailcontant;
					$maincontant .= '<div class="click-but">';
					$maincontant .= '<div class="but">';
					$maincontant .= '<a href="' . $weburl .'/ncparentresetpassword/checkoutpage"> <span>REVISE YOUR PAYMENT</span> </a>';
					$maincontant .= '</div>';
					$maincontant .= '</div>';
					$maincontant .= '<div class="contact">';
					$maincontant .= '<h3>Or contact your sales manager for help: ' . $userinfo -> first_name . ' ' . $userinfo -> last_name .' ' . $userinfo -> mobile .  ' </h3>';
					$maincontant .= '</div>';
					
					$topset = file_get_contents('../public/email/topmail.html');
					$bottomset = file_get_contents('../public/email/bottom.html');
					
					$mail = new PHPMailer;
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = 'contact@haselfre.com';                 // SMTP username
					$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;                                    // TCP port to connect to
					$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
					$mail->addAddress($user -> email, '');   //user email
					$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = 'Payment Unsuccessful: Please Revise Your Payment To Complete Your Purchase at Nidara-Children...';
					$mail->Body    = $topset . '' . $maincontant . '' . $bottomset;
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					if(!$mail->send()) {
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						
						 
						$manageremailinfo = ' <div class="page-title">';
						$manageremailinfo .= '<h3> Payment Unsuccessful For ' . $user -> first_name . ' ' . $user -> last_name . '</h3>';
						$manageremailinfo .= ' </div>';
						$manageremailinfo .= ' <div class="page-content">';
						$manageremailinfo .= '<p>Dear ' . $userinfo -> first_name . ' ' . $userinfo -> last_name . ',</p>';
						$manageremailinfo .= '<p>We`re writing to let you know that the customer has not been able to purchase the Nidara-Children product online. </p>
						<p>The details are given below. Please contact them and help them complete the purchase.</p>';
						$manageremailinfo .= '<p>Coustomer Name: ' . $user -> first_name .' ' . $user -> last_name . '</p>';
						$manageremailinfo .= '<p>Coustomer Email: ' . $user -> email .'</p>';
						$manageremailinfo .= '<p>Coustomer Mobile' . $user -> mobile .'</p>';
						$manageremailinfo .= ' </div>';
						$manageremailinfo .= $mailcontant;
						
						$mail = new PHPMailer;
						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'contact@haselfre.com';                 // SMTP username
						$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to
						$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
						$mail->addAddress($userinfo -> email, '');   //user email
						$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->Subject = 'Payment Unsuccessful For ' . $user -> first_name . '  ' . $user -> last_name;
						$mail->Body    = $topset . '' . $manageremailinfo . '' . $bottomset;
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
						if(!$mail->send()) {
							echo 'Message could not be sent.';
							echo 'Mailer Error: ' . $mail->ErrorInfo;
						} else {
							echo 'Message has been sent';
						}
					}
					 echo "<script>window.location='$weburl/ncparentresetpassword/payment-cancel';</script>";
				}
			}
		}
		else 
		{
			 $order = new NCOrderPaymentStatus();
			$order->order_id = $order_id;
			$order->tracking_id = $tracking_id;
			$order->bank_ref_no = $bank_ref;
			$order->order_status = $order_status;
			$order->failure_message = $failure;
			$order->payment_mode = $paymentmode;
			$order->card_name = $card_name;
			$order->status_code = $status_code;
			$order->status_message = $status_message;
			$order->currency = $currency;
			$order->amount = $amount;
			$order->save(); 
			$collection = $this->modelsManager->createBuilder ()->columns ( array (
				'NCOrderList.id as id',
				'NCOrderList.order_id as order_ids',
				'NCOrderList.user_id as user_id',
			))->from("NCOrderList")
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->inwhere("NCOrderList.order_id",array($order_id))
			->getQuery ()->execute ();
			foreach($collection as $value){
				$orderstatus = NCOrderStatus::findFirstByorder_id($value->id);
				$orderstatus->status = 'Failure';
				if(!$orderstatus -> save()){
					echo "<script>window.location='$weburl/ncparentresetpassword/payment-cancel';</script>";
				}
				else{
				$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
					'NCProductPricing.id as ids',
					'NCProductPricing.product_type as product_type',
					'NCProduct.product_name as product_name',
					'NCProduct.product_img as product_img',
					'NCProductPricing.product_price as product_price',
				))->from("NCOrderList")
				->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
				->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
				->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
				->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
				->inwhere("NCOrderList.id",array($value->id))
				->groupBy("NCOrderProductList.product_id")
				->getQuery ()->execute ();
				$productArray = array();
				$mailcontant = '';
				foreach($ncproduct as $ncproductvalue){
				$mailcontant .= ' <div class="product-section">';
				$mailcontant .= '<div class="col-mil-3">
					<img src="' . $ncproductvalue->product_img .'" alt="170x150_logo.jpg" style="width:100%" />
				</div> ';
				$mailcontant .= '
				<div class="col-mil-6">
					<h3> ' . $ncproductvalue->product_name . '</h3>
					<p>Rs. ' . $ncproductvalue->product_price . ' / month for 12 months</p>
				</div>';
				$mailcontant .= '
					<div class="col-mil-3">
						<p class="bottome">Total:  Rs. ' . ( $ncproductvalue->product_price +( $ncproductvalue->product_price *18/100) ) . ' </p>
					</div>
				</div>';
				}
				$mailcontant .='';
					$user = Users::findFirstByid($value->user_id);
					$managerinfo = $this->modelsManager->createBuilder ()->columns ( array (
							'Users.id as id',
							'Users.email as email',
							'Users.mobile as mobile',
							'Users.first_name as first_name',
							'Users.last_name as last_name',
						))->from("SchoolUserMap")
						->leftjoin('Schools','SchoolUserMap.schools_id = Schools.id')
						->leftjoin('SchoolParentMap','SchoolParentMap.school_id = Schools.id')
						->leftjoin('Users','SchoolUserMap.users_id = Users.id')
						->inwhere('SchoolParentMap.user_id',array($value->user_id))
						->getQuery ()->execute ();
						foreach($managerinfo as $userinfo){
							
						}
					$maincontant = ' <div class="page-title">';
					$maincontant .= '<h3>Payment Unsuccessful: Please Revise Your Payment To Complete Your Purchase at Nidara-Children...</h3>';
					$maincontant .= ' </div>';
					$maincontant .= ' <div class="page-content">';
					$maincontant .= '<p>Dear ' . $user -> first_name . ',</p>';
					$maincontant .= '<p>We re writing to let you know that the payment for the items listed below has been declined. Please revise your payment details to complete registration for the early childhood program.</p>
				<p> The issuing bank may have declined the charge if the name or account details entered do not match the bank’s records.   </p>';
					$maincontant .= ' </div>';
					$maincontant .= $mailcontant;
					$maincontant .= '<div class="click-but">';
					$maincontant .= '<div class="but">';
					$maincontant .= '<a href="' . $weburl .'/ncparentresetpassword/checkoutpage"> <span>REVISE YOUR PAYMENT</span> </a>';
					$maincontant .= '</div>';
					$maincontant .= '</div>';
					$maincontant .= '<div class="contact">';
					$maincontant .= '<h3>Or contact your sales manager for help: ' . $userinfo -> first_name . ' ' . $userinfo -> last_name .' ' . $userinfo -> mobile .  ' </h3>';
					$maincontant .= '</div>';
					
					
					$topset = file_get_contents('../public/email/topmail.html');
					$bottomset = file_get_contents('../public/email/bottom.html');
					
					$mail = new PHPMailer;
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = 'contact@haselfre.com';                 // SMTP username
					$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;                                    // TCP port to connect to
					$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
					$mail->addAddress($user -> email, '');   //user email
					$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = 'Payment Unsuccessful: Please Revise Your Payment To Complete Your Purchase at Nidara-Children...';
					$mail->Body    = $topset . '' . $maincontant . '' . $bottomset;
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					if(!$mail->send()) {
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						
						$manageremailinfo = ' <div class="page-title">';
						$manageremailinfo .= '<h3> Payment Unsuccessful For ' . $user -> first_name . ' ' . $user -> last_name . '</h3>';
						$manageremailinfo .= ' </div>';
						$manageremailinfo .= ' <div class="page-content">';
						$manageremailinfo .= '<p>Dear ' . $userinfo -> first_name . ' ' . $userinfo -> last_name .',</p>';
						$manageremailinfo .= '<p>We`re writing to let you know that the customer has not been able to purchase the Nidara-Children product online. </p>
						<p>The details are given below. Please contact them and help them complete the purchase.</p>';
						$manageremailinfo .= '<p>Coustomer Name: ' . $user -> first_name .' ' . $user -> last_name . '</p>';
						$manageremailinfo .= '<p>Coustomer Email: ' . $user -> email .'</p>';
						$manageremailinfo .= '<p>Coustomer Mobile: ' . $user -> mobile .'</p>';
						$manageremailinfo .= ' </div>';
						$manageremailinfo .= $mailcontant;
						
						$mail = new PHPMailer;
						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'contact@haselfre.com';                 // SMTP username
						$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to
						$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
						$mail->addAddress($userinfo -> email, '');   //user email
						$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->Subject = 'Payment Unsuccessful For ' . $user -> first_name . '  ' . $user -> last_name;
						$mail->Body    = $topset . '' . $manageremailinfo . '' . $bottomset;
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
						if(!$mail->send()) {
							echo 'Message could not be sent.';
							echo 'Mailer Error: ' . $mail->ErrorInfo;
						} else {
							echo 'Message has been sent';
						}
					}
					 echo "<script>window.location='$weburl/ncparentresetpassword/payment-cancel';</script>";
				}
			}
		}
		
	}
	
	
	public function searchproduct(){
		$input_data = $this->request->getJsonRawBody ();
		$search = $input_data->searchitem;
		$resultq = $this->modelsManager->createQuery('SELECT * FROM NCProduct WHERE  product_name LIKE :searchitem: AND product_status = 1'); 
		$result = $resultq->execute(
			[
				'searchitem' => "%$search%",
			]
		);
		if(count($result) !== 0){
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $result
			] );
		}
		else{
			$resultq2 = $this->modelsManager->createQuery('SELECT * FROM NCProduct WHERE  product_des LIKE :searchitem: AND product_status = 1'); 
			$result2 = $resultq2->execute(
				[
					'searchitem' => "%$search%",
				]
			);
			if(count($result2) !== 0){
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $result2
				] );
			}
			else{
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'No data not find'
				] );
			}
		}
		
	}
	
	public function invoceemailsend(){
		$input_data = $this->request->getJsonRawBody ();
		
		$userinfo = $this->modelsManager->createBuilder ()->columns ( array (
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.email as email',
			'NCOrderAmount.total_amount as total_amount',
			'NCOrderAmount.tax_amount as tax_amount',
			'NCOrderAmount.discoun_amount as discoun_amount',
			'NCOrderAmount.cart_amount as cart_amount',
			'NCOrderAmount.order_id as order_ids',
			'UsersAddress.address_1 as address_1',
			'UsersAddress.address_2 as address_2',
			'UsersAddress.city as city',
			'UsersAddress.state as state',
			'UsersAddress.country as country',
			'UsersAddress.post_code as post_code',
		))->from('NCOrderList')
		->leftjoin('NCOrderAmount','NCOrderAmount.order_id = NCOrderList.order_id')
		->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
		->leftjoin('Users','NCOrderAmount.user_id = Users.id')
		->leftjoin('UsersAddress','UsersAddress.user_id = Users.id')
		->inwhere('NCOrderAmount.user_id',array($input_data->user_id))
		->inwhere("NCOrderStatus.status",array('Success'))
		->getQuery ()->execute ();
		$invocearray = array();
		foreach($userinfo as $user){
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
				'NCProductPricing.id as ids',
				'NCProductPricing.product_type as product_type',
				'NCProduct.product_name as product_name',
				'NCProduct.product_img as product_img',
				'NCProductPricing.product_price as product_price',
			))->from("NCOrderList")
			->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
			->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
			->inwhere("NCOrderList.order_id",array($user->order_ids))
			->getQuery ()->execute ();
			$emailvalue = array();
			foreach($ncproduct as $value){
				$product_data['ids'] = $value->ids;
				$product_data['product_type'] = $value->product_type;
				$product_data['product_name'] = $value->product_name;
				$product_data['product_img'] = $value->product_img;
				$product_data['product_price'] = $value->product_price;
				$emailvalue[] = $product_data;
			}
			$product['first_name'] = $user->first_name;
			$product['last_name'] = $user->last_name;
			$product['email'] = $user->email;
			$product['total_amount'] = $user->total_amount;
			$product['tax_amount'] = $user->tax_amount;
			$product['discoun_amount'] = $user->discoun_amount;
			$product['cart_amount'] = $user->cart_amount;
			$product['order_id'] = $user->order_ids;
			$product['address_1'] = $user->address_1;
			$product['address_2'] = $user->address_2;
			$product['state'] = $user->state;
			$product['country'] = $user->country;
			$product['city'] = $user->city;
			$product['post_code'] = $user->post_code;
			$product['product_list'] = $emailvalue;
			$invocearray[] = $product;
			
		}
		return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $invocearray
				] );
	}

public function freetrailsave()
	{

		$input_data = $this->request->getJsonRawBody ();

		$fretrail=new NcProductFreetrail();
		$fretrail -> order_id =$input_data -> order_id;
		$fretrail -> status =$input_data -> status;
		if($fretrail->save())
		{
		return $this->response->setJsonContent ( [ 
					'status' => true,
					'Message' => 'Saved' 
			] );	
		}
		else{

return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Not Saved' 
			] );	

		}

			
	}
	

}

