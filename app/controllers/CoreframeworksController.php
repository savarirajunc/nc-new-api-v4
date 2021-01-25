<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class CoreframeworksController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$core_frm = CoreFrameworks::find ();
		if ($core_frm) :
			return Json_encode ( $core_frm );
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
			$core_frm_getbyid = CoreFrameworks::findFirstByid ( $id );
			if ($core_frm_getbyid) :
				return Json_encode ( $core_frm_getbyid );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to create CoreFrameworks information
	 */
	public function create() {
		$input_data = $this->request->getJsonRawBody ();
		
		/**
		 * This object using valitaion
		 */
		$validation = new Validation ();
		$validation->add ( 'name', new PresenceOf ( [ 
				'message' => 'name is required' 
		] ) );
		$validation->add ( 'status', new PresenceOf ( [ 
				'message' => 'status is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$core_frm_create = new CoreFrameworks ();
			$core_frm_create->id = $input_data->id;
			$core_frm_create->name = $input_data->name;
			$core_frm_create->status = $input_data->status;
			$core_frm_create->created_at = date ( 'Y-m-d H:i:s' );
			$core_frm_create->created_by = 1;
			if ($core_frm_create->save ()) :
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'successfully' 
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
	 * This function using to CoreFrameworks information edit
	 */
	 
	 
	public function subjectmapcreate(){
		$input_data = $this->request->getJsonRawBody ();
		$validation = new Validation ();
		$validation->add ( 'core_frameworks_id', new PresenceOf ( [ 
				'message' => 'Core Frameworks Id is required' 
		] ) );
		$validation->add ( 'subject_id', new PresenceOf ( [ 
				'message' => 'Subject Id is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$core_framework = new CoreFrameworksSubjectMap();
			$core_framework->id = $this->guidedlearningidgen->getNewId ("guidedlearningadd");
			$core_framework->core_frameworks_id=$input_data->core_frameworks_id;
			$core_framework->subject_id=$input_data->subject_id;
			if ($core_framework->save ()) :
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'successfully' 
				] );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
				] );
			endif;
		endif;
	}
	 
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
			$validation->add ( 'name', new PresenceOf ( [ 
					'message' => 'name is required' 
			] ) );
			$validation->add ( 'status', new PresenceOf ( [ 
					'message' => 'status is required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach
				;
				return $this->response->setJsonContent ( $result );
			 else :
				$core_frm_update = CoreFrameworks::findFirstByid ( $id );
				if ($core_frm_update) :
					$core_frm_update->name = $input_data->name;
					$core_frm_update->status = $input_data->status;
					$core_frm_update->created_by = $id;
					if ($core_frm_update->save ()) :
						return $this->response->setJsonContent ( [ 
								'status' => true,
								'message' => 'successfully' 
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
			$core_frm_delete = CoreFrameworks::findFirstByid ( $id );
			if ($core_frm_delete) :
				if ($core_frm_delete->delete ()) :
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'Message' => 'Record has been deleted successfully ' 
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
	
	/**
	 * Get core frameworks details
	 * @return array
	 */
	public function getcoreframeworks()
	{
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Kid Id is null'
			] );
		}
		$getgender = NidaraKidProfile::findFirstByid($id);
		if(!empty($getgender)){
			$gender = $getgender->gender;
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$day_value = $day_id;
		if($day_id >= 0){
			$day_id += 1; 
		}
		$gamecheck = 1;
		if($day_id > 1){
			$kidprofile = NidaraKidProfile::findFirstByid ($id);
			if($kidprofile -> test_kid_status == 0){
				$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GuidedLearningDayGameMap.game_id as games_id',
					'GuidedLearningDayGameMap.day_id as day_id',
					'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_id',
				))->from("GuidedLearningDayGameMap")
				->where('GuidedLearningDayGameMap.day_id < ' . $day_id . '')
				->inwhere("GuidedLearningDayGameMap.day_guided_learning_id", array($kidprofile -> grade))
				->groupBy('GuidedLearningDayGameMap.game_id')
				->getQuery()->execute ();
				foreach($guidedlearning_id as $value){
					$game_getses = $this->modelsManager->createBuilder()->columns(array(
						'KidsGamesStatus.session_id as session_id',
						'KidsGamesStatus.current_status as current_status',
						'KidsGamesStatus.game_id as game_id',
					))->from('KidsGamesStatus')
					->where('KidsGamesStatus.created_date < "'. date ('Y-m-d') .'"')
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
		}
		$month = 0;
		$week = 0;
		$day = 0;
		
		
		if($day_id > 5){
			$getday = (int)($day_value/5);
			$day = ((int)($day_id - ($getday * 5)));
		} else {
			$day = $day_id;
		}
		if($day_id <= 20){
			$month = 1;
			$week = ((int)($day_value/5)+1);
		}
		else{
			$getmonth = ((int)($day_id/20));
			$month = $getmonth+1;
			$remain = ($day_id - ($getmonth*20));
			$week = ((int)($remain/5)+1);
		}
		$grade_id = isset ($input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Grade Id is null'
			] );
		}

		$getdemokid=DemoChildList::findFirstBynidara_kid_profile_id($id);
			if($gender == 'famale'){


				if(!$getdemokid)
					{
					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();

				}
				else
				{
					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'DemoGameList' )
				->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "DemoGameList.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();

				}
			}
			else{


				if(!$getdemokid)
				{
						
						$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();

				}
				else
				{

					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'DemoGameList' )
				->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "DemoGameList.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();


				}
			}
		// }
		
		$core_array = array ();
		
		foreach ( $games_database as $core_data ) {
			if(!empty($core_data->game_id)){
			$gamepercentage = $this->getgamepercentage ( $core_data->game_id,$id);
			$numberoftime = $this->numberoftime ( $core_data->game_id,$id);
			$subject = GrMainReporting::findBysubject_id($core_data->subject_id);
			if(count($subject) == 0){
				$subject_id = 0;
			}
			else if(count($subject) > 0){
				$subject_id = $core_data->subject_id;
			}
			$core_data->coresubject = $subject_id;
			$GrMainReporting = $this->modelsManager->createBuilder ()->columns ( array (
					'GrMainReporting.id as id',
					'GrMainReporting.gr_framwork_id as gr_framwork_id',
					'GrMainReporting.add_grade_range_max as add_grade_range_max',
					'GrMainReporting.add_grade_range_min as add_grade_range_min',
					'GrMainReporting.add_color as add_color',
					'GrMainReporting.subject_id as subject_id',
			))->from('GrMainReporting')
			->inwhere("GrMainReporting.gr_type_id",array($core_data->framework_id))
			->inwhere("GrMainReporting.subject_id",array($subject_id))
			//->inwhere("GrMainReporting.gr_frame_type",array(1))
			->getQuery ()->execute ();
			$gr_array = array ();
			foreach ( $GrMainReporting as $grcolorval ) {
				$grading_val ['id'] = $grcolorval->id;
				$grading_val ['gr_frame_id'] = $grcolorval->gr_framwork_id;
				$grading_val ['max'] = $grcolorval->add_grade_range_max;
				$grading_val ['min'] = $grcolorval->add_grade_range_min;
				$grading_val ['color'] = $grcolorval->add_color;
				$grading_val ['subject_id'] = $grcolorval->subject_id;
				$gr_array[] = $grading_val;
			if($gamepercentage >= 2){
			  $core_data->kid_played=TRUE;
			  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
			  if($gamepercentage >= $grcolorval->add_grade_range_min && $gamepercentage <= $grcolorval->add_grade_range_max){
				$core_data->grade_color=$grcolorval->add_color;
			  }
			  $core_data->game_playing = $numberoftime;
			  //}
			}
			else if($gamepercentage == 1){
				$core_data->daily_tips="Game not Completed";
			}
			else{
			 $core_data->daily_tips="Milestone activity not completed";
			}
			}
			$core_data->coregrading=$gr_array;
			$core_data->game_percentage=$gamepercentage;
			$core_framework_name = strtolower( str_replace ( ' ', '_', $core_data->core_framework_name ) );
			$core_array [] = $core_data->core_framework_name;
			$core_frm_array [$core_framework_name] [] = $core_data;
			}
		}
		$core_frame = CoreFrameworks::find ();
		foreach ( $core_frame as $core ) {
			if (! in_array ( $core->name, $core_array )) {
				$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
				$core_frm_array [$core->name] = array ();
			}
		}
		$kid=NidaraKidProfile::findFirstByid($id);
		if(!empty($kid)){
		$core_frm_array['kid_name']=$kid->first_name;
		$core_frm_array['child_photo']=$kid->child_photo;
		}
		$core_frm_array['today_date']=date('l, F d, Y');
		$core_frm_array['month'] = $month;
		$core_frm_array['week'] = $week;
		$core_frm_array['day'] = $day;
		if($gamecheck == 0){
			$day_id2 = $day_id - 1;
			$month2 = 0;
			$week2 = 0;
			$day2 = 0;
			$day_value2 = $day_id2;
			if($day_id2 > 5){
				$getday2 = (int)($day_value2/5);
				$day2 = ((int)($day_id2 - ($getday2 * 5)));
			} else {
				$day2 = $day_id2;
			}
			if($day_id2 <= 20){
				$month2 = 1;
				$week2 = ((int)($day_value2/5)+1);
			}
			else{
				$getmonth2 = ((int)($day_id2/20));
				$month2 = $getmonth2+1;
				$remain2 = ($day_id2 - ($getmonth2*20));
				$week2 = ((int)($remain2/5)+1);
			}
			$grade_id2 = isset ($input_data->grade_id ) ? $input_data->grade_id : '';
			if(empty($grade_id2)){
				return $this->response->setJsonContent ( [
						'status' => false,
						'data' => 'Grade Id is null'
				] );
			}

			$getdemokid2=DemoChildList::findFirstBynidara_kid_profile_id($id);
				if($gender == 'famale'){


					if(!$getdemokid2)
						{
						$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'GuidedLearningDayGameMap' )
					->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
							$day_id2
					) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.tina",array(
						1
					))->getQuery ()->execute ();

					}
					else
					{
						$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'DemoGameList' )
					->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )
					->inwhere ( "DemoGameList.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.tina",array(
						1
					))->getQuery ()->execute ();

					}
				}
				else{


					if(!$getdemokid2)
					{
							
							$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'GuidedLearningDayGameMap' )
					->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
							$day_id2
					) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.rahul",array(
						1
					))->getQuery ()->execute ();

					}
					else
					{

						$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'DemoGameList' )
					->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )
					->inwhere ( "DemoGameList.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.rahul",array(
						1
					))->getQuery ()->execute ();


					}
				}
			// }
		
			$core_array2 = array ();
			
			foreach ( $games_database2 as $core_data2 ) {
				if(!empty($core_data2->game_id)){
				$gamepercentage2 = $this->getgamepercentage ( $core_data2->game_id,$id);
				$numberoftime2 = $this->numberoftime ( $core_data2->game_id,$id);
				$subject2 = GrMainReporting::findBysubject_id($core_data2->subject_id);
				if(count($subject2) == 0){
					$subject_id2 = 0;
				}
				else if(count($subject2) > 0){
					$subject_id2 = $core_data2->subject_id;
				}
				$core_data2->coresubject = $subject_id2;
				$GrMainReporting2 = $this->modelsManager->createBuilder ()->columns ( array (
						'GrMainReporting.id as id',
						'GrMainReporting.gr_framwork_id as gr_framwork_id',
						'GrMainReporting.add_grade_range_max as add_grade_range_max',
						'GrMainReporting.add_grade_range_min as add_grade_range_min',
						'GrMainReporting.add_color as add_color',
						'GrMainReporting.subject_id as subject_id',
				))->from('GrMainReporting')
				->inwhere("GrMainReporting.gr_type_id",array($core_data2->framework_id))
				->inwhere("GrMainReporting.subject_id",array($subject_id2))
				//->inwhere("GrMainReporting.gr_frame_type",array(1))
				->getQuery ()->execute ();
				$gr_array2 = array ();
				foreach ( $GrMainReporting2 as $grcolorval2 ) {
					$grading_val2 ['id'] = $grcolorval2->id;
					$grading_val2 ['gr_frame_id'] = $grcolorval2->gr_framwork_id;
					$grading_val2 ['max'] = $grcolorval2->add_grade_range_max;
					$grading_val2 ['min'] = $grcolorval2->add_grade_range_min;
					$grading_val2 ['color'] = $grcolorval2->add_color;
					$grading_val2 ['subject_id'] = $grcolorval2->subject_id;
					$gr_array2[] = $grading_val2;
				if($gamepercentage2 >= 2){
				  $core_data->kid_played=TRUE;
				  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
				  if($gamepercentage2 >= $grcolorval2->add_grade_range_min && $gamepercentage2 <= $grcolorval2->add_grade_range_max){
					$core_data2->grade_color=$grcolorval2->add_color;
				  }
				  $core_data2->game_playing = $numberoftime2;
				  //}
				}
				else if($gamepercentage2 == 1){
					$core_data2->daily_tips="Game not Completed";
				}
				else{
				 $core_data2->daily_tips="Milestone activity not completed";
				}
				}
				$core_data2->coregrading=$gr_array;
				$core_data2->game_percentage=$gamepercentage2;
				$core_framework_name2 = strtolower( str_replace ( ' ', '_', $core_data2->core_framework_name ) );
				$core_array2 [] = $core_data2->core_framework_name;
				$core_frm_array2 [$core_framework_name2] [] = $core_data2;
				}
			}
			$core_frame2 = CoreFrameworks::find ();
			foreach ( $core_frame2 as $core2 ) {
				if (! in_array ( $core2->name, $core_array2 )) {
					$core2->name = strtolower( str_replace ( ' ', '_', $core2->name ) );
					$core_frm_array2 [$core2->name] = array ();
				}
			}
			$kid2=NidaraKidProfile::findFirstByid($id);
			if(!empty($kid2)){
			$core_frm_array2['kid_name']=$kid2->first_name;
			$core_frm_array2['child_photo']=$kid2->child_photo;
			}
			$core_frm_array2['today_date']=date('l, F d, Y');
			$core_frm_array2['month'] = $month2;
			$core_frm_array2['week'] = $week2;
			$core_frm_array2['day'] = $day2;
		}
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array,
				'leatdata' => $core_frm_array2
		] );
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamepercentage
		] );
	}

	public function getcoreframeworks_demokid()
	{
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
			/*if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}*/
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Kid Id is null'
			] );
		}
		$getgender = NidaraKidProfile::findFirstByid($id);
		if(!empty($getgender)){
			$gender = $getgender->gender;
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$day_value = $day_id;
		if($day_id >= 0){
			$day_id += 1; 
		}

		

$kidprofile = NidaraKidProfile::findFirstByid ($id);

$democheck = $this->modelsManager->createBuilder ()->columns ( array (
						'SalesmanDemoKid.id',
						'SalesmanDemoKid.kid_id',
					))->from("SalesmanDemoKid")
					->inwhere("SalesmanDemoKid.kid_id",array($input_data->nidara_kid_profile_id))
					->getQuery()->execute ();

		if(count($democheck) > 0)
		{
			$gradcheck = $this->modelsManager->createBuilder ()->columns ( array (
						'GuidedLearningDemoKid.id',
						'GuidedLearningDemoKid.day_id',
					))->from("GuidedLearningDemoKid")
					->inwhere("GuidedLearningDemoKid.grade_id",array($kidprofile -> grade))
					->getQuery()->execute ();	

			if(count($gradcheck)>0)
			{
				$day_id=$gradcheck[0]->day_id;
			}		
		}
		$gamecheck = 1;
		if($day_id > 1){
			
			if($kidprofile -> test_kid_status == 0){
				$guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
					'GuidedLearningDayGameMap.game_id as games_id',
					'GuidedLearningDayGameMap.day_id as day_id',
					'GuidedLearningDayGameMap.day_guided_learning_id as day_guided_learning_id',
				))->from("GuidedLearningDayGameMap")
				->where('GuidedLearningDayGameMap.day_id < ' . $day_id . '')
				->inwhere("GuidedLearningDayGameMap.day_guided_learning_id", array($kidprofile -> grade))
				->groupBy('GuidedLearningDayGameMap.game_id')
				->getQuery()->execute ();
				foreach($guidedlearning_id as $value){
					$game_getses = $this->modelsManager->createBuilder()->columns(array(
						'KidsGamesStatus.session_id as session_id',
						'KidsGamesStatus.current_status as current_status',
						'KidsGamesStatus.game_id as game_id',
					))->from('KidsGamesStatus')
					->where('KidsGamesStatus.created_date < "'. date ('Y-m-d') .'"')
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
		} 

		
		$month = 0;
		$week = 0;
		$day = 0;
		
		
		if($day_id > 5){
			$getday = (int)($day_value/5);
			$day = ((int)($day_id - ($getday * 5)));
		} else {
			$day = $day_id;
		}
		if($day_id <= 20){
			$month = 1;
			$week = ((int)($day_value/5)+1);
		}
		else{
			$getmonth = ((int)($day_id/20));
			$month = $getmonth+1;
			$remain = ($day_id - ($getmonth*20));
			$week = ((int)($remain/5)+1);
		}
		$grade_id = isset ($input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Grade Id is null'
			] );
		}

		$getdemokid=DemoChildList::findFirstBynidara_kid_profile_id($id);
			if($gender == 'famale'){


				if(!$getdemokid)
					{
					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();

				}
				else
				{
					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'DemoGameList' )
				->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "DemoGameList.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();

				}
			}
			else{


				if(!$getdemokid)
				{
						
						$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();

				}
				else
				{

					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'DemoGameList' )
				->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "DemoGameList.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();


				}
			}
		// }
		
		$core_array = array ();
		
		foreach ( $games_database as $core_data ) {
			if(!empty($core_data->game_id)){
			$gamepercentage = $this->getgamepercentage ( $core_data->game_id,$id);
			$numberoftime = $this->numberoftime ( $core_data->game_id,$id);
			$subject = GrMainReporting::findBysubject_id($core_data->subject_id);
			if(count($subject) == 0){
				$subject_id = 0;
			}
			else if(count($subject) > 0){
				$subject_id = $core_data->subject_id;
			}
			$core_data->coresubject = $subject_id;
			$GrMainReporting = $this->modelsManager->createBuilder ()->columns ( array (
					'GrMainReporting.id as id',
					'GrMainReporting.gr_framwork_id as gr_framwork_id',
					'GrMainReporting.add_grade_range_max as add_grade_range_max',
					'GrMainReporting.add_grade_range_min as add_grade_range_min',
					'GrMainReporting.add_color as add_color',
					'GrMainReporting.subject_id as subject_id',
			))->from('GrMainReporting')
			->inwhere("GrMainReporting.gr_type_id",array($core_data->framework_id))
			->inwhere("GrMainReporting.subject_id",array($subject_id))
			//->inwhere("GrMainReporting.gr_frame_type",array(1))
			->getQuery ()->execute ();
			
			$gr_array = array ();
			foreach ( $GrMainReporting as $grcolorval ) {
				$grading_val ['id'] = $grcolorval->id;
				$grading_val ['gr_frame_id'] = $grcolorval->gr_framwork_id;
				$grading_val ['max'] = $grcolorval->add_grade_range_max;
				$grading_val ['min'] = $grcolorval->add_grade_range_min;
				$grading_val ['color'] = $grcolorval->add_color;
				$grading_val ['subject_id'] = $grcolorval->subject_id;
				$gr_array[] = $grading_val;
			if($gamepercentage >= 2){
			  $core_data->kid_played=TRUE;
			  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
			  if($gamepercentage >= $grcolorval->add_grade_range_min && $gamepercentage <= $grcolorval->add_grade_range_max){
				$core_data->grade_color=$grcolorval->add_color;
			  }
			  $core_data->game_playing = $numberoftime;
			  //}
			}
			else if($gamepercentage == 1){
				$core_data->daily_tips="Game not Completed";
			}
			else{
			 $core_data->daily_tips="Milestone activity not completed";
			}
			}
			$core_data->coregrading=$gr_array;
			$core_data->game_percentage=$gamepercentage;
			$core_framework_name = strtolower( str_replace ( ' ', '_', $core_data->core_framework_name ) );
			$core_array [] = $core_data->core_framework_name;
			$core_frm_array [$core_framework_name] [] = $core_data;
			}
		}
		
		$core_frame = CoreFrameworks::find ();
		foreach ( $core_frame as $core ) {
			if (! in_array ( $core->name, $core_array )) {
				$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
				$core_frm_array [$core->name] = array ();
			}
		}
		$kid=NidaraKidProfile::findFirstByid($id);
		
		if(!empty($kid)){
		$core_frm_array['kid_name']=$kid->first_name;
		$core_frm_array['child_photo']=$kid->child_photo;
		}
		$core_frm_array['today_date']=date('l, F d, Y');
		$core_frm_array['month'] = $month;
		$core_frm_array['week'] = $week;
		$core_frm_array['day'] = $day;
		if($gamecheck == 0){
			
			$day_id2 = $day_id - 1;
			$month2 = 0;
			$week2 = 0;
			$day2 = 0;
			$day_value2 = $day_id2;
			if($day_id2 > 5){
				$getday2 = (int)($day_value2/5);
				$day2 = ((int)($day_id2 - ($getday2 * 5)));
			} else {
				$day2 = $day_id2;
			}
			if($day_id2 <= 20){
				$month2 = 1;
				$week2 = ((int)($day_value2/5)+1);
			}
			else{
				$getmonth2 = ((int)($day_id2/20));
				$month2 = $getmonth2+1;
				$remain2 = ($day_id2 - ($getmonth2*20));
				$week2 = ((int)($remain2/5)+1);
			}
			$grade_id2 = isset ($input_data->grade_id ) ? $input_data->grade_id : '';
			if(empty($grade_id2)){
				return $this->response->setJsonContent ( [
						'status' => false,
						'data' => 'Grade Id is null'
				] );
			}

			$getdemokid2=DemoChildList::findFirstBynidara_kid_profile_id($id);
				if($gender == 'famale'){


					if(!$getdemokid2)
						{
						$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'GuidedLearningDayGameMap' )
					->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
							$day_id2
					) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.tina",array(
						1
					))->getQuery ()->execute ();

					}
					else
					{
						$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'DemoGameList' )
					->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )
					->inwhere ( "DemoGameList.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.tina",array(
						1
					))->getQuery ()->execute ();

					}
				}
				else{


					if(!$getdemokid2)
					{
							
							$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'GuidedLearningDayGameMap' )
					->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
							$day_id2
					) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.rahul",array(
						1
					))->getQuery ()->execute ();

					}
					else
					{

						$games_database2 = $this->modelsManager->createBuilder ()->columns ( array (
							'GamesDatabase.id',
							'GamesDatabase.game_id',
							'GamesDatabase.games_name',
							'GamesDatabase.status',
							'GamesDatabase.daily_tips',
							'CoreFrameworks.name as core_framework_name',
							'CoreFrameworks.id as framework_id',
							'Subject.id as subject_id',
							'Subject.subject_name',
					) )->from ( 'DemoGameList' )
					->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
					->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
					->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
					->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
					->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
					->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
							$id
					) )
					->inwhere ( "DemoGameList.day_guided_learning_id", array (
							$grade_id2
					) )->inwhere("GamesDatabase.rahul",array(
						1
					))->getQuery ()->execute ();


					}
				}
			// }
		
			$core_array2 = array ();
			
			foreach ( $games_database2 as $core_data2 ) {
				if(!empty($core_data2->game_id)){
				$gamepercentage2 = $this->getgamepercentage ( $core_data2->game_id,$id);
				$numberoftime2 = $this->numberoftime ( $core_data2->game_id,$id);
				$subject2 = GrMainReporting::findBysubject_id($core_data2->subject_id);
				if(count($subject2) == 0){
					$subject_id2 = 0;
				}
				else if(count($subject2) > 0){
					$subject_id2 = $core_data2->subject_id;
				}
				$core_data2->coresubject = $subject_id2;
				$GrMainReporting2 = $this->modelsManager->createBuilder ()->columns ( array (
						'GrMainReporting.id as id',
						'GrMainReporting.gr_framwork_id as gr_framwork_id',
						'GrMainReporting.add_grade_range_max as add_grade_range_max',
						'GrMainReporting.add_grade_range_min as add_grade_range_min',
						'GrMainReporting.add_color as add_color',
						'GrMainReporting.subject_id as subject_id',
				))->from('GrMainReporting')
				->inwhere("GrMainReporting.gr_type_id",array($core_data2->framework_id))
				->inwhere("GrMainReporting.subject_id",array($subject_id2))
				//->inwhere("GrMainReporting.gr_frame_type",array(1))
				->getQuery ()->execute ();
				$gr_array2 = array ();
				foreach ( $GrMainReporting2 as $grcolorval2 ) {
					$grading_val2 ['id'] = $grcolorval2->id;
					$grading_val2 ['gr_frame_id'] = $grcolorval2->gr_framwork_id;
					$grading_val2 ['max'] = $grcolorval2->add_grade_range_max;
					$grading_val2 ['min'] = $grcolorval2->add_grade_range_min;
					$grading_val2 ['color'] = $grcolorval2->add_color;
					$grading_val2 ['subject_id'] = $grcolorval2->subject_id;
					$gr_array2[] = $grading_val2;
				if($gamepercentage2 >= 2){
				  $core_data->kid_played=TRUE;
				  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
				  if($gamepercentage2 >= $grcolorval2->add_grade_range_min && $gamepercentage2 <= $grcolorval2->add_grade_range_max){
					$core_data2->grade_color=$grcolorval2->add_color;
				  }
				  $core_data2->game_playing = $numberoftime2;
				  //}
				}
				else if($gamepercentage2 == 1){
					$core_data2->daily_tips="Game not Completed";
				}
				else{
				 $core_data2->daily_tips="Milestone activity not completed";
				}
				}
				$core_data2->coregrading=$gr_array;
				$core_data2->game_percentage=$gamepercentage2;
				$core_framework_name2 = strtolower( str_replace ( ' ', '_', $core_data2->core_framework_name ) );
				$core_array2 [] = $core_data2->core_framework_name;
				$core_frm_array2 [$core_framework_name2] [] = $core_data2;
				}
			}
			$core_frame2 = CoreFrameworks::find ();
			foreach ( $core_frame2 as $core2 ) {
				if (! in_array ( $core2->name, $core_array2 )) {
					$core2->name = strtolower( str_replace ( ' ', '_', $core2->name ) );
					$core_frm_array2 [$core2->name] = array ();
				}
			}
			$kid2=NidaraKidProfile::findFirstByid($id);
			if(!empty($kid2)){
			$core_frm_array2['kid_name']=$kid2->first_name;
			$core_frm_array2['child_photo']=$kid2->child_photo;
			}
			$core_frm_array2['today_date']=date('l, F d, Y');
			$core_frm_array2['month'] = $month2;
			$core_frm_array2['week'] = $week2;
			$core_frm_array2['day'] = $day2;
		}
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array,
				// 'leatdata' => $core_frm_array2
		] );
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamepercentage
		] );
	}	
	
	public function numberoftime($game_id,$kid_id){
		$today_date = date('Y-m-d');
		$numberoftime = 0;
		$game_question = $this->modelsManager->createBuilder ()->columns ( array (
			'COUNT(KidsGamesStatus.id) as id',
		))->from('KidsGamesStatus')
		->inwhere('KidsGamesStatus.game_id',array($game_id))
		->inwhere('KidsGamesStatus.nidara_kid_profile_id',array($kid_id))
		->inwhere('KidsGamesStatus.current_status',array(1))
		->inwhere('KidsGamesStatus.created_date',array($today_date))
		->getQuery ()->execute ();
		if(!empty($game_question)){
			$numberoftime = $game_question;
		}
		return $numberoftime; 
	}

	/**
	 * 
	 * @param unknown $game_id
	 * @return multitype:unknown
	 */
	public function getgamepercentage($game_id,$kid_id){
		$today_date = date('Y-m-d');
		$question_id = 0;
		$question_no = 0;
		$kidprofile = NidaraKidProfile::findFirstByid ($kid_id);
		if($kidprofile -> test_kid_status == 0){
			$game_question = $this->modelsManager->createBuilder ()->columns ( array (
				'COUNT(GameQuestionAnswer.question_id) as question_id',
			))->from('GameQuestionAnswer')
			->inwhere('GameQuestionAnswer.game_id',array($game_id))
			->getQuery ()->execute ();
			foreach($game_question as $question_value){
				$question_id = $question_value->question_id;
			}
			$game_answer = $this->modelsManager->createBuilder ()->columns ( array (
				'COUNT(GameAnswers.questions_no) as questions_no',
			) )->from ( 'GameAnswers')
			->inwhere('GameAnswers.game_id',array($game_id))
			->inwhere('GameAnswers.nidara_kid_profile_id',array($kid_id))
			->getQuery ()->execute ();
			foreach($game_answer as $answer_value){
				$question_no = $answer_value->questions_no;
			}
			if($question_no == 0){
				$totalpercentage = 0;
				return $totalpercentage; 
			}
			else if($question_id != $question_no){
				$game_question2 = $this->modelsManager->createBuilder ()->columns ( array (
				'KidsGamesStatus.current_status as current_status',
				))->from('KidsGamesStatus')
				->inwhere('KidsGamesStatus.game_id',array($game_id))
				->inwhere('KidsGamesStatus.nidara_kid_profile_id',array($kid_id))
				->inwhere('KidsGamesStatus.current_status',array(1))
				->getQuery ()->execute ();
				foreach($game_question2 as $game_question2_value){
					
				}
				if($game_question2_value->current_status < 1){
					$totalpercentage = 1;
				return $totalpercentage;
				}
			else{
				$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
					'GameAnswers.answers as answers',
					'GameAnswers.questions_no as questions_no',
					'GameAnswers.session_id as session_id',
					'GameAnswers.game_id as game_ids'
					) )->from ( 'GuidedLearningDayGameMap' )
					->leftjoin ( 'GameAnswers', 'GuidedLearningDayGameMap.game_id=GameAnswers.game_id' )
					->leftjoin ( 'GamesDatabase', 'GameAnswers.game_id=GamesDatabase.id' )
					->leftjoin ( 'NidaraKidProfile', 'GameAnswers.nidara_kid_profile_id=NidaraKidProfile.id' )
					->inwhere('GameAnswers.game_id',array($game_id))
					->inwhere('GameAnswers.nidara_kid_profile_id',array($kid_id))
					->getQuery ()->execute ();
					$gamedatabasearray=array();
					$percentage=0;
					foreach ( $gamedatabase as $gamedata ) {
						if($gamedata->questions_no != 0){
						if($gamedata->answers == 1){
							$answers = $answers + 1;
							$total = $total + 1;
						}
						else if($gamedata->answers > 1){
							$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
								'GameQuestionAnswer.game_type_value as game_type_value',
							))->from('GameQuestionAnswer')
							->inwhere('GameQuestionAnswer.game_id',array($game_id))
							->inwhere('GameQuestionAnswer.question_id',array($gamedata->questions_no))
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
					if(!empty($gamedatabasearray)){
						if($percentage == 0){
							$totalpercentage = 2;
						}
						else{
							$totalpercentage=round($percentage);
						}
						
					}
					return $totalpercentage; 
				}
			}
		} else {
		$game_question = $this->modelsManager->createBuilder ()->columns ( array (
			'COUNT(GameQuestionAnswer.question_id) as question_id',
		))->from('GameQuestionAnswer')
		->inwhere('GameQuestionAnswer.game_id',array($game_id))
		->getQuery ()->execute ();
		foreach($game_question as $question_value){
			$question_id = $question_value->question_id;
		}
		$game_answer = $this->modelsManager->createBuilder ()->columns ( array (
			'COUNT(GameAnswers.questions_no) as questions_no',
		) )->from ( 'GameAnswers')
		->inwhere('GameAnswers.game_id',array($game_id))
		->inwhere('GameAnswers.nidara_kid_profile_id',array($kid_id))
		->inwhere ('GameAnswers.created_at',array($today_date))
		->getQuery ()->execute ();
		foreach($game_answer as $answer_value){
			$question_no = $answer_value->questions_no;
		}
		if($question_no == 0){
			$totalpercentage = 0;
			return $totalpercentage; 
		}
		else if($question_id != $question_no){
			$game_question2 = $this->modelsManager->createBuilder ()->columns ( array (
			'KidsGamesStatus.current_status as current_status',
			))->from('KidsGamesStatus')
			->inwhere('KidsGamesStatus.game_id',array($game_id))
			->inwhere('KidsGamesStatus.nidara_kid_profile_id',array($kid_id))
			->inwhere('KidsGamesStatus.current_status',array(1))
			->inwhere('KidsGamesStatus.created_date',array($today_date))
			->getQuery ()->execute ();
			foreach($game_question2 as $game_question2_value){
				
			}
			if($game_question2_value->current_status < 1){
				$totalpercentage = 1;
			return $totalpercentage;
			}
		else{
			$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
				'GameAnswers.answers as answers',
				'GameAnswers.questions_no as questions_no',
				'GameAnswers.session_id as session_id',
				'GameAnswers.game_id as game_ids'
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GameAnswers', 'GuidedLearningDayGameMap.game_id=GameAnswers.game_id' )
				->leftjoin ( 'GamesDatabase', 'GameAnswers.game_id=GamesDatabase.id' )
				->leftjoin ( 'NidaraKidProfile', 'GameAnswers.nidara_kid_profile_id=NidaraKidProfile.id' )
				->inwhere('GameAnswers.game_id',array($game_id))
				->inwhere('GameAnswers.nidara_kid_profile_id',array($kid_id))
				->inwhere ('GameAnswers.created_at',array($today_date))
				->getQuery ()->execute ();
				$gamedatabasearray=array();
				$percentage=0;
				foreach ( $gamedatabase as $gamedata ) {
					if($gamedata->questions_no != 0){
					if($gamedata->answers == 1){
						$answers = $answers + 1;
						$total = $total + 1;
					}
					else if($gamedata->answers > 1){
						$game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
							'GameQuestionAnswer.game_type_value as game_type_value',
						))->from('GameQuestionAnswer')
						->inwhere('GameQuestionAnswer.game_id',array($game_id))
						->inwhere('GameQuestionAnswer.question_id',array($gamedata->questions_no))
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
				if(!empty($gamedatabasearray)){
					if($percentage == 0){
						$totalpercentage = 2;
					}
					else{
						$totalpercentage=round($percentage);
					}
					
				}
				return $totalpercentage; 
			}
		}
		}
	}
	
	
	
	
	/**
	 * Get core Parent Game
	 * @return array
	 */
	public function getparentcoreframeworks() {
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
			$baseurl = $this->config->baseurl;
			$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
			// if ($token_check->status != 1) {
			// 	return $this->response->setJsonContent ( [ 
			// 			"status" => false,
			// 			"message" => "Invalid User" 
			// 	] );
			// }
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Kid Id is null'
			] );
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		if(empty($day_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Day Id is null'
			] );
		}
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Grade Id is null'
			] );
		}
		$games_database = $this->modelsManager->createBuilder ()->columns ( array (
				'ParentGuidedLearningDayGameMap.id',
				'ParentGuidedLearningDayGameMap.game_id',
				'ParentGamesDatabase.games_name',
				'CoreFrameworks.name as core_framework_name',
				'CoreFrameworks.id as framework_id',
				'Subject.id as subject_id',
				'Subject.subject_name as subject_name',
		) )->from ( 'ParentGuidedLearningDayGameMap' )
		->leftjoin ( 'ParentGamesDatabase', 'ParentGuidedLearningDayGameMap.game_id=ParentGamesDatabase.id' )
		->leftjoin ( 'KidGuidedLearningMap', 'ParentGuidedLearningDayGameMap.guided_learning_id=KidGuidedLearningMap.guided_learning_id' )
		->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
		->leftjoin ( 'CoreFrameworks', 'ParentGuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
		->leftjoin ( 'Subject', 'ParentGuidedLearningDayGameMap.subject_id=Subject.id' )
		->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
				$id
		) )->inwhere ( "ParentGuidedLearningDayGameMap.day_id", array (
				$day_id
		) )->inwhere ( "ParentGuidedLearningDayGameMap.guided_learning_id", array (
				$grade_id
		) )->getQuery ()->execute ();
		
		$core_array = array ();
		
		foreach ( $games_database as $core_data ) {
			 if(!empty($core_data->game_id)){
				
			$gamepercentage = $this->getparentgamepercentage ( $core_data->game_id,$id );
			$subject = GrMainReporting::findBysubject_id($core_data->subject_id);
			if(count($subject) == 0){
				$subject_id = 0;
			}
			else if(count($subject) > 0){
				$subject_id = $core_data->subject_id;
			}
			
			$core_data->coresubject = $subject_id;
			$GrMainReporting = $this->modelsManager->createBuilder ()->columns ( array (
					'GrMainReporting.id as id',
					'GrMainReporting.gr_framwork_id as gr_framwork_id',
					'GrMainReporting.add_grade_range_max as add_grade_range_max',
					'GrMainReporting.add_grade_range_min as add_grade_range_min',
					'GrMainReporting.add_color as add_color',
					'GrMainReporting.subject_id as subject_id',
			))->from('GrMainReporting')
			->inwhere("GrMainReporting.gr_type_id",array($core_data->framework_id))
			->inwhere("GrMainReporting.subject_id",array($subject_id))
			->inwhere("GrMainReporting.gr_frame_type",array(2))
			->getQuery ()->execute ();
			$gr_array = array ();
			foreach ( $GrMainReporting as $grcolorval ) {
				$grading_val ['id'] = $grcolorval->id;
				$grading_val ['gr_frame_id'] = $grcolorval->gr_framwork_id;
				$grading_val ['max'] = $grcolorval->add_grade_range_max;
				$grading_val ['min'] = $grcolorval->add_grade_range_min;
				$grading_val ['color'] = $grcolorval->add_color;
				$grading_val ['subject_id'] = $grcolorval->subject_id;
				$gr_array[] = $grading_val;
			if($gamepercentage == 0 || $gamepercentage == 100){
			  $core_data->kid_played=TRUE;
			  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
			  if($gamepercentage >= $grcolorval->add_grade_range_min && $gamepercentage <= $grcolorval->add_grade_range_max){
				$core_data->grade_color = $grcolorval->add_color;
			  }

			  //}
			}
			
			else{
			 $core_data->daily_tips="Milestone activity not completed";
			}
			}
			$core_data->coregrading=$gr_array;
			$core_data->game_percentage = $gamepercentage;
			$core_framework_name = strtolower( str_replace ( ' ', '_', $core_data->core_framework_name ) );
			$core_array [] = $core_data->core_framework_name;
			$core_frm_array [$core_framework_name] [] = $core_data;
			}
		} 
		$core_frame = CoreFrameworks::find ();
		foreach ( $core_frame as $core ) {
			if (! in_array ( $core->name, $core_array )) {
				$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
				$core_frm_array [$core->name] = array ();
			}
		}
		$kid=NidaraKidProfile::findFirstByid($id);
		if(!empty($kid)){
		$core_frm_array['kid_name']=$kid->first_name;
		}
		$core_frm_array['today_date']=date('l, F d, Y');
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array
		] );
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamepercentage
		] );
	}

	/**
	 * 
	 * @param unknown $game_id
	 * @return multitype:unknown
	 */
	public function getparentgamepercentage($game_id,$kid_id){
		$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
					'ParentGamesAnswer.answer as answers',
					'ParentGamesAnswer.game_id as game_id'
				) )->from('ParentGamesAnswer')
			->inwhere ( "ParentGamesAnswer.nidara_kid_profile_id", array (
					$kid_id 
			) )
			->inwhere ( "ParentGamesAnswer.game_id", array (
					$game_id 
			) )->getQuery ()->execute ();
			$gamedatabasearray=array();
			$percentage=0;
			foreach ( $gamedatabase as $gamedata ) {
				if($gamedata->answers){
					$answers = $answers + 1;
					$total = $total + 1;
				}
				else{
					$total = $total + 1;
			
				}
				$gamedatabasearray [] = $gamedata;
			}
			$percentage = round((($answers)/($total))*100);
			if(!empty($gamedatabasearray)){
				$totalpercentage=round($percentage);
			}
			return $totalpercentage; 
	}

