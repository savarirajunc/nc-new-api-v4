<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class KidparentsmapController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$kidparentsmap = KidParentsMap::find ();
		if ($kidparentsmap) :
			
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' =>$kidparentsmap 
			] );
			
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faield' 
			] );
		endif;
	}
	/*
	 * Fetch Record from database based on ID :-
	 */
	public function getbyid() {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		 else :
			$kidparentsmap_getbyid = KidParentsMap::findFirstByid ( $id );
			if ($kidparentsmap_getbyid) :

				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' =>$kidparentsmap_getbyid 
			] );
				
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to create KidParentsMap information
	 */
	public function create() {
		$input_data = $this->request->getJsonRawBody ();
		
		/**
		 * This object using valitaion
		 */
		$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
				'message' => 'nidara_kid_profile_id is required' 
		] ) );
		$validation->add ( 'users_id', new PresenceOf ( [ 
				'message' => 'users_id is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$collection = new KidParentsMap ();
			$collection->id = $input_data->id;
			$kidparentsmap_create->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
			$kidparentsmap_create->users_id = $input_data->users_id;
			if ($kidparentsmap_create->save ()) :
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'succefully' 
				] );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to KidParentsMap information edit
	 */
	public function update() {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null' 
			] );
		 else :
			$validation = new Validation ();
			
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'nidara_kid_profile_idis required' 
			] ) );
			$validation->add ( 'users_id', new PresenceOf ( [ 
					'message' => 'users_idis required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach
				;
				return $this->response->setJsonContent ( $result );
			 else :
				$kidparentsmap_update = KidParentsMap::findFirstByid ( $id );
				if ($kidparentsmap_update) :
					
					$kidparentsmap_update->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$kidparentsmap_update->users_id = $input_data->users_id;
					if ($kidparentsmap_update->save ()) :
						return $this->response->setJsonContent ( [ 
								'status' => true,
								'message' => 'succefully' 
						] );
					 else :
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Failed' 
						] );
					endif;
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Invalid id' 
					] );
				endif;
			endif;
		endif;
	}
	/**
	 * This function using delete kids caregiver information
	 */
	public function delete() {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null' 
			] );
		 else :
			$kidparentsmap_delete = KidParentsMap::findFirstByid ( $id );
			if ($kidparentsmap_delete) :
				if ($kidparentsmap_delete->delete ()) :
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'Message' => 'Record has been deleted succefully ' 
					] );
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Data could not be deleted' 
					] );
				endif;
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'ID doesn\'t' 
				] );
			endif;
		endif;
	}
	
	
	
	
	public function kidinfo_doctor() {
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		$users_id = $input_data->user_id;
		$getby_userid = $kidifo = $this->modelsManager->createBuilder ()->columns ( array (
				'KidParentsMap.nidara_kid_profile_id as nidara_kid_profile_id',
			))->from('KidParentsMap')
			->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
			->inwhere("KidParentsMap.users_id",array($users_id))
			->inwhere("NidaraKidProfile.status",array(1))
			->getQuery ()->execute ();
		if ($getby_userid) {
			$kidaprofiles = array ();
			foreach ( $getby_userid as $key => $value ) {
				$kid_id = $value ['nidara_kid_profile_id'];
				$kid_profile = NidaraKidProfile::findFirst ( $kid_id )->toArray ();
				$kid_profile['nidara_kid_profile_id']=$kid_profile['id'];
				$kid_guide = DailyRoutineAttendance::findFirstByattendanceDate(date ('Y-m-d'));
				if(empty($kid_guide)){
					$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
						'COUNT(DailyRoutineAttendance.id) as days',
					))->from('DailyRoutineAttendance')
					->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($kid_profile['nidara_kid_profile_id']))
					->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
					->getQuery ()->execute ();
					foreach($kid_total_day as $value){
						$kid_info['days'] = $value->days + 1;
					}
				}
				else{
					$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
						'COUNT(DailyRoutineAttendance.id) as days',
					))->from('DailyRoutineAttendance')
					->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($kid_profile['nidara_kid_profile_id']))
					->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
					->getQuery ()->execute ();
					foreach($kid_total_day as $value){
						$kid_info['days'] = $value->days;
					}
					
				}
				$kid_profile['no_days'] = $kid_info['days'];
				unset($kid_profile['id']);
				if($key == 0){
					$kid_profile['is_default_kid']=1;
				}else{
					$kid_profile['is_default_kid']=0;
				}
				$kid_guide = KidGuidedLearningMap::findFirstBynidara_kid_profile_id ( $kid_id );
				if(!empty($kid_guide)){
				  $kid_profile['package_id']=$kid_guide->id;
				  $guided=GuidedLearning::findFirstByid($kid_guide->guided_learning_id);
			          $kid_profile['package_name']=$guided->learning_model;
				}
				$kid_dailyroutine = DailyRoutineStatus::findFirstBynidara_kid_profile_id( $kid_id );
				if(empty($kid_dailyroutine)){
					$kid_profile['dailyroutine_status'] = 0;
				}
				else{
					$kid_profile['dailyroutine_status'] = $kid_dailyroutine -> status;
				}
				$kidaprofiles [] = $kid_profile;
			}
			
			return $this->response->setJsonContent ( [ 
					'status'=> true,
					'data' =>$kidaprofiles
			] );
			
		} else {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Data not found' 
			] );
		}
	}
	
	
	
	
		
		
	
		public function kidinfo() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		$authentication = new AuthenticationController ();
		$validatetoken = $authentication->validatetoken ( $headers ['Token'] );
		$token_users = TokenUsers::findFirstBytoken ( $headers ['Token'] );
		// $baseurl = $this->config->baseurl;
		// $token_validate = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
		// if ($token_validate->status != 1) {
		// 		return $this->response->setJsonContent ( [ 
		// 				"status" => false,
		// 				"message" => "Invalid User" 
		// 		] );
		// 	}
		$users_id = $token_users -> users_id;
		$getby_userid  = $this->modelsManager->createBuilder ()->columns ( array (
				'KidParentsMap.nidara_kid_profile_id as nidara_kid_profile_id',
			))->from('KidParentsMap')
			->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
			->inwhere("KidParentsMap.users_id",array($users_id))
			->inwhere("NidaraKidProfile.status",array(1,2,6))
			->orderBy("KidParentsMap.id")
			->getQuery ()->execute ();
			
		if ($getby_userid) {
			$kidaprofiles = array ();
			foreach ( $getby_userid as $key => $value ) {
				$kid_id = $value ['nidara_kid_profile_id'];
				$kid_profile = NidaraKidProfile::findFirst ( $kid_id )->toArray ();
				$kid_profile['nidara_kid_profile_id']=$kid_profile['id'];
				$kid_guide = DailyRoutineAttendance::findFirstByattendanceDate(date ('Y-m-d'));
				if(empty($kid_guide)){
					$freetrial = NcProductFreetrail::findFirstBykid_id($kid_profile['nidara_kid_profile_id']);
					$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
						'DailyRoutineAttendance.id as id',
					))->from('DailyRoutineAttendance')
					->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($kid_profile['nidara_kid_profile_id']))
					->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
					->getQuery ()->execute ();
					$gamecheck = 1;
					$contofday = count($kid_total_day);
					if($contofday == 1){
						$dayid = $contofday + 1; 
					} else {
						$dayid = $contofday;
					}
					$kidprofile = NidaraKidProfile::findFirstByid ( $kid_profile['nidara_kid_profile_id'] );
					if($kidprofile -> test_kid_status == 0){
						$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
							'GuidedLearningDayGameMap.game_id as games_id',
							'GuidedLearningDayGameMap.day_id as day_id',
							'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_id',
						))->from("GuidedLearningDayGameMap")
						->where('GuidedLearningDayGameMap.day_id < ' . $dayid . '')
						->inwhere("GuidedLearningDayGameMap.day_guided_learning_id", array($kidprofile -> grade))
						->groupBy('GuidedLearningDayGameMap.game_id')
						->getQuery()->execute ();
						foreach($guidedlearning_id as $value){
							$game_getses = $this->modelsManager->createBuilder()->columns(array(
								'KidsGamesStatus.session_id as session_id',
								'KidsGamesStatus.current_status as current_status',
								'KidsGamesStatus.game_id as game_id',
							))->from('KidsGamesStatus')
							->inwhere('KidsGamesStatus.game_id', array(
								 $value->games_id
							))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
								$kidprofile -> id
							))->inwhere('KidsGamesStatus.current_status', array(
								1
							))->getQuery()->execute();
							if(count($game_getses) <= 0){
								$gamecheck = 0;
							}
						}
					}
					$kid_info['gamelist'] = $guidedlearning_id;
					$freetrial = NcProductFreetrail::findFirstBykid_id($kid_profile['nidara_kid_profile_id']);
					if($gamecheck == 0){
						if($contofday > 0){
							$kid_info['days'] = $contofday - 1;
						} else {
							$kid_info['days'] = $contofday;
						}
						$dayidvalue = $contofday - 1;
					} else {
						$kid_info['days'] = $contofday;
						$dayidvalue = $contofday;
					}
					if(!$freetrial){
						$kid_info['freetrial'] = 0;
					} else {
						if($dayidvalue > 5){
							$kid_info['freetrial'] = 2;
						} else {
							$kid_info['freetrial'] = 1;
						}
					}
				}
				else{
					$kidprofile = NidaraKidProfile::findFirstByid ( $kid_profile['nidara_kid_profile_id'] );
					if($kidprofile -> test_kid_status == 0){
						$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
							'DailyRoutineAttendance.id as id',
						))->from('DailyRoutineAttendance')
						->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($kid_profile['nidara_kid_profile_id']))
						->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
						->getQuery ()->execute ();
					} else {
						$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
							'DailyRoutineAttendance.id as id',
						))->from('DailyRoutineAttendance')
						->where('DailyRoutineAttendance.attendanceDate < "'. date ('Y-m-d') .'"')
						->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($kid_profile['nidara_kid_profile_id']))
						->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
						->getQuery ()->execute ();
					}
					$gamecheck = 1;
					$contofday = count($kid_total_day);
					if($contofday == 1){
						$dayid = $contofday + 1; 
					} else {
						$dayid = $contofday;
					}
					$guidedlearning_id = '';
					if($kidprofile -> test_kid_status == 0){
						$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
							'GuidedLearningDayGameMap.game_id as games_id',
							'GuidedLearningDayGameMap.day_id as day_id',
							'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_id',
						))->from("GuidedLearningDayGameMap")
						->where('GuidedLearningDayGameMap.day_id < ' . $dayid . '')
						->inwhere("GuidedLearningDayGameMap.day_guided_learning_id", array($kidprofile -> grade))
						->groupBy('GuidedLearningDayGameMap.game_id')
						->getQuery()->execute ();
						foreach($guidedlearning_id as $value){
							$game_getses = $this->modelsManager->createBuilder()->columns(array(
								'KidsGamesStatus.session_id as session_id',
								'KidsGamesStatus.current_status as current_status',
								'KidsGamesStatus.game_id as game_id',
							))->from('KidsGamesStatus')
							->inwhere('KidsGamesStatus.game_id', array(
								 $value->games_id
							))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
								$kidprofile->id
							))->inwhere('KidsGamesStatus.current_status', array(
								1
							))->getQuery()->execute();
							if(count($game_getses) <= 0){
								$gamecheck = 0;
							}
						}
						
					}
					$kid_info['gamelist'] = $guidedlearning_id;
					$freetrial = NcProductFreetrail::findFirstBykid_id($kid_profile['nidara_kid_profile_id']);
					if($gamecheck == 0){
						if($contofday > 0){
							$kid_info['days'] = $contofday - 1;
						} else {
							$kid_info['days'] = $contofday;
						}
						$dayidvalue = $contofday - 1;
					} else {
						if($kidprofile -> test_kid_status == 0){
							$kid_info['days'] = $contofday - 1;
						} else {
							$kid_info['days'] = $contofday;
						}
						$dayidvalue = $contofday;
					}
					if(!$freetrial){
						$kid_info['freetrial'] = 0;
					} else {
						if($dayidvalue > 5){
							$kid_info['freetrial'] = 2;
						} else {
							$kid_info['freetrial'] = 1;
						}
					}
					
				}
				$kid_profile['no_days'] = $kid_info['days'];
				$kid_profile['dayid'] = $dayid;
				$kid_profile['gamelist'] =$kid_info['gamelist'];
				$kid_profile['free_trial'] = $kid_info['freetrial'];
				unset($kid_profile['id']);
				if($key == 0){
					$kid_profile['is_default_kid']=1;
				}else{
					$kid_profile['is_default_kid']=0;
				}
				$kid_guide = KidGuidedLearningMap::findFirstBynidara_kid_profile_id ( $kid_id );
				if(!empty($kid_guide)){
				  $kid_profile['package_id']=$kid_guide->id;
				  $guided=GuidedLearning::findFirstByid($kid_guide->guided_learning_id);
			          $kid_profile['package_name']=$guided->learning_model;
				}
				$kid_dailyroutine = DailyRoutineStatus::findFirstBynidara_kid_profile_id( $kid_id );
				if(empty($kid_dailyroutine)){
					$kid_profile['dailyroutine_status'] = 0;
				}
				else{
					$kid_profile['dailyroutine_status'] = $kid_dailyroutine -> status;
				}
				$kidaprofiles [] = $kid_profile;
			}
			
			return $this->response->setJsonContent ( [ 
					'status'=> true,
					'data' =>$kidaprofiles
			] );
			
		} else {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Data not found' 
			] );
		}
	}
	
	
	
	public function dailyroutine_status(){
		$input_data = $this->request->getJsonRawBody ();
		$collection = new DailyRoutineStatus ();
		$collection->id = $this->dailyroutineidgen->getNewId ( "daily_routine" );
		$collection->nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
		$collection->status = 1;
		$collection->created_at = date ( 'Y-m-d H:i:s' );
		if($collection->save()){
			return $this->response->setJsonContent(['status' => true, 'message' => 'successfully']);
		}
		else{
			return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
		}
	}
	
	public function dailyroutine_status_update(){
		$input_data = $this->request->getJsonRawBody ();
		$collection = DailyRoutineStatus::findFirstBynidara_kid_profile_id ($input_data -> nidara_kid_profile_id);
		if(!$collection){
			$collection = new DailyRoutineStatus ();
			$collection->id = $this->dailyroutineidgen->getNewId ( "daily_routine" );
			$collection->nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
		}
		$collection->status = 2;
		$collection->created_at = date ( 'Y-m-d H:i:s' );
		if($collection->save()){
			return $this->response->setJsonContent(['status' => true, 'message' => 'successfully']);
		}
		else{
			return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
		}
	}
	
	
	public function getkidinfobyuserid(){
			$input_data = $this->request->getJsonRawBody ();
			
			 $getby_userid = $this->modelsManager->createBuilder ()->columns ( array (
				'NidaraKidProfile.id as id',
				'NidaraKidProfile.first_name as first_name',
				'NidaraKidProfile.middle_name as middle_name',
				'NidaraKidProfile.last_name as last_name',
				'NidaraKidProfile.date_of_birth as date_of_birth',
				'NidaraKidProfile.age as age',
				'NidaraKidProfile.birthterm as birthterm',
				'NidaraKidProfile.birthweek as birthweek',
				'NidaraKidProfile.gender as gender',
				'NidaraKidProfile.height as height',
				'NidaraKidProfile.weight as weight',
				'NidaraKidProfile.grade as grade',
				'NidaraKidProfile.child_photo as child_photo',
				'NidaraKidProfile.child_avatar as child_avatar',
				'NidaraKidProfile.created_at as created_at',
				'NidaraKidProfile.expiry_date as expiry_date',
				'NidaraKidProfile.created_by as created_by',
				'NidaraKidProfile.modified_at as modified_at',
				'NidaraKidProfile.free_trial as free_trial',
				'NidaraKidProfile.choose_time as choose_time',
				'NidaraKidProfile.board_of_education as board_of_education',
				'NidaraKidProfile.status as status',
				'NidaraKidProfile.order_id as order_id',
				'NidaraKidProfile.cancel_subscription as cancel_subscription',
				'NidaraKidProfile.relationship_to_child as relationship_to_child',
			))->from('KidParentsMap')
			->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
			->inwhere("KidParentsMap.users_id",array($input_data -> user_id))
			->inwhere("NidaraKidProfile.status",array(2,3))
			->orderBy("KidParentsMap.id")
			->getQuery ()->execute ();
			 $getchildarray = array();
			foreach($getby_userid as $value){
				// $kid_profile = NidaraKidProfile::findFirstByid($value -> id);
				$data['childInfo'] = $kid_profile;
				$data['id'] = $value -> id;
				$data['first_name'] = $value -> first_name;	
				$data['middle_name'] = $value -> middle_name;	
				$data['last_name'] = $value -> last_name;	
				$data['date_of_birth'] = $value -> date_of_birth;	
				$data['age'] = $value -> age;	
				$data['birthterm'] = $value -> birthterm;	
				$data['birthweek'] = $value -> birthweek;	
				$data['gender'] = $value -> gender;	
				$data['height'] = $value -> height;	
				$data['weight'] = $value -> weight;
				$data['grade'] = $value -> grade;	
				$data['child_photo'] = $value -> child_photo;	
				$data['child_avatar'] = $value -> child_avatar;	
				$data['created_at'] = $value -> created_at;	
				$data['expiry_date'] = $value -> expiry_date;	
				$data['created_by'] = $value -> created_by;	
				$data['modified_at'] = $value -> modified_at;	
				$data['board_of_education'] = $value -> board_of_education;	
				$data['status'] = $value -> status;
				$data['imageerror'] = 0;	
				$data['free_trial'] = $value -> free_trial;	
				$data['choose_time'] = $value -> choose_time;
				$data['order_id'] = $value -> order_id;	
				$data['cancel_subscription'] = $value -> cancel_subscription;	
				$data['relationship_to_child'] = $value -> relationship_to_child;
				$getchildarray[] = $data;
			} 
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $getchildarray
			] );
			
		} 
		
			
}
