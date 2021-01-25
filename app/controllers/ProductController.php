<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class ProductController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */

/*
getproductinfobypid : this service used to get the Product Information
Input : {"user_id":2434}
Tables :
1.NidaraKidProfile
2.NCProduct
*/

	public function getproductinfobypid(){
		$input_data = $this->request->getJsonRawBody ();
		$userinfo = NcSalesmanParentMap::findFirstByuser_id($input_data -> user_id);
		if(!$userinfo){
			$curdate=date('Y-m-d');
			$datecheck=$this->modelsManager->createBuilder ()->columns ( array (
		    		'SchoolRegistrationDate.id'
		      	) )->from ('SchoolRegistrationDate')
		       ->leftjoin('SchoolParentMap','SchoolParentMap.school_id=SchoolRegistrationDate.school_id')
	   		->where("SchoolRegistrationDate.status=1 AND SchoolParentMap.user_id =".$input_data -> user_id." AND SchoolRegistrationDate.start_date <='".$curdate."'AND SchoolRegistrationDate.end_date >='".$curdate."'")
		        ->getQuery ()->execute ();
			if(count($datecheck) <= 0 ){
				return $this->response->setJsonContent ( [ 
		                      'status' => false,
		                       'message' =>"Please Contact Your School Manager"
		                ] );
			}
		}
		$countofchild = 0;
		$countofprogram = 0;
		$getgenter = $this->modelsManager->createBuilder ()->columns ( array (
            		'NidaraKidProfile.id as kidid',
            		'NidaraKidProfile.status as kidstatus',
            		'NidaraKidProfile.gender as gender',
			'NidaraKidProfile.first_name',
			'NidaraKidProfile.middle_name',
 			'NidaraKidProfile.last_name',
 			'NidaraKidProfile.age',
                       'NidaraKidProfile.grade as grade_id',
                       'Grade.grade_name',
              	) )->from ('NidaraKidProfile')
               ->leftjoin('KidParentsMap','KidParentsMap.nidara_kid_profile_id=NidaraKidProfile.id')
               ->leftjoin('Grade','NidaraKidProfile.grade=Grade.id')
               ->inwhere('KidParentsMap.users_id',array($input_data -> user_id))
		->groupby("NidaraKidProfile.id")
               ->getQuery ()->execute ();
		if(getgenter){
			$productInfoval=array();
			$productInfovalfinal=array();
			$productarray = array();
			foreach($getgenter as $getgenterval){
				$gender = '';
				if($getgenterval->gender == 'male'){
					$gender = 'boy';
				}
				else {
					$gender = 'girl';
				}
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
				
				foreach( $ncproduct as $value ){
					$paymentvalue = CoreWebsitePaymentGateway::findFirstByapiurl($input_data -> api);
					$ncproduct_price = $this->modelsManager->createBuilder ()->columns ( array (
						'NCProductPricing.id as id',
						'NCProductPricing.product_price as product_price',
						'NCProductPricing.product_type as product_type',
					))->from ('NCProductPricing')
					->inwhere("NCProductPricing.product_id",array($value -> id))
					->inwhere("NCProductPricing.product_type",array($getgenterval -> grade_id))
					->getQuery ()->execute ();
					// $ncproduct_price = NCProductPricing::findByproduct_id($value -> id);
					$ncproduct_price_array = array();
					foreach($ncproduct_price as $product_data){
						$value_data['type_id'] = $product_data -> id;
						$value_data['productPrice'] = $product_data -> product_price + ($product_data -> product_price*18/100);
						$value_data['productAgeStage'] = $product_data -> product_type;
						$ncproduct_price_array[] = $value_data;
					}
					$program = ProductSelectProgram::findFirstBykid_id($getgenterval -> kidid);
					if(!$program){
						$product_value['program'] = '';
					} else {
						$product_value['program'] = $program -> select_program;
						$countofprogram = +1;
					}
					
					$freetrial = NcProductFreetrail::findFirstBykid_id($getgenterval -> kidid);
					if(!$freetrial){
						$product_value['freetrial'] = false;
					} else {
						$product_value['freetrial'] = true;
						// $countofprogram = +1;
					}
					$countofchild = +1;
					$product_value['id'] = $value->id;
					$product_value['productName'] = $value->product_name;
					$product_value['genderType'] = $value->product_type;
					$product_value['productDescription'] = $value->product_des;
					$product_value['imageUpload'] = $value->product_img;
					$product_value['productPriceingQty'] = $ncproduct_price_array;
					$product_value['kidid']=$getgenterval -> kidid;
					$product_value['kidstatus']=$getgenterval -> kidstatus;
					$product_value['first_name']=$getgenterval -> first_name;
					$product_value['middle_name']=$getgenterval -> middle_name;
					$product_value['last_name']=$getgenterval -> last_name;
					$product_value['age']=$getgenterval -> age;
					$product_value['grade_id']=$getgenterval -> grade_id;
					$product_value['grade_name']=$getgenterval -> grade_name;
					$product_value['gender']=$getgenterval -> gender;

					$productarray[] = $product_value;
				}
			}

			return $this->response->setJsonContent ( [ 
					'status' => true,
					'productInfo' =>$productarray,
					'countofchild' => $countofchild,
					'countofprogram' => $countofprogram,
					
					
			] );

		}
		else {
 			return $this->response->setJsonContent ( [ 
                                        'status' => false,
                                        'productInfo' =>"Data is Empty"
                        ] );
		}

	}

	

