<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class NidarauseragreetcController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = NidaraUserAgreeTC::find();
        if ($subject):
            return Json_encode($subject);
        else:
            return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Faield']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid() {

        $input_data = $this->request->getJsonRawBody();
        $user_id = isset($input_data->user_id) ? $input_data->user_id : '';
		if(empty($user_id)){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give user id"
			] );
		}
		else{
			$collection = NidaraUserAgreeTC::findFirstByuser_id($user_id);
			if(!$collection){
				return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "user not agree tc",
					'ip_address' => $_SERVER['REMOTE_ADDR'],
					'time' => date('H:i:s'),
					'date' => date('Y-m-d')
				] );
			}
			else{
				if($collection -> status > 4){
					$getfeedbackstatus = ParentFeedBackStatus::findFirstByuser_id($user_id);
					return $this->response->setJsonContent ( [
						"status" => true,
						"data" => $getfeedbackstatus
					] );
				}
				else {
					return $this->response->setJsonContent ( [
						"status" => true,
						"data" => $collection
					] );
				}
				
			}
		}
       
    }
	
	public function create(){
		 $input_data = $this->request->getJsonRawBody();
		 $collection = NidaraUserAgreeTC::findFirstByuser_id($input_data -> user_id);
		 if(!$collection){
			 $collection = new NidaraUserAgreeTC();
		 }
		 $collection -> user_id = $input_data -> user_id;
		 $collection -> ip_address = $_SERVER['REMOTE_ADDR'];
		 $collection -> status = $input_data -> status;
		 if(!$collection -> save()){
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "user not agree tc",
					"data" => $collection
				] ); 
		 }
		 else{
			 return $this->response->setJsonContent ( [
					"status" => true,
					"message" => "Sucessfully Accepted"
				] ); 
		 }
	}

    

} 
