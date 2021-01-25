<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class GuidedlearningdaygamemapController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $guidedlearning = $this->modelsManager->createBuilder ()->columns ( array (
			'GuidedLearningDayGameMap.id as id',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'GuidedLearning.learning_model as learning_model',
			'GamesDatabase.games_name as games_name',
			'Days.days as days'
		))->from('GuidedLearningDayGameMap')
		->leftjoin('GuidedLearning','GuidedLearningDayGameMap.day_guided_learning_id = GuidedLearning.id')
		->leftjoin('CoreFrameworks','GuidedLearningDayGameMap.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','GuidedLearningDayGameMap.subject_id = Subject.id')
		->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
		->leftjoin('Days','GuidedLearningDayGameMap.day_id = Days.id')
		->orderBy('GuidedLearningDayGameMap.id DESC')
		->getQuery()->execute ();
		
		$guidedlearningarray = array();
		 foreach($guidedlearning as $value){
			 $guided_val['id'] = $value -> id;
			 $guided_val['grade_name'] = $value -> learning_model;
			 $guided_val['core_name'] = $value -> name;
			 $guided_val['subject_name'] = $value -> subject_name;
			 $guided_val['games_name'] = $value -> games_name;
			 $guided_val['days'] = $value -> days;
			 $guidedlearningarray[] = $guided_val;
		 }
		
		$chunked_array = array_chunk ( $guidedlearningarray, 15 );
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
	

	public function getdailygame(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		/*  */
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		if($day_id <= 0){
			$day_id = 1;
		}
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the grade_id' 
			] );
		}
		else {
			$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesDatabase.game_id as games_id',
				'GamesDatabase.games_name as games_name',
				'GamesDatabase.games_folder as games_folder',
				'GamesDatabase.daily_tips as daily_tips'
			))->from("GuidedLearningDayGameMap")
			->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
			->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
			->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
			->getQuery()->execute ();
			if ($guidedlearning_id) {
			$games = array ();
			$i=1;
			$gamecolor = GameColors::findFirstByday ( date('l') );
			$games ['background_image'] = $gamecolor->background_color;
			foreach ( $guidedlearning_id as $games_data ) {
				$game_value['games_id'] = $games_data -> games_id;
					$game_value['grade_id'] = $games_data -> grade_id;
					$game_value['games_name'] = $games_data -> games_name;
					$game_value['games_folder'] = $games_data -> games_folder;
					$game_value['daily_tips'] = $games_data -> daily_tips;
					$game_value['day_id'] = $day_id;
				$games_data_array [] = $game_value;
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
	
	
		public function getdailygame_new(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		/*  */
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		$kidprofile = NidaraKidProfile::findFirstByid ( $input_data->kid_id );
		if($day_id <= 0){
			$day_id = 1;
		}
		else{
			$day_id += 1;
		}
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the grade_id' 
			] );
		}
		else {
			/* if($grade_id == 5){
				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array(4,5))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array(4,5))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}

			}
			else{ */
				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
		//	}
			
			if ($guidedlearning_id) {
				$gameplaycheckvalue = 1;
				$games = array ();
				$i=1;
				$gamecolor = GameColors::findFirstByday ( date('l') );
				$games ['background_image'] = $gamecolor->background_color;
				$games ['gif'] = $gamecolor->gif;
				$games ['img'] = $gamecolor->img;
				$games ['gender'] = $input_data->gender;
				foreach ( $guidedlearning_id as $games_data ) {
					
					if($kidprofile -> test_kid_status == 0){
						$game_getses = $this->modelsManager->createBuilder()->columns(array(
							'KidsGamesStatus.session_id as session_id',
							'KidsGamesStatus.current_status as current_status',
							'KidsGamesStatus.game_id as game_id',
						))->from('KidsGamesStatus')
						->where('KidsGamesStatus.created_date < "'. date ('Y-m-d') .'"')
						->inwhere('KidsGamesStatus.game_id', array(
							 $games_data->games_id
						))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
							$input_data->kid_id
						))->inwhere('KidsGamesStatus.current_status', array(
							1
						))->getQuery()->execute();
						if(count($game_getses) <= 0){
							$game_value['status'] = true;
						} else {
							$game_value['status'] = false;
						}
						
						$game_getses2 = $this->modelsManager->createBuilder()->columns(array(
							'KidsGamesStatus.session_id as session_id',
							'KidsGamesStatus.current_status as current_status',
							'KidsGamesStatus.game_id as game_id',
						))->from('KidsGamesStatus')
						->inwhere('KidsGamesStatus.game_id', array(
							 $games_data->games_id
						))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
							$input_data->kid_id
						))->inwhere('KidsGamesStatus.current_status', array(
							1
						))->getQuery()->execute();
						if(count($game_getses2) <= 0){
							$gameplaycheckvalue = 2;
						}
					} else {
						$game_value['status'] = true;
					}
					$game_value['games_id'] = $games_data -> games_id;
					$game_value['grade_id'] = $games_data -> grade_id;
					$game_value['games_name'] = $games_data -> games_name;
					$game_value['games_folder'] = $games_data -> games_folder;
					$game_value['daily_tips'] = $games_data -> daily_tips;
					$game_value['day_id'] = $day_id;
					$games_data_array [] = $game_value;
					$i++;
				}
				if(($grade_id) == 4 || ($grade_id) == 5 || ($grade_id) == 6 || ($grade_id) == 7 || ($grade_id) == 8){
					 $chunked_array = array_chunk ( $games_data_array, 1 );
					array_replace ( $chunked_array, $chunked_array );
					$keyed_array = array ();
					foreach ( $chunked_array as $chunked_arrays ) {
						$keyed_array [] ['page'] = $chunked_arrays;
					}
					if($kidprofile -> test_kid_status == 0){
						if($gameplaycheckvalue == 2){
								$games['palygamestatus'] = false;
							} else {
								$games['palygamestatus'] = true;
							}
					} else {
						$games['palygamestatus'] = false;
					}
					$games ['games'] = $keyed_array;
					return Json_encode ( $games );
					return $this->response->setJsonContent ([ 
							'status' => true,
							'data' =>$games
					]);
				}
				else{
					$chunked_array = array_chunk ( $games_data_array, 4 );
					array_replace ( $chunked_array, $chunked_array );
					$keyed_array = array ();
					foreach ( $chunked_array as $chunked_arrays ) {
						$keyed_array [] ['page'] = $chunked_arrays;
					}
					if($kidprofile -> test_kid_status == 0){
						if($gameplaycheckvalue == 2){
								$games['palygamestatus'] = false;
							} else {
								$games['palygamestatus'] = true;
							}
					} else {
						$games['palygamestatus'] = false;
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
	}
	
	
	public function getdailygameschool(){
		$input_data = $this->request->getJsonRawBody ();
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		if($day_id <= 0){
			$day_id = 1;
		}
		else{
			$day_id += 1;
		}
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the grade_id' 
			] );
		}
		else {
			/* if($grade_id == 5){
				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array(4,5))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array(4,5))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}

			}
			else{ */
				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
		//	}
			
			if ($guidedlearning_id) {
			$games = array ();
			$i=1;
			$gamecolor = GameColors::findFirstByday ( date('l') );
			$games ['background_image'] = $gamecolor->background_color;
			$games ['gif'] = $gamecolor->gif;
			$games ['img'] = $gamecolor->img;
			$games ['gender'] = $input_data->gender;
			foreach ( $guidedlearning_id as $games_data ) {
				$game_value['games_id'] = $games_data -> games_id;
					$game_value['grade_id'] = $games_data -> grade_id;
					$game_value['games_name'] = $games_data -> games_name;
					$game_value['games_folder'] = $games_data -> games_folder;
					$game_value['daily_tips'] = $games_data -> daily_tips;
					$game_value['day_id'] = $day_id;
				$games_data_array [] = $game_value;
				$i++;
			}
			if(($grade_id) == 4 || ($grade_id) == 5){
				 $chunked_array = array_chunk ( $games_data_array, 1 );
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
			else{
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
	}
	
	
	
	public function chidexpriychack(){
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the id' 
			] );
		}
		else{
			$collection = NidaraKidProfile::findFirstByid($id);
			$daysnow = strtotime(date("Y-m-d"));
			$daysexpriy = strtotime($collection->expiry_date);
			$days = round(($daysnow - $daysexpriy)/(60 * 60 * 24));
			$days_2 = round(($daysexpriy - $daysnow)/(60 * 60 * 24));
			if($days > 0){
				return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Your Nidara-Children access has expired.<br> Please renew to continue developing your child.",
					"data" => $days
				] );
			}
			else if($days_2 <= 60){
					$kid_total_day = $this->modelsManager->createBuilder ()->columns ( array (
						'COUNT(DailyRoutineAttendance.id) as days',
					))->from('DailyRoutineAttendance')
					->inwhere("DailyRoutineAttendance.nidara_kid_profile_id",array($id))
					->inwhere("DailyRoutineAttendance.task_name",array('nidarachildrensession'))
					->getQuery ()->execute ();
					foreach($kid_total_day as $value){
						$kid_info['days'] = $value->days;
					}
					if($value->days <= 175){
						return $this->response->setJsonContent ( [ 
						"status" => true,
						"error" => 'Your Child play only '. $value->days .'days and you have only ' . $days_2 . 'days'
					] );
					}
					else{
						return $this->response->setJsonContent ( [ 
							"status" => true,
							"data" => $value->days
						] );
					}
			}
			else{
				return $this->response->setJsonContent ( [ 
					"status" => true,
					"data" => $collection->id
				] );
			}
		}
	}
	
	
	public function getbyid(){
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the id' 
			] );
		}
		else{
			$guidedlearning_id = GuidedLearningDayGameMap::findByid($id);
			if($guidedlearning_id){
				return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$guidedlearning_id
				]);
			}
			else{
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed' 
				] );
			}
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
					 $collection->day_guided_learning_id = $input_data->grade_id;
					 $collection->framework_id = $value->framework_id;
					 $collection->subject_id = $value->subject_id;
					 $collection->game_id = $value->game_id;
					 $collection->created_at = date ( 'Y-m-d H:i:s' );
				 }
				  if(empty($value -> day_id)){
						$collection->day_id = $input_data->day_id; 
					 } else {
						 $collection->day_id = $value->day_id;
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
	
	public function getbygrade(){
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the id' 
			] );
		}
		else{
			//$guidedlearning_id = GuidedLearningDayGameMap::findByday_guided_learning_id($id);
			$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT GuidedLearningDayGameMap.day_id as day_id',
			'GuidedLearningDayGameMap.day_guided_learning_id as grade_id'
			))->from('GuidedLearningDayGameMap')
			->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($id))
			->getQuery ()->execute ();
			$guidedarray = array();
			foreach($guidedlearning_id as $value){
				$guided_val['day_id'] = $value->day_id;
				$guided_val['grade_id'] = $value->grade_id;
				$guidedarray[] = $guided_val;
			}
			$chunked_array = array_chunk ( $guidedarray, 90 );
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
	
	public function gettaggingmap(){
		$input_data = $this->request->getJsonRawBody ();
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($day_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the day_id' 
			] );
		}
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the grade_id' 
			] );
		}
		$guidedlearning = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT GuidedLearningDayGameMap.day_id as day_id',
			'GuidedLearningDayGameMap.day_guided_learning_id as grade_id'
		))->from('GuidedLearningDayGameMap')
		->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
		->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
		->getQuery ()->execute ();
		 $guidedarray = array();
		 foreach($guidedlearning as $value){
			$guidedlearning2 = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearningDayGameMap.id as id',
				'GuidedLearningDayGameMap.day_id as day_id',
				'GuidedLearningDayGameMap.day_guided_learning_id as grade_id',
				'GuidedLearningDayGameMap.framework_id as framework_id',
				'GuidedLearningDayGameMap.subject_id as subject_id',
				'GuidedLearningDayGameMap.game_id as game_id',
				'GuidedLearningDayGameMap.game_id as game_id',
			))->from('GuidedLearningDayGameMap')
			->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
			->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
			->getQuery ()->execute ();
			
			$guided_day_array = array();
			foreach($guidedlearning2 as $value2){
				$gamecoredetails = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesDatabase.game_id as games_id',
				'GamesDatabase.games_name as games_name',
				'GamesDatabase.games_folder as games_folder',
				'GamesDatabase.daily_tips as daily_tips'
				))->from('GamesCoreframeMap')
				->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
				->inwhere("GamesCoreframeMap.grade_id",array($value2 -> grade_id))
				->inwhere("GamesCoreframeMap.framework_id",array($value2 -> framework_id))
				->inwhere("GamesCoreframeMap.subject_id",array($value2 -> subject_id))
				->getQuery ()->execute ();
				
				$gamecorearray = array ();
				foreach($gamecoredetails as $value3){
					$game_value['games_id'] = $value3 -> games_id;
					$game_value['grade_id'] = $value3 -> grade_id;
					$game_value['games_name'] = $value3 -> games_name;
					$game_value['games_folder'] = $value3 -> games_folder;
					$game_value['daily_tips'] = $value3 -> daily_tips;
					$gamecorearray[] = $game_value;
				} 
				$data_val['id'] = $value2 -> id;
				$data_val['day_id'] = $value2 -> day_id;
				$data_val['grade_id'] = $value2 -> grade_id;
				$data_val['framework_id'] = $value2 -> framework_id;
				$data_val['subject_id'] = $value2 -> subject_id;
				$data_val['game_id'] = $value2 -> game_id;
				$data_val['game_data'] = $gamecorearray;
				$guided_day_array[] = $data_val;
			}
			$game_data['day_id'] = $value -> day_id;
			$game_data['grade_id'] = $value -> grade_id;
			$game_data['dayGamaGroup'] = $guided_day_array;
			$guidedarray[] = $game_data;
		 }
		return $this->response->setJsonContent ([ 
		'status' => true,
		'data' =>$guidedarray
		]);		 
	}

