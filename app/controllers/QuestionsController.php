<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class QuestionsController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

/**
     * Fetch all Record from database :-
     */

    public function viewall() {
        $subject = Questions::find();
        if ($subject):
            return Json_encode($subject);
        else:
            return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Faield']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Invalid input parameter']);
        else:
            $collection = Questions::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create Questions information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('complexity_level', new PresenceOf(['message' => 'complexity_level is required']));
        $validation->add('name', new PresenceOf(['message' => 'name is required']));
        $validation->add('desc', new PresenceOf(['message' => 'desc is required']));
        $validation->add('tags', new PresenceOf(['message' => 'tags is required']));
        $validation->add('lessons_id', new PresenceOf(['message' => 'lessons_id is required']));
        $validation->add('created_at', new PresenceOf(['message' => 'created_at is required']));
        $validation->add('created_by', new PresenceOf(['message' => 'created_by is required']));
        $validation->add('modified_at', new PresenceOf(['message' => 'modified_at is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
            $collection = new Questions();
            $collection->id = $input_data->id;
            $collection->complexity_level = $input_data->complexity_level;
            $collection->name = $input_data->name;
            $collection->desc = $input_data->desc;
            $collection->tags = $input_data->tags;
            $collection->lessons_id = $input_data->lessons_id;
            $collection->created_at = $input_data->created_at;
            $collection->created_by = $input_data->created_by;
            $collection->modified_at = $input_data->modified_at;
            if ($collection->save()):
                return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to Questions information edit
     */
    public function update($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'idis required']));
            $validation->add('complexity_level', new PresenceOf(['message' => 'complexity_levelis required']));
            $validation->add('name', new PresenceOf(['message' => 'nameis required']));
            $validation->add('desc', new PresenceOf(['message' => 'descis required']));
            $validation->add('tags', new PresenceOf(['message' => 'tagsis required']));
            $validation->add('lessons_id', new PresenceOf(['message' => 'lessons_idis required']));
            $validation->add('created_at', new PresenceOf(['message' => 'created_atis required']));
            $validation->add('created_by', new PresenceOf(['message' => 'created_byis required']));
            $validation->add('modified_at', new PresenceOf(['message' => 'modified_atis required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $collection = Questions::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->complexity_level = $input_data->complexity_level;
                    $collection->name = $input_data->name;
                    $collection->desc = $input_data->desc;
                    $collection->tags = $input_data->tags;
                    $collection->lessons_id = $input_data->lessons_id;
                    $collection->created_at = $input_data->created_at;
                    $collection->created_by = $input_data->created_by;
                    $collection->modified_at = $input_data->modified_at;
                    if ($collection->save()):
                        return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
                    else:
                        return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
                    endif;
                else:
                    return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Invalid id']);
                endif;
            endif;
        endif;
    }

/**
     * This function using delete kids caregiver information
     */

    public function delete() {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
        else:
            $collection = Questions::findFirstByid($id);
            if ($collection):
                if ($collection->delete()):
                    return $this->response->setJsonContent(['status' => 'OK', 'Message' => 'Record has been deleted succefully ']);
                else:
                    return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data could not be deleted']);
                endif;
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }

    public function getbycategoryid(){
      $input_data = $this->request->getJsonRawBody();
      $category_id = isset($input_data->questions_category_id) ? $input_data->questions_category_id:'';
      if (empty($category_id)):
          return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
      else:
          $questions_category_id = Questions::find("questions_category_id=$category_id");
          if ($questions_category_id):
              return Json_encode($questions_category_id);
          else:
              return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
          endif;
      endif;

    }
	/**
	 * @param category name
	 * @return array
	 */
	public function getprenidaraquestions() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the nidara kid profile id' 
			] );
		}
		$category_name = isset ( $input_data->category_name ) ? $input_data->category_name : '';
		if (empty ( $category_name )) {
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Invalid input parameter' 
			]);
		} else {
			$questionscategory = QuestionsCategory::findFirstBycategory_name ($category_name);
			if (empty ( $questionscategory )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Question category is invalid' 
				] );
			}
			$category_id = $questionscategory->id;
			$question_category = Questions::find ( "questions_category_id=$category_id" );
			if ($question_category) {
				$result = array ();
				foreach ( $question_category as $key => $value ) {
					$option = Options::find ( "questions_id=$value->id" );
					$questions = $value->toArray ();
					$questions ['options'] = $option->toArray ();
					$answers=$this->getKidAnswers($value->id,$input_data->nidara_kid_profile_id);
					if (! empty ( $answers )) {
						$questions['answers']=$answers;
					}
					$result [] = $questions;
				}
				return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$result
				]);
			} else {
				return $this->response->setJsonContent ([ 
						'status' => false,
						'message' => 'Data not found' 
				]);
			}
		}
	}
	
        /**
	 * Get kid submitted answer
	 * @param integer $questions_id
	 * @param integer $nidara_kid_profile_id
	 * @return array
	 */
    public function getKidAnswers($questions_id,$nidara_kid_profile_id){
		$answers = $this->modelsManager->createBuilder ()->columns ( array (
				'Answers.id as answer_id',
				'Answers.questions_id',
				'Answers.options_id as options_id',
				'Options.option as option_name',
		) )->from ( 'Answers' )
		->leftjoin ( 'Questions', 'Questions.id=Answers.questions_id' )
		->leftjoin ( 'Options', 'Answers.options_id=Options.id' )
		->leftjoin ( 'QuestionsCategory', 'Questions.questions_category_id=QuestionsCategory.id' )
		->inwhere ( "Questions.id", array (
				$questions_id
		) )
		->inwhere ( "Answers.nidara_kid_profile_id", array (
				$nidara_kid_profile_id
		) )->getQuery ()->execute ();
		$kidanswers=array();
		foreach($answers as $answer){
				$kidanswers["answer_id"] = $answer->answer_id;
				$kidanswers["option_id"] = $answer->options_id;
				$kidanswers["option_name"] = $answer->option_name;
				$kidanswers["question_id"] = $answer->questions_id;
			}
		if(empty($kidanswers)){
			$kidanswers["question_id"] = $questions_id;
			$kidanswers["option_id"] = 0;
			$kidanswers["option_name"] = "";
		}
		return $kidanswers;
	}
}
