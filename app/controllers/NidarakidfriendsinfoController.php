<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;

class NidarakidfriendsinfoController extends \Phalcon\Mvc\Controller {

    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $kidfriends_view = NidaraKidFriendsInfo::find();
        if ($kidfriends_view):
            
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidfriends_view
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
            $kidfriends_getbyid = NidaraKidFriendsInfo::findFirstBynidara_kid_profile_id($id);
            if ($kidfriends_getbyid):
                return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$kidfriends_getbyid
			]);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'You have not entered any information',"data"=>array()]);
            endif;
        endif;
    }

    /**
     * This function using to create NidaraKidFriendsInfo information
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
        $validation->add('name_of_friend', new PresenceOf(['message' => 'Name of Friend is required']));
        $validation->add('name_of_friend', new Alpha(['message'=>'Friends Name is only letters']));
        $validation->add('name_of_friend', new StringLength(['max'=>20,'min'=> 2,'messageMaximum' => 'Friend name is maximum 20',
            'messageMinimum' => 'Friend Name is minimum 2']));
        $validation->add('gender', new PresenceOf(['message' => 'Gender is required']));
        $validation->add('friend_type', new PresenceOf(['message' => 'Friend type is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
	     $kidfriendexist = NidaraKidFriendsInfo::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
	    if (! empty ( $kidfriendexist )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Friends information already exist for this kid' 
				] );
	    }
            $kidfriends_create = new NidaraKidFriendsInfo();
            $kidfriends_create->id = $this->kididgen->getNewId("nidarakidprofileinfo");
            $kidfriends_create->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $kidfriends_create->name_of_friend = $input_data->name_of_friend;
            $kidfriends_create->gender = $input_data->gender;
            $kidfriends_create->friend_type = $input_data->friend_type;
            $kidfriends_create->created_at = date('Y-m-d H:i:s');
            $kidfriends_create->created_by = 1;
            $kidfriends_create->modified_at= date('Y-m-d H:i:s');
            if ($kidfriends_create->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'Friend  information saved successfully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to NidaraKidFriendsInfo information edit
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
            $validation->add('name_of_friend', new PresenceOf(['message' => 'Name of_Friend is required']));
            $validation->add('name_of_friend', new Alpha(['message'=>'Friends Name is only letters']));
            $validation->add('name_of_friend', new StringLength(['max'=>20,'min'=> 2,'messageMaximum' => 'Friend name is maximum 20',
                'messageMinimum' => 'Friend Name is minimum 2']));
            $validation->add('gender', new PresenceOf(['message' => 'Gender is required']));
            $validation->add('friend_type', new PresenceOf(['message' => 'Friend type is required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $kidfriends_update = NidaraKidFriendsInfo::findFirstByid($id);
                if ($kidfriends_update):
                    $kidfriends_update->name_of_friend = $input_data->name_of_friend;
                    $kidfriends_update->gender = $input_data->gender;
                    $kidfriends_update->friend_type = $input_data->friend_type;
                    $kidfriends_update->modified_at = date('Y-m-d H:i:s');
                    if ($kidfriends_update->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'Friend  information updated successfully']);
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
            $kidfriends_delete = NidaraKidFriendsInfo::findFirstByid($id);
            if ($kidfriends_delete):
                if ($kidfriends_delete->delete()):
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
