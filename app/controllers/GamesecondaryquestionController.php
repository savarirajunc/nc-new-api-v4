<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class GamesecondaryquestionController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		/* $headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		 */
		$subject = GameSecondaryQuestion::find ();
		if ($subject) :
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$subject
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed',
				        "data"=>array() 
			] );
		endif;
	}
	/*
	 * Fetch Record from database based on ID :-
	 */
	public function getbyid() {
		$input_data = $this->request->getJsonRawBody ();
		//$gamearray = array();
		$gamecoredetails = $this->modelsManager->createBuilder ()->columns ( array (
			'GameSecondaryQuestion.id as id',
			'GameSecondaryQuestion.game_id as games_id',
			'GameSecondaryQuestion.subject_id as subject_id',
			'Standard.standard_name as standard_name',
			'Indicators.indicator_name as indicator_name',
			'GameSecondaryQuestion.question_type as question_type',
			'GameSecondaryQuestion.question as question',
			'GameSecondaryQuestion.standard as standard',
			'GameSecondaryQuestion.indicators as indicators',
		))->from('GameSecondaryQuestion')
		->leftjoin('Subject','GameSecondaryQuestion.subject_id = Subject.id')
		->leftjoin('Standard','GameSecondaryQuestion.standard = Standard.id')
		->leftjoin('Indicators','GameSecondaryQuestion.indicators = Indicators.id')
		->inwhere('GameSecondaryQuestion.game_id',array($input_data -> game_id))
		->getQuery ()->execute ();
		$gamevaluearray = array();
		if(count($gamecoredetails) > 0){
			foreach($gamecoredetails as $value){
				$data['id'] = $value -> id;
				$data['framework_id'] = '2';
				$data['subject_id'] = $value -> subject_id;
				$data['standard'] = $value -> standard_name;
				$data['indicator'] = $value -> indicator_name;
				$data['standard_id'] = $value -> standard;
				$data['indicator_id'] = $value -> indicators;
				$data['questiontype'] = $value -> question_type;
				$data['question'] = $value -> question;
				$gamevaluearray[] = $data;
			}
		} else {
			$data['id'] = $value -> id;
			$data['framework_id'] = '2';
				$data['subject_id'] = '';
				$data['standard'] = '';
				$data['indicator'] = '';
				$data['standard_id'] = '';
				$data['indicator_id'] = '';
				$data['questiontype'] = '';
				$data['question'] = '';
				$gamevaluearray[] = $data;
		}
		$getvalue['game_id'] = $input_data -> game_id;
		$getvalue['gameCoreFrame'] = $gamevaluearray;
		$gamearray = $getvalue;
		// $id = isset ( $input_data->id ) ? $input_data->id : '';
		// if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $gamearray 
			] );
		//  else :
		// 	$collection = Grade::findFirstByid ( $id );
		// 	if ($collection) :
		// 		return Json_encode ( $collection );
		// 	 else :
		// 		return $this->response->setJsonContent ( [ 
		// 				'status' => 'Error',
		// 				'Message' => 'Data not found' 
		// 		] );
		// 	endif;
		// endif;
	}
	/**
	 * This function using to create Grade information
	 */
	public function create() {
		$input_data = $this->request->getJsonRawBody ();
		foreach($input_data -> gameCoreFrame as $value){
            $collection = GameSecondaryQuestion::findFirstByid ( $value -> id );
            if(!$collection){
                $collection = new GameSecondaryQuestion();
            }
            $collection -> game_id = $input_data -> game_id;
            $collection -> subject_id = $value -> subject_id;
            $collection -> standard = $value -> standard_id;
            $collection -> indicators = $value -> indicator_id;
            $collection -> question_type = $value -> questiontype;
            $collection -> question = $value -> question;
            if(!$collection -> save()){
                return $this->response->setJsonContent ( [ 
                    'status' => 'Error',
                    'message' => 'Failed' 
                ] );
            }
        }
        return $this->response->setJsonContent ( [ 
            'status' => true,
            'message' => 'succefully' 
        ] );
    //] );
		/**
		 * This object using valitaion
		 */
		
	}
	/**
	 * This function using to Grade information edit
	 */
	public function update($id = null) {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => 'Error',
					'message' => 'Id is null' 
			] );
		 else :
			$validation = new Validation ();
			$validation->add ( 'id', new PresenceOf ( [ 
					'message' => 'idis required' 
			] ) );
			$validation->add ( 'grade_name', new PresenceOf ( [ 
					'message' => 'grade_nameis required' 
			] ) );
			$validation->add ( 'status', new PresenceOf ( [ 
					'message' => 'statusis required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach
				;
				return $this->response->setJsonContent ( $result );
			 else :
				$collection = Grade::findFirstByid ( $id );
				if ($collection) :
					$collection->id = $input_data->id;
					$collection->grade_name = $input_data->grade_name;
					$collection->status = $input_data->status;
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
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => 'Error',
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
					'status' => 'Error',
					'message' => 'Id is null' 
			] );
		 else :
			$collection = Grade::findFirstByid ( $id );
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
}
