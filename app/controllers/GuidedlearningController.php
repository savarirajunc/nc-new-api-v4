<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class GuidedlearningController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = GuidedLearning::find();
        if ($subject):

	    return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$subject
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
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
        else:
            $collection = GuidedLearning::findFirstByid($id);
            if ($collection):
                
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$collection
			]);    
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create GuidedLearning information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('learning_model', new PresenceOf(['message' => 'learning_model is required']));
        $validation->add('status', new PresenceOf(['message' => 'status is required']));
        $validation->add('description', new PresenceOf(['message' => 'description is required']));
        $validation->add('created_at', new PresenceOf(['message' => 'created_at is required']));
        $validation->add('created_by', new PresenceOf(['message' => 'created_by is required']));
        $validation->add('modified_at', new PresenceOf(['message' => 'modified_at is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
	    return $this->response->setJsonContent ([ 
					'status' => false,
					'message' =>$result
			]);
            //return $this->response->setJsonContent($result);
        else:
            $collection = new GuidedLearning();
            $collection->id = $input_data->id;
            $collection->learning_model = $input_data->learning_model;
            $collection->status = $input_data->status;
            $collection->description = $input_data->description;
            $collection->created_at = $input_data->created_at;
            $collection->created_by = $input_data->created_by;
            $collection->modified_at = $input_data->modified_at;
            if ($collection->save()):
                return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
            else:
                return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to GuidedLearning information edit
     */
    public function update($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'idis required']));
            $validation->add('learning_model', new PresenceOf(['message' => 'learning_modelis required']));
            $validation->add('status', new PresenceOf(['message' => 'statusis required']));
            $validation->add('description', new PresenceOf(['message' => 'descriptionis required']));
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
                $collection = GuidedLearning::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->learning_model = $input_data->learning_model;
                    $collection->status = $input_data->status;
                    $collection->description = $input_data->description;
                    $collection->created_at = $input_data->created_at;
                    $collection->created_by = $input_data->created_by;
                    $collection->modified_at = $input_data->modified_at;
                    if ($collection->save()):
                        return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
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
            $collection = GuidedLearning::findFirstByid($id);
            if ($collection):
                if ($collection->delete()):
                    return $this->response->setJsonContent(['status' => true, 'Message' => 'Record has been deleted succefully ']);
                else:
                    return $this->response->setJsonContent(['status' => false, 'Message' => 'Data could not be deleted']);
                endif;
            else:
                return $this->response->setJsonContent(['status' => false, 'Message' => 'ID doesn\'t']);endif;
		endif;
	}
	public function getguidedlearnings() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$user_info=$this->tokenvalidate->getuserinfo ( $headers['Token'], $baseurl );
		$userid = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
		$parameters = array (
				'api_key' => $this->config->wpapi_key,
				'email'=>'vil@rootsbridge.com',
				'per_page'=>10,
				'page'=>1
		);
		$inputparams = json_encode ( $parameters );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $this->config->wpurl.'/list-orders.php');
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Content-Length: ' . strlen($inputparams)
		]
		);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS,$inputparams );
		$contents = curl_exec ( $ch );
		curl_close ( $ch );
		$orders = json_decode ( $contents );
		$packages = array ();
		foreach ( $orders->orders as $order ) {
			foreach ( $order->products as $product ) {
				if (! in_array ( $product->learning_code, $packages )) {
					$packages [] = $product->learning_code;
				}
			}
		}
		$guided_learning = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearning.id as package_id',
				'GuidedLearning.learning_model as package_name',
		) )->from ( 'GuidedLearning' )
		->inwhere ( "GuidedLearning.learning_code",
				$packages 
		)->getQuery ()->execute ();
		$guided_learnings = array ();
		foreach ( $guided_learning as $guided_learning_data ) {
			$getby_userid = KidParentsMap::find ( "users_id=$userid" )->toArray ();
			foreach ( $getby_userid as $key => $value ) {
				$kid_id = $value ['nidara_kid_profile_id'];
				$kid_guide = $this->modelsManager->createBuilder ()->columns ( array (
					'KidGuidedLearningMap.guided_learning_id',
				) )->from ( 'KidGuidedLearningMap' )
				->inwhere ( "KidGuidedLearningMap.guided_learning_id",
					array($guided_learning_data->package_id)
				)->inwhere ( "KidGuidedLearningMap.nidara_kid_profile_id",
					array($kid_id)
				)->getQuery ()->getSingleResult ();
			}
			if(empty($kid_guide)){
			$guided_learnings [] = $guided_learning_data;
			}
		}
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$guided_learnings
			]);
	}
	public function paymentinfo() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
		}
		
		$user_info=$this->tokenvalidate->getuserinfo ( $headers['Token'], $baseurl );
		$userid = isset ( $user_info->user_info->id ) ? $user_info->user_info->id : '';
		$parameters = array (
				'api_key' => $this->config->wpapi_key,
				'email'=>'vil@rootsbridge.com',
				'per_page'=>10,
				'page'=>1
		);
		$inputparams = json_encode ( $parameters );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $this->config->wpurl.'/list-orders.php');
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Content-Length: ' . strlen($inputparams)
		]
		);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS,$inputparams );
		$contents = curl_exec ( $ch );
		curl_close ( $ch );
		$orders = json_decode ( $contents );
		$packages = array ();
		foreach ( $orders->orders as $order ) {
			foreach ( $order->products as $product ) {
				if (! in_array ( $product->learning_code, $packages )) {
					$packages [] = $product->learning_code;
				}
			}
		}
		$getby_userid = KidParentsMap::find ( "users_id=$userid" )->toArray ();
		$kid_id=array();
		foreach ( $getby_userid as $key => $value ) {
				$kid_id[] = $value ['nidara_kid_profile_id'];
			}
		$guided_learning = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearning.id as package_id',
				'GuidedLearning.learning_model as package_name',
				'GuidedLearning.description',
				'NidaraKidProfile.id as nidara_kid_profile_id',
				'NidaraKidProfile.status',
				'NidaraKidProfile.first_name as kid_name'
		) )->from ( 'GuidedLearning' )
		->leftjoin ( 'KidGuidedLearningMap', 'GuidedLearning.id=KidGuidedLearningMap.guided_learning_id' )
		->leftjoin ( 'NidaraKidProfile', 'NidaraKidProfile.id=KidGuidedLearningMap.nidara_kid_profile_id' )
		->inwhere ( "GuidedLearning.learning_code",
				$packages 
		)->inwhere ( "NidaraKidProfile.id",
				$kid_id 
		)->getQuery ()->execute ();
		$guided_learnings = array ();
		foreach ( $guided_learning as $guided_learning_data ) {
			if($guided_learning_data->status == 1){
				$guided_learning_data->status='Active';
			}else{
				$guided_learning_data->status='InActive';
			}
			$guided_learnings [] = $guided_learning_data;
		}
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$guided_learnings
			]);
	}
}
