<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Date;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Aws\Credentials\CredentialProvider;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
require BASE_PATH.'/vendor/autoload.php';
class NidaraschoolkidprofileController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$subject = NidaraSchoolKidProfile::find ();
		if ($subject) :
			return $this->response->setJsonContent ( [ 
					'status' => 'true',
					'data' => $subject 
			] );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => 'false',
					'Message' => 'Faield' 
			] );
		endif;
	}
	
	/*
	 * Fetch Record from database based on ID :-
	 */
	
	public function getchildlistbyschool(){
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
		$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'SchoolDailyAttendance.create_at as create_at',
			))->from("SchoolDailyAttendance")
			->leftjoin('NidaraSchoolKidProfile','SchoolDailyAttendance.child_id = NidaraSchoolKidProfile.id')
			->leftjoin('KidSchoolMap','KidSchoolMap.nidara_kid_profile_id = NidaraSchoolKidProfile.id')
			->leftjoin('Schools','KidSchoolMap.schools_id = Schools.id')
			->leftjoin('Classes','KidSchoolMap.class_id = Classes.id')
			->leftjoin('Sections','KidSchoolMap.sections_id = Sections.id')
			->leftjoin('SchoolDailyAttendance','SchoolDailyAttendance.school_id = Schools.id')
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->inwhere('SchoolUserMap.users_id',array($user_id))
			->inwhere('KidSchoolMap.class_id',array($class_id))
			->inwhere('KidSchoolMap.sections_id',array($section_id))
			->inwhere('SchoolDailyAttendance.status',array(1))
			->getQuery()->execute ();
			return $this->response->setJsonContent ( [
				"status" => true,
				'data' => $collection2
			]);
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
			->leftjoin('SchoolUserMap','Schools.id = SchoolUserMap.schools_id')
			->inwhere('SchoolUserMap.users_id',array($user_id))
			->inwhere('KidSchoolMap.class_id',array($class_id))
			->inwhere('KidSchoolMap.sections_id',array($section_id))
			->groupBy('NidaraSchoolKidProfile.id')
			->getQuery()->execute ();
			$childinfoarray = array();
			foreach($getgameanser as $value){
				$seatnum = $this->modelsManager->createBuilder ()->columns ( array (
						'SeatMaster.seat_no as seat_no',
					))->from("SeatMaster")
					->leftjoin('SchoolLabSeat','SchoolLabSeat.seat_no = SeatMaster.id')
					->inwhere('SchoolLabSeat.kid_id',array($value -> id))
					->getQuery()->execute ();
					foreach($seatnum as $seatnum_value){
						
					}
				$child_data['seat_no'] = $seatnum_value -> seat_no;
				$child_data['id'] = $value -> id;
				$child_data['ncs_id'] = $value -> ncs_id;
				$child_data['first_name'] = $value -> first_name;
				$child_data['last_name'] = $value -> last_name;
				$child_data['gender'] = $value -> gender;
				$child_data['date_of_birth'] = $value -> date_of_birth;
				$child_data['hours'] = '';
				$child_data['minutes'] = '';
				$child_data['message'] = '';
				$child_data['class'] = '';
				$child_data['child_photo'] = $value -> child_photo;
				$child_data['from_date'] = date('d');
				$child_data['from_month'] = date('m');
				$child_data['from_years'] = date('Y');
				$child_data['to_date'] = date('d');
				$child_data['to_month'] = date('m');
				$child_data['to_years'] = date('Y');
				$childinfoarray[] = $child_data;
			}
		}
		return $this->response->setJsonContent ( [
			"status" => true,
			'data' => $childinfoarray
		]);
	}
}
