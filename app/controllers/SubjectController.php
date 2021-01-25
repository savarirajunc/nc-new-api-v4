<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class SubjectController extends \Phalcon\Mvc\Controller {

	
	/**
	 * Fetch all Record from database :-
	 */
	public function viewall() {
		try {
			$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Please give the token"
				] );
			}
			/* $baseurl = $this->config->baseurl;
			$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
			if ($token_check->status != 1) {
				return $this->response->setJsonContent ( [
						"status" => false,
						"message" => "Invalid User"
				] );
			} */
			$data = Subject::find ();
			if ($data) :
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'data' => $data 
				] );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'There is no data' 
				] );
			endif;
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while getting datas.'
        	] );
        }
    }
	
	public function getsubjectdata(){
		$data = $this->modelsManager->createBuilder ()->columns ( array(
		'Subject.*'
		))->from('Subject')
		->getQuery ()->execute ();
		$subject_array = array();
		foreach($data as $value){
			$subject_value['id'] = $value->id;
			if (strpos($value->subject_name, "Education") !== false) {
				$subject_value['subject_name'] = str_replace("Core Education - ","",$value->subject_name);
			}else if (strpos($value->subject_name, "Interest") !== false) {
				$subject_value['subject_name'] = str_replace("Core Interest Dev - ","",$value->subject_name);
			}else if (strpos($value->subject_name, "Health") !== false) {
				$subject_value['subject_name'] = str_replace("Core Health - ","",$value->subject_name);
			}
			$subject_value['core_type'] = $value->core_type;
			$subject_array[] = $subject_value;
		}
		return $this->response->setJsonContent ( [
    		"status" => true,
    		"data" => $subject_array
    	] );
	}
    
   	 /**
     * Fetch all Record from database :-
     */
    public function subjectview() {
    	try {
    		$headers = $this->request->getHeaders ();
    		if (empty ( $headers ['Token'] )) {
    			return $this->response->setJsonContent ( [
    					"status" => false,
    					"message" => "Please give the token"
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
    		$input_data = $this->request->getJsonRawBody ();
		if (empty ( $input_data->kid_id )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the kid"
			] );
		}
    	$data = $this->modelsManager->createBuilder ()
    		->columns ( array('Subject.*'))
			->from ( 'GuidedLearningGamesMap')
			->join ( 'GamesTagging', 'GamesTagging.id=GuidedLearningGamesMap.games_tagging_id' )
			->join ( 'StandardIndicatorsMap', 'StandardIndicatorsMap.indicators_id=GamesTagging.indicators_id' )
			->join ( 'StandardSubject', 'StandardSubject.standard_id=StandardIndicatorsMap.standard_id' )
			->join ( 'Subject', 'Subject.id=StandardSubject.subject_id' )
			->leftjoin ( 'GuidedLearningSchedule', 'GuidedLearningGamesMap.guided_learning_schedule_id=GuidedLearningSchedule.id' )
			->leftjoin ( 'KidGuidedLearningMap', 'GuidedLearningSchedule.guided_learning_id=KidGuidedLearningMap.guided_learning_id')
			->groupBy ( 'Subject.id' )
			->inWhere ( 'KidGuidedLearningMap.nidara_kid_profile_id', array (
						$input_data->kid_id 
			) )->getQuery ()->execute ();
		if ($data) :
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $data 
			] );
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => 'false',
					'Message' => 'Faield' 
			] );
		endif;
    	} catch ( Exception $e ) {
    		return $this->response->setJsonContent ( [
    				'status' => false,
    				'message' => 'Error while getting datas.'
    		] );
    	}
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
            $collection = Subject::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create Subject information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('subject_name', new PresenceOf(['message' => 'subject_name is required']));
        $validation->add('description', new PresenceOf(['message' => 'description is required']));
        $validation->add('status', new PresenceOf(['message' => 'status is required']));
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
            $collection = new Subject();
            $collection->id = $input_data->id;
            $collection->subject_name = $input_data->subject_name;
            $collection->description = $input_data->description;
            $collection->status = $input_data->status;
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
     * This function using to Subject information edit
     */
    public function update($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'idis required']));
            $validation->add('subject_name', new PresenceOf(['message' => 'subject_nameis required']));
            $validation->add('description', new PresenceOf(['message' => 'descriptionis required']));
            $validation->add('status', new PresenceOf(['message' => 'statusis required']));
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
                $collection = Subject::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->subject_name = $input_data->subject_name;
                    $collection->description = $input_data->description;
                    $collection->status = $input_data->status;
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
            $collection = Subject::findFirstByid($id);
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
