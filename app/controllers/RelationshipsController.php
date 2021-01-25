<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class RelationshipsController extends \Phalcon\Mvc\Controller {
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
		
		$subject = Relationships::find ();
		if ($subject) :
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $subject
			] );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Faield' 
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
			$collection = Relationships::findFirstByid ( $id );
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
	 * This function using to create Relationships information
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
		$validation->add ( 'name', new PresenceOf ( [ 
				'message' => 'name is required' 
		] ) );
		$validation->add ( 'status', new PresenceOf ( [ 
				'message' => 'status is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$collection = new Relationships ();
			$collection->id = $input_data->id;
			$collection->name = $input_data->name;
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
		endif;
	}
	/**
	 * This function using to Relationships information edit
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
			$validation->add ( 'name', new PresenceOf ( [ 
					'message' => 'nameis required' 
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
				$collection = Relationships::findFirstByid ( $id );
				if ($collection) :
					$collection->id = $input_data->id;
					$collection->name = $input_data->name;
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
			$collection = Relationships::findFirstByid ( $id );
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
