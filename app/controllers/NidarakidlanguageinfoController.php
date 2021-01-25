<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;

class NidarakidlanguageinfoController extends \Phalcon\Mvc\Controller {
    public function index() {

    }
    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $kidlanguage_view = NidaraKidLanguageInfo::find();
        if ($kidlanguage_view):
		
	    return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidlanguage_view
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
            $kidlanguage_getbyid = NidaraKidLanguageInfo::findFirstBynidara_kid_profile_id($id);
            if ($kidlanguage_getbyid):
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidlanguage_getbyid
			]);
                
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
            endif;
        endif;
    }

    /**
     * This function using to create NidaraKidLanguageInfo information
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
        $validation->add('language', new PresenceOf(['message' => 'Language is required']));
        $validation->add('language', new Alpha(['message'=>'Language is only letters']));
        $validation->add('language', new StringLength(['max'=>20,'min'=> 2,'messageMaximum' => 'Language is maximum 20',
            'messageMinimum' => 'Language is minimum 2']));
        $validation->add('location', new PresenceOf(['message' => 'Location is required']));
        $validation->add('location', new Alpha(['message'=>'Location is only letters']));
        //$validation->add('child_understand_english', new PresenceOf(['message' => 'Child understand english is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
	     $kidlanguageexist = NidaraKidLanguageInfo::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $kidlanguageexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Language information already exist for this kid' 
				] );
	    }
            $kidlanguage_create = new NidaraKidLanguageInfo();
            $kidlanguage_create->id =$this->kididgen->getNewId("nidarakidlanguageinfo");
            $kidlanguage_create->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $kidlanguage_create->language = $input_data->language;
            $kidlanguage_create->location = $input_data->location;
            $kidlanguage_create->child_understand_english = 1;
            $kidlanguage_create->created_at = date('Y-m-d H:i:s');
            $kidlanguage_create->created_by = 1;
            if ($kidlanguage_create->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'Language information updated succefully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Cannot update the language information']);
            endif;
        endif;
    }

    /**
     * This function using to NidaraKidLanguageInfo information edit
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
            $validation = new Validation();
            $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid profile id is required']));
            $validation->add('nidara_kid_profile_id', new Digit(['message'=>'Kid profile id is only Digit']));
            $validation->add('language', new PresenceOf(['message' => 'Language is required']));
            $validation->add('language', new Alpha(['message'=>'Language is only letters']));
            $validation->add('language', new StringLength(['max'=>20,'min'=> 2,'messageMaximum' => 'Language is maximum 20',
                'messageMinimum' => 'Language is minimum 2']));
            $validation->add('location', new PresenceOf(['message' => 'Location is required']));
            $validation->add('location', new Alpha(['message'=>'Location is only letters']));
            $validation->add('child_understand_english', new PresenceOf(['message' => 'Child understand english is required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $kidlanguage_update = NidaraKidLanguageInfo::findFirstByid($id);
                if ($kidlanguage_update):
                    $kidlanguage_update->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
                    $kidlanguage_update->language = $input_data->language;
                    $kidlanguage_update->location = $input_data->location;
                    $kidlanguage_update->child_understand_english = $input_data->child_understand_english;
                    $kidlanguage_update->modified_at = date('Y-m-d H:i:s');
                    if ($kidlanguage_update->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'Language information updated succefully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Cannot update the language information']);
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
            $kidlanguage_delete = NidaraKidLanguageInfo::findFirstByid($id);
            if ($kidlanguage_delete):
                if ($kidlanguage_delete->delete()):
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
