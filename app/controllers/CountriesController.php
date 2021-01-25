<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class CountriesController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$headers = $this->request->getHeaders ();
		/* if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		} */
		
		$countries = Countries::find ();
		if ($countries) :
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $countries
			] );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Data not found' 
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
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		 else :
			$countries = Countries::findFirstByid ( $id );
			if ($countries) :
				return Json_encode ( $countries );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to create Countries information
	 */
	public function create() {
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
		$validation->add ( 'sortname', new PresenceOf ( [ 
				'message' => 'sort name is required' 
		] ) );
		$validation->add ( 'name', new PresenceOf ( [ 
				'message' => 'country name is required' 
		] ) );
		$validation->add ( 'phonecode', new PresenceOf ( [ 
				'message' => 'country phone code is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$countries = new Countries ();
			$countries->id = $input_data->id;
			$countries->sortname = $input_data->sortname;
			$countries->name = $input_data->name;
			$countries->phonecode = $input_data->phonecode;
			if ($countries->save ()) :
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'successfully' 
				] );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to Countries information edit
	 */
	public function update($id = null) {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null' 
			] );
		 else :
			$validation = new Validation ();
			
			$validation->add ( 'sortname', new PresenceOf ( [ 
				'message' => 'sort name is required' 
			] ) );
			$validation->add ( 'name', new PresenceOf ( [ 
					'message' => 'country name is required' 
			] ) );
			$validation->add ( 'phonecode', new PresenceOf ( [ 
					'message' => 'country phone code is required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach
				;
				return $this->response->setJsonContent ( $result );
			 else :
				$countries = Countries::findFirstByid ( $id );
				if ($countries) :
					$countries->id = $input_data->id;
					$countries->sortname = $input_data->sortname;
					$countries->name = $input_data->name;
					$countries->phonecode = $input_data->phonecode;
					if ($countries->save ()) :
						return $this->response->setJsonContent ( [ 
								'status' => true,
								'message' => 'successfully' 
						] );
					 else :
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Failed' 
						] );
					endif;
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
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
					'status' => false,
					'message' => 'Id is null' 
			] );
		 else :
			$countries = Countries::findFirstByid ( $id );
			if ($countries) :
				if ($countries->delete ()) :
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'Message' => 'Record has been deleted successfully ' 
					] );
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Data could not be deleted' 
					] );
				endif;
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'ID doesn\'t' 
				] );
			endif;
		endif;
	}
}
