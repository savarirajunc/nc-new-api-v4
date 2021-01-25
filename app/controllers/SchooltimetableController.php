<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
class SchooltimetableController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	public function viewall() {
		$timetable = SchoolTimetable::find ();
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
	public function getclass() {
		$timetable = Classes::find ();
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
	public function getsection() {
		$timetable = Sections::find ();
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
	
	public function getncstimetable(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$IP = $_SERVER['REMOTE_ADDR'];
		$computername =  gethostbyaddr($IP);

		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		
		else{
			$gettimetable = $this->modelsManager->createBuilder ()->columns ( array (
				'SchoolTimetable.school_id as school_id',
				'SchoolTimetable.from_time as from_time',
				'SchoolTimetable.to_time as to_time',
			))->from("SchoolTimetable")
			->leftjoin('Schools','SchoolTimetable.school_id = Schools.id')
			->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
			->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
			->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->inwhere('SchoolUserMap.users_id',array($user_id))
			->groupBy('SchoolTimetable.from_time')
			->orderBy('SchoolTimetable.from_time')
			->getQuery()->execute ();
			$timearray = array();
			if(count($gettimetable) <= 0){
				return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Failed"
				] );
			}
			else{
				foreach($gettimetable as $value){
					$gettimetable2 = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolTimetable.school_id as school_id',
						'SchoolTimetable.day_id as day_id',
						'SchoolTimetable.class_id as class_id',
						'SchoolTimetable.section_id as section_id',
						'Classes.class_name as class_name',
						'Sections.section_name as section_name',
						'SchedulerDays.name as name',
					))->from("SchoolTimetable")
					->leftjoin('Schools','SchoolTimetable.school_id = Schools.id')
					->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
					->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
					->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
					->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
					->inwhere('SchoolUserMap.users_id',array($user_id))
					->inwhere('SchoolTimetable.from_time',array($value -> from_time))
					->orderBy('SchoolTimetable.day_id')
					->getQuery()->execute ();
					$gettime['from_time'] = $value-> from_time;
					$gettime['to_time'] = $value-> to_time;
					$gettime['day_table'] = $gettimetable2;
					$timearray[] = $gettime;
				}
			}
		}
		return $this->response->setJsonContent ( [
			"status" => true,
			"data" => $timearray,
			'school_id' => $value-> school_id
		] );
	}
	
	public function getchildinfo(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$user_id = isset ( $input_data->user_id ) ? $input_data->user_id : '';
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else{
			$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'KidSchoolMap.class_id as class_id',
				'KidSchoolMap.sections_id as section_id',
				'SchoolTimetable.school_id as school_id',
				'Classes.class_name as class_name',
				'Sections.section_name as section_name',
				'MakeupSession.child_id as child_id',
				'MakeupSession.id as id',
				'MakeupSession.missed_session as missed_session',
			))->from("MakeupSession")
			->leftjoin('KidSchoolMap','KidSchoolMap.nidara_kid_profile_id = MakeupSession.child_id')
			->leftjoin('Schools','MakeupSession.school_id = Schools.id')
			->leftjoin('SchoolTimetable','SchoolTimetable.school_id = Schools.id')
			->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
			->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
			->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->where('MakeupSession.time <= "'. date('H:i:s') .'" AND MakeupSession.end_time >= "'. date('H:i:s') .'"')
			->inwhere('SchoolUserMap.users_id',array($user_id))
			->inwhere('MakeupSession.set_session',array(date('Y-m-d')))
			->groupBy('MakeupSession.id')
			->getQuery()->execute ();
			/* return $this->response->setJsonContent ( [
					"status" => true,
					"message" => $getvalue
			] ); */
			if(count($getvalue) == 0){
				$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
					'SchoolTimetable.class_id as class_id',
					'SchoolTimetable.section_id as section_id',
					'SchoolTimetable.school_id as school_id',
					'Classes.class_name as class_name',
					'Sections.section_name as section_name',
					'SchoolTimetable.reporting_time as reporting_time',
				))->from("SchoolTimetable")
				->leftjoin('Schools','SchoolTimetable.school_id = Schools.id')
				->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
				->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
				->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
				->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
				->leftjoin('SchoolCoordinatorStatus','Schools.id = SchoolCoordinatorStatus.school_id')
				->where('SchoolTimetable.from_time <= "'. date('H:i:s') .'" AND SchoolTimetable.to_time >= "'. date('H:i:s') .'"')
				->inwhere('SchoolUserMap.users_id',array($user_id))
				->inwhere('SchoolCoordinatorStatus',array('start_session'))
				->inwhere('SchedulerDays.name',array(date('l')))
				->getQuery()->execute ();
				foreach($getvalue as $value){
				$gettimetable = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
					'NidaraSchoolKidProfile.first_name as first_name',
					'NidaraSchoolKidProfile.middle_name as middle_name',
					'NidaraSchoolKidProfile.last_name as last_name',
					'NidaraSchoolKidProfile.date_of_birth as date_of_birth',
					'NidaraSchoolKidProfile.age as age',
					'NidaraSchoolKidProfile.gender as gender',
					'NidaraSchoolKidProfile.height as height',
					'NidaraSchoolKidProfile.weight as weight',
					'NidaraSchoolKidProfile.grade as grade',
					'NidaraSchoolKidProfile.child_photo as child_photo',
					'NidaraSchoolKidProfile.child_avatar as child_avatar',
					'NidaraSchoolKidProfile.created_at as created_at',
					'NidaraSchoolKidProfile.expiry_date as expiry_date',
					'NidaraSchoolKidProfile.created_by as created_by',
					'NidaraSchoolKidProfile.modified_at as modified_at',
					'NidaraSchoolKidProfile.status as status',
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
				foreach($gettimetable as $value2){
					$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolDailyAttendance.id as id',
						'SchoolDailyAttendance.status as status',
					))->from("SchoolDailyAttendance")
					->inwhere('SchoolDailyAttendance.child_id',array($value2 -> id))
					->inwhere('SchoolDailyAttendance.create_at',array(date('Y-m-d')))
					->getQuery()->execute ();
					foreach($collection2 as $collet){
						
					}
					if($collet-> status == 1){
						$child_data['present'] = true;
					}
					else{
						$child_data['present'] = false;
					}
					$seatnum = $this->modelsManager->createBuilder ()->columns ( array (
						'SeatMaster.seat_no as seat_no',
					))->from("SchoolLabSeat")
					->leftjoin('SeatMaster','SchoolLabSeat.seat_no = SeatMaster.id')
					->inwhere('SchoolLabSeat.kid_id',array($value2 -> id))
					->getQuery()->execute ();
					foreach($seatnum as $seatnum_value){
						
					}
					$child_data['seat_no'] = $seatnum_value -> seat_no;
					$child_data['id'] = $value2 -> id;
					$child_data['ncs_id'] = $value2 -> ncs_id;
					$child_data['first_name'] = $value2 -> first_name;
					$child_data['last_name'] = $value2 -> last_name;
					$child_data['gender'] = $value2 -> gender;
					$child_data['date_of_birth'] = $value2 -> date_of_birth;
					$child_data['child_photo'] = $value2 -> child_photo;
					$childinfoarray[] = $child_data;
				}
				}
				return $this->response->setJsonContent ( [
					"status" => true,
					"data" => $childinfoarray,
					'class' => $value -> class_name,
					'section' => $value -> section_name,
					'reporting_time' => $value -> reporting_time,
					'time' => date('H:i:s'),
					'day' => date('l'),
					'school_id' => $value -> school_id
				] );
			}
			else{
				$childarray = array();
				foreach($getvalue as $value){
				$gettimetable = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
					'NidaraSchoolKidProfile.first_name as first_name',
					'NidaraSchoolKidProfile.middle_name as middle_name',
					'NidaraSchoolKidProfile.last_name as last_name',
					'NidaraSchoolKidProfile.date_of_birth as date_of_birth',
					'NidaraSchoolKidProfile.age as age',
					'NidaraSchoolKidProfile.gender as gender',
					'NidaraSchoolKidProfile.height as height',
					'NidaraSchoolKidProfile.weight as weight',
					'NidaraSchoolKidProfile.grade as grade',
					'NidaraSchoolKidProfile.child_photo as child_photo',
					'NidaraSchoolKidProfile.child_avatar as child_avatar',
					'NidaraSchoolKidProfile.created_at as created_at',
					'NidaraSchoolKidProfile.expiry_date as expiry_date',
					'NidaraSchoolKidProfile.created_by as created_by',
					'NidaraSchoolKidProfile.modified_at as modified_at',
					'NidaraSchoolKidProfile.status as status',
				))->from("KidSchoolMap")
				->leftjoin('NidaraSchoolKidProfile','KidSchoolMap.nidara_kid_profile_id = NidaraSchoolKidProfile.id')
				->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->leftjoin('SchoolDailyAttendance','SchoolDailyAttendance.school_id = Schools.id')
				->inwhere('KidSchoolMap.nidara_kid_profile_id',array($value -> child_id))
				->inwhere('KidSchoolMap.schools_id',array($value -> school_id))
				->inwhere('KidSchoolMap.class_id',array($value -> class_id))
				->inwhere('KidSchoolMap.sections_id',array($value -> section_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
				$childinfoarray = array();
				foreach($gettimetable as $value2){
					$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
						'SchoolDailyAttendance.id as id',
						'SchoolDailyAttendance.status as status',
					))->from("SchoolDailyAttendance")
					->inwhere('SchoolDailyAttendance.child_id',array($value2 -> id))
					->inwhere('SchoolDailyAttendance.create_at',array($value -> missed_session))
					->getQuery()->execute ();
					foreach($collection2 as $collet){
						
					}
					if($collet-> status == 1){
						$child_data['present'] = true;
					}
					else{
						$child_data['present'] = false;
					}
					$seatnum = $this->modelsManager->createBuilder ()->columns ( array (
						'SeatMaster.seat_no as seat_no',
					))->from("SchoolLabSeat")
					->leftjoin('SeatMaster','SchoolLabSeat.seat_no = SeatMaster.id')
					->inwhere('SchoolLabSeat.kid_id',array($value2 -> id))
					->getQuery()->execute ();
					foreach($seatnum as $seatnum_value){
						
					}
					
					$childinfoarray[] = $child_data;
				}
				$child_data['seat_no'] = $seatnum_value -> seat_no;
				$child_data['id'] = $value2 -> id;
				$child_data['ncs_id'] = $value2 -> ncs_id;
				$child_data['first_name'] = $value2 -> first_name;
				$child_data['last_name'] = $value2 -> last_name;
				$child_data['gender'] = $value2 -> gender;
				$child_data['date_of_birth'] = $value2 -> date_of_birth;
				$child_data['child_photo'] = $value2 -> child_photo;
				$child_data['missed_session'] = $value -> missed_session;
				// $datavalue['child-info'] = $childinfoarray;
				$childarray[] = $child_data;
			}
			return $this->response->setJsonContent ( [
					"status" => true,
					"data" => $childarray,
					'school_id' => $value -> school_id
				]);
			}
			
		}
	}
	
	public function getdailyseat(){
		$input_data = $this->request->getJsonRawBody ();
		$access_key = isset ( $input_data->access_key ) ? $input_data->access_key : '';
		$ip_address = isset ( $input_data->ip_address ) ? $input_data->ip_address : '';
		if(empty($access_key)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give school access_key"
			] );
		}
		else{
			$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'KidSchoolMap.class_id as class_id',
				'KidSchoolMap.sections_id as section_id',
				'SchoolTimetable.school_id as school_id',
				'Classes.class_name as class_name',
				'Sections.section_name as section_name',
				'MakeupSession.child_id as child_id',
				'MakeupSession.id as id',
				'MakeupSession.missed_session as missed_session',
			))->from("MakeupSession")
			->leftjoin('KidSchoolMap','KidSchoolMap.nidara_kid_profile_id = MakeupSession.child_id')
			->leftjoin('Schools','MakeupSession.school_id = Schools.id')
			->leftjoin('SchoolTimetable','SchoolTimetable.school_id = Schools.id')
			->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
			->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
			->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->where('MakeupSession.time <= "'. date('H:i:s') .'" AND MakeupSession.end_time >= "'. date('H:i:s') .'"')
			->inwhere('Schools.access_key',array($access_key))
			->inwhere('MakeupSession.set_session',array(date('Y-m-d')))
			->groupBy('MakeupSession.id')
			->getQuery()->execute ();
			
			/* $getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'SchoolTimetable.class_id as class_id',
				'SchoolTimetable.section_id as section_id',
				'SchoolTimetable.school_id as school_id',
				'Classes.class_name as class_name',
				'Sections.section_name as section_name',
				'MakeupSession.child_id as child_id',
				'MakeupSession.seat_no as seat_no',
			))->from("MakeupSession")
			->leftjoin('Schools','MakeupSession.school_id = Schools.id')
			->leftjoin('SchoolTimetable','SchoolTimetable.school_id = Schools.id')
			->leftjoin('SchedulerDays','SchoolTimetable.day_id = SchedulerDays.id')
			->leftjoin('Classes','SchoolTimetable.class_id = Classes.id')
			->leftjoin('Sections','SchoolTimetable.section_id = Sections.id')
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->where('MakeupSession.time <= "'. date('H:i:s') .'" AND MakeupSession.end_time >= "'. date('H:i:s') .'"')
			->inwhere('Schools.access_key',array($access_key))
			->inwhere('MakeupSession.set_session',array(date('Y-m-d')))
			->groupBy('MakeupSession.child_id')
			->getQuery()->execute (); */
			if(count($getvalue) == 0){
				
				$getvalue2 = $this->modelsManager->createBuilder ()->columns ( array (
				'SchoolTimetable.class_id as class_id',
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
			->where('SchoolTimetable.from_time <= "'. date('H:i:s') .'" AND SchoolTimetable.to_time >= "'. date('H:i:s') .'"')
			->inwhere('Schools.access_key',array($access_key))
			->inwhere('SchedulerDays.name',array(date('l')))
			->getQuery()->execute ();
			foreach($getvalue2 as $value){
				$gettimetable = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
					'NidaraSchoolKidProfile.first_name as first_name',
					'NidaraSchoolKidProfile.middle_name as middle_name',
					'NidaraSchoolKidProfile.last_name as last_name',
					'NidaraSchoolKidProfile.date_of_birth as date_of_birth',
					'NidaraSchoolKidProfile.age as age',
					'NidaraSchoolKidProfile.gender as gender',
					'NidaraSchoolKidProfile.height as height',
					'NidaraSchoolKidProfile.weight as weight',
					'NidaraSchoolKidProfile.grade as grade',
					'NidaraSchoolKidProfile.child_photo as child_photo',
					'NidaraSchoolKidProfile.child_avatar as child_avatar',
					'NidaraSchoolKidProfile.created_at as created_at',
					'NidaraSchoolKidProfile.expiry_date as expiry_date',
					'NidaraSchoolKidProfile.created_by as created_by',
					'NidaraSchoolKidProfile.modified_at as modified_at',
					'NidaraSchoolKidProfile.status as status'
				))->from("SchoolLabSeat")
				->leftjoin('SeatMaster','SchoolLabSeat.seat_no = SeatMaster.id')
				->leftjoin('Schools','SeatMaster.school_id = Schools.id')
				->leftjoin('KidSchoolMap','SchoolLabSeat.kid_id = KidSchoolMap.nidara_kid_profile_id')
				->leftjoin('NidaraSchoolKidProfile','KidSchoolMap.nidara_kid_profile_id = NidaraSchoolKidProfile.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->inwhere('KidSchoolMap.schools_id',array($value -> school_id))
				->inwhere('SeatMaster.ip_address',array($ip_address))
				->inwhere('KidSchoolMap.class_id',array($value -> class_id))
				->inwhere('KidSchoolMap.sections_id',array($value -> section_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
			}
			}
			else{
				foreach($getvalue as $value){
				$gettimetable = $this->modelsManager->createBuilder ()->columns ( array (
					'NidaraSchoolKidProfile.id as id',
					'NidaraSchoolKidProfile.ncs_id as ncs_id',
					'NidaraSchoolKidProfile.first_name as first_name',
					'NidaraSchoolKidProfile.middle_name as middle_name',
					'NidaraSchoolKidProfile.last_name as last_name',
					'NidaraSchoolKidProfile.date_of_birth as date_of_birth',
					'NidaraSchoolKidProfile.age as age',
					'NidaraSchoolKidProfile.gender as gender',
					'NidaraSchoolKidProfile.height as height',
					'NidaraSchoolKidProfile.weight as weight',
					'NidaraSchoolKidProfile.grade as grade',
					'NidaraSchoolKidProfile.child_photo as child_photo',
					'NidaraSchoolKidProfile.child_avatar as child_avatar',
					'NidaraSchoolKidProfile.created_at as created_at',
					'NidaraSchoolKidProfile.expiry_date as expiry_date',
					'NidaraSchoolKidProfile.created_by as created_by',
					'NidaraSchoolKidProfile.modified_at as modified_at',
					'NidaraSchoolKidProfile.status as status'
				))->from("SchoolLabSeat")
				->leftjoin('SeatMaster','SchoolLabSeat.seat_no = SeatMaster.id')
				->leftjoin('Schools','SeatMaster.school_id = Schools.id')
				->leftjoin('KidSchoolMap','SchoolLabSeat.kid_id = KidSchoolMap.nidara_kid_profile_id')
				->leftjoin('NidaraSchoolKidProfile','KidSchoolMap.nidara_kid_profile_id = NidaraSchoolKidProfile.id')
				->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
				->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
				->inwhere('KidSchoolMap.schools_id',array($value -> school_id))
				->inwhere('SeatMaster.ip_address',array($ip_address))
				->inwhere('KidSchoolMap.class_id',array($value -> class_id))
				->inwhere('KidSchoolMap.sections_id',array($value -> section_id))
				->groupBy('NidaraSchoolKidProfile.id')
				->getQuery()->execute ();
			}
			}
			return $this->response->setJsonContent ( [
			"status" => true,
			"data" => $gettimetable,
			'class_id' => $value -> class_id,
			'myIP' => $ip_address,
		] );
		}
	}
	
	public function setschoolattendance(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$school_id = isset ( $input_data->school_id ) ? $input_data->school_id : '';
		
		if(empty($school_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user school_id"
			] );
		}
		else{
			$getchilinfo = $input_data -> childinfo;
			foreach($getchilinfo as $value){
				$create_at = $value -> missed_session;
				if(empty($create_at)){
					$create_at = date('Y-m-d');
				}
				$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'SchoolDailyAttendance.id as id'
				))->from("SchoolDailyAttendance")
				->inwhere('SchoolDailyAttendance.child_id',array($value -> id))
				->inwhere('SchoolDailyAttendance.create_at',array($create_at))
				->getQuery()->execute ();
				$collection3 = $this->modelsManager->createBuilder ()->columns ( array (
					'SchoolAttendanceAbsentList.id as ids'
				))->from("SchoolAttendanceAbsentList")
				->inwhere('SchoolAttendanceAbsentList.child_id',array($value -> id))
				->inwhere('SchoolAttendanceAbsentList.create_at',array($create_at))
				->getQuery()->execute ();
				if(count($collection2) == 0){
					$collection = new SchoolDailyAttendance();
					$collection -> school_id = $school_id;
					$collection -> child_id = $value -> id;
					if($value -> present == true){
					$collection -> status = 1;
					}
					else{
						$collection -> status = 0;
						$absentlist = new SchoolAttendanceAbsentList();
						$absentlist -> school_id = $school_id;
						$absentlist -> child_id = $value -> id;
						$absentlist -> create_at = date('Y-m-d');
						if(!$absentlist -> save()){
							return $this->response->setJsonContent ( [
								"status" => false,
								"message" => $absentlist
							] );
						}
					}
					$collection -> create_at = date('Y-m-d');
				}
				else{
					foreach($collection2 as $value2){
						
					}
					foreach($collection3 as $value3){
							
					}
					$collection = SchoolDailyAttendance::findFirstByid($value2->id);
					$collection -> school_id = $school_id;
					$collection -> child_id = $value -> id;
					if($value -> present == true){
						$collection -> status = 1;
						if(count($collection3) > 0){
							$absentlist2 = SchoolAttendanceAbsentList::findFirstByid($value3->ids);
							if($absentlist2 -> delete ()){
								
							}
						}
					}
					else{
						$collection -> status = 0;
						if(count($collection3) == 0){
							$absentlist = new SchoolAttendanceAbsentList();
							$absentlist -> school_id = $school_id;
							$absentlist -> child_id = $value -> id;
							$absentlist -> create_at = date('Y-m-d');
							if(!$absentlist -> save()){
								return $this->response->setJsonContent ( [
									"status" => false,
									"message" => $absentlist
								] );
							}
						}
					}
					$collection -> create_at = date('Y-m-d');
				}
				if(!$collection->save()){
					return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Failed"
					] );
				}
			}
			return $this->response->setJsonContent ( [
				"status" => true,
				"message" => "Please ask the children to start the session",
				
			] );
		}
	}
	
	public function checkattendance(){
		$input_data = $this->request->getJsonRawBody ();
		$child_id = isset ( $input_data->child_id ) ? $input_data->child_id : '';
		if(empty($child_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give child_id"
			] );
		}
		else{
			$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'SchoolDailyAttendance.id as id'
			))->from("SchoolDailyAttendance")
			->inwhere('SchoolDailyAttendance.child_id',array($child_id))
			->inwhere('SchoolDailyAttendance.status',array(1))
			->inwhere('SchoolDailyAttendance.create_at',array(date('Y-m-d')))
			->getQuery()->execute ();
			if(count($collection2) == 0){
				return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Sesstion not ready"
			] );
			}
			else{
				$collection = $this->modelsManager->createBuilder ()->columns ( array (
					'COUNT(SchoolDailyAttendance.id) as day'
				))->from("SchoolDailyAttendance")
				->inwhere('SchoolDailyAttendance.child_id',array($child_id))
				->getQuery()->execute ();
				return $this->response->setJsonContent ( [
					"status" => true,
					"data" => $collection,	
				] );
			}
		}
	}
}