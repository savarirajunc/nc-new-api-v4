<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class PrenidaraquestionnaireController extends \Phalcon\Mvc\Controller {

    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $answers_view = Answers::find();
        if ($answers_view):
	
	    return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$answers_view
			]);
            
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Failed']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid() {
		$input_data = $this->request->getJsonRawBody ();
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
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			] );
		}
		if (empty ( $input_data->category_name )) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Please give the category name'
			] );
		}
		$category = QuestionsCategory::findFirstBycategory_name ( $input_data->category_name );
		 $answers = $this->modelsManager->createBuilder ()->columns ( array (
		 		'Answers.id as answer_id',
		 		'Answers.questions_id',
		 		'Answers.options_id as options_id',
				'Options.option as option_name',
		 ) )->from ( 'Answers' )
		 ->leftjoin ( 'Questions', 'Questions.id=Answers.questions_id' )
		 ->leftjoin ( 'Options', 'Answers.options_id=Options.id' )
		 ->leftjoin ( 'QuestionsCategory', 'Questions.questions_category_id=QuestionsCategory.id' )
		 ->inwhere ( "QuestionsCategory.id", array (
		 		$category->id
		 ) )
		  ->inwhere ( "Answers.nidara_kid_profile_id", array (
		 		$input_data->nidara_kid_profile_id
		 ) )->groupby("Questions.id")->getQuery ()->execute ();
			if ($answers) :
				return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$answers
			]);
				
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Data not found' 
				] );
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
        /**
         * This object using valitaion
         */
        $validation = new Validation();
        //$validation->add('questions_id', new PresenceOf(['message' => 'Questions id is required']));
        $validation->add('session_id', new PresenceOf(['message' => 'Session id is required']));
        $validation->add('is_correct', new PresenceOf(['message' => 'Is_correct is required']));
        //$validation->add('options_id', new PresenceOf(['message' => 'options id is required']));
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara Kid profile id is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):

            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;

            return $this->response->setJsonContent($result);
        else:
         foreach ( $question_option as $key => $value ) {
				$questions = new QuestionsController ();
				$answersexist = $questions->getKidAnswers ( $value->questions_id, $input_data->nidara_kid_profile_id );
				if (empty ( $answersexist )) {
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
          return $this->response->setJsonContent(['status' => true, 'message' => 'successfully']);

        endif;

    }

    /**
     * This function using to Answers information edit
     */
    public function update() {
		$input_data = $this->request->getJsonRawBody ();
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
		$question_option = $input_data->questionarie;
		/**
		 * This object using valitaion
		 */
		$validation = new Validation ();
		$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
				'message' => 'Nidara Kid profile id is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) :
			
			foreach ( $messages as $message ) :
				$result [] = $message->getMessage ();
			endforeach
			;
			
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => $result
			]);  
			//return $this->response->setJsonContent ( $result );
		 else :
			foreach ( $question_option as $key => $value ) {
				$answers = Answers::findFirstByquestions_id ($value->questions_id);
				if(empty($answers)){
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Please give the valid answer' 
					] );
				}
				$answers->options_id = $value->options_id;
				$answers->modified_at = date ( 'Y-m-d H:i:s' );
				if (! $answers->save ()) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Failed' 
					] );
				}
			}
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'successfully' 
			] );
		

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

}
