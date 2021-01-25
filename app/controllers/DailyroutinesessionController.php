<?php
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Aws\Credentials\CredentialProvider;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
require BASE_PATH.'/vendor/autoload.php';
class DailyroutinesessionController extends \Phalcon\Mvc\Controller {
	
	public function viewall()
	{
		
		$DailyRoutineSession=DailyRoutineSession::find();
		if ($DailyRoutineSession) :
			return Json_encode ( $DailyRoutineSession );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faild' 
			] );
		endif;
	}


public function dailyschedulerlist()
	{
		$shudlelist = $this->modelsManager->createBuilder ()->columns ( array (
				'DailySchedulerlist.id',
				'DailySchedulerlist.session_for',
				'DailySchedulerlist.create_at',
				'DailyRoutineSession.id as daily_routine_sessionid',
				'DailyRoutineSession.session_name',
				'DailyRoutineSession.status',
				'DailySchedulerlist.start_time',
				'DailySchedulerlist.end_time',
				'NcTimelist.id as starttimeid',
		) )->from ('DailySchedulerlist')
		->leftjoin('DailyRoutineSession','DailySchedulerlist.session_for = DailyRoutineSession.id')
		->leftjoin('NcTimelist','DailySchedulerlist.start_time = NcTimelist.id')
		->getQuery ()->execute ();

		return $this->response->setJsonContent ( [ 
					'status' => true,
					'Data' => $shudlelist  
			] );

	}


public function viewalltime()
	{
		
		$NcTimelist=NcTimelist::find();
		if ($NcTimelist) :

			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $NcTimelist
			] );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faild' 
			] );
		endif;
	}

	
	public function savedailyroutine(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
				"status" => false,
				"message" => "Please give the token"
			] );
		}
		$input_data = $this->request->getJsonRawBody ();
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the input datas" 
			] );
		} else {
			$kid_profile = NidaraKidProfile::findFirstByid($input_data -> nidara_kid_profile_id);
			$kid_profile -> choose_time = $input_data -> choose_time;
			$kid_profile -> save();
			foreach($input_data -> dailyRoutine as $values){
				$getStartTime = NcTimelist::findFirstByid($values -> start_time);
				$getEndTime = NcTimelist::findFirstByid($values -> end_time);
				if($values->session_for == '6'){
				$routine_days = $this->modelsManager->createBuilder ()->columns ( array (
					'DailyRoutine.id as id' 
				) )->from ( 'DailyRoutine' )
				->inwhere ( "DailyRoutine.nidara_kid_profile_id", array ( $input_data -> nidara_kid_profile_id ))
				->inwhere ( "DailyRoutine.session_for", array ( $values->session_for ))
				->inwhere ( "DailyRoutine.set_time", array ( $getStartTime -> tittle_value ))
				->getQuery ()->execute ();
				} else {
				$routine_days = $this->modelsManager->createBuilder ()->columns ( array (
					'DailyRoutine.id as id' 
				) )->from ( 'DailyRoutine' )
				->inwhere ( "DailyRoutine.nidara_kid_profile_id", array ( $input_data -> nidara_kid_profile_id ))
				->inwhere ( "DailyRoutine.session_for", array ( $values->session_for ))
				->getQuery ()->execute ();
				}
				if(count($routine_days) <= 0){
					$routine = new DailyRoutine ();
					$routine->id = $this->dailyroutineidgen->getNewId ( "dailyroutine" );
					$getTask = DailyRoutineSession::findFirstByid($values->session_for);
					$routine->task_name = $getTask -> session_name;
					$routine->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
				}
				else{
				foreach($routine_days as $days){
					$routine = DailyRoutine::findFirstByid($days -> id);
						if(empty($routine)){
							$routine = new DailyRoutine ();
							$routine->id = $this->dailyroutineidgen->getNewId ( "dailyroutine" );
							$getTask = DailyRoutineSession::findFirstByid($values->session_for);
							$routine->task_name = $getTask -> session_name;
							$routine->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
						}
					}
				}
				$routine->set_time = $getStartTime -> tittle_value;
				$routine->end_time = $getEndTime -> tittle_value;
				$routine->session_for = $values->session_for;
				$routine->reminder = '15_min_before';
				$routine->repeatday = 'repeat';
				if(!$routine->save ()){
					return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Data not saved",
						"data" => $routine
					] );
				}
			}
			if($input_data -> choose_time === '1'){
				$collaction_day = $this->modelsManager->createBuilder ()->columns ( array(
					'DailyRoutineAttendance.id as id',
				))->from('DailyRoutineAttendance')
				->inwhere('DailyRoutineAttendance.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
				->inwhere('DailyRoutineAttendance.attendanceDate',array(date ('Y-m-d')))
				->getQuery ()->execute ();
				if(count($collaction_day) > 0){
					return $this->response->setJsonContent ( [
						"status" => true,
						"message" => "You have changed your mode to Anytime Mode.  You have already completed the session today. The update will take effect from tomorrow onwards."
					] );
				} else {
					return $this->response->setJsonContent ( [
						"status" => true,
						"message" => "Your session time has been updated to Anytime Mode successfully."
					] );
				}
			} else {
				return $this->response->setJsonContent ( [
					"status" => true,
					"message" => "Your session time has been updated successfully."
				] );
			}
		}
	}
}
