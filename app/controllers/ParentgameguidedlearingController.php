<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ParentgameguidedlearingController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
   public function viewall() {
        $game_data_val = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearning.learning_model as learning_model',
				'ParentGamesDatabase.games_name as games_name',
				'CoreFrameworks.name as name',
				'Subject.subject_name as subject_name',
				'ParentGuidedLearningDayGameMap.day_id as day_id',
				'ParentGuidedLearningDayGameMap.framework_id as framework_id',
				'ParentGuidedLearningDayGameMap.subject_id as subject_id',
				'ParentGuidedLearningDayGameMap.guided_learning_id as guided_learning_id',
				'ParentGuidedLearningDayGameMap.game_id as game_id',
			))->from('ParentGuidedLearningDayGameMap')
			->leftjoin('CoreFrameworks','ParentGuidedLearningDayGameMap.framework_id = CoreFrameworks.id')
			->leftjoin('GuidedLearning','ParentGuidedLearningDayGameMap.guided_learning_id = GuidedLearning.id')
			->leftjoin('Subject','ParentGuidedLearningDayGameMap.subject_id = Subject.id')
			->leftjoin('ParentGamesDatabase','ParentGuidedLearningDayGameMap.game_id = GamesDatabase.id')
			->orderBy('ParentGuidedLearningDayGameMap.id DESC')
			->getQuery ()->execute ();
			$gamearray = array();
			foreach($game_data_val as $game_data){
				$game_val['learning_model'] = $game_data->learning_model;
				$game_val['games_name'] = $game_data->games_name;
				$game_val['core_name'] = $game_data->name;
				$game_val['subject_name'] = $game_data->subject_name;
				$game_val['day_id'] = $game_data->day_id;
				$game_val['framework_id'] = $game_data->framework_id;
				$game_val['subject_id'] = $game_data->subject_id;
				$game_val['guided_learning_id'] = $game_data->guided_learning_id;
				$game_val['game_id'] = $game_data->game_id;
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
