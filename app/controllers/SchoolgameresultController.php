<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
class SchoolgameresultController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	public function viewall() {
		$timetable = SchoolGamesAnswers::find ();
		if ($timetable) :
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$timetable
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed',
				        "data"=>array() 
			] );
		endif;
	}
	
	public function getschoollevelgameanswer(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		$fromdate = isset ( $input_data->fromdate ) ? $input_data->fromdate : '';
		$todate = isset ( $input_data->todate ) ? $input_data->todate : '';
		if(empty($fromdate)){
			$fromdate = date('Y-m-d');
		}
		if(empty($todate)){
			$todate = date('Y-m-d');
		}
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else{
				$getgameanser = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
				))->from("KidSchoolMap")
				->leftjoin('NidaraSchoolKidProfile','KidSchoolMap.nidara_kid_profile_id = NidaraSchoolKidProfile.id')
				->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->leftjoin('SchoolDailyAttendance','SchoolDailyAttendance.school_id = Schools.id')
				->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
				->inwhere('SchoolUserMap.users_id',array($user_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
				$gameresultarray = array();
				$coutinchild = 0;
				$schoolvalue = 0;
				foreach($getgameanser as $value){
					$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolGamesAnswers.answers as answers',
						'SchoolGamesAnswers.questions_no as questions_no',
						'SchoolGamesAnswers.session_id as session_id',
						'SchoolGamesAnswers.game_id as game_id',
					))->from('SchoolGamesAnswers')
					->where ('SchoolGamesAnswers.created_at <= "'. $todate .'" AND SchoolGamesAnswers.created_at >= "'. $fromdate .'"')
					->inwhere('SchoolGamesAnswers.nidara_kid_profile_id',array($value -> ncs_id))
					->groupBy('SchoolGamesAnswers.id')
					->getQuery()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					$totalpercentage = 0;
					$answers = 0;
					$total = 0;
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
						if($answers != 0 || $total != 0){
							$percentage = ((($answers)/($total))*1);
						}
						else{
							$percentage = 0;
						}
						if(!empty($gamedatabasearray)){
							$totalpercentage=($percentage);
						}
					$coutinchild++;
					$schoolvalue += $totalpercentage;
					$getgameanser_value['id'] = $value -> id;
					$getgameanser_value['ncs_id'] = $value -> ncs_id;
					$getgameanser_value['totalpercentage'] = $totalpercentage;
					$gameresultarray[] = $getgameanser_value;
				}
				if($totalpercentage != 0 || $coutinchild != 0){
					$total_value = round(($schoolvalue/$coutinchild)*100);
				}
				else{
					$total_value = 0;
				}
				if($total_value >= 90){
						$color = '#9ccc65';
						$value_text = 'Secure';
					}
					else if($total_value >= 70){
						$color = '#ffee58';
						$value_text = 'Progressing';
					}
					else if($total_value != 0 && $total_value < 70){
						$color = '#ef5350';
						$value_text = 'Apprehension';
					}
					else {
						$color = '#e2e2e2';
						$value_text = 'N/A';
					}
			}
			return $this->response->setJsonContent ( [
			"status" => true,
			"data" => $gameresultarray,
			"count" => $coutinchild,
			"total_value" => $total_value,
			"color" => $color,
			"text" => $value_text
		] );
	}
	
	public function getschoolclasslevel(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		$fromdate = isset ( $input_data->fromdate ) ? $input_data->fromdate : '';
		$todate = isset ( $input_data->todate ) ? $input_data->todate : '';
		if(empty($fromdate)){
			$fromdate = date('Y-m-d');
		}
		if(empty($todate)){
			$todate = date('Y-m-d');
		}
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else{
			$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT SchoolTimetable.class_id as class_id',
				'SchoolTimetable.section_id as section_id',
				'SchoolTimetable.school_id as school_id',
				'Classes.class_name as class_name',
				'Sections.section_name as section_name',
			))->from("SchoolTimetable")
			->leftjoin('Schools','SchoolTimetable.school_id = Schools.id')
			->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
			->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
			->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->inwhere('SchoolUserMap.users_id',array($user_id))
			->getQuery()->execute ();
			$classarray = array();
			foreach($getvalue as $value){
				$gettimetable = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
				))->from("KidSchoolMap")
				->leftjoin('NidaraSchoolKidProfile','KidSchoolMap.nidara_kid_profile_id = NidaraSchoolKidProfile.id')
				->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->leftjoin('SchoolDailyAttendance','SchoolDailyAttendance.school_id = Schools.id')
				->inwhere('KidSchoolMap.schools_id',array($value -> school_id))
				->inwhere('KidSchoolMap.class_id',array($value -> class_id))
				->inwhere('KidSchoolMap.sections_id',array($value -> section_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
				$childinfoarray = array();
				$coutinchild = 0;
				$schoolvalue = 0;
				foreach($gettimetable as $value2){
					$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolGamesAnswers.answers as answers',
						'SchoolGamesAnswers.questions_no as questions_no',
						'SchoolGamesAnswers.session_id as session_id',
						'SchoolGamesAnswers.game_id as game_id',
					))->from('SchoolGamesAnswers')
					->where ('SchoolGamesAnswers.created_at <= "'. $todate .'" AND SchoolGamesAnswers.created_at >= "'. $fromdate .'"')
					->inwhere('SchoolGamesAnswers.nidara_kid_profile_id',array($value2 -> ncs_id))
					->groupBy('SchoolGamesAnswers.id')
					->getQuery()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					$totalpercentage = 0;
					$answers = 0;
					$total = 0;
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
						if($answers != 0 || $total != 0){
							$percentage = ((($answers)/($total))*1);
						}
						else{
							$percentage = 0;
						}
						if(!empty($gamedatabasearray)){
							$totalpercentage=($percentage);
						}
					$coutinchild++;
					$schoolvalue += $totalpercentage;
					$getgameanser_value['id'] = $value2 -> id;
					$getgameanser_value['ncs_id'] = $value2 -> ncs_id;
					$getgameanser_value['totalpercentage'] = $totalpercentage;
					$childinfoarray[] = $getgameanser_value;
				}
				if($schoolvalue != 0 || $coutinchild != 0){
					$classdata['total_value'] = round(($schoolvalue/$coutinchild)*100);
				}
				else{
					$classdata['total_value'] = 0;
				}
				if($classdata['total_value'] >= 90){
					$classdata['color'] = '#9ccc65';
					$classdata['text'] = 'Secure';
				}
				else if($classdata['total_value'] >= 70){
					$classdata['color'] = '#ffee58';
					$classdata['text'] = 'Progressing';
				}
				else if($classdata['total_value'] != 0 && $classdata['total_value'] < 70){
					$classdata['color'] = '#ef5350';
					$classdata['text'] = 'Apprehension';
				}
				else {
					$classdata['color'] = '#e2e2e2';
					$classdata['text'] = 'N/A';
				}
				$classdata['class_id'] = $value -> class_id;
				$classdata['schoolvalue'] = $schoolvalue;
				$classdata['coutinchild'] = $coutinchild;
				$classdata['section_id'] = $value -> section_id;
				$classdata['class_name'] = $value -> class_name;
				$classdata['section_name'] = $value -> section_name;
				$classdata['childinfo'] = $childinfoarray;
				$classarray[] = $classdata;
			}
			return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $classarray,
			] );
		}
	}
	
	
	public function getclasslevelsubject(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		$fromdate = isset ( $input_data->fromdate ) ? $input_data->fromdate : '';
		$todate = isset ( $input_data->todate ) ? $input_data->todate : '';
		if(empty($fromdate)){
			$fromdate = date('Y-m-d');
		}
		if(empty($todate)){
			$todate = date('Y-m-d');
		}
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else {
			$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesCoreframeMap.subject_id as subject_id',
				'Subject.subject_name as subject_name',
			))->from("GamesCoreframeMap")
			->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
			->groupBy('GamesCoreframeMap.subject_id')
			->getQuery()->execute ();
			$classarray = array();
			foreach($getvalue as $value){
				$gamedatabase2 = $this->modelsManager->createBuilder ()->columns ( array (
					'DISTINCT Classes.id as class_id',
					'Sections.id as section_id',
					'Classes.class_name as class_name',
					'Sections.section_name as section_name',
				))->from("KidSchoolMap")
				->leftjoin ('NidaraSchoolKidProfile', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
				->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
				->leftjoin('SchoolGamesAnswers','SchoolGamesAnswers.nidara_kid_profile_id = NidaraSchoolKidProfile.ncs_id')
				->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
				->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
				->inwhere('SchoolUserMap.users_id',array($user_id))
				->inwhere('GamesCoreframeMap.subject_id',array($value -> subject_id))
				->groupBy('KidSchoolMap.nidara_kid_profile_id')
				->getQuery()->execute ();
				$total_value = 0;
				$coutoftotal = 0;
				$classarrayvalue = array();
				foreach($gamedatabase2 as $value3){
					$gamedatabase1 = $this->modelsManager->createBuilder ()->columns ( array (
						'NidaraSchoolKidProfile.id as id',
						'NidaraSchoolKidProfile.ncs_id as ncs_id',
						'SchoolGamesAnswers.game_id as game_id',
					))->from("KidSchoolMap")
					->leftjoin ('NidaraSchoolKidProfile', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
					->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
					->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
					->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
					->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
					->leftjoin('SchoolGamesAnswers','SchoolGamesAnswers.nidara_kid_profile_id = NidaraSchoolKidProfile.ncs_id')
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
					->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
					->inwhere('SchoolUserMap.users_id',array($user_id))
					->inwhere('KidSchoolMap.class_id',array($value3 -> class_id))
					->inwhere('KidSchoolMap.sections_id',array($value3 -> section_id))
					->groupBy('KidSchoolMap.nidara_kid_profile_id')
					->getQuery()->execute ();
					$childinfoarray = array();
					$coutinchild = 0;
					$schoolvalue = 0;
					foreach($gamedatabase1 as $value2){
						$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
							'SchoolGamesAnswers.answers as answers',
							'SchoolGamesAnswers.questions_no as questions_no',
							'SchoolGamesAnswers.session_id as session_id',
							'SchoolGamesAnswers.game_id as game_id',
						))->from('SchoolGamesAnswers')
						->where ('SchoolGamesAnswers.created_at <= "'. $todate .'" AND SchoolGamesAnswers.created_at >= "'. $fromdate .'"')
						->inwhere('SchoolGamesAnswers.nidara_kid_profile_id',array($value2 -> ncs_id))
						->inwhere('SchoolGamesAnswers.game_id',array($value2 -> game_id))
						->groupBy('SchoolGamesAnswers.id')
						->getQuery()->execute ();
						$gamedatabasearray=array();
						$percentage=0;
						$totalpercentage = 0;
						$answers = 0;
						$total = 0;
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
							$percentage = ((($answers)/($total))*1);
						}
						else{
							$percentage = 0;
						}
						if(!empty($gamedatabasearray)){
							$totalpercentage=($percentage);
						}
						$coutinchild++;
						$schoolvalue += $totalpercentage;
						$getgameanser_value['id'] = $value2 -> id;
						$getgameanser_value['ncs_id'] = $value2 -> ncs_id;
						$getgameanser_value['class_id'] = $value2 -> class_id;
						$getgameanser_value['section_id'] = $value2 -> section_id;
						$getgameanser_value['game_id'] = $value2 -> game_id;
						$getgameanser_value['totalpercentage'] = $totalpercentage;
						$getgameanser_value['gameinfo'] = $gamedatabasearray;
						$childinfoarray[] = $getgameanser_value;
					}
					if($schoolvalue != 0 || $coutinchild != 0){
						$total_value2 = ($schoolvalue/$coutinchild);
					} else {
						$total_value2 = 0;
					}
					$coutoftotal++;
					$arrayvalue['class_id'] = $value3 -> class_id;
					$arrayvalue['section_id'] = $value3 -> section_id;
					$arrayvalue['coutinchild'] = $coutinchild;
					$arrayvalue['total_value2'] = $total_value2;
					$total_value += $total_value2;
					$arrayvalue['schoolvalue'] = $schoolvalue;
					$arrayvalue['childinfo'] = $childinfoarray;
					$classarrayvalue[] = $arrayvalue;
				}
				if($total_value != 0 || $coutoftotal != 0){
					$classdata['total_value'] = round(($total_value/$coutoftotal)*100);
				}
				else{
					$classdata['total_value'] = 0;
				}
				
				if($classdata['total_value'] >= 90){
					$classdata['color'] = '#9ccc65';
					$classdata['text'] = 'Secure';
				}
				else if($classdata['total_value'] >= 70){
					$classdata['color'] = '#ffee58';
					$classdata['text'] = 'Progressing';
				}
				else if($classdata['total_value'] != 0 && $classdata['total_value'] < 70){
					$classdata['color'] = '#ef5350';
					$classdata['text'] = 'Apprehension';
				}
				else {
					$classdata['color'] = '#e2e2e2';
					$classdata['text'] = 'N/A';
				}
				if (strpos($value -> subject_name, "Education") !== false) {
					$classdata['subject_name'] = str_replace("Core Education - ","",$value->subject_name);
				}else if (strpos($value->subject_name, "Interest") !== false) {
					$classdata['subject_name'] = str_replace("Core Interest Dev - ","",$value->subject_name);
				}else if (strpos($value->subject_name, "Health") !== false) {
					$classdata['subject_name'] = str_replace("Core Health - ","",$value->subject_name);
				} 
				$classdata['total_value'] = $total_value;
				$classdata['coutoftotal'] = $coutoftotal;
				$classdata['classarrayvalue'] = $classarrayvalue;
				$classdata['subject_id'] = $value -> subject_id;
				$classarray[] = $classdata;
			}
		}
		return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $classarray,
		] );
	}
	
		public function getclasslevelfilter(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		$class_id = isset ( $input_data->class_id ) ? $input_data->class_id : '';
		$section_id = isset ( $input_data->section_id ) ? $input_data->section_id : '';
		$fromdate = isset ( $input_data->fromdate ) ? $input_data->fromdate : '';
		$todate = isset ( $input_data->todate ) ? $input_data->todate : '';
		if(empty($fromdate)){
			$fromdate = date('Y-m-d');
		}
		if(empty($todate)){
			$todate = date('Y-m-d');
		}
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else {
			$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesCoreframeMap.subject_id as subject_id',
				'Subject.subject_name as subject_name',
			))->from("SchoolGamesAnswers")
			->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
			->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
			->leftjoin ('NidaraSchoolKidProfile', 'SchoolGamesAnswers.nidara_kid_profile_id=NidaraSchoolKidProfile.ncs_id')
			->leftjoin ('KidSchoolMap', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
			->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
			->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
			->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
			->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
			->inwhere('SchoolUserMap.users_id',array($user_id))
			->inwhere('KidSchoolMap.class_id',array($class_id))
			->inwhere('KidSchoolMap.sections_id',array($section_id))
			->groupBy('GamesCoreframeMap.subject_id')
			->getQuery()->execute ();
			$classarray = array();
			foreach($getvalue as $value){
				$gamedatabase1 = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
				))->from("KidSchoolMap")
				->leftjoin ('NidaraSchoolKidProfile', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
				->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
				->leftjoin('SchoolGamesAnswers','SchoolGamesAnswers.nidara_kid_profile_id = NidaraSchoolKidProfile.ncs_id')
				->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
				->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
				->inwhere('SchoolUserMap.users_id',array($user_id))
				->inwhere('KidSchoolMap.class_id',array($class_id))
				->inwhere('KidSchoolMap.sections_id',array($section_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
				$childinfoarray = array();
				$coutinchild = 0;
				$schoolvalue = 0;
				foreach($gamedatabase1 as $value2){
					$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolGamesAnswers.id as id',
						'SchoolGamesAnswers.answers as answers',
						'SchoolGamesAnswers.questions_no as questions_no',
						'SchoolGamesAnswers.session_id as session_id',
						'SchoolGamesAnswers.game_id as game_id',
					))->from("SchoolGamesAnswers")
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
					->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
					->leftjoin ('NidaraSchoolKidProfile', 'SchoolGamesAnswers.nidara_kid_profile_id=NidaraSchoolKidProfile.ncs_id')
					->leftjoin ('KidSchoolMap', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
					->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
					->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
					->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
					->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
					->where ('SchoolGamesAnswers.created_at <= "'. $todate .'" AND SchoolGamesAnswers.created_at >= "'. $fromdate .'"')
					->inwhere('SchoolGamesAnswers.nidara_kid_profile_id',array($value2 -> ncs_id))
					->inwhere('GamesCoreframeMap.subject_id',array($value -> subject_id))
					->groupBy('SchoolGamesAnswers.id')
					->getQuery()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					$totalpercentage = 0;
					$answers = 0;
					$total = 0;
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
						$percentage = ((($answers)/($total))*1);
					}
					else{
						$percentage = 0;
					}
					if(!empty($gamedatabasearray)){
						$totalpercentage=($percentage);
					}
					$coutinchild++;
					$schoolvalue += $totalpercentage;
					$getgameanser_value['id'] = $value2 -> id;
					$getgameanser_value['ncs_id'] = $value2 -> ncs_id;
					$getgameanser_value['total'] = $total;
					$getgameanser_value['answers'] = $answers;
					$getgameanser_value['game_id'] = $value2 -> game_id;
					$getgameanser_value['totalpercentage'] = $totalpercentage;
					$getgameanser_value['gameinfo'] = $gamedatabasearray;
					$childinfoarray[] = $getgameanser_value;
				}
				if($schoolvalue != 0 || $coutinchild != 0){
					$classdata['total_value'] = round(($schoolvalue/$coutinchild)*100);
				}
				else{
					$classdata['total_value'] = 0;
				}
				if($classdata['total_value'] >= 90){
					$classdata['color'] = '#9ccc65';
					$classdata['text'] = 'Secure';
				}
				else if($classdata['total_value'] >= 70){
					$classdata['color'] = '#ffee58';
					$classdata['text'] = 'Progressing';
				}
				else if($classdata['total_value'] != 0 && $classdata['total_value'] < 70){
					$classdata['color'] = '#ef5350';
					$classdata['text'] = 'Apprehension';
				}
				else {
					$classdata['color'] = '#e2e2e2';
					$classdata['text'] = 'N/A';
				}
				if (strpos($value -> subject_name, "Education") !== false) {
					$classdata['subject_name'] = str_replace("Core Education - ","",$value->subject_name);
				}else if (strpos($value->subject_name, "Interest") !== false) {
					$classdata['subject_name'] = str_replace("Core Interest Dev - ","",$value->subject_name);
				}else if (strpos($value->subject_name, "Health") !== false) {
					$classdata['subject_name'] = str_replace("Core Health - ","",$value->subject_name);
				} 
				$getclass = Classes::findFirstByid($class_id);
				$classdata['class_name'] = $getclass -> class_name;
				$getsection = Sections::findFirstByid($section_id);
				$classdata['section_name'] = $getsection -> section_name;
				$classdata['schoolvalue'] = $schoolvalue;
				$classdata['coutinchild'] = $coutinchild;
				$classdata['subject_id'] = $value -> subject_id;
				$classdata['childinfo'] = $childinfoarray;
				$classarray[] = $classdata;
			}
		}
		return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $classarray,
				'class_name' => $getclass -> class_name,
				'section_name' => $getsection -> section_name,
		] );
	}
	
	
	public function getsubjectlevelfilter(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		$class_id = isset ( $input_data->class_id ) ? $input_data->class_id : '';
		$section_id = isset ( $input_data->section_id ) ? $input_data->section_id : '';
		$fromdate = isset ( $input_data->fromdate ) ? $input_data->fromdate : '';
		$todate = isset ( $input_data->todate ) ? $input_data->todate : '';
		$subject_id = isset($input_data -> subject_id) ? $input_data->subject_id : '';
		if(empty($fromdate)){
			$fromdate = '2019-03-10';
		}
		if(empty($todate)){
			$todate = date('Y-m-d');
		}
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else{
			$classarray = array();
			$gamedatabase1 = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.first_name as first_name',
					'NidaraSchoolKidProfile.last_name as last_name',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
				))->from("KidSchoolMap")
				->leftjoin ('NidaraSchoolKidProfile', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
				->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
				->leftjoin('SchoolGamesAnswers','SchoolGamesAnswers.nidara_kid_profile_id = NidaraSchoolKidProfile.ncs_id')
				->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
				->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
				->inwhere('SchoolUserMap.users_id',array($user_id))
				->inwhere('KidSchoolMap.class_id',array($class_id))
				->inwhere('KidSchoolMap.sections_id',array($section_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
				$childinfoarray = array();
				$coutinchild = 0;
				$schoolvalue = 0;
				foreach($gamedatabase1 as $value2){
					$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolGamesAnswers.id as id',
						'SchoolGamesAnswers.answers as answers',
						'SchoolGamesAnswers.questions_no as questions_no',
						'SchoolGamesAnswers.session_id as session_id',
						'SchoolGamesAnswers.game_id as game_id',
					))->from("SchoolGamesAnswers")
					->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
					->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
					->leftjoin ('NidaraSchoolKidProfile', 'SchoolGamesAnswers.nidara_kid_profile_id=NidaraSchoolKidProfile.ncs_id')
					->leftjoin ('KidSchoolMap', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
					->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
					->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
					->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
					->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
					->where ('SchoolGamesAnswers.created_at <= "'. $todate .'" AND SchoolGamesAnswers.created_at >= "'. $fromdate .'"')
					->inwhere('SchoolGamesAnswers.nidara_kid_profile_id',array($value2 -> ncs_id))
					->inwhere('GamesCoreframeMap.subject_id',array($subject_id))
					->groupBy('SchoolGamesAnswers.id')
					->getQuery()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					$totalpercentage = 0;
					$answers = 0;
					$total = 0;
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
						$percentage = ((($answers)/($total))*1);
					}
					else{
						$percentage = 0;
					}
					if(!empty($gamedatabasearray)){
						$totalpercentage=($percentage);
					}
					$coutinchild++;
					$schoolvalue += $totalpercentage;
					$getgameanser_value['id'] = $value2 -> id;
					$getgameanser_value['ncs_id'] = $value2 -> ncs_id;
					$getgameanser_value['first_name'] = $value2 -> first_name;
					$getgameanser_value['last_name'] = $value2 -> last_name;
					$getgameanser_value['game_id'] = $value2 -> game_id;
					$getgameanser_value['totalpercentage'] = $totalpercentage;
					$getgameanser_value['gameinfo'] = $gamedatabasearray;
					$childinfoarray[] = $getgameanser_value;
				}
				if($schoolvalue != 0 || $coutinchild != 0){
					$classdata['total_value'] = round(($schoolvalue/$coutinchild)*100);
				}
				else{
					$classdata['total_value'] = 0;
				}
				if($classdata['total_value'] >= 90){
					$classdata['color'] = '#9ccc65';
					$classdata['text'] = 'Secure';
				}
				else if($classdata['total_value'] >= 70){
					$classdata['color'] = '#ffee58';
					$classdata['text'] = 'Progressing';
				}
				else if($classdata['total_value'] != 0 && $classdata['total_value'] < 70){
					$classdata['color'] = '#ef5350';
					$classdata['text'] = 'Apprehension';
				}
				else {
					$classdata['color'] = '#e2e2e2';
					$classdata['text'] = 'N/A';
				}
				$subject = Subject::findFirstByid($subject_id);
				if (strpos($subject -> subject_name, "Education") !== false) {
					$classdata['subject_name'] = str_replace("Core Education - ","",$subject->subject_name);
				}else if (strpos($subject->subject_name, "Interest") !== false) {
					$classdata['subject_name'] = str_replace("Core Interest Dev - ","",$subject->subject_name);
				}else if (strpos($subject->subject_name, "Health") !== false) {
					$classdata['subject_name'] = str_replace("Core Health - ","",$subject->subject_name);
				}
				$subject_new = Subject::find();
				$subjectarray = array();
				foreach($subject_new as $subject_value){
					if (strpos($subject_value -> subject_name, "Education") !== false) {
						$subjectdata['subject_name'] = str_replace("Core Education - ","",$subject_value->subject_name);
					}else if (strpos($subject_value->subject_name, "Interest") !== false) {
						$subjectdata['subject_name'] = str_replace("Core Interest Dev - ","",$subject_value->subject_name);
					}else if (strpos($subject_value->subject_name, "Health") !== false) {
						$subjectdata['subject_name'] = str_replace("Core Health - ","",$subject_value->subject_name);
					}
					$subjectdata['subject_id'] = $subject_value -> id;
					$subjectarray[] = $subjectdata;
				}
				$classdata['subjectinfo'] = $subjectarray;
				$getclass = Classes::findFirstByid($class_id);
				$classdata['class_name'] = $getclass -> class_name;
				$getsection = Sections::findFirstByid($section_id);
				$classdata['section_name'] = $getsection -> section_name;
				$classdata['schoolvalue'] = $schoolvalue;
				$classdata['coutinchild'] = $coutinchild;
				$classdata['childinfo'] = $childinfoarray;
				$classarray[] = $classdata;
		}
		return $this->response->setJsonContent ( [
				"status" => true,
				"data" => $classarray,
				'class_name' => $getclass -> class_name,
				'section_name' => $getsection -> section_name,
		] );
		
	}
	
	public function getchildlevelfilter(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		$class_id = isset ( $input_data->class_id ) ? $input_data->class_id : '';
		$section_id = isset ( $input_data->section_id ) ? $input_data->section_id : '';
		$fromdate = isset ( $input_data->fromdate ) ? $input_data->fromdate : '';
		$todate = isset ( $input_data->todate ) ? $input_data->todate : '';
		$subject_id = isset($input_data -> subject_id) ? $input_data->subject_id : '';
		$child_id = isset($input_data -> child_id) ? $input_data->child_id : '';
		if(empty($fromdate)){
			$fromdate = '2019-03-10';
		}
		if(empty($todate)){
			$todate = date('Y-m-d');
		}
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else{
			$childinfoarray = array();
			$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
				'SchoolGamesAnswers.id as id',
				'SchoolGamesAnswers.answers as answers',
				'SchoolGamesAnswers.questions_no as questions_no',
				'SchoolGamesAnswers.session_id as session_id',
				'SchoolGamesAnswers.game_id as game_id',
			))->from("SchoolGamesAnswers")
			->leftjoin('GamesCoreframeMap','GamesCoreframeMap.game_id = SchoolGamesAnswers.game_id')
			->leftjoin ('Subject', 'GamesCoreframeMap.subject_id=Subject.id')
			->leftjoin ('NidaraSchoolKidProfile', 'SchoolGamesAnswers.nidara_kid_profile_id=NidaraSchoolKidProfile.ncs_id')
			->leftjoin ('KidSchoolMap', 'KidSchoolMap.nidara_kid_profile_id=NidaraSchoolKidProfile.id')
			->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
			->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
			->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
			->leftjoin('SchoolUserMap','SchoolUserMap.schools_id = Schools.id')
			->inwhere('SchoolGamesAnswers.nidara_kid_profile_id',array($child_id))
			->inwhere('GamesCoreframeMap.subject_id',array($subject_id))
			->groupBy('SchoolGamesAnswers.id')
			->getQuery()->execute ();
			$gamedatabasearray=array();
			$percentage=0;
			$totalpercentage = 0;
			$answers = 0;
			$total = 0;
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
				$percentage = ((($answers)/($total))*100);
			}
			else{
				$percentage = 0;
			}
			if(!empty($gamedatabasearray)){
				$totalpercentage=($percentage);
			}
			$getgameanser_value['total_value'] = $totalpercentage;
			$getgameanser_value['gamedatabasearray'] = $gamedatabasearray;
			$subject = Subject::findFirstByid($subject_id);
			if (strpos($subject -> subject_name, "Education") !== false) {
				$getgameanser_value['subject_name'] = str_replace("Core Education - ","",$subject->subject_name);
			}else if (strpos($subject->subject_name, "Interest") !== false) {
				$getgameanser_value['subject_name'] = str_replace("Core Interest Dev - ","",$subject->subject_name);
			}else if (strpos($subject->subject_name, "Health") !== false) {
				$getgameanser_value['subject_name'] = str_replace("Core Health - ","",$subject->subject_name);
			}
			if($getgameanser_value['total_value'] >= 90){
				$getgameanser_value['color'] = '#9ccc65';
				$getgameanser_value['text'] = 'Secure';
			}
			else if($getgameanser_value['total_value'] >= 70){
				$getgameanser_value['color'] = '#ffee58';
				$getgameanser_value['text'] = 'Progressing';
			}
			else if($getgameanser_value['total_value'] != 0 && $getgameanser_value['total_value'] < 70){
				$getgameanser_value['color'] = '#ef5350';
				$getgameanser_value['text'] = 'Apprehension';
			}
			else {
				$getgameanser_value['color'] = '#e2e2e2';
				$getgameanser_value['text'] = 'N/A';
			}
			$getclass = Classes::findFirstByid($class_id);
			$getgameanser_value['class_name'] = $getclass -> class_name;
			$getgameanser_value['child_id'] = $child_id;
			$getsection = Sections::findFirstByid($section_id);
			$getgameanser_value['section_name'] = $getsection -> section_name;
			$getgameanser_value['totalpercentage'] = $totalpercentage;
			$childinfoarray[] = $getgameanser_value;
		}
		return $this->response->setJsonContent ( [
			"status" => true,
			"data" => $childinfoarray,
			'class_name' => $getclass -> class_name,
			'section_name' => $getsection -> section_name,
		] );
		
	}
}