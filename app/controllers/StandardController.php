<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class StandardController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = Standard::find();
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
            $collection = Standard::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create Standard information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('standard_name', new PresenceOf(['message' => 'standard_name is required']));
      /*  $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('status', new PresenceOf(['message' => 'status is required']));
        $validation->add('created_at', new PresenceOf(['message' => 'created_at is required']));
        $validation->add('created_by', new PresenceOf(['message' => 'created_by is required']));
       */ 
	$messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
            $collection = new Standard();
            $collection->id = $this ->indicatorsidgen->getNewId('indicatorsidgen');
            $collection->standard_name = $input_data->standard_name;
            $collection->status = 1;
            $collection->created_at = date ( 'Y-m-d H:i:s' );
            $collection->created_by = $collection->id;
            if ($collection->save()):
				$collection_indicators = new Indicators();
				$collection_indicators->id = $this ->indicatorsidgen->getNewId('indicatorsidgen_id');
				$collection_indicators->indicator_name = $input_data->indicator_name;
				//$collection->parent_id = $input_data->parent_id;
				if ($collection_indicators->save()){
					$collection2 = new Indicators();
					$collection2->id = $this ->indicatorsidgen->getNewId('indicatorsidgen');
					$collection2->indicator_name = $input_data->indicator_name2;
					if($collection2->save()){
						$collection_ind_map1 = new StandardIndicatorsMap();
						$collection_ind_map1->id = $this ->indicatorsidgen->getNewId('indicatorsidgen');
						$collection_ind_map1->guided_id = $input_data->guided_id;
						$collection_ind_map1->coreframwor_id = $input_data->coreframwor_id;
						$collection_ind_map1->subject_id = $input_data->subject_id;
						$collection_ind_map1->standard_id = $collection->id;
						$collection_ind_map1->indicators_id = $collection_indicators->id;
						if ($collection_ind_map1->save()){
							$collection_ind_map2 = new StandardIndicatorsMap();
							$collection_ind_map2->id = $this ->indicatorsidgen->getNewId('indicatorsidgen');
							$collection_ind_map2->guided_id = $input_data->guided_id;
							$collection_ind_map2->coreframwor_id = $input_data->coreframwor_id;
							$collection_ind_map2->subject_id = $input_data->subject_id;
							$collection_ind_map2->standard_id = $collection->id;
							$collection_ind_map2->indicators_id = $collection2->id;
							if ($collection_ind_map2->save()){
								return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
							}
							return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
						}
						return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
					}
				}
                return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to Standard information edit
     */
    public function update($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'idis required']));
            $validation->add('standard_name', new PresenceOf(['message' => 'standard_nameis required']));
            $validation->add('status', new PresenceOf(['message' => 'statusis required']));
            $validation->add('created_at', new PresenceOf(['message' => 'created_atis required']));
            $validation->add('created_by', new PresenceOf(['message' => 'created_byis required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $collection = Standard::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->standard_name = $input_data->standard_name;
                    $collection->status = $input_data->status;
                    $collection->created_at = $input_data->created_at;
                    $collection->created_by = $input_data->created_by;
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
            $collection = Standard::findFirstByid($id);
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
	public function mapval(){
		
	}

}
