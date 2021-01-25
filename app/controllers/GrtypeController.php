<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class GrtypeController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$gr_type = GRType::find ();
		if ($gr_type) :
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$gr_type 
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faield' 
			] );
		endif;
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
			$validation->add ( 'gr_framework_id', new PresenceOf ( [ 
					'message' => 'Framework Id is required' 
			] ) );
			$validation->add ( 'type_name', new PresenceOf ( [ 
					'message' => 'Type is required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
			else :
			$collection = new GRType ();
			$collection->id = $this->gradingreporting->getNewId ( "gratingreporting" );
			$collection->gr_framework_id = $input_data->gr_framework_id;
			$collection->type_name = $input_data->type_name;
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
}

