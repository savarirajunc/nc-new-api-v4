<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;
require BASE_PATH.'/vendor/autoload.php';
use Aws\S3\Exception\S3Exception;
class GamesdatabasenewController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
         $subject = GamesDatabase::find();
        if ($subject):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif; 
    }
	
	public function gamesanswers() {
        $subject3 = GamesAnswers::find();
        if ($subject3):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject3		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

     public function gameviewall() {
        $subject2 = GamesCoreframeMap::find();
        if ($subject2):
            return Json_encode($subject2);
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
        if (empty($id)){
            return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
		}
        else{
            $collection = GamesDatabase::findFirstByid($id);
			$gamearray = array();
			$gamearray[] = $collection;
			return $this->response->setJsonContent(['status' => true, 'data' => $gamearray]);
		}
    }

    /**
     * This function using to create GamesDatabase information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
        $messages = $validation->validate($input_data);
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
        else{
            $collection = new GamesDatabase();
            $collection->id = $this ->gamesidgen->getNewId('gameidgen');
            $collection->game_id = $collection->id;
            $collection->status = 1;
			$collection->games_name = $input_data->games_name;
			$collection->games_folder = $input_data->games_folder;
			$collection->daily_tips = $input_data->daily_tips;
			$collection->game_type = $collection->games_name;
            $collection->created_at = date ( 'Y-m-d H:i:s' );
            $collection->created_by = $collection->id;
            $collection->modified_at = date ( 'Y-m-d H:i:s' );
            if ($collection->save()){
				$game_cor_val = $input_data -> gameCoreFrame;
					foreach($game_cor_val as $value ){
						$game_cor_map = new GamesCoreframeMap();
						$game_cor_map -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map -> grade_id = $input_data->grade_id;
						$game_cor_map -> standard_id = $value->standard_id;
						$game_cor_map -> gamecoretype = $value->gamecoretype;
						$game_cor_map -> indicator_id = $value->indicator_id;
						$game_cor_map -> framework_id = $value->framework_id;
						$game_cor_map -> subject_id = $value->subject_id;
						$game_cor_map -> game_id = $collection->id;
						$game_cor_map->save();
						$game_cor_map2 = new GamesCoreframeMap();
						$game_cor_map2 -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map2 -> grade_id = $game_cor_map -> grade_id;
						$game_cor_map2 -> standard_id = $game_cor_map -> standard_id;
						$game_cor_map2 -> gamecoretype = $game_cor_map -> gamecoretype;
						$game_cor_map2 -> indicator_id = $value->indicator_id1;
						$game_cor_map2 -> framework_id = $game_cor_map -> framework_id;
						$game_cor_map2 -> subject_id = $game_cor_map -> subject_id;
						$game_cor_map2 -> game_id = $collection->id;
						$game_cor_map2-> save();
						$game_cor_map3 = new GamesCoreframeMap();
						$game_cor_map3 -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map3 -> grade_id = $game_cor_map -> grade_id;
						$game_cor_map3 -> standard_id = $game_cor_map -> standard_id;
						$game_cor_map3 -> gamecoretype = $game_cor_map -> gamecoretype;
						$game_cor_map3 -> indicator_id = $value->indicator_id2;
						$game_cor_map3 -> framework_id = $game_cor_map -> framework_id;
						$game_cor_map3 -> subject_id = $game_cor_map -> subject_id;
						$game_cor_map3 -> game_id = $collection->id;
						$game_cor_map3->save();
						$game_cor_map4 = new GamesCoreframeMap();
						$game_cor_map4 -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map4 -> grade_id = $game_cor_map -> grade_id;
						$game_cor_map4 -> standard_id = $game_cor_map -> standard_id;
						$game_cor_map4 -> gamecoretype = $game_cor_map -> gamecoretype;
						$game_cor_map4 -> indicator_id = $value->indicator_id3;
						$game_cor_map4 -> framework_id = $game_cor_map -> framework_id;
						$game_cor_map4 -> subject_id = $game_cor_map -> subject_id;
						$game_cor_map4 -> game_id = $collection->id;
						$game_cor_map4->save();
					}
					$game_quetion_answer = $input_data -> gameQuestionanswer;
						foreach($game_quetion_answer as $game_quetion){
								$question_answer = new GamesQuestionAnswer();
								$question_answer -> id = $this ->gamesidgen->getNewId('gameidgen');
								$question_answer -> question_id = $game_quetion->question_id;
								$question_answer -> question = $game_quetion->question;
								$question_answer -> answer = $game_quetion->answer;
								$question_answer -> answer_des = $game_quetion->answer_des;
								$question_answer -> game_id = $collection->id;
								if(! $question_answer -> save ()){
									return $this->response->setJsonContent ( [ 
											'status' => false,
											'message' => 'Failed' 
									] );
								}
						}
						return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
						}
					else{
						return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
					}
			}
    }

    /**
     * This function using to GamesDatabase information edit
     */
	 
	public function questionanswer(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation();
        /* $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('game_id', new PresenceOf(['message' => 'game_id is required']));
        $validation->add('status', new PresenceOf(['message' => 'status is required']));
        $validation->add('created_at', new PresenceOf(['message' => 'created_at is required']));
        $validation->add('created_by', new PresenceOf(['message' => 'created_by is required']));
        $validation->add('modified_at', new PresenceOf(['message' => 'modified_at is required'])); */
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
        else:
			$question_answer = new GamesQuestionAnswer();
			$question_answer -> id = $this ->gamesidgen->getNewId('gameidgen');
			$question_answer -> question_id = $input_data->questionid;
			$question_answer -> question = $input_data->question;
			$question_answer -> answer = $input_data->answer;
			$question_answer -> answer_des = $input_data->answer_des;
			$question_answer -> game_id = $input_data->game_id;
			if($question_answer -> save ()){
			$question_answer2 = new GamesQuestionAnswer();
			$question_answer2 -> id = $this ->gamesidgen->getNewId('gameidgen');
			$question_answer2 -> question_id = $input_data->questionid2;
			$question_answer2 -> question = $input_data->question2;
			$question_answer2 -> answer = $input_data->answer2;
			$question_answer2 -> answer_des = $input_data->answer_des2;
			$question_answer2 -> game_id = $input_data->game_id;
			if($question_answer2 -> save ()){
			$question_answer3 = new GamesQuestionAnswer();
			$question_answer3 -> id = $this ->gamesidgen->getNewId('gameidgen');
			$question_answer3 -> question_id = $input_data->questionid3;
			$question_answer3 -> question = $input_data->question3;
			$question_answer3 -> answer = $input_data->answer3;
			$question_answer3 -> answer_des = $input_data->answer_des3;
			$question_answer3 -> game_id = $input_data->game_id;
			if($question_answer3 -> save ()){
			$question_answer4 = new GamesQuestionAnswer();
			$question_answer4 -> id = $this ->gamesidgen->getNewId('gameidgen');
			$question_answer4 -> question_id = $input_data->questionid4;
			$question_answer4 -> question = $input_data->question4;
			$question_answer4 -> answer = $input_data->answer4;
			$question_answer4 -> answer_des = $input_data->answer_des4;
			$question_answer4 -> game_id = $input_data->game_id;
			if($question_answer4 -> save ()){
			return $this->response->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
			}
			return $this->response->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
			}
			return $this->response->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
			} 
			return $this->response->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
			}
			else{
				return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
			}
		endif;
	}
    public function update() {

        $input_data = $this->request->getJsonRawBody();
        $game_id = isset($input_data->games_id) ? $input_data->games_id : '';
        if (empty($game_id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $collection = GamesDatabase::findFirstBygame_id($game_id);
                if ($collection):
                    $collection->games_name = $input_data->games_name;
					$collection->games_folder = $input_data->games_folder;
					$collection->daily_tips = $input_data->daily_tips;
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
	
	
	/*public function getsignedgameurl(){
		$input_data = $this->request->getJsonRawBody ();
		$profile = 'default';
		$path = APP_PATH . '/config/credentials_new.ini';
		
		$provider = CredentialProvider::ini ( $profile, $path );
		$provider = CredentialProvider::memoize ( $provider );
		
		// Instantiate an Amazon S3 client.
		/* $sharedConfig = new S3Client ( [ 
				'version' => 'latest',
				'region' => 'ap-south-1',
				'credentials' => $provider 
		] );
		
		$sharedConfig = [
        'region'  => 'ap-south-1',
        'version' => 'latest',
		'credentials' => $provider
		]; //I have AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY as environment variables

		$s3Client = new Aws\S3\S3Client($sharedConfig);

		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => 'games.nidarachildren.com',
			'Key'    => $input_data->game_url
		]);

		$request = $s3Client->createPresignedRequest($cmd, '+10 minutes');
		$presignedUrl = (string) $request->getUri();
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'data' => $presignedUrl
		] );
	}*/
	
	
	/**
	 * This function using delete kids caregiver information
	 */
	public function delete() {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null' 
			] );
		 else :
			$collection = GamesDatabase::findFirstByid ( $id );
			if ($collection) :
				if ($collection->delete ()) :
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'Message' => 'Record has been deleted succefully ' 
					] );
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Data could not be deleted' 
					] );
				endif;
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }
    
	public function savegamequsans(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation ();
			$validation->add ( 'game_id', new PresenceOf ( [ 
					'message' => 'Game id is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			/* $validation->add ( 'answers', new PresenceOf ( [ 
					'message' => 'Please give the answers' 
			] ) ); */
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => $result
				] );
			}
			else {
				$qut_ans_game = GamesAnswers::findFirstBygame_id($input_data -> game_id);
				if(!$qut_ans_game){
					$qut_ans = new GamesAnswers();
					$qut_ans -> id = $this->gamesidgen->getNewId ( 'answers');
				}
				else if($qut_ans_game){
					$qut_ans_kid = GamesAnswers::findFirstBynidara_kid_profile_id($input_data -> nidara_kid_profile_id);
					if(!$qut_ans_kid){
						$qut_ans = new GamesAnswers();
						$qut_ans -> id = $this->gamesidgen->getNewId ( 'answers');
					}
					else if($qut_ans_kid){
						$qut_ans_question_id = $this->modelsManager->createBuilder ()->columns ( array (
								'GamesAnswers.nidara_kid_profile_id as nidara_kid_profile_id',
								'GamesAnswers.questions_no as questions_no',
							) )->from('GamesAnswers')
							->inwhere("GamesAnswers.nidara_kid_profile_id",array($input_data -> nidara_kid_profile_id))
							->inwhere("GamesAnswers.questions_no",array($input_data -> question_id))
							->getQuery ()->execute ();
							
						if(count($qut_ans_question_id) == 0){
							$qut_ans = new GamesAnswers();
							$qut_ans -> id = $this->gamesidgen->getNewId ( 'answers');
						}
					}
				}
				$qut_ans -> session_id = $input_data->session_id;
				$qut_ans -> game_id = $input_data -> game_id;
				$qut_ans -> nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
				$qut_ans -> questions_no = $input_data -> question_id;
				$qut_ans -> answers = $input_data -> options;
				$qut_ans -> time = $input_data -> time;
				if($qut_ans->save ()){
					return $this->response->setJsonContent(['status' => true, 'message' => 'Answer save successfully']);
				}
				else{
					return $this->response->setJsonContent(['status' => false, 'message' => 'Answer save error']);
				}
			}
	}
	public function savegamestatus(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation ();
			$validation->add ( 'gameId', new PresenceOf ( [ 
					'message' => 'Game id is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			/* $validation->add ( 'answers', new PresenceOf ( [ 
					'message' => 'Please give the answers' 
			] ) ); */
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => $result
				] );
			}
			else{
				$kidstatus = KidsGamesStatus::findFirstBygame_id ( $input_data -> gameId );
									

				if (! $kidstatus) {
					$kidstatus = new KidsGamesStatus ();
					$kidstatus->id = $this->gamesidgen->getNewId ( 'kidgamestatus' );
					$kidstatus->game_id = $input_data -> gameId;
					$kidstatus->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
				} 
				
				$kidstatus-> guided_learning_games_map_id = $input_data->guided_learning_games_map_id;
				if ($input_data->current_status == 'quit') {
					$kidstatus->current_status = "quit";
				} else {
					$kidstatus->current_status = "completed";
				}
				if($kidstatus->save ()){
				
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => $kidstatus
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Error'
					] );
				}
			}
	}
	
     /**
     * Save game result
     */
	public function savegamesresult() {
		try {
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
			$input_data = $this->request->getJsonRawBody ();
			if (empty ( $input_data )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Please give the same result'
				] );
			}
			$validation = new Validation ();
			$validation->add ( 'game_id', new PresenceOf ( [ 
					'message' => 'Game id is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			$validation->add ( 'answers', new PresenceOf ( [ 
					'message' => 'Please give the answers' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => $result
				] );
			}
			foreach ( $input_data->answers as $answer ) {
				if(!isset($answer->options)){
					return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "Please give the options"
					] );	
				}
				foreach ( $answer->options as $option ) {
					$optionobj =Options::findFirstByid($option);
					$answers = new Answers ();
					$answers->id = $this->gamesidgen->getNewId ( 'answers' );
					$answers->questions_id = $answer->question_id;
					$answers->session_id = $input_data->session_id;
					$answers->is_correct = $optionobj->is_answer;
					$answers->options_id = $option;
					$answers->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$answers->created_at = date ( 'Y-m-d H:i:s' );
					$answers->created_by = 1;
					$answers->save ();
				}
			}
			// Save the result status for kid
			$kidstatus = KidsGamesStatus::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
			if (! $kidstatus) {
				$kidstatus = new KidsGamesStatus ();
				$kidstatus->id = $this->gamesidgen->getNewId ( 'kidgamestatus' );
				$kidstatus->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
			}
			$gamemapid=$this->getGuidedLearningId($input_data->game_id);
			$kidstatus->guided_learning_games_map_id = $gamemapid->guided_learning_schedule_id;
			if ($input_data->current_status == 'quit') {
				$kidstatus->current_status = "quit";
			} else {
				$kidstatus->current_status = "completed";
			}
			$kidstatus->save ();
			
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Game saved successfully' 
			] );
		} catch ( Exception $e ) {echo $e->getMessage();
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while saving the datas' 
			] );
		}
	}
	
	
	public function getgameinfo(){
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => 'Hi'
			] );
			/* $game_data_val = $this->modelsManager->createBuilder ()->columns ( array (
				'(DISTINCT GamesCoreframeMap.game_id as game_id)',
				'GamesDatabase.games_name as games_name',
				'Grade.grade_name as grade_name',
				'CoreFrameworks.name as name',
				'Subject.subject_name as subject_name',
			))->from('GamesCoreframeMap')
			->leftjoin('CoreFrameworks','GamesCoreframeMap.framework_id = CoreFrameworks.id')
			->leftjoin('Grade','GamesCoreframeMap.grade_id = Grade.id')
			->leftjoin('Subject','GamesCoreframeMap.subject_id = Subject.id')
			->leftjoin('GamesDatabase','GamesCoreframeMap.game_id = GamesDatabase.id')
			->orderBy('GamesCoreframeMap.game_id DESC')->getQuery ()->execute ();
			$gamearray = array();
			foreach($game_data_val as $game_data){
				$game_val['game_id'] = $game_data->game_id;
				$game_val['games_name'] = $game_data->games_name;
				$game_val['grade_name'] = $game_data->grade_name;
				$game_val['core_name'] = $game_data->name;
				$game_val['subject_name'] = $game_data->subject_name;
				$gamearray[] = $game_val;
			}
			return $this->response->setJsonContent ( [
				'status' => true,
				'data' => $gamearray
			] ); */
	}
	
	
	
	/**
	 * Get Game Map id
	 * @param integer $gameid
	 * @return array
	 */
	 
	 
	public function getgamedata(){
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => 'Hi'
			] );
	}
	public function getGuidedLearningId($gameid){
		$gamemap = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearningDayGameMap.id',
				'GuidedLearningDayGameMap.day_guided_learning_id',
		) )->from ( 'GamesDatabase' )
		->join ( 'GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
		->inwhere ( "GamesDatabase.game_id", array (
				$gameid
		) )->getQuery ()->execute ();
		$guided_learning_map = array ();
		foreach ( $gamemap as $guided_learning_map ) {
			return $guided_learning_map;
		}
	}
	
	/**
	 * Get answer status
	 * @param object $answer
	 * @return number
	 */
	public function getlessonstatus($answer) {
		$gamestatus = $this->modelsManager->createBuilder ()->columns ( array (
				'Questions.id as question_id',
				'Options.id as option_id',
				"Options.is_answer",
				"Options.is_multi_answer" 
		) )->from ( 'Questions' )->leftjoin ( 'Options', 'Options.questions_id=Questions.id' )
		->inwhere ( "Questions.id", array (
				$answer->question_id 
		) )->inwhere ( "Options.id", $answer->options )->getQuery ()->execute ();
		$wrong_answer = 0;
		foreach ( $gamestatus as $game ) {
			if (empty ( $game->is_answer )) {
				$wrong_answer ++;
			}
		}
		return $wrong_answer;
	}
	
	/**
	 * Get Game info
	 * @return string
	 */
	public function getgameinfobygameid() {
		try {
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
			$input_data = $this->request->getJsonRawBody ();
			$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
			if (empty ( $game_id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Please give the game id' 
				] );
			}
			if (empty ( $input_data->nidara_kid_profile_id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Please give the kid id' 
				] );
			}
			$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
					'Questions.id as question_id',
					'GamesDatabase.game_type' 
			) )->from ( 'Questions' )->leftjoin ( 'QuestionsTagging', 'QuestionsTagging.questions_id=Questions.id' )
			->leftjoin ( 'Indicators', 'QuestionsTagging.indicators_id=Indicators.id' )
			->leftjoin ( 'GamesTagging', 'Indicators.id=GamesTagging.indicators_id' )
			->leftjoin ( 'GamesDatabase', 'GamesTagging.games_database_id=GamesDatabase.id' )
			->orderBy ( 'Questions.id' )->inwhere ( "GamesDatabase.game_id", array (
					$game_id 
			) )->getQuery ()->execute ();
			$gamedatas = array ();
			$i = 1;
			$game_name = $this->getGameNameByGameId ( $game_id );
			$optionssdataarray = array ();
			foreach ( $gamedatabase as $gamedata ) {
				$options = Options::findByquestions_id ( $gamedata->question_id );
				$optionssdataarray = array ();
				foreach ( $options as $option ) {
					$optionssdata ['option_id'] = $option->id;
					$optionssdata ['option'] = $option->option;
					$optionssdata ['is_correct'] = $option->is_answer;
					$optionssdata ['is_image'] = $option->is_image;
					$optionssdata ['image_path'] = $option->id.'.png';
					$optionssdataarray [] = $optionssdata;
				}
				$questionsdata ['options'] = $optionssdataarray;
				$questionsdata ['questions_id'] = $gamedata->question_id;
				$questionsdatas [] = $questionsdata;
			}
			$session_id = $this->getSessionId ( $game_id, $input_data->nidara_kid_profile_id );
			$questionaries ['game_id'] = $game_id;
			$questionaries ['session_id'] = $session_id;
			$questionaries ['questionaries'] = $questionsdatas;
			return $this->response->setJsonContent ( $questionaries );
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Error while getting the datas'.$e->getMessage() 
			]);
		}
	}
	
	/**
	 * Save session id
	 */
	public function getSessionMainId(){
	$gamesesstion->session_id = uniqid ();
	return $this->response->setJsonContent ([ 
					'status' => true,
					'data' => $gamesesstion->session_id 
			]);
	}
	/**
	 * Save game history
	 */
	public function getSessionId() {
		//$gamemapid = $this->getGuidedLearningId ( $gameid );
		$input_data = $this->request->getJsonRawBody();
		$gamehistory = new GameHistory ();
		$gamehistory->id = $this->gamesidgen->getNewId ( 'gameshistory' );
		$gamehistory->session_id = $input_data->session_id;
		$gamehistory->nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
		$gamehistory->guided_learning_games_map_id = $input_data->guided_learning_games_map_id;
		$gamehistory->created_at = date ( 'Y-m-d H:i:s' );
		$gamehistory->created_by = 1;
		if($gamehistory->save ()){
			return $this->response->setJsonContent ([ 
					'status' => true,
					'message' => 'Game saved successfully' 
			]);
		}
		else{
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => $gamehistory 
			]);
		}
		
		//return $gamehistory->session_id;
	}
	
	/**
	 * Get Game name by game id
	 * @param string $gameid
	 * @return value
	 *
	public function getGameNameByGameId($gameid) {
		$gamearray = array (
				"5992eaf7c114b" => "A",
				"599006c9dd128" => "one",
				"599007029d6df" => "five",
				"599c561d37d82" => "Emotion motion",
				"599c54be3f0f1" => "Nutrition",
				"599c5402348c6" => "Yoga",
				"599c51fc3b5b4" => "Brain Game",
				"599c515ea3952" => "Write Number 5",
				"599c446c91889" => "Dinosaurs",
				"59903520328af" => "In The Sky",
				"599033e356d49" => "My Body",
				"59900e7c907ee" => "Big and Small"
		);
		return $gamearray [$gameid];
	}
	
	**
	 * Dummy function for test
	 */
	public function dummydata() {
		$json = file_get_contents ( APP_PATH . "/library/gamesdata/games.json" );
		return $this->response->setJsonContent ( json_decode ( $json, true ) );
	}
	
}
