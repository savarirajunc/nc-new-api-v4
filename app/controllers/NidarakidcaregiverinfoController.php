<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Numericality;
class NidarakidcaregiverinfoController extends \Phalcon\Mvc\Controller {
    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $kidcaregiver_view = NidaraKidCaregiverInfo::find();
        if ($kidcaregiver_view):

		
	return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidcaregiver_view
			]);
			
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
        $id = isset($input_data->nidara_kid_profile_id) ? $input_data->nidara_kid_profile_id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Please select the kid to update the information']);
        else:
            $kidcaregiver_getby_id = NidaraKidCaregiverInfo::findFirstBynidara_kid_profile_id($id);
            if ($kidcaregiver_getby_id):
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidcaregiver_getby_id
			]);
                
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
            endif;
        endif;
    }

    /**
     * This function using to create NidaraKidCaregiverInfo information
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
        /**
         * This object using valitaion
         */
        $validation = new Validation();
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid profile id is required']));
        $validation->add('nidara_kid_profile_id', new Digit(['message'=>'Nidara kid profile id is only Digit']));
        $validation->add('name', new PresenceOf(['message' => 'Name is required']));
        $validation->add('name', new Alpha(['message'=>'Name is only letters']));
        $validation->add('name', new StringLength(['max'=>20,'min'=> 2,'messageMaximum' => 'Name is maximum 20',
            'messageMinimum' => 'Name is minimum 2']));
        $validation->add('relationship_to_child', new PresenceOf(['message' => 'Relationship to child is required']));
        $validation->add('amount_of_time_spent_with_child', new PresenceOf(['message' => 'Amount of time spent with child is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
		return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => $result
				] );
        else:
	     $kidcaregiverexist = NidaraKidCaregiverInfo::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $kidcaregiverexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Caregiver information already exist for this kid' 
				] );
	    }
            $kidcaregiver_create = new NidaraKidCaregiverInfo();
            $kidcaregiver_create->id = $this->kididgen->getNewId("nidarakidcaregiverinfo");
            $kidcaregiver_create->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $kidcaregiver_create->name = $input_data->name;
            $kidcaregiver_create->relationship_to_child = $input_data->relationship_to_child;
            $kidcaregiver_create->amount_of_time_spent_with_child = $input_data->amount_of_time_spent_with_child;
            $kidcaregiver_create->created_at = date('Y-m-d H:i:s');
            $kidcaregiver_create->created_by = 1;
            if ($kidcaregiver_create->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'Caregiver information saved successfully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Cannot save Caregiver information']);
            endif;
        endif;
    }

    /**
     * This function using to NidaraKidCaregiverInfo information edit
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
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' =>false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid profile id is required']));
            $validation->add('nidara_kid_profile_id', new Digit(['message'=>'Nidara kid profile id is only Digit']));
            $validation->add('name', new PresenceOf(['message' => 'Name is required']));
            $validation->add('name', new Alpha(['message'=>'Name is only letters']));
            $validation->add('name', new StringLength(['max'=>20,'min'=> 2,'messageMaximum' => 'Name is maximum 20',
                'messageMinimum' => 'Name is minimum 2']));
            $validation->add('relationship_to_child', new PresenceOf(['message' => 'Relationship to child is required']));
            $validation->add('amount_of_time_spent_with_child', new PresenceOf(['message' => 'Amount of time spent with child is required']));
	    $validation->add('amount_of_time_spent_with_child', new Numericality(['message'=>'Amount of time should be numeric']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => $result
				] );
            else:
                $kidcaregiver_update = NidaraKidCaregiverInfo::findFirstByid($id);
                if ($kidcaregiver_update):
                    $kidcaregiver_update->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
                    $kidcaregiver_update->name = $input_data->name;
                    $kidcaregiver_update->relationship_to_child = $input_data->relationship_to_child;
                    $kidcaregiver_update->amount_of_time_spent_with_child = $input_data->amount_of_time_spent_with_child;
                    $kidcaregiver_update->created_by = 1;
                    $kidcaregiver_update->modified_at = date('Y-m-d H:i:s');
                    if ($kidcaregiver_update->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'Caregiver information saved successfully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Cannot save caregiver information']);
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
            $kidcaregiver_delete = NidaraKidCaregiverInfo::findFirstByid($id);
            if ($kidcaregiver_delete):
                if ($kidcaregiver_delete->delete()):
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
