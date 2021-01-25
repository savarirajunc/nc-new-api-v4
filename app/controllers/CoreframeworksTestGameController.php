<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class CoreframeworksTestGameController extends \Phalcon\Mvc\Controller {

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


$collection = GuidedLearningTestUser::findFirstBykid_id($input_data->nidara_kid_profile_id);

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
				->where('GuidedLearningDayGameMap.day_id >="' . $sdate .'" AND GuidedLearningDayGameMap.day_id <="'.$edate.'"')
				->inwhere("GuidedLearningDayGameMap.subject_id",array($collection->subject_id))
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
				->where('GuidedLearningDayGameMap.day_id >="' . $sdate .'" AND GuidedLearningDayGameMap.day_id <="'.$edate.'"')
				->inwhere("GuidedLearningDayGameMap.subject_id",array($collection->subject_id))
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
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
				->where('GuidedLearningDayGameMap.day_id >="' . $sdate .'" AND GuidedLearningDayGameMap.day_id <="'.$edate.'"')
				->inwhere("GuidedLearningDayGameMap.subject_id",array($collection->subject_id))
				->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id", array (
						$id
				) )
				->inwhere ( "GuidedLearningDayGameMap.day_guided_learning_id", array (
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

			$subject = GradingReporting::findBysubject_id($core_data->subject_id);
			if(count($subject) == 0){
				$subject_id = 0;
			}
			else if(count($subject) > 0){
				$subject_id = $core_data->subject_id;
			}

			$core_data->coresubject = $subject_id;
			$gradingreporting = $this->modelsManager->createBuilder ()->columns ( array (
					'GradingReporting.id as id',
					'GradingReporting.gr_framwork_id as gr_framwork_id',
					'GradingReporting.add_grade_range_max as add_grade_range_max',
					'GradingReporting.add_grade_range_min as add_grade_range_min',
					'GradingReporting.add_color as add_color',
					'GradingReporting.subject_id as subject_id',
			))->from('GradingReporting')
			->inwhere("GradingReporting.gr_type_id",array($core_data->framework_id))
			->inwhere("GradingReporting.subject_id",array($subject_id))
			//->inwhere("GradingReporting.gr_frame_type",array(1))
			->getQuery ()->execute ();
			$gr_array = array ();
			foreach ( $gradingreporting as $grcolorval ) {
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
				$subject2 = GradingReporting::findBysubject_id($core_data2->subject_id);
				if(count($subject2) == 0){
					$subject_id2 = 0;
				}
				else if(count($subject2) > 0){
					$subject_id2 = $core_data2->subject_id;
				}
				$core_data2->coresubject = $subject_id2;
				$gradingreporting2 = $this->modelsManager->createBuilder ()->columns ( array (
						'GradingReporting.id as id',
						'GradingReporting.gr_framwork_id as gr_framwork_id',
						'GradingReporting.add_grade_range_max as add_grade_range_max',
						'GradingReporting.add_grade_range_min as add_grade_range_min',
						'GradingReporting.add_color as add_color',
						'GradingReporting.subject_id as subject_id',
				))->from('GradingReporting')
				->inwhere("GradingReporting.gr_type_id",array($core_data2->framework_id))
				->inwhere("GradingReporting.subject_id",array($subject_id2))
				//->inwhere("GradingReporting.gr_frame_type",array(1))
				->getQuery ()->execute ();
				$gr_array2 = array ();
				foreach ( $gradingreporting2 as $grcolorval2 ) {
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

	public function getgamepercentage($game_id,$kid_id){
		$today_date = date('Y-m-d');
		$question_id = 0;
		$question_no = 0;
		$kidprofile = NidaraKidProfile::findFirstByid ($kid_id);
		if($kidprofile -> test_kid_status == 0){
			$game_question = $this->modelsManager->createBuilder ()->columns ( array (
				'COUNT(GamesQuestionAnswer.question_id) as question_id',
			))->from('GamesQuestionAnswer')
			->inwhere('GamesQuestionAnswer.game_id',array($game_id))
			->getQuery ()->execute ();
			foreach($game_question as $question_value){
				$question_id = $question_value->question_id;
			}
			$game_answer = $this->modelsManager->createBuilder ()->columns ( array (
				'COUNT(GamesAnswers.questions_no) as questions_no',
			) )->from ( 'GamesAnswers')
			->inwhere('GamesAnswers.game_id',array($game_id))
			->inwhere('GamesAnswers.nidara_kid_profile_id',array($kid_id))
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
					'GamesAnswers.answers as answers',
					'GamesAnswers.questions_no as questions_no',
					'GamesAnswers.session_id as session_id',
					'GamesAnswers.game_id as game_ids'
					) )->from ( 'GuidedLearningDayGameMap' )
					->leftjoin ( 'GamesAnswers', 'GuidedLearningDayGameMap.game_id=GamesAnswers.game_id' )
					->leftjoin ( 'GamesDatabase', 'GamesAnswers.game_id=GamesDatabase.id' )
					->leftjoin ( 'NidaraKidProfile', 'GamesAnswers.nidara_kid_profile_id=NidaraKidProfile.id' )
					->inwhere('GamesAnswers.game_id',array($game_id))
					->inwhere('GamesAnswers.nidara_kid_profile_id',array($kid_id))
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
			'COUNT(GamesQuestionAnswer.question_id) as question_id',
		))->from('GamesQuestionAnswer')
		->inwhere('GamesQuestionAnswer.game_id',array($game_id))
		->getQuery ()->execute ();
		foreach($game_question as $question_value){
			$question_id = $question_value->question_id;
		}
		$game_answer = $this->modelsManager->createBuilder ()->columns ( array (
			'COUNT(GamesAnswers.questions_no) as questions_no',
		) )->from ( 'GamesAnswers')
		->inwhere('GamesAnswers.game_id',array($game_id))
		->inwhere('GamesAnswers.nidara_kid_profile_id',array($kid_id))
		->inwhere ('GamesAnswers.created_at',array($today_date))
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
				'GamesAnswers.answers as answers',
				'GamesAnswers.questions_no as questions_no',
				'GamesAnswers.session_id as session_id',
				'GamesAnswers.game_id as game_ids'
				) )->from ( 'GuidedLearningDayGameMap' )
				->leftjoin ( 'GamesAnswers', 'GuidedLearningDayGameMap.game_id=GamesAnswers.game_id' )
				->leftjoin ( 'GamesDatabase', 'GamesAnswers.game_id=GamesDatabase.id' )
				->leftjoin ( 'NidaraKidProfile', 'GamesAnswers.nidara_kid_profile_id=NidaraKidProfile.id' )
				->inwhere('GamesAnswers.game_id',array($game_id))
				->inwhere('GamesAnswers.nidara_kid_profile_id',array($kid_id))
				->inwhere ('GamesAnswers.created_at',array($today_date))
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
}
