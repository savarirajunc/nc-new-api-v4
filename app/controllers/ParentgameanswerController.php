<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ParentgameanswerController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = ParentGamesAnswer::find();
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
            $collection = ParentGamesAnswer::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
            endif;
        endif;
    }

    public function create(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation();
        /* $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('game_id', new PresenceOf(['message' => 'game_id is required']));
        $validation->add('status', new PresenceOf(['message' => 'status is required']));
        $validation->add('created_at', new PresenceOf(['message' => 'created_at is required']));
        $validation->add('created_by', new PresenceOf(['message' => 'created_by is required']));
        $validation->add('modified_at', new PresenceOf(['message' => 'modified_at is required'])); */
        $messages = $validation->validate($input_data);
		if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
		else{
			$parentquesanswer = $input_data->parentGameQuestionAnswer;
			foreach($parentquesanswer as $value){
				$collection = ParentGamesAnswer::findFirstBygame_id($value->game_id);
				if(!$collection){
					$collection = new ParentGamesAnswer();
					$collection ->id = $this ->parentgamesidgen->getNewId('parentGameAnswer');
					$collection ->game_id = $value->game_id;
				}
				$collection ->answer = $value->answervalue;
				$collection ->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
				$collection ->created_at = date ( 'Y-m-d H:i:s' );
				if(!$collection -> save()){
					return $this->response->setJsonContent(['status' => false, 'Message' => 'Failed']);
				}
			}
			$collection = new ParentGameAnswerStatus();
			$collection -> id = $this ->parentgamesidgen->getNewId('parentGameAnswerStatus');
			$collection -> day_id = $input_data->day_id;
			$collection -> nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
			$collection -> status = "Completed";
			$collection -> created_at = date ( 'Y-m-d H:i:s' );
			$collection -> save ();
				return $this->response->setJsonContent(['status' => true, 'Message' => 'Successful']);
		}
	}
}