public function getcoreframeworksbykidid()	
	{

		$input_data = $this->request->getJsonRawBody ();
		/*$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		else{*/
			$id=$input_data->nidara_kid_profile_id;
			$from = $input_data->from_date;
			$to = $input_data->to_date;
			$game_get = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GameAnswers.game_id',
				'GameAnswers.created_at',
			))->from('GameAnswers')
			->where("GameAnswers.created_at >='".$from."' and GameAnswers.created_at <='".$to."'")
			->inwhere ('GameAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->getQuery ()->execute ();

			if(count($game_get) <= 0){
				return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Ther is no data"
				] );
			} else {

		foreach ($game_get as $gamelist) {
			
			$guidelerning = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearningDayGameMap.id',
				'GuidedLearningDayGameMap.day_id',
				'GuidedLearningDayGameMap.framework_id',
				'GuidedLearningDayGameMap.subject_id',
				'GuidedLearningDayGameMap.day_guided_learning_id',
				'CoreFrameworks.name',
			))->from('GuidedLearningDayGameMap')
			->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
			->inwhere ('GuidedLearningDayGameMap.game_id',array($gamelist->game_id))
			->getQuery ()->execute ();

			foreach ($guidelerning as $guidelerningvalue) {
				
				$getgender = NidaraKidProfile::findFirstByid($id);
		if(!empty($getgender)){
			$gender = $getgender->gender;
		}
		$day_id = $guidelerningvalue->day_id;
		$month = 0;
		$week = 0;
		if($day_id >= 0){
			$day_id += 1;
		}
		if($day_id <= 20){
			$month = 1;
			$week = ((int)($day_id/5)+1);
		}
		else{
			$getmonth = ((int)($day_id/20));
			$month = $getmonth+1;
			$remain = ($day_id - ($getmonth*20));
			$week = ((int)($remain/5)+1);
		}
		$grade_id = $getgender->grade;
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Grade Id is null'
			] );
		}

		
			if($gender == 'famale'){
				$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();
			}
			else{
				$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();

		
			}
		

		
		$core_array = array ();
		
		foreach ( $games_database as $core_data ) {
			if(!empty($core_data->game_id)){
			$gamepercentage = $this->getgamepercentage ( $core_data->game_id,$id);
			$numberoftime = $this->numberoftime ( $core_data->game_id,$id);
			$subject = GrMainReporting::findBysubject_id($core_data->subject_id);
			if(count($subject) == 0){
				$subject_id = 0;
			}
			else if(count($subject) > 0){
				$subject_id = $core_data->subject_id;
			}
			$core_data->coresubject = $subject_id;
			$GrMainReporting = $this->modelsManager->createBuilder ()->columns ( array (
					'GrMainReporting.id as id',
					'GrMainReporting.gr_framwork_id as gr_framwork_id',
					'GrMainReporting.add_grade_range_max as add_grade_range_max',
					'GrMainReporting.add_grade_range_min as add_grade_range_min',
					'GrMainReporting.add_color as add_color',
					'GrMainReporting.subject_id as subject_id',
			))->from('GrMainReporting')
			->inwhere("GrMainReporting.gr_type_id",array($core_data->framework_id))
			->inwhere("GrMainReporting.subject_id",array($subject_id))
			//->inwhere("GrMainReporting.gr_frame_type",array(1))
			->getQuery ()->execute ();
			$gr_array = array ();
			foreach ( $GrMainReporting as $grcolorval ) {
				$grading_val ['id'] = $grcolorval->id;
				$grading_val ['gr_frame_id'] = $grcolorval->gr_framwork_id;
				$grading_val ['max'] = $grcolorval->add_grade_range_max;
				$grading_val ['min'] = $grcolorval->add_grade_range_min;
				$grading_val ['color'] = $grcolorval->add_color;
				$grading_val ['subject_id'] = $grcolorval->subject_id;
				$gr_array[] = $grading_val;
			if($gamepercentage >= 2){
			  $core_data->kid_played=TRUE;
			  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
			  if($gamepercentage >= $grcolorval->add_grade_range_min && $gamepercentage <= $grcolorval->add_grade_range_max){
				$core_data->grade_color=$grcolorval->add_color;
			  }
			  $core_data->game_playing = $numberoftime;
			  //}
			}
			else if($gamepercentage == 1){
				$core_data->daily_tips="Game not Completed";
			}
			else{
			 $core_data->daily_tips="Milestone activity not completed";
			}
			}
			$core_data->coregrading=$gr_array;
			$core_data->game_percentage=$gamepercentage;
			$core_framework_name = strtolower( str_replace ( ' ', '_', $core_data->core_framework_name ) );
			$core_array [] = $core_data->core_framework_name;
			$core_frm_array [$core_framework_name] [] = $core_data;
			}
		}
		$core_frame = CoreFrameworks::find ();
		foreach ( $core_frame as $core ) {
			if (! in_array ( $core->name, $core_array )) {
				$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
				$core_frm_array [$core->name] = array ();
			}
		}
		$kid=NidaraKidProfile::findFirstByid($id);
		if(!empty($kid)){
		$core_frm_array['kid_name']=$kid->first_name;
		}
		$core_frm_array['today_date']=date('l, F d, Y');
		$core_frm_array['month'] = $month;
		$core_frm_array['week'] = $week;
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array
		] );
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamepercentage
		] );
			}
		}



			return $this->response->setJsonContent ( [ 
					"status" => true,
					"message" => $framworklist 
			] );
		}
		

	}

