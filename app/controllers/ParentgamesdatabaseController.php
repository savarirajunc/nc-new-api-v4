<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ParentgamesdatabaseController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
         $parentgame_data = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT ParentGamesCoreframeMap.grade_id as grade_id',
			'ParentGamesCoreframeMap.framework_id as framework_id',
			'ParentGamesCoreframeMap.subject_id as subject_id',
			'ParentGamesCoreframeMap.game_id as game_id',
			'Grade.grade_name as grade_name',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'ParentGamesDatabase.games_name as games_name',
			'Days.days as days',
		))->from('ParentGamesCoreframeMap')
		->leftjoin('Grade','ParentGamesCoreframeMap.grade_id = Grade.id')
		->leftjoin('CoreFrameworks','ParentGamesCoreframeMap.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','ParentGamesCoreframeMap.subject_id = Subject.id')
		->leftjoin('ParentGamesDatabase','ParentGamesCoreframeMap.game_id = ParentGamesDatabase.id')
		->leftjoin('ParentGuidedLearningDayGameMap','ParentGuidedLearningDayGameMap.game_id = ParentGamesDatabase.id')
		->leftjoin('Days','ParentGuidedLearningDayGameMap.day_id = Days.id')
		->orderBy('ParentGamesCoreframeMap.id DESC')
		->getQuery ()->execute ();
		$parentgame_array = array();
		foreach($parentgame_data as $game_value){
			$game_data ['grade_id'] = $game_value -> grade_id;
			$game_data ['framework_id'] = $game_value -> framework_id;
			$game_data ['subject_id'] = $game_value -> subject_id;
			$game_data ['game_id'] = $game_value -> game_id;
			$game_data ['grade_name'] = $game_value -> grade_name;
			$game_data ['core_name'] = $game_value -> name;
			$game_data ['subject_name'] = $game_value -> subject_name;
			$game_data ['games_name'] = $game_value -> games_name;
			$game_data ['days'] = $game_value -> days;
			$parentgame_array[] = $game_data;
		}
		$chunked_array = array_chunk ( $parentgame_array, 15 );
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
	
	public function create(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation();
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
		else{
			$collection = new ParentGamesDatabase();
			$collection -> id = $this ->parentgamesidgen->getNewId('parentgameidgen');
			$collection -> game_id = $collection -> id;
			$collection ->games_name = $input_data ->games_name;
			$collection ->created_at = date ( 'Y-m-d H:i:s' );
			if($collection ->save()){
			$game_cor_val = $input_data -> gameCoreFrame;
				foreach($game_cor_val as $value ){
					$game_cor_map = new ParentGamesCoreframeMap();
					$game_cor_map -> id = $collection->id;
					$game_cor_map -> grade_id = $input_data->grade_id;
					$game_cor_map -> standard_id = $value->standard_id;
					$game_cor_map -> gamecoretype = $value->gamecoretype;
					$game_cor_map -> indicator_id = $value->indicator_id;
					$game_cor_map -> framework_id = $value->framework_id;
					$game_cor_map -> subject_id = $value->subject_id;
					$game_cor_map -> game_id = $collection->id;
					$game_cor_map->save();
					$game_cor_map2 = new ParentGamesCoreframeMap();
					$game_cor_map2 -> id = $this ->parentgamesidgen->getNewId('parentgameidgen');
					$game_cor_map2 -> grade_id = $game_cor_map -> grade_id;
					$game_cor_map2 -> standard_id = $game_cor_map -> standard_id;
					$game_cor_map2 -> gamecoretype = $game_cor_map -> gamecoretype;
					$game_cor_map2 -> indicator_id = $value->indicator_id1;
					$game_cor_map2 -> framework_id = $game_cor_map -> framework_id;
					$game_cor_map2 -> subject_id = $game_cor_map -> subject_id;
					$game_cor_map2 -> game_id = $collection->id;
					$game_cor_map2-> save();
					$game_cor_map3 = new ParentGamesCoreframeMap();
					$game_cor_map3 -> id = $this ->parentgamesidgen->getNewId('parentgameidgen');
					$game_cor_map3 -> grade_id = $game_cor_map -> grade_id;
					$game_cor_map3 -> standard_id = $game_cor_map -> standard_id;
					$game_cor_map3 -> gamecoretype = $game_cor_map -> gamecoretype;
					$game_cor_map3 -> indicator_id = $value->indicator_id2;
					$game_cor_map3 -> framework_id = $game_cor_map -> framework_id;
					$game_cor_map3 -> subject_id = $game_cor_map -> subject_id;
					$game_cor_map3 -> game_id = $collection->id;
					$game_cor_map3->save();
					$game_cor_map4 = new ParentGamesCoreframeMap();
					$game_cor_map4 -> id = $this ->parentgamesidgen->getNewId('parentgameidgen');
					$game_cor_map4 -> grade_id = $game_cor_map -> grade_id;
					$game_cor_map4 -> standard_id = $game_cor_map -> standard_id;
					$game_cor_map4 -> gamecoretype = $game_cor_map -> gamecoretype;
					$game_cor_map4 -> indicator_id = $value->indicator_id3;
					$game_cor_map4 -> framework_id = $game_cor_map -> framework_id;
					$game_cor_map4 -> subject_id = $game_cor_map -> subject_id;
					$game_cor_map4 -> game_id = $collection->id;
					$game_cor_map4->save();
					$collection2 = new ParentGuidedLearningDayGameMap();
					$collection2->id = $this->guidedlearningidgen->getNewId ("guided_learning_gmae");
					$collection2-> day_id = $input_data->days;
					$collection2-> framework_id = $game_cor_map -> framework_id;
					$collection2-> subject_id = $game_cor_map -> subject_id;
					$collection2-> guided_learning_id = $game_cor_map -> grade_id;
					$collection2-> game_id = $collection->id;
					$collection2-> created_at = date ( 'Y-m-d H:i:s' );
					$collection2-> save();
				} 
				return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
			}
		}
	}
	
	public function gameupdate(){
		$input_data = $this->request->getJsonRawBody ();
			$validation = new Validation();
			$messages = $validation->validate($input_data);
			if (count($messages)){
				foreach ($messages as $message) :
					$result[] = $message->getMessage();
				endforeach;
				return $this->response->setJsonContent($result);
			}
			else{
				$collection = ParentGamesDatabase::findFirstBygame_id( $input_data-> game_id);
				$collection ->games_name = $input_data ->games_name;
				$collection -> save();
				$collection2 = ParentGamesCoreframeMap::findFirstBygame_id( $input_data-> game_id);
				$collection2 ->grade_id = $input_data ->grade_id;
				$collection2 -> save();
				
				return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
			}
		
	}
	public function standardupdate(){
		$input_data = $this->request->getJsonRawBody ();
			$validation = new Validation();
			$messages = $validation->validate($input_data);
			if (count($messages)){
				foreach ($messages as $message) :
					$result[] = $message->getMessage();
				endforeach;
				return $this->response->setJsonContent($result);
			}
			else{
				$game_data = $input_data -> gameData;
				foreach($game_data as $game_value){
				$grade_id = $game_value -> grade_id;
				}
				
				$standard_data = $input_data -> indicatorData;
				foreach($standard_data as $standard_value){
					
					$standard = $standard_value -> standard_id;
					$indicator_data = $standard_value -> game;
					foreach($indicator_data as $indicator_value){
						$collection_data = ParentGamesCoreframeMap::findFirstByid($indicator_value-> id);
						$collection_data -> indicator_id = $indicator_value->indicator_id;
						$collection_data -> standard_id = $standard;
						$collection_data -> save();
					}
				}
				return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
		}
		
	}
	
	public function getbycoremap(){
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
		
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		
		if(empty($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
		
		if(empty($game_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'game_id Id is null'
			] );
		}
		
		$parentgame_core_map = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT ParentGamesCoreframeMap.grade_id as grade_id',
			'ParentGamesDatabase.games_name as games_name',
			'ParentGamesDatabase.id as id'
		))->from('ParentGamesCoreframeMap')
		->leftjoin('Grade','ParentGamesCoreframeMap.grade_id = Grade.id')
		->leftjoin('ParentGamesDatabase','ParentGamesCoreframeMap.game_id = ParentGamesDatabase.id')
		->inwhere("ParentGamesCoreframeMap.grade_id",array($grade_id))
 		->inwhere("ParentGamesCoreframeMap.subject_id",array($subject_id))
 		->inwhere("ParentGamesCoreframeMap.game_id",array($game_id))
		->getQuery ()->execute ();	 
		$parentgame_core_map_array = array(); 
		
		foreach($parentgame_core_map as $parent_game_val){
			$parent_core_val['grade_id'] = $parent_game_val->grade_id;
			$parent_core_val['game_id'] = $parent_game_val->id;
			$parent_core_val['games_name'] = $parent_game_val->games_name;
			$parentgame_core_map_array[] = $parent_core_val;
		}
		return $this->response->setJsonContent(['status' => true, 'data' => $parentgame_core_map_array]);
	}
	
	public function getbycoremapsub(){
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
		
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		
		if(empty($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
		
		if(empty($game_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'game_id Id is null'
			] );
		}
		
		$parentgame_core_map = $this->modelsManager->createBuilder ()->columns ( array (
			'DISTINCT ParentGamesCoreframeMap.grade_id as grade_id',
			'ParentGamesCoreframeMap.framework_id as framework_id',
			'ParentGamesCoreframeMap.subject_id as subject_id',
			'ParentGamesCoreframeMap.gamecoretype as gamecoretype',
			'ParentGamesCoreframeMap.standard_id as standard_id',
			'ParentGamesDatabase.games_name as games_name',
			'ParentGamesDatabase.id as id',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'Standard.standard_name as standard_name'
		))->from('ParentGamesCoreframeMap')
		->leftjoin('Grade','ParentGamesCoreframeMap.grade_id = Grade.id')
		->leftjoin('CoreFrameworks','ParentGamesCoreframeMap.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','ParentGamesCoreframeMap.subject_id = Subject.id')
		->leftjoin('Standard','ParentGamesCoreframeMap.standard_id = Standard.id')
		->leftjoin('ParentGamesDatabase','ParentGamesCoreframeMap.game_id = ParentGamesDatabase.id')
		->inwhere("ParentGamesCoreframeMap.grade_id",array($grade_id))
 		->inwhere("ParentGamesCoreframeMap.subject_id",array($subject_id))
 		->inwhere("ParentGamesCoreframeMap.game_id",array($game_id))
		->orderBy("ParentGamesCoreframeMap.gamecoretype")
		->getQuery ()->execute ();	 
		$parentgame_core_map_array = array(); 
		
		foreach($parentgame_core_map as $parent_game_val){
			
			$parentgame_core_map2 = $this->modelsManager->createBuilder ()->columns ( array (
			'ParentGamesCoreframeMap.id as id',
			'ParentGamesCoreframeMap.grade_id as grade_id',
			'ParentGamesCoreframeMap.framework_id as framework_id',
			'ParentGamesCoreframeMap.subject_id as subject_id',
			'ParentGamesCoreframeMap.gamecoretype as gamecoretype',
			'ParentGamesCoreframeMap.standard_id as standard_id',
			'ParentGamesCoreframeMap.indicator_id as indicator_id',
			'ParentGamesDatabase.games_name as games_name',
			'ParentGamesDatabase.id as games_id',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'Standard.standard_name as standard_name'
		))->from('ParentGamesCoreframeMap')
		->leftjoin('Grade','ParentGamesCoreframeMap.grade_id = Grade.id')
		->leftjoin('CoreFrameworks','ParentGamesCoreframeMap.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','ParentGamesCoreframeMap.subject_id = Subject.id')
		->leftjoin('Standard','ParentGamesCoreframeMap.standard_id = Standard.id')
		->leftjoin('ParentGamesDatabase','ParentGamesCoreframeMap.game_id = ParentGamesDatabase.id')
		->inwhere("ParentGamesCoreframeMap.game_id",array($parent_game_val->id))
 		->inwhere("ParentGamesCoreframeMap.standard_id",array($parent_game_val->standard_id))
		->orderBy("ParentGamesCoreframeMap.gamecoretype")
		->orderBy("ParentGamesCoreframeMap.standard_id")
		->getQuery ()->execute ();	 
		$parentgame_core_map_array2 = array(); 
		
		foreach($parentgame_core_map2 as $parent_game_val2){
			$parent_core_val2['id'] = $parent_game_val2->id;
			$parent_core_val2['grade_id'] = $parent_game_val2->grade_id;
			$parent_core_val2['framework_id'] = $parent_game_val2->framework_id;
			$parent_core_val2['subject_id'] = $parent_game_val2->subject_id;
			$parent_core_val2['game_id'] = $parent_game_val2->games_id;
			$parent_core_val2['games_name'] = $parent_game_val2->games_name;
			$parent_core_val2['core_name'] = $parent_game_val2->name;
			$parent_core_val2['subject_name'] = $parent_game_val2->subject_name;
			$parent_core_val2['standard_name'] = $parent_game_val2->standard_name;
			$parent_core_val2['gamecoretype'] = $parent_game_val2->gamecoretype;
			$parent_core_val2['standard_id_ind'] = $parent_game_val2->standard_id;
			$parent_core_val2['indicator_id'] = $parent_game_val2->indicator_id;
			$parentgame_core_map_array2[] = $parent_core_val2;
		}
			/* return $this->response->setJsonContent(['status' => true, 'value' => $parentgame_core_map_array2]); */
			
			$parent_core_val['grade_id'] = $parent_game_val->grade_id;
			$parent_core_val['framework_id'] = $parent_game_val->framework_id;
			$parent_core_val['subject_id'] = $parent_game_val->subject_id;
			$parent_core_val['game_id'] = $parent_game_val->id;
			$parent_core_val['games_name'] = $parent_game_val->games_name;
			$parent_core_val['core_name'] = $parent_game_val->name;
			$parent_core_val['subject_name'] = $parent_game_val->subject_name;
			$parent_core_val['standard_name'] = $parent_game_val->standard_name;
			$parent_core_val['gamecoretype'] = $parent_game_val->gamecoretype;
			$parent_core_val['standard_id'] = $parent_game_val->standard_id;
			$parent_core_val['game'] = $parentgame_core_map_array2;
			$parentgame_core_map_array[] = $parent_core_val;
		}
		return $this->response->setJsonContent(['status' => true, 'data' => $parentgame_core_map_array]);
	}
	
	public function getbycoremapind(){
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
		
		$subject_id = isset ( $input_data->subject_id ) ? $input_data->subject_id : '';
		
		if(empty($subject_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Subject Id is null'
			] );
		}
		$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
		
		if(empty($game_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'game_id Id is null'
			] );
		}
		
		$parentgame_core_map = $this->modelsManager->createBuilder ()->columns ( array (
			'ParentGamesCoreframeMap.id as id',
			'ParentGamesCoreframeMap.grade_id as grade_id',
			'ParentGamesCoreframeMap.framework_id as framework_id',
			'ParentGamesCoreframeMap.subject_id as subject_id',
			'ParentGamesCoreframeMap.gamecoretype as gamecoretype',
			'ParentGamesCoreframeMap.standard_id as standard_id',
			'ParentGamesCoreframeMap.indicator_id as indicator_id',
			'ParentGamesDatabase.games_name as games_name',
			'ParentGamesDatabase.id as games_id',
			'CoreFrameworks.name as name',
			'Subject.subject_name as subject_name',
			'Standard.standard_name as standard_name'
		))->from('ParentGamesCoreframeMap')
		->leftjoin('Grade','ParentGamesCoreframeMap.grade_id = Grade.id')
		->leftjoin('CoreFrameworks','ParentGamesCoreframeMap.framework_id = CoreFrameworks.id')
		->leftjoin('Subject','ParentGamesCoreframeMap.subject_id = Subject.id')
		->leftjoin('Standard','ParentGamesCoreframeMap.standard_id = Standard.id')
		->leftjoin('ParentGamesDatabase','ParentGamesCoreframeMap.game_id = ParentGamesDatabase.id')
		->inwhere("ParentGamesCoreframeMap.grade_id",array($grade_id))
 		->inwhere("ParentGamesCoreframeMap.subject_id",array($subject_id))
 		->inwhere("ParentGamesCoreframeMap.game_id",array($game_id))
		->orderBy("ParentGamesCoreframeMap.gamecoretype")
		->orderBy("ParentGamesCoreframeMap.standard_id")
		->getQuery ()->execute ();	 
		$parentgame_core_map_array = array(); 
		
		foreach($parentgame_core_map as $parent_game_val){
			$parent_core_val['id'] = $parent_game_val->id;
			$parent_core_val['grade_id'] = $parent_game_val->grade_id;
			$parent_core_val['framework_id'] = $parent_game_val->framework_id;
			$parent_core_val['subject_id'] = $parent_game_val->subject_id;
			$parent_core_val['game_id'] = $parent_game_val->games_id;
			$parent_core_val['games_name'] = $parent_game_val->games_name;
			$parent_core_val['core_name'] = $parent_game_val->name;
			$parent_core_val['subject_name'] = $parent_game_val->subject_name;
			$parent_core_val['standard_name'] = $parent_game_val->standard_name;
			$parent_core_val['gamecoretype'] = $parent_game_val->gamecoretype;
			$parent_core_val['standard_id_ind'] = $parent_game_val->standard_id;
			$parent_core_val['indicator_id'] = $parent_game_val->indicator_id;
			$parentgame_core_map_array[] = $parent_core_val;
		}
		return $this->response->setJsonContent(['status' => true, 'data' => $parentgame_core_map_array]);
	}
	
}
