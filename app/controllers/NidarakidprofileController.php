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
class NidarakidprofileController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$subject = NidaraKidProfile::find ();
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
	public function getbyid() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		 else :
			$kidprofile = NidaraKidProfile::findFirstByid ( $id )->toArray();
			if ($kidprofile) :
				$guided_learning = KidGuidedLearningMap::findFirstBynidara_kid_profile_id ($id);
				if (! empty ( $guided_learning )) {
					$kidprofile['guided_learning_id'] = $guided_learning->id;
				}
				

				return $this->response->setJsonContent ( [ 
						'status' => true,
						'data' =>$kidprofile
				] );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'You have not entered any information' 
				] );
			endif;
		endif;
	}
	
	/**
	 * This function using to create NidaraKidProfile information
	 */
	public function save() {
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
			$user_info=$this->tokenvalidate->getuserinfo ( $headers['Token'], $baseurl );
			$userid = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
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
		$validation->add ( 'first_name', new PresenceOf ( [ 
				'message' => 'First Name is required' 
		] ) );
		/* $validation->add ( 'first_name', new Alpha ( [ 
				'message' => 'First name must contain only letters' 
		] ) ); */
		$validation->add ( 'first_name', new StringLength ( [ 
				'max' => 20,
				'min' => 2,
				'messageMaximum' => 'First Name is maximum 20',
				'messageMinimum' => 'First Name is minimum 2' 
		] ) );
		if (! empty ( $input_data->middle_name )) {
				$validation->add ( 'middle_name', new Alpha ( [ 
				'message' => 'Middle name must contain only letters' 
		] ) );
		}
		$validation->add ( 'last_name', new PresenceOf ( [ 
				'message' => 'Last Name is required' 
		] ) );
		/* $validation->add ( 'last_name', new Alpha ( [ 
				'message' => 'Last name must contain only letters' 
		] ) ); */
		$validation->add ( 'last_name', new StringLength ( [ 
				'max' => 20,
				'min' => 2,
				'messageMaximum' => 'Last Name is maximum 20',
				'messageMinimum' => 'Last Name is minimum 2' 
		] ) );
		$validation->add ( 'date_of_birth', new PresenceOf ( [ 
				'message' => 'Date of birth is required' 
		] ) );
		$validation->add ( 'date_of_birth', new Date ( [ 
				'format' => 'Y-m-d',
				'message' => 'The date is invalid' 
		] ) );
		
		if(!empty($input_data->age)){
		$validation->add ( 'age', new Digit ( [ 
				'message' => 'Please enter a valid Age' 
		] ) );
		}
		$validation->add ( 'gender', new PresenceOf ( [ 
				'message' => 'Gender is required' 
		] ) );
		$validation->add ( 'grade', new PresenceOf ( [ 
				'message' => 'Grade is required' 
		] ) );
		
		$genders = array (
					"male",
					"female" 
			);
		if (! in_array ( $input_data->gender, $genders )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Please give the valid gender' 
				] );
		}
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => $message->getMessage ()
				] );
			endforeach
			;
		 else :
			if (! empty ( $input_data->parent_mobile )) {
					$mobile = Users::findFirstBymobile ( $input_data->parent_mobile );
					if (empty ( $mobile )) {
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Please enter valid parent mobile number to add the kid information' 
						] );
					}
				}
			if (! empty ( $input_data->board_of_education )) {
					$board = BoardOfEducation::findFirstByid ( $input_data->board_of_education );
					if (empty ( $board )) {
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Board of education is invalid' 
						] );
					}
				}
				if (! empty ( $input_data->relationship_to_child )) {
					$relationship = Relationships::findFirstByid ( $input_data->relationship_to_child );
					if (empty ( $relationship )) {
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Relationship is invalid' 
						] );
					}
				}
				if (! empty ( $input_data->grade )) {
					$grade = Grade::findFirstByid ( $input_data->grade );
					if (empty ( $grade )) {
						return $this->response->setJsonContent ( [
								'status' => false,
								'message' => 'Grade is invalid'
						] );
					}
				}
			$kidprofile = NidaraKidProfile::findFirstByid ( $id );
			if (empty($kidprofile)) {
				$kidprofile = new NidaraKidProfile ();
				$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
			}
			$kidprofile->first_name = $input_data->first_name;
			if (! empty ( $input_data->middle_name )) {
				$kidprofile->middle_name = $input_data->middle_name;
			}
			$kidprofile->last_name = $input_data->last_name;
			$kidprofile->date_of_birth = $input_data->date_of_birth;
			if(!empty($input_data->age)){
			$kidprofile->age = $input_data->age;
			}
			$kidprofile->gender = $input_data->gender;
			$kidprofile->height = $input_data->height;
			$kidprofile->weight = $input_data->weight;
			$kidprofile->grade = $input_data->grade;
			$kidprofile->child_photo = $input_data->child_photo;
			// $kidprofile_create->child_avatar = $input_data->child_avatar;
			$kidprofile->created_at = date ( 'Y-m-d H:i:s' );
			if(empty($input_data->created_by)){
				$kidprofile->created_by = $userid;
			} else {
				$kidprofile->created_by = $input_data->created_by;
			}
			$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
			$kidprofile->status = 1;
			$kidprofile->cancel_subscription = 1;
			if(!empty($input_data->board_of_education)){
			$kidprofile->board_of_education = $input_data->board_of_education;
			}
			if(!empty($input_data->relationship_to_child)){
			$kidprofile->relationship_to_child = $input_data->relationship_to_child;
		        }
			if (empty ( $input_data->child_photo )) {
				$gender = $input_data->gender;
				if ($gender == 'male') {
					$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_male.png';
				} else {
					$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_female.png';
				}
			} else {
				$kidprofile->child_photo = $input_data->child_photo;
			}
			if ($kidprofile->save ()) {
				if (! empty ( $input_data->guided_learning_id )) {
					$kid_guide=KidGuidedLearningMap::findFirstBynidara_kid_profile_id($kidprofile->id);
					if(empty($kid_guide)){
					$kid_guide = new KidGuidedLearningMap ();
					$kid_guide->id = $this->kididgen->getNewId ( "nidarakidlearningmap" );
					$kid_guide->nidara_kid_profile_id = $kidprofile->id;
					$kid_guide->guided_learning_id = $input_data->grade;
					$kid_guide->save ();
					}
						
				}
					$parentsmap=KidParentsMap::findFirstBynidara_kid_profile_id($kidprofile->id);
					if(empty($parentsmap)){
						$parentsmap = new KidParentsMap ();
						$parentsmap->id = $this->kididgen->getNewId ( "kidparentsmap" );
						$parentsmap->nidara_kid_profile_id = $kidprofile->id;
						$parentsmap->users_id = $userid;
						$parentsmap->save();
					}
				return $this->response->setJsonContent ([ 
						'status' => true,
						'message' => 'Child details saved successfully',
						'kid_id' => $kidprofile->id 
				]);
			} else {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Cannot add kid' 
					] );
				}
		

		endif;
	}
	
	
	
	public function childdeactive(){
		
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
		$kid_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $kid_id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Nidara kid profile id is empty' 
			] );
		}
		else{
			$token_validate = $this->tokenvalidate->usercheckbypassword ( $headers ['Token'], $baseurl,$input_data->password );
			$username = $token_validate->username;
			$user = Users::findFirstByemail ($username );
			if(empty($user)){
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'invalid user and password' 
				] );
			}
			else{
				$user_id = $user->id;
				$account = new ChildDeactive ();
				$account->id = $this->parentsidgen->getNewId ( "child-deactivation" );
				$account->elaboration = $input_data->elaboration;
				$account->users_id = $user->id;
				$account->child_id = $kid_id;
				$account->why_are_you_leaving_id = $input_data->why_are_you_leaving_id;
				$account->save();
				$kidifo = $this->modelsManager->createBuilder ()->columns ( array (
					'COUNT(KidParentsMap.id) as child_count',
				))->from('KidParentsMap')
				->leftjoin('NidaraKidProfile','KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
				->inwhere("KidParentsMap.users_id",array($user_id))
				->inwhere("NidaraKidProfile.status",array(1))
				->getQuery ()->execute ();
				foreach($kidifo as $value){
					
				}
				if($value->child_count == 1){
					$childinfo = NidaraKidProfile::findFirstByid($kid_id);
					$childinfo->first_name = md5($childinfo->first_name);
					$childinfo->middle_name = md5($childinfo->middle_name);
					$childinfo->last_name = md5($childinfo->last_name);
					$childinfo->grade = md5($childinfo->grade);
					$childinfo->child_photo = md5($childinfo->child_photo);
					$childinfo->child_avatar = md5($childinfo->child_avatar);
					$childinfo->status = 2;
					$childinfo->save();
					$userinfo = Users::findFirstByid($user_id);
					if(!empty($userinfo)){
						$userinfo->parent_type = md5($userinfo->parent_type);
						$userinfo->user_type = md5($userinfo->user_type);
						$userinfo->first_name = md5($userinfo->first_name);
						$userinfo->last_name = md5($userinfo->last_name);
						$userinfo->mobile = md5($userinfo->mobile);
						$userinfo->occupation = md5($userinfo->occupation);
						$userinfo->company_name = md5($userinfo->company_name);
						$userinfo->status = 4;
						if($userinfo->save()){
							return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Your account is requested for deactivation in parent' 
							] );
						}
						else{
							return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Deactivation Failed' 
							] );
						}
					}
				}
				else{
					$childinfo = NidaraKidProfile::findFirstByid($kid_id);
					$childinfo->first_name = md5($childinfo->first_name);
					$childinfo->middle_name = md5($childinfo->middle_name);
					$childinfo->last_name = md5($childinfo->last_name);
					$childinfo->grade = md5($childinfo->grade);
					$childinfo->child_photo = md5($childinfo->child_photo);
					$childinfo->child_avatar = md5($childinfo->child_avatar);
					$childinfo->status = 2;
					$childinfo->save();
					if($childinfo->save()){
						return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'Your account is requested for deactivation' 
						] );
					}
					else{
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Deactivation Failed' 
						] );
					}
				}
			}
		}
		
		
	}
	
		public function getbaselineinfo(){
		$input_data = $this->request->getJsonRawBody();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$childhealth2 = ChildHealthInfo::findFirstBychild_id($input_data -> child_id);
		$childinfo = NidaraKidProfile::findFirstByid($input_data -> child_id);
		$dateOfBirth = $childinfo -> date_of_birth;
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$year = $diff->format('%y');
		if($year > 6){
			$age = 6 * 12;
		} else {
			$age = $year * 12;
		}
		$question = $this->modelsManager->createBuilder ()->columns ( array (
			'CoreChildHealthQuestion.question_type as question_type',
		)) -> from('CoreChildHealthQuestion')
		->groupBy('CoreChildHealthQuestion.question_type')
		->getQuery()->execute ();
		$learning = $this->modelsManager->createBuilder ()->columns ( array (
			'CoreChildLearningQuestion.id as id',
			'CoreChildLearningQuestion.month as month',
			'CoreChildLearningQuestion.question as question',
			'CoreChildLearningQuestion.question_type as question_type',
			'CoreChildLearningQuestion.create_at as create_at',
		)) -> from('CoreChildLearningQuestion')
		->groupBy('CoreChildLearningQuestion.question_type')
		->getQuery()->execute ();
		$learningnc = CoreChildLearnNcQuestion::find();
		$vision = CoreChildSensoryScreeningVisionQuestion::find();
		$hearing = CoreChildSensoryScreeningHearingQuestion::find();
		$oral_hygiene = CoreChildSensoryScreeningOralHygieneQuestion::find();
		$childinfoarray = array();
		$healthcatarray = array();
		$learningarray = array();
		$nclearnarray = array();
		$visionarray = array();
		$hearingarray = array();
		$oral_hygienearray = array();
		foreach($question as $healthvalue){
			if($healthvalue -> question_type == 4){
				$question2 = $this->modelsManager->createBuilder ()->columns ( array (
					'CoreChildHealthQuestion.id as id',
					'CoreChildHealthQuestion.month as month',
					'CoreChildHealthQuestion.question as question',
					'CoreChildHealthQuestion.question_type as question_type',
					'CoreChildHealthQuestion.create_at as create_at',
				)) -> from('CoreChildHealthQuestion')
				->inwhere('CoreChildHealthQuestion.month', array(0))
				->inwhere('CoreChildHealthQuestion.question_type',array($healthvalue -> question_type))
				->groupBy('CoreChildHealthQuestion.month')
				->getQuery()->execute ();
			} else {
				$question2 = $this->modelsManager->createBuilder ()->columns ( array (
					'CoreChildHealthQuestion.month as month',
				)) -> from('CoreChildHealthQuestion')
				->where('CoreChildHealthQuestion.month < ' . $age . ' AND CoreChildHealthQuestion.month >=' . ($age - 12) . '')
				->inwhere('CoreChildHealthQuestion.question_type',array($healthvalue -> question_type))
				->groupBy('CoreChildHealthQuestion.month')
				->getQuery()->execute ();
			}
			$healthmontharray = array();
			foreach($question2 as $healthvalue2){
				$question3 = $this->modelsManager->createBuilder ()->columns ( array (
					'CoreChildHealthQuestion.id as id',
					'CoreChildHealthQuestion.month as month',
					'CoreChildHealthQuestion.question as question',
					'CoreChildHealthQuestion.question_type as question_type',
					'CoreChildHealthQuestion.create_at as create_at',
				)) -> from('CoreChildHealthQuestion')
				->inwhere('CoreChildHealthQuestion.month', array($healthvalue2 -> month))
				->inwhere('CoreChildHealthQuestion.question_type',array($healthvalue -> question_type))
				->getQuery()->execute ();
				$healtharray = array();
				foreach($question3 as $healthvalue3){
					$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
						'CoreChildHealthAnswer.id as id',
						'CoreChildHealthAnswer.question_id as question_id',
						'CoreChildHealthAnswer.answer as answer',
					))->from("CoreChildHealthAnswer")
					->inwhere('CoreChildHealthAnswer.question_id',array($healthvalue3 -> id))
					->inwhere('CoreChildHealthAnswer.child_id',array($input_data -> child_id))
					->getQuery()->execute ();
					foreach($childhealth as $value){
						
					}
					$healthdata['id'] = $healthvalue3 -> id;
					$healthdata['question'] = $healthvalue3 -> question;
					$healthdata['answer'] = $value -> answer;
					$healtharray[] = $healthdata;
				}
				$healthmonth['month'] =  $healthvalue2 -> month;
				$healthmonth['questions'] =  $healtharray;
				$healthmontharray[] =  $healthmonth;
			}
			if($healthvalue -> question_type == 1){
				$healthcat['cat_name'] = 'Gross Motor Skills';
			} else if($healthvalue -> question_type == 2) {
				$healthcat['cat_name'] = 'Fine Motor Skills';
			} else if($healthvalue -> question_type == 3) {
				$healthcat['cat_name'] = 'Probem Solving Skils';
			} else if($healthvalue -> question_type == 4) {
				$healthcat['cat_name'] = 'Overall';
			}
			$healthcat['month'] = $healthmontharray;
			$healthcat['question_type'] = $healthvalue -> question_type;
			$healthcatarray [] = $healthcat;
		}
		foreach($learning as $learningvalue){
			$montharray = array();
			$learning2 = $this->modelsManager->createBuilder ()->columns ( array (
					'CoreChildLearningQuestion.month as month',
			)) -> from('CoreChildLearningQuestion')
			->where('CoreChildLearningQuestion.month < ' . $age . ' AND CoreChildLearningQuestion.month >=' . ($age - 12) . '')
			->inwhere('CoreChildLearningQuestion.question_type',array($learningvalue -> question_type))
			->groupBy('CoreChildLearningQuestion.month')
			->getQuery()->execute ();
			foreach($learning2 as $learningvalue2){
				$learning3 = $this->modelsManager->createBuilder ()->columns ( array (
						'CoreChildLearningQuestion.id as id',
						'CoreChildLearningQuestion.month as month',
						'CoreChildLearningQuestion.question as question',
						'CoreChildLearningQuestion.question_type as question_type',
				)) -> from('CoreChildLearningQuestion')
				->inwhere('CoreChildLearningQuestion.month',array($learningvalue2 -> month))
				->inwhere('CoreChildLearningQuestion.question_type',array($learningvalue -> question_type))
				->getQuery()->execute ();
				$learnarray = array();
				foreach($learning3 as $learningvalue3){
					$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
						'CoreChildLearningAnswer.id as id',
						'CoreChildLearningAnswer.question_id as question_id',
						'CoreChildLearningAnswer.answer as answer',
					))->from("CoreChildLearningAnswer")
					->inwhere('CoreChildLearningAnswer.question_id',array($learningvalue3 -> id))
					->inwhere('CoreChildLearningAnswer.child_id',array($input_data -> child_id))
					->getQuery()->execute ();
					foreach($childhealth as $value){
						
					}
					$learndata['id'] = $learningvalue3 -> id;
					$learndata['question'] = $learningvalue3 -> question;
					$learndata['answer'] = $value -> answer;
					$learnarray[] = $learndata;
				}
				$monthdata['month'] = $learningvalue2 -> month;
				$monthdata['questions'] = $learnarray;
				$montharray[ ] = $monthdata;
			}
			
			if($learningvalue -> question_type == 1){
				$healthdata2['cat_name'] = 'Communication Skills';
			} else {
				$healthdata2['cat_name'] = 'Probem Solving Skils';
			}
			$healthdata2['month'] = $montharray;
			$learningarray[] = $healthdata2;
		}
		foreach($learningnc as $nclearnvalue){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildLearnNcAnswer.id as id',
				'CoreChildLearnNcAnswer.question_id as question_id',
				'CoreChildLearnNcAnswer.answer as answer',
			))->from("CoreChildLearnNcAnswer")
			->inwhere('CoreChildLearnNcAnswer.question_id',array($nclearnvalue -> id))
			->inwhere('CoreChildLearnNcAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			foreach($childhealth as $value){
				
			}
			$healthdata['id'] = $nclearnvalue -> id;
			$healthdata['month'] = $nclearnvalue -> month;
			$healthdata['question'] = $nclearnvalue -> question;
			$healthdata['answer'] = $value -> answer;
			$healthdata['question_type'] = $nclearnvalue -> question_type;
			$healthdata['create_at'] = $nclearnvalue -> create_at;
			$nclearnarray[] = $healthdata;
		}
		
		foreach($vision as $visionvalue){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildSensoryScreeningVisionAnswer.id as id',
				'CoreChildSensoryScreeningVisionAnswer.question_id as question_id',
				'CoreChildSensoryScreeningVisionAnswer.answer as answer',
			))->from("CoreChildSensoryScreeningVisionAnswer")
			->inwhere('CoreChildSensoryScreeningVisionAnswer.question_id',array($visionvalue -> id))
			->inwhere('CoreChildSensoryScreeningVisionAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			foreach($childhealth as $value){
				
			}
			$visiondata['id'] = $visionvalue -> id;
			$visiondata['question'] = $visionvalue -> question;
			$visiondata['answer'] = $value -> answer;
			$visiondata['create_at'] = $visionvalue -> create_at;
			$visionarray[] = $visiondata;
		}
		
		foreach($hearing as $hearingvalue){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildSensoryScreeningHearingAnswer.id as id',
				'CoreChildSensoryScreeningHearingAnswer.question_id as question_id',
				'CoreChildSensoryScreeningHearingAnswer.answer as answer',
			))->from("CoreChildSensoryScreeningHearingAnswer")
			->inwhere('CoreChildSensoryScreeningHearingAnswer.question_id',array($hearingvalue -> id))
			->inwhere('CoreChildSensoryScreeningHearingAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			foreach($childhealth as $value){
				
			}
			$visiondata['id'] = $hearingvalue -> id;
			$visiondata['question'] = $hearingvalue -> question;
			$visiondata['answer'] = $value -> answer;
			$visiondata['create_at'] = $hearingvalue -> create_at;
			$hearingarray[] = $visiondata;
		}
		
		foreach($oral_hygiene as $oral_hygienevalue){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildSensoryScreeningOralHygieneAnswer.id as id',
				'CoreChildSensoryScreeningOralHygieneAnswer.question_id as question_id',
				'CoreChildSensoryScreeningOralHygieneAnswer.answer as answer',
			))->from("CoreChildSensoryScreeningOralHygieneAnswer")
			->inwhere('CoreChildSensoryScreeningOralHygieneAnswer.question_id',array($oral_hygienevalue -> id))
			->inwhere('CoreChildSensoryScreeningOralHygieneAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			foreach($childhealth as $value){
				
			}
			$visiondata['id'] = $oral_hygienevalue -> id;
			$visiondata['question'] = $oral_hygienevalue -> question;
			$visiondata['answer'] = $value -> answer;
			$visiondata['create_at'] = $oral_hygienevalue -> create_at;
			$oral_hygienearray[] = $visiondata;
		}
		
		if($childhealth2){
			$childinfoarray[] = $childhealth2;
		} else {
			$value['id'] = '';
			$value['child_id'] = $input_data -> child_id;
			$childinfoarray[] = $value;
		}
		return $this->response->setJsonContent ([ 
						'status' => true,
						'data' => $childinfoarray,
						'health' => $healthcatarray,
						'learning' => $learningarray,
						'nclearn' => $nclearnarray,
						'vision' => $visionarray,
						'hearing' => $hearingarray,
						'oral_hygiene' => $oral_hygienearray,
						'childinfo' => $childinfo,
					]);
	}
	
	
	public function childbaselinecreat(){
		$input_data = $this->request->getJsonRawBody();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		$kidprofile = NidaraKidProfile::findFirstByid($input_data -> child_id);
		$kidprofile -> age = $input_data -> age;
		$kidprofile -> child_photo = $input_data -> child_photo;
		$kidprofile -> save();
		$childhealth = ChildHealthInfo::findFirstBychild_id($input_data -> child_id);
		if(!$childhealth){
			$childhealth = new ChildHealthInfo();
			$childhealth -> child_id = $input_data -> child_id;
		}
		$childhealth -> height = $input_data -> height;
		$childhealth -> age = $input_data -> age;
		$childhealth -> weight = $input_data -> weight;
		$childhealth -> doctor_name = $input_data -> doctor_name;
		$childhealth -> circumference = $input_data -> circumference;
		$childhealth -> vision = $input_data -> vision;
		$childhealth -> hearing = $input_data -> hearing;
		$childhealth -> oral_hygiene = $input_data -> oral_hygiene;
		$childhealth -> medical_concerns = $input_data -> medical_concerns;
		$childhealth -> breakfast = $input_data -> breakfast;
		$childhealth -> morning_snack = $input_data -> morning_snack;
		$childhealth -> lunch = $input_data -> lunch;
		$childhealth -> evening_snack = $input_data -> evening_snack;
		$childhealth -> dinner = $input_data -> dinner;
		if(!$childhealth -> save()){
			return $this->response->setJsonContent ([ 
				'status' => false,
				'message' => 'Please answer all the questions to your best knowledge.',
				'data' => $childhealth
			]);
		} else {
		foreach($input_data -> learnNcQuestion as $nclearn){
			$childlearnnc = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildLearnNcAnswer.id as id',
			))->from("CoreChildLearnNcAnswer")
			->inwhere('CoreChildLearnNcAnswer.question_id',array($nclearn -> id))
			->inwhere('CoreChildLearnNcAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			if(count($childlearnnc) == 0){
				$childlearnnc = new CoreChildLearnNcAnswer();
				$childlearnnc -> child_id = $input_data -> child_id;
				$childlearnnc -> question_id = $nclearn -> id;
				$childlearnnc -> answer = $nclearn -> answer;
				if(!$childlearnnc -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Please answer all the questions to your best knowledge.',
						'data' => $childlearnnc
					]);
				}
			}
			else{
				foreach($childlearnnc as $ncvalue){
					$childnclearn = CoreChildLearnNcAnswer::findFirstByid($ncvalue -> id);
					$childnclearn -> answer = $nclearn -> answer;
					$childnclearn -> save();
				}
			}
		}
		foreach($input_data -> learnQuestion as $learning1){
			foreach($learning1 -> month as $month){
				foreach($month -> questions as  $learning){
					$childlearing = $this->modelsManager->createBuilder ()->columns ( array (
						'CoreChildLearningAnswer.id as id',
					))->from("CoreChildLearningAnswer")
					->inwhere('CoreChildLearningAnswer.question_id',array($learning -> id))
					->inwhere('CoreChildLearningAnswer.child_id',array($input_data -> child_id))
					->getQuery()->execute ();
					if(count($childlearing) == 0){
						$childlearing = new CoreChildLearningAnswer();
						$childlearing -> child_id = $input_data -> child_id;
						$childlearing -> question_id = $learning -> id;
						$childlearing -> answer = $learning -> answer;
						if(!$childlearing -> save()){
							return $this->response->setJsonContent ([ 
								'status' => false,
								'message' => 'Please answer all the questions to your best knowledge. 3',
								'data' => $childlearing
							]);
						}
					}
					else{
						foreach($childlearing as $learnvalue){
							$childlearn = CoreChildLearningAnswer::findFirstByid($learnvalue -> id);
							$childlearn -> answer = $learning -> answer;
							$childlearn -> save();
						}
					}
				
				}
			}
		}

		foreach($input_data -> healthQuestion as $health1){
			foreach($health1 -> month as $month){
				foreach($month -> questions as  $health){
					$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
						'CoreChildHealthAnswer.id as id',
					))->from("CoreChildHealthAnswer")
					->inwhere('CoreChildHealthAnswer.question_id',array($health -> id))
					->inwhere('CoreChildHealthAnswer.child_id',array($input_data -> child_id))
					->getQuery()->execute ();
					if(count($childhealth) == 0){
						$childhealth = new CoreChildHealthAnswer();
						$childhealth -> child_id = $input_data -> child_id;
						$childhealth -> question_id = $health -> id;
						$childhealth -> answer = $health -> answer;
						if(!$childhealth -> save()){
							return $this->response->setJsonContent ([ 
								'status' => false,
								'message' => 'Please answer all the questions to your best knowledge.',
							]);
						}
					}
					else{
						foreach($childhealth as $healthvalue){
							$childhealthsub = CoreChildHealthAnswer::findFirstByid($healthvalue -> id);
							$childhealthsub -> answer = $health -> answer;
							$childhealthsub -> save();
						}
					}
				}
			}
		}
		
		foreach($input_data -> visionQuestion as $health){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildSensoryScreeningVisionAnswer.id as id',
			))->from("CoreChildSensoryScreeningVisionAnswer")
			->inwhere('CoreChildSensoryScreeningVisionAnswer.question_id',array($health -> id))
			->inwhere('CoreChildSensoryScreeningVisionAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			if(count($childhealth) == 0){
				$childhealth = new CoreChildSensoryScreeningVisionAnswer();
				$childhealth -> child_id = $input_data -> child_id;
				$childhealth -> question_id = $health -> id;
				$childhealth -> answer = $health -> answer;
				if(!$childhealth -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Please answer all the questions to your best knowledge.',
					]);
				}
			}
			else{
				foreach($childhealth as $healthvalue){
					$childhealthsub = CoreChildSensoryScreeningVisionAnswer::findFirstByid($healthvalue -> id);
					$childhealthsub -> answer = $health -> answer;
					$childhealthsub -> save();
				}
			}
		}
		
		
		foreach($input_data -> hearingQuestion as $health){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildSensoryScreeningHearingAnswer.id as id',
			))->from("CoreChildSensoryScreeningHearingAnswer")
			->inwhere('CoreChildSensoryScreeningHearingAnswer.question_id',array($health -> id))
			->inwhere('CoreChildSensoryScreeningHearingAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			if(count($childhealth) == 0){
				$childhealth = new CoreChildSensoryScreeningHearingAnswer();
				$childhealth -> child_id = $input_data -> child_id;
				$childhealth -> question_id = $health -> id;
				$childhealth -> answer = $health -> answer;
				if(!$childhealth -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Please answer all the questions to your best knowledge.',
					]);
				}
			}
			else{
				foreach($childhealth as $healthvalue){
					$childhealthsub = CoreChildSensoryScreeningHearingAnswer::findFirstByid($healthvalue -> id);
					$childhealthsub -> answer = $health -> answer;
					$childhealthsub -> save();
				}
			}
		}
		
		
		foreach($input_data -> oralhygieneQuestion as $health){
			$childhealth = $this->modelsManager->createBuilder ()->columns ( array (
				'CoreChildSensoryScreeningOralHygieneAnswer.id as id',
			))->from("CoreChildSensoryScreeningOralHygieneAnswer")
			->inwhere('CoreChildSensoryScreeningOralHygieneAnswer.question_id',array($health -> id))
			->inwhere('CoreChildSensoryScreeningOralHygieneAnswer.child_id',array($input_data -> child_id))
			->getQuery()->execute ();
			if(count($childhealth) == 0){
				$childhealth = new CoreChildSensoryScreeningOralHygieneAnswer();
				$childhealth -> child_id = $input_data -> child_id;
				$childhealth -> question_id = $health -> id;
				$childhealth -> answer = $health -> answer;
				if(!$childhealth -> save()){
					return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Please answer all the questions to your best knowledge.',
					]);
				}
			}
			else{
				foreach($childhealth as $healthvalue){
					$childhealthsub = CoreChildSensoryScreeningOralHygieneAnswer::findFirstByid($healthvalue -> id);
					$childhealthsub -> answer = $health -> answer;
					$childhealthsub -> save();
				}
			}
		}
		}

		return $this->response->setJsonContent ([ 
			'status' => true,
			'message' => 'Child Baseline saved successfully',
		]);
	}
	
	/**
	 * This function using to NidaraKidProfile information edit
	 */
	public function update() {
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
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Nidara kid profile id is empty' 
			] );
		}
		$validation = new Validation ();
		$validation->add ( 'first_name', new PresenceOf ( [ 
				'message' => 'Please enter First Name ' 
		] ) );
		/* $validation->add ( 'first_name', new Alpha ( [ 
				'message' => 'Field must contain only letters' 
		] ) ); */
		$validation->add ( 'first_name', new StringLength ( [ 
				'min' => 2,
				'messageMinimum' => 'First name has to be more than 2 characters ' 
		] ) );
		if (! empty ( $input_data->middle_name )) {
				$validation->add ( 'middle_name', new Alpha ( [ 
				'message' => 'Middle name must contain only letters ' 
		] ) );
		}
		$validation->add ( 'last_name', new PresenceOf ( [ 
				'message' => 'Please Enter Last Name ' 
		] ) );
		/* $validation->add ( 'last_name', new Alpha ( [ 
				'message' => 'Field must contain only letters' 
		] ) ); */
		$validation->add ( 'last_name', new StringLength ( [ 
				'min' => 2,
				'messageMinimum' => 'Last Name has to be more than 2 characters ' 
		] ) );
		$validation->add ( 'date_of_birth', new PresenceOf ( [ 
				'message' => 'Please Enter Date of birth ' 
		] ) );
		$validation->add ( 'date_of_birth', new Date ( [ 
				'format' => 'Y-m-d',
				'message' => 'The date is invalid ' 
		] ) );
		/* $validation->add ( 'age', new PresenceOf ( [ 
				'message' => 'Please Enter Age ' 
		] ) ); */ 
		
		$validation->add ( 'gender', new PresenceOf ( [ 
				'message' => 'Please Select Gender ' 
		] ) );
		$validation->add ( 'grade', new PresenceOf ( [ 
				'message' => ' Please Select Grade ' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent ( $result );
		}
		$kidprofile = NidaraKidProfile::findFirstByid ( $id );
		if ($kidprofile) {
			$kidprofile->first_name = $input_data->first_name;
			if (! empty ( $input_data->middle_name )) {
				$kidprofile->middle_name = $input_data->middle_name;
			}
			$kidprofile->last_name = $input_data->last_name;
			$kidprofile->date_of_birth = $input_data->date_of_birth;
			if(!empty($input_data->age)){
			$kidprofile->age = $input_data->age;
			}
			$kidprofile->birthterm = $input_data->birthterm;
			$kidprofile->birthweek = $input_data->birthweek;
			$kidprofile->gender = $input_data->gender;
			$kidprofile->height = $input_data->height;
			$kidprofile->weight = $input_data->weight;
			$kidprofile->grade = $input_data->grade;
			$kidprofile->status = $input_data->status;
			$kidprofile->choose_time = $input_data->choose_time;

			$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
			if (empty ( $input_data->child_photo )) {
				$gender = $input_data->gender;
				if ($gender == 'male') {
					$kidprofile->child_photo = "https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_male.png";
				} else {
					$kidprofile->child_photo = "https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_female.png";
				}
			} else {
				$kidprofile->child_photo = $input_data->child_photo;
			}
			if ($kidprofile->save ()) {
				return $this->response->setJsonContent ([ 
						'status' => true,
						'message' => 'Child details updated successfully' 
				]);
			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
				] );
			}
		}
	}
	
	public function childResgiter(){
		$input_data = $this->request->getJsonRawBody ();
		$user_id = isset($input_data->user_id) ? $input_data->user_id : '';
		$collection = $this->modelsManager->createBuilder ()->columns ( array (
			'COUNT(DailyRoutineAttendance.id) as days',
			'NidaraKidProfile.id as id',
		))->from('NidaraKidProfile')
		->leftjoin('DailyRoutineAttendance','DailyRoutineAttendance.nidara_kid_profile_id = NidaraKidProfile.id')
		->inwhere('NidaraKidProfile.free_trial',array(1))
		->inwhere('NidaraKidProfile.order_id',array($input_data->order_id))
		->getQuery ()->execute ();
		foreach($collection as $value){
				
			}	
		if(!empty($value->id)){
			
			$day = round(365 - $value->days);
			$collection->expiry_date = date("Y-m-d",strtotime('+'. $day .'days'));
			$collection->free_trial = 0;
			$collection->created_at = date ( 'Y-m-d' );
			if($collection->save()){
				return $this->response->setJsonContent ([ 
						'status' => true,
						'message' => 'Child details saved successfully',
						'kid_id' => $collection->id 
				]);
			}
			else{
				return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Fildedd',
				]);	
			}
		}
		else{
		$kidprofile = new NidaraKidProfile ();
		$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
		$kidprofile->first_name = 'Enter First Name';
			if (! empty ( $input_data->middle_name )) {
				$kidprofile->middle_name = $input_data->middle_name;
			}
			$kidprofile->last_name = 'Enter Last Name';
			$kidprofile->date_of_birth = date ( 'Y-m-d');
			if(!empty($input_data->age)){
			$kidprofile->age = 2;
			}
			$kidprofile->gender = $input_data->gender;
			$kidprofile->height = 0;
			$kidprofile->weight = 0;
			$kidprofile->grade = $input_data->grade;
			$kidprofile->child_photo = $input_data->child_photo;
			// $kidprofile_create->child_avatar = $input_data->child_avatar;
			
			
			$kidprofile->created_at = date ( 'Y-m-d' );
			
			if($input_data->endday == 1){
				$kidprofile->expiry_date = date("Y-m-d",strtotime('+7days'));
				$kidprofile->free_trial = 1;
			}
			
			else{
				$kidprofile->expiry_date = date("Y-m-d",strtotime('+365days'));
				$kidprofile->free_trial = 0;
			}
			$kidprofile->order_id = $input_data->order_id;
			$kidprofile->created_by = $user_id;
			$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
			$kidprofile->status = 1;
			$kidprofile->cancel_subscription = 1;
			if(!empty($input_data->board_of_education)){
				$kidprofile->board_of_education = $input_data->board_of_education;
			}
			if(!empty($input_data->relationship_to_child)){
			$kidprofile->relationship_to_child = $input_data->relationship_to_child;
		        }
			if (empty ( $input_data->child_photo )) {
				$gender = $input_data->gender;
				if ($gender == 'male') {
					$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_male.png';
				} else {
					$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_female.png';
				}
			} else {
				$kidprofile->child_photo = $input_data->child_photo;
			}
			
			if ($kidprofile->save ()) {
				
				if (! empty ( $input_data->grade )) {
					$kid_guide=KidGuidedLearningMap::findFirstBynidara_kid_profile_id($kidprofile->id);
					if(empty($kid_guide)){
					$kid_guide = new KidGuidedLearningMap ();
					$kid_guide->id = $this->kididgen->getNewId ( "nidarakidlearningmap" );
					$kid_guide->nidara_kid_profile_id = $kidprofile->id;
					$kid_guide->guided_learning_id = $input_data->grade;
					$kid_guide->save ();
					}
						
				}
					$parentsmap=KidParentsMap::findFirstBynidara_kid_profile_id($kidprofile->id);
					if(empty($parentsmap)){
						$parentsmap = new KidParentsMap ();
						$parentsmap->id = $this->kididgen->getNewId ( "kidparentsmap" );
						$parentsmap->nidara_kid_profile_id = $kidprofile->id;
						$parentsmap->users_id = $user_id;
						$parentsmap->save();
					}
				return $this->response->setJsonContent ([ 
						'status' => true,
						'message' => 'Child details saved successfully',
						'kid_id' => $kidprofile->id 
				]);
			}
			else{
				
			return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Fildedd',
				]);	
			}
		}
		
	}
	
	/**
	 * This function using delete kids caregiver information
	 */
	public function delete() {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => 'Error',
					'message' => 'Id is null' 
			] );
		 else :
			$collection = NidaraKidProfile::findFirstByid ( $id );
			if ($collection) :
				if ($collection->delete ()) :
					return $this->response->setJsonContent ( [ 
							'status' => 'OK',
							'Message' => 'Record has been deleted succefully ' 
					] );
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => 'Error',
							'Message' => 'Data could not be deleted' 
					] );
				endif;
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => 'Error',
						'Message' => 'ID doesn\'t' 
				] );
			endif;
		endif;
	}
	public function kid_board_of_education() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null' 
			] );
		} else {
			if (! empty ( $input_data->board_of_education )) {
				$board = BoardOfEducation::findFirstByid ( $input_data->board_of_education );
				if (empty ( $board )) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Board of education is invalid' 
					] );
				}
			}else{
				return $this->response->setJsonContent ( [
						'status' => false,
						'message' => 'Board of education is required'
				] );
			}
			if (! empty ( $input_data->grade )) {
				$grade = Grade::findFirstByid ( $input_data->grade );
				if (empty ( $grade )) {
					return $this->response->setJsonContent ( [
							'status' => false,
							'message' => 'Grade is invalid'
					] );
				}
			}else{
				return $this->response->setJsonContent ( [
						'status' => false,
						'message' => 'Grade is required'
				] );
			}
			$kid_board_update = NidaraKidProfile::findFirstByid ( $id );
			if ($kid_board_update) {
				$kid_board_update->board_of_education = $input_data->board_of_education;
				$kid_board_update->grade = $input_data->grade;
				if ($kid_board_update->save ()) {
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Kid profile updated successfully' 
					] );
				} else {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Failed' 
					] );
				}
			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'ID doesn\'t' 
				] );
			}
		}
	}
	public function cancel_subscription() {
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
			$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
			if (empty ( $id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Id is null' 
				] );
			} else {
				$user_info=$this->tokenvalidate->getuserinfo ( $headers['Token'], $baseurl );
				$userid = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
				$kid_payment_info = NidaraKidProfile::findFirstByid ( $id );
				if ($kid_payment_info) {
					$parent_id = array ();
					$user_id = array ();
					$kid_payment_info->cancel_subscription = 2;
					if ($kid_payment_info->save ()) {
						$kidparentmap = KidParentsMap::findFirstBynidara_kid_profile_id ( $input_data->kid_id );
						$parent_id = $kidparentmap->users_id;
						$user = Users::findFirstByid ( $userid );
						$profile = 'default';
						$path = APP_PATH . '/library/credentials.ini';
						$provider = CredentialProvider::ini ( $profile, $path );
						$provider = CredentialProvider::memoize ( $provider );
						// Instantiate an Amazon S3 client.
						$ses = SesClient::factory ( array (
								'version' => 'latest',
								'region' => 'us-east-1',
								'credentials' => $provider 
						) );
						
						$request ['Source'] = "priyanka@rootsbridge.com";
						$request ['Destination'] ['ToAddresses'] = array (
								"priyanka@rootsbridge.com" 
						);
						$request ['Message'] ['Subject'] ['Data'] = "Nidara";
						$baseurl = $this->config->appurl;
						$changeurl = $baseurl . '/?token=' . $token;
						$request ['Message'] ['Body'] ['Text'] ['Data'] = "Folowing User is requested for the cancellation<br><br>
					First name: " . $user->first_name . "<br>
					Last name: " . $user->last_name . "<br>
					Email: " . $user->email . "<br>
					Phone: " . $user->mobile . "<br>
					Kid Name:" . $kid_payment_info->first_name . "";
						$request ['Message'] ['Body'] ['Text'] ['Charset'] = 'UTF-8';
						$request ['Message'] ['Body'] ['Html'] ['Data'] = "Folowing User is requested for the cancellation<br><br>
					First name: " . $user->first_name . "<br>
					Last name: " . $user->last_name . "<br>
					Email: " . $user->email . "<br>
					Phone: " . $user->mobile . "<br>
					Kid Name:" . $kid_payment_info->first_name . "";
						$request ['Message'] ['Body'] ['Html'] ['Charset'] = 'UTF-8';
						$result = $ses->sendEmail ( $request );
						if ($result) {
							return $this->response->setJsonContent ( [ 
									'status' => true,
									'message' => 'Your account is requested for cancellation' 
							] );
						} else {
							return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' => 'Cannot send the mail' 
							] );
						}
					} else {
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Failed' 
						] );
					}
				} else {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'ID doesn\'t' 
					] );
				}
			}
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Cannot cancel subscribe' 
			] );
		}
	}
	public function viewchildinfofilter(){
		$input_data = $this->request->getJsonRawBody ();
		$collection -> grade = $input_data -> grade;
		$collection -> gender = $input_data -> gender;
		$collection -> all = $input_data -> all;
		if($collection -> all){
			$child_info = $this->modelsManager->createBuilder ()->from('NidaraKidProfile')
			->getQuery() ->execute();
		}
		else if($collection -> gender){
			$child_info = $this->modelsManager->createBuilder ()->from('NidaraKidProfile')
			->inwhere("NidaraKidProfile.gender",array($collection -> gender))
			->getQuery() ->execute();
			if($collection -> grade){
				$child_info = $this->modelsManager->createBuilder ()->from('NidaraKidProfile')
				->inwhere("NidaraKidProfile.gender",array($collection -> gender))
				->inwhere("NidaraKidProfile.grade",array($collection -> grade))
				->getQuery() ->execute();
			}
		}
		else if($collection -> grade){
			$child_info = $this->modelsManager->createBuilder ()->from('NidaraKidProfile')
			->inwhere("NidaraKidProfile.grade",array($collection -> grade))
			->getQuery() ->execute();
		}
		
		$filterarray = array();
		foreach($child_info as $value){
			$filterarray[] = $value;
		}
		/* return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$filterarray
			]); */
			$chunked_array = array_chunk ( $filterarray, 15 );
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

	public function dailysession(){
			
				$arrayvalue= array();
				$input_data = $this->request->getJsonRawBody ();
				$kid_profile = NidaraKidProfile::findFirstByid($input_data -> nidara_kid_profile_id);
				if($kid_profile -> choose_time == '1'){
					$collaction_day = $this->modelsManager->createBuilder ()->columns ( array(
						'DailyRoutineAttendance.id as id',
						'DailyRoutineAttendance.start_time as start_time',
					))->from('DailyRoutineAttendance')
					->inwhere('DailyRoutineAttendance.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->inwhere('DailyRoutineAttendance.attendanceDate',array(date ('Y-m-d')))
					->getQuery ()->execute ();
					if(count($collaction_day) > 0){
						foreach($collaction_day as $datavalue){
							$cenvertedTime = date('H:i:s',strtotime('+1 hour',strtotime($datavalue->start_time)));
							$value['start_time']=$datavalue->start_time;
							$value['end_time']=$cenvertedTime;
							$arrayvalue[] = $value;
						}
					} else {
						$cenvertedTime = date('H:i:s',strtotime('+1 hour',strtotime(date('H:i:s'))));
						$value['start_time']=date('H:i:s');
						$value['end_time']=$cenvertedTime;
						$arrayvalue[] = $value;
					}return $this->response->setJsonContent ( [
						'status' => true,
						'data' => $arrayvalue
					] ); 
					
				}
				else if($kid_profile -> choose_time == '2'){
					$collaction_day = $this->modelsManager->createBuilder ()->columns ( array(
						'DailyRoutine.id as id',
						'DailyRoutine.set_time as start_time',
						'DailyRoutine.end_time',
					))->from('DailyRoutine')
					->inwhere('DailyRoutine.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
					->inwhere('DailyRoutine.session_for',array(1))
					->getQuery ()->execute ();
					if(count($collaction_day) > 0 ){

$stime=strtotime($collaction_day[0]->start_time);
$etime=strtotime($collaction_day[0]->end_time);


						if((date("H:i:s",$stime) <= date('H:i:s')) && (date("H:i:s",$etime) >= date('H:i:s')))
						{
						foreach($collaction_day as $datavalue){
							$cenvertedTime = date('H:i:s',strtotime('+1 hour',strtotime($datavalue->start_time)));
							$value['start_time']=$datavalue->start_time;
							$value['end_time']=$datavalue->end_time;
							$arrayvalue[] = $value;
						}
					}
					else
					{
return $this->response->setJsonContent ( [
					'status' => false,
					'data' => "This is not a write time"
				] ); 
						
					}
					} else {
						return $this->response->setJsonContent ( [
					'status' => false,
					'data' => "This is not a write time"
				] ); 
					}

					return $this->response->setJsonContent ( [
						'status' => true,
						'data' => $arrayvalue
					] ); 
					
				} else {
					return $this->response->setJsonContent ( [
					'status' => false,
					'data' => "This is not a write time"
				] ); 
				}
				
				
	}
	
	
	public function setStatus(){
		$input_data = $this->request->getJsonRawBody ();
		$kid_profile = NidaraKidProfile::findFirstByid($input_data -> nidara_kid_profile_id);
		$kid_profile -> status = 1;
		$kid_profile -> save();
		return $this->response->setJsonContent ( [
			'status' => true,
			'message' => 'Update successfully'
		] ); 
	}
}
