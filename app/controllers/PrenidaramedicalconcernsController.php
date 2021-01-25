<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class PrenidaramedicalconcernsController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

/**
     * Fetch all Record from database :-
     */

    public function viewall() {
        $medical = PreNidaraMedicalConcerns::find();
        if ($medical):
            
	    return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$medical
			]);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid($id = null) {

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
        $id = isset($input_data->nidara_kid_profile_id) ? $input_data->nidara_kid_profile_id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
        else:
            $medical = PreNidaraMedicalConcerns::findFirstBynidara_kid_profile_id($id);
            if ($medical):
                return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$medical
			]);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
            endif;
        endif;
    }

    /**
     * This function using to create PreNidaraMedicalConcerns information
     */
    public function create() {
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
        $input_data = $this->request->getJsonRawBody();
        if (empty($input_data)) {
            return $this->response->setJsonContent(["status" => false, "message" => "Please give the input datas"]);
        }


        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('medical_concern', new PresenceOf(['message' => 'medical concern is required']));
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid rofile id is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
	    $medicalexist = PreNidaraMedicalConcerns::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $medicalexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'medical concern already exist for this kid' 
				] );
	    }
            $medical = new PreNidaraMedicalConcerns();
            $medical->id = $this->questionsidgen->getNewId("medicalconcern");
            $medical->medical_concern = $input_data->medical_concern;
            $medical->status = 1;
            $medical->created_at = date('Y-m-d H:i:s');
            $medical->created_by = 1;
            $medical->modified_at = date('Y-m-d H:i:s');
            $medical->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            if ($medical->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'medical concerns saved successfully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Cannot save medical concerns']);
            endif;
        endif;
    }

    /**
     * This function using to PreNidaraMedicalConcerns information edit
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
        $id = isset($input_data->nidara_kid_profile_id) ? $input_data->nidara_kid_profile_id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();

            $validation->add('medical_concern', new PresenceOf(['message' => 'medical concern is required']));
            $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid rofile id is required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
		return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
            else:
                $medical = PreNidaraMedicalConcerns::findFirstBynidara_kid_profile_id($input_data->nidara_kid_profile_id);
                if ($medical):
                    $medical->medical_concern = $input_data->medical_concern;
                    $medical->modified_at = date('Y-m-d H:i:s');
                    if ($medical->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'medical concerns saved successfully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Cannot save medical concerns']);
                    endif;
                else:
                    return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid id']);
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
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $medical = PreNidaraMedicalConcerns::findFirstByid($id);
            if ($medical):
                if ($medical->delete()):
                    return $this->response->setJsonContent(['status' => true, 'Message' => 'Record has been deleted successfully ']);
                else:
                    return $this->response->setJsonContent(['status' => false, 'Message' => 'Data could not be deleted']);
                endif;
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }
   public function getbynidarakidprofileid() {
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
          $input_data = $this->request->getJsonRawBody();
           $nidara_kid_profile_id = isset($input_data->nidara_kid_profile_id) ? $input_data->nidara_kid_profile_id : '';
            if (empty($nidara_kid_profile_id)):
                 return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
             else:
                 $medical = PreNidaraMedicalConcerns::findFirstBynidara_kid_profile_id($nidara_kid_profile_id);
                   if ($medical):
				  return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$medical
			        ]);
                               else:
                                   return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
                               endif;
                           endif;
     }
}
