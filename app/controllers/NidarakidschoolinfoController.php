<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Date;
use Phalcon\Validation\Validator\PresenceOf;

class NidarakidschoolinfoController extends \Phalcon\Mvc\Controller {

    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $kidschoolinfo_view = NidaraKidSchoolInfo::find();
        if ($kidschoolinfo_view):
            
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidschoolinfo_view
			]);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
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
            $kidschoolinfo_getbyid = NidaraKidSchoolInfo::findFirstBynidara_kid_profile_id($id);
            if ($kidschoolinfo_getbyid):
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidschoolinfo_getbyid
			]);
                
            else:
		$data['school_name']="";
		$data['address2']="";
		$data['town_city']="";
		$data['state']="";	
		$data['country']="";
		$data['school_type']="";
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>$data]);
            endif;
        endif;
    }

    /**
     * This function using to create NidaraKidSchoolInfo information
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
        $validation->add('nidara_kid_profile_id',new PresenceOf(['message' => 'Nidara kid profile id is required']));
        $validation->add('school_name', new PresenceOf(['message' => 'School name is required']));
        $validation->add('school_name', new Alpha(['message'=>'School Name must contain only letters']));
        $validation->add('school_type', new PresenceOf(['message' => 'School type is required']));
        $validation->add('address2', new PresenceOf(['message' => 'Address 2 is required']));
        $validation->add('town_city', new PresenceOf(['message' => 'Town city is required']));
        $validation->add('state', new PresenceOf(['message' => 'State is required']));
        $validation->add('country', new PresenceOf(['message' => 'Country is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
	    $kidschoolinfoexist = NidaraKidSchoolInfo::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $kidschoolinfoexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'School information already exist for this kid' 
				] );
	    }
	    $kidschoolinfo = new NidaraKidSchoolInfo ();
	    $kidschoolinfo->id = $input_data->id;
	    $kidschoolinfo->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $kidschoolinfo->school_name = $input_data->school_name;
            $kidschoolinfo->school_type = $input_data->school_type;
            $kidschoolinfo->address2 = $input_data->address2;
            $kidschoolinfo->town_city = $input_data->town_city;
            $kidschoolinfo->state = $input_data->state;
            $kidschoolinfo->country = $input_data->country;
            $kidschoolinfo->created_at =date('Y-m-d H:i:s');
            $kidschoolinfo->created_by = 1;
            if ($kidschoolinfo->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'school information created successfully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to NidaraKidSchoolInfo information edit
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
            $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Nidara kid profile id is required']));
            $validation->add('school_name', new PresenceOf(['message' => 'School name is required']));
            $validation->add('school_type', new PresenceOf(['message' => 'School type is required']));
            $validation->add('address2', new PresenceOf(['message' => 'Address 2 is required']));
            $validation->add('town_city', new PresenceOf(['message' => 'Town city is required']));
            $validation->add('state', new PresenceOf(['message' => 'State is required']));
            $validation->add('country', new PresenceOf(['message' => 'Country is required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $kidschoolinfo = NidaraKidSchoolInfo::findFirstBynidara_kid_profile_id($id);
		if(empty($kidschoolinfo)){
			$kidschoolinfo = new NidaraKidSchoolInfo ();
			$kidschoolinfo->id = $this->kididgen->getNewId("nidarakidschoolinfo");
		}
                    $kidschoolinfo->school_name = $input_data->school_name;
                    $kidschoolinfo->school_type = $input_data->school_type;
                    $kidschoolinfo->address2 = $input_data->address2;
                    $kidschoolinfo->town_city = $input_data->town_city;
                    $kidschoolinfo->state = $input_data->state;
                    $kidschoolinfo->country = $input_data->country;
		    $kidschoolinfo->nidara_kid_profile_id=$input_data->nidara_kid_profile_id;
                    $kidschoolinfo->modified_at = date('Y-m-d H:i:s');
                    if ($kidschoolinfo->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'saved successfully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
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
            $kidschoolinfo_delete = NidaraKidSchoolInfo::findFirstByid($id);
            if ($kidschoolinfo_delete):
                if ($kidschoolinfo_delete->delete()):
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
