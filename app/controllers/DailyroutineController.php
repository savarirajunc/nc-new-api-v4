<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Mvc\Model;
use Phalcon\Validation\Validator\PresenceOf;
class DailyroutineController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$daily_routine = DailyRoutine::find ();
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
	
	/**
	 * This function using to create DailySchedulerDaysMap information
	 */
	
	public function routineviewall() {
		$daily_routine = DailyRoutine::find ();
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
	
	/**
	 * This function using to create DailySchedulerDaysMap information
	 */

	 
	public function create(){
		try {
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
			$input_data = $this->request->getJsonRawBody ();
			if (empty ( $input_data )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the input datas" 
				] );
			}
			if($input_data->choose_time == 1){
				$collaction = NidaraKidProfile::findFirstByid ($input_data->nidara_kid_profile_id);
				$collaction->set_time = $input_data->choose_time;
				if($collaction->save()){
					$secces = 'Your daily Nidara session time has been updated successfully.Your Nidara session today will also start on the updated time.';
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => $secces
					] );
				}
			}
			else{
			$collaction = NidaraKidProfile::findFirstByid ($input_data->nidara_kid_profile_id);
			$collaction->set_time = $input_data->choose_time;
			$collaction->save();
			/**
			 * This object using valitaion
			 */
			$validation = new Validation ();
			$validation->add ( 'task_name', new PresenceOf ( [ 
					'message' => 'Task name is required' 
			] ) );
			$validation->add ( 'set_time', new PresenceOf ( [ 
					'message' => 'To set_time is required' 
			] ) );
			$validation->add ( 'reminder', new PresenceOf ( [ 
					'message' => 'reminder is required' 
			] ) );
			$validation->add ( 'repeatday', new PresenceOf ( [ 
					'message' => 'repeat is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach;

				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
			 else : 
			      if(preg_match('/NaN/',$input_data->from_time)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid from time" 
					] );
			      }elseif(preg_match('/NaN/',$input_data->to_time)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid to time" 
					] );
			      }
				$routine = $input_data->scheduler_days_id;
				if (empty ( $routine )) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please select the days" 
					] );
				}
				foreach ( $routine as $value ) {
					$shedulerday = SchedulerDays::findFirstByname ( $value->day );
					if(empty($shedulerday)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid routine day" 
					] );
					}
				}
				$check_val = $input_data->session_for;
				if($check_val == 1){
				
					$routine_days=DailyRoutine::findFirstBynidara_kid_profile_id($input_data->nidara_kid_profile_id);
					
					if(empty($routine_days)){
						$routine_days = new DailyRoutine ();
						$routine_days->id = $this->dailyroutineidgen->getNewId ( "dailyroutine" );
						$routine_days->task_name = $input_data->task_name;
						$routine_days->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					}
					
					$routine_days->set_time = $input_data->set_time;
					$routine_days->session_for = $input_data->session_for;
					$routine_days->reminder = $input_data->reminder;
					$routine_days->repeatday = $input_data->repeatday;
					$routine_days->save ();
					foreach ( $routine as $value ) {
						$shedulerday = SchedulerDays::findFirstByname ( $value->day );
						$routinedaysexist = $this->modelsManager->createBuilder ()->columns ( array (
								'DailyRoutineDayMap.id as routine_id' 
						) )->from ( 'DailyRoutineDayMap' )->inwhere ( "daily_routine_id", array (
								$routine_days->id 
						) )->inwhere ( "scheduler_days_id", array (
								$shedulerday->id 
						) )->getQuery ()->getSingleResult ();
						if (! empty ( $routinedaysexist )) {
							if ($value->option != 1) {
								$routineexist = DailyRoutineDayMap::findFirstByid ( $routinedaysexist->routine_id );
								$routineexist->delete ();
							}
						} 
						else {
							if ($value->option == 1) {
								$daily_routine = new DailyRoutineDayMap ();
								$daily_routine->id = $this->dailyroutineidgen->getNewId ( "dailyschedulemap" );
								$daily_routine->daily_routine_id = $routine_days->id;
								$daily_routine->scheduler_days_id = $shedulerday->id;
								if (! $daily_routine->save ()) {
									return $this->response->setJsonContent ( [ 
											'status' => false,
											'message' => 'Failed' 
									] );
								}
							}
						}
					}
				}
				else if($check_val == 2){
					$routinetoday = DailyRoutineToday::findFirstBynidara_kid_profile_id($input_data->nidara_kid_profile_id);
					if(empty($routinetoday)){
						$routine_days = new DailyRoutineToday ();
						$routine_days->id = $this->dailyroutineidgen->getNewId ( "dailyroutineToday" );
						$routine_days->task_name = $input_data->task_name;
						$routine_days->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
						$routine_days->set_time = $input_data->set_time;
						$routine_days->session_for = $input_data->session_for;
						if($routine_days->save()){
								return $this->response->setJsonContent ( [ 
									'status' => true,
									'message' => 'Time change just for today has been done successfully.  You have only 1 time change attempt for this month left.'
								] );
							}
					}
					else{
						$collaction_date = DailyRoutineToday::find(['month(createdDate) = month(now())']);
						$collaction = round(count($collaction_date));
						if($collaction < 2){
							$routine_days = new DailyRoutineToday ();
							$routine_days->id = $this->dailyroutineidgen->getNewId ( "dailyroutineToday" );
							$routine_days->task_name = $input_data->task_name;
							$routine_days->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
							$routine_days->set_time = $input_data->set_time;
							$routine_days->session_for = $input_data->session_for;
							if($routine_days->save()){
								return $this->response->setJsonContent ( [ 
									'status' => true,
									'message' => 'Time change just for today has been done successfully. You have only '.(1 - $collaction).' time change attempt for this month left.'
								] );
							}
						}
						else{
							return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' => 'You have already used your 2 time change attempts for this month.' 
								] );
						}
						
					}
				}
				$secces = 'Your daily Nidara session time has been updated successfully.Your Nidara session today will also start on the updated time.';
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => $secces
				] );
				endif;
			} 
		}
		catch ( Exception $e ) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while saving the datas' 
			] );
		}
	}
	
	
	
	 
	 
	public function save() {
		try {
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
			$input_data = $this->request->getJsonRawBody ();
			if (empty ( $input_data )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the input datas" 
				] );
			}
			/**
			 * This object using valitaion
			 */
			$validation = new Validation ();
			$validation->add ( 'task_name', new PresenceOf ( [ 
					'message' => 'Task name is required' 
			] ) );
			$validation->add ( 'set_time', new PresenceOf ( [ 
					'message' => 'To set_time is required' 
			] ) );
			$validation->add ( 'reminder', new PresenceOf ( [ 
					'message' => 'reminder is required' 
			] ) );
			$validation->add ( 'repeatday', new PresenceOf ( [ 
					'message' => 'repeat is required' 
			] ) );
			$validation->add ( 'scheduler_days_id', new PresenceOf ( [ 
					'message' => 'SchedulerDays is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach;

				return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
			 else : 
			      if(preg_match('/NaN/',$input_data->from_time)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid from time" 
					] );
			      }elseif(preg_match('/NaN/',$input_data->to_time)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid to time" 
					] );
			      }
				$routine = $input_data->scheduler_days_id;
				if (empty ( $routine )) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please select the days" 
					] );
				}
				foreach ( $routine as $value ) {
					$shedulerday = SchedulerDays::findFirstByname ( $value->day );
					if(empty($shedulerday)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid routine day" 
					] );
					}
				}
				$routine_days = DailyRoutine::findFirstByid ( $input_data->task_id );
				if (empty ( $routine_days )) {
					$routine_days = new DailyRoutine ();
					$routine_days->id = $this->dailyroutineidgen->getNewId ( "dailyroutine" );
				}
				/* if(isset($input_data->status)){
					$routine_days->status = $input_data->status;
				} */
				$routine_days->id = $input_data->task_id;
				$routine_days->set_time = $input_data->set_time;
				$routine_days->reminder = $input_data->reminder;
				$routine_days->repeatday = $input_data->repeatday;
				$routine_days->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
				$routine_days->save ();
				foreach ( $routine as $value ) {
					$shedulerday = SchedulerDays::findFirstByname ( $value->day );
					$routinedaysexist = $this->modelsManager->createBuilder ()->columns ( array (
							'DailyRoutineDayMap.id as routine_id' 
					) )->from ( 'DailyRoutineDayMap' )->inwhere ( "daily_routine_id", array (
							$routine_days->id 
					) )->inwhere ( "scheduler_days_id", array (
							$shedulerday->id 
					) )->getQuery ()->getSingleResult ();
					if (! empty ( $routinedaysexist )) {
						if ($value->option != 1) {
							$routineexist = DailyRoutineDayMap::findFirstByid ( $routinedaysexist->routine_id );
							$routineexist->delete ();
						}
					} else {
						if ($value->option == 1) {
							$daily_routine = new DailyRoutineDayMap ();
							$daily_routine->id = $this->dailyroutineidgen->getNewId ( "dailyschedulemap" );
							$daily_routine->daily_routine_id = $routine_days->id;
							$daily_routine->scheduler_days_id = $shedulerday->id;
							if (! $daily_routine->save ()) {
								return $this->response->setJsonContent ( [ 
										'status' => false,
										'message' => 'Failed' 
								] );
							}
						}
					}
				}
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'Daily Routine saved successfully' 
				] );
			endif;
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while saving the datas' 
			] );
		}
	}
	public function getroutinekidid() {
		try{
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
		$input_data = $this->request->getJsonRawBody ();
		$nidara_kid_profile_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $nidara_kid_profile_id )) :
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			]);
		 else :
			
			//$days = DailyRoutine::findBynidara_kid_profile_id($nidara_kid_profile_id);

			$curtime=date('H:i:s');
			$query = $this->modelsManager->createQuery("SELECT * FROM DailyRoutine  where '".$curtime."' BETWEEN set_time and end_time and nidara_kid_profile_id='".$nidara_kid_profile_id."'");
			$days  = $query->execute();
			$dailyroutineidarray=array();
			if ($days) {
				foreach($days as $routianevale){
				$dailyroutine['id']=$routianevale->id;
				$dailyroutine['task_name']=$routianevale->task_name;
				if($routianevale->session_for === 1){
					$collaction = NidaraKidProfile::findFirstByid ($nidara_kid_profile_id);
					if($collaction->choose_time == 1){
						$dailyroutine['set_time']=date('H:i:s');
					}
					else{
						$dailyroutine['set_time']=$routianevale->set_time;	
					}
					$dailyattendance = $this->modelsManager->createBuilder ()->columns ( array(
						'DailyRoutineAttendance.id as id',
					))->from('DailyRoutineAttendance')
					->inwhere('DailyRoutineAttendance.nidara_kid_profile_id',array($nidara_kid_profile_id))
					->inwhere('DailyRoutineAttendance.attendanceDate',array(date ('Y-m-d')))
					->getQuery ()->execute ();
					// $dailyattendance=DailyRoutineAttendance::findFirstBynidara_kid_profile_id($nidara_kid_profile_id);
					$dailyattendancearray = array();
					if(empty($dailyattendance)){
						$data_filed['start_time'] = 0;
						$dailyattendancearray[] = $data_filed;
					}
					else{
						foreach($dailyattendance as $dailyattendancevalue){
							$data_filed['start_time'] = $dailyattendancevalue->start_time;
							$data_filed['kid_id'] = $dailyattendancevalue->nidara_kid_profile_id;
							$dailyattendancearray[] = $data_filed;
						}
					}
				} else {
					$dailyroutine['set_time']=$routianevale->set_time;	
					$data_filed['start_time'] = 0;
					$dailyattendancearray[] = $data_filed;
				}
				$getStartTime = NcTimelist::findFirstBytittle_value($routianevale->set_time);
				$getEndTime = NcTimelist::findFirstBytittle_value($routianevale->end_time);
				$dailyroutine['reminder']=$routianevale->reminder;
				$dailyroutine['end_time']=$routianevale->end_time;
				$dailyroutine['session_for']=$routianevale->session_for;
				$dailyroutine['start_time_title'] = $getStartTime->tittle_name;
				$dailyroutine['end_time_title'] = $getEndTime->tittle_name;
				$getTask = DailyRoutineSession::findFirstByid($routianevale->session_for);
				$dailyroutine['title'] = $getTask -> title;
				$dailyroutine['repeatday']=$routianevale->repeatday;
				$dailyroutine['createdDate']=$routianevale->createdDate;				
				$dailyroutine['nidara_kid_profile_id']=$routianevale->nidara_kid_profile_id;
				$dailytoday= DailyRoutineToday::findFirstBynidara_kid_profile_id($routianevale->nidara_kid_profile_id);
				$dailytodayarray = array(); 
				if(empty($dailytoday)){
							$data_filed2['today_start_time'] = 0;
							$dailytodayarray[] = $data_filed2;
				}
				else{
					$dailytoday_day = DailyRoutineToday::find(['day(createdDate) = day(now())']);
					if(count($dailytoday_day) == 0){
							$data_filed3['today_start_time'] = 0;
							$dailytodayarray[] = $data_filed3;
					}
					else{
						foreach($dailytoday_day as $value2){
							$data_filed2['today_start_time'] = $value2->set_time;
							//$data_filed2['day_test'] = $dailytoday_day;
							$dailytodayarray[] = $data_filed2;
							
						}
					}
					
				}
				$routinedays=SchedulerDays::find();
				$dailyroutinearray=array();
					foreach ( $routinedays as $shedulerday ) {
						$routingdaysexist="";
					    if(isset($days->id) && !empty($days->id)){
						$routinedaysexist = $this->modelsManager->createBuilder ()->columns ( array (
								'DailyRoutineDayMap.id as routine_id',
						) )->from ( 'DailyRoutineDayMap' )->inwhere ( "daily_routine_id", array (
								$days->id 
						) )->inwhere ( "scheduler_days_id", array (
								$shedulerday->id 
						) )->getQuery ()->getSingleResult ();
					     }
						$routinedayarray ['day'] = $shedulerday->name;
						if (! empty ( $routinedaysexist )) {
							$routinedayarray ['option'] = 1;
						} else {
							$routinedayarray['option'] = 0;
						}
						$dailyroutinearray[]=$routinedayarray;
					}
			        $dailyroutine['scheduler_days_id']= $dailyroutinearray;
					$dailyroutine['start_time']=$dailyattendancearray;
					$dailyroutine['today_start_time']=$dailytodayarray;
					$dailyroutineidarray[]=$dailyroutine;
				}
				
			}
			else{
				$dailyroutine['task_name']="";
				$dailyroutine['set_time2']=date('H:i:s');	
				$dailyroutine['reminder']="";					
				$dailyroutine['repeatday']="";	
			}
				
					
				return $this->response->setJsonContent ([ 
					'status' => true,
					'data' => $dailyroutineidarray
			]);
			
		endif;
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Error while getting the datas'
			] );
		}
	}
	
	/**
	 * Get daily routine session list
	 */
		

	
	/**
	 * Get daily scheduler by kid profile id
	 * @param integer $nidara_kid_profile_id
	 * @return string
	 */
	public function getbykidid() {
		try{
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
		$input_data = $this->request->getJsonRawBody ();
		$dailyroutindata = array();
		$nidara_kid_profile_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $nidara_kid_profile_id )) :
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			]);
		 else :
		 	$daylyroutine = DailyRoutine::findBynidara_kid_profile_id($nidara_kid_profile_id);
		 	$kid_profile = NidaraKidProfile::findFirstByid($nidara_kid_profile_id);
		 	if(count($daylyroutine) <= 0){
		 	$day = $this->modelsManager->createBuilder ()->columns ( array (
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
			// $day = DailyRoutine::findBynidara_kid_profile_id($nidara_kid_profile_id);
			$daysarray = array();
			if ($day) {
			foreach($day as $days){
				$dailyroutine['task_name']=$days->task_name;
				$dailyroutine['start_time']=$days->start_time;
				$dailyroutine['end_time']=$days->end_time;	
				$dailyroutine['reminder']=$days->reminder;
				$dailyroutine['repeatday']=$days->repeatday;
				$dailyroutine['session_for']=$days->session_for;
				$getTask = DailyRoutineSession::findFirstByid($days->session_for);
				$dailyroutine['title']=$getTask -> title;				
				$dailyroutine['nidara_kid_profile_id']=$days->nidara_kid_profile_id;
				$daysarray[] = $dailyroutine;
				}
				//$dailyroutine = DailyRoutineToday::findFirstBynidara_kid_profile_id($days->nidara_kid_profile_id);
			}
			else{


				$dailyroutine['task_name']="";
				$dailyroutine['set_time']=date('H:i:s');	
				$dailyroutine['reminder']="";					
				$dailyroutine['repeatday']="";
				$dailyroutine['session_for']='';
				$daysarray[] = $dailyroutine;	
			}
		} else {
			foreach($daylyroutine as $days){
				$getStartTime = NcTimelist::findFirstBytittle_value($days->set_time);
				$getEndTime = NcTimelist::findFirstBytittle_value($days->end_time);
				$dailyroutine['task_name']=$days->task_name;
				$dailyroutine['start_time']=$getStartTime->id;
				$dailyroutine['end_time']=$getEndTime->id;	
				$dailyroutine['reminder']=$days->reminder;
				$dailyroutine['repeatday']=$days->repeatday;
				$dailyroutine['session_for']=$days->session_for;
				$getTask = DailyRoutineSession::findFirstByid($days->session_for);
				$dailyroutine['title']=$getTask -> title;				
				$dailyroutine['nidara_kid_profile_id']=$days->nidara_kid_profile_id;
				$daysarray[] = $dailyroutine;
				}
				//$dailyroutine = DailyRoutineToday::findFirstBynidara_kid_profile_id($days->nidara_kid_profile_id);
			}
			
				$routinedays=SchedulerDays::find();
				$dailyroutinearray=array();
					foreach ( $routinedays as $shedulerday ) {
						$routingdaysexist="";
					    if(isset($days->id) && !empty($days->id)){
						$routinedaysexist = $this->modelsManager->createBuilder ()->columns ( array (
								'DailyRoutineDayMap.id as routine_id',
						) )->from ( 'DailyRoutineDayMap' )->inwhere ( "daily_routine_id", array (
								$days->id 
						) )->inwhere ( "scheduler_days_id", array (
								$shedulerday->id 
						) )->getQuery ()->getSingleResult ();
					     }
						$routinedayarray ['day'] = $shedulerday->name;
						if (! empty ( $routinedaysexist )) {
							$routinedayarray ['option'] = 1;
						} else {
							$routinedayarray['option'] = 0;
						}
						$dailyroutinearray[]=$routinedayarray;
					}
			        $dailyroutine2['scheduler_days_id']= $dailyroutinearray;
			        $dailyroutine2['dailyRoutineData']= $daysarray;
			        $dailyroutine2['choose_time']= $kid_profile -> choose_time;
			        $dailyroutindata[] = $dailyroutine2;
				return $this->response->setJsonContent ([ 
					'status' => true,
					'data' => $dailyroutindata
			]);
			
		endif;
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Error while getting the datas'
			] );
		}
	}
	
	public function savechilddayattendance(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$input_data = $this->request->getJsonRawBody ();
		$getchildinfo = $this->modelsManager->createBuilder ()->columns ( array (
			'ChildDayAttendance.id as id',
			'ChildDayAttendance.day_id as day_id',
		))->from("ChildDayAttendance")
		->inwhere("ChildDayAttendance.child_id", array($input_data->child_id))
		->inwhere("ChildDayAttendance.day_id", array($input_data->day_id))
		->inwhere("ChildDayAttendance.create_at", array(date('Y-m-d')))
		->getQuery()->execute ();
		if(count($getchildinfo) <= 0){
			$addchildinfo = new ChildDayAttendance();
			$addchildinfo -> child_id = $input_data->child_id;
			$addchildinfo -> day_id = $input_data->day_id;
			$addchildinfo -> create_at = date('Y-m-d');
			if(!$addchildinfo -> save()){
				return $this->response->setJsonContent([
					'status' => false,
					'message' => 'Data not found for id',
					'data' => $addchildinfo
				]);	
			} else {
				return $this->response->setJsonContent([
					'status' => true,
					'message' => 'Activated successfully'
				]);
			}
		} else {
			foreach($getchildinfo as $value){
				$collaction = ChildDayAttendance::findFirstByid($value -> id);
				$collaction -> day_id = $input_data->day_id;
				if(!$collaction -> save()){
					return $this->response->setJsonContent([
						'status' => false,
						'message' => 'Data not found for id',
						'data' => $collaction
					]);	
				}
			}
			return $this->response->setJsonContent([
					'status' => true,
					'message' => 'Activated successfully'
				]);
		}
	}
	
	public function getdailycount(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$input_data = $this->request->getJsonRawBody ();
		$day_id = $input_data -> day_id;
		if($day_id % 5 == 0){
			if($day_id > 5){
				$week2 = ((int)($day_id/5));
				$day = ($week2 * 5);
				$getday = ($day_id - $day);
			} else {
				$getday = 1;
			}
			
			$getchildinfo = $this->modelsManager->createBuilder ()->columns ( array (
			'ChildDayAttendance.day_id as day_id',
			))->from("ChildDayAttendance")
			->where('ChildDayAttendance.day_id <=' . $day_id .' AND ChildDayAttendance.day_id >=' .  $getday)
			->inwhere("ChildDayAttendance.child_id", array($input_data->child_id))
			->groupBy('ChildDayAttendance.day_id')
			->getQuery()->execute ();
		} else {
			if($day_id > 5){
				$week2 = ((int)($day_id/5));
				$day = ($week2 * 5);
				$getday = ($day_id - $day);
			} else {
				$getday = 1;
			}
			$getchildinfo = $this->modelsManager->createBuilder ()->columns ( array (
			'ChildDayAttendance.day_id as day_id',
			))->from("ChildDayAttendance")
			->where('ChildDayAttendance.day_id <=' . $day_id .' AND ChildDayAttendance.day_id >=' .  $getday)
			->inwhere("ChildDayAttendance.child_id", array($input_data->child_id))
			->groupBy('ChildDayAttendance.day_id')
			->getQuery()->execute ();
		}
		$getvaluearray = array();
		if(count($getchildinfo) > 0){
			foreach($getchildinfo as $value){
				$day_id2 = (int)($value->day_id);
				$day_value = $day_id2;
				$day2 = 0;
				if($day_id2 > 5){
					$getday = (int)($day_value/5);
					$day2 = ((int)($day_id2 - ($getday * 5)));
				} else {
					$day2 = $day_id2;
				}
				if($day_id2 <= 20){
					$month = 1;
					$week = ((int)($day_id2/5)+1);
				}
				else{
					$getmonth = ((int)($day_id2/20));
					$month = $getmonth+1;
					$remain = ($day_id2 - ($getmonth*20));
					$week = ((int)($remain/5)+1);
				}
				$getdaycout = $this->modelsManager->createBuilder ()->columns ( array (
						'ChildDayAttendance.day_id as day_id',
					))->from("ChildDayAttendance")
					->inwhere("ChildDayAttendance.child_id", array($input_data->child_id))
					->inwhere("ChildDayAttendance.day_id", array($value->day_id))
					->getQuery()->execute ();
				$data['day_id'] = $value -> day_id;
				$data['daycount'] = count($getdaycout);
				$data['week'] = $week;
				$data['month'] = $month;
				$data['day'] = $day2;
				if($input_data -> day_id == $value -> day_id){
					$data['status'] = false;
				} else {
					$data['status'] = true;
				}
				
				$getvaluearray[] = $data;
			}
		} else {
				$data['day_id'] = $input_data -> day_id;
				$data['daycount'] = 0;
				$data['week'] = 1;
				$data['month'] = 1;
				if($input_data -> day_id > 0){
					$data['day'] = $input_data -> day_id;
				} else {
					$data['day'] = 1;
				}
				$data['status'] = false;
				$getvaluearray[] = $data;
		}
		
		return $this->response->setJsonContent([
			'status' => true,
			'data' => $getvaluearray,
			'getday' => $getday .' And ' . $day2
		]);
	}
	
	public function dailyattendance(){
		
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$input_data = $this->request->getJsonRawBody ();
		$collaction_id = DailyRoutineAttendance::findBynidara_kid_profile_id($input_data->nidara_kid_profile_id);
		if(count($collaction_id) == 0){
			$collaction = new DailyRoutineAttendance();
			$collaction ->id = $this->dailyroutineidgen->getNewId ( "dailyroutineAttendance" );
			$collaction ->task_name = $input_data->task_name;
			$collaction ->start_time = $input_data->start_time;
			$collaction ->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
			$collaction ->attendanceDate = date ('Y-m-d');
			if($collaction->save()){
				return $this->response->setJsonContent([
					'status' => true,
					'Message' => 'Activated successfully'
				]);
			}
			else{
				return $this->response->setJsonContent([
					'status' => false,
					'Message' => 'Data not found for id'
				]);	
			}
		}
		else {
			$gamecheck = 1;
			$contofday = count($collaction_id);
					if($contofday == 1){
						$dayid = $contofday + 1; 
					} else {
						$dayid = $contofday;
					}
			$kidprofile = NidaraKidProfile::findFirstByid ( $input_data->nidara_kid_profile_id );
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
			if($gamecheck == 1){
			/* $collaction_day = DailyRoutineAttendance::findByattendanceDate(date ('Y-m-d')); */
			$collaction_day = $this->modelsManager->createBuilder ()->columns ( array(
				'DailyRoutineAttendance.id as id',
			))->from('DailyRoutineAttendance')
			->inwhere('DailyRoutineAttendance.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
			->inwhere('DailyRoutineAttendance.attendanceDate',array(date ('Y-m-d')))
			->getQuery ()->execute ();
				if(count($collaction_day) == 0){
					$collaction = new DailyRoutineAttendance();
					$collaction ->id = $this->dailyroutineidgen->getNewId ( "dailyroutineAttendance" );
					$collaction ->task_name = $input_data->task_name;
					$collaction ->start_time = $input_data->start_time;
					$collaction ->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$collaction ->attendanceDate = date ('Y-m-d');
					if($collaction->save()){
						return $this->response->setJsonContent([
							'status' => true,
							'Message' => 'Activated successfully'
						]);
					}
					else{
						return $this->response->setJsonContent([
							'status' => false,
							'Message' => 'Data not found for day'
						]);	
					}
				}
				else{
					return $this->response->setJsonContent([
					'status' => false,
					'Message' => 'Data not found'
					]);
					
				}
			} else {
				return $this->response->setJsonContent([
					'status' => false,
					'Message' => 'Game not completed',
					'day_id' => $dayid
					]);
			}
		}
	}
	
	public function routinestatus(){
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$input_data = $this->request->getJsonRawBody ();
		$collaction = new DailyRoutineAttendanceStartEnd();
		$collaction -> child_id = $input_data->child_id;
		$collaction -> session_id = $input_data->session_id;
		$collaction -> start_end = $input_data->start_end;
		$collaction -> status = $input_data->status;
		$collaction -> create_in = date('Y-m-d');
		if(!$collaction -> save()){
			return $this->response->setJsonContent ( [ 
				'status' => false,
				'message' => 'Failed' 
			] );
		}
		else {
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'message' => 'successfully' 
			] );
		}
	}
	
	public function activatenidara() {
		try {
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
			$input_data = $this->request->getJsonRawBody ();
			$nidara_kid_profile_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
			if (empty ( $nidara_kid_profile_id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid input parameter' 
				] );
			}
			if (empty ( $input_data->status )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Please give the status' 
				] );
			}
			$days = DailyRoutine::findFirstBynidara_kid_profile_id ( $nidara_kid_profile_id );
			if ($days) {
				$days->status = $input_data->status;
				$days->save ();
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'Message' => 'Activated successfully' 
				] );
			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			}
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while saving the datas' 
			] );
		}
	}
	
	public function getsessionstartend(){
		$input_data = $this->request->getJsonRawBody ();
		$nidara_kid_profile_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $nidara_kid_profile_id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		}
		else{
			
			$startcount = $this->modelsManager->createBuilder ()->columns ( array(
				'DailyRoutineAttendanceStartEnd.id as id',
				'DailyRoutineAttendanceStartEnd.status as status',
			))->from('DailyRoutineAttendanceStartEnd')
			->inwhere('DailyRoutineAttendanceStartEnd.create_in',array(date('Y-m-d')))
			->inwhere('DailyRoutineAttendanceStartEnd.child_id',array($nidara_kid_profile_id))
			->inwhere('DailyRoutineAttendanceStartEnd.start_end',array(1))
			->getQuery ()->execute ();
			$statue_count = 0;
			$start_count = 0;
			$startarray = array();
			foreach($startcount as $value_start){
				if($value_start-> status == 1){
					$statue_count++;
					$start_count++;
				}
				else{
					$start_count++;
				}
			}
			$endcount = $this->modelsManager->createBuilder ()->columns ( array(
				'DailyRoutineAttendanceStartEnd.id as id',
				'DailyRoutineAttendanceStartEnd.status as status',
			))->from('DailyRoutineAttendanceStartEnd')
			->inwhere('DailyRoutineAttendanceStartEnd.create_in',array(date('Y-m-d')))
			->inwhere('DailyRoutineAttendanceStartEnd.child_id',array($nidara_kid_profile_id))
			->inwhere('DailyRoutineAttendanceStartEnd.start_end',array(2))
			->getQuery ()->execute ();
			$end_statue_count = 0;
			$end_count = 0;
			$endarray = array();
			foreach($endcount as $value_end){
				if($value_end-> status == 1){
					$end_statue_count++;
					$end_count++;
				}
				else{
					$end_count++;
				}
			}
			if(count($startcount) != 0 || count($endcount) != 0){
				$startcount_avg = round(($statue_count/$start_count)*100);
				$endcount_avg = round(($end_statue_count/$end_count)*100);
				$start_data['start_end'] = 1;
				$start_data['avd'] = $startcount_avg;
				if($startcount_avg >= 90){
					$start_data['color'] = '#CED9AF';
				}
				else if($startcount_avg >= 70){
					$start_data['color'] = '#FFEBB8';
				}
				else if($startcount_avg < 70){
					$start_data['color'] = '#F18F91';
				}
				
				$end_data['start_end'] = 2;
				$end_data['avd'] = $endcount_avg;
				if($endcount_avg >= 90){
					$end_data['color'] = '#CED9AF';
				}
				else if($endcount_avg >= 70){
					$end_data['color'] = '#FFEBB8';
				}
				else if($endcount_avg < 70){
					$end_data['color'] = '#F18F91';
				}
				$startarray[] = $start_data;
				$endarray[] = $end_data;
			}
		}
		
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'start' =>  $startarray,
			'end' => $endarray
		] );
		
	}
	
	public function getsessionstartendbyid(){
		$input_data = $this->request->getJsonRawBody ();
		$nidara_kid_profile_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $nidara_kid_profile_id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		}
		else{
			
			$startcount = $this->modelsManager->createBuilder ()->columns ( array(
				'DailyRoutineAttendanceStartEnd.id as id',
				'DailyRoutineAttendanceStartEnd.status as status',
			))->from('DailyRoutineAttendanceStartEnd')
			->inwhere('DailyRoutineAttendanceStartEnd.create_in',array(date('Y-m-d')))
			->inwhere('DailyRoutineAttendanceStartEnd.child_id',array($nidara_kid_profile_id))
			->inwhere('DailyRoutineAttendanceStartEnd.start_end',array($input_data->start_end))
			->getQuery ()->execute ();
			$statue_count = 0;
			$start_count = 0;
			$startarray = array();
			foreach($startcount as $value_start){
				$data_value['id'] = $value_start -> id;
				$data_value['status'] = $value_start -> status;
				if($value_start -> status == 1){
					$data_value['emotion'] = '<span>Happy</span>';
				}
				else{
					$data_value['emotion'] = '<span>&nbsp;Sad &nbsp;&nbsp;</span>';
				}
				$startarray[] = $data_value;
			}
		}
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'data' =>  $startarray,
		] );
	}
}

