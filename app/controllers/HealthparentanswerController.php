<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class HealthparentanswerController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = GamesAnswers::find();
        if ($subject):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }
	
	

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
        else:
            $collection = HealthParentAnswers::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
            endif;
        endif;
    }

    public function create(){
		$input_data = $this->request->getJsonRawBody();
		$email_id = isset( $input_data-> email_id)? $input_data-> email_id : '';
		if(empty($email_id)){
			$validation = new Validation();
		   
			$messages = $validation->validate($input_data);
			if (count($messages)){
				foreach ($messages as $message) :
					$result[] = $message->getMessage();
				endforeach;
				return $this->response->setJsonContent($result);
			}
			else{
				$parentquesanswer = $input_data->parentQuestionAnswer;
				foreach($parentquesanswer as $value){
					$collaction = HealthParentAnswers::findFirstByparent_question_id($value->id);
					if(!$collaction){
						$collaction = new HealthParentAnswers();
						$collaction ->id = $this ->parentgamesidgen->getNewId('parentAnswer');
						$collaction ->parent_question_id = $value->id;
					}
					$collaction ->parent_answer = $value->answervalue;
					$collaction ->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$collaction ->created_at = date ( 'Y-m-d H:i:s' );
					//return $this->response->setJsonContent(['status' => true, 'Message' => $collaction]);
					if(!$collaction -> save()){
						return $this->response->setJsonContent(['status' => false, 'message' => 'Please fill in your responses to go to the previous or next page.']);
					}
				}
				//return $this->response->setJsonContent(['status' => true, 'Message' => $collaction]);
				$collaction = new HealthParentAnswerStatus();
				$collaction -> id = $this ->parentgamesidgen->getNewId('parentAnswerStatus');
				$collaction -> day_id = $input_data->day_id;
				$collaction -> nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
				$collaction -> status = "Completed";
				$collaction -> created_at = date ( 'Y-m-d H:i:s' );
				$collaction -> save ();
					return $this->response->setJsonContent(['status' => true, 'Message' => 'Successful']);
			}
		}
		else{
			$validation = new Validation();
		   
			$messages = $validation->validate($input_data);
			if (count($messages)){
				foreach ($messages as $message) :
					$result[] = $message->getMessage();
				endforeach;
				return $this->response->setJsonContent($result);
			}
			else{
				$parentquesanswer = $input_data->parentQuestionAnswer;
				foreach($parentquesanswer as $value){
					$collaction = HealthCampParentAnswers::findFirstByparent_question_id($value->id);
					if(!$collaction){
						$collaction = new HealthCampParentAnswers();
						$collaction ->id = $this ->parentgamesidgen->getNewId('parentAnswer');
						$collaction ->parent_question_id = $value->id;
					}
					$collaction ->parent_answer = $value->answervalue;
					$collaction ->nidara_parent_email = $input_data->email_id;
					$collaction ->created_at = date ( 'Y-m-d H:i:s' );
					//return $this->response->setJsonContent(['status' => true, 'Message' => $collaction]);
					if(!$collaction -> save()){
						return $this->response->setJsonContent(['status' => false, 'message' => 'Please fill in your responses to go to the previous or next page.']);
					}
				}
				return $this->response->setJsonContent(['status' => true, 'Message' => 'Successful']);
			}
			
		}
	}
	
	public function sevechildinfo(){
		$input_data = $this->request->getJsonRawBody();
		$collaction = new ParentQuestionChildInfo();
		// $collaction -> id = $this ->parentgamesidgen->getNewId('parentAnswerStatus');
		$collaction->firstname = $input_data->firstname;
		$collaction->lastname = $input_data->lastname;
		$collaction->email = $input_data->email;
		$collaction->CaregiverType = $input_data->CaregiverType;
		$collaction->childfirstname = $input_data->childfirstname;
		$collaction->childlastname = $input_data->childlastname;
		$collaction->gender = $input_data->gender;
		$collaction->Grade = $input_data->Grade;
		$collaction->Height = $input_data->Height;
		$collaction->Weight = $input_data->Weight;
		$collaction->DoctorName = $input_data->DoctorName;
		$collaction->dob = $input_data->dob;
		if(!$collaction -> save()){
			return $this->response->setJsonContent(['status' => false, 'data' => $collaction, 'message' => 'Please fill in your responses to go to the next page.']);
		}
		else{
			return $this->response->setJsonContent(['status' => true, 'Message' => 'Successful']);
		}
	}
}
