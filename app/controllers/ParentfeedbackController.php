<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ParentfeedbackController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = ParentFeedBackQues::find();
        if ($subject):
            return Json_encode($subject);
        else:
            return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Faield']);
        endif;
    }
	
	public function create(){
		 $input_data = $this->request->getJsonRawBody();
		 $questionanswer = $input_data -> questionAnswe;
		 foreach($questionanswer as $value){
			 $collection = new ParentFeedBackAns();
			 $collection -> user_id = $input_data -> user_id;
			 $collection -> question_id = $value -> id;
			 $collection -> answer = $value -> answer;
			 if(!$value -> remarks){
				 $collection -> remarks = '';
			 }
			 else{
				 $collection -> remarks = $value -> remarks;
			 }
			 
			 if(!$collection -> save()){
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please complete the daily feedback form"
					] ); 
			 }
		 }
		$getfeedbackstatus = ParentFeedBackStatus::findFirstByuser_id($input_data -> user_id);
			 $getfeedbackstatus -> status = 'start_session';
			 $getfeedbackstatus -> feedback_date = date('Y-m-d');
			 if(!$getfeedbackstatus -> save()){
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please check"
					] );  
			 } 
			 return $this->response->setJsonContent ( [
					"status" => true,
					"message" => "Your daily feedback has been submitted successfully."
				] ); 
	}
	
	public function startsession(){
		$input_data = $this->request->getJsonRawBody();
		$getfeedbackstatus = ParentFeedBackStatus::findFirstByuser_id($input_data -> user_id);
		$getfeedbackstatus -> status = $input_data -> status;
		$getfeedbackstatus -> feedback_date = date('Y-m-d');
			if(!$getfeedbackstatus -> save()){
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "user not agree tc"
					] );  
			 } 
			 return $this->response->setJsonContent ( [
				"status" => true,
				"message" => "Sucessfully Accepted"
			] ); 
	}

    

} 
