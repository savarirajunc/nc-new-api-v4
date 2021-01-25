<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class DailyschedulerController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$daily_scheduler = DailySchedulerDaysMap::find ();
		if ($daily_scheduler) :
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$daily_scheduler 
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
			$validation->add ( 'from_time', new PresenceOf ( [ 
					'message' => 'From time is required' 
			] ) );
			$validation->add ( 'to_time', new PresenceOf ( [ 
					'message' => 'To time is required' 
			] ) );
			$validation->add ( 'reminder', new PresenceOf ( [ 
					'message' => 'reminder is required' 
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
				$scheduler = $input_data->scheduler_days_id;
				if (empty ( $scheduler )) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please select the days" 
					] );
				}
				foreach ( $scheduler as $value ) {
					$shedulerday = SchedulerDays::findFirstByname ( $value->day );
					if(empty($shedulerday)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Please enter the valid scheduler day" 
					] );
					}
				}
				$scheduler_days = DailyScheduler::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
				if (empty ( $scheduler_days )) {
					$scheduler_days = new DailyScheduler ();
					$scheduler_days->id = $this->dailyscheduleridgen->getNewId ( "dailyschedule" );
					$scheduler_days->status = 1;
				}
				if(isset($input_data->status)){
					$scheduler_days->status = $input_data->status;
				}
				$scheduler_days->from_time = $input_data->from_time;
				$scheduler_days->to_time = $input_data->to_time;
				$scheduler_days->reminder = $input_data->reminder;
				$scheduler_days->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
				$scheduler_days->save ();
				foreach ( $scheduler as $value ) {
					$shedulerday = SchedulerDays::findFirstByname ( $value->day );
					$shedulerdaysexist = $this->modelsManager->createBuilder ()->columns ( array (
							'DailySchedulerDaysMap.id as schedule_id' 
					) )->from ( 'DailySchedulerDaysMap' )->inwhere ( "daily_scheduler_id", array (
							$scheduler_days->id 
					) )->inwhere ( "scheduler_days_id", array (
							$shedulerday->id 
					) )->getQuery ()->getSingleResult ();
					if (! empty ( $shedulerdaysexist )) {
						if ($value->option != 1) {
							$scheduleexist = DailySchedulerDaysMap::findFirstByid ( $shedulerdaysexist->schedule_id );
							$scheduleexist->delete ();
						}
					} else {
						if ($value->option == 1) {
							$daily_scheduler = new DailySchedulerDaysMap ();
							$daily_scheduler->id = $this->dailyscheduleridgen->getNewId ( "dailyschedulemap" );
							$daily_scheduler->daily_scheduler_id = $scheduler_days->id;
							$daily_scheduler->scheduler_days_id = $shedulerday->id;
							if (! $daily_scheduler->save ()) {
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
						'message' => 'Daily scheduler saved successfully' 
				] );
			endif;
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while saving the datas' 
			] );
		}
	}
	
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
		$nidara_kid_profile_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $nidara_kid_profile_id )) :
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			]);
		 else :
			$days = DailyScheduler::findFirstBynidara_kid_profile_id($nidara_kid_profile_id);
			if ($days) {
				$dailyscheduler['from_time']=$days->from_time;
				$dailyscheduler['to_time']=$days->to_time;	
				$dailyscheduler['reminder']=$days->reminder;	
				$dailyscheduler['nidara_kid_profile_id']=$days->nidara_kid_profile_id;
			}else{
				$dailyscheduler['from_time']="";
				$dailyscheduler['to_time']="";	
				$dailyscheduler['taks']="";	
				$dailyscheduler['reminder']="";	
			}		
				$shedulerdays=SchedulerDays::find();
				$dailyschedulerarray=array();
					foreach ( $shedulerdays as $shedulerday ) {
						$shedulerdaysexist="";
					    if(isset($days->id) && !empty($days->id)){
						$shedulerdaysexist = $this->modelsManager->createBuilder ()->columns ( array (
								'DailySchedulerDaysMap.id as schedule_id',
						) )->from ( 'DailySchedulerDaysMap' )->inwhere ( "daily_scheduler_id", array (
								$days->id 
						) )->inwhere ( "scheduler_days_id", array (
								$shedulerday->id 
						) )->getQuery ()->getSingleResult ();
					     }
						$shedulerdayarray ['day'] = $shedulerday->name;
						if (! empty ( $shedulerdaysexist )) {
							$shedulerdayarray ['option'] = 1;
						} else {
							$shedulerdayarray['option'] = 0;
						}
						$dailyschedulerarray[]=$shedulerdayarray;
					}
			        $dailyscheduler['scheduler_days_id']= $dailyschedulerarray;
				return $this->response->setJsonContent ([ 
					'status' => true,
					'data' => $dailyscheduler
			]);
			
		endif;
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Error while getting the datas'
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
			$days = DailyScheduler::findFirstBynidara_kid_profile_id ( $nidara_kid_profile_id );
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
}