public function getdailygametemplectwise(){
		$input_data = $this->request->getJsonRawBody ();
		
		/*  */
		//$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		$kidprofile = NidaraKidProfile::findFirstByid ( $input_data->kid_id );
			/*if($day_id <= 0){
				$day_id = 1;
			}
			else{
				$day_id += 1;
			}*/
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the grade_id' 
			] );
		}
		else {
$collection = GuidedLearningTestUser::findFirstBykid_id($input_data->kid_id);

if($collection->week==1)
{
	$sdate=1;
}
else if($collection->week==2)
{
	$sdate=6;
}
else if($collection->week==3)
{
	$sdate=11;
}
else
{
	$sdate=16;
}

if($collection->month !=1)
{
	$sdate=$sdate+(($collection->month-1) * 20);
}

$edate=$sdate+4;



			
				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->where('GuidedLearningDayGameMap.day_id >="' . $sdate .'" AND GuidedLearningDayGameMap.day_id <="'.$edate.'"')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.subject_id",array($collection->subject_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->where('GuidedLearningDayGameMap.day_id >="' . $sdate . '" AND GuidedLearningDayGameMap.day_id <="' .$edate. '"')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.subject_id",array($collection->subject_id))
					->inwhere("GamesDatabase.rahul",array(1))
					
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
		
			
			if ($guidedlearning_id) {
				$gameplaycheckvalue = 1;
				$games = array ();
				$i=1;
				$gamecolor = GameColors::findFirstByday ( date('l') );
				$games ['background_image'] = $gamecolor->background_color;
				$games ['gif'] = $gamecolor->gif;
				$games ['img'] = $gamecolor->img;
				$games ['gender'] = $input_data->gender;
				foreach ( $guidedlearning_id as $games_data ) {
					
					if($kidprofile -> test_kid_status == 0){
						$game_getses = $this->modelsManager->createBuilder()->columns(array(
							'KidsGamesStatus.session_id as session_id',
							'KidsGamesStatus.current_status as current_status',
							'KidsGamesStatus.game_id as game_id',
						))->from('KidsGamesStatus')
						->where('KidsGamesStatus.created_date < "'. date ('Y-m-d') .'"')
						->inwhere('KidsGamesStatus.game_id', array(
							 $games_data->games_id
						))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
							$input_data->kid_id
						))->inwhere('KidsGamesStatus.current_status', array(
							1
						))->getQuery()->execute();
						if(count($game_getses) <= 0){
							$game_value['status'] = true;
						} else {
							$game_value['status'] = false;
						}
						
						$game_getses2 = $this->modelsManager->createBuilder()->columns(array(
							'KidsGamesStatus.session_id as session_id',
							'KidsGamesStatus.current_status as current_status',
							'KidsGamesStatus.game_id as game_id',
						))->from('KidsGamesStatus')
						->inwhere('KidsGamesStatus.game_id', array(
							 $games_data->games_id
						))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
							$input_data->kid_id
						))->inwhere('KidsGamesStatus.current_status', array(
							1
						))->getQuery()->execute();
						if(count($game_getses2) <= 0){
							$gameplaycheckvalue = 2;
						}
					} else {
						$game_value['status'] = true;
					}
					$game_value['games_id'] = $games_data -> games_id;
					$game_value['grade_id'] = $games_data -> grade_id;
					$game_value['games_name'] = $games_data -> games_name;
					$game_value['games_folder'] = $games_data -> games_folder;
					$game_value['daily_tips'] = $games_data -> daily_tips;
					$game_value['day_id'] = $day_id;
					$games_data_array [] = $game_value;
					$i++;
				}
				if(($grade_id) == 4 || ($grade_id) == 5 || ($grade_id) == 6 || ($grade_id) == 7 || ($grade_id) == 8){
					 $chunked_array = array_chunk ( $games_data_array, 1 );
					array_replace ( $chunked_array, $chunked_array );
					$keyed_array = array ();
					foreach ( $chunked_array as $chunked_arrays ) {
						$keyed_array [] ['page'] = $chunked_arrays;
					}
					if($kidprofile -> test_kid_status == 0){
						if($gameplaycheckvalue == 2){
								$games['palygamestatus'] = false;
							} else {
								$games['palygamestatus'] = true;
							}
					} else {
						$games['palygamestatus'] = false;
					}
					$games ['games'] = $keyed_array;
					return Json_encode ( $games );
					return $this->response->setJsonContent ([ 
							'status' => true,
							'data' =>$games
					]);
				}
				else{
					$chunked_array = array_chunk ( $games_data_array, 4 );
					array_replace ( $chunked_array, $chunked_array );
					$keyed_array = array ();
					foreach ( $chunked_array as $chunked_arrays ) {
						$keyed_array [] ['page'] = $chunked_arrays;
					}
					if($kidprofile -> test_kid_status == 0){
						if($gameplaycheckvalue == 2){
								$games['palygamestatus'] = false;
							} else {
								$games['palygamestatus'] = true;
							}
					} else {
						$games['palygamestatus'] = false;
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
	}


	function saveguidedlearningtestuser()
	{
		$input_data = $this->request->getJsonRawBody ();

		 $collection = GuidedLearningTestUser::findFirstBykid_id($input_data->kid_id);
            if (!$collection)
            {
                $collection = new GuidedLearningTestUser();
            }
            $collection->month = $input_data->month;
            $collection->week = $input_data->week;
            $collection->subject_id = $input_data->subject_id;
            $collection->kid_id = $input_data->nidara_kid_profile_id;
            $collection->created_at = date('Y-m-d');
            

            if ($collection->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => 'Data Saved', "data" => $collection]);
            }
            else
            {
            	 return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'This game info not saved', "data" => $collection]);

            }
	}

 public function getdailygame_new_demokid(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		/*if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}*/
		/*  */
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		$kidprofile = NidaraKidProfile::findFirstByid ( $input_data->kid_id );



$demogamelist=false;



		if($day_id <= 0){
			$day_id = 1;
		}
		else{
			$day_id += 1;


		}


		$democheck = $this->modelsManager->createBuilder ()->columns ( array (
						'SalesmanDemoKid.id',
						'SalesmanDemoKid.kid_id',
					))->from("SalesmanDemoKid")
					->inwhere("SalesmanDemoKid.kid_id",array($input_data->kid_id))
					->getQuery()->execute ();

		if(count($democheck) > 0)
		{
			$gradcheck = $this->modelsManager->createBuilder ()->columns ( array (
						'GuidedLearningDemoKid.id',
						'GuidedLearningDemoKid.day_id',
					))->from("GuidedLearningDemoKid")
					->inwhere("GuidedLearningDemoKid.grade_id",array($grade_id))
					->getQuery()->execute ();	

			if(count($gradcheck)>0)
			{
				$day_id=$gradcheck[0]->day_id;
			}

			$demogamelist=true;		
		}





		if(empty($grade_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the grade_id' 
			] );
		}
		else {
			/* if($grade_id == 5){
				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array(4,5))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array(4,5))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}

			}
			else{ */

				if($demogamelist==false)
				{

				if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("GuidedLearningDayGameMap")
					->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
					->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
					->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('GuidedLearningDayGameMap.game_id')
					->getQuery()->execute ();
				}

				}
				else
				{

					if($input_data->gender == 'famale'){
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.game_id as games_id',
						'GamesDatabase.games_name as games_name',
						'GamesDatabase.games_folder as games_folder',
						'GamesDatabase.daily_tips as daily_tips'
					))->from("DemoGameList")
					->leftjoin('GamesDatabase','DemoGameList.game_id = GamesDatabase.id')
					->inwhere("DemoGameList.day_guided_learning_id",array($grade_id))
					->inwhere("DemoGameList.day_id",array($day_id))
					->inwhere("GamesDatabase.tina",array(1))
					->groupBy('DemoGameList.game_id')
					->getQuery()->execute ();
				}
				else{
					$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesDatabase.game_id as games_id',
					'GamesDatabase.games_name as games_name',
					'GamesDatabase.games_folder as games_folder',
					'GamesDatabase.daily_tips as daily_tips'
					))->from("DemoGameList")
					->leftjoin('GamesDatabase','DemoGameList.game_id = GamesDatabase.id')
					->inwhere("DemoGameList.day_guided_learning_id",array($grade_id))
					->inwhere("DemoGameList.day_id",array($day_id))
					->inwhere("GamesDatabase.rahul",array(1))
					->groupBy('DemoGameList.game_id')
					->getQuery()->execute ();
				}

				}
		//	}
			
			if ($guidedlearning_id) {
				$gameplaycheckvalue = 1;
				$games = array ();
				$i=1;
				$gamecolor = GameColors::findFirstByday ( date('l') );
				$games ['background_image'] = $gamecolor->background_color;
				$games ['gif'] = $gamecolor->gif;
				$games ['img'] = $gamecolor->img;
				$games ['gender'] = $input_data->gender;
				foreach ( $guidedlearning_id as $games_data ) {
					$collaction_id = DailyRoutineAttendance::findBynidara_kid_profile_id($kidprofile -> id);
					
					if($kidprofile -> test_kid_status == 0){
						if(count($collaction_id) > $day_id){
							$game_getses = $this->modelsManager->createBuilder()->columns(array(
								'KidsGamesStatus.session_id as session_id',
								'KidsGamesStatus.current_status as current_status',
								'KidsGamesStatus.game_id as game_id',
							))->from('KidsGamesStatus')
							->where('KidsGamesStatus.created_date <="'. date ('Y-m-d') .'"')
							->inwhere('KidsGamesStatus.game_id', array(
								 $games_data->games_id
							))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
								$input_data->kid_id
							))->inwhere('KidsGamesStatus.current_status', array(
								1
							))->getQuery()->execute();
						} else {
							$game_getses = $this->modelsManager->createBuilder()->columns(array(
								'KidsGamesStatus.session_id as session_id',
								'KidsGamesStatus.current_status as current_status',
								'KidsGamesStatus.game_id as game_id',
							))->from('KidsGamesStatus')
							->where('KidsGamesStatus.created_date < "'. date ('Y-m-d') .'"')
							->inwhere('KidsGamesStatus.game_id', array(
								 $games_data->games_id
							))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
								$input_data->kid_id
							))->inwhere('KidsGamesStatus.current_status', array(
								1
							))->getQuery()->execute();
						}
						if(count($game_getses) <= 0){
							$game_value['status'] = true;
						} else {
							$game_value['status'] = false;
						}
						
						$game_getses2 = $this->modelsManager->createBuilder()->columns(array(
							'KidsGamesStatus.session_id as session_id',
							'KidsGamesStatus.current_status as current_status',
							'KidsGamesStatus.game_id as game_id',
						))->from('KidsGamesStatus')
						->inwhere('KidsGamesStatus.game_id', array(
							 $games_data->games_id
						))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
							$input_data->kid_id
						))->inwhere('KidsGamesStatus.current_status', array(
							1
						))->getQuery()->execute();
						if(count($game_getses2) <= 0){
							$gameplaycheckvalue = 2;
						}
					} else {
						$game_value['status'] = true;
					}
					$game_value['games_id'] = $games_data -> games_id;
					// $game_value['grade_id'] = $games_data -> grade_id;
					$game_value['games_name'] = $games_data -> games_name;
					$game_value['games_folder'] = $games_data -> games_folder;
					$game_value['daily_tips'] = $games_data -> daily_tips;
					$game_value['day_id'] = $day_id;
					$games_data_array [] = $game_value;
					$i++;
				}
				if(($grade_id) == 4 || ($grade_id) == 5 || ($grade_id) == 6 || ($grade_id) == 7 || ($grade_id) == 8){
					 $chunked_array = array_chunk ( $games_data_array, 1 );
					array_replace ( $chunked_array, $chunked_array );
					$keyed_array = array ();
					foreach ( $chunked_array as $chunked_arrays ) {
						$keyed_array [] ['page'] = $chunked_arrays;
					}
					if($kidprofile -> test_kid_status == 0){
						if($gameplaycheckvalue == 2){
								$games['palygamestatus'] = false;
							} else {
								$games['palygamestatus'] = true;
							}
					} else {
						$games['palygamestatus'] = false;
					}
					$games ['games'] = $keyed_array;
					return Json_encode ( $games );
					return $this->response->setJsonContent ([ 
							'status' => true,
							'data' =>$games
					]);
				}
				else{
					$chunked_array = array_chunk ( $games_data_array, 4 );
					array_replace ( $chunked_array, $chunked_array );
					$keyed_array = array ();
					foreach ( $chunked_array as $chunked_arrays ) {
						$keyed_array [] ['page'] = $chunked_arrays;
					}
					if($kidprofile -> test_kid_status == 0){
						if($gameplaycheckvalue == 2){
								$games['palygamestatus'] = false;
							} else {
								$games['palygamestatus'] = true;
							}
					} else {
						$games['palygamestatus'] = false;
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
	}
	
}