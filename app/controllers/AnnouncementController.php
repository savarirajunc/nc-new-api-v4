<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class AnnouncementController extends \Phalcon\Mvc\Controller {

    public function index() {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $answers_view = NCAnnouncement::find();
        if ($answers_view):
            return Json_encode($answers_view);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Failed']);
        endif;
    }

   /*
   * Create a new annpuncement and update data
   */
   public function create(){
	   $input_data = $this->request->getJsonRawBody ();
	   $id = isset ( $input_data->id ) ? $input_data->id : '';
	   if(!empty($id)){
		  $collection = NCAnnouncement::findFirstByid($id);
		  $collection->start_date = $input_data->start_date;
		  $collection->end_date = $input_data->end_date;
		  $collection->title = $input_data->title;
		  $collection->messages = $input_data->messages;
		  $collection->created_at = date('Y-m-d H:i:s');
		  if(!$collection->save()){
			  return $this->response->setJsonContent([
				'status' => false,
				'messages' => 'date update error'
			  ]);
		  }
		  else{
			  return $this->response->setJsonContent([
				'status' => true,
				'messages' => 'Update succesfull'
			  ]);
		  }
	   }
	   else{
		  $collection = new NCAnnouncement();
		  $collection->start_date = $input_data->start_date;
		  $collection->end_date = $input_data->end_date;
		  $collection->title = $input_data->title;
		  $collection->messages = $input_data->messages;
		  $collection->created_at = date('Y-m-d H:i:s');
		  if(!$collection->save()){
			  return $this->response->setJsonContent([
				'status' => false,
				'messages' => 'date save error'
			  ]);
		  }
		  else{
			  return $this->response->setJsonContent([
				'status' => true,
				'messages' => 'Save succesfull'
			  ]);
		  }   
	   }
   }
   
   /*
   * Get from data in tabel usin id
   */
   public function getdatabyid(){
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if(empty($id)){
			return $this->response->setJsonContent([
				'status' => false,
				'messages' => 'id is null'
			]);
		}
		else{
			$collection = NCAnnouncement::findFirstByid($id);
			return $this->response->setJsonContent([
				'status'=> 	true,
				'data' 	=> 	$collection
			]);
		}
   }
   
   public function getannouncement(){
	   $today = date('Y-m-d');
	   $collection = $this->modelsManager->createBuilder ()->columns ( array (
		'NCAnnouncement.title as title',
		'NCAnnouncement.messages as messages',
	   ))->from('NCAnnouncement')
	   ->where('NCAnnouncement.end_date >= "'. $today .'" AND NCAnnouncement.start_date <= "'. $today .'"')
	   ->getQuery ()->execute ();
	   $getarray = array();
	   if(count($collection) == 0){
		   $data_val['title_2'] = 'You have no new messages.';
		   $getarray[] = $data_val;
		  return $this->response->setJsonContent([
			'status'=> 	true,
			'data' 	=> 	$getarray,
			'date' => $today
			]);  
	   }
	   else{
		   return $this->response->setJsonContent([
				'status'=> 	true,
				'data' 	=> 	$collection,
				'date' => $today
			]);
	   }
   }
   
}
