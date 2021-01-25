<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class GamesquestionanswerController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

	public function viewall() {
        $subject = GamesQuestionAnswer::find();
        if ($subject):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }
	
	public function update(){
		$input_data = $this->request->getJsonRawBody();
		$question_answer = $input_data -> gameQuestionanswer;
		foreach($question_answer as $value){
			$collection = GamesQuestionAnswer::findFirstByid($value -> id);
			if(!$collection){
				$collection = new GamesQuestionAnswer ();
				$collection -> id = $this ->gamesidgen->getNewId('gameidgen');
				$collection -> game_id = $value->game_id;
				$collection -> question_id = $value -> question_id;
				$collection -> question = $value -> question;
				$collection -> answer = $value -> answer;
				$collection -> game_type = $value -> game_type;
				$collection -> game_type_value = $value -> game_type_value;
				$collection -> answer_des = $value -> answer_des;
				if(!$collection->save()){
					return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
				} 
				else{
					$game_question_taggin = $game_quetion->question_map;
					foreach($game_question_taggin as $questionvalue){
						$indicators_value = $questionvalue->indicators;
						foreach($indicators_value as $indicatorsvalue ){
							$collection1 = new QuestionGameCoreMap();
							$collection1 -> question_wight = $questionvalue->question_wight;
							$collection1 -> game_id = $value->game_id;
							$collection1 -> question_id = $value -> question_id;
							$collection1 -> grade_id = $value -> grade_id;
							$collection1 -> framework_id = $questionvalue->framework_id;
							$collection1 -> subject_id = $questionvalue->subject_id;
							$collection1 -> standard_id = $questionvalue->standard_id;
							$collection1 -> tagging = $questionvalue -> tagging;
							$collection1 -> indicator_id = $indicatorsvalue->indicator_id;
							if(!$collection1->save()){
								return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
							}
						}
					}
				}
			}
			else{
				$collection -> question_id = $value -> question_id;
				$collection -> question = $value -> question;
				$collection -> answer = $value -> answer;
				$collection -> answer_des = $value -> answer_des;
				$collection -> game_type = $value -> game_type;
				$collection -> game_type_value = $value -> game_type_value;
				if(!$collection->save()){
					return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
				}
				else {
					$question_core_map = $value->question_map;
					
					foreach($question_core_map as $mapvalue){
						$indicators = $mapvalue->indicators;
						
						foreach($indicators as $indicatorvalue){
							$indicator = QuestionGameCoreMap::findFirstByid($indicatorvalue->id);
							if(!$indicator){
								$indicator = new QuestionGameCoreMap();
								
							}
							$indicator -> question_wight = $mapvalue->question_wight;
							$indicator -> game_id = $value->game_id;
							$indicator -> question_id = $value -> id;
							$indicator -> grade_id = $value -> grade_id;
							$indicator -> framework_id = $mapvalue->framework_id;
							$indicator -> subject_id = $mapvalue->subject_id;
							$indicator -> standard_id = $mapvalue->standard_id;
							$indicator -> tagging = $mapvalue -> tagging;
							$indicator -> indicator_id = $indicatorvalue->indicator_id;
							 if(!$indicator->save()){
								return $this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
							}
						}
					}
					
				}
				
			}
		}
		return $this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);	
	}
	
	public function getbygameid(){
		$input_data = $this->request->getJsonRawBody();
		$collection = $this->modelsManager->createBuilder ()->columns ( array (
			'GamesQuestionAnswer.id as id',
			'GamesQuestionAnswer.question_id as question_id',
			'GamesQuestionAnswer.question as question',
			'GamesQuestionAnswer.answer as answer',
			'GamesQuestionAnswer.game_type as game_type',
			'GamesQuestionAnswer.game_type_value as game_type_value',
			'GamesQuestionAnswer.answer_des as answer_des',
		))->from('GamesQuestionAnswer')
		->inwhere('GamesQuestionAnswer.game_id',array($input_data->game_id))
		->getQuery ()->execute ();
		$questionarray = array();
		if(!$collection){
			return $this->response->setJsonContent(['status' => false, 'Message' => 'game id is null']);
		}
		else{
			foreach($collection as $value){
				$game_question = $this->modelsManager->createBuilder ()->columns ( array (
					'DISTINCT QuestionGameCoreMap.framework_id as framework_id',
					'QuestionGameCoreMap.subject_id as subject_id',
					'QuestionGameCoreMap.standard_id as standard_id',
					'QuestionGameCoreMap.tagging as tagging',
					'QuestionGameCoreMap.question_wight as question_wight',
				))->from('QuestionGameCoreMap')
				->inwhere('QuestionGameCoreMap.question_id',array($value->id))
				->inwhere('QuestionGameCoreMap.game_id',array($input_data->game_id))
				->getQuery ()->execute ();
				$question_maparray = array();
				foreach($game_question as $question_core){
					$game_question_int = $this->modelsManager->createBuilder ()->columns ( array (
						'QuestionGameCoreMap.id as id',
						'QuestionGameCoreMap.indicator_id as indicator_id',
					))->from('QuestionGameCoreMap')
					->inwhere('QuestionGameCoreMap.question_id',array($value->id))
					->inwhere('QuestionGameCoreMap.game_id',array($input_data->game_id))
					->inwhere('QuestionGameCoreMap.tagging',array($question_core->tagging))
					->getQuery ()->execute ();
					$indicatorarray = array();
					foreach($game_question_int as $indicator){
						$indicator_data['id'] = $indicator->id;
						$indicator_data['indicator_id'] = $indicator->indicator_id;
						$indicatorarray[] = $indicator_data;
					}
					$question_core_data['framework_id'] = $question_core->framework_id;
					$question_core_data['subject_id'] = $question_core->subject_id;
					$question_core_data['standard_id'] = $question_core->standard_id;
					$question_core_data['tagging'] = $question_core->tagging;
					$question_core_data['question_wight'] = $question_core->question_wight;
					$question_core_data['indicators'] = $indicatorarray;
					$question_maparray[] = $question_core_data;
				}
				$game_question_data['id'] = $value->id;
				$game_question_data['question_id'] = $value->question_id;
				$game_question_data['question'] = $value->question;
				$game_question_data['answer'] = $value->answer;
				$game_question_data['game_type'] = $value->game_type;
				$game_question_data['game_type_value'] = $value->game_type_value;
				$game_question_data['answer_des'] = $value->answer_des;
				$game_question_data['question_map'] = $question_maparray;
				$questionarray[] = $game_question_data;
			}
			return $this->response->setJsonContent(['status' => true, 'data' => $questionarray ]);
		}
		
	}
}
