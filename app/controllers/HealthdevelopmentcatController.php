<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class HealthdevelopmentcatController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$daily_routine = HealthDevelopmentCatagory::find ();
		if ($daily_routine) :
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$daily_routine
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faield' 
			] );
		endif;
	}
	
	
	public function getschoolresult(){
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
						'CoreFrameworks.name as framework_name',
						'Subject.subject_name as subject_name',
						'GamesAnswers.nidara_kid_profile_id as nidara_kid_profile_ids',
					))->from('GamesAnswers')
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'" AND GamesAnswers.questions_no != 0')
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->orderBy('GamesCoreframeMap.subject_id ASC')
					->groupBy('GamesCoreframeMap.subject_id')
					->getQuery ()->execute ();
					$core_array = array ();
					$subjectarray = array();
					foreach($subject_get as $subvalue){
						$getstandard = $this->modelsManager->createBuilder ()->columns ( array (
							'Standard.id as standard_ids',
							'Standard.standard_name as standard_name',
							'CoreFrameworks.name as core_framework_names',
							'Standard.weightage as weightage',
							'GamesAnswers.questions_no as questions_no',
							'GamesAnswers.answers as answers',
							'GamesAnswers.session_id as session_id',
							'GamesAnswers.game_id as game_id',
							'GamesAnswers.id as id',
						))->from('GamesAnswers')
						->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
						->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
						->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
						->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
						->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
						->inwhere ('GamesCoreframeMap.subject_id',array($subvalue->subject_id))
						->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
						->groupBy('GamesAnswers.id')
						->getQuery ()->execute ();
						$gameanswerarray = array();
						$totalweight = 0;
						$answerweight = 0;
						foreach($getstandard as $getstandard_value){
							if($getstandard_value-> questions_no != 0){
								if($getstandard_value->answers == 1 ){
									$answerweight += $getstandard_value->weightage;
									$totalweight += $getstandard_value->weightage;
								}
								else if($gamedata->answers > 1){
									$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
										'GamesQuestionAnswer.game_type_value as game_type_value',
									))->from('GamesQuestionAnswer')
									->inwhere('GamesQuestionAnswer.game_id',array($game_id))
									->inwhere('GamesQuestionAnswer.question_id',array($getstandard_value->questions_no))
									->getQuery ()->execute ();
									foreach($game_question_answer as $questionanswer){
										if($questionanswer->game_type_value == $gamedata->answers){
											$answerweight += $getstandard_value->weightage;
											$totalweight += $getstandard_value->weightage;
										}
										else{
											$totalweight += $getstandard_value->weightage;
										}
									}
								}
								else{
									$totalweight += $getstandard_value->weightage;	
								}
							}
						}
						if($answerweight != 0 || $totalweight != 0){
							$sub_data['average'] = round(($answerweight/$totalweight)*100);
						}
						if($sub_data['average'] >= 90){
							$sub_data['color'] = '#9ccc65';
						}
						else if($sub_data['average'] >= 70){
							$sub_data['color'] = '#ffee58';
						}
						else if($sub_data['average'] < 70){
							$sub_data['color'] = '#ef5350';
						}
						$sub_data['subject_id'] = $subvalue-> subject_id;
						$sub_data['framework_name'] = $subvalue-> framework_name;
						if (strpos($subvalue->subject_name, "Education") !== false) {
							$sub_data['subject'] = str_replace("Core Education - ","",$subvalue->subject_name);
						}else if (strpos($subvalue->subject_name, "Interest") !== false) {
							$sub_data['subject'] = str_replace("Core Interest Dev - ","",$subvalue->subject_name);
						}else if (strpos($subvalue->subject_name, "Health") !== false) {
							$sub_data['subject'] = str_replace("Core Health - ","",$subvalue->subject_name);
						} 
						$core_framework_name = strtolower( str_replace ( ' ', '_', $subvalue->framework_name ) );
						$core_array [] = $subvalue->framework_name;
						$core_frm_array [$core_framework_name] [] = $sub_data;
					}
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
			] );
		}
	}
	
	public function getschoolresultbystandid(){
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
				$getstandardname = $this->modelsManager->createBuilder ()->columns ( array (
					'Standard.id as standard_ids',
					'Standard.standard_name as standard_name',
				))->from('GamesAnswers')
				->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesCoreframeMap.subject_id',array($input_data -> subject_id))
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->groupBy('Standard.id')
				->getQuery ()->execute ();
				$standardarray = array ();
				foreach($getstandardname as $getstandardname_value){
					$getstandard = $this->modelsManager->createBuilder ()->columns ( array (
						'CoreFrameworks.name as core_framework_names',
						'Standard.weightage as weightage',
						'GamesAnswers.questions_no as questions_no',
						'GamesAnswers.answers as answers',
						'GamesAnswers.session_id as session_id',
						'GamesAnswers.game_id as game_id',
						'GamesAnswers.id as id',
					))->from('GamesAnswers')
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'" AND GamesAnswers.questions_no != 0')
					->inwhere ('GamesCoreframeMap.standard_id',array($getstandardname_value -> standard_ids))
					->inwhere ('GamesCoreframeMap.subject_id',array($input_data -> subject_id))
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->groupBy('GamesAnswers.id')
					->getQuery ()->execute ();
					$gameanswerarray = array();
					$totalweight = 0;
					$answerweight = 0;
					foreach($getstandard as $getstandard_value){
						if($getstandard_value-> questions_no != 0){
							if($getstandard_value->answers == 1 ){
								$answerweight += $getstandard_value->weightage;
								$totalweight += $getstandard_value->weightage;
							}
							else if($gamedata->answers > 1){
								$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($getstandard_value->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $questionanswer){
									if($questionanswer->game_type_value == $gamedata->answers){
										$answerweight += $getstandard_value->weightage;
										$totalweight += $getstandard_value->weightage;
									}
									else{
										$totalweight += $getstandard_value->weightage;
									}
								}
							}
							else{
								$totalweight += $getstandard_value->weightage;	
							}
						}
						$gameanswerarray[] = $getstandard_value;
					}
					$standard_data['gameanswerarray'] = $gameanswerarray;
					if($answerweight != 0 || $totalweight != 0){
						$standard_data['average'] = round(($answerweight/$totalweight)*100);
					}
					
					if($standard_data['average'] >= 90){
						$standard_data['color'] = '#9ccc65';
					}
					else if($standard_data['average'] >= 70){
						$standard_data['color'] = '#ffee58';
					}
					else if($standard_data['average'] < 70){
						$standard_data['color'] = '#ef5350';
					}
					$standard_data['standard_ids'] = $getstandardname_value -> standard_ids;
					$standard_data['subject'] = $input_data -> subject_id;
					$standard_data['total_percentage'] = $standard_data['average'];
					$standard_data['standard_name'] = $getstandardname_value -> standard_name;
					$standardarray[] = $standard_data;
				}
			}
			return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $standardarray,
			] );
		}
	}
	
	public function getschoolresultbyindicator(){
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
				$game_get = $this->modelsManager->createBuilder ()->columns ( array (
					'Indicators.id as indicator_id',
					'Indicators.indicator_name as indicator_name',
				))->from('GamesAnswers')
				->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
				->leftjoin ( 'Indicators', 'GamesCoreframeMap.indicator_id = Indicators.id')
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere ('GamesCoreframeMap.subject_id',array($input_data->subject_id))
				->inwhere ('GamesCoreframeMap.standard_id',array($input_data->standard_ids))
				->groupBy ('GamesCoreframeMap.indicator_id')
				->getQuery ()->execute ();
				$indicatorarray = array();
				foreach($game_get as $game_get_value){
					$getstandard = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesAnswers.questions_no as questions_no',
						'GamesAnswers.answers as answers',
						'GamesAnswers.session_id as session_id',
						'GamesAnswers.game_id as game_id',
						'GamesAnswers.id as id',
					))->from('GamesAnswers')
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->leftjoin ( 'Indicators', 'GamesCoreframeMap.indicator_id = Indicators.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'" AND GamesAnswers.questions_no != 0')
					->inwhere ('GamesCoreframeMap.indicator_id',array($game_get_value -> indicator_id))
					->inwhere ('GamesCoreframeMap.standard_id',array($input_data -> standard_ids))
					->inwhere ('GamesCoreframeMap.subject_id',array($input_data -> subject_id))
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->groupBy('GamesAnswers.id')
					->getQuery ()->execute ();
					$gameanswerarray = array();
					$totalweight = 0;
					$answerweight = 0;
					foreach($getstandard as $getstandard_value){
						if($getstandard_value-> questions_no != 0){
							if($getstandard_value->answers == 1 ){
								$answerweight++;
								$totalweight++;
							}
							else if($gamedata->answers > 1){
								$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($getstandard_value->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $questionanswer){
									if($questionanswer->game_type_value == $gamedata->answers){
										$answerweight++;
										$totalweight++;
									}
									else{
										$totalweight++;
									}
								}
							}
							else{
								$totalweight++;	
							}
						}
						$gameanswerarray[] = $getstandard_value;
					}
					$standard_data['gameanswerarray'] = $gameanswerarray;
					if($answerweight != 0 || $totalweight != 0){
						$standard_data['average'] = round(($answerweight/$totalweight)*100);
					}
					else{
						$standard_data['average'] = 0;
					}
					
					if($standard_data['average'] >= 90){
						$standard_data['color'] = '#9ccc65';
					}
					else if($standard_data['average'] >= 70){
						$standard_data['color'] = '#ffee58';
					}
					else if($standard_data['average'] < 70){
						$standard_data['color'] = '#ef5350';
					}
					$standard_data['indicator_id'] = $game_get_value -> indicator_id;
					$standard_data['standard_id'] = $input_data -> standard_ids;
					$standard_data['subject'] = $input_data -> subject_id;
					$standard_data['total_percentage'] = $standard_data['average'];
					$standard_data['indicator_name'] = $game_get_value -> indicator_name;
					$indicatorarray[] = $standard_data;
				}
			}
			return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $indicatorarray,
			] );
		}
	}
	
	public function getgameresultbyid(){
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
				$get_game = $this->modelsManager->createBuilder ()->columns ( array (
					'GamesAnswers.game_id as game_id',
					'GamesAnswers.id as id',
					'GamesDatabase.games_name as games_name'
				))->from('GamesAnswers')
				->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
				->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
				->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
				->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
				->leftjoin ( 'Indicators', 'GamesCoreframeMap.indicator_id = Indicators.id')
				->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'"')
				->inwhere ('GamesCoreframeMap.indicator_id',array($input_data -> indicator_id))
				->inwhere ('GamesCoreframeMap.standard_id',array($input_data -> standard_ids))
				->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->groupBy('GamesAnswers.game_id')
				->getQuery ()->execute ();
				$gamearray = array();
				foreach($get_game as $get_game_value){
					$getstandard = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesAnswers.questions_no as questions_no',
						'GamesAnswers.answers as answers',
						'GamesAnswers.session_id as session_id',
						'GamesAnswers.game_id as game_id',
						'GamesAnswers.id as id',
					))->from('GamesAnswers')
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = GamesAnswers.game_id')
					->leftjoin ( 'Subject', 'GamesCoreframeMap.subject_id=Subject.id' )
					->leftjoin ( 'CoreFrameworks', 'GamesCoreframeMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Standard', 'GamesCoreframeMap.standard_id = Standard.id')
					->leftjoin ( 'Indicators', 'GamesCoreframeMap.indicator_id = Indicators.id')
					->where ('GamesAnswers.created_at < "'. $enddate .'" AND GamesAnswers.created_at > "'. $startdate .'" AND GamesAnswers.questions_no != 0')
					->inwhere('GamesAnswers.game_id',array($get_game_value -> game_id))
					->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->groupBy('GamesAnswers.id')
					->getQuery ()->execute ();
					$gameanswerarray = array();
					$totalweight = 0;
					$answerweight = 0;
					foreach($getstandard as $getstandard_value){
						if($getstandard_value-> questions_no != 0){
							if($getstandard_value->answers == 1 ){
								$answerweight++;
								$totalweight++;
							}
							else if($gamedata->answers > 1){
								$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
									'GamesQuestionAnswer.game_type_value as game_type_value',
								))->from('GamesQuestionAnswer')
								->inwhere('GamesQuestionAnswer.game_id',array($game_id))
								->inwhere('GamesQuestionAnswer.question_id',array($getstandard_value->questions_no))
								->getQuery ()->execute ();
								foreach($game_question_answer as $questionanswer){
									if($questionanswer->game_type_value == $gamedata->answers){
										$answerweight++;
										$totalweight++;
									}
									else{
										$totalweight++;
									}
								}
							}
							else{
								$totalweight++;	
							}
						}
						$gameanswerarray[] = $getstandard_value;
					}
					$standard_data['gameanswerarray'] = $gameanswerarray;
					if($answerweight != 0 || $totalweight != 0){
						$standard_data['average'] = round(($answerweight/$totalweight)*100);
					}
					else{
						$standard_data['average'] = 0;
					}
					
					if($standard_data['average'] >= 90){
						$standard_data['color'] = '#9ccc65';
					}
					else if($standard_data['average'] >= 70){
						$standard_data['color'] = '#ffee58';
					}
					else if($standard_data['average'] < 70){
						$standard_data['color'] = '#ef5350';
					}
					$standard_data['game_id'] = $get_game_value -> game_id;
					$standard_data['total_percentage'] = $standard_data['average'];
					$standard_data['games_name'] = $get_game_value -> games_name;
					$gamearray[] = $standard_data;
				}
			}
			return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $gamearray,
			] );
		}
	}
}

