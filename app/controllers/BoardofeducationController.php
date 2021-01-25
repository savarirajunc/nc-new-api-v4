<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class BoardofeducationController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$subject = BoardOfEducation::find ();
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
	public function getbyid($id = null) {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => 'Error',
					'message' => 'Invalid input parameter' 
			] );
		 else :
			$collection = BoardOfEducation::findFirstByid ( $id );
			if ($collection) :
				return Json_encode ( $collection );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => 'Error',
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to create BoardOfEducation information
	 */
	public function create() {
		$input_data = $this->request->getJsonRawBody ();
		
		/**
		 * This object using valitaion
		 */
		$validation = new Validation ();
		$validation->add ( 'id', new PresenceOf ( [ 
				'message' => 'id is required' 
		] ) );
		$validation->add ( 'board_name', new PresenceOf ( [ 
				'message' => 'board_name is required' 
		] ) );
		$validation->add ( 'created_at', new PresenceOf ( [ 
				'message' => 'created_at is required' 
		] ) );
		$validation->add ( 'created_by', new PresenceOf ( [ 
				'message' => 'created_by is required' 
		] ) );
		$validation->add ( 'modified_at', new PresenceOf ( [ 
				'message' => 'modified_at is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$collection = new BoardOfEducation ();
			$collection->id = $input_data->id;
			$collection->board_name = $input_data->board_name;
			$collection->created_at = $input_data->created_at;
			$collection->created_by = $input_data->created_by;
			$collection->modified_at = $input_data->modified_at;
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
	/**
	 * This function using to BoardOfEducation information edit
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
			$validation->add ( 'board_name', new PresenceOf ( [ 
					'message' => 'board_nameis required' 
			] ) );
			$validation->add ( 'created_at', new PresenceOf ( [ 
					'message' => 'created_atis required' 
			] ) );
			$validation->add ( 'created_by', new PresenceOf ( [ 
					'message' => 'created_byis required' 
			] ) );
			$validation->add ( 'modified_at', new PresenceOf ( [ 
					'message' => 'modified_atis required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach
				;
				return $this->response->setJsonContent ( $result );
			 else :
				$collection = BoardOfEducation::findFirstByid ( $id );
				if ($collection) :
					$collection->id = $input_data->id;
					$collection->board_name = $input_data->board_name;
					$collection->created_at = $input_data->created_at;
					$collection->created_by = $input_data->created_by;
					$collection->modified_at = $input_data->modified_at;
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
			$collection = BoardOfEducation::findFirstByid ( $id );
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
