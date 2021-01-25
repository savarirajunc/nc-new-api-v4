<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class GuidedlearningscheduleController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$guidedlearningschedule = GuidedLearningSchedule::find ();
		if ($guidedlearningschedule) :
			return Json_encode ( $guidedlearningschedule );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Failed' 
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
			$guidedlearning_getby_id = GuidedLearningSchedule::findFirstByid ( $id );
			if ($guidedlearning_getby_id) :
				return Json_encode ( $guidedlearning_getby_id );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	
	/**
	 * This function using to create GuidedLearningSchedule information
	 */
	public function create() {
		$input_data = $this->request->getJsonRawBody ();
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		$validation = new Validation ();
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		}
		 else {
			 $guidedlearning_create = $input_data -> dayGamaGroup;
			 
			 foreach($guidedlearning_create as $value){
				 $collection = new GuidedLearningDayGameMap ();
				 $collection->id = $this->guidedlearningidgen->getNewId ("guided_learning_gmae");
				 $collection->day_id = $input_data->day_id;
				 $collection->day_guided_learning_id = $input_data->grade_id;
				 $collection->framework_id = $value->framework_id;
				 $collection->subject_id = $value->subject_id;
				 $collection->game_id = $value->game_id;
				 $collection->created_at = date ( 'Y-m-d H:i:s' );
				 if(!$collection->save()){
					 return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
					] );
				 }
		 }
		 return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		 }
	}
	
	/**
	 * This function using to GuidedLearningSchedule information edit
	 */
	public function update() {
		$input_data = $this->request->getJsonRawBody ();
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		else {
			 $guidedlearning_create = $input_data -> dayGamaGroup;
			 
			 foreach($guidedlearning_create as $value){
				 $collection = GuidedLearningDayGameMap::findFirstByid ($value->id);
				 if(!$collection){
					 $collection = new GuidedLearningDayGameMap ();
					 $collection->id = $this->guidedlearningidgen->getNewId ("guided_learning_gmae");
					 $collection->day_id = $input_data->day_id;
					 $collection->day_guided_learning_id = $input_data->grade_id;
					 $collection->framework_id = $value->framework_id;
					 $collection->subject_id = $value->subject_id;
					 $collection->game_id = $value->game_id;
					 $collection->created_at = date ( 'Y-m-d H:i:s' );
				 }
				 $collection->framework_id = $value->framework_id;
				 $collection->subject_id = $value->subject_id;
				 $collection->game_id = $value->game_id;
				 if(!$collection->save()){
					 return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
					] );
				 }
		 }
		 return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		 }
		
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
			$guidedlearning_delete = GuidedLearningSchedule::findFirstByid ( $id );
			if ($guidedlearning_delete) :
				if ($guidedlearning_delete->delete ()) :
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
	
	
	
	/*
	
	* @return string
	
	*/
		public function getguidedlearning(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		/*  */
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->kid_id ) ? $input_data->kid_id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the kid id' 
			] );
		}
		else {
			$guidedlearning = $this->modelsManager->createBuilder ()->columns ( array (
				'KidGuidedLearningMap.guided_learning_id as guided_learning_id',
				'NidaraKidProfile.created_at as created_at',
			))->from('KidGuidedLearningMap')
			->leftjoin('NidaraKidProfile','KidGuidedLearningMap.nidara_kid_profile_id = NidaraKidProfile.id')
			->inwhere("KidGuidedLearningMap.nidara_kid_profile_id",array($id))
			->getQuery ()->execute ();
			 
			 $guidedlearningarray = array();
			 foreach($guidedlearning as $value){
				 $guided_learning_id['guided_learning_id'] = $value->guided_learning_id;
 				 /* $days = strtotime(date("M d Y ")); 
// 				 $days = date("Y-m-d"); 
//				 $guided_learning_id['no_days'] = floor(($days - strtotime($value->created_at))/86400);
//				 $guided_learning_id['no_days'] = date_diff(date("Y-m-d",strtotime($value->created_at)),$days);
				if($days === strtotime($value->created_at)){
					$guided_learning_id['no_days'] = 1;
				}
				else{
					$guided_learning_id['no_days'] = (date("d",($days - (strtotime($value->created_at)))));
				} */
				$kid_guide = DailyRoutineAttendance::findFirstByattendanceDate(date ('Y-m-d'));
				if(empty($kid_guide)){
					$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
						'DailyRoutineAttendance.id as id',
					))->from('DailyRoutineAttendance')
					->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($id))
					->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
					->getQuery ()->execute ();
					$gamecheck = 1;
					$contofday = count($kid_total_day);
					if($contofday == 1){
						$dayid = $contofday + 1; 
					} else {
						$dayid = $contofday;
					}
					$kidprofile = NidaraKidProfile::findFirstByid ($id);
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
								$input_data->kid_id
							))->inwhere('KidsGamesStatus.current_status', array(
								1
							))->getQuery()->execute();
							if(count($game_getses) <= 0){
								$gamecheck = 0;
							}
						}
					}
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
				}
				else{
					
					$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
						'DailyRoutineAttendance.id as id',
					))->from('DailyRoutineAttendance')
					->where('DailyRoutineAttendance.attendanceDate < "'. date ('Y-m-d') .'"')
					->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($id))
					->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
					->getQuery ()->execute ();
					$gamecheck = 1;
					$contofday = count($kid_total_day);
					if($contofday == 1){
						$dayid = $contofday + 1; 
					} else {
						$dayid = $contofday;
					}
					$kidprofile = NidaraKidProfile::findFirstByid ($id);
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
								$input_data->nidara_kid_profile_id
							))->inwhere('KidsGamesStatus.current_status', array(
								1
							))->getQuery()->execute();
							if(count($game_getses) <= 0){
								$gamecheck = 0;
							}
						}
					}
					if($gamecheck == 0){
						if($contofday > 0){
							$kid_info['days'] = $contofday;
						} else {
							$kid_info['days'] = $contofday;
						}
						$dayidvalue = $contofday - 1;
					} else {
						$kid_info['days'] = $contofday;
						$dayidvalue = $contofday;
					}
					
				}
				$days = 0;
				$guided_learning_id['no_days'] = $kid_info['days'];
				$guided_learning_id['days'] = (date("days",($days - (strtotime($value->created_at)))));
				$guided_learning_id['Profile Data'] = date("Y-m-d",strtotime($value->created_at));
				$guided_learning_id['Data'] = $days;
				$guidedlearningarray[] = $guided_learning_id;
			 }
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$guidedlearningarray 
			]);
		}
	}

	/**
	 * 
	 * @return string
	 */
	public function getkidgames() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		/*  */
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->kid_id ) ? $input_data->kid_id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the kid id' 
			] );
		} else {
			$games_database = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.id',
					'GamesDatabase.game_id',
					'GamesDatabase.games_name',
					'GamesDatabase.games_folder',
					'GamesDatabase.status',
			) )->from ( 'KidGuidedLearningMap' )->join ( 'GuidedLearning', 'KidGuidedLearningMap.guided_learning_id=GuidedLearning.id' )->join ( 'GuidedLearningSchedule', 'GuidedLearningSchedule.guided_learning_id=GuidedLearning.id' )->join ( 'GuidedLearningGamesMap', 'GuidedLearningGamesMap.guided_learning_schedule_id=GuidedLearningSchedule.id' )->join ( 'GamesTagging', 'GamesTagging.id=GuidedLearningGamesMap.games_tagging_id' )->join ( 'GamesDatabase', 'GamesTagging.games_database_id=GamesDatabase.id' )->groupBy ("GamesDatabase.id")->where ( "KidGuidedLearningMap.nidara_kid_profile_id", array ($id ) )->getQuery ()->execute ();
			$games = array ();
			$i=1;
			$gamecolor = GameColors::findFirstByday ( date('l') );
			$games ['background_image'] = $gamecolor->background_color;
			foreach ( $games_database as $games_data ) {
				$gamename = str_replace ( " – ", " ", strtolower ( $games_data->games_name ) );
				$games_data->routerLink = str_replace ( " ", "_", strtolower ( $gamename ) );
				$games_data->games_folder = '/' . ltrim ( $games_data->games_folder, '/' );
				$games_data_array [] = $games_data;
				$i++;
			}
				$chunked_array = array_chunk ( $games_data_array, 4 );
				array_replace ( $chunked_array, $chunked_array );
				$keyed_array = array ();
				foreach ( $chunked_array as $chunked_arrays ) {
					$keyed_array [] ['page'] = $chunked_arrays;
				}
				$games ['games'] = $keyed_array;
				return Json_encode ( $games );
				return $this->response->setJsonContent ([ 
						'status' => true,
						'data' =>$games
				]);
		}
	}
}
