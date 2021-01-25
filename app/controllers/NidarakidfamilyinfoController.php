<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Alpha;
class NidarakidfamilyinfoController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $kidfamily_view = NidaraKidFamilyInfo::find();
        if ($kidfamily_view):
            
	    return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidfamily_view
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
            $kidfamily_getby_id = NidaraKidFamilyInfo::findFirstBynidara_kid_profile_id($id);
            if ($kidfamily_getby_id):
                
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidfamily_getby_id
			]);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
            endif;
        endif;
    }
    /**
     * This function using to create NidaraKidFamilyInfo information
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
        $validation->add('nidara_kid_profile_id', new Digit(['message'=>'Kid profile id is only Digit']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
	    $kidfamilyexist = NidaraKidFamilyInfo::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $kidfamilyexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Family information already exist for this kid' 
				] );
	    }
            $kidfamily_create = new NidaraKidFamilyInfo();
            $kidfamily_create->id = $this->kididgen->getNewId("nidarakidfamilyinfo");
            $kidfamily_create->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $kidfamily_create->mother = $input_data->mother;
            $kidfamily_create->father = $input_data->father;
            $kidfamily_create->grandfather = $input_data->grandfather;
            $kidfamily_create->grandmother = $input_data->grandmother;
            $kidfamily_create->created_at = date('Y-m-d H:i:s');
            $kidfamily_create->created_by = 1;
            $kidfamily_create->modified_at = date('Y-m-d H:i:s');
            if ($kidfamily_create->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'Family information saved successfully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to NidaraKidFamilyInfo information edit
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
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
                $kidfamily_update = NidaraKidFamilyInfo::findFirstByid($id);
                if ($kidfamily_update):
                    $kidfamily_update->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
                    $kidfamily_update->mother = $input_data->mother;
                    $kidfamily_update->father = $input_data->father;
                    $kidfamily_update->grandfather = $input_data->grandfather;
                    $kidfamily_update->grandmother = $input_data->grandmother;
                    $kidfamily_update->created_by = $id;
                    $kidfamily_update->modified_at = date('Y-m-d H:i:s');
                    if ($kidfamily_update->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'Family information saved successfully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
                    endif;
                else:
                    return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid id']);
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
            $kidfamily_delete = NidaraKidFamilyInfo::findFirstByid($id);
            if ($kidfamily_delete):
                if ($kidfamily_delete->delete()):
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
