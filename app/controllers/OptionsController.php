<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class OptionsController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = Options::find();
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
            $collection = Options::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create Options information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('option', new PresenceOf(['message' => 'option is required']));
        $validation->add('is_image', new PresenceOf(['message' => 'is_image is required']));
        $validation->add('image_path', new PresenceOf(['message' => 'image_path is required']));
        $validation->add('is_answer', new PresenceOf(['message' => 'is_answer is required']));
        $validation->add('is_multi_answer', new PresenceOf(['message' => 'is_multi_answer is required']));
        $validation->add('questions_id', new PresenceOf(['message' => 'questions_id is required']));
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
            $collection = new Options();
            $collection->id = $input_data->id;
            $collection->option = $input_data->option;
            $collection->is_image = $input_data->is_image;
            $collection->image_path = $input_data->image_path;
            $collection->is_answer = $input_data->is_answer;
            $collection->is_multi_answer = $input_data->is_multi_answer;
            $collection->questions_id = $input_data->questions_id;
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
     * This function using to Options information edit
     */
    public function update($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'idis required']));
            $validation->add('option', new PresenceOf(['message' => 'optionis required']));
            $validation->add('is_image', new PresenceOf(['message' => 'is_imageis required']));
            $validation->add('image_path', new PresenceOf(['message' => 'image_pathis required']));
            $validation->add('is_answer', new PresenceOf(['message' => 'is_answeris required']));
            $validation->add('is_multi_answer', new PresenceOf(['message' => 'is_multi_answeris required']));
            $validation->add('questions_id', new PresenceOf(['message' => 'questions_idis required']));
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
                $collection = Options::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->option = $input_data->option;
                    $collection->is_image = $input_data->is_image;
                    $collection->image_path = $input_data->image_path;
                    $collection->is_answer = $input_data->is_answer;
                    $collection->is_multi_answer = $input_data->is_multi_answer;
                    $collection->questions_id = $input_data->questions_id;
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
            $collection = Options::findFirstByid($id);
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
    /**
     * Get options by questions id
     */
    public function getbyquestionid(){
      $input_data = $this->request->getJsonRawBody();
      $questions_id = isset($input_data->questions_id) ? $input_data->questions_id :'';
      if (empty($questions_id)):
          return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
      else:
          $options_getbyid = Options::find("questions_id=$questions_id");
          if ($options_getbyid):
              return Json_encode($options_getbyid);
          else:
              return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
          endif;
      endif;

    }
   public function getbyquestionid(){
      $input_data = $this->request->getJsonRawBody();
      $questions_id = isset($input_data->questions_id) ? $input_data->questions_id :'';
      if (empty($questions_id)):
          return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
      else:
          $options_getbyid = Options::find("questions_id=$questions_id");
          if ($options_getbyid):
              return Json_encode($options_getbyid);
          else:
              return $this->response->setJsonContent(['status' => false, 'Message' => 'Data not found']);
          endif;
      endif;

    }
}
