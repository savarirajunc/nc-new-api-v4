<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class DoctorcodeController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	public function getparentdata(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		$users_id = $input_data->user_id;
		$get_parent = $this->modelsManager->createBuilder ()->columns ( array (
			'Users.id as ids',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.mobile as mobile',
		))->from('DoctorComplete')
		->leftjoin('DoctorParentMap','DoctorComplete.user_id = DoctorParentMap.user_id')
		->leftjoin('DoctorCode','DoctorParentMap.doctor_code = DoctorCode.doctor_code')
		->leftjoin('Users','Users.id = DoctorParentMap.user_id')
		->inwhere('DoctorCode.user_id',array($users_id))
		->getQuery ()->execute ();
		$parent_array = array();
		if(count($get_parent) == 0){
			$user_info = $this->modelsManager->createBuilder ()->columns ( array (
				'Users.id as id',
				'Users.first_name as first_name',
				'Users.last_name as last_name',
				'Users.mobile as mobile',
			))->from('DoctorCode')
			->leftjoin('DoctorParentMap','DoctorParentMap.doctor_code = DoctorCode.doctor_code')
			->leftjoin('Users','Users.id = DoctorParentMap.user_id')
			->leftjoin('KidParentsMap','KidParentsMap.users_id = Users.id')
			->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
			->inwhere('DoctorCode.user_id',array($users_id))
			->inwhere('NidaraKidProfile.status',array(1))
			->getQuery ()->execute ();
			$userinfo_array = array();
			foreach($user_info as $value){
					$user_data['id'] = $value->id;
					$user_data['first_name'] = $value->first_name;
					$user_data['last_name'] = $value->last_name;
					$user_data['mobile'] = $value->mobile;
				$userinfo_array[] = $user_data;
			}
		}
		else{
			foreach($get_parent as $value2){
				$user_info = $this->modelsManager->createBuilder ()->columns ( array (
					'Users.id as id',
					'Users.first_name as first_name',
					'Users.last_name as last_name',
					'Users.mobile as mobile',
				))->from('DoctorCode')
				->leftjoin('DoctorParentMap','DoctorParentMap.doctor_code = DoctorCode.doctor_code')
				->leftjoin('Users','Users.id = DoctorParentMap.user_id')
				->leftjoin('KidParentsMap','KidParentsMap.users_id = Users.id')
				->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
				->inwhere('DoctorCode.user_id',array($users_id))
				->inwhere('NidaraKidProfile.status',array(1))
				->getQuery ()->execute ();
				$userinfo_array = array();
				foreach($user_info as $value){
					if($value2->ids != $value->id){
						$user_data['id'] = $value->id;
						$user_data['first_name'] = $value->first_name;
						$user_data['last_name'] = $value->last_name;
						$user_data['mobile'] = $value->mobile;
					}
					else{
						$user_data['mass'] = "There are no new patients.";
					}
					$userinfo_array[] = $user_data;
				}
				
				$user_data2['id'] = $value2->ids;
				$user_data2['first_name'] = $value2->first_name;
				$user_data2['last_name'] = $value2->last_name;
				$user_data2['mobile'] = $value2->mobile;
				$parent_array[] = $user_data2;
			}
		}
		
		
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'data' => $userinfo_array,
			'parent' => $parent_array,
		] );
	}
	
	public function getdoctorlist(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		else{
			$getdoctorlist = $this->modelsManager->createBuilder ()->columns ( array (
				'Users.id as id',
				'Users.first_name as first_name',
				'Users.last_name as last_name',
				'Users.email as email',
				'Users.mobile as mobile',
				'Users.status as status',
				'DoctorCode.doctor_code as doctor_code'
			))->from('DoctorCode')
			->leftjoin('Users','DoctorCode.user_id = Users.id')
			->getQuery ()->execute ();
			$parentarray = array();
			foreach($getdoctorlist as $value){
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
		}
	}
	
	public function parentcomplete(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		else{
			$collection = DoctorComplete::findFirstByuser_id($input_data->user_id);
			if(!$collection){
				$collection = new DoctorComplete ();
				$collection -> user_id = $input_data->user_id;
				$collection -> created_at =  date('Y-m-d H:i:s');
				if(!$collection->save()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'The Data is not saved',
						'date' => $collection
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'The Data is saved'
					] );
				}
			}
			else{
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'This User is Complete',
						'date' => $collection
					] );
			}
		}
	}
	
	public function doctorvisiterlist(){
		$input_data = $this->request->getJsonRawBody ();
		/* if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		} */
		$vist_value = $input_data->visitDetails;
		$vist_cound = 0;
//		foreach($vist_value as $value){
		foreach($vist_value as $value){
			$vist_cound = $vist_cound + 1;
			$date = $value->year .'-'. $value->month .'-'. $value->date;
			$time = $value->hours .':'. $value->minutes .':00';
/* 			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => date('Y-m-d',$date)
				] ); */
			$collecton = new DoctorVisit ();
			$collecton->child_id = $input_data->child_id;
			$collecton->visit_no = $vist_cound;
			$collecton->visit_date = $date;
			$collecton->time = $time;
			if(!$collecton->save()){
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'The Data is not saved',
					'date' => $collecton
				] );
			}
			// $vist_cound = +1;
		}
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'The Data is saved'
				] );
		
	}
	
	
	
	public function getvisitlist(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		
		$collection = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.child_id as child_id',
			'DoctorVisit.time as time',
			'DoctorVisit.visit_no as visit_no',
			'NidaraKidProfile.first_name as first_name',
			'NidaraKidProfile.last_name as last_name',
			'NidaraKidProfile.child_photo as child_photo',
		))->from('DoctorVisit')
		->leftjoin('NidaraKidProfile','NidaraKidProfile.id = DoctorVisit.child_id')
		->leftjoin('KidParentsMap','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
		->leftjoin('Users','KidParentsMap.users_id = Users.id')
		->leftjoin('DoctorParentMap','DoctorParentMap.user_id = Users.id')
		->leftjoin('DoctorCode','DoctorParentMap.doctor_code = DoctorCode.doctor_code')
		->inwhere('DoctorCode.user_id',array($input_data->user_id))
		->inwhere('DoctorVisit.visit_date',array(date('Y-m-d')))
		->getQuery ()->execute ();
		$child_data_main = array();
		foreach($collection as $value){
			$child_data['child_id'] = $value->child_id;
			$child_data['time'] = $value->time;
			$child_data['first_name'] = $value->first_name;
			$child_data['visit_no'] = $value->visit_no;
			$child_data['last_name'] = $value->last_name;
			$child_data['child_photo'] = $value->child_photo;
			$child_data_main[] = $child_data;
		}
		
		return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $child_data_main
				] );
		
	}
	
	
	public function getvisitlistbychildid(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		else{
			$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
				'DoctorVisit.visit_date as visit_date',
				'DoctorVisit.visit_no as visit_no',
				'DoctorVisit.visit_date as visit_date',
			))->from('DoctorVisit')
			->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $vist_date
				] );
		}
	}
	
	public function addhospitaladdress(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		else if(empty($input_data->id)){
			if($input_data->clinictype == 'clinic'){
				$address = new ClinicAddress();
				$address -> clinic_name = $input_data->hospital_name;
				$address -> practicing_time = $input_data->fromtime;
				$address -> practicing_time_to = $input_data->totime;
				$address -> street_address_1 = $input_data->address_1;
				$address -> street_address_2 = $input_data->address_2;
				$address -> city = $input_data->city;
				$address -> state = $input_data->state;
				$address -> country = $input_data->country;
				$address -> pin_code = $input_data->postcode;
				$address -> secretary_name = $input_data->first_name;
				$address -> secretary_email = $input_data->email;
				$address -> secretary_mobile_no = $input_data->mobile;
				$address -> any_software_for_appointment = $input_data->software;
				$address -> user_id = $input_data->user_id;
				if(!$address -> save()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'The data is not save',
						'data' => $address
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'The data is saved' 
					] );
				}
				
			}
			else{
				$address = new HospitalAddress();
				$address -> hospital_name = $input_data->hospital_name;
				$address -> practicing_time = $input_data->fromtime;
				$address -> practicing_time_to = $input_data->totime;
				$address -> street_address_1 = $input_data->address_1;
				$address -> street_address_2 = $input_data->address_2;
				$address -> city = $input_data->city;
				$address -> state = $input_data->state;
				$address -> country = $input_data->country;
				$address -> pin_code = $input_data->postcode;
				$address -> secretary_name = $input_data->first_name;
				$address -> secretary_email = $input_data->email;
				$address -> secretary_mobile_no = $input_data->mobile;
				$address -> any_software_for_appointment = $input_data->software;
				$address -> user_id = $input_data->user_id;
				if(!$address -> save()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'The data is not save',
						'data' => $address
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'The data is saved' 
					] );
				}
			}
		}
		else{
			if($input_data->clinictype == 'clinic'){
				$address = ClinicAddress::findFirstByid($input_data->id);
				$address -> clinic_name = $input_data->clinic_name;
				$address -> practicing_time = $input_data->fromtime;
				$address -> practicing_time_to = $input_data->totime;
				$address -> street_address_1 = $input_data->street_address_1;
				$address -> street_address_2 = $input_data->street_address_2;
				$address -> city = $input_data->city;
				$address -> state = $input_data->state;
				$address -> country = $input_data->country;
				$address -> pin_code = $input_data->pin_code;
				$address -> secretary_name = $input_data->secretary_name;
				$address -> secretary_email = $input_data->secretary_email;
				$address -> secretary_mobile_no = $input_data->secretary_mobile_no;
				$address -> any_software_for_appointment = $input_data->any_software_for_appointment;
				if(!$address -> save()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'The data is not save',
						'data' => $address
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'The data is saved' 
					] );
				}
				
			}
			else{
				$address =  HospitalAddress::findFirstByid($input_data->id);
				$address -> hospital_name = $input_data->hospital_name;
				$address -> practicing_time = $input_data->fromtime;
				$address -> practicing_time_to = $input_data->totime;
				$address -> street_address_1 = $input_data->street_address_1;
				$address -> street_address_2 = $input_data->street_address_2;
				$address -> city = $input_data->city;
				$address -> state = $input_data->state;
				$address -> country = $input_data->country;
				$address -> pin_code = $input_data->pin_code;
				$address -> secretary_name = $input_data->secretary_name;
				$address -> secretary_email = $input_data->secretary_email;
				$address -> secretary_mobile_no = $input_data->secretary_mobile_no;
				$address -> any_software_for_appointment = $input_data->any_software_for_appointment;
				if(!$address -> save()){
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'The data is not save',
						'data' => $address
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'The data is saved' 
					] );
				}
			}
		}
	}
}

