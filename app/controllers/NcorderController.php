<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class NcorderController extends \Phalcon\Mvc\Controller {
	//CONST UniqId = 'ncp012gb';
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$ncproduct = NCOrderList::find ();
		$orderproductarray = array();
		foreach( $ncproduct as $value ){
			$ncorder_product = NCOrderProductList::findByorder_id($value -> id);
			$order_product_list = array();
			foreach ($ncorder_product as $order_product_value) {
				$order_product_price = NCProductPricing::findByproduct_main_id($order_product_value -> product_id);
				$productarray = array();
				foreach($order_product_price as $product_price){
					$product_price_value['product_price'] = $product_price->product_price;
					$product_price_value['product_type'] = $product_price->product_type;
					$product_price_value['totale'] +=  $product_price->product_price;
					$productarray[] = $product_price_value;
				}
				$order_product_list_value['id'] = $order_product_value -> id;
				$order_product_list_value['order_id'] = $order_product_value -> order_id;
				$order_product_list_value['product_id'] = $order_product_value -> product_id;
				$order_product_list_value['product_qty'] = (count($ncorder_product));
				$order_product_list_value['product_details'] = $productarray;
				$order_product_list [] = $order_product_list_value;
			}
			$ncorder_status  = NCOrderStatus::findByorder_id($value -> id);
			$ncorder_status_array = array();
			foreach($ncorder_status as $status_value){
				$status_value_data['id'] = $status_value->id;
				$status_value_data['order_id'] = $status_value->order_id;
				$status_value_data['status'] = $status_value->status;
				$ncorder_status_array[] = $status_value_data;
			}
			$ncuser_info = Users::findByid($value->user_id);
			$userinfoarray = array();
			foreach($ncuser_info as $user_value){
				$user_data['first_name'] = $user_value->first_name;
				$user_data['last_name'] = $user_value->last_name;
				$userinfoarray[] = $user_data;
			}
			$order_data['id'] = $value->id;
			$order_data['order_id'] = $value->order_id;
			$order_data['user_id'] = $value->user_id;
			$order_data['bysource'] = $value->bysource;
			$order_data['userinfo'] = $userinfoarray;
			$order_data['status'] = $ncorder_status_array;
			$order_data['order_price'] = $order_product_list;
			$orderproductarray[] = $order_data;
		} 
		
		
		$chunked_array = array_chunk ( $orderproductarray, 15 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			$product ['order'] = $keyed_array;
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $product
			] ); 
	}
	
	
	public function addproductorder(){
		$input_data = $this->request->getJsonRawBody ();
		$session_id = isset ($input_data->session_id) ? $input_data->session_id : '';
		$user_id = isset($input_data->user_id) ? $input_data->user_id : '';
		$user_type = isset($input_data->user_type) ? $input_data->user_type : '';
		 if(empty($session_id)){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Give session_id id"
			] );
		}
		
		$product = NCProductCart::findBysession_id($session_id);
		
		if(count($product) == 0){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Give product id"
			] );
		}
		else{
			$collection = $this->modelsManager->createBuilder()->columns ( array (
				'NCOrderList.id as id'
			))->from('NCOrderList')
			->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
			->inwhere('NCOrderList.user_id',array($user_id))
			->inwhere('NCOrderStatus.status',array($input_data->status))
			->getQuery ()->execute ();
			if(count($collection) == 0){
				$collection_1 = new NCOrderList();
				$collection_1 ->id = $this->ncproductidgen->getNewId ( "Order" );
				$collection_1 ->order_id = ('NIDARA-ORDER-'. $collection_1 ->id .'');
				$collection_1 ->user_id = $user_id;
				$collection_1 ->bysource = $user_type;
				$collection_1 ->save();
				$collection_2 = new NCOrderStatus();
				$collection_2 ->id = $this->ncproductidgen->getNewId ( "Order Status" );
				$collection_2 ->order_id = $collection_1 ->id;
				$collection_2 ->status = $input_data->status;
				$collection_2 ->save();
				foreach($product as $value){
					$order_save = new NCOrderProductList();
					$order_save ->id = $this->ncproductidgen->getNewId ( "Order Product" );
					$order_save ->order_id = $collection_1 ->id;
					$order_save ->product_id = $value->product_id;
					if(!$order_save->save()){
						return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Filde"
						] );
					}
				}
				return $this->response->setJsonContent ([ 
						'status' => true,
						'message' =>'successfully one'
				]);
			}
			else{
				foreach($collection as $order_value){
					foreach($product as $value){
						$order_save = new NCOrderProductList();
						$order_save ->id = $this->ncproductidgen->getNewId ( "Order Product" );
						$order_save ->order_id = $order_value ->id;
						$order_save ->product_id = $value->product_id;
						if(!$order_save->save()){
							return $this->response->setJsonContent ( [
							"status" => false,
							"message" => "Filde"
							] );
						}
					}
					return $this->response->setJsonContent ([ 
						'status' => true,
						'message' =>'successfully two'
					]);
				}
			}
		}
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
		$id = isset ($input_data->id) ? $input_data->id : '';
		/* return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $id
		] ); */
		if( empty($id)){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Please give Order Id"
			] );

		}
	else{
		
		$order_list = $this->modelsManager->createBuilder ()->columns ( array (
			'NCOrderList.id as ids',
			'NCOrderList.order_id as order_id',
			'NCOrderProductList.product_id as product_id',
			'NCOrderStatus.status as status',
			'NCProductPricing.product_id as main_product_id',
			'NCProductPricing.product_price as product_price',
			'NCProductPricing.product_type as product_type',
			'NCProduct.product_name as product_name',
			'NCProduct.product_img as product_img',
			'NCProduct.product_type as gender_type',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.email as email',
			'Users.mobile as mobile',
			'Users.occupation as occupation',
			'Users.company_name as company_name',
			'Users.country_of_residence as country_of_residence',
			'Users.country_of_citizenship as country_of_citizenship',
		))->from('NCOrderList')
		->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
		->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
		->leftjoin('NCProductPricing','NCProductPricing.id = NCOrderProductList.product_id')
		->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
		->leftjoin('Users','Users.id = NCOrderList.user_id')
		->inwhere("NCOrderList.id", array($id))
		->getQuery ()->execute ();
		$orderdetails = array();
		foreach($order_list as $value){
			$order_value['id'] = $value->ids;
			$order_value['order_id'] = $value->order_id;
			$order_value['product_id'] = $value->product_id;
			$order_value['status'] = $value->status;
			$order_value['main_product_id'] = $value->main_product_id;
			$order_value['product_price'] = $value->product_price;
			$order_value['product_type'] = $value->product_type;
			$order_value['product_name'] = $value->product_name;
			$order_value['product_img'] = $value->product_img;
			$order_value['gender_type'] = $value->gender_type;
			$order_value['first_name'] = $value->first_name;
			$order_value['last_name'] = $value->last_name;
			$order_value['email'] = $value->email;
			$order_value['mobile'] = $value->mobile;
			$order_value['occupation'] = $value->occupation;
			$order_value['company_name'] = $value->company_name;
			$order_value['country_of_residence'] = $value->country_of_residence;
			$order_value['country_of_citizenship'] = $value->country_of_citizenship;
			$order_value['total'] = round((($value->product_price)*18/100)+$value->product_price);
			$orderdetails[] = $order_value;
		}
		return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $orderdetails
		] );
	}
	}
	
	public function ordertotalamount(){
		$input_data = $this->request->getJsonRawBody ();
		$collection = new NCOrderAmount();
		$collection->order_id = $input_data->order_id;
		$collection->user_id = $input_data->user_id;
		$collection->total_amount = $input_data->main_tolale_value;
		$collection->discoun_amount = $input_data->discount;
		$collection->cart_amount = $input_data->cart_amount;
		$collection->tax_amount = $input_data->main_tax;
		if($collection->save()){
			return $this->response->setJsonContent ( [
				"status" => true,
				"message" => "Save Sucssfull"
			] );
		}
		else{
			return $this->response->setJsonContent ( [
				"status" => true,
				"message" => "con not save data"
			] );
		}
	}
}

