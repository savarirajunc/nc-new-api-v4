<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class GradingreportingController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		/* $grating_reportin = GradingReporting::find ();
		if ($grating_reportin) :
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$grating_reportin 
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faield' 
			] );
		endif; */
		$grating_reportin = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT Grade.id as grade_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.id as core_frame_id',
			'CoreFrameworks.name as name',
			'Subject.id as subject_id',
			'Subject.subject_name as subject_name',
			'GradingReporting.gr_frame_type as gr_frame_type'
		))->from('GradingReporting')
		->leftjoin('Grade','GradingReporting.gr_framwork_id = Grade.id')
		->leftjoin('CoreFrameworks','GradingReporting.gr_type_id = CoreFrameworks.id')
		->leftjoin('Subject','GradingReporting.subject_id = Subject.id')->getQuery() ->execute();
		$gratingarray = array();
		foreach($grating_reportin as $grating_val){
			$garating['grade_id'] = $grating_val->grade_id;
			$garating['core_frame_id'] = $grating_val->core_frame_id;
			$garating['subject_id'] = $grating_val->subject_id;
			$garating['grade_name'] = $grating_val->grade_name;
			$garating['core_name'] = $grating_val->name;
			$garating['subject_name'] = $grating_val->subject_name;
			$garating['gr_frame_type'] = $grating_val->gr_frame_type;
			$gratingarray[] = $garating;
		}
		$chunked_array = array_chunk ( $gratingarray, 15 );
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
			$validation->add ( 'gr_type_id', new PresenceOf ( [ 
					'message' => 'Gr Type is required' 
			] ) );
			$validation->add ( 'add_grade', new PresenceOf ( [ 
					'message' => 'To Grade is required' 
			] ) );
			$validation->add ( 'add_grade_range_max', new PresenceOf ( [ 
					'message' => 'Grade Range Max is required' 
			] ) );
			$validation->add ( 'add_grade_range_min', new PresenceOf ( [ 
					'message' => 'Grade Range min is required' 
			] ) );

			$validation->add ( 'add_definition', new PresenceOf ( [ 
					'message' => 'Definition is required' 
			] ) );
			$validation->add ( 'add_proficiency_level', new PresenceOf ( [ 
					'message' => 'Proficiency level is required' 
			] ) );
			$validation->add ( 'add_does_id_mean', new PresenceOf ( [ 
					'message' => 'DOES IT MEAN FOR PARENTS is required' 
			] ) );
			$validation->add ( 'add_nidara_recommendation', new PresenceOf ( [ 
					'message' => 'Nidara Recommendation is required' 
			] ) );
			$validation->add ( 'add_color', new PresenceOf ( [ 
					'message' => 'Color is required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
			else :
			if ($input_data->gr_type_id == 2){
				$collection = new GradingReporting ();
				$collection->id = $this->gradingreporting->getNewId ( "gratingreporting" );
				$collection->gr_framwork_id = $input_data->gr_framwork_id;
				$collection->gr_type_id = $input_data->gr_type_id;
				$collection->subject_id = $input_data->subject_id;
			}
			else{
				$collection = new GradingReporting ();
				$collection->id = $this->gradingreporting->getNewId ( "gratingreporting" );
				$collection->gr_framwork_id = $input_data->gr_framwork_id;
				$collection->gr_type_id = $input_data->gr_type_id;
				$collection->subject_id = 0;
			}
			$collection->gr_frame_type = $input_data->gr_frame_type;
			$collection->add_grade = $input_data->add_grade;
			$collection->add_grade_range_min = $input_data->add_grade_range_min;
			$collection->add_grade_range_max = $input_data->add_grade_range_max;
			$collection->add_definition = $input_data->add_definition;
			$collection->add_proficiency_level = $input_data->add_proficiency_level;
			$collection->add_does_id_mean = $input_data->add_does_id_mean;
			$collection->add_nidara_recommendation = $input_data->add_nidara_recommendation;
			$collection->add_color = $input_data->add_color;
			if ($collection->save ()) :
				return $this->response->setJsonContent ( [ 
						'status' => 'Ok',
						'message' => 'succefully' 
				] );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => 'Error',
						'message' => 'Failed' 
				] );
			endif;
		endif;
	}
	
	public function update(){
		$input_data = $this->request->getJsonRawBody ();
		$validation = new Validation(); 
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
		else{
			$gradingreporting = $input_data -> gradingUpdate;
			foreach($gradingreporting as $value){
				$collection = GradingReporting::findFirstByid( $value->id );
				$collection->gr_framwork_id = $value->gr_framwork_id;
				$collection->gr_type_id = $value->gr_type_id;
				$collection->subject_id = $value->subject_id;
				$collection->gr_frame_type = $value->gr_frame_type;
				$collection->add_grade = $value->add_grade;
				$collection->add_grade_range_min = $value->add_grade_range_min;
				$collection->add_grade_range_max = $value->add_grade_range_max;
				$collection->add_definition = $value->add_definition;
				$collection->add_proficiency_level = $value->add_proficiency_level;
				$collection->add_does_id_mean = $value->add_does_id_mean;
				$collection->add_nidara_recommendation = $value->add_nidara_recommendation;
				$collection->add_color = $value->add_color;
				if(!$collection->save ()){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Failed' 
					] );
				}
			}
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'message' => 'succefully' 
			] );
		}
	}
	
	public function getbycorewise(){
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
		$core_frame_id = isset($input_data->core_frame_id) ? $input_data->core_frame_id : '';
		
		if(empty ($core_frame_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'CoreFrameworks Id is null'
			] );
		}
		$grating_core = $this->modelsManager->createBuilder ()->columns ( array (
			'GradingReporting.id as id',
			'Grade.id as grade_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.id as core_frame_id',
			'CoreFrameworks.name as name',
			'Subject.id as subject_id',
			'Subject.subject_name as subject_name',
			'GradingReporting.add_grade as add_grade',
			'GradingReporting.gr_frame_type as gr_frame_type',
			'GradingReporting.add_grade_range_min as add_grade_range_min',
			'GradingReporting.add_grade_range_max as add_grade_range_max',
			'GradingReporting.add_proficiency_level as add_proficiency_level',
			'GradingReporting.add_does_id_mean as add_does_id_mean',
			'GradingReporting.add_nidara_recommendation as add_nidara_recommendation',
			'GradingReporting.add_definition as add_definition',
			'GradingReporting.add_color as add_color'
		))->from('GradingReporting')
		->leftjoin('Grade','GradingReporting.gr_framwork_id = Grade.id')
		->leftjoin('CoreFrameworks','GradingReporting.gr_type_id = CoreFrameworks.id')
		->leftjoin('Subject','GradingReporting.subject_id = Subject.id')
		->inwhere ('GradingReporting.gr_framwork_id',array($grade_id))
		->inwhere ('GradingReporting.gr_type_id',array($core_frame_id))
		->getQuery ()->execute ();
		$gratingarray = array();
		foreach($grating_core as $grating_val){
			$garating['id'] = $grating_val->id;
			$garating['gr_framwork_id'] = $grating_val->grade_id;
			$garating['gr_type_id'] = $grating_val->core_frame_id;
			$garating['subject_id'] = $grating_val->subject_id;
			$garating['gr_frame_type'] = $grating_val->gr_frame_type;
			$garating['grade_name'] = $grating_val->grade_name;
			$garating['core_name'] = $grating_val->name;
			$garating['subject_name'] = $grating_val->subject_name;
			$garating['add_grade'] = $grating_val->add_grade;
			$garating['add_grade_range_min'] = $grating_val->add_grade_range_min;
			$garating['add_grade_range_max'] = $grating_val->add_grade_range_max;
			$garating['add_proficiency_level'] = $grating_val->add_proficiency_level;
			$garating['add_does_id_mean'] = $grating_val->add_does_id_mean;
			$garating['add_definition'] = $grating_val->add_definition;
			$garating['add_nidara_recommendation'] = $grating_val->add_nidara_recommendation;
			$garating['add_color'] = $grating_val->add_color;
			$gratingarray[] = $garating;
		}
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$gratingarray 
			]);
	}
	
	public function getbysubjectwise(){
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
		
		$core_frame_id = isset($input_data->core_frame_id) ? $input_data->core_frame_id : '';
		
		if(empty ($core_frame_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'CoreFrameworks Id is null'
			] );
		}
		
		$subject_id = isset($input_data->subject_id) ? $input_data->subject_id : '';
		if(empty ($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		
		$grating_subject = $this->modelsManager->createBuilder ()->columns ( array (
			'GradingReporting.id as id',
			'Grade.id as grade_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.id as core_frame_id',
			'CoreFrameworks.name as name',
			'Subject.id as subject_id',
			'Subject.subject_name as subject_name',
			'GradingReporting.add_grade as add_grade',
			'GradingReporting.gr_frame_type as gr_frame_type',
			'GradingReporting.add_grade_range_min as add_grade_range_min',
			'GradingReporting.add_grade_range_max as add_grade_range_max',
			'GradingReporting.add_proficiency_level as add_proficiency_level',
			'GradingReporting.add_does_id_mean as add_does_id_mean',
			'GradingReporting.add_nidara_recommendation as add_nidara_recommendation',
			'GradingReporting.add_definition as add_definition',
			'GradingReporting.add_color as add_color'
		))->from('GradingReporting')
		->leftjoin('Grade','GradingReporting.gr_framwork_id = Grade.id')
		->leftjoin('CoreFrameworks','GradingReporting.gr_type_id = CoreFrameworks.id')
		->leftjoin('Subject','GradingReporting.subject_id = Subject.id')
		->inwhere ('GradingReporting.gr_framwork_id',array($grade_id))
		->inwhere ('GradingReporting.gr_type_id',array($core_frame_id))
		->inwhere ('GradingReporting.subject_id',array($subject_id))
		->getQuery ()->execute ();
		
		$gratingarray = array();
		foreach($grating_subject as $grating_val){
			$garating['id'] = $grating_val->id;
			$garating['gr_framwork_id'] = $grating_val->grade_id;
			$garating['gr_type_id'] = $grating_val->core_frame_id;
			$garating['subject_id'] = $grating_val->subject_id;
			$garating['gr_frame_type'] = $grating_val->gr_frame_type;
			$garating['grade_name'] = $grating_val->grade_name;
			$garating['core_name'] = $grating_val->name;
			$garating['subject_name'] = $grating_val->subject_name;
			$garating['add_grade'] = $grating_val->add_grade;
			$garating['add_grade_range_min'] = $grating_val->add_grade_range_min;
			$garating['add_grade_range_max'] = $grating_val->add_grade_range_max;
			$garating['add_proficiency_level'] = $grating_val->add_proficiency_level;
			$garating['add_does_id_mean'] = $grating_val->add_does_id_mean;
			$garating['add_definition'] = $grating_val->add_definition;
			$garating['add_nidara_recommendation'] = $grating_val->add_nidara_recommendation;
			$garating['add_color'] = $grating_val->add_color;
			$gratingarray[] = $garating;
		}
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$gratingarray 
			]);
	}
}

