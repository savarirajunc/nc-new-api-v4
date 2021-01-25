<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class NcdiscountcouponController extends \Phalcon\Mvc\Controller {
	//CONST UniqId = 'ncp012gb';
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
			'NCDiscountCoupons.id as id',
			'NCDiscountCoupons.discount_coupon_name as discount_coupon_name',
			'NCDiscountCoupons.discount_valid as discount_valid',
			'NCDiscountCoupons.discount_valid_end as discount_valid_end',
			'NCDiscountCoupons.discount_limit as discount_limit',
			'NCDiscountCoupons.coupon_code as coupon_code',
			'NCDiscountCoupons.coupon_status as coupon_status',
			'NCDiscountCoupons.discount_type as discount_type',
			'NCDiscountCoupons.discount_value as discount_value',
		))->from ('NCDiscountCoupons')
		->inwhere("NCProduct.coupon_status",array(1))
		->getQuery ()->execute ();
		$couponarray = array();
		foreach($ncproduct as $value){
			$coupon_data['id'] = $value -> id;
			$coupon_data['discount_coupon_name'] = $value -> discount_coupon_name;
			$coupon_data['discount_type'] = $value -> discount_type;
			$coupon_data['discount_valid'] = $value -> discount_valid;
			$coupon_data['discount_valid_end'] = $value -> discount_valid_end;
			$coupon_data['discount_limit'] = $value -> discount_limit;
			$coupon_data['coupon_code'] = $value -> coupon_code;
			$coupon_data['coupon_status'] = $value -> coupon_status;
			$coupon_data['discount_value'] = $value -> discount_value;
			$couponarray[] = $coupon_data;
		}
		$chunked_array = array_chunk ( $couponarray, 15 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			$product ['coupons'] = $keyed_array;
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $product
			] ); 
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
			$validation->add ( 'discount_valid', new PresenceOf ( [ 
					'message' => 'discount_valid is required' 
			] ) );
			$validation->add ( 'coupon_code', new PresenceOf ( [ 
					'message' => 'Coupon Code is required' 
			] ) );
			$validation->add ( 'discount_type', new PresenceOf ( [ 
					'message' => 'Discount Type is required' 
			] ) );
			$validation->add ( 'discount_value', new PresenceOf ( [ 
					'message' => 'Discount Value image url is required' 
			] ) );
			
			$coupon_id = isset ($input_data->id) ? $input_data->id : '';
					
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
				$collection = NCDiscountCoupons::findFirstByid($coupon_id);
				if(!$collection){
					$collection = new NCDiscountCoupons();
					$collection-> id = $this->ncproductidgen->getNewId ( "New Coupone" );
					$collection-> created_date = date('Y-m-d H:i:s');
					
				}
				$collection-> discount_valid = $input_data -> discount_valid;
				$collection-> discount_valid_end = $input_data -> discount_valid_end;
				$collection-> coupon_code = $input_data -> coupon_code;
				$collection-> discount_type = $input_data -> discount_type;
				$collection-> discount_value = $input_data -> discount_value;
				$collection-> coupon_status = 1;
				$collection-> discount_coupon_name = $input_data -> discount_coupon_name;
				if(count($input_data -> discount_limit) == 0){
					$collection-> discount_limit = 0;
				}
				if(count($input_data -> discount_limit) != 0){
					$collection-> discount_limit = $input_data -> discount_limit;
				}
				$collection-> modified_data = date('Y-m-d H:i:s');
				if(!$collection -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' =>'Filde'
					]);
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
		$coupon_id = isset ($input_data->id) ? $input_data->id : '';
		$ncproduct = NCDiscountCoupons::findByid ($coupon_id);
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $ncproduct
			] ); 
	}
	
	public function deletebyid(){
		$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please give the token"
				] );
			}
		$input_data = $this->request->getJsonRawBody ();
		$coupon_id = isset ($input_data->id) ? $input_data->id : '';
		$collection = NCDiscountCoupons::findFirstByid($coupon_id);
		if(!$collection){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Faield"
			] );
		}
		$collection-> coupon_status = 2;
		if(!$collection -> save()){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Faield"
			] );
		}
		return $this->response->setJsonContent ([ 
			'status' => true,
			'message' =>'successfully delete'
		]);
	}
	 
	public function setCouponDiscount(){
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset ($input_data->user_id) ? $input_data->user_id : '';
		$coupon_code = isset ($input_data->coupon_code) ? $input_data->coupon_code : '';
		if(empty($coupon_code)){
			
			$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
				'NCProductPricing.product_price as total',
				'NCOrderList.order_id as order_id',
				'NCOrderProductList.id as order_product',
				'SUM (NCProductPricing.product_price) as subtotal',
			))->from("NCCheckoutOrder")
			->leftjoin('NCOrderList','NCCheckoutOrder.order_id = NCOrderList.id')
			->leftjoin('NCOrderProductList','NCOrderProductList.id = NCCheckoutOrder.product_order_id')
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
			->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
			->inwhere("NCOrderList.user_id",array($user_id))
			->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure','Free Trial'))
			->groupBy("NCCheckoutOrder.order_id")
			->getQuery ()->execute ();
			$productArray = array();
			foreach($ncproduct as $value){
				$product_data['order_id'] = $value->order_id;
					$product_data['main_tolale_value'] = $value->subtotal;
					$product_data['total'] = $value->total;
					$product_data['main_tax'] = (($product_data['main_tolale_value']*18/100));
					$product_data['cart_amount'] = ($product_data['main_tolale_value']+($product_data['main_tolale_value']*18/100));
					$productArray[] = $product_data;
				// $freetrial = NcProductFreetrail::findFirstByorder_id($value->order_product);
				
			}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $productArray
			] );
		}
		else{
			$collection = NCDiscountCoupons::findBycoupon_code($coupon_code);
			if(count($collection) !== 0){
			 $days = strtotime(date("M d Y "));
			foreach($collection as $value){
				$totalamount = array();
				$collection_coupon_date = date('d',(strtotime($value->discount_valid_end) - $days));
					if((strtotime($value->discount_valid_end) >= $days) && (strtotime($value->discount_valid) < $days)){
						$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
							'SUM (NCProductPricing.product_price) as subtotal',
							'NCOrderList.order_id as order_id',
						))->from("NCOrderList")
						->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
						->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
						->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
						->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
						->inwhere("NCOrderList.user_id",array($user_id))
						->inwhere("NCOrderStatus.status",array('Order New','Aborted','Failure','Free Trial'))
						->getQuery ()->execute ();
					$productArray = array();
					foreach($ncproduct as $value2){
						$product_data['totale'] =  ($value2->subtotal);
						$product_data['tax'] =  (($product_data['totale']*18/100));
						$product_data['main_tolale'] =   ($product_data['totale']+($product_data['totale']*18/100));
						$product_data['order'] = $value2->order_id;
						$productArray[] = $product_data;
					}
					$data_value['order_id'] = $product_data['order'];
					$data_value ['main_tolale_value'] =  $product_data['totale'];
					$data_value ['discount_coupon'] =  $value->discount_value;
					$data_value ['discount_type'] = $value->discount_type;
					if($data_value ['discount_type'] === 'amount'){
						$data_value['discount_amount'] =  ($data_value ['main_tolale_value'] - $data_value ['discount_coupon']);
						$data_value['discount'] = $data_value ['discount_coupon'];
					}
					else if($data_value ['discount_type'] === 'percentage'){
						$data_value['discount_amount'] = ($data_value ['main_tolale_value'] - ($data_value ['main_tolale_value']*$data_value ['discount_coupon']/100));
						$data_value['discount'] = ($data_value ['main_tolale_value']*$data_value ['discount_coupon']/100);
					}
					$data_value ['main_tax'] =  (($data_value['discount_amount']*18/100));
					$data_value ['cart_amount'] =  ($data_value ['discount_amount'] + ($data_value['discount_amount']*18/100));
					$data_value ['coupon_code'] = $value -> coupon_code;
					$totalamount[] = $data_value;
				}
				else{
					return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "The coupon code you entered couldn't be applied to any items in your order."
					] );
				}
			}
			return $this->response->setJsonContent ( [
						'status' => true,
						'message' => "The coupon code you have entered has been added successfully.",
						'data' => $totalamount
					] ); 
			}
			else{
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "The coupon code you entered couldn't be applied to any items in your order."
					] );
			}
		}
	}


	public function getbycouponcodetype(){
		$input_data = $this->request->getJsonRawBody ();
		$coupon_code = isset ($input_data->coupon_code) ? $input_data->coupon_code : '';
		$collection = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorCode.user_id as user_id',
			'DoctorCode.doctor_code as doctor_codes',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
		))->from('DoctorCode')
		->leftjoin('Users','DoctorCode.user_id = Users.id')
		->inwhere('DoctorCode.doctor_code',array($coupon_code))
		->inwhere('Users.status',array(1,2,3))
		->getQuery ()->execute ();
		// DoctorCode::findFirstBydoctor_code($coupon_code);
		if(count($collection) == 0){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "The doctor code you entered is invalid."
			] );
		}
		else {
			$couponearray = array();
			foreach($collection as $value){
				$data_value['doctor_code'] = $value->doctor_code;
				$couponearray[] = $data_value;
			}
			return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $collection
			] );
		}
		
		
	}
}