public function getcoreframeworkstest() {
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
			if (!empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
			// $baseurl = $this->config->baseurl;
			// $token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
			// if ($token_check->status != 1) {
			// 	return $this->response->setJsonContent ( [ 
			// 			"status" => false,
			// 			"message" => "Invalid User" 
			// 	] );
			// }
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if(empty($id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Kid Id is null'
			] );
		}
		$getgender = NidaraKidProfile::findFirstByid($id);
		if(!empty($getgender)){
			$gender = $getgender->gender;
		}
		$day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
		$month = 0;
		$week = 0;
		$day = 0;
		$day_value = $day_id;
		if($day_id >= 0){
			$day_id += 1; 
		}
		if($day_id > 5){
			$getday = (int)($day_value/5);
			$day = ((int)($day_id - ($getday * 5)));
		} else {
			$day = $day_id;
		}
		if($day_id <= 20){
			$month = 1;
			$week = ((int)($day_value/5)+1);
		}
		else{
			$getmonth = ((int)($day_id/20));
			$month = $getmonth+1;
			$remain = ($day_id - ($getmonth*20));
			$week = ((int)($remain/5)+1);
		}
		$grade_id = isset ($input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'data' => 'Grade Id is null'
			] );
		}

		$getdemokid=DemoChildList::findFirstBynidara_kid_profile_id($id);

		
			if($gender == 'famale'){


				if(!$getdemokid)
					{
					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();

				}
				else
				{
					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'DemoGameList' )
				->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "DemoGameList.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.tina",array(
					1
				))->getQuery ()->execute ();

				}
			}
			else{


				if(!$getdemokid)
				{
						
						$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesDatabase', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=GuidedLearningDayGameMap.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )->inwhere ( "GuidedLearningDayGameMap.day_id", array (
						$day_id
				) )->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();

				}
				else
				{

					$games_database = $this->modelsManager->createBuilder ()->columns ( array (
						'GamesDatabase.id',
						'GamesDatabase.game_id',
						'GamesDatabase.games_name',
						'GamesDatabase.status',
						'GamesDatabase.daily_tips',
						'CoreFrameworks.name as core_framework_name',
						'CoreFrameworks.id as framework_id',
						'Subject.id as subject_id',
						'Subject.subject_name',
				) )->from ( 'DemoGameList' )
				->leftjoin ( 'GamesDatabase', 'DemoGameList.game_id=GamesDatabase.id' )
				->leftjoin ( 'KidGuidedLearningMap', 'KidGuidedLearningMap.guided_learning_id=DemoGameList.day_guided_learning_id' )
				->leftjoin ( 'NidaraKidProfile', 'KidGuidedLearningMap.nidara_kid_profile_id=NidaraKidProfile.id' )
				->leftjoin ( 'CoreFrameworks', 'DemoGameList.framework_id=CoreFrameworks.id' )
				->leftjoin ( 'Subject', 'DemoGameList.subject_id=Subject.id' )
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "DemoGameList.day_guided_learning_id", array (
						$grade_id
				) )->inwhere("GamesDatabase.rahul",array(
					1
				))->getQuery ()->execute ();


				}
			}
		// }
		
		$core_array = array ();
		
		foreach ( $games_database as $core_data ) {
			if(!empty($core_data->game_id)){
			$gamepercentage = $this->getgamepercentage ( $core_data->game_id,$id);
			$numberoftime = $this->numberoftime ( $core_data->game_id,$id);
			$subject = GrMainReporting::findBysubject_id($core_data->subject_id);
			if(count($subject) == 0){
				$subject_id = 0;
			}
			else if(count($subject) > 0){
				$subject_id = $core_data->subject_id;
			}
			$core_data->coresubject = $subject_id;
			$GrMainReporting = $this->modelsManager->createBuilder ()->columns ( array (
					'GrMainReporting.id as id',
					'GrMainReporting.gr_framwork_id as gr_framwork_id',
					'GrMainReporting.add_grade_range_max as add_grade_range_max',
					'GrMainReporting.add_grade_range_min as add_grade_range_min',
					'GrMainReporting.add_color as add_color',
					'GrMainReporting.subject_id as subject_id',
			))->from('GrMainReporting')
			->inwhere("GrMainReporting.gr_type_id",array($core_data->framework_id))
			->inwhere("GrMainReporting.subject_id",array($subject_id))
			//->inwhere("GrMainReporting.gr_frame_type",array(1))
			->getQuery ()->execute ();
			$gr_array = array ();
			foreach ( $GrMainReporting as $grcolorval ) {
				$grading_val ['id'] = $grcolorval->id;
				$grading_val ['gr_frame_id'] = $grcolorval->gr_framwork_id;
				$grading_val ['max'] = $grcolorval->add_grade_range_max;
				$grading_val ['min'] = $grcolorval->add_grade_range_min;
				$grading_val ['color'] = $grcolorval->add_color;
				$grading_val ['subject_id'] = $grcolorval->subject_id;
				$gr_array[] = $grading_val;
			if($gamepercentage >= 2){
			  $core_data->kid_played=TRUE;
			  //if($core_data->framework_id == $grcolorval->gr_framwork_id){
			  if($gamepercentage >= $grcolorval->add_grade_range_min && $gamepercentage <= $grcolorval->add_grade_range_max){
				$core_data->grade_color=$grcolorval->add_color;
			  }
			  $core_data->game_playing = $numberoftime;
			  //}
			}
			else if($gamepercentage == 1){
				$core_data->daily_tips="Game not Completed";
			}
			else{
			 $core_data->daily_tips="Milestone activity not completed";
			}
			}
			$core_data->coregrading=$gr_array;
			$core_data->game_percentage=$gamepercentage;
			$core_framework_name = strtolower( str_replace ( ' ', '_', $core_data->core_framework_name ) );
			$core_array [] = $core_data->core_framework_name;
			$core_frm_array [$core_framework_name] [] = $core_data;
			}
		}
		$core_frame = CoreFrameworks::find ();
		foreach ( $core_frame as $core ) {
			if (! in_array ( $core->name, $core_array )) {
				$core->name = strtolower( str_replace ( ' ', '_', $core->name ) );
				$core_frm_array [$core->name] = array ();
			}
		}
		$kid=NidaraKidProfile::findFirstByid($id);
		if(!empty($kid)){
		$core_frm_array['kid_name']=$kid->first_name;
		$core_frm_array['child_photo']=$kid->child_photo;
		}
		$core_frm_array['today_date']=date('l, F d, Y');
		$core_frm_array['month'] = $month;
		$core_frm_array['week'] = $week;
		$core_frm_array['day'] = $day;
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $core_frm_array
		] );
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamepercentage
		] );
	}	

}
