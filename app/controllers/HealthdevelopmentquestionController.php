<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class HealthdevelopmentquestionController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$healthviewall = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT HealthDevelopmentQuestion.grade_id as grade_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'HealthDevelopmentCatagory.health_dev_cat as health_dev_cat',
			'HealthDevelopmentQuestion.subject_id as subject_id',
			'HealthDevelopmentQuestion.heth_cat as heth_cat',
		))->from('HealthDevelopmentQuestion')
		->leftjoin('Grade','HealthDevelopmentQuestion.grade_id = Grade.id')
		->leftjoin('CoreFrameworks','HealthDevelopmentQuestion.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','HealthDevelopmentQuestion.subject_id = Subject.id')
		->leftjoin('HealthDevelopmentCatagory','HealthDevelopmentQuestion.heth_cat = HealthDevelopmentCatagory.id')->getQuery ()->execute ();
		
		$healtharray = array();
		foreach($healthviewall as $value){
			$health_list['grade_name'] = $value -> grade_name;
			$health_list['core_name'] = $value -> name;
			$health_list['subject_name'] = $value -> subject_name;
			$health_list['health_dev_cat'] = $value -> health_dev_cat;
			$health_list['grade_id'] = $value -> grade_id;
			$health_list['subject_id'] = $value -> subject_id;
			$health_list['heth_cat'] = $value -> heth_cat;
			$healtharray[] = $health_list;
		}
		$chunked_array = array_chunk ( $healtharray, 15 );
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
	public function create(){
		$input_data = $this->request->getJsonRawBody();
		/**
         * This object using valitaion 
         */
       $validation = new Validation(); 
		/* $validation->add('id', new PresenceOf(['message' => 'id is required'])); */
        $validation->add('grade_id', new PresenceOf(['message' => 'grade_id is required']));
        $validation->add('framework_id', new PresenceOf(['message' => 'framework_id is required']));
        $validation->add('subject_id', new PresenceOf(['message' => 'subject_id is required']));
        $validation->add('heth_cat', new PresenceOf(['message' => 'heth_cat is required']));  
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
        else{
			$health_qustion = $input_data -> healthDevelopmentQA;
			$i = 0;
			foreach($health_qustion as $value){
				$collection = new HealthDevelopmentQuestion();
				$collection -> id = $this->gradingreporting->getNewId ( "gratingreporting" );
				$collection -> grade_id = $input_data -> grade_id;
				$collection -> framework_id = $input_data -> framework_id;
				$collection -> subject_id = $input_data -> subject_id;
				$collection -> heth_cat = $input_data -> heth_cat;
				$collection -> question_id = $value -> question_id;
				$collection -> question = $value -> question;
				$collection->save();
				$i++;
			}
			return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		}
		
	}
	
	
	public function update(){
		$input_data = $this->request->getJsonRawBody();
		/**
         * This object using valitaion 
         */
		 
        $validation = new Validation(); 
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
        else{
			$gamecoredata = $input_data -> gamaData;
			foreach($gamecoredata as $gamecorevalue){
			$health_qustion = $input_data -> healthDevelopmentQA;
			$i = 0;
			foreach($health_qustion as $value){
				$collection = HealthDevelopmentQuestion::findFirstByid( $value->id );
				
				$collection -> grade_id = $gamecorevalue -> grade_id;
				$collection -> subject_id = $gamecorevalue -> subject_id;
				$collection -> heth_cat = $gamecorevalue -> heth_cat;
				$collection -> question_id = $value -> question_id;
				$collection -> question = $value -> question;
				 /* return $this->response->setJsonContent(['status' => true, 'message' => $value -> question_id,$value -> question]); */
				if(!$collection->save()){
					return $this->response->setJsonContent(['status' => false, 'message' => 'failed']);
				}
				$i++;
			}
			
			}
		}
		return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		
	}
	
	
		public function getbyhealthval(){
		$input_data = $this->request->getJsonRawBody ();
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
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		} 
		
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		
		if(empty($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		
		$heth_cat = isset ( $input_data->heth_cat ) ? $input_data->heth_cat : '';
		
		if(empty($heth_cat)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'heth_cat Id is null'
			] );
		}
		
		
		$healthdevelopment = $this->modelsManager->createBuilder ()->columns ( array (
			'HealthDevelopmentQuestion.id as id',
			'HealthDevelopmentQuestion.question_id as question_id',
			'HealthDevelopmentQuestion.question as question',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.id as framework_id',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'HealthDevelopmentCatagory.health_dev_cat as health_dev_cat'
		))->from('HealthDevelopmentQuestion')
			->leftjoin('Grade','HealthDevelopmentQuestion.grade_id = Grade.id')
			->leftjoin('CoreFrameworks','HealthDevelopmentQuestion.framework_id = CoreFrameworks.id')
			->leftjoin('HealthDevelopmentCatagory','HealthDevelopmentQuestion.heth_cat = HealthDevelopmentCatagory.id')
			->leftjoin('Subject','HealthDevelopmentQuestion.subject_id = Subject.id')
			->inwhere("HealthDevelopmentQuestion.grade_id",array($grade_id))
			->inwhere('HealthDevelopmentQuestion.subject_id',array($subject_id))
			->inwhere('HealthDevelopmentQuestion.heth_cat',array($heth_cat))
			->getQuery ()->execute ();
			 
			 $healthdevelopmentarray = array();
			 foreach($healthdevelopment as $value){
				 $standard_database_val['id'] = $value->id;
				 $standard_database_val['question_id'] = $value->question_id;
				 $standard_database_val['question'] = $value->question;
				 $standard_database_val['grade_name'] = $value->grade_name;
				 $standard_database_val['core_name'] = $value->name;
				 $standard_database_val['subject_name'] = $value->subject_name;
				 $standard_database_val['framework_id'] = $value->framework_id;
				 $standard_database_val['health_dev_cat'] = $value->health_dev_cat;
				 $healthdevelopmentarray [] = $standard_database_val;
			 }
		 return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $healthdevelopmentarray
		] );
	}
	
	public function getbyhealthcore(){
		$input_data = $this->request->getJsonRawBody ();
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
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		} 
		
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		
		if(empty($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		
		$heth_cat = isset ( $input_data->heth_cat ) ? $input_data->heth_cat : '';
		
		if(empty($heth_cat)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'heth_cat Id is null'
			] );
		}
		
		
		$healthdevelopment_val = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT HealthDevelopmentQuestion.grade_id as grade_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'HealthDevelopmentCatagory.health_dev_cat as health_dev_cat',
			'HealthDevelopmentQuestion.framework_id as framework_id',
			'HealthDevelopmentQuestion.subject_id as subject_id',
			'HealthDevelopmentQuestion.heth_cat as heth_cat',
		))->from('HealthDevelopmentQuestion')
			->leftjoin('Grade','HealthDevelopmentQuestion.grade_id = Grade.id')
			->leftjoin('CoreFrameworks','HealthDevelopmentQuestion.framework_id = CoreFrameworks.id')
			->leftjoin('HealthDevelopmentCatagory','HealthDevelopmentQuestion.heth_cat = HealthDevelopmentCatagory.id')
			->leftjoin('Subject','HealthDevelopmentQuestion.subject_id = Subject.id')
			->inwhere("HealthDevelopmentQuestion.grade_id",array($grade_id))
			->inwhere('HealthDevelopmentQuestion.subject_id',array($subject_id))
			->inwhere('HealthDevelopmentQuestion.heth_cat',array($heth_cat))
			->getQuery ()->execute ();
			 
			 $healthdevelopmentarrayval = array();
			 foreach($healthdevelopment_val as $value){
				 $standard_database_val['grade_id'] = $value->grade_id;
				 $standard_database_val['subject_id'] = $value->subject_id;
				 $standard_database_val['heth_cat'] = $value->heth_cat;
				 $standard_database_val['framework_id'] = $value->framework_id;
				 $healthdevelopmentarrayval [] = $standard_database_val;
			 }
		 return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $healthdevelopmentarrayval
		] );
	}
}

