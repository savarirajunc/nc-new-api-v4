<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class StateController extends \Phalcon\Mvc\Controller {
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$state = States::find ();
		if ($state) :
			return Json_encode ( $state );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faield' 
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
			$state = States::findFirstByid ( $id );
			if ($state) :
				return Json_encode ( $state );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	/**
	 * This function using to create State information
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
		$validation->add ( 'state id', new PresenceOf ( [ 
				'message' => 'state_id is required' 
		] ) );
		$validation->add ( 'state_name', new PresenceOf ( [ 
				'message' => 'state name is required' 
		] ) );
		$validation->add ( 'countries_id', new PresenceOf ( [ 
				'message' => 'countries id is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
		 else :
			$state = new States ();
			$state->id = $input_data->id;
			$state->name = $input_data->name;
			$state->country_id = $input_data->country_id;
			if ($state->save ()) :
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
	 * This function using to State information edit
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
			$validation->add ( 'state_id', new PresenceOf ( [ 
					'message' => 'state id is required' 
			] ) );
			$validation->add ( 'state_name', new PresenceOf ( [ 
					'message' => 'state name is required' 
			] ) );
			$validation->add ( 'countries_id', new PresenceOf ( [ 
					'message' => 'countries id is required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
				foreach ( $messages as $message ) :
					$result [] = $message->getMessage ();
				endforeach
				;
				return $this->response->setJsonContent ( $result );
			 else :
				$state = States::findFirstByid ( $id );
				if ($collection) :
					$state->id = $input_data->id;
					$state->name = $input_data->name;
					$state->country_id = $input_data->country_id;
					if ($state->save ()) :
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
			$state = States::findFirstByid ( $id );
			if ($state) :
				if ($state->delete ()) :
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

	public function getbycountriesid($countries_id = null) {
		$input_data = $this->request->getJsonRawBody ();
		
		$countries_id = isset ( $input_data->countries_id ) ? $input_data->countries_id : '';
		if (empty ( $countries_id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		 else :
    // public $country_id;
	$state = States::findBycountry_id ($countries_id);
			if ($state) :
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $state 
				] );
				// return Json_encode ( $state );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}

	public function getcitiesbystatesid(){
		$input_data = $this->request->getJsonRawBody ();
		
		$city = isset ( $input_data->state ) ? $input_data->state : '';
		if (empty ( $city )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		} else {
			$cities = Cities::findBystate_id($city);
			if ($cities) :
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $cities 
				] );
				// return Json_encode ( $state );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
			endif;
		}
	}
}

