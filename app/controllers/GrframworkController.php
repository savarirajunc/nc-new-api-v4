<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class GrframworkController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$gr_framework = GRFramework::find ();
		if ($gr_framework) :
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$gr_framework 
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
			$validation->add ( 'framework_name', new PresenceOf ( [ 
					'message' => 'Framework is required' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) :
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			return $this->response->setJsonContent ( $result );
			else :
			$collection = new GRFramework ();
			$collection->id = $this->gradingreporting->getNewId ( "gratingreporting" );
			$collection->framework_name = $input_data->framework_name;
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

