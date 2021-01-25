<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class AnswersController extends \Phalcon\Mvc\Controller {

    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $answers_view = Answers::find();
        if ($answers_view):
            return Json_encode($answers_view);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Failed']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid() {

        $input_data = $this->request->getJsonRawBody();
	$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		$baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl);
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Invalid User" 
			] );
		}
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
        else:
            $answers_getby_id = Answers::findFirstByid($id);
            if ($answers_getby_id):
                return Json_encode($answers_getby_id);
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create Answers information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();
	$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		$baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl);
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Invalid User" 
			] );
		}
        $question_option=$input_data->questionarie;
	if(empty($question_option)){
	return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please select the options" 
			] );
	}
        /**
         * This object using valitaion
         */
        $validation = new Validation();
        $validation->add('session_id', new PresenceOf(['message' => 'Session id is required']));
        $validation->add('is_correct', new PresenceOf(['message' => 'Is_correct is required']));
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara Kid profile id is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):

            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;

            return $this->response->setJsonContent($result);
        else:
           foreach ( $question_option as $key => $value ) {
				if(!empty($value->options_id)){
				$questions = new QuestionsController ();
				$answersexist = $questions->getKidAnswers ( $value->questions_id, $input_data->nidara_kid_profile_id );
				if (empty ( $answersexist['answer_id'] )) {
					$answers = new Answers ();
					$answers->id = $this->questionsidgen->getNewId ( "answers" );
					$answers->session_id = $input_data->session_id;
					$answers->is_correct = $input_data->is_correct;
					$answers->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$answers->created_at = date ( 'Y-m-d H:i:s' );
					$answers->created_by = 1;
				} else {
					$answers = Answers::findFirstByid ( $answersexist ['answer_id'] );
				}
				$answers->questions_id = $value->questions_id;
				$answers->options_id = $value->options_id;
				$answers->modified_at = date ( 'Y-m-d H:i:s' );
				if (! $answers->save ()) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Failed' 
					] );
				}
			   }
			}
          return $this->response->setJsonContent(['status' => true, 'message' => 'saved successfully']);

        endif;

    }

    /**
     * This function using to Answers information edit
     */
    public function update() {

        $input_data = $this->request->getJsonRawBody();
	$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the token" 
			] );
		}
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		$baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl);
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Invalid User" 
			] );
		}
        $question_option=$input_data->questionarie;
        /**
         * This object using valitaion
         */
        $validation = new Validation();
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara Kid profile id is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):

            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;

            return $this->response->setJsonContent($result);
        else:
          $i=1;
          foreach ($question_option as $key => $value) {
            $answers_create =Answers::findFirstBy(array());
            $answers_create->questions_id =$value->questions_id;
            $answers_create->options_id = $value->options_id;
            $answers_create->modified_at =date('Y-m-d H:i:s');
            if (!$answers_create->save()){
                return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
            }
            $i++;
          }
          return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);

        endif;

    }

    /**
     * This function using delete kids caregiver information
     */
    public function delete() {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $answers_delete = Answers::findFirstByid($id);
            if ($answers_delete):
                if ($answers_delete->delete()):
                    return $this->response->setJsonContent(['status' => true, 'Message' => 'Record has been deleted succefully ']);
                else:
                    return $this->response->setJsonContent(['status' => false, 'Message' => 'Data could not be deleted']);
                endif;
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }
	
	public function setguidedlearning(){
		$input_data = $this->request->getJsonRawBody ();
		$kid_id = isset ( $input_data->id ) ? $input_data->id : '';
		if(empty($kid_id)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the kid id' 
			] );
		}
		else{
			$datevalue = ((20*($input_data->month -1))+(5*($input_data->week -1))+$input_data->day);
			$time = date("Y-m-d",strtotime('-2days'));
			$collaction_id = DailyRoutineAttendance::findBynidara_kid_profile_id($kid_id);
			if(!empty($collaction_id)){
				$collaction_id->delete();
				for($i = 1;$i < $datevalue;$i++){
					$collaction = new DailyRoutineAttendance();
					$collaction ->id = $this->dailyroutineidgen->getNewId ( "dailyroutineAttendance" );
					$collaction ->task_name = 'nidarachildrensession';
					$collaction ->start_time = date('H:i');
					$collaction ->nidara_kid_profile_id = $kid_id;
					$collaction ->attendanceDate = $time;
					if(!$collaction->save()){
						return $this->response->setJsonContent([
							'status' => false,
							'message' => 'data cont save in created_at'
							
						]);
					}
				}
				return $this->response->setJsonContent([
							'status' => true,
							'message' => 'Activated successfully'
					]);
				
			}
			else{
				$i = 0;
				for($i = 1;$i < $datevalue;$i++){
					$collaction = new DailyRoutineAttendance();
					$collaction ->id = $this->dailyroutineidgen->getNewId ( "dailyroutineAttendance" );
					$collaction ->task_name = 'nidarachildrensession';
					$collaction ->start_time = date('H:i');
					$collaction ->nidara_kid_profile_id = $kid_id;
					$collaction ->attendanceDate = $time;
					if(!$collaction->save()){
						return $this->response->setJsonContent([
							'status' => false,
							'message' => 'data cont save in created_at'
							
						]);
					}
				}
				return $this->response->setJsonContent([
							'status' => true,
							'message' => 'Activated successfully'
					]);
				
			}
			
			/* $collection = NidaraKidProfile::findFirstByid($kid_id);
			if(!empty($collection)){
				$collection->created_at = $time;
				if(!$collection->save()){
					return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'data cont save in created_at' 
				] );
				}
				else{
					return $this->response->setJsonContent ([ 
						'status' => true,
						"message" => 'save succefully',
						$collection->created_at
				]);
				}
				
			}
			else{
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'data cont save' 
				] );
			} */
		}
	}

}