/*
viewaddress : this service is used for save the user address

Tables : users_address

Input : {"user_id":0}


*/

		public function saveaddress(){
	
		$input_data = $this->request->getJsonRawBody ();
		
		$users = Users::findFirstByid($input_data -> id);
		if(!$users){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Plese give user id',
				] );
		} else {
			$users -> first_name = $input_data -> first_name;
			$users -> last_name = $input_data -> last_name;
			$users -> mobile = $input_data -> mobile;
			$users -> parent_type = $input_data -> parent_type;
			if(!$users -> save()){
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'user not save',
				] );	
			} else {
				$userAddress = UsersAddress::findFirstByuser_id($input_data -> id);
				if(!$userAddress){
					$userAddress = new UsersAddress();
				}
				$userAddress -> user_id =$input_data -> id;
				$userAddress -> address_1 = $input_data -> address_1;
				$userAddress -> address_2 = $input_data -> address_2;
				$userAddress -> city = $input_data -> city;
				$userAddress -> state = $input_data -> state;
				$userAddress -> country = $input_data -> country;
				$userAddress -> post_code = $input_data -> post_code;
				$userAddress -> created_by =$input_data -> id;
				if(!$userAddress -> save()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Please fill in all the fields',
						'data' => $userAddress
					] );
				} else {
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'Data Save successfully',
					] );
				}
			}
		}
			
		
	}




/*
viewaddress : this service is used for geting the user address

Tables : users_address

Input : {"user_id":0}


*/


function viewaddress()
{
			$input_data = $this->request->getJsonRawBody ();

$viewaddress = $this->modelsManager->createBuilder ()->columns ( array (
                                'UsersAddress.id',
                                'UsersAddress.user_id',
                                'UsersAddress.address_1',
                                'UsersAddress.address_2',
                                'UsersAddress.city',
                                'UsersAddress.state',
                                'UsersAddress.country',
                                'UsersAddress.post_code',
                                'UsersAddress.created_at',
                                 'UsersAddress.created_by',
                                'UsersAddress.modified_at',

             			 ) )->from ('UsersAddress')
               ->inwhere('UsersAddress.user_id',array($input_data -> user_id))
                ->getQuery ()->execute ();

if(count($viewaddress)>0)
{
	return $this->response->setJsonContent ( [ 
							'status' => true,
							"data" => $viewaddress
						] );
}
else
{
return $this->response->setJsonContent ( [ 
							'status' => false,
							"data" => "Data not Available"
						] );

}

}
	

}
