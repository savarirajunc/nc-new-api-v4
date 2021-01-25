<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
class NidarakidphysicalinfoController extends \Phalcon\Mvc\Controller {

    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $kidphysical_view = NidaraKidPhysicalInfo::find();
        if ($kidphysical_view):
            

		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidphysical_view
			]);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid(){

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
            return $this->response->setJsonContent(['status' => false,'message' => 'Please select the kid to update the information']);
        else:
            $kidphysical_getbyid = NidaraKidPhysicalInfo::findFirstBynidara_kid_profile_id($id);
            if ($kidphysical_getbyid):
			
                return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidphysical_getbyid
			]);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
            endif;
        endif;
    }

    /**
     * This function using to create NidaraKidPhysicalInfo information
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
        $validation->add('height', new PresenceOf(['message' => 'Height is required']));
        $validation->add('height', new Digit(['message'=>'Height is only Digit']));
        $validation->add('weight', new PresenceOf(['message' => 'Weight is required']));
        $validation->add('weight', new Digit(['message'=>'Weight is only Digit']));
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
	    if($input_data->height == 0){
		return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please enter the valid height" 
			] );
	    }elseif($input_data->weight == 0){
		return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please enter the valid weight" 
			] );
	    }
	    $kidphysicalexist = NidaraKidPhysicalInfo::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $kidphysicalexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Physical information already exist for this kid' 
				] );
	    }
            $kidphysical_create = new NidaraKidPhysicalInfo();
            $kidphysical_create->id = $this->kididgen->getNewId("nidarakidphysicalinfo");
            $kidphysical_create->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $kidphysical_create->height = $input_data->height;
            $kidphysical_create->weight = $input_data->weight;
            $kidphysical_create->created_at =date('Y-m-d H:i:s');
            $kidphysical_create->created_by = 1;
            if ($kidphysical_create->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'Physical information saved successfully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to NidaraKidPhysicalInfo information edit
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
	    if($input_data->height == 0){
		return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please enter the valid height" 
			] );
	    }elseif($input_data->weight == 0){
		return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please enter the valid weight" 
			] );
	    }
            $validation = new Validation();
            $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid profile id is required']));
            $validation->add('nidara_kid_profile_id', new Digit(['message'=>'Kid profile id is only Digit']));
            $validation->add('height', new PresenceOf(['message' => 'Height is required']));
            $validation->add('height', new Digit(['message'=>'Height is only Digit']));
            $validation->add('weight', new PresenceOf(['message' => 'Weight is required']));
            $validation->add('weight', new Digit(['message'=>'Weight is only Digit']));
	    
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
		if($input_data->height == 0){
		return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please enter the valid height" 
			] );
	    }elseif($input_data->weight == 0){
		return $this->response->setJsonContent ( [ 
					"status" => false,
					"message" => "Please enter the valid weight" 
			] );
	    }
                $kidphysical_update = NidaraKidPhysicalInfo::findFirstByid($id);
                if ($kidphysical_update):
                    $kidphysical_update->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
                    $kidphysical_update->height = $input_data->height;
                    $kidphysical_update->weight = $input_data->weight;
                    $kidphysical_update->modified_at = date('Y-m-d H:i:s');
                    if ($kidphysical_update->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'Physical information saved successfully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
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
            $kidphysical_delete = NidaraKidPhysicalInfo::findFirstByid($id);
            if ($kidphysical_delete):
                if ($kidphysical_delete->delete()):
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
