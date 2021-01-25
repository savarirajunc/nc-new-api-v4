<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class HealthdevequesviewController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		$daily_routine = HealthDevelopmentQuestion::find ();
		if ($daily_routine) :
			
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$daily_routine
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Faield' 
			] );
		endif;
	}
}

