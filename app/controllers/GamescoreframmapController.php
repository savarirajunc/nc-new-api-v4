<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class GamescoreframmapController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }
	public function viewall() {
        $game_data_val = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesCoreframeMap.game_id as game_id',
				'GamesDatabase.games_name as games_name',
				'Grade.grade_name as grade_name',
				'CoreFrameworks.name as name',
				'Subject.subject_name as subject_name',
			))->from('GamesCoreframeMap')
			->leftjoin('CoreFrameworks','GamesCoreframeMap.framework_id = CoreFrameworks.id')
			->leftjoin('Grade','GamesCoreframeMap.grade_id = Grade.id')
			->leftjoin('Subject','GamesCoreframeMap.subject_id = Subject.id')
			->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
			->orderBy('GamesCoreframeMap.id DESC')
			->groupBy('GamesCoreframeMap.game_id')
			->getQuery ()->execute ();
			$gamearray = array();
			foreach($game_data_val as $game_data){
				$game_val['game_id'] = $game_data->game_id;
				$game_val['games_name'] = $game_data->games_name;
				$game_val['grade_name'] = $game_data->grade_name;
				$game_val['core_name'] = $game_data->name;
				$game_val['subject_name'] = $game_data->subject_name;
				$gamearray[] = $game_val;
			}
			$chunked_array = array_chunk ( $gamearray, 15 );
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
	
	public function getgamefilter(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the token" 
			] );
		}
		else{
			$game_data_val = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesCoreframeMap.game_id as game_id',
				'GamesDatabase.games_name as games_name',
				'Grade.grade_name as grade_name',
				'CoreFrameworks.name as name',
				'Subject.subject_name as subject_name',
			))->from('GamesCoreframeMap')
			->leftjoin('CoreFrameworks','GamesCoreframeMap.framework_id = CoreFrameworks.id')
			->leftjoin('Grade','GamesCoreframeMap.grade_id = Grade.id')
			->leftjoin('Subject','GamesCoreframeMap.subject_id = Subject.id')
			->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
			->inwhere('GamesCoreframeMap.grade_id',array($input_data->grade))
			->inwhere('GamesCoreframeMap.framework_id',array($input_data->coreframework))
			->inwhere('GamesCoreframeMap.subject_id',array($input_data->subjects))
			->orderBy('GamesCoreframeMap.game_id DESC') 
			->groupBy('GamesCoreframeMap.game_id')
			->getQuery ()->execute ();
			$gamearray = array();
			foreach($game_data_val as $game_data){
				$game_val['game_id'] = $game_data->game_id;
				$game_val['games_name'] = $game_data->games_name;
				$game_val['grade_name'] = $game_data->grade_name;
				$game_val['core_name'] = $game_data->name;
				$game_val['subject_name'] = $game_data->subject_name;
				$gamearray[] = $game_val;
			}
			$chunked_array = array_chunk ( $gamearray, 15 );
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

	public function getGameListFilter(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the token" 
			] );
		}
		else{
			$game_data_val = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearningDayGameMap.game_id as game_id',
				'GamesDatabase.games_name as games_name',
				'GamesDatabase.games_folder as games_folder',
				'Grade.grade_name as grade_name',
				'CoreFrameworks.name as name',
				'Subject.subject_name as subject_name',
			))->from('GuidedLearningDayGameMap')
			->leftjoin('CoreFrameworks','GuidedLearningDayGameMap.framework_id = CoreFrameworks.id')
			->leftjoin('Grade','GuidedLearningDayGameMap.day_guided_learning_id = Grade.id')
			->leftjoin('Subject','GuidedLearningDayGameMap.subject_id = Subject.id')
			->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
			->where('GuidedLearningDayGameMap.day_id >="' . $input_data->startday . '" AND GuidedLearningDayGameMap.day_id <="' . $input_data->endday . '"')
			->inwhere('GuidedLearningDayGameMap.day_guided_learning_id',array($input_data->grade_id))
			->inwhere('GuidedLearningDayGameMap.framework_id',array($input_data->coreframework))
			->inwhere('GuidedLearningDayGameMap.subject_id',array($input_data->subjects))
			->orderBy('GuidedLearningDayGameMap.game_id DESC') 
			->groupBy('GuidedLearningDayGameMap.game_id')
			->getQuery ()->execute ();
			$gamearray = array();
			foreach($game_data_val as $game_data){
				$game_val['game_id'] = $game_data->game_id;
				$game_val['games_name'] = $game_data->games_name;
				$game_val['games_folder'] = $game_data->games_folder;
				$game_val['core_name'] = $game_data->name;
				$game_val['subject_name'] = $game_data->subject_name;
				$gamearray[] = $game_val;
			}			$games ['games'] = $gamearray;
			
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $games,
			] ); 
		}
	}

	
	public function getbygamegrade(){
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
		$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
		if(empty($game_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Game Id is null'
			] );
		}
		
		$gamecoredetails = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesDatabase.game_id as games_id',
				'GamesDatabase.games_name as games_name',
				'GamesDatabase.games_folder as games_folder',
				'GamesDatabase.daily_tips as daily_tips',
				'Grade.grade_name as grade_name',
				'GamesCoreframeMap.grade_id as grade_id'
			))->from('GamesCoreframeMap')
			->leftjoin('CoreFrameworks','GamesCoreframeMap.framework_id = CoreFrameworks.id')
			->leftjoin('Grade','GamesCoreframeMap.grade_id = Grade.id')
			->leftjoin('Subject','GamesCoreframeMap.subject_id = Subject.id')
			->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
			->inwhere("GamesCoreframeMap.game_id",array($game_id))
			->getQuery ()->execute ();
			
			$gamecorearray = array ();
			foreach($gamecoredetails as $value){
				$game_value['games_id'] = $value -> games_id;
				$game_value['grade_id'] = $value -> grade_id;
				$game_value['games_name'] = $value -> games_name;
				$game_value['games_folder'] = $value -> games_folder;
				$game_value['daily_tips'] = $value -> daily_tips;
				$gamecorearray[] = $game_value;
			}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamecorearray
			] );
	}
	
	public function getbystandared(){
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
		$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
		if(empty($game_id)){
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Game Id is null'
			] );
		}
		$gamestandaerdvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'distinct GamesCoreframeMap.game_id as games_id',
				'GamesCoreframeMap.subject_id as subject_id',
				'GamesCoreframeMap.framework_id as framework_id',
				'GamesCoreframeMap.standard_id as standard_id',
				'GamesCoreframeMap.gamecoretype as gamecoretype',
				'GamesCoreframeMap.grade_id as grade_id',
			))->from('GamesCoreframeMap')
			->inwhere("GamesCoreframeMap.game_id",array($game_id))
			->getQuery ()->execute ();
			$gamestandaredarray = array();
			foreach($gamestandaerdvalue as $gamecorestand){
				
				$gamestandindicat = $this->modelsManager->createBuilder ()->columns ( array (
				'GamesCoreframeMap.id as id',
				'GamesCoreframeMap.game_id as games_ids',
				'GamesCoreframeMap.grade_id as grade_id',
				'GamesCoreframeMap.subject_id as subject_id',
				'GamesCoreframeMap.framework_id as framework_id',
				'CoreFrameworks.name as name',
				'Subject.subject_name as subject_name',
				'Standard.standard_name as standard_name',
				'Indicators.indicator_name as indicator_name',
				'GamesCoreframeMap.indicator_id as indicator_id',
			))->from('GamesCoreframeMap')
			->leftjoin('CoreFrameworks','GamesCoreframeMap.framework_id = CoreFrameworks.id')
			->leftjoin('Grade','GamesCoreframeMap.grade_id = Grade.id')
			->leftjoin('Subject','GamesCoreframeMap.subject_id = Subject.id')
			->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
			->leftjoin('Standard','GamesCoreframeMap.standard_id = Standard.id')
			->leftjoin('Indicators','GamesCoreframeMap.indicator_id = Indicators.id')
			->inwhere("GamesCoreframeMap.game_id",array($gamecorestand -> games_id))
			->inwhere("GamesCoreframeMap.gamecoretype",array($gamecorestand -> gamecoretype))
			->inwhere("GamesCoreframeMap.framework_id",array($gamecorestand -> framework_id))
			->inwhere("GamesCoreframeMap.subject_id",array($gamecorestand -> subject_id))
			->inwhere("GamesCoreframeMap.standard_id",array($gamecorestand -> standard_id)) 
			->getQuery ()->execute ();
			
			$gamestandintarray = array();
			foreach($gamestandindicat as $value){
				$game_int_value['id'] = $value->id;
				$game_int_value['games_id'] = $value->games_ids;
				$game_int_value['grade_id'] = $value->grade_id;
				$game_int_value['framework_id'] = $value->framework_id;
				$game_int_value['subject_id'] = $value->subject_id;
				$game_int_value['core_name'] = $value->name;
				$game_int_value['subject_name'] = $value->subject_name;
				$game_int_value['standard_name'] = $value->standard_name;
				$game_int_value['indicator_name'] = $value->indicator_name;
				$game_int_value['indicator_id'] = $value->indicator_id;
				$gamestandintarray[] = $game_int_value;
			}
				$game_stand ['games_id'] = $gamecorestand -> games_id;
				$game_stand ['grade_id'] = $gamecorestand -> grade_id;
				$game_stand ['subject_id'] = $gamecorestand -> subject_id;
				$game_stand ['framework_id'] = $gamecorestand -> framework_id;
				$game_stand ['standard_name'] = $gamecorestand -> standard_name;
				$game_stand ['standard_id'] = $gamecorestand -> standard_id;
				$game_stand ['gamecoretype'] = $gamecorestand -> gamecoretype;
				$game_stand ['Indicators'] = $gamestandintarray;
				$gamestandaredarray[]=$game_stand;
			}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamestandaredarray
			] );
		
	}
	
	public function getgamelistcoremap(){
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
		else{
		$gamecoredetails = $this->modelsManager->createBuilder ()->columns ( array (
				'DISTINCT GamesDatabase.game_id as games_id',
				'GamesDatabase.games_name as games_name',
				'GamesDatabase.games_folder as games_folder',
				'GamesDatabase.daily_tips as daily_tips'
			))->from('GamesCoreframeMap')
			->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
			->inwhere("GamesCoreframeMap.grade_id",array($grade_id))
			->inwhere("GamesCoreframeMap.framework_id",array($framework_id))
			->inwhere("GamesCoreframeMap.subject_id",array($subject_id))
			->getQuery ()->execute ();
			
			$gamecorearray = array ();
			foreach($gamecoredetails as $value){
				$game_value['games_id'] = $value -> games_id;
				$game_value['grade_id'] = $value -> grade_id;
				$game_value['games_name'] = $value -> games_name;
				$game_value['games_folder'] = $value -> games_folder;
				$game_value['daily_tips'] = $value -> daily_tips;
				$gamecorearray[] = $game_value;
			}
		}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamecorearray
			] );	
	}
	
	public function gameimgsave(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the token" 
			] );
		}else if(empty($input_data -> game_id)){
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please select the game" 
			] );

		} else if(empty($input_data -> tina)){
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please check tina section" 
			] );

		} else if(empty($input_data -> rahul)){
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please check rahul section" 
			] );

		}
		
		 else {
			$gameData = $input_data -> tina;
			$gameData2 = $input_data -> rahul;
			foreach($gameData as $value){
				$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'GameQuestionImageMaster.id as id'
				))->from('GameQuestionImageMaster')
				->inwhere("GameQuestionImageMaster.ref_game_id",array($input_data -> game_id))
				->inwhere("GameQuestionImageMaster.tina",array(1))
				->inwhere("GameQuestionImageMaster.object_name",array($value -> file))
				->getQuery ()->execute ();
				if(count($collection2) <= 0){
					$collection = new GameQuestionImageMaster ();
				} else {
					foreach($collection2 as $value2){
						$collection = GameQuestionImageMaster::findFirstByid($value2 -> id);
					}
				}
				$collection -> ref_game_id = $input_data -> game_id;
				$collection -> object_name = $value -> file;
				$collection -> tina = 1;
				$collection -> rahul = 0;
				$collection -> image_name = $value -> object_des;
				$collection -> created_by = $input_data -> user_id;
				if(!$collection->save()){
					return $this->response->setJsonContent(['status' => 'false', 'message' => 'Game image description save failed please check the from', 'data1' => $collection]);
				} 
			}
			foreach($gameData2 as $value){
				$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'GameQuestionImageMaster.id as id'
				))->from('GameQuestionImageMaster')
				->inwhere("GameQuestionImageMaster.ref_game_id",array($input_data -> game_id))
				->inwhere("GameQuestionImageMaster.rahul",array(1))
				->inwhere("GameQuestionImageMaster.object_name",array($value -> file))
				->getQuery ()->execute ();
				if(count($collection2) <= 0){
					$collection = new GameQuestionImageMaster ();
				} else {
					foreach($collection2 as $value2){
						$collection = GameQuestionImageMaster::findFirstByid($value2 -> id);
					}
				}
				$collection -> ref_game_id = $input_data -> game_id;
				$collection -> object_name = $value -> file;
				$collection -> tina = 0;
				$collection -> rahul = 1;
				$collection -> image_name = $value -> object_des;
				$collection -> created_by = $input_data -> user_id;
				if(!$collection->save()){
					return $this->response->setJsonContent(['status' => 'false', 'message' => 'Game image description save failed please check the from', 'data1' => $collection]);
				} 
			}

			return $this->response->setJsonContent(['status' => true, 'message' => 'Game image description save succefully']);
		}
	}
	public function gameimgdes(){
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
				"status" => false,
				"message" => "Please give the token" 
			] );
		} else {
			if($input_data -> tina == 1){
			$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'GameQuestionImageMaster.id as id',
					'GameQuestionImageMaster.image_name as image_name'
				))->from('GameQuestionImageMaster')
				->inwhere("GameQuestionImageMaster.ref_game_id",array($input_data -> game_id))
				->inwhere("GameQuestionImageMaster.tina",array(1))
				->inwhere("GameQuestionImageMaster.object_name",array($input_data -> file))
				->getQuery ()->execute ();
				$gamearray = array();
				if(count($collection2) <= 0){
					$collection['id'] = '';
					$collection['image_name'] = '';
					$gamearray[] = $collection;
				} else {
				foreach($collection2 as $value2){
					$collection['id'] = $value2 -> id;
					$collection['image_name'] = $value2 -> image_name;
					$gamearray[] = $collection;
					}
				}
			return $this->response->setJsonContent(['status' => true, 'data' => $gamearray]);
			}
			if($input_data -> rahul == 1){
			$collection2 = $this->modelsManager->createBuilder ()->columns ( array (
					'GameQuestionImageMaster.id as id',
					'GameQuestionImageMaster.image_name as image_name'
				))->from('GameQuestionImageMaster')
				->inwhere("GameQuestionImageMaster.ref_game_id",array($input_data -> game_id))
				->inwhere("GameQuestionImageMaster.rahul",array(1))
				->inwhere("GameQuestionImageMaster.object_name",array($input_data -> file))
				->getQuery ()->execute ();
				$gamearray = array();
				if(count($collection2) <= 0){
					$collection['id'] = '';
					$collection['image_name'] = '';
					$gamearray[] = $collection;
				} else {
				foreach($collection2 as $value2){
					$collection['id'] = $value2 -> id;
					$collection['image_name'] = $value2 -> image_name;
					$gamearray[] = $collection;
					}
				}
			return $this->response->setJsonContent(['status' => true, 'data' => $gamearray]);
			}

		}
	}
	
}

