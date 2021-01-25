<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class GuidedlearninggamesmapController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = GuidedLearningGamesMap::find();
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
            $collection = GuidedLearningGamesMap::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create GuidedLearningGamesMap information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('guided_learning_schedule_id', new PresenceOf(['message' => 'guided_learning_schedule_id is required']));
        $validation->add('games_tagging_id', new PresenceOf(['message' => 'games_tagging_id is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
            $collection = new GuidedLearningGamesMap();
            $collection->id = $input_data->id;
            $collection->guided_learning_schedule_id = $input_data->guided_learning_schedule_id;
            $collection->games_tagging_id = $input_data->games_tagging_id;
            if ($collection->save()):
                return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
	 * This function using to GuidedLearningSchedule information edit
	 */
	public function update() {
		$input_data = $this->request->getJsonRawBody ();
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please give the input datas" 
			] );
		}
		else {
			 $guidedlearning_create = $input_data -> dayGamaGroup;
			 
			 foreach($guidedlearning_create as $value){
				 $collection = GuidedLearningDayGameMap::findFirstByid ($value->id);
				 if(!$collection){
					 $collection = new GuidedLearningDayGameMap ();
					 $collection->id = $this->guidedlearningidgen->getNewId ("guided_learning_gmae");
					 $collection->day_id = $input_data->day_id;
					 $collection->day_guided_learning_id = $input_data->grade_id;
					 $collection->framework_id = $value->framework_id;
					 $collection->subject_id = $value->subject_id;
					 $collection->game_id = $value->game_id;
					 $collection->created_at = date ( 'Y-m-d H:i:s' );
				 }
				 $collection->framework_id = $value->framework_id;
				 $collection->subject_id = $value->subject_id;
				 $collection->game_id = $value->game_id;
				 if(!$collection->save()){
					 return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Failed' 
					] );
				 }
		 }
		 return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		 }
		
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
            $collection = GuidedLearningGamesMap::findFirstByid($id);
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

}
