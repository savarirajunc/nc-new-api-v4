<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class StandardindicatorsmapController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
        $subject = StandardIndicatorsMap::find();
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
            $collection = StandardIndicatorsMap::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create StandardIndicatorsMap information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('standard_id', new PresenceOf(['message' => 'standard_id is required']));
        $validation->add('indicators_id', new PresenceOf(['message' => 'indicators_id is required']));
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
            $collection = new StandardIndicatorsMap();
            $collection->id = $input_data->id;
            $collection->standard_id = $input_data->standard_id;
            $collection->indicators_id = $input_data->indicators_id;
            if ($collection->save()):
                return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
            else:
                return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
            endif;
        endif;
    }

    /**
     * This function using to StandardIndicatorsMap information edit
     */
    public function update($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'idis required']));
            $validation->add('standard_id', new PresenceOf(['message' => 'standard_idis required']));
            $validation->add('indicators_id', new PresenceOf(['message' => 'indicators_idis required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $collection = StandardIndicatorsMap::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->standard_id = $input_data->standard_id;
                    $collection->indicators_id = $input_data->indicators_id;
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
            $collection = StandardIndicatorsMap::findFirstByid($id);
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
	
	public function getbystandardmap(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
			/* $baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Invalid User" 
			] );
		} */
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$framework_id = 0;
		$subject_id = 0;
		if(!empty($input_data->framework_id) && !empty($input_data->subject_id) ){
				$framework_id = $input_data->framework_id;
				$subject_id = $input_data->subject_id;
		}
		else{
			$game_core_sub2 = $input_data -> gameCoreFrame;
			foreach($game_core_sub2 as $value2){
							// $framework_id = isset ( $value->framework_id ) ? $value->framework_id : '';
							$subject_id = $value2->subject_id;
			}
						$game_core_sub = $input_data -> gameCoreFrame;
						foreach($game_core_sub as $value1){
							$framework_id = $value1->framework_id ;
							// $subject_id = isset ( $value->subject_id ) ? $value->subject_id : '';
						}
			$gamequestionmap = $input_data->gameQuestionanswer;
			foreach($gamequestionmap as $gamequesvalue){
				$questiontagging = $gamequesvalue->questionCoreFrame;
				foreach($questiontagging as $value){
					$framework_id = $value->quesframework_id;
					$subject_id = $value->quessubject_id ;
					if(empty($subject_id)){
						$game_core_sub2 = $input_data -> gameCoreFrame;
						foreach($game_core_sub2 as $value2){
							// $framework_id = isset ( $value->framework_id ) ? $value->framework_id : '';
							$subject_id = $value2->subject_id;
						}
					}
					if(empty($framework_id)){
						$game_core_sub = $input_data -> gameCoreFrame;
						foreach($game_core_sub as $value1){
							$framework_id = $value1->framework_id ;
							// $subject_id = isset ( $value->subject_id ) ? $value->subject_id : '';
						}
					}
				}
			}
		}
		$standard_database = $this->modelsManager->createBuilder ()->columns ( array (
					'DISTINCT Standard.id as id',
					'Standard.standard_name as standard_name',
				))->from('StandardIndicatorsMap')
				->leftjoin('Standard','StandardIndicatorsMap.standard_id = Standard.id')
				->leftjoin('CoreFrameworks','StandardIndicatorsMap.coreframwor_id = CoreFrameworks.id')
				->leftjoin('GuidedLearning','StandardIndicatorsMap.guided_id = GuidedLearning.id')
				->leftjoin('Subject','StandardIndicatorsMap.subject_id = Subject.id')
				->inwhere("StandardIndicatorsMap.guided_id",array($grade_id))
				->inwhere('StandardIndicatorsMap.coreframwor_id',array($framework_id))
				->inwhere('StandardIndicatorsMap.subject_id',array($subject_id))
				->getQuery ()->execute ();
				 
				 $standardmaparray = array();
				 foreach($standard_database as $stant_val){
					 $standard_database_val['id'] = $stant_val->id;
					 $standard_database_val['standard_name'] = $stant_val->standard_name;
					 $standardmaparray [] = $standard_database_val;
				 }
				return $this->response->setJsonContent ( [
					'status' => true,
					'data' => $standardmaparray
				] );
		 
	}
	
	public function getbyindicatormap(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the token" 
			] );
		}
		/* $baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Invalid User" 
			] );
		} */
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$framework_id = 0;
		$subject_id = 0;
		if(!empty($input_data->framework_id) && !empty($input_data->subject_id) ){
				$framework_id = $input_data->framework_id;
				$subject_id = $input_data->subject_id;
		}
		else{
			$gamequestionmap = $input_data->gameQuestionanswer;
			$game_core_sub2 = $input_data -> gameCoreFrame;
						foreach($game_core_sub2 as $value2){
							// $framework_id = isset ( $value->framework_id ) ? $value->framework_id : '';
							$subject_id = $value2->subject_id;
						}
						$game_core_sub = $input_data -> gameCoreFrame;
						foreach($game_core_sub as $value1){
							$framework_id = $value1->framework_id ;
							// $subject_id = isset ( $value->subject_id ) ? $value->subject_id : '';
						}
			foreach($gamequestionmap as $gamequesvalue){
				$questiontagging = $gamequesvalue->questionCoreFrame;
				foreach($questiontagging as $value){
					$framework_id = $value->quesframework_id;
					$subject_id = $value->quessubject_id ;
					if(empty($subject_id)){
						$game_core_sub2 = $input_data -> gameCoreFrame;
						foreach($game_core_sub2 as $value2){
							// $framework_id = isset ( $value->framework_id ) ? $value->framework_id : '';
							$subject_id = $value2->subject_id;
						}
					}
					if(empty($framework_id)){
						$game_core_sub = $input_data -> gameCoreFrame;
						foreach($game_core_sub as $value1){
							$framework_id = $value1->framework_id ;
							// $subject_id = isset ( $value->subject_id ) ? $value->subject_id : '';
						}
					}
				}
			}
		}
			$indicator_database = $this->modelsManager->createBuilder ()->columns ( array (
				'Indicators.id as id',
				'Indicators.indicator_name as indicator_name'
			))->from('StandardIndicatorsMap')
			->leftjoin('Indicators','StandardIndicatorsMap.indicators_id = Indicators.id')
			->leftjoin('CoreFrameworks','StandardIndicatorsMap.coreframwor_id = CoreFrameworks.id')
			->leftjoin('GuidedLearning','StandardIndicatorsMap.guided_id = GuidedLearning.id')
			->leftjoin('Subject','StandardIndicatorsMap.subject_id = Subject.id')
			->inwhere("StandardIndicatorsMap.guided_id",array($grade_id))
			->inwhere('StandardIndicatorsMap.coreframwor_id',array($framework_id))
			->inwhere('StandardIndicatorsMap.subject_id',array($subject_id))
			->getQuery ()->execute ();
			 
			 $indicatormaparray = array();
			 foreach($indicator_database as $stant_val){
				 if($stant_val->indicator_name){
				 $indicator_database_val['id'] = $stant_val->id;
				 $indicator_database_val['indicator_name'] = $stant_val->indicator_name;
				 $indicatormaparray [] = $indicator_database_val;
				 }
			 }
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $indicatormaparray
			] );
	}
	
	
	 public function getby_grade_coreframe(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the token" 
			] );
		}
		/* $baseurl = $this->config->baseurl;
		$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
		if ($token_check->status != 1) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Invalid User" 
			] );
		} */
		
		$grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
		if(empty($grade_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Grade Id is null'
			] );
		}
		$framework_id = isset ( $input_data->framework_id ) ? $input_data->framework_id : '';
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		if(empty($framework_id)){
			return $this->response->setJsonContent ( [
				'status' => false,
				'message' => 'Framework Id is null'
			] );
		}
		else if(empty($subject_id)){
			return $this->response->setJsonContent ( [
				'status' => false,
				'message' => 'Subject Id is null'
			] );
		}
		
		$standardmap = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT StandardIndicatorsMap.standard_id as standard_id',
			'Standard.standard_name as standard_name',
		))->from('StandardIndicatorsMap')
		->leftjoin('Standard','StandardIndicatorsMap.standard_id = Standard.id')
		->inwhere("StandardIndicatorsMap.guided_id",array($grade_id))
		->inwhere('StandardIndicatorsMap.coreframwor_id',array($framework_id))
		->inwhere('StandardIndicatorsMap.subject_id',array($subject_id))
		->getQuery ()->execute ();
		
		$standardmaparray = array();
		foreach($standardmap as $standard_value){
			$indicatormap = $this->modelsManager->createBuilder ()->columns ( array (
				'StandardIndicatorsMap.id as id',
				'Indicators.id as indicator_id',
				'Indicators.indicator_name as indicator_name'
			))->from('StandardIndicatorsMap')
			->leftjoin('Indicators','StandardIndicatorsMap.indicators_id = Indicators.id')
			->inwhere("StandardIndicatorsMap.guided_id",array($grade_id))
			->inwhere('StandardIndicatorsMap.coreframwor_id',array($framework_id))
			->inwhere('StandardIndicatorsMap.subject_id',array($subject_id))
			//->inwhere('StandardIndicatorsMap.standard_id',array(3851))
			->inwhere("StandardIndicatorsMap.standard_id",array($standard_value->standard_id))
			//->inwhere("Indicators.indicator_name",array("NULL"))
			->getQuery ()->execute ();
			$indicatormaparray = array();
			foreach($indicatormap as $value){
				if($value->indicator_name){
				$indicator_val['id'] =  $value->id;
				$indicator_val['indicator_id'] =  $value->indicator_id;
				$indicator_val['indicator_name'] =  $value->indicator_name;
				$indicatormaparray[] = $indicator_val;
				}
			}
			$standard_val['standard_id'] = $standard_value->standard_id;
			$standard_val['standard_name'] = $standard_value->standard_name;
			$standard_val['Indicators'] = $indicatormaparray;
			$standardmaparray[] =$standard_val;
		}
		$chunked_array = array_chunk ( $standardmaparray, 15 );
			array_replace ( $chunked_array, $chunked_array );
			$keyed_array = array ();
			foreach ( $chunked_array as $chunked_arrays ) {
				$keyed_array [] = $chunked_arrays;
			}
			$games ['games'] = $keyed_array;
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $games
			] ); 
	 }

} 
