<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
class HealthdevedaysmapController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$healthdaymapviewall = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT HealthDevQuesDayMap.grade_id as grade_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.name as name',
			'CoreFrameworks.id as framework_id',
			'Subject.subject_name as subject_name',
			'DaysHealth.days as days',
			'HealthDevelopmentCatagory.health_dev_cat as health_dev_cat',
			'HealthDevQuesDayMap.subject_id as subject_id',
			'HealthDevQuesDayMap.heth_cat as heth_cat',
			'HealthDevQuesDayMap.day_id as day_id',
		))->from('HealthDevQuesDayMap')
		->leftjoin('Grade','HealthDevQuesDayMap.grade_id = Grade.id')
		->leftjoin('CoreFrameworks','HealthDevQuesDayMap.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','HealthDevQuesDayMap.subject_id = Subject.id')
		->leftjoin('DaysHealth','HealthDevQuesDayMap.day_id = DaysHealth.id')
		->leftjoin('HealthDevelopmentCatagory','HealthDevQuesDayMap.heth_cat = HealthDevelopmentCatagory.id')->getQuery ()->execute ();
		
		$dailymap_healtharray = array();
		foreach($healthdaymapviewall as $value){
			$health_list['grade_name'] = $value -> grade_name;
			$health_list['core_name'] = $value -> name;
			$health_list['subject_name'] = $value -> subject_name;
			$health_list['days'] = $value -> days;
			$health_list['health_dev_cat'] = $value -> health_dev_cat;
			$health_list['grade_id'] = $value -> grade_id;
			$health_list['subject_id'] = $value -> subject_id;
			$health_list['heth_cat'] = $value -> heth_cat;
			$health_list['day_id'] = $value -> day_id;
			$health_list['framework_id'] = $value -> framework_id;
			$dailymap_healtharray[] = $health_list;
		}
		$chunked_array = array_chunk ( $dailymap_healtharray, 15 );
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
	public function create(){
		$input_data = $this->request->getJsonRawBody();
		/**
         * This object using valitaion 
         */
       $validation = new Validation(); 
		/* $validation->add('id', new PresenceOf(['message' => 'id is required'])); */
        $validation->add('grade_id', new PresenceOf(['message' => 'grade_id is required']));
        $validation->add('framework_id', new PresenceOf(['message' => 'framework_id is required']));
        $validation->add('subject_id', new PresenceOf(['message' => 'subject_id is required']));
        $validation->add('heth_cat', new PresenceOf(['message' => 'heth_cat is required']));  
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
        else{
			$health_qustion = $input_data -> healthDevelopmentQA;
			
			$i = 0;
			foreach($health_qustion as $value){
				$collection = new HealthDevQuesDayMap();
				$collection -> id = $this->gradingreporting->getNewId ( "gratingreporting" );
				$collection -> day_id = $input_data -> days;
				$collection -> grade_id = $input_data -> grade_id;
				$collection -> framework_id = $input_data -> framework_id;
				$collection -> subject_id = $input_data -> subject_id;
				$collection -> heth_cat = $input_data -> heth_cat;
				$collection -> question_id = $value -> question;
				if(!$collection -> save()){
					return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
				}
				$i++;
			}
			return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		}
		
	}
	
	public function getbydaymapques(){
		$input_data = $this->request->getJsonRawBody ();
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
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		} 
		
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		
		if(empty($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		
		$heth_cat = isset ( $input_data->heth_cat ) ? $input_data->heth_cat : '';
		
		if(empty($heth_cat)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'heth_cat Id is null'
			] );
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		
		if(empty($heth_cat)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'heth_cat Id is null'
			] );
		}
		
		
		$healthdevelopment = $this->modelsManager->createBuilder ()->columns ( array (
			'HealthDevQuesDayMap.id as id',
			'HealthDevQuesDayMap.question_id as question_id',
		))->from('HealthDevQuesDayMap')
			->inwhere("HealthDevQuesDayMap.grade_id",array($grade_id))
			->inwhere('HealthDevQuesDayMap.subject_id',array($subject_id))
			->inwhere('HealthDevQuesDayMap.heth_cat',array($heth_cat))
			->inwhere('HealthDevQuesDayMap.day_id',array($day_id))
			->getQuery ()->execute ();
			 
			 $healthdevelopmentarray = array();
			 foreach($healthdevelopment as $value){
				 $standard_database_val['id'] = $value->id;
				 $standard_database_val['question_id'] = $value->question_id;
				 $healthdevelopmentarray [] = $standard_database_val;
			 }
		 return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $healthdevelopmentarray
		] );
	}
	
	public function update(){
		$input_data = $this->request->getJsonRawBody();
		/**
         * This object using valitaion 
         */
		 
		
       $validation = new Validation(); 
		
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
        else{
			$health_qustion = $input_data -> healthDevelopmentQA;
			foreach($health_qustion as $value){
				$collection = HealthDevQuesDayMap::findFirstByid( $value->id );
				$collection -> day_id = $input_data -> days;
				$collection -> grade_id = $input_data -> grade_id;
				$collection -> subject_id = $input_data -> subject_id;
				$collection -> heth_cat = $input_data -> heth_cat;
				$collection -> question_id = $value -> question_id;
				if(!$collection -> save()){
					return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
				}
				$i++;
			}
			return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		}
		
	}
	
	public function getbykididdaymap(){
		$input_data = $this->request->getJsonRawBody ();
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
		$id = isset( $input_data-> nidara_kid_profile_id)? $input_data-> nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Kid Id is null'
			] );
		}
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		//return $this->response->setJsonContent(['status' => true, 'data' => $grade_id]);
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		if(empty($day_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Day Id is null'
			] );
		}
		$collection = array();
		$collection = $this->modelsManager->createBuilder ()->columns ( array (
				'HealthParentAnswerStatus.id as id',
				'HealthParentAnswerStatus.day_id as day_id',
				'HealthParentAnswerStatus.nidara_kid_profile_id as nidara_kid_profile_id'
			))->from('HealthParentAnswerStatus')
			->inwhere("HealthParentAnswerStatus.nidara_kid_profile_id",array($id))
			//->inwhere('HealthParentAnswerStatus.day_id',array($day_id))
			->getQuery ()->execute ();
			//return $this->response->setJsonContent(['status' => true, 'data' =>count($collaction2)]);
		if(count($collection) == 0){
			$parentquestion = $this->modelsManager->createBuilder ()->columns ( array (
				'HealthDevelopmentQuestion.id as id',
				'HealthDevelopmentQuestion.question_id as question_id',
				'HealthDevelopmentQuestion.question as question',
				'HealthDevelopmentCatagory.health_dev_cat as health_dev_cat',
			))->from('HealthDevQuesDayMap')
			->leftjoin('Grade','HealthDevQuesDayMap.grade_id = Grade.id')
			->leftjoin('CoreFrameworks','HealthDevQuesDayMap.framework_id = CoreFrameworks.id')
			->leftjoin('Subject','HealthDevQuesDayMap.subject_id = Subject.id')
			->leftjoin('HealthDevelopmentCatagory','HealthDevQuesDayMap.heth_cat = HealthDevelopmentCatagory.id')
			->leftjoin('HealthDevelopmentQuestion','HealthDevQuesDayMap.question_id = HealthDevelopmentQuestion.id')
			->inwhere("HealthDevQuesDayMap.grade_id",array($grade_id))
			->getQuery ()->execute ();
			
			$parentquestionarray = array();
			
			foreach($parentquestion as $value){
				$parentValue ['id'] = $value -> id;
				$parentValue ['question_id'] = $value -> question_id;
				$parentValue ['question'] = $value -> question;
				$parentValue ['health_dev_cat'] = $value -> health_dev_cat;
				$parentquestionarray[] = $parentValue;
			}
			if(count($parentquestionarray) == 0){
				return $this->response->setJsonContent(['status' => true, 'value' => 'Data already save']);
			}
			return $this->response->setJsonContent(['status' => true, 'data' => $parentquestionarray]);
		}
		else{
			return $this->response->setJsonContent(['status' => true, 'value' => 'Data already save']);
		}
	}
	
	public function getbyparentgame(){
		$input_data = $this->request->getJsonRawBody ();
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
		$id = isset( $input_data-> nidara_kid_profile_id)? $input_data-> nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Kid Id is null'
			] );
		}
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		//return $this->response->setJsonContent(['status' => true, 'data' => $grade_id]);
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		if(empty($day_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Day Id is null'
			] );
		}
		$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
				'ParentGameAnswerStatus.id as id',
				'ParentGameAnswerStatus.day_id as day_id',
				'ParentGameAnswerStatus.nidara_kid_profile_id as nidara_kid_profile_id'
			))->from('ParentGameAnswerStatus')
			->inwhere("ParentGameAnswerStatus.nidara_kid_profile_id",array($id))
			->inwhere('ParentGameAnswerStatus.day_id',array($day_id))
			->getQuery ()->execute ();
			if(count($collection2) == 0){
			$parentgame = $this->modelsManager->createBuilder ()->columns ( array (
					'ParentGuidedLearningDayGameMap.game_id as game_id',
					'ParentGuidedLearningDayGameMap.guided_learning_id as guided_learning_id',
					'ParentGuidedLearningDayGameMap.day_id as day_id',
					'ParentGamesDatabase.games_name as games_name',
					'CoreFrameworks.name as core_framework_name',
					'Subject.subject_name as subject_name',
				))->from('ParentGuidedLearningDayGameMap')
				->leftjoin('Subject','ParentGuidedLearningDayGameMap.subject_id = Subject.id')
				->leftjoin('CoreFrameworks','ParentGuidedLearningDayGameMap.framework_id = CoreFrameworks.id')
				->leftjoin('ParentGamesDatabase','ParentGuidedLearningDayGameMap.game_id = ParentGamesDatabase.id')
				->inwhere("ParentGuidedLearningDayGameMap.guided_learning_id",array($grade_id))
				->inwhere('ParentGuidedLearningDayGameMap.day_id',array($day_id))
				->getQuery ()->execute ();
				$parentgamearray = array();
				foreach($parentgame as $value){
					$core_framework_name = strtolower( str_replace ( ' ', '_', $value->core_framework_name ) );
					$core_array [] = $value->core_framework_name;
					$core_frm_array [$core_framework_name] [] = $value;
				}
				$core_frame = CoreFrameworks::find ();
				foreach ( $core_frame as $core ) {
					if (! in_array ( $core->name, $core_array )) {
						$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
						$core_frm_array [$core->name] = array ();
					}
				}
				if(count($core_frm_array[$core_framework_name]) == 0){
					return $this->response->setJsonContent(['status' => true, 'value' => 'Today No Parent Game']);
				}
				else{
					return $this->response->setJsonContent(['status' => true, 'message' => $core_frm_array[$core_framework_name]]);
				}
			}
			else{
				return $this->response->setJsonContent(['status' => true, 'value' => 'Data already save']);
			}
		}
		
		
		
		public function getbyparentgamefirsteducation(){
			$input_data = $this->request->getJsonRawBody ();
			$main_grade_id = isset( $input_data-> grade_id)? $input_data-> grade_id : '';
			$core_id = isset( $input_data-> core_id)? $input_data-> core_id : '';
			$email_id = isset( $input_data-> email_id)? $input_data-> email_id : '';
			if(empty($main_grade_id)){
				$id = isset( $input_data-> nidara_kid_profile_id)? $input_data-> nidara_kid_profile_id : '';
				if(empty($id)){
					return $this->response->setJsonContent ( [
							'status' => false,
							'message' => 'Kid Id is null'
					] );
				}
				if($id == 1527){
					$grade_id = 2;
				}
				else{
					$kid_info = NidaraKidProfile::findFirstByid($id);
					$grade_id = $kid_info->grade;
				}
				if(empty($grade_id)){
					return $this->response->setJsonContent ( [
							'status' => false,
							'message' => 'Grade Id is null'
					] );
				}
				//return $this->response->setJsonContent(['status' => true, 'message' => $grade_id]);
				$parentquestion = $this->modelsManager->createBuilder ()->columns ( array (
					'HealthDevQuesDayMap.id as id',
					'HealthDevQuesDayMap.question_id as question_id',
					'HealthDevelopmentQuestion.question as question',
					'CoreFrameworks.name as core_framework_name',
					'Subject.subject_name as subject_name',
				))->from('HealthDevQuesDayMap')
				->leftjoin('CoreFrameworks','HealthDevQuesDayMap.framework_id = CoreFrameworks.id')
				->leftjoin('Subject','HealthDevQuesDayMap.subject_id = Subject.id')
				->leftjoin('HealthDevelopmentQuestion','HealthDevQuesDayMap.question_id = HealthDevelopmentQuestion.id')
				->inwhere("HealthDevQuesDayMap.grade_id",array($grade_id))
				->inwhere("HealthDevQuesDayMap.framework_id",array($core_id))
				->getQuery ()->execute ();
				$parentgamearray = array();
				foreach($parentquestion as $value){
					$parentanswer = $this->modelsManager->createBuilder ()->columns ( array (
						'HealthParentAnswers.parent_answer as parent_answer',
					))->from('HealthParentAnswers')
					->inwhere('HealthParentAnswers.nidara_kid_profile_id',array($id))
					->inwhere('HealthParentAnswers.parent_question_id',array($value->id))
					->getQuery ()->execute ();
					foreach($parentanswer as $parentanswervalue){
						
					}
					$question['id'] = $value->id;
					$question['question_id'] = $value->question_id;
					$question['question'] = $value->question;
					$question['core_framework_name'] = $value->core_framework_name;
					$question['subject_name'] = $value->subject_name;
					$question['answervalue'] = $parentanswervalue->parent_answer;
					$core_framework_name = strtolower( str_replace ( ' ', '_', $value->core_framework_name ) );
					$core_array [] = $value->core_framework_name;
					$core_frm_array [$core_framework_name] [] = $value;
					$parentgamearray [] =  $question;
				}
			}
			else{
				$grade_id = $main_grade_id;
				//return $this->response->setJsonContent(['status' => true, 'data' => $grade_id]);
				if(empty($grade_id)){
					return $this->response->setJsonContent ( [
							'status' => false,
							'message' => 'Grade Id is null'
					] );
				}
				//return $this->response->setJsonContent(['status' => true, 'message' => $grade_id]);
				$parentquestion = $this->modelsManager->createBuilder ()->columns ( array (
					'HealthCampQuestion.id as id',
					'HealthCampQuestion.id as question_id',
					'HealthCampQuestion.question as question',
					'CoreFrameworks.name as core_framework_name',
					'Subject.subject_name as subject_name',
				))->from('HealthCampQuestion')
				->leftjoin('CoreFrameworks','HealthCampQuestion.framework_id = CoreFrameworks.id')
				->leftjoin('Subject','HealthCampQuestion.subject_id = Subject.id')
				->inwhere("HealthCampQuestion.grade_id",array($grade_id))
				->inwhere("HealthCampQuestion.framework_id",array($core_id))
				->getQuery ()->execute ();
				$parentgamearray = array();
				foreach($parentquestion as $value){
					$parentanswer = $this->modelsManager->createBuilder ()->columns ( array (
						'HealthCampParentAnswers.parent_answer as parent_answer',
					))->from('HealthCampParentAnswers')
					->inwhere('HealthCampParentAnswers.nidara_parent_email',array($email_id))
					->inwhere('HealthCampParentAnswers.parent_question_id',array($value->id))
					->getQuery ()->execute ();
					foreach($parentanswer as $parentanswervalue){
						
					}
					$question['id'] = $value->id;
					$question['question_id'] = $value->question_id;
					$question['question'] = $value->question;
					$question['core_framework_name'] = $value->core_framework_name;
					$question['subject_name'] = $value->subject_name;
					$question['answervalue'] = $parentanswervalue->parent_answer;
					$core_framework_name = strtolower( str_replace ( ' ', '_', $value->core_framework_name ) );
					$core_array [] = $value->core_framework_name;
					$core_frm_array [$core_framework_name] [] = $value;
					$parentgamearray [] =  $question;
				}
			}
			$chunked_array = array_chunk ( $parentgamearray, 5 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			if(($core_id) == 1){
				$games ['core_education'] = $keyed_array;
			}
			else if(($core_id) == 2){
				$games ['core_health'] = $keyed_array;
			}
			else if(($core_id) == 3){
				$games ['core_interest'] = $keyed_array;
			}
			
			
			/* return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $games
			] ); */
			
			$core_frame = CoreFrameworks::find ();
			foreach ( $core_frame as $core ) {
				if (! in_array ( $core->name, $core_array )) {
					$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
					$core_frm_array [$core->name] = array ();
				}
			}
			if(count($core_frm_array[$core_framework_name]) == 0){
				return $this->response->setJsonContent(['status' => true, 'value' => 'Today No Parent Game']);
			}
			else{
				return $this->response->setJsonContent(['status' => true, 'data' => $games]);
			}
		}
		
	
	public function getbyparentquestionday(){
		$input_data = $this->request->getJsonRawBody ();
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
		$id = isset( $input_data-> nidara_kid_profile_id)? $input_data-> nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Kid Id is null'
			] );
		}
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		//return $this->response->setJsonContent(['status' => true, 'data' => $grade_id]);
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		if(empty($day_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Day Id is null'
			] );
		}
		$parentquestion = $this->modelsManager->createBuilder ()->columns ( array (
				'HealthDevelopmentCatagory.health_dev_cat as health_dev_cat',
				'HealthDevQuesDayMap.grade_id as grade_id',
				'HealthDevQuesDayMap.framework_id as framework_id',
				'HealthDevQuesDayMap.subject_id as subject_id',
				'HealthDevQuesDayMap.heth_cat as heth_cat',
				'HealthDevQuesDayMap.question_id as question_id',
			))->from('HealthDevQuesDayMap')
			->leftjoin('HealthDevelopmentCatagory','HealthDevQuesDayMap.heth_cat = HealthDevelopmentCatagory.id')
			->inwhere("HealthDevQuesDayMap.grade_id",array($grade_id))
			//->inwhere('HealthDevQuesDayMap.day_id',array($day_id))
			->getQuery ()->execute ();
			
		$parent_health_cat_array = array ();
		foreach($parentquestion as $parent_health_cat_value){
			$health_qustionar = $this->modelsManager->createBuilder ()->columns ( array (
				'HealthDevelopmentQuestion.id as id',
				'HealthDevelopmentQuestion.heth_cat as heth_cat',
				'HealthDevelopmentQuestion.question_id as question_id',
				'HealthDevelopmentQuestion.question as question',
			))->from('HealthDevelopmentQuestion')
			->inwhere("HealthDevelopmentQuestion.id",array($parent_health_cat_value->question_id))
			->inwhere("HealthDevelopmentQuestion.heth_cat",array($parent_health_cat_value->heth_cat))
			->getQuery ()->execute ();
			$health_qustion_val_array = array();
			foreach($health_qustionar as $health_qustion_value){
				$health_answer = $this->modelsManager->createBuilder ()->columns(array(
					'HealthParentAnswers.parent_question_id as parent_question_id',
					'HealthParentAnswers.parent_answer as parent_answer',
					'HealthParentAnswers.nidara_kid_profile_id as nidara_kid_profile_id',
				))->from('HealthParentAnswers')
				->inwhere("HealthParentAnswers.nidara_kid_profile_id",array($id))
				->inwhere("HealthParentAnswers.parent_question_id", array($health_qustion_value->id))
				->getQuery ()->execute ();
				/* return $this->response->setJsonContent ( [
						'status' => true,
						'data' => $health_qustion_value->id
				] ); */
				$health_answer_val_array = array();
				foreach($health_answer as $health_answer_value){
					$health_answer_val['parent_question_id'] = $health_answer_value->parent_question_id;
					$health_answer_val['parent_answer'] = $health_answer_value->parent_answer;
					$health_answer_val['nidara_kid_profile_id'] = $health_answer_value->nidara_kid_profile_id;
					$health_answer_val_array[] = $health_answer_val;
				}
				$health_qustion_val['id'] = $health_qustion_value->id;
				$health_qustion_val['heth_cat'] = $health_qustion_value->heth_cat;
				$health_qustion_val['question_id'] = $health_qustion_value->question_id;
				$health_qustion_val['question'] = $health_qustion_value->question;
				$health_qustion_val['answer'] = $health_answer_val_array;
				$health_qustion_val_array[] = $health_qustion_val;
			}
			$parent_health_val['health_dev_cat'] = $parent_health_cat_value->health_dev_cat;
			$parent_health_val['grade_id'] = $parent_health_cat_value->grade_id;
			$parent_health_val['framework_id'] = $parent_health_cat_value->framework_id;
			$parent_health_val['subject_id'] = $parent_health_cat_value->subject_id;
			$parent_health_val['heth_cat'] = $parent_health_cat_value->heth_cat;
			$parent_health_val['question_id'] = $parent_health_cat_value->question_id;
			$parent_health_val['health_qustion'] = $health_qustion_val_array;
			$parent_health_cat_array[] = $parent_health_val;
		}
		if(empty($parent_health_cat_array)){
			return $this->response->setJsonContent ( [
					'status' => true,
					'message' => 'today task is not'
			] );
		}
		else{
			return $this->response->setJsonContent ( [
						'status' => true,
						'data' => $parent_health_cat_array
				] );
		}
	}
	
	
	
	public function getbyhealthkidanswer(){
		$input_data = $this->request->getJsonRawBody ();
			$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
			$id = isset( $input_data-> nidara_kid_profile_id)? $input_data-> nidara_kid_profile_id : '';
			$core_id = isset( $input_data-> core_id)? $input_data-> core_id : '';
			if(empty($id)){
				return $this->response->setJsonContent ( [
						'status' => false,
						'message' => 'Kid Id is null'
				] );
			}
			if($id == 1527){
					$grade_id = 2;
				}
				else{
					$kid_info = NidaraKidProfile::findFirstByid($id);
					$grade_id = $kid_info->grade;
				}
			//return $this->response->setJsonContent(['status' => true, 'data' => $grade_id]);
			if(empty($grade_id)){
				return $this->response->setJsonContent ( [
						'status' => false,
						'message' => 'Grade Id is null'
				] );
			}
			//return $this->response->setJsonContent(['status' => true, 'message' => $grade_id]);
			$parentquestion = $this->modelsManager->createBuilder ()->columns ( array (
				'HealthDevQuesDayMap.id as id',
				'HealthDevQuesDayMap.question_id as question_id',
				'HealthDevelopmentQuestion.question as question',
				'CoreFrameworks.name as core_framework_name',
				'Subject.subject_name as subject_name',
			))->from('HealthDevQuesDayMap')
			->leftjoin('CoreFrameworks','HealthDevQuesDayMap.framework_id = CoreFrameworks.id')
			->leftjoin('Subject','HealthDevQuesDayMap.subject_id = Subject.id')
			->leftjoin('HealthDevelopmentQuestion','HealthDevQuesDayMap.question_id = HealthDevelopmentQuestion.id')
			->inwhere("HealthDevQuesDayMap.grade_id",array($grade_id))
			->inwhere("HealthDevQuesDayMap.framework_id",array($core_id))
			->getQuery ()->execute ();
			$parentgamearray = array();
			foreach($parentquestion as $value){
				$parentanswer = $this->modelsManager->createBuilder ()->columns ( array (
					'HealthParentAnswers.parent_answer as parent_answer',
				))->from('HealthParentAnswers')
				->inwhere('HealthParentAnswers.nidara_kid_profile_id',array($id))
				->inwhere('HealthParentAnswers.parent_question_id',array($value->id))
				->getQuery ()->execute ();
				foreach($parentanswer as $parentanswervalue){
					
				}
				$question['id'] = $value->id;
				$question['question_id'] = $value->question_id;
				$question['question'] = $value->question;
				$question['core_framework_name'] = $value->core_framework_name;
				$question['subject_name'] = $value->subject_name;
				$question['answervalue'] = $parentanswervalue->parent_answer;
				$core_framework_name = strtolower( str_replace ( ' ', '_', $value->core_framework_name ) );
				$core_array [] = $value->core_framework_name;
				$core_frm_array [$core_framework_name] [] = $value;
				$parentgamearray [] =  $question;
			}
			
			$core_frame = CoreFrameworks::find ();
			foreach ( $core_frame as $core ) {
				if (! in_array ( $core->name, $core_array )) {
					$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
					$core_frm_array [$core->name] = array ();
				}
			}
			if(count($core_frm_array[$core_framework_name]) == 0){
				return $this->response->setJsonContent(['status' => true, 'value' => 'Today No Parent Game']);
			}
			else{
				return $this->response->setJsonContent(['status' => true, 'data' => $parentgamearray]);
			}
	}
	
	public function getbykidinfo(){
		$input_data = $this->request->getJsonRawBody ();
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
		$id = isset( $input_data-> nidara_kid_profile_id)? $input_data-> nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Kid Id is null'
			] );
		}
			 $kid_info = NidaraKidProfile::findFirstByid($id);
			 $kid_value_info = array ();
			 $kid_value_info[] = $kid_info;
			 return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $kid_value_info
				] );
	}
	
	
	public function getbygradeidqution(){
		$input_data = $this->request->getJsonRawBody ();
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
			
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		//return $this->response->setJsonContent(['status' => true, 'data' => $grade_id]);
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$parentquestionval = HealthDevelopmentQuestion::findBygrade_id($grade_id);
		
		$parentvaluearray = array();
		foreach($parentquestionval as $value){
			$parentvaluearray[] = $value;
		}
		return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $parentvaluearray
			] );
	}

	 public function getgameresult(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else if(empty($input_data->visit_no)){
			return $this->response->setJsonContent ( [
			'status' => false,
			'error' => 'No data available'
			] );
		}
		else{
		$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.visit_date as visit_date',
		))->from('DoctorVisit')
		->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
		->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
		->getQuery ()->execute ();
		$visitarray = array();
		foreach($vist_date as $visitvalue){
			$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$visitarray[]=$visit_data;
		}
		$date = date('Y-m-d');
		$scheduled = date('Y-m-d',strtotime($visitvalue->visit_date));
		$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
		$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
		if($date < $scheduled){
			return $this->response->setJsonContent ( [
			'status' => false,
			'message' => 'This visit is scheduled on '.date('d-m-Y',strtotime($visitvalue->visit_date)),
			'error' => 'No data available'
			] );
		}
		else{
			$subject_get = $this->modelsManager->createBuilder ()->columns ( array (
					'Subject.id as subject_id',
					'Subject.subject_name as subject_name',
					'GamesAnswers.nidara_kid_profile_id as nidara_kid_profile_ids',
				))->from('GamesAnswers')
				->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
				->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->orderBy('GuidedLearningDayGameMap.subject_id ASC')
				->groupBy('GuidedLearningDayGameMap.subject_id')
				->getQuery ()->execute ();
				$core_array = array ();
				$subjectarray = array();
				foreach($subject_get as $subvalue){
					$getstandard = $this->modelsManager->createBuilder ()->columns ( array (
						'Standard.id as standard_ids',
						'Standard.standard_name as standard_name',
						'CoreFrameworks.name as core_framework_names',
						'Standard.weightage as weightage'
					))->from('GamesAnswers')
					->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
					->inwhere ('GuidedLearningDayGameMap.subject_id',array($subvalue->subject_id))
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->groupBy('GamesCoreframeMap.standard_id')
					->getQuery ()->execute ();
					$standard_array = array();
					$weightage = 0;
					$total_percentage = 0;
					$total_value = 0;
					$count = +1;
					foreach($getstandard as $standard_value){
						$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesAnswers.answers as answers',
							'GamesAnswers.questions_no as questions_no',
							'GamesAnswers.session_id as session_id',
							'GamesAnswers.game_id as game_id',
						))->from('GamesAnswers')
						->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
						->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
						->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
						->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
						->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
						->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
						->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
						->inwhere ('GuidedLearningDayGameMap.subject_id',array($subvalue->subject_id))
						->inwhere ('GamesCoreframeMap.standard_id',array($standard_value->standard_ids))
						->groupBy ('GamesAnswers.questions_no')
						->getQuery ()->execute ();
						$gamedatabasearray=array();
						$percentage=0;
						foreach ( $gamedatabase as $gamedata ) {
							if($gamedata->questions_no != 0){
							if($gamedata->answers == 1 ){
									$answers = $answers + 1;
									$total = $total + 1;
								}
								else if($gamedata->answers > 1){
									$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
										'GamesQuestionAnswer.game_type_value as game_type_value',
									))->from('GamesQuestionAnswer')
									->inwhere('GamesQuestionAnswer.game_id',array($game_id))
									->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
									->getQuery ()->execute ();
									foreach($game_question_answer as $questionanswer){
										if($questionanswer->game_type_value == $gamedata->answers){
											$answers = $answers + 1;
											$total = $total + 1;
										}
										else{
											$total = $total + 1;
										}
									}
								}
								else{
									$total = $total + 1;
							
								}
							}								
							$gamedatabasearray [] = $game_data;
						}
						$percentage = ((($answers)/($total))*1);
						if(!empty($gamedatabasearray)){
							$totalpercentage=($percentage);
						} 
					$standard_data['standard_ids'] = $standard_value->standard_ids;
					$standard_data['standard_name'] = $standard_value->standard_name;
					$standard_data['weightage'] = $standard_value->weightage;
					$standard_data['gamedatabasearray'] = $gamedatabasearray;
					$weightage += $standard_value->weightage;
					$standard_data['totalpercentage'] = ($totalpercentage * $standard_value->weightage);
					$total_percentage += $standard_data['totalpercentage'];
					$standard_data['length'] = count($getstandard);
					$standard_data['count'] = $count;
					$standard_array[] = $standard_data;
					$count ++;
				}
				$sub_data['total_value'] = round($total_percentage*100);
				$sub_data['total_weightage'] = round($weightage*100);
				if($sub_data['total_weightage'] != 0){
					$sub_data['average'] =  round((($sub_data['total_value'])/($sub_data['total_weightage']))*100);
				}
				else {
					$sub_data['average'] = $sub_data['total_weightage'];
				}
				// $sub_data['total'] = $total_percentage/$weightage;
				$sub_data['subject_id'] = $subvalue->subject_id;
				$sub_data['standard'] = $standard_array;
					if (strpos($subvalue->subject_name, "Education") !== false) {
						$sub_data['subject'] = str_replace("Core Education - ","",$subvalue->subject_name);
					}else if (strpos($subvalue->subject_name, "Interest") !== false) {
						$sub_data['subject'] = str_replace("Core Interest Dev - ","",$subvalue->subject_name);
					}else if (strpos($subvalue->subject_name, "Health") !== false) {
						$sub_data['subject'] = str_replace("Core Health - ","",$subvalue->subject_name);
					} 
				// 
					if($sub_data['average'] >= 90){
						$sub_data['color'] = '#9ccc65';
					}
					else if($sub_data['average'] >= 70){
						$sub_data['color'] = '#ffee58';
					}
					else if($sub_data['average'] < 70){
						$sub_data['color'] = '#ef5350';
					}
					$sub_data['subject_name'] = $subvalue->subject_name;
					$sub_data['enddate'] = $enddate;
					$sub_data['startdate'] = $startdate;
					$core_framework_name = strtolower( str_replace ( ' ', '_', $standard_value->core_framework_names ) );
					$core_array [] = $standard_value->core_framework_names;
					$core_frm_array [$core_framework_name] [] = $sub_data;
					//$subjectarray[] [$core_framework_name] = $sub_data;
				}
				$core_frame = CoreFrameworks::find ();
				foreach ( $core_frame as $core ) {
					if (! in_array ( $core->name, $core_array )) {
						$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
						$core_frm_array [$core->name] = array ();
					}
				}
				return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array,
				'test' => $visitarray
				] );
			}
		}
			
	} 

	
	
		public function getgameresulttwo(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else if(empty($input_data->visit_no)){
			return $this->response->setJsonContent ( [
			'status' => false,
			'error' => 'No data available'
			] );
		}
		else{
		$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.visit_date as visit_date',
		))->from('DoctorVisit')
		->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
		->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
		->getQuery ()->execute ();
		$visitarray = array();
		foreach($vist_date as $visitvalue){
			$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$visitarray[]=$visit_data;
		}
		$date = date('Y-m-d');
		$scheduled = date('Y-m-d',strtotime($visitvalue->visit_date));
		$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
		$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
		if($date < $scheduled){
			return $this->response->setJsonContent ( [
			'status' => false,
			'message' => 'This visit is scheduled on '.date('d-m-Y',strtotime($visitvalue->visit_date)),
			'error' => 'No data available'
			] );
		}
		else{
			$subject_get = $this->modelsManager->createBuilder ()->columns ( array (
					'Subject.id as subject_id',
					'Subject.subject_name as subject_name',
					'GamesAnswers.nidara_kid_profile_id as nidara_kid_profile_ids',
					'GamesAnswers.game_id as game_ids'
				))->from('GamesAnswers')
				->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->orderBy('GuidedLearningDayGameMap.framework_id ASC')
				->orderBy('GuidedLearningDayGameMap.subject_id ASC')
				->groupBy('GuidedLearningDayGameMap.subject_id')
				->getQuery ()->execute ();
				$core_array = array ();
				$subjectarray = array();
				
				foreach($subject_get as $subvalue){
				$getheathcaretag = $this->modelsManager->createBuilder ()->columns (array(
					'GameTaggingTransection.health_parameter as health_parameter',
					'GameTaggingTransection.slidenum as slidenum',
					'GameTaggingTransection.Weightage as weightage',
				))->from('GamesAnswers')
				->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'GameTaggingTransection', 'GameTaggingTransection.GameID = GamesAnswers.game_id' )
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GuidedLearningDayGameMap.subject_id',array($subvalue->subject_id))
				->groupBy('GameTaggingTransection.health_parameter')
				->getQuery ()->execute ();
				$heathcarearray = array();
				foreach($getheathcaretag as $heathcarevalue){
					$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
							'DISTINCT GamesAnswers.answers as answers',
							'GamesAnswers.questions_no as questions_no',
							'GamesAnswers.session_id as session_id',
							'GamesAnswers.game_id as game_id',
							'GamesAnswers.id as id',
							'CoreFrameworks.name as core_framework_names',
							'GameTaggingTransection.Weightage as weightage',
							'GamesAnswers.slide_no as slide_no',
							'GameTaggingTransection.Data_Capture_Parameter as data_capture_parameter',
							'GameTaggingDataCapture.status as status',
						))->from('GamesAnswers')
						->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
						->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
						->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
						->leftjoin ( 'GameTaggingTransection', 'GameTaggingTransection.GameID = GamesAnswers.game_id' )
						->leftjoin ( 'GameTaggingDataCapture', 'GameTaggingTransection.Data_Capture_Parameter = GameTaggingDataCapture.id' )
						->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
						->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
						->inwhere ('GamesAnswers.slide_no',array($heathcarevalue->slidenum))
						->inwhere ('GameTaggingTransection.health_parameter',array($heathcarevalue->health_parameter))
						->getQuery ()->execute ();
						$gamedatabasearray=array();
						$percentage=0;
						$answers = 0;
						$total = 0;
						$actual_weightage = 0;
						$total_weightage = 0;
						foreach ( $gamedatabase as $gamedata ) {
							if($gamedata->slide_no > 1){
							if($gamedata->status == 'Fullgame'){
								$actual_weightage = $gamedata->weightage;
								$total_weightage = $gamedata->weightage;
							}
							else {
								if($gamedata->answers == 1 ){
										$answers = $answers + 1;
										$actual_weightage += $gamedata->weightage;
										$total = $total + 1;
										$total_weightage += $gamedata->weightage;
									}
									else if($gamedata->answers > 1){
										$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
											'GamesQuestionAnswer.game_type_value as game_type_value',
										))->from('GamesQuestionAnswer')
										->inwhere('GamesQuestionAnswer.game_id',array($game_id))
										->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
										->getQuery ()->execute ();
										foreach($game_question_answer as $questionanswer){
											if($questionanswer->game_type_value <= $gamedata->answers){
												$answers = $answers + 1;
												$actual_weightage += $gamedata->weightage;
												$total = $total + 1;
												$total_weightage += $gamedata->weightage;
											}
											else if($questionanswer->game_type_value > $gamedata->answers){
												$answers = $answers + 1;
												$actual_weightage += ($questionanswer->game_type_value/$gamedata->answers) * $gamedata->weightage;
												$total = $total + 1;
												$total_weightage += $gamedata->weightage;
											}
											else{
												$total = $total + 1;
												$total_weightage += $gamedata->weightage;
											}
										}
									}
									else{
										$total = $total + 1;
										$total_weightage += $gamedata->weightage;
								
									}
								}
							}
							$gamedatabasearray [] = $gamedata;
						}
						if($actual_weightage != 0 || $total_weightage != 0){
							$percentage = ((($actual_weightage)/($total_weightage))*100);
						}
						else{
							$percentage = ($actual_weightage);
						}
						
						if(!empty($gamedatabasearray)){
							$totalpercentage=($percentage);
						}
						if(!empty($heathcarevalue -> health_parameter)){
							$heathcare_data['health_parameter'] = $heathcarevalue -> health_parameter;
							$heathcare_data['gamedatabasearray'] = $gamedatabasearray;
							$heathcare_data['total_weightage'] = $total_weightage;
							$heathcare_data['actual_weightage'] = $actual_weightage;
							$heathcare_data['totalpercentage'] = $totalpercentage;
							if($totalpercentage != 0){
								$heathcare_data['total_percentage'] = round($totalpercentage);
							}
							else{
								$heathcare_data['total_percentage'] = round($totalpercentage);
							}
							$heathcare_data['total'] = $heathcare_data['total_percentage'];
							if($heathcare_data['total'] >= 90){
							$heathcare_data['color'] = '#9ccc65';
							}
							else if($heathcare_data['total'] >= 70){
								$heathcare_data['color'] = '#ffee58';
							}
							else if($heathcare_data['total'] < 70){
								$heathcare_data['color'] = '#ef5350';
							}
							$heathcarearray[] = $heathcare_data;
						}
					}
					$sub_data['subject_id'] = $subvalue->subject_id;
					
					if (strpos($subvalue->subject_name, "Education") !== false) {
						$sub_data['subject'] = str_replace("Core Education - ","",$subvalue->subject_name);
					}else if (strpos($subvalue->subject_name, "Interest") !== false) {
						$sub_data['subject'] = str_replace("Core Interest Dev - ","",$subvalue->subject_name);
					}else if (strpos($subvalue->subject_name, "Health") !== false) {
						$sub_data['subject'] = str_replace("Core Health - ","",$subvalue->subject_name);
					} 
					$sub_data['subject_name'] = $subvalue->subject_name;
					$sub_data['enddate'] = $enddate;
					$sub_data['healthcare'] = $heathcarearray;
					$sub_data['startdate'] = $startdate;
					$core_framework_name = strtolower( str_replace ( ' ', '_', $gamedata->core_framework_names ) );
					$core_array [] = $gamedata->core_framework_names;
					$core_frm_array [$core_framework_name] [] = $sub_data;
				}
				
				$core_frame = CoreFrameworks::find ();
				foreach ( $core_frame as $core ) {
					if (! in_array ( $core->name, $core_array )) {
						$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
						$core_frm_array [$core->name] = array ();
					}
				}
				return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array,
				'test' => $visitarray
				] );
			}
		}
			
	} 
	
	
	
	public function gethealthgamevalue(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else if(empty($input_data->visit_no)){
			return $this->response->setJsonContent ( [
			'status' => false,
			'error' => 'No data available'
			] );
		}
		else{
		$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.visit_date as visit_date',
		))->from('DoctorVisit')
		->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
		->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
		->getQuery ()->execute ();
		$visitarray = array();
		foreach($vist_date as $visitvalue){
			$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$visitarray[]=$visit_data;
		}
		$date = date('Y-m-d');
		$scheduled = date('Y-m-d',strtotime($visitvalue->visit_date));
		$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
		$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
		if($date < $scheduled){
			return $this->response->setJsonContent ( [
			'status' => false,
			'message' => 'This visit is scheduled on '.date('d-m-Y',strtotime($visitvalue->visit_date)),
			'error' => 'No data available'
			] );
		}
		else{
			$getheathcaretag = $this->modelsManager->createBuilder ()->columns (array(
				'GamesAnswers.created_at as created_at',
				'GameTaggingTransection.health_parameter as health_parameter',
				'GamesAnswers.game_id as game_id',
				'GamesDatabase.games_name as games_name',
				'GameTaggingTransection.slidenum as slidenum',
				'GameTaggingTransection.Weightage as weightage',
			))->from('GamesAnswers')
			->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
			->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
			->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
			->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
			->leftjoin ( 'GameTaggingTransection', 'GameTaggingTransection.GameID = GamesAnswers.game_id' )
			->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->inwhere ('GuidedLearningDayGameMap.subject_id',array($input_data->subject_id))
			->inwhere ('GameTaggingTransection.health_parameter',array($input_data->health_parameter))
			->groupBy('GamesAnswers.created_at')
			->getQuery ()->execute ();
			$heathcarearray = array();
			foreach($getheathcaretag as $heathcarevalue){
				
				$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
					'DISTINCT GamesAnswers.answers as answers',
					'GamesAnswers.questions_no as questions_no',
					'GamesAnswers.session_id as session_id',
					'GamesAnswers.game_id as game_id',
					'CoreFrameworks.name as core_framework_names',
					'GameTaggingTransection.Weightage as weightage',
					'GamesAnswers.slide_no as slide_no',
					'GamesAnswers.id as id',
					'GameTaggingTransection.Data_Capture_Parameter as data_capture_parameter',
					'GameTaggingTransection.health_parameter as health_parameter',
					'GameTaggingDataCapture.status as status',
				))->from('GamesAnswers')
				->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id')
				->leftjoin ( 'GameTaggingTransection', 'GameTaggingTransection.GameID = GamesAnswers.game_id')
				->leftjoin ( 'GameTaggingDataCapture', 'GameTaggingTransection.Data_Capture_Parameter = GameTaggingDataCapture.id' )
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GamesAnswers.game_id',array($heathcarevalue->game_id))
				->inwhere ('GamesAnswers.slide_no',array($heathcarevalue->slidenum))
				->inwhere ('GameTaggingTransection.health_parameter',array($heathcarevalue->health_parameter))
				->groupBy ('GamesAnswers.questions_no')
				->getQuery ()->execute ();
				$gamedatabasearray=array();
					foreach ( $gamedatabase as $gamedata ) {
						if($gamedata -> slide_no != 1){
							if($gamedata->status == 'Fullgame'){
								$getanswer['question'] = 'Full game playing';
								$getanswer['getanswer'] = 'True';
								$getanswer['color'] = '#9ccc65';
							}
						if($gamedata->questions_no != 0){
							$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
									'GamesQuestionAnswer.question_id as question_ids',
									'GamesQuestionAnswer.question as question',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($gamedata->game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $game_question_value){
									
								}
								$getanswer['question'] = $game_question_value->question;
						}
						else{
							$slide_name = $this->modelsManager->createBuilder ()->columns ( array (
								'GameTaggingSlideCategory.category_name as category_name',
							))->from('GameTaggingTransection')
							->leftjoin('GameTaggingSlideCategory','GameTaggingTransection.Slideid = GameTaggingSlideCategory.id')
							->inwhere('GameTaggingTransection.health_parameter',array($heathcarevalue->health_parameter))
							->inwhere('GameTaggingTransection.GameID',array($gamedata->game_id))
							->inwhere('GameTaggingTransection.slidenum',array($gamedata->slide_no))
							->getQuery ()->execute ();
							foreach($slide_name as $slide_value){
								
							}
							$getanswer['question'] = $slide_value->category_name;
							
						}
						if($gamedata->answers == 1){
								$answer += 1;
								$total += 1;
								$getanswer['getanswer'] = 'True';
								$getanswer['color'] = '#9ccc65';
							}
							else if($gamedata->answers > 1){
								$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
									'GamesQuestionAnswer.question_id as question_ids',
									'GamesQuestionAnswer.question as question',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $questionanswer){
									if($questionanswer->game_type_value == $gamedata->answers){
										$answers = $answers + 1;
										$total = $total + 1;
										$getanswer['getanswer'] = 'True';
										$getanswer['color'] = '#9ccc65';
									}
									else{
										$total = $total + 1;
										$getanswer['getanswer'] = 'False';
										$getanswer['color'] = '#ef5350';
									}
								}
							}
							else{
								$total += 1;
								$getanswer['getanswer'] = 'False';
								$getanswer['color'] = '#ef5350';
							}
						}
						// $getanswer['question'] = $gamedata->question;
						$getanswer['answers'] = $gamedata->answers;
						$getanswer['health_parameter'] = $gamedata->health_parameter;
						$getanswer['slide_no'] = $gamedata->slide_no;
						$getanswer['data_capture_parameter'] = $gamedata->data_capture_parameter;
						$getanswer['status'] = $gamedata->status;
						$getanswer['id'] = $gamedata->id;
						$getanswer['questions_no'] = $gamedata->questions_no;
						$getanswer['session_id'] = $gamedata->session_id;
						$getanswer['game_id'] = $gamedata->game_id;
						$getanswer['weightage'] = $gamedata->weightage;
						$gamedatabasearray [] = $getanswer;
					}
					if(!empty($heathcarevalue -> health_parameter)){
						$heathcare_data['created_at'] = $heathcarevalue -> created_at;
						$heathcare_data['status'] = $gamedata->status;
						$heathcare_data['health_parameter'] = $heathcarevalue -> health_parameter;
						$heathcare_data['game_id'] = $heathcarevalue->game_id;
						$heathcare_data['game_data'] = $gamedatabasearray;
						$heathcare_data['games_name'] = $heathcarevalue->games_name;
						$heathcare_data['weightage'] = $heathcarevalue->weightage;
						$heathcarearray[] = $heathcare_data;
					}
				}
				
			return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $heathcarearray,
			'test' => $visitarray
			] );
		}
		}
			
	} 

	
	
	public function getgameresultbysubject(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else {
			$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
				'DoctorVisit.visit_date as visit_date',
			))->from('DoctorVisit')
			->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
			->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			$visitarray = array();
			foreach($vist_date as $visitvalue){
				 $visit = "2018-11-20";
				$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
				$visitarray[]=$visit_data;
			}
			$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
			$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$getstandard = $this->modelsManager->createBuilder ()->columns ( array (
						'Standard.id as standard_ids',
						'Standard.standard_name as standard_name',
						'CoreFrameworks.name as core_framework_names',
						'Standard.weightage as weightage'
					))->from('GamesAnswers')
					->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
					->inwhere ('GuidedLearningDayGameMap.subject_id',array($input_data->subject_id))
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->groupBy('GamesCoreframeMap.standard_id')
					->getQuery ()->execute ();
					$standard_array = array();
					$weightage = 0;
					$total_percentage = 0;
					$total_value = 0;
					$count = +1;
					foreach($getstandard as $standard_value){
					$game_get = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesAnswers.game_id as game_id',
						'GuidedLearningDayGameMap.framework_id as framework_id',
						'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_ids',
						'GamesDatabase.games_name',
					))->from('GamesAnswers')
					->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->inwhere ('GuidedLearningDayGameMap.subject_id',array($input_data->subject_id))
					->inwhere ('GamesCoreframeMap.standard_id',array($standard_value->standard_ids))
					->groupBy ('GamesAnswers.game_id')
					->getQuery ()->execute ();
						$game_data = array();
						$subject_totalpercentage = 0;
						$game_count = 0;				
						foreach($game_get as $game_get_value){
						$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesAnswers.answers as answers',
						'GamesAnswers.questions_no as questions_no',
						'GamesAnswers.session_id as session_id',
						'GamesAnswers.game_id as game_ids',
						))->from('GamesAnswers')
						->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
						->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
						->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
						->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
						->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
						->inwhere ('GamesAnswers.game_id',array($game_get_value->game_id))
						->groupBy ('GamesAnswers.questions_no')
						->getQuery ()->execute ();
							$gamedatabasearray=array();
							$percentage=0;
							foreach ( $gamedatabase as $gamedata ) {
								if($gamedata->questions_no != 0){
								if($gamedata->answers == 1 ){
									$answers = $answers + 1;
									$total = $total + 1;
								}
								else if($gamedata->answers > 1){
									$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
										'GamesQuestionAnswer.game_type_value as game_type_value',
									))->from('GamesQuestionAnswer')
									->inwhere('GamesQuestionAnswer.game_id',array($game_id))
									->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
									->getQuery ()->execute ();
									foreach($game_question_answer as $questionanswer){
										if($questionanswer->game_type_value == $gamedata->answers){
											$answers = $answers + 1;
											$total = $total + 1;
										}
										else{
											$total = $total + 1;
										}
									}
								}
								else{
									$total = $total + 1;
							
								}
								}
							$gamedatabasearray [] = $gamedata;
							}
							$percentage = ((($answers)/($total))*1);
							if(!empty($gamedatabasearray)){
								$totalpercentage=($percentage);
							}
							else{
								$totalpercentage = 0;
							}
							$subject_totalpercentage += $totalpercentage;
							$game_count += 1;
							$games['day_guided_learning_ids'] = $game_get_value->day_guided_learning_ids;
							$games['game_id'] = $game_get_value->game_id;
							$games['totalpercentage'] = $totalpercentage;
							$games['framework_id'] = $game_get_value->framework_id;
							$games['games_name'] = $game_get_value->games_name;
							$game_data[] = $games;
						}
					$standard_data['standard_ids'] = $standard_value->standard_ids;
					$standard_data['subject_totalpercentage'] = $subject_totalpercentage;
					$standard_data['game_data'] = $game_data;
					$standard_data['standard_name'] = $standard_value->standard_name;
					$standard_data['subject_totalpercentage'] = $subject_totalpercentage;
					$standard_data['game_count'] = $game_count;
					$standard_data['total_percentage'] = ($subject_totalpercentage/$game_count)* $standard_value->weightage;
					$standard_data['weightage'] = ($standard_value->weightage);
					$weightage += $standard_value->weightage;
					if($standard_value->weightage != 0){
						$standard_data['total'] = round(($standard_data['total_percentage'] / $standard_data['weightage'])*100);
					}
					else {
						$standard_data['total'] = $standard_data['weightage'];
					}
							if($standard_data['total'] >= 90){
							$standard_data['color'] = '#9ccc65';
							}
							else if($standard_data['total'] >= 70){
								$standard_data['color'] = '#ffee58';
							}
							else if($standard_data['total'] < 70){
								$standard_data['color'] = '#ef5350';
							}
					$standard_data['length'] = count($getstandard);
					$standard_data['count'] = $count;
					$standard_array[] = $standard_data;
					$count ++;
					}
			return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $standard_array
			] );
		}
	}
	
	public function getindicatorbygame(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else {
			$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.visit_date as visit_date',
			))->from('DoctorVisit')
			->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
			->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			$visitarray = array();
			foreach($vist_date as $visitvalue){
				 $visit = "2018-11-20";
				$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
				$visitarray[]=$visit_data;
			}
			$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
			$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$game_get = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesAnswers.game_id as game_id',
				'GuidedLearningDayGameMap.framework_id as framework_id',
				'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_ids',
				'GamesDatabase.games_name',
				'Indicators.id as indicator_id',
				'Indicators.indicator_name as indicator_name',
			))->from('GamesAnswers')
			->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
			->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
			->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
			->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
			->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
			->leftjoin ( 'Indicators', 'GamesCoreframeMap.indicator_id = Indicators.id')
			->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
			->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->inwhere ('GamesCoreframeMap.standard_id',array($input_data->standard_ids))
			->groupBy ('GamesCoreframeMap.indicator_id')
			->getQuery ()->execute ();
			$game_data = array();
			$subject_totalpercentage = 0;
			$game_count = 0;				
				foreach($game_get as $game_get_value){
					$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesAnswers.answers as answers',
				'GamesAnswers.questions_no as questions_no',
				'GamesAnswers.session_id as session_id',
				'GamesAnswers.game_id as game_ids',
				'GamesDatabase.games_name',
				))->from('GamesAnswers')
				->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
				->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GamesAnswers.game_id',array($game_get_value->game_id))
				->inwhere ('GamesCoreframeMap.indicator_id',array($game_get_value->indicator_id))
				->getQuery ()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					foreach ( $gamedatabase as $gamedata ) {
						if($gamedata->questions_no != 0){
							if($gamedata->answers == 1 ){
								$answers = $answers + 1;
								$total = $total + 1;
							}
							else if($gamedata->answers > 1){
								$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $questionanswer){
									if($questionanswer->game_type_value == $gamedata->answers){
										$answers = $answers + 1;
										$total = $total + 1;
									}
									else{
										$total = $total + 1;
									}
								}
							}
							else{
								$total = $total + 1;
						
							}
						}
					$gamedatabasearray [] = $gamedata;
					}
					$percentage = round((($answers)/($total))*100);
					if(!empty($gamedatabasearray)){
						$totalpercentage=round($percentage);
					}
					else{
						$totalpercentage = 0;
					}
					$subject_totalpercentage += $totalpercentage;
					$game_count += 1;
					$games['day_guided_learning_ids'] = $game_get_value->day_guided_learning_ids;
					$games['game_id'] = $game_get_value->game_id;
					$games['games_name'] = $game_get_value->games_name;
					$games['standard_id'] = $input_data->standard_ids;
					$games['totalpercentage'] = $totalpercentage;
					$games['indicator_id'] = $game_get_value->indicator_id;
					$games['indicator_name'] = $game_get_value->indicator_name;
					$games['total'] = $totalpercentage;
					if($games['total'] >= 90){
					$games['color'] = '#9ccc65';
					}
					else if($games['total'] >= 70){
						$games['color'] = '#ffee58';
					}
					else if($games['total'] < 70){
						$games['color'] = '#ef5350';
					}
					$game_data[] = $games;
				}
				return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $game_data
			] );
		}
	}
	
	public function getgamefilterbysubject(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else {
			$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.visit_date as visit_date',
			))->from('DoctorVisit')
			->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
			->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			$visitarray = array();
			foreach($vist_date as $visitvalue){
				 $visit = "2018-11-20";
				$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
				$visitarray[]=$visit_data;
			}
			$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
			$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$game_get = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesAnswers.game_id as game_id',
				'GuidedLearningDayGameMap.framework_id as framework_id',
				'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_ids',
				'GamesDatabase.games_name',
			))->from('GamesAnswers')
			->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
			->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
			->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
			->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
			->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
			->leftjoin ( 'Indicators', 'GamesCoreframeMap.indicator_id = Indicators.id')
			->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
			->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->inwhere ('GamesCoreframeMap.standard_id',array($input_data->standard_ids))
			->inwhere ('GamesCoreframeMap.indicator_id',array($input_data->indicator_id))
			->groupBy('GamesAnswers.game_id')
			->getQuery ()->execute ();
			
				$game_data = array();
				$subject_totalpercentage = 0;
				$game_count = 0;				
				foreach($game_get as $game_get_value){
				$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesAnswers.answers as answers',
				'GamesAnswers.questions_no as questions_no',
				'GamesAnswers.session_id as session_id',
				'GamesAnswers.game_id as game_ids',
				'GamesDatabase.games_name',
				))->from('GamesAnswers')
				->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
				->leftjoin ( 'GamesCoreframeMap', 'GuidedLearningDayGameMap.game_id = GamesCoreframeMap.game_id')
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GamesAnswers.game_id',array($game_get_value->game_id))
				->inwhere ('GamesCoreframeMap.indicator_id',array($input_data->indicator_id))
				->groupBy('GamesAnswers.questions_no')
				->getQuery ()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					foreach ( $gamedatabase as $gamedata ) {
						if($gamedata->questions_no != 0){
							if($gamedata->answers == 1 ){
								$answers = $answers + 1;
								$total = $total + 1;
							}
							else if($gamedata->answers > 1){
								$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($gamedata->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $questionanswer){
									if($questionanswer->game_type_value == $gamedata->answers){
										$answers = $answers + 1;
										$total = $total + 1;
									}
									else{
										$total = $total + 1;
									}
								}
							}
							else{
								$total = $total + 1;
						
							}
						}
					$gamedatabasearray [] = $gamedata;
					}
					if($answers != 0 || $total != 0){
						$percentage = round((($answers)/($total))*100);
					}
					else {
						$percentage = 0;
					}
					if(!empty($gamedatabasearray)){
						$totalpercentage=round($percentage);
					}
					else{
						$totalpercentage = 0;
					}
					$subject_totalpercentage += $totalpercentage;
					$game_count += 1;
					$games['total'] = $totalpercentage;
					if($games['total'] >= 90){
					$games['color'] = '#9ccc65';
					}
					else if($games['total'] >= 70){
						$games['color'] = '#ffee58';
					}
					else if($games['total'] < 70){
						$games['color'] = '#ef5350';
					}
					$games['day_guided_learning_ids'] = $game_get_value->day_guided_learning_ids;
					$games['game_id'] = $game_get_value->game_id;
					$games['framework_id'] = $game_get_value->framework_ids;
					$games['games_name'] = $gamedata->games_name;
					$game_data[] = $games;
				}
			return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $game_data
			] );
		}
	}
	
	
	public function getgameinfobyid(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else{
			$vist_date = $this->modelsManager->createBuilder ()->columns ( array (
			'DoctorVisit.visit_date as visit_date',
			))->from('DoctorVisit')
			->inwhere('DoctorVisit.visit_no',array($input_data->visit_no))
			->inwhere('DoctorVisit.child_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			$visitarray = array();
			foreach($vist_date as $visitvalue){
				 $visit = "2018-11-20";
				$visit_data['days'] = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
				$visitarray[]=$visit_data;
			}
			$enddate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date));
			$startdate = date('Y-m-d H:i:s',strtotime($visitvalue->visit_date .'-90days'));
			$game_get = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesAnswers.created_at as created_ats',
				'GamesAnswers.game_id as game_ids',
				'GamesDatabase.games_name',
				'GuidedLearningDayGameMap.subject_id as subject_id',
				'GuidedLearningDayGameMap.framework_id as framework_id',
			))->from('GamesAnswers')
			->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
			->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
			->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
			->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
			->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
			->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			$gamedata_array = array();
			foreach($game_get as $value){
				$gamedetails = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesAnswers.questions_no as questions_no',
			))->from('GamesAnswers')
			->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->inwhere ('GamesAnswers.created_at ',array($value->created_ats))
			->orderBy ( 'GamesAnswers.questions_no' )
			->groupBy('GamesAnswers.questions_no')
			->getQuery ()->execute ();
			$total_time = 0;
			$gamedetailsarray = array();
			foreach($gamedetails as $gameanswer){
				$game_result = $this->modelsManager->createBuilder ()->columns ( array (
					'DISTINCT GamesAnswers.questions_no as questions_nos',
					'GamesAnswers.answers as answers',
					'GamesAnswers.time as time',
				))->from('GamesAnswers')
				->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GamesAnswers.created_at ',array($value->created_ats))
				->getQuery ()->execute ();
				$time = 0;
				$getanswer;
				$answer = 0;
				$total = 0;
				$game_result_array = array();
				foreach($game_result as $result_value) {
					if($result_value->questions_nos != 0){
						$game_que_ans = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesQuestionAnswer.question_id as question_ids',
							'GamesQuestionAnswer.question as question',
							'GamesQuestionAnswer.game_type_value as game_type_value',
						))->from('GamesQuestionAnswer')
						->inwhere('GamesQuestionAnswer.game_id',array($input_data->game_id))
						->inwhere('GamesQuestionAnswer.question_id',array($result_value->questions_nos))
						->getQuery ()->execute ();
						foreach($game_que_ans as $game_result_value){
							
						}
						if($result_value->answers == 1){
							$answer += 1;
							$total += 1;
							$getanswer = 'True';
						}
						else if($result_value->answers > 1){
							if($game_result_value->game_type_value == $result_value->answers){
								$answer += 1;
								$total += 1;
								$getanswer = 'True';
							}
							else{
								$total += 1;
								$getanswer = 'False';
							}
						}
						else{
							$total += 1;
							$getanswer = 'False';
						}
					}
					$game_result_data['questions_no'] = $result_value->questions_nos;
					$game_result_data['question'] = $game_result_value->question;
					$game_result_data['game_type_value'] = $game_result_value->game_type_value;
					$game_result_data['answers'] = $getanswer;
					$game_result_data['time'] = ($result_value->time - $time);
					$time = $result_value->time;
					$game_result_array[] = $game_result_data;
				}
			}
				$game_data['created_at'] = $value->created_ats;			
				$game_data['game_answers'] = $game_result_array;
				$game_data['Total'] = $total ;
				$game_data['answer'] = $answer ;
				$game_data['wrong'] = $total - $answer;
				$game_data['game_id'] = $value->game_ids;
				$game_data['game_time'] = $result_value->time;
				$game_data['game_name'] = $value->games_name;
				$game_data['subject_id'] = $value->subject_id;
				$game_data['framework_id'] = $value->framework_id;
				$gamedata_array [] = $game_data;
			}
			return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $gamedata_array
			] );
		}
	}
	
	
		public function getgameinfofilterbyid(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else{
			$game_get = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesAnswers.created_at as created_ats',
				'GamesAnswers.game_id as game_ids',
				'GamesDatabase.games_name',
				'GuidedLearningDayGameMap.subject_id as subject_id',
				'GuidedLearningDayGameMap.framework_id as framework_id',
			))->from('GamesAnswers')
			->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
			->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
			->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
			->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
			->inwhere ('GamesAnswers.created_at',array($input_data->dates))
			->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();
			$gamedata_array = array();
			foreach($game_get as $value){
				$gamedetails = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesAnswers.questions_no as questions_no',
			))->from('GamesAnswers')
			->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
			->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->inwhere ('GamesAnswers.created_at ',array($value->created_ats))
			->orderBy ( 'GamesAnswers.questions_no' )
			->getQuery ()->execute ();
			$total_time = 0;
			$gamedetailsarray = array();
			foreach($gamedetails as $gameanswer){
				$game_result = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesAnswers.questions_no as questions_nos',
					'GamesAnswers.answers as answers',
					'GamesAnswers.time as time',
				))->from('GamesAnswers')
				->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GamesAnswers.created_at ',array($value->created_ats))
				->getQuery ()->execute ();
				$time = 0;
				$getanswer;
				$answer = 0;
				$total = 0;
				$game_result_array = array();
				foreach($game_result as $result_value){
					$game_que_ans = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesQuestionAnswer.question_id as question_ids',
						'GamesQuestionAnswer.question as question',
						'GamesQuestionAnswer.game_type_value as game_type_value',
					))->from('GamesQuestionAnswer')
					->inwhere('GamesQuestionAnswer.game_id',array($input_data->game_id))
					->inwhere('GamesQuestionAnswer.question_id',array($result_value->questions_nos))
					->getQuery ()->execute ();
					foreach($game_que_ans as $game_result_value){
						
					}
					if($result_value->answers == 1){
						$answer += 1;
						$total += 1;
						$getanswer = 'True';
					}
					else if($result_value->answers > 1){
						if($game_result_value->game_type_value == $result_value->answers){
							$answer += 1;
							$total += 1;
							$getanswer = $result_value->answers;
						}
						else{
							$total += 1;
							$getanswer = $result_value->answers;
						}
					}
					else{
						$total += 1;
						$getanswer = 'False';
					}
					$game_result_data['questions_no'] = $result_value->questions_nos;
					$game_result_data['question'] = $game_result_value->question;
					$game_result_data['game_type_value'] = $game_result_value->game_type_value;
					$game_result_data['answers'] = $getanswer;
					$game_result_data['time'] = ($result_value->time - $time);
					$time = $result_value->time;
					$game_result_array[] = $game_result_data;
				}
			}
				$game_data['created_at'] = $value->created_ats;			
				$game_data['game_answers'] = $game_result_array;
				$game_data['Total'] = $total ;
				$game_data['answer'] = $answer ;
				$game_data['wrong'] = $total - $answer;
				$game_data['game_id'] = $value->game_ids;
				$game_data['game_time'] = $result_value->time;
				$game_data['game_name'] = $value->games_name;
				$game_data['subject_id'] = $value->subject_id;
				$game_data['framework_id'] = $value->framework_id;
				$gamedata_array [] = $game_data;
			}
			return $this->response->setJsonContent ( [
			'status' => true,
			'data' => $gamedata_array
			] );
		}
	}
	
	
	public function sendparentanswer(){
		$input_data = $this->request->getJsonRawBody ();
		$email_id = isset( $input_data-> email_id)? $input_data-> email_id : '';
		$gamedetails_answer = $this->modelsManager->createBuilder ()->columns ( array (
			'HealthCampParentAnswers.parent_answer as parent_answer',
			'HealthCampQuestion.question as question',
		))->from('HealthCampParentAnswers')
		->leftjoin ('HealthCampQuestion','HealthCampParentAnswers.parent_question_id = HealthCampQuestion.id')
		->inwhere('HealthCampParentAnswers.nidara_parent_email',array($email_id))
		->getQuery()->execute ();
		$answer = '';
		foreach($gamedetails_answer as $value){
			$answer .='<div style="width: 100%;float: left;padding: 10px;"><div class="" style="width: 45%;float: left;border: 1px solid #e5e5e5;padding: 0px 10px;">';
			$answer .='<p>'. $value->question .'</p></div>';
			$answer .='<ul style="list-style: none;width: 45%;float: left;padding: 0px;">';
			if($value->parent_answer == 'yes'){
				
				$answer .='<li style="float: left;padding: 0px 10px;"> <span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;background: #83d0c9;"></span> <span>Yes</span></li>
				<li style="float: left;padding: 0px 10px;"><span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;"></span> <span>No</span></li>
				<li style="float: left;padding: 0px 10px;"><span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;"></span> <span>Sometime</span></li>';
				
			}
			else if($value->parent_answer == 'no'){
				
				$answer .='<li style="float: left;padding: 0px 10px;"> <span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;"></span> <span>Yes</span></li>
				<li style="float: left;padding: 0px 10px;"><span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px; background: #83d0c9;"></span> <span>No</span></li>
				<li style="float: left;padding: 0px 10px;"><span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;"></span> <span>Sometime</span></li>';
				
				
				
			}
			else if($value->parent_answer == 'some_time'){
				
				$answer .='<li style="float: left;padding: 0px 10px;"> <span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;"></span> <span>Yes</span></li>
				<li style="float: left;padding: 0px 10px;"><span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px;"></span> <span>No</span></li>
				<li style="float: left;padding: 0px 10px;"><span style="width: 20px;height: 20px;float: left;border: 2px solid #e5e5e5;border-radius: 100%;top: 0px;position: relative;margin-right: 5px; background: #83d0c9;"></span> <span>Sometime</span></li>';
				
				
				
			}
			$answer .='</ul></div>';
		}
		$answer .='';
		
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'baskar@haselfre.com';                 // SMTP username
		$mail->Password = 'k6zcNyXJw27MCT0j';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('customersupport@haselfre.com', 'Nidara-Children');
		$mail->addAddress($email_id, '');     // Add a recipient
																// Name is optional
		$mail->addReplyTo('customersupport@haselfre.com', 'Information');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'NC Healthy Child Development Camp - Anawer ';
		$mail->Body    = '
						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

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
       
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

        
        <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
          <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
         <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
           <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
         </div>
         
         <div class="sub-mail-cont" style="width: 100%;">
          '. $answer .'
         </div>
          
          <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
         <p style="line-height: 18px;">Best regards,</p>
         <p style="line-height: 18px;">
          </p>
          <p style="line-height: 18px;">Nidara Children</p>
        </div>
        <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
          <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
           <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
           <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
           <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
           <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
         </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
         <br /><span>You are receiving this email because you opted in at our website.
          </span>
          
          <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
        </div>
         </div>
       </div>
       
</td></tr></table>
</body>
</html>

		';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
			] );
			
		} else {
		
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Message has been sent' 
			] );
		}
	}
}

