<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Alpha as AlphaValidator;
class UsersController extends \Phalcon\Mvc\Controller {
    public function index() {

    }
    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $parentsprofile_view = Users::find();
		$parentarray = array();
		foreach($parentsprofile_view as $value){
			$parentarray[] = $value;
		}
		$chunked_array = array_chunk ( $parentarray, 15 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			$games ['games'] = $keyed_array;
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $games
			] ); 
			
        /* if ($parentsprofile_view):
            
	    return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$parentsprofile_view
			]);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Failed']);
        endif; */
    }

    /*
     * Fetch Record from database based on ID :-
     */
	
	public function getbyid(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$input_data = $this->request->getJsonRawBody ();
		$userinfo = Users::findFirstByid($input_data->id);
		if($userinfo){
			return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $userinfo
			] );
		} else {
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "No user data"
			] );
		}
		// $useraddress = UserAddress::findFirstByuser_id ($input_data->id);
		// $userdata = array();
		// $userAddressarray = array();
		// if(!$userinfo){
		// 	return $this->response->setJsonContent ( [
		// 			"status" => false,
		// 			"message" => "Invalid User id" 
		// 	] );
		// }
		// else{
		// 	if(!$useraddress){
		// 		$userAddressdata['address_1'] = '';
		// 		$userAddressdata['city'] = '';
		// 		$userAddressdata['address_2'] = '';
		// 		$userAddressdata['state'] = '';
		// 		$userAddressdata['country'] = '';
		// 		$userAddressdata['post_code'] = '';
		// 		$userAddressarray[] = $userAddressdata;
		// 	} else {
		// 		$userAddressarray[] = $useraddress;
		// 	}
			
		// 	$userdata[] = $userinfo;
		// 	$hospitalarray[] = $hospitalinfo; 
		// 	$clinicarray[] = $clinicinfo; 
		// 	$userqulifecation[] = $userinfoqus;
		// 	return $this->response->setJsonContent ( [
		// 			"status" => true,
		// 			"data" => $userdata,
		// 			"user_info" => $userqulifecation,
		// 			"hospital_info" => $hospitalarray,
		// 			"clinic_info" => $clinicarray,
		// 			"useraddress" => $userAddressarray,
		// 	] );
		// }
	}
	
	public function seveuserinfo(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		else if(empty($input_data->id)){
			$user = new DoctorInfo();
			$user->register_no = $input_data->register_no;
			$user->qualification = $input_data->qualification;
			$user->user_id = $input_data->user_id;
			if(!$user->save()){
				return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Data not save" 
				] );
			}
			else{
				return $this->response->setJsonContent ( [ 
					"status" => true,
					"message" => "Data save" 
				] );
			}
		}
		else{
			$user = DoctorInfo::findFirstByid($input_data->id);
			$user->register_no = $input_data->register_no;
			$user->qualification = $input_data->qualification;
			if(!$user->save()){
				return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Data not save" 
				] );
			}
			else{
				return $this->response->setJsonContent ( [ 
					"status" => true,
					"message" => "Data save" 
				] );
			}
		}
	}
	
    public function getUserMap(){
    	$headers = $this->request->getHeaders ();
    	$input_data = $this->request->getJsonRawBody ();
	if (empty ( $headers ['Token'] )) {
		return $this->response->setJsonContent ( [
			"status" => false,
			"message" => "Please give the token"
		] );
	} else {
		// $userinfo = NcSalesmanParentMap::findBysalesman_id($input_data -> salesman_id)
		$getuservalue = $this->modelsManager->createBuilder ()->columns ( array (
                    'Users.id as id',
                    'Users.first_name as first_name',
                    'Users.email as email',
                    'Users.status as status',
                 ))->from("NcSalesmanParentMap")
                 ->leftjoin('Users','Users.id = NcSalesmanParentMap.user_id')
                 ->inwhere('NcSalesmanParentMap.salesman_id',array($input_data -> salesman_id))
                 ->getQuery()->execute ();
                 $parentarray = array();
                foreach($getuservalue as $value){
                    $data2['id'] = $value -> id;
                    $data2['first_name'] = $value -> first_name;
                    $data2['email'] = $value -> email;
                    if($value -> status == 1){
                        $data2['status'] = 'Registered';
                    } else {
                        $data2['status'] = 'Not Registered';
                    }
                    $parentarray[] = $data2;
                }
                return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$parentarray
			]);
	}
    }

    public function getmyaccountinfo() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_info = $this->tokenvalidate->getuserinfo ( $headers ['Token'], $baseurl );
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null'
			] );
		}
			$users = Users::findFirstByid ( $id );
			if ($users) :
			$userinfo [$users->parent_type] = $users;
			$map = ParentsMappingProfiles::findFirstByprimary_parents_id ( $id );
			if (! empty ( $map->secondary_parent_id )) {
				$secuser = Users::findFirstByid ( $map->secondary_parent_id );
				$userinfo [$secuser->parent_type] = $secuser;
			}else{
			$secusers['first_name']='';
			$secusers['last_name']='';
			$secusers['email']='';
			$secusers['mobile']='';
			$secusers['occupation']='';
			$secusers['company_name']='';
			if($users->parent_type == 'father'){
				$secusers['parent_type']='mother';
				$userinfo ['mother'] = $secusers;
			}else{
				$secusers['parent_type']='father';
				$userinfo ['father'] = $secusers;
			}
			}
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' => $userinfo
			]);
			
		 
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'You have not entered any information' 
				] );
			endif;
	}

    /**
     * This function using to create NidaraParentsProfile information
     */
    public function save() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Invalid User" 
			] );
		}
		$input_data = $this->request->getJsonRawBody ();
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		/**
		 * This object using valitaion
		 */
		if (! empty ( $input_data->father )) {
			$validation = new Validation ();
			$validation->add ( 'first_name', new PresenceOf ( [ 
					'message' => 'first name is required' 
			] ) );
			$validation->add ( 'last_name', new PresenceOf ( [ 
					'message' => 'last name is required' 
			] ) );
			$validation->add ( [ 
					"first_name",
					"last_name" 
			], new AlphaValidator ( [ 
					"message" => [ 
							"first_name" => "First name must contain only  letters",
							"last_name " => "Last name must contain only letters" 
					] 
			] ) );
			$validation->add ( [ 
					"first_name",
					"last_name" 
			], new StringLength ( [ 
					"max" => [ 
							"first_name" => 20,
							"last_name" => 30 
					],
					"min" => [ 
							"first_name" => 4,
							"last_name" => 2 
					],
					"messageMaximum" => [ 
							"first_name" => "We don't like really long firstnames",
							"last_name" => "We don't like really long last names" 
					],
					"messageMinimum" => [ 
							"name_last" => "We don't like too short first names",
							"name_first" => "We don't like too short last names" 
					] 
			] ) );
			$validation->add ( 'email', new PresenceOf ( [ 
					'message' => 'email is required' 
			] ) );
			$validation->add ( 'email', new Email ( [ 
					'message' => 'The e-mail is not valid' 
			] ) );
			$validation->add ( 'mobile', new PresenceOf ( [ 
					'message' => 'mobile number is required' 
			] ) );
			$validation->add ( "mobile", new DigitValidator ( [ 
					"message" => "mobile number field must be numeric" 
			] ) );
			$validation->add ( 'mobile', new StringLength ( [ 
					"max" => 10,
					"min" => 2,
					"messageMaximum" => "The Mobile Number must be 10 digits long",
					"messageMinimum" => "The Mobile Number must be 10 digits long" 
			] ) );
			$validation->add ( 'occupation', new PresenceOf ( [ 
					'message' => 'occupation is required' 
			] ) );
			$validation->add ( 'company_name', new PresenceOf ( [ 
					'message' => 'company name is required']));
        	$messages = $validation->validate ( $input_data->father );
		if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
			}
		}
                if (! empty ( $input_data->mother )) {
			$validationmother = new Validation ();
			$validationmother->add ( 'first_name', new PresenceOf ( [ 
					'message' => 'first name is required' 
			] ) );
			$validationmother->add ( 'last_name', new PresenceOf ( [ 
					'message' => 'last name is required' 
			] ) );
			$validationmother->add ( [ 
					"first_name",
					"last_name" 
			], new AlphaValidator ( [ 
					"message" => [ 
							"first_name" => "First name must contain only  letters",
							"last_name " => "Last name must contain only letters" 
					] 
			] ) );
			$validationmother->add ( [ 
					"first_name",
					"last_name" 
			], new StringLength ( [ 
					"max" => [ 
							"first_name" => 20,
							"last_name" => 30 
					],
					"min" => [ 
							"first_name" => 4,
							"last_name" => 2 
					],
					"messageMaximum" => [ 
							"first_name" => "We don't like really long firstnames",
							"last_name" => "We don't like really long last names" 
					],
					"messageMinimum" => [ 
							"name_last" => "We don't like too short first names",
							"name_first" => "We don't like too short last names" 
					] 
			] ) );
			$validationmother->add ( 'email', new PresenceOf ( [ 
					'message' => 'email is required' 
			] ) );
			$validationmother->add ( 'email', new Email ( [ 
					'message' => 'The e-mail is not valid' 
			] ) );
			$validationmother->add ( 'mobile', new PresenceOf ( [ 
					'message' => 'mobile number is required' 
			] ) );
			$validationmother->add ( "mobile", new DigitValidator ( [ 
					"message" => "mobile number field must be numeric" 
			] ) );
			$validationmother->add ( 'mobile', new StringLength ( [ 
					"max" => 10,
					"min" => 10,
					"messageMaximum" => "The Mobile Number must be 10 digits long",
					"messageMinimum" => "The Mobile Number must be 10 digits long" 
			] ) );
			$validationmother->add ( 'occupation', new PresenceOf ( [ 
					'message' => 'occupation is required' 
			] ) );
			$validationmother->add ( 'company_name', new PresenceOf ( [ 
					'message' => 'company name is required']));
        	$messagesmother = $validationmother->validate ( $input_data->mother );
		if (count ( $messagesmother )) {
				foreach ( $messagesmother as $messagemother ) {
					$resultmother [] = $messagemother->getMessage ();
				}
				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$resultmother
			]);
			}
		}
		$baseurl = $this->config->baseurl;
		$token_validate = $this->tokenvalidate->getuserinfo ( $headers ['Token'], $baseurl );
		if(empty($token_validate)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Invalid User'
			] );
		}
		$username = $token_validate->user_info->email;
		$user = Users::findFirstByemail ( $username );
		foreach ( $input_data as $key => $userinfo ) {
			if ($userinfo->id) {
				$users = Users::findFirstByid ( $userinfo->id );
				if(empty($users)){
					return $this->response->setJsonContent ( [
						'status' => false,
						'message' => 'Please give the valid user id'
					] );
				}
			} else {
				$users = new Users ();
				$users->id = $this->parentsidgen->getNewId ( "users" );
			}
			$users->parent_type = $key;
			$users->user_type = 'parent';
			$users->first_name = $userinfo->first_name;
			$users->last_name = $userinfo->last_name;
			$users->email = $userinfo->email;
			$users->mobile = $userinfo->mobile;
			$users->occupation = $userinfo->occupation;
			$users->company_name = $userinfo->company_name;
			$users->created_at = date ( 'Y-m-d H:i:s' );
			$users->created_by = $user->id;
			$users->modified_at = date ( 'Y-m-d H:i:s' );
			$users->status = 1;
			$users->save ();
			if ($user->id != $users->id) {
				$parents_map = ParentsMappingProfiles::findFirstByprimary_parents_id ( $user->id );
				if ($parents_map) {
					$parents_map->secondary_parent_id = $users->id;
					$parents_map->secondary_parent_type = $key;
					$parents_map->save ();
				}
			}
			$collection = new UsersAddress ();
			$collection->id = $this->parentsidgen->getNewId ( "address" );
			$collection-> user_id = $users->id;
			$collection->address_1 = $userinfo->address_1;
			$collection->address_2 = $userinfo->address_2;
			$collection->city = $userinfo->city;
			$collection->state = $userinfo->state;
			$collection->country = $userinfo->country;
			$collection->post_code = $userinfo->post_code;
			$collection->created_at = date ( 'Y-m-d H:i:s' );
			$collection->created_by = $user->id;
			$collection->modified_at = date ( 'Y-m-d H:i:s' );
			$collection->save();
			
		}
		return $this->response->setJsonContent ( [ 
				'status' => true,
				'message' => 'Account information updated successfully' 
		] );
	}

    /**
     * This function using to NidaraParentsProfile information edit
     */
    public function update() {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('parent_type', new PresenceOf(['message' => 'Parent type is required']));
            $validation->add('first_name', new PresenceOf(['message' => 'First Name is required']));
            $validation->add('last_name', new PresenceOf(['message' => 'Last Name is required']));
            $validation->add('email', new PresenceOf(['message' => 'Email is required']));
            $validation->add('mobile', new PresenceOf(['message' => 'Mobile Number is required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
		return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
                //return $this->response->setJsonContent($result);
            else:
                $parentsprofile_update = Users::findFirstByid($id);
                if ($parentsprofile_update):
                    $parentsprofile_update->id = $input_data->id;
                    $parentsprofile_update->parent_type = $input_data->parent_type;
                    $parentsprofile_update->first_name = $input_data->first_name;
                    $parentsprofile_update->last_name = $input_data->last_name;
                    $parentsprofile_update->email = $input_data->email;
                    $parentsprofile_update->mobile = $input_data->mobile;
                    $parentsprofile_update->photo = $input_data->photo;
                    $parentsprofile_update->occupation = $input_data->occupation;
                    $parentsprofile_update->company_name = $input_data->company_name;
                    $parentsprofile_update->created_by = $id;
                    $parentsprofile_update->modified_at =date('Y-m-d H:i:s');
					
                    if ($parentsprofile_update->save()):
						$collection = UsersAddress::findFirstByuser_id($id);
						//return $this->response->setJsonContent(['status' => true, 'message' => $collection]);
						if(!$collection){
							$collection = new UsersAddress ();
							$collection->id = $this->parentsidgen->getNewId ( "address" );
							$collection-> user_id = $id;
							
						}
						$collection->address_1 = $input_data->address_1;
						$collection->address_2 = $input_data->address_2;
						$collection->city = $input_data->city;
						$collection->state = $input_data->state;
						$collection->country = $input_data->country;
						$collection->post_code = $input_data->post_code;
						$collection->created_at = date ( 'Y-m-d H:i:s' );
						$collection->created_by = $id;
						$collection->modified_at = date ( 'Y-m-d H:i:s' );
						if($collection->save()){
							return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
						} else {
							return $this->response->setJsonContent(['status' => false, 'message' => 'Address Failed']);
						}
                        
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
                    endif;
                else:
                    return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid id']);
                endif;
            endif;
        endif;
    }
	
	/**
	* This function using delete kids caregiver information
	*/
	
	public function statusupdate(){
		$input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
		if (empty($id)){
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
		}
		else {
			$parent_statusupdate = Users::findFirstByid($id);
			if($parent_statusupdate){
				$parent_statusupdate->id = $input_data->id;
				$parent_statusupdate->status = 4;
				$parent_statusupdate->act_status = 2;
				if($parent_statusupdate->save()){
					return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
				}
				else{
					return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
				}
			}
		}
	}
	

    /**
     * This function using delete kids caregiver information
     */
    public function delete() {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $parentsprofile_delete = Users::findFirstByid($id);
            if ($parentsprofile_delete):
                if ($parentsprofile_delete->delete()):
                    return $this->response->setJsonContent(['status' => true, 'Message' => 'Record has been deleted succefully ']);
                else:
                    return $this->response->setJsonContent(['status' => false, 'Message' => 'Data could not be deleted']);
                endif;
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }
	
	/**
	 * Country updation by kid id
	 */
	public function countryupdatebyuserid() {
		try {
			$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please give the token"
				] );
			}
			/* $baseurl = $this->config->baseurl;
			$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
			if ($token_check->status != 1) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Invalid User"
				] );
			} */
			$user_info=$this->tokenvalidate->getuserinfo ( $headers ['Token'], $baseurl );
			$input_data = $this->request->getJsonRawBody ();
			$id = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
			if (empty ( $id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Id is null' 
				] );
			}
			$validation = new Validation ();
			$validation->add ( 'country_of_residence', new PresenceOf ( [ 
					'message' => 'Country of residence is required' 
			] ) );
			$validation->add ( 'country_of_citizenship', new PresenceOf ( [ 
					'message' => 'Country of citizen is required' 
			] ) );
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
			
			$users = Users::findFirstByid ( $id );
			if ($users) :
				$users->country_of_residence = $input_data->country_of_residence;
				$users->country_of_citizenship = $input_data->country_of_citizenship;
				if ($users->save ()) :
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Country updated successfully' 
					] );
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Failed' 
					] );
				endif;
			 
			 else :
				return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Invalid id' 
				]);
			endif;
			} catch ( Exception $e ) {
				return $this->response->setJsonContent ( [
						'status' => false,
						'message' => 'Cannot update country details'
				] );
			}
	}
	
	/* User status update based on school */
	
	public function updateuserstatus(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user = Users::findFirstByid ( $input_data -> user_id );
		$user -> status = '2';
		if(!$user -> save()){
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "user status not update"
			] );	
		} else {
			return $this->response->setJsonContent ( [
				"status" => true,
				"message" => "user status update successfully"
			] );
		}
	}
	
	public function getcountryinfo(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$user_info=$this->tokenvalidate->getuserinfo ( $headers['Token'], $baseurl );
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Id is null'
			] );
		}
		$user = Users::findFirstByid ( $id );
		if (! empty ( $user )) {
			$country ['country_of_residence'] = $user->country_of_residence;
			$country ['country_of_citizenship'] = $user->country_of_citizenship;
			$country ['user_id'] = $user->id;
			
		}
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$country
			]);
		
	}
	
	public function getparentinfobyfilter(){
		$input_data = $this->request->getJsonRawBody ();
		//$ts = DateTime();
		$collection -> all = $input_data -> all;
		$collection -> usertype = $input_data -> usertype;
		$collection -> todate  = $input_data -> todate;
		$todate = $input_data -> todate;
		//$todate = date('Y-m-d');
		$collection -> fromdate = $input_data -> fromdate;
		$collection -> emoloyed = $input_data -> emoloyed;
		$collection -> parent_type = $input_data -> parent_type;
		$collection -> states = $input_data -> states;
		$collection -> Country = $input_data -> Country;	
		if($collection -> all){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->getQuery() ->execute();
		}
		else if($collection -> todate || $collection -> fromdate){
			/* $grating_reportin = User::findBycreated_at($collection -> todate <= $collection -> fromdate) */
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->betweenWhere("Users.created_at",($collection -> fromdate),($collection -> todate))
			->getQuery() ->execute();
		}
		else if($collection -> usertype){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.user_type",array($collection -> usertype))
			->getQuery() ->execute();
			if($collection -> parent_type){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.user_type",array($collection -> usertype))
				->inwhere("Users.parent_type",array($collection -> parent_type))
				->getQuery() ->execute();
				if($collection -> emoloyed){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.user_type",array($collection -> usertype))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->getQuery() ->execute();
					if($collection -> states){
						$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
						->inwhere("Users.user_type",array($collection -> usertype))
						->inwhere("Users.parent_type",array($collection -> parent_type))
						->inwhere("Users.occupation",array($collection -> emoloyed))
						->inwhere("Users.country_of_state",array($collection -> states))
						->getQuery() ->execute();
					}
					else if($collection -> states){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.user_type",array($collection -> usertype))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.country_of_state",array($collection -> states))
					->getQuery() ->execute();
					}
					else if($collection -> Country){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.country_of_residence",array($collection -> Country))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.user_type",array($collection -> usertype))
					->getQuery() ->execute();
						if($collection -> states){
						$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
						->inwhere("Users.user_type",array($collection -> usertype))
						->inwhere("Users.parent_type",array($collection -> parent_type))
						->inwhere("Users.country_of_state",array($collection -> states))
						->inwhere("Users.country_of_residence",array($collection -> Country))
						->inwhere("Users.occupation",array($collection -> emoloyed))
						->getQuery() ->execute();
						}
					}
				}
				else if($collection -> states){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.user_type",array($collection -> usertype))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->inwhere("Users.country_of_state",array($collection -> states))
					->getQuery() ->execute();
				}
				else if($collection -> Country){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->inwhere("Users.parent_type",array($collection -> parent_type))
				->inwhere("Users.user_type",array($collection -> usertype))
				->getQuery() ->execute();
					if($collection -> states){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.user_type",array($collection -> usertype))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->inwhere("Users.country_of_state",array($collection -> states))
					->inwhere("Users.country_of_residence",array($collection -> Country))
					->getQuery() ->execute();
					}
				}
			}
			else if($collection -> emoloyed){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.user_type",array($collection -> usertype))
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->getQuery() ->execute();
				 if($collection -> Country){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->inwhere("Users.user_type",array($collection -> usertype))
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->getQuery() ->execute();
					if($collection -> states){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.user_type",array($collection -> usertype))
					->inwhere("Users.country_of_state",array($collection -> states))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.country_of_residence",array($collection -> Country))
					->getQuery() ->execute();
					}
				}
				else if($collection -> states){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.user_type",array($collection -> usertype))
				->inwhere("Users.country_of_state",array($collection -> states))
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->getQuery() ->execute();
				}
			}
			else if($collection -> Country){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.country_of_residence",array($collection -> Country))
			->inwhere("Users.user_type",array($collection -> usertype))
			->getQuery() ->execute();
				if($collection -> states){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.user_type",array($collection -> usertype))
				->inwhere("Users.country_of_state",array($collection -> states))
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->getQuery() ->execute();
				}
			}
			else if($collection -> states){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.user_type",array($collection -> usertype))
			->inwhere("Users.country_of_state",array($collection -> states))
			->getQuery() ->execute();
			}
		}
		else if($collection -> parent_type){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.parent_type",array($collection -> parent_type))
			->getQuery() ->execute();
			if($collection -> Country){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->inwhere("Users.parent_type",array($collection -> parent_type))
				->getQuery() ->execute();
				if($collection -> states){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_state",array($collection -> states))
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->inwhere("Users.parent_type",array($collection -> parent_type))
				->getQuery() ->execute();
				}
			}
			else if($collection -> states){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_state",array($collection -> states))
				->inwhere("Users.parent_type",array($collection -> parent_type))
				->getQuery() ->execute();
			}
			else if($collection -> emoloyed){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->getQuery() ->execute();
				if($collection -> Country){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.country_of_residence",array($collection -> Country))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->getQuery() ->execute();
					if($collection -> states){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.country_of_state",array($collection -> states))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.country_of_residence",array($collection -> Country))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->getQuery() ->execute();
					}
				}
				else if($collection -> states){
					$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
					->inwhere("Users.country_of_state",array($collection -> states))
					->inwhere("Users.occupation",array($collection -> emoloyed))
					->inwhere("Users.parent_type",array($collection -> parent_type))
					->getQuery() ->execute();
				}
			}
		}
		else if($collection -> emoloyed){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->getQuery() ->execute();
			if($collection -> Country){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->getQuery() ->execute();
				if($collection -> states){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_state",array($collection -> states))
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->inwhere("Users.country_of_residence",array($collection -> Country))
				->getQuery() ->execute();
				}
			}
			else if($collection -> states){
				$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
				->inwhere("Users.country_of_state",array($collection -> states))
				->inwhere("Users.occupation",array($collection -> emoloyed))
				->getQuery() ->execute();
			}
		}
		else if($collection -> Country){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.country_of_residence",array($collection -> Country))
			->getQuery() ->execute();
			if($collection -> states){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.country_of_state",array($collection -> states))
			->inwhere("Users.country_of_residence",array($collection -> Country))
			->getQuery() ->execute();
			}
		}
		else if($collection -> states){
			$grating_reportin = $this->modelsManager->createBuilder ()->from('Users')
			->inwhere("Users.country_of_state",array($collection -> states))
			->getQuery() ->execute();
		}
		
		$filterarray = array();
		foreach($grating_reportin as $value){
			$filterarray[] = $value;
		}
		/* return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$filterarray
			]); */
			$chunked_array = array_chunk ( $filterarray, 15 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			$games ['games'] = $keyed_array;
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $games
			] ); 
	}
}
