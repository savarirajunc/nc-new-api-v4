<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class GameanswersController extends \Phalcon\Mvc\Controller
{

    public function index()
    {

    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall()
    {
        $subject = GamesAnswers::find();
        if ($subject):
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $subject]);

        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    /*
     * Fetch Record from database based on ID :-
    */

    public function getbyid($id = null)
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
        else:
            $collection = GamesDatabase::findFirstByid($id);
            if ($collection):
                return Json_encode($collection);
            else:
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'Message' => 'Data not found']);
            endif;
        endif;
    }

    /**
     * This function using to create GamesDatabase information
     */
    public function create()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        /**
         * This object using valitaion
         */
        $validation = new Validation();
        $messages = $validation->validate($input_data);
        if (count($messages))
        {
            foreach ($messages as $message):
                $result[] = $message->getMessage();
            endforeach;
            return $this
                ->response
                ->setJsonContent($result);
        }
        else
        {
            $collection = new GamesDatabase();
            $collection->id = $this
                ->gamesidgen
                ->getNewId('gameidgen');
            $collection->game_id = $collection->id;
            $collection->status = 1;
            $collection->games_name = $input_data->games_name;
            $collection->games_folder = $input_data->games_folder;
            $collection->daily_tips = $input_data->daily_tips;
            $collection->game_type = $collection->games_name;
            $collection->created_at = date('Y-m-d H:i:s');
            $collection->created_by = $collection->id;
            $collection->modified_at = date('Y-m-d H:i:s');
            if ($collection->save())
            {
                $game_cor_map = new GamesCoreframeMap();
                $game_cor_map->id = $collection->id;
                $game_cor_map->grade_id = $input_data->grade_id;
                $game_cor_map->standard_id = $input_data->standard_id;
                $game_cor_map->indicator_id = $input_data->indicator_id;
                $game_cor_map->framework_id = $input_data->framework_id;
                $game_cor_map->subject_id = $input_data->subject_id;
                $game_cor_map->game_id = $collection->id;
                $game_cor_map->save();
                $game_cor_map2 = new GamesCoreframeMap();
                $game_cor_map2->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $game_cor_map2->grade_id = $game_cor_map->grade_id;
                $game_cor_map2->standard_id = $game_cor_map->standard_id;
                $game_cor_map2->indicator_id = $input_data->indicator_id2;
                $game_cor_map2->framework_id = $game_cor_map->framework_id;
                $game_cor_map2->subject_id = $game_cor_map->subject_id;
                $game_cor_map2->game_id = $collection->id;
                $game_cor_map2->save();
                $game_cor_map3 = new GamesCoreframeMap();
                $game_cor_map3->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $game_cor_map3->grade_id = $game_cor_map->grade_id;
                $game_cor_map3->standard_id = $game_cor_map->standard_id;
                $game_cor_map3->indicator_id = $input_data->indicator_id3;
                $game_cor_map3->framework_id = $game_cor_map->framework_id;
                $game_cor_map3->subject_id = $game_cor_map->subject_id;
                $game_cor_map3->game_id = $collection->id;
                $game_cor_map3->save();
                $question_answer = new GamesQuestionAnswer();
                $question_answer->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $question_answer->question_id = $input_data->questionid;
                $question_answer->question = $input_data->question;
                $question_answer->answer = $input_data->answer;
                $question_answer->answer_des = $input_data->answer_des;
                $question_answer->game_id = $collection->id;
                $question_answer->save();
                $question_answer2 = new GamesQuestionAnswer();
                $question_answer2->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $question_answer2->question_id = $input_data->questionid2;
                $question_answer2->question = $input_data->question2;
                $question_answer2->answer = $input_data->answer2;
                $question_answer2->answer_des = $input_data->answer_des2;
                $question_answer2->game_id = $collection->id;
                $question_answer2->save();
                $question_answer3 = new GamesQuestionAnswer();
                $question_answer3->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $question_answer3->question_id = $input_data->questionid3;
                $question_answer3->question = $input_data->question3;
                $question_answer3->answer = $input_data->answer3;
                $question_answer3->answer_des = $input_data->answer_des3;
                $question_answer3->game_id = $collection->id;
                $question_answer3->save();
                $question_answer4 = new GamesQuestionAnswer();
                $question_answer4->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $question_answer4->question_id = $input_data->questionid4;
                $question_answer4->question = $input_data->question4;
                $question_answer4->answer = $input_data->answer4;
                $question_answer4->answer_des = $input_data->answer_des4;
                $question_answer4->game_id = $collection->id;
                $question_answer4->save();
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => 'succefully']);
            }
            else
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Failed']);
            }
        }
    }

    /**
     * This function using to GamesDatabase information edit
     */

    public function questionanswer()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $validation = new Validation();
        /* $validation->add('id', new PresenceOf(['message' => 'id is required']));
        $validation->add('game_id', new PresenceOf(['message' => 'game_id is required']));
        $validation->add('status', new PresenceOf(['message' => 'status is required']));
        $validation->add('created_at', new PresenceOf(['message' => 'created_at is required']));
        $validation->add('created_by', new PresenceOf(['message' => 'created_by is required']));
        $validation->add('modified_at', new PresenceOf(['message' => 'modified_at is required'])); */
        $messages = $validation->validate($input_data);
        if (count($messages)):
            foreach ($messages as $message):
                $result[] = $message->getMessage();
            endforeach;
            return $this
                ->response
                ->setJsonContent($result);
        else:
            $question_answer = new GamesQuestionAnswer();
            $question_answer->id = $this
                ->gamesidgen
                ->getNewId('gameidgen');
            $question_answer->question_id = $input_data->questionid;
            $question_answer->question = $input_data->question;
            $question_answer->answer = $input_data->answer;
            $question_answer->answer_des = $input_data->answer_des;
            $question_answer->game_id = $input_data->game_id;
            if ($question_answer->save())
            {
                $question_answer2 = new GamesQuestionAnswer();
                $question_answer2->id = $this
                    ->gamesidgen
                    ->getNewId('gameidgen');
                $question_answer2->question_id = $input_data->questionid2;
                $question_answer2->question = $input_data->question2;
                $question_answer2->answer = $input_data->answer2;
                $question_answer2->answer_des = $input_data->answer_des2;
                $question_answer2->game_id = $input_data->game_id;
                if ($question_answer2->save())
                {
                    $question_answer3 = new GamesQuestionAnswer();
                    $question_answer3->id = $this
                        ->gamesidgen
                        ->getNewId('gameidgen');
                    $question_answer3->question_id = $input_data->questionid3;
                    $question_answer3->question = $input_data->question3;
                    $question_answer3->answer = $input_data->answer3;
                    $question_answer3->answer_des = $input_data->answer_des3;
                    $question_answer3->game_id = $input_data->game_id;
                    if ($question_answer3->save())
                    {
                        $question_answer4 = new GamesQuestionAnswer();
                        $question_answer4->id = $this
                            ->gamesidgen
                            ->getNewId('gameidgen');
                        $question_answer4->question_id = $input_data->questionid4;
                        $question_answer4->question = $input_data->question4;
                        $question_answer4->answer = $input_data->answer4;
                        $question_answer4->answer_des = $input_data->answer_des4;
                        $question_answer4->game_id = $input_data->game_id;
                        if ($question_answer4->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
                        }
                        return $this
                            ->response
                            ->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
                    }
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
                }
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => 'Game Maping successfully']);
            }
            else
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Failed']);
            }
        endif;
    }
    public function update($id = null)
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';

        if (empty($id)):
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $validation->add('id', new PresenceOf(['message' => 'id is required']));
            $validation->add('game_id', new PresenceOf(['message' => 'game_idis required']));
            $validation->add('status', new PresenceOf(['message' => 'statusis required']));
            $validation->add('created_at', new PresenceOf(['message' => 'created_atis required']));
            $validation->add('created_by', new PresenceOf(['message' => 'created_byis required']));
            $validation->add('modified_at', new PresenceOf(['message' => 'modified_atis required']));
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this
                    ->response
                    ->setJsonContent($result);
            else:
                $collection = GamesDatabase::findFirstByid($id);
                if ($collection):
                    $collection->id = $input_data->id;
                    $collection->game_id = $input_data->game_id;
                    $collection->status = $input_data->status;
                    $collection->created_at = $input_data->created_at;
                    $collection->created_by = $input_data->created_by;
                    $collection->modified_at = $input_data->modified_at;
                    if ($collection->save()):
                        return $this
                            ->response
                            ->setJsonContent(['status' => true, 'message' => 'succefully']);
                    else:
                        return $this
                            ->response
                            ->setJsonContent(['status' => false, 'message' => 'Failed']);
                    endif;
                else:
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'message' => 'Invalid id']);
                endif;
            endif;
        endif;
    }

    /**
     * This function using delete kids caregiver information
     */
    public function delete()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)):
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $collection = GamesDatabase::findFirstByid($id);
            if ($collection):
                if ($collection->delete()):
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'Message' => 'Record has been deleted succefully ']);
                else:
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'Message' => 'Data could not be deleted']);
                endif;
            else:
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }

    public function savegamequsans()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $validation = new Validation();
        $validation->add('game_id', new PresenceOf(['message' => 'Game id is required']));
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Please give the kid id']));
        /* $validation->add ( 'answers', new PresenceOf ( [
        'message' => 'Please give the answers'
        ] ) ); */
        $messages = $validation->validate($input_data);
        if (count($messages))
        {
            foreach ($messages as $message)
            {
                $result[] = $message->getMessage();
            }
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => $result]);
        }
        else
        {
            $qut_ans_game = GamesAnswers::findFirstBygame_id($input_data->game_id);
            if (!$qut_ans_game)
            {
                $qut_ans = new GamesAnswers();
                $qut_ans->id = $this
                    ->gamesidgen
                    ->getNewId('answers');
            }
            else if ($qut_ans_game)
            {
                $qut_ans_kid = GamesAnswers::findFirstBynidara_kid_profile_id($input_data->nidara_kid_profile_id);
                if (!$qut_ans_kid)
                {
                    $qut_ans = new GamesAnswers();
                    $qut_ans->id = $this
                        ->gamesidgen
                        ->getNewId('answers');
                }
                else if ($qut_ans_kid)
                {
                    $qut_ans_question_id = GamesAnswers::findFirstByquestions_no($input_data->question_id);
                    if (!$qut_ans_question_id)
                    {
                        $qut_ans = new GamesAnswers();
                        $qut_ans->id = $this
                            ->gamesidgen
                            ->getNewId('answers');
                    }
                }
            }
            $qut_ans->session_id = $input_data->session_id;
            $qut_ans->game_id = $input_data->game_id;
            $qut_ans->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            $qut_ans->questions_no = $input_data->question_id;
            $qut_ans->answers = $input_data->options;
            $qut_ans->time = $input_data->time;
            if ($qut_ans->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => 'Answer save successfully']);
            }
            else
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Answer save error']);
            }
        }
    }
    public function savegamestatus()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $validation = new Validation();
        $validation->add('gameId', new PresenceOf(['message' => 'Game id is required']));
        $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Please give the kid id']));
        /* $validation->add ( 'answers', new PresenceOf ( [
        'message' => 'Please give the answers'
        ] ) ); */
        $messages = $validation->validate($input_data);
        if (count($messages))
        {
            foreach ($messages as $message)
            {
                $result[] = $message->getMessage();
            }
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => $result]);
        }
        else
        {
            $kidstatus = KidsGamesStatus::findFirstBygame_id($input_data->gameId);

            if (!$kidstatus)
            {
                $kidstatus = new KidsGamesStatus();
                $kidstatus->id = $this
                    ->gamesidgen
                    ->getNewId('kidgamestatus');
                $kidstatus->game_id = $input_data->gameId;
                $kidstatus->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            }

            $kidstatus->guided_learning_games_map_id = $input_data->guided_learning_games_map_id;
            if ($input_data->current_status == 'quit')
            {
                $kidstatus->current_status = "quit";
            }
            else
            {
                $kidstatus->current_status = "completed";
            }
            if ($kidstatus->save())
            {

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'message' => $kidstatus]);
            }
            else
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Error']);
            }
        }
    }

    /**
     * Save game result
     */
    public function savegamesresult()
    {
        try
        {
            $headers = $this
                ->request
                ->getHeaders();
            if (empty($headers['Token']))
            {
                return $this
                    ->response
                    ->setJsonContent(["status" => false, "message" => "Please give the token"]);
            }
            /* $baseurl = $this->config->baseurl;
            $token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
            if ($token_check->status != 1) {
            return $this->response->setJsonContent ( [
            "status" => false,
            "message" => "Invalid User"
            ] );
            } */
            $input_data = $this
                ->request
                ->getJsonRawBody();
            if (empty($input_data))
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Please give the same result']);
            }
            $validation = new Validation();
            $validation->add('game_id', new PresenceOf(['message' => 'Game id is required']));
            $validation->add('nidara_kid_profile_id', new PresenceOf(['message' => 'Please give the kid id']));
            $validation->add('answers', new PresenceOf(['message' => 'Please give the answers']));
            $messages = $validation->validate($input_data);
            if (count($messages))
            {
                foreach ($messages as $message)
                {
                    $result[] = $message->getMessage();
                }
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => $result]);
            }
            foreach ($input_data->answers as $answer)
            {
                if (!isset($answer->options))
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'message' => "Please give the options"]);
                }
                foreach ($answer->options as $option)
                {
                    $optionobj = Options::findFirstByid($option);
                    $answers = new Answers();
                    $answers->id = $this
                        ->gamesidgen
                        ->getNewId('answers');
                    $answers->questions_id = $answer->question_id;
                    $answers->session_id = $input_data->session_id;
                    $answers->is_correct = $optionobj->is_answer;
                    $answers->options_id = $option;
                    $answers->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
                    $answers->created_at = date('Y-m-d');
                    $answers->created_by = 1;
                    $answers->save();
                }
            }
            // Save the result status for kid
            $kidstatus = KidsGamesStatus::findFirstBynidara_kid_profile_id($input_data->nidara_kid_profile_id);
            if (!$kidstatus)
            {
                $kidstatus = new KidsGamesStatus();
                $kidstatus->id = $this
                    ->gamesidgen
                    ->getNewId('kidgamestatus');
                $kidstatus->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
            }
            $gamemapid = $this->getGuidedLearningId($input_data->game_id);
            $kidstatus->guided_learning_games_map_id = $gamemapid->guided_learning_schedule_id;
            if ($input_data->current_status == 'quit')
            {
                $kidstatus->current_status = "quit";
            }
            else
            {
                $kidstatus->current_status = "completed";
            }
            $kidstatus->save();

            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Game saved successfully']);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Error while saving the datas']);
        }
    }

    /**
     * Get Game Map id
     * @param integer $gameid
     * @return array
     */
    public function getGuidedLearningId($gameid)
    {
        $gamemap = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'GuidedLearningDayGameMap.id',
            'GuidedLearningDayGameMap.day_guided_learning_id',
        ))
            ->from('GamesDatabase')
            ->join('GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id')
            ->inwhere("GamesDatabase.game_id", array(
            $gameid
        ))->getQuery()
            ->execute();
        $guided_learning_map = array();
        foreach ($gamemap as $guided_learning_map)
        {
            return $guided_learning_map;
        }
    }

    /**
     * Get answer status
     * @param object $answer
     * @return number
     */
    public function getlessonstatus($answer)
    {
        $gamestatus = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'Questions.id as question_id',
            'Options.id as option_id',
            "Options.is_answer",
            "Options.is_multi_answer"
        ))
            ->from('Questions')
            ->leftjoin('Options', 'Options.questions_id=Questions.id')
            ->inwhere("Questions.id", array(
            $answer->question_id
        ))
            ->inwhere("Options.id", $answer->options)
            ->getQuery()
            ->execute();
        $wrong_answer = 0;
        foreach ($gamestatus as $game)
        {
            if (empty($game->is_answer))
            {
                $wrong_answer++;
            }
        }
        return $wrong_answer;
    }

    /**
     * Get Game info
     * @return string
     */
    public function getgameinfobygameid()
    {
        try
        {
            $headers = $this
                ->request
                ->getHeaders();
            if (empty($headers['Token']))
            {
                return $this
                    ->response
                    ->setJsonContent(["status" => false, "message" => "Please give the token"]);
            }
            /* $baseurl = $this->config->baseurl;
            $token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
            if ($token_check->status != 1) {
            return $this->response->setJsonContent ( [
            "status" => false,
            "message" => "Invalid User"
            ] );
            } */
            $input_data = $this
                ->request
                ->getJsonRawBody();
            $game_id = isset($input_data->game_id) ? $input_data->game_id : '';
            if (empty($game_id))
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'Message' => 'Please give the game id']);
            }
            if (empty($input_data->nidara_kid_profile_id))
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'Message' => 'Please give the kid id']);
            }
            $gamedatabase = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'Questions.id as question_id',
                'GamesDatabase.game_type'
            ))
                ->from('Questions')
                ->leftjoin('QuestionsTagging', 'QuestionsTagging.questions_id=Questions.id')
                ->leftjoin('Indicators', 'QuestionsTagging.indicators_id=Indicators.id')
                ->leftjoin('GamesTagging', 'Indicators.id=GamesTagging.indicators_id')
                ->leftjoin('GamesDatabase', 'GamesTagging.games_database_id=GamesDatabase.id')
                ->orderBy('Questions.id')
                ->inwhere("GamesDatabase.game_id", array(
                $game_id
            ))->getQuery()
                ->execute();
            $gamedatas = array();
            $i = 1;
            $game_name = $this->getGameNameByGameId($game_id);
            $optionssdataarray = array();
            foreach ($gamedatabase as $gamedata)
            {
                $options = Options::findByquestions_id($gamedata->question_id);
                $optionssdataarray = array();
                foreach ($options as $option)
                {
                    $optionssdata['option_id'] = $option->id;
                    $optionssdata['option'] = $option->option;
                    $optionssdata['is_correct'] = $option->is_answer;
                    $optionssdata['is_image'] = $option->is_image;
                    $optionssdata['image_path'] = $option->id . '.png';
                    $optionssdataarray[] = $optionssdata;
                }
                $questionsdata['options'] = $optionssdataarray;
                $questionsdata['questions_id'] = $gamedata->question_id;
                $questionsdatas[] = $questionsdata;
            }
            $session_id = $this->getSessionId($game_id, $input_data->nidara_kid_profile_id);
            $questionaries['game_id'] = $game_id;
            $questionaries['session_id'] = $session_id;
            $questionaries['questionaries'] = $questionsdatas;
            return $this
                ->response
                ->setJsonContent($questionaries);
        }
        catch(Exception $e)
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => 'Error while getting the datas' . $e->getMessage() ]);
        }
    }

    /*
    public function getgameresult(){
    $input_data = $this->request->getJsonRawBody ();
    $headers = $this->request->getHeaders ();
    if (empty ( $headers ['Token'] )) {
    return $this->response->setJsonContent ( [
        "status" => false,
        "message" => "Please give the token" 
    ] );
    }
    else{
    $today_date = date('Y-m-d');
    $game_get = $this->modelsManager->createBuilder ()->columns ( array (
    'DISTINCT GamesAnswers.session_id as session_id',
    'GamesAnswers.game_id as game_ids',
    'GamesDatabase.games_name as games_name',
    'GuidedLearningDayGameMap.subject_id as subject_id',
    'GuidedLearningDayGameMap.framework_id as framework_id',
    ))->from('GamesAnswers')
    ->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
    ->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
    ->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
    ->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.created_at',array($today_date))
    ->getQuery ()->execute ();
    $gamedata_array = array();
    
    foreach($game_get as $value){
    $gamedetails = $this->modelsManager->createBuilder ()->columns ( array (
    'DISTINCT GamesAnswers.questions_no as questions_no',
    ))->from('GamesAnswers')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.session_id ',array($value->session_id))
    ->orderBy ( 'GamesAnswers.questions_no' )
    ->getQuery ()->execute ();
    $total_time = 0;
    $gamedetailsarray = array();
    foreach($gamedetails as $gameanswer){
     if($gameanswer->questions_no != 0){ 
    $game_result = $this->modelsManager->createBuilder ()->columns ( array (
        'GamesAnswers.questions_no as questions_nos',
        'GamesAnswers.answers as answers',
        'GamesAnswers.time as time',
    ))->from('GamesAnswers')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.session_id ',array($value->session_id))
    ->getQuery ()->execute ();
    //$time = 0;
    $getanswer;
    $answer = 0;
    $total = 0;
    $time = 0;
    $game_result_array = array();
    foreach($game_result as $result_value){
        if($result_value->questions_nos != 0){
        $game_que_ans = $this->modelsManager->createBuilder ()->columns ( array (
            'GamesQuestionAnswer.question_id as question_ids',
            'GamesQuestionAnswer.question as question',
            'GamesQuestionAnswer.game_type_value as game_type_value',
        ))->from('GamesQuestionAnswer')
        ->inwhere('GamesQuestionAnswer.game_id',array($input_data->game_id))
        ->inwhere('GamesQuestionAnswer.question_id',array($result_value->questions_nos))
        ->getQuery ()->execute ();
        foreach($game_que_ans as $game_result_value){
            
        }
        $testcontent = strtolower($game_result_value->question);
        if($result_value->answers == 1){
            $answer += 1;
            $total += 1;
            
            if((strpos ($testcontent , 'colour') !== false) || (strpos ($testcontent , 'color') !== false)){
                $getanswer = 'Colored within the lines';
            }
            else if(strpos ($testcontent , 'trace') !== false){
                $getanswer = 'Traced on the lines';
            }
            else if(strpos ($testcontent , 'draw') !== false){
                $getanswer = 'Drawing was Correct';
            }
            else{
                $getanswer = 'Selected the correct answer';
            }
            
        }
        else if($result_value->answers > 1){
            if($game_result_value->game_type_value == $result_value->answers){
                $answer += 1;
                $total += 1;
                $getanswer = $result_value->answers;
            }
            else{
                $total += 1;
                $getanswer = $result_value->answers;
            }
        }
        else{
            $total += 1;
            if((strpos ($testcontent , 'colour') !== false) || (strpos ($testcontent , 'color') !== false)){
                $getanswer = 'Colored outside the lines';
            }
            else if(strpos ($testcontent , 'trace') !== false){
                $getanswer = 'Traced outside the lines';
            }
            else if(strpos ($testcontent , 'draw') !== false){
                $getanswer = 'Drawing was wrong';
            }
            else{
                $getanswer = 'Selected the wrong answer';
            }
        }
        $game_result_data['questions_no'] = $result_value->questions_nos;
        $game_result_data['testcontent'] = $testcontent;
        $game_result_data['question'] = ucfirst($game_result_value->question);
        $game_result_data['game_type_value'] = $game_result_value->game_type_value;
        $game_result_data['answers'] = $getanswer;
        $game_result_data['time'] = ($result_value->time);
        $time += $result_value->time;
        $game_result_array[] = $game_result_data;
    }
    }
    }
    }
    $game_data['created_at'] = $value->created_ats;         
    $game_data['game_answers'] = $game_result_array;
    $game_data['Total'] = $total ;
    $game_data['answer'] = $answer ;
    $game_data['wrong'] = $total - $answer;
    $game_data['game_id'] = $value->game_ids;
    $game_data['game_time'] = $time;
    $game_data['game_name'] = $value->games_name;
    $game_data['subject_id'] = $value->subject_id;
    $game_data['framework_id'] = $value->framework_id;
    $gamedata_array [] = $game_data;
    }
    return $this->response->setJsonContent ( [
    'status' => true,
    'data' => $gamedata_array
    ] );
    }
    } */

    /*
    
    
    public function getgameresult(){
    $input_data = $this->request->getJsonRawBody ();
    $headers = $this->request->getHeaders ();
    if (empty ( $headers ['Token'] )) {
    return $this->response->setJsonContent ( [
        "status" => false,
        "message" => "Please give the token" 
    ] );
    }
    else{
    $today_date = date('Y-m-d');
    $game_get = $this->modelsManager->createBuilder ()->columns ( array (
    'DISTINCT GamesAnswers.session_id as session_id',
    'GamesAnswers.game_id as game_ids',
    'GamesDatabase.games_name as games_name',
    'GuidedLearningDayGameMap.subject_id as subject_id',
    'GuidedLearningDayGameMap.framework_id as framework_id',
    ))->from('GamesAnswers')
    ->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
    ->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
    ->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
    ->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.created_at',array($today_date))
    ->getQuery ()->execute ();
    $gamedata_array = array();
    
    foreach($game_get as $value){
    $gamedetails = $this->modelsManager->createBuilder ()->columns ( array (
    'DISTINCT GamesAnswers.id as id',
    'GamesAnswers.game_id as game_id',
    'GamesAnswers.session_id as session_id',
    'GamesAnswers.questions_no as questions_no',
    'GamesAnswers.answers as answers',
    'GamesAnswers.time as time',
    'GamesAnswers.slide_no as slide_no',
    'GamesCoreframeMap.id as id',
    'Standard.standard_name as standard_name',
    'GamesCoreframeMap.gamecoretype as gamecoretype',
    'GamesDatabase.games_name as games_name'
    ))->from('GamesAnswers')
    ->leftjoin('GamesDatabase', 'GamesDatabase.id = GamesAnswers.game_id')
    ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
    ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.session_id ',array($value->session_id))
    ->groupBy('GamesAnswers.id')
    ->orderBy ( 'GamesAnswers.slide_no' )
    ->getQuery ()->execute ();
    $total_time = 0;
    $questioncount = 0;
    $answercount = 0;
    $worngcount = 0;
    $gamedetailsarray = array();
    $time = 0;
    foreach($gamedetails as $gameanswer){
    
    $time += $gameanswer -> time;
    //$reseltarray[] = $value3;
    if($gameanswer -> slide_no == 2){
        $game_data['Intro_Slide'] = $gameanswer -> time;
    }
    if($gameanswer -> slide_no == 3){
        $game_data['Learning_Slide'] = $gameanswer -> time;
    }
    if($gameanswer -> slide_no == 4){
        $game_data['Game_Intro'] = $gameanswer -> time;
    }
    if($gameanswer -> gamecoretype == 1){
        $game_data['Primary_Tagging'] = $gameanswer -> standard_name ;
    }
    if($gameanswer -> gamecoretype == 2){
        $game_data['Secondary_Tagging'] = $gameanswer -> standard_name;
    }
    //$game_result_array[] = $game_result_data;
    }
    
    $game_data['over_all_time'] = $time;
    $game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
    'GamesQuestionAnswer.game_type_value as game_type_value',
    'GamesQuestionAnswer.question_id as question_id',
    'GamesQuestionAnswer.question as question',
    'GamesQuestionAnswer.answer as answer',
    ))->from('GamesQuestionAnswer')
    ->inwhere('GamesQuestionAnswer.game_id',array($input_data -> game_id))
    ->getQuery ()->execute ();
    
    foreach($game_question_answer as $questionvalue){
    $questioncount += 1;
        $game_answer = $this->modelsManager->createBuilder ()->columns ( array (
            'GamesAnswers.id as id',
            'GamesAnswers.questions_no as questions_no',
            'GamesAnswers.answers as answers',
            'GamesAnswers.time as time',
            'GamesAnswers.slide_no as slide_no',
        ))->from("GamesAnswers")
        ->inwhere ('GamesAnswers.session_id ',array($value->session_id))
        ->inwhere('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
        ->inwhere('GamesAnswers.game_id',array($input_data -> game_id))
        ->inwhere('GamesAnswers.questions_no',array($questionvalue -> question_id))
        ->groupBy('GamesAnswers.id')
        ->getQuery()->execute ();
        if(count($game_answer) > 0){
            foreach($game_answer as $value6){
    
                }
                if($value6 -> answers > 1){
                    if($questionvalue -> game_type_value > 10){
                        if($questionvalue -> game_type_value == $value6 -> answers){
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = $questionvalue -> question . ': ' . $value6 -> answers;
                            $data2['time'] = $value6 -> time;
                        } else if($questionvalue -> game_type_value < $value6 -> answers){
                            $data2['question'] = $questionvalue -> question;
                            if($questionvalue -> game_type_value > 20){
                                $qustionvalue = 'How many times did the child click on the monster';
                            } else {
                                $qustionvalue = 'How many objects did the child click on';
                            }
                            $data2['qu_answer'] = $qustionvalue . ': ' . $value6 -> answers . ' <br> How many times did the child miss - hand eye coordination ' . ($value6 -> answers - $questionvalue -> game_type_value);
                            $data2['time'] = $value6 -> time;
                        } else {
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = 'child does not complete the activity' . $value6 -> answers;
                            $data2['time'] = $value6 -> time;
                        }
                    } else {
                        if($questionvalue -> game_type_value ==  $value6 -> answers){
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue -> game_type_value / $value6 -> answers) . ' try';
                            $data2['time'] = $value6 -> time;
                        } else if($questionvalue -> game_type_value < $value6 -> answers){
                             $numbercheck = ($value6 -> answers / $questionvalue -> game_type_value);
                            if(($numbercheck %  $questionvalue -> game_type_value) == 0){
                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';
                            } else {
                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                            }
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = $getAnsewer;
                            $data2['time'] = $value6 -> time;
                        }
                    }
                } else {
                    
                    $testcontent = strtolower($questionvalue -> question );
                    if($value6 -> answers == 1){
                        $answercount += 1;
                        if((strpos ($testcontent , 'colour') !== false) || (strpos ($testcontent , 'color') !== false)){
                            $getanswer = 'Colored within the lines';
                        }
                        else if(strpos ($testcontent , 'trace') !== false){
                            $getanswer = 'Traced on the lines';
                        }
                        else{
                            $getanswer = 'Selected the correct answer';
                        }
                    } else {
                        $worngcount += 1;
                        if((strpos ($testcontent , 'colour') !== false) || (strpos ($testcontent , 'color') !== false)){
                            $getanswer = 'Colored out side the lines';
                        }
                        else if(strpos ($testcontent , 'trace') !== false){
                            $getanswer = 'Traced on out side the lines';
                        }
                        else{
                            $getanswer = 'Selected the Worng answer';
                        }
                    }
                    $data2['question'] = $questionvalue -> question;
                    $data2['qu_answer'] = $getanswer;
                    $data2['time'] = $value6 -> time;
                }
            }
        else{
            $data2['question'] = $questionvalue -> question;
            $data2['qu_answer'] = 'Not Answered';
            $data2['time'] = '';
            $worngcount += 1;
        }
        $gamedetailsarray[] = $data2;
    }
    $game_data['question'] = $gamedetailsarray;
    $checkanswer = ($answercount+$worngcount);
    if($questioncount == $checkanswer){
        $game_data['questioncount'] = $questioncount;
        $game_data['answer'] = $answercount;
        $game_data['worngcount'] = $worngcount;
    }
    $game_data['created_at'] = $value->created_ats;         
    $game_data['game_answers'] = $game_result_array;
    $game_data['Total'] = $total ;
    $game_data['answercount'] = $answer ;
    $game_data['wrong'] = $total - $answer;
    $game_data['game_id'] = $value->game_ids;
    $game_data['game_time'] = $time;
    $game_data['game_name'] = $value->games_name;
    $game_data['subject_id'] = $value->subject_id;
    $game_data['framework_id'] = $value->framework_id;
    $gamedata_array [] = $game_data;
    }
    return $this->response->setJsonContent ( [
    'status' => true,
    'data' => $gamedata_array
    ] );
    }
    } */

    /*public function getSecondaryDataOld()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $headers = $this
            ->request
            ->getHeaders();
        if (empty($headers['Token']))
        {
            return $this
                ->response
                ->setJsonContent(["status" => false, "message" => "Please give the token"]);
        }
        else
        {

            $today_date = date('Y-m-d');
            $game_getses = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT KidsGamesStatus.session_id as session_id',
            ))
                ->from('KidsGamesStatus')
                ->inwhere('KidsGamesStatus.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('KidsGamesStatus.created_date', array(
                $today_date
            ))->getQuery()
                ->execute();
            $gamearrayval = array();

            foreach ($game_getses as $ses)
            {
                $i = 1;
                $gamedata_array = array();

                # code...
                

                $game_get = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'Subject.id as subject_id',
                    'Subject.subject_name as subject_name',
                ))
                    ->from('Subject')
                    ->inwhere('Subject.core_type', array(
                    2,
                    4
                ))
                    ->getQuery()
                    ->execute();
                if (count($game_get) > 0)
                {
                    foreach ($game_get as $subjectvalue)
                    {
                        $gamecoredetails = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'GameSecondaryQuestion.id as id',
                            'GameSecondaryQuestion.game_id as games_id',
                            'Standard.standard_name as standard_name',
                            'Indicators.indicator_name as indicator_name',
                            'GameSecondaryQuestion.question_type as question_type',
                            'GameSecondaryQuestion.question as question',
                            'GameSecondaryQuestion.question_id as question_id',
                            'GameSecondaryQuestion.standard as standard',
                            'GameSecondaryQuestion.indicators as indicators',
                        ))
                            ->from('GameSecondaryQuestion')
                            ->leftjoin('Subject', 'GameSecondaryQuestion.subject_id = Subject.id')
                            ->leftjoin('Standard', 'GameSecondaryQuestion.standard = Standard.id')
                            ->leftjoin('Indicators', 'GameSecondaryQuestion.indicators = Indicators.id')
                            ->inwhere('GameSecondaryQuestion.game_id', array(
                            $input_data->game_id
                        ))
                            ->inwhere('GameSecondaryQuestion.subject_id', array(
                            $subjectvalue->subject_id
                        ))
                            ->getQuery()
                            ->execute();
                        $gamevaluearray = array();
                        if (count($gamecoredetails) > 0)
                        {
                            foreach ($gamecoredetails as $secondaryvalue)
                            {
                                $answers = 0;
                                $total = 0;
                                $worng = 0;

                                if ($secondaryvalue->question_type == '4')
                                {

                                    $game_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswers.id as id',
                                        'GamesAnswers.questions_no as questions_no',
                                        'GamesAnswers.answers as answers',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray1 = array();
                                    foreach ($game_answer as $gameansewervalue)
                                    {
                                        if ($gameansewervalue->questions_no != 0)
                                        {
                                            if ($gameansewervalue->answers == 1)
                                            {
                                                $answers = $answers + 1;
                                                $total = $total + 1;
                                            }
                                            else if ($gameansewervalue->answers > 1)
                                            {
                                                $game_question_answer = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                                ))
                                                    ->from('GamesQuestionAnswer')
                                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                                    $gameansewervalue->questions_no
                                                ))
                                                    ->getQuery()
                                                    ->execute();
                                                foreach ($game_question_answer as $questionanswer)
                                                {
                                                    if ($questionanswer->game_type_value == $gameansewervalue->answers)
                                                    {
                                                        $answers = $answers + 1;
                                                        $total = $total + 1;
                                                    }
                                                    else
                                                    {
                                                        $total = $total + 1;
                                                        $worng = $worng + 1;
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $total = $total + 1;
                                                $worng = $worng + 1;
                                            }
                                        }
                                    }
                                    $data1['qu_answer'] = $answers;
                                    $gamedetailsarray1[] = $data1;
                                    $secondarydata['answers'] = $gamedetailsarray1;
                                    $secondarydata['question'] = $secondaryvalue->question;
                                }
                                else if ($secondaryvalue->question_type == '5')
                                {

                                    $game_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswers.id as id',
                                        'GamesAnswers.questions_no as questions_no',
                                        'GamesAnswers.answers as answers',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray2 = array();
                                    foreach ($game_answer as $gameansewervalue)
                                    {
                                        if ($gameansewervalue->questions_no != 0)
                                        {
                                            if ($gameansewervalue->answers == 1)
                                            {
                                                $answers = $answers + 1;
                                                $total = $total + 1;
                                            }
                                            else if ($gameansewervalue->answers > 1)
                                            {
                                                $game_question_answer = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                                ))
                                                    ->from('GamesQuestionAnswer')
                                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                                    $gameansewervalue->questions_no
                                                ))
                                                    ->getQuery()
                                                    ->execute();
                                                foreach ($game_question_answer as $questionanswer)
                                                {
                                                    if ($questionanswer->game_type_value == $gameansewervalue->answers)
                                                    {
                                                        $answers = $answers + 1;
                                                        $total = $total + 1;
                                                    }
                                                    else
                                                    {
                                                        $total = $total + 1;
                                                        $worng = $worng + 1;
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $total = $total + 1;
                                                $worng = $worng + 1;
                                            }

                                        }

                                    }
                                    $data2['qu_answer'] = $worng;
                                    $gamedetailsarray2[] = $data2;
                                    $secondarydata['answers'] = $gamedetailsarray2;
                                    $secondarydata['question'] = $secondaryvalue->question;
                                }
                                else
                                {
                                    $game_question_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesQuestionAnswer.game_type_value as game_type_value',
                                        'GamesQuestionAnswer.question_id as question_id',
                                        'GamesQuestionAnswer.question as question',
                                        'GamesQuestionAnswer.answer as answer',
                                    ))
                                        ->from('GamesQuestionAnswer')
                                        ->inwhere('GamesQuestionAnswer.question_id', array(
                                        $secondaryvalue->question_id
                                    ))
                                        ->inwhere('GamesQuestionAnswer.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray = array();
                                    foreach ($game_question_answer as $questionvalue)
                                    {
                                        $questioncount += 1;
                                        $game_answer = $this
                                            ->modelsManager
                                            ->createBuilder()
                                            ->columns(array(
                                            'GamesAnswers.id as id',
                                            'GamesAnswers.questions_no as questions_no',
                                            'GamesAnswers.answers as answers',
                                            'GamesAnswers.time as time',
                                            'GamesAnswers.slide_no as slide_no',
                                        ))
                                            ->from("GamesAnswers")
                                            ->inwhere('GamesAnswers.created_at', array(
                                            date('Y-m-d')
                                        ))
                                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                            $input_data->nidara_kid_profile_id
                                        ))
                                            ->inwhere('GamesAnswers.game_id', array(
                                            $input_data->game_id
                                        ))
                                            ->inwhere('GamesAnswers.questions_no', array(
                                            $questionvalue->question_id
                                        ))
                                            ->inwhere('GamesAnswers.session_id ', array(
                                            $ses->session_id
                                        ))
                                            ->groupBy('GamesAnswers.id')
                                            ->getQuery()
                                            ->execute();

                                        if (count($game_answer) > 0)
                                        {
                                            foreach ($game_answer as $value6)
                                            {

                                            }
                                            if ($value6->answers > 1)
                                            {
                                                if ($questionvalue->game_type_value > 10)
                                                {
                                                    if ($questionvalue->game_type_value == $value6->answers)
                                                    {
                                                        $data2['question'] = $questionvalue->question;
                                                        $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                                        $data2['time'] = $value6->time;
                                                    }
                                                    else if ($questionvalue->game_type_value < $value6->answers)
                                                    {
                                                        $data2['question'] = $questionvalue->question;
                                                        if ($questionvalue->game_type_value > 20)
                                                        {
                                                            $qustionvalue = 'How many times did the child click on the monster';
                                                        }
                                                        else
                                                        {
                                                            $qustionvalue = 'How many objects did the child click on';
                                                        }
                                                        $data2['qu_answer'] = $qustionvalue . ': ' . $value6->answers . ' <br> How many times did the child miss - hand eye coordination ' . ($value6->answers - $questionvalue->game_type_value);
                                                        $data2['time'] = $value6->time;
                                                    }
                                                    else
                                                    {
                                                        $data2['question'] = $questionvalue->question;
                                                        $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                                        $data2['time'] = $value6->time;
                                                    }
                                                }
                                                else
                                                {
                                                    if ($questionvalue->game_type_value == $value6->answers)
                                                    {
                                                        $data2['question'] = $questionvalue->question;
                                                        $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';
                                                        $data2['time'] = $value6->time;
                                                    }
                                                    else if ($questionvalue->game_type_value < $value6->answers)
                                                    {
                                                        $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                                        if (($numbercheck % $questionvalue->game_type_value) == 0)
                                                        {
                                                            $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';
                                                        }
                                                        else
                                                        {
                                                            $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                                        }
                                                        $data2['question'] = $questionvalue->question;
                                                        $data2['qu_answer'] = $getAnsewer;
                                                        $data2['time'] = $value6->time;
                                                    }
                                                }
                                            }
                                            else
                                            {

                                                $testcontent = strtolower($questionvalue->question);
                                                if ($value6->answers == 1)
                                                {
                                                    $answercount += 1;
                                                    if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                                                    {
                                                        $getanswer = 'Colored within the lines';
                                                    }
                                                    else if (strpos($testcontent, 'trace') !== false)
                                                    {
                                                        $getanswer = 'Traced on the lines';
                                                    }
                                                    else if (strpos($testcontent, 'did you child') !== false)
                                                    {
                                                        $getanswer = 'Yes';
                                                    }
                                                    else
                                                    {
                                                        $getanswer = 'Selected the correct answer';
                                                    }
                                                }
                                                else
                                                {

                                                    if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                                                    {
                                                        $getanswer = 'Colored out side the lines';
                                                        $worngcount += 1;
                                                    }
                                                    else if (strpos($testcontent, 'did you child') !== false)
                                                    {
                                                        $getanswer = 'No';
                                                        $answercount += 1;
                                                    }
                                                    else if (strpos($testcontent, 'trace') !== false)
                                                    {
                                                        $getanswer = 'Traced on out side the lines';
                                                        $worngcount += 1;
                                                    }
                                                    else
                                                    {
                                                        $getanswer = 'Selected the Worng answer';
                                                        $worngcount += 1;
                                                    }
                                                }
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $getanswer;
                                                $data2['time'] = $value6->time;
                                            }
                                        }
                                        else
                                        {
                                            $data2['question'] = $questionvalue->question;
                                            $data2['qu_answer'] = 'Not Answered';
                                            $data2['time'] = '';
                                            $worngcount += 1;
                                        }
                                        $gamedetailsarray[] = $data2;
                                    }
                                    $secondarydata['answers'] = $gamedetailsarray;
                                    foreach ($secondarydata['answers'] as $answervalue)
                                    {

                                    }
                                    $secondarydata['check'] = $answervalue;
                                    $secondarydata['question'] = $secondaryvalue->question . '' . $answervalue->qu_answer;
                                }
                                $secondarydata['standard_name'] = $secondaryvalue->standard_name;
                                $secondarydata['indicator_name'] = $secondaryvalue->indicator_name;

                                $gaemstatus = $gamedetails = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'KidsGamesStatus.id',

                                ))
                                    ->from('KidsGamesStatus')
                                    ->inwhere('KidsGamesStatus.game_id', array(
                                    $input_data->game_id
                                ))
                                    ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                                    $input_data->nidara_kid_profile_id
                                ))
                                    ->inwhere('KidsGamesStatus.session_id ', array(
                                    $ses->session_id
                                ))
                                    ->inwhere('KidsGamesStatus.current_status', array(
                                    1
                                ))
                                    ->getQuery()
                                    ->execute();

                                if (count($gaemstatus) > 0)
                                {
                                    $gamedata_arraystatus["gamestatus"] = true;
                                }
                                else
                                {
                                    $gamedata_arraystatus["gamestatus"] = false;
                                }

                                $gamevaluearray[] = $secondarydata;
                            }
                        }
                        else
                        {
                            continue;
                        }
                        $subjectdata['subject_id'] = $subjectvalue->subject_id;
                        $subjectdata['questionMap'] = $gamevaluearray;
                        $subjectdata['subject_name'] = $subjectvalue->subject_name;
                        $gamedata_array[] = $subjectdata;

                        $i = $i + 1;

                    }
                }
                if ($gamedata_array != [])
                {
                    $gamedata_arraystatus['subjectvalue'] = $gamedata_array;
                    $gamearrayval[] = $gamedata_arraystatus;
                }

            }
            return $this
                ->response
                ->setJsonContent(["status" => true, "data" => $gamearrayval]);
        }
    }*/

    public function getSecondaryData()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $headers = $this
            ->request
            ->getHeaders();
        if (empty($headers['Token']))
        {
            return $this
                ->response
                ->setJsonContent(["status" => false, "message" => "Please give the token"]);
        }
        else
        {

            $today_date = date('Y-m-d');
            $game_getses = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT KidsGamesStatus.session_id as session_id',
            ))
                ->from('KidsGamesStatus')
                ->inwhere('KidsGamesStatus.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('KidsGamesStatus.created_date', array(
                $today_date
            ))->getQuery()
                ->execute();
            $gamearrayval = array();

            foreach ($game_getses as $ses)
            {
                $i = 1;
                $gamedata_array = array();

                # code...
                

                $game_get = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'Subject.id as subject_id',
                    'Subject.subject_name as subject_name',
                ))
                    ->from('Subject')
                    ->inwhere('Subject.core_type', array(
                    2,
                    4
                ))
                    ->getQuery()
                    ->execute();
                if (count($game_get) > 0)
                {
                    foreach ($game_get as $subjectvalue)
                    {
                        $gamecoredetails = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'GameSecondaryQuestion.id as id',
                            'GameSecondaryQuestion.game_id as games_id',
                            'GameSecondaryQuestion.answer_show_type as answer_show_type',
                            'Standard.standard_name as standard_name',
                            'Indicators.indicator_name as indicator_name',
                            'GameSecondaryQuestion.question_type as question_type',
                            'GameSecondaryQuestion.question as question',
                            'GameSecondaryQuestion.question_id as question_id',
                            'GameSecondaryQuestion.standard as standard',
                            'GameSecondaryQuestion.indicators as indicators'
                        ))
                            ->from('GameSecondaryQuestion')
                            ->leftjoin('Subject', 'GameSecondaryQuestion.subject_id = Subject.id')
                            ->leftjoin('Standard', 'GameSecondaryQuestion.standard = Standard.id')
                            ->leftjoin('Indicators', 'GameSecondaryQuestion.indicators = Indicators.id')
                            ->inwhere('GameSecondaryQuestion.game_id', array(
                            $input_data->game_id
                        ))
                            ->inwhere('GameSecondaryQuestion.subject_id', array(
                            $subjectvalue->subject_id
                        ))
                            ->getQuery()
                            ->execute();
                        $gamevaluearray = array();
                        if (count($gamecoredetails) > 0)
                        {

                            foreach ($gamecoredetails as $secondaryvalue)
                            {
                                $answers = 0;
                                $total = 0;
                                $worng = 0;
                                $timecount = 0;

                                if ($secondaryvalue->question_type == '4')
                                {
                                   

                                    $game_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswers.id as id',
                                        'GamesAnswers.questions_no as questions_no',
                                        'GamesAnswers.answers as answers',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray1 = array();
                                    if (count($game_answer))
                                    {
                                        foreach ($game_answer as $gameansewervalue)
                                        {
                                            $timecount = $timecount + $gameansewervalue->time;
                                            if ($gameansewervalue->questions_no != 0)
                                            {

                                                if ($gameansewervalue->answers == 1)
                                                {
                                                    $answers = $answers + 1;
                                                    $total = $total + 1;
                                                }
                                                else if ($gameansewervalue->answers > 1)
                                                {
                                                    $game_question_answer = $this
                                                        ->modelsManager
                                                        ->createBuilder()
                                                        ->columns(array(
                                                        'GamesQuestionAnswer.game_type_value as game_type_value',
                                                    ))
                                                        ->from('GamesQuestionAnswer')
                                                        ->inwhere('GamesQuestionAnswer.game_id', array(
                                                        $input_data->game_id
                                                    ))
                                                        ->inwhere('GamesQuestionAnswer.question_id', array(
                                                        $gameansewervalue->questions_no
                                                    ))
                                                        ->getQuery()
                                                        ->execute();
                                                    foreach ($game_question_answer as $questionanswer)
                                                    {
                                                        if ($questionanswer->game_type_value == $gameansewervalue->answers)
                                                        {
                                                            $answers = $answers + 1;
                                                            $total = $total + 1;
                                                        }
                                                        else
                                                        {
                                                            $total = $total + 1;
                                                            $worng = $worng + 1;
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $total = $total + 1;
                                                    $worng = $worng + 1;
                                                }
                                            }
                                        }
                                    }

                                    if ($secondaryvalue->answer_show_type == '1')
                                    {
                                        $data1['qu_answer'] = $answers;
                                    }

                                    if($secondaryvalue->answer_show_type == '6')
                                    {
                                        $data1['qu_answer'] =0;
                                         $game_answer = $this
                                                ->modelsManager
                                                ->createBuilder()
                                                ->columns(array(
                                                'GameAnswersShootgame.id',
                                                'GameAnswersShootgame.child_id',
                                                'GameAnswersShootgame.game_id',
                                                'GameAnswersShootgame.session_id',
                                                'GameAnswersShootgame.click_count',
                                                'GameAnswersShootgame.time',
                                                'GameAnswersShootgame.create_at',

                                            ))
                                                ->from("GameAnswersShootgame")
                                                ->inwhere('GameAnswersShootgame.child_id', array(
                                                $input_data->nidara_kid_profile_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.game_id', array(
                                                $input_data->game_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.session_id', array(
                                                 $ses->session_id
                                            ))
                                                ->orderby('GameAnswersShootgame.id')
                                                ->getQuery()
                                                ->execute();

            

                                            if (count($game_answer) > 0)
                                            {

                                                $shootgamecheck = true;

                                                $totalcount = array();
                                                $countold = '00:00:00';
                                                $i = 0;
                                                foreach ($game_answer as $valueshoot)
                                                {
                                                    if ($i == 0)
                                                    {
                                                        $count['differentcount'] = "00";
                                                    }
                                                    else
                                                    {
                                                        $count['differentcount'] = date('s', strtotime($valueshoot->time) - strtotime($countold));
                                                    }

                                                    $count['child_id'] = $valueshoot->child_id;
                                                    $count['game_id'] = $valueshoot->game_id;
                                                    $count['click_count'] = $valueshoot->click_count;
                                                    $count['session_id'] = $valueshoot->session_id;
                                                    $countold = $valueshoot->time;

                                                    $i = i + 1;

                                                    $totalcount[] = $count;
                                                }

                                
                                                $secondarydata['time_interval'] = $totalcount;
                                                $data5['total_click_count'] = count($game_answer);
                                                $data5['shootgame'] = $shootgamecheck;

                                            }
                                    }

                                    if ($secondaryvalue->answer_show_type == '2')
                                    {
                                              $game_answer_value = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesQuestionAnswer.id as id',
                                        'GamesAnswers.questions_no',
                                        'GamesQuestionAnswer.answer',
                                        'GamesQuestionAnswer.game_type_value',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->leftjoin('GamesQuestionAnswer', 'GamesQuestionAnswer.question_id = GamesAnswers.questions_no')
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                           ->inwhere('GamesQuestionAnswer.game_id', array(
                                        $input_data->game_id
                                    ))
                                    ->inwhere('GamesAnswers.questions_no', array(
                                       $secondaryvalue->question_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();

                                        if(count($game_answer_value) > 0)
                                        {
                                        if((strpos($secondaryvalue->question,'activity')==true) && $secondaryvalue->question_id==0 && (str_replace(' ', '',$game_answer_value[0]->answer)!='color') && (str_replace(' ', '',$game_answer_value[0]->answer)!='trace'))
                                        {




                                             $data1['qu_answer'] = $timecount;
                                        }
                                        else
                                        {
                                        $data1['qu_answer'] = $game_answer_value[0]->time;
                                        }
                                    }
                                    else
                                    {

                                        	$data1['qu_answer'] = $timecount;
                                        
                                    }


                                       
                                    }
                                    $gamedetailsarray1[] = $data1;
                                    $secondarydata['answers'] = $gamedetailsarray1;
                                    $secondarydata['question'] = $secondaryvalue->question;

                                }
                                else if ($secondaryvalue->question_type == '5')
                                {

                                    $game_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswers.id as id',
                                        'GamesAnswers.questions_no as questions_no',
                                        'GamesAnswers.answers as answers',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray2 = array();
                                    foreach ($game_answer as $gameansewervalue)
                                    {
                                        if ($gameansewervalue->questions_no != 0)
                                        {
                                            if ($gameansewervalue->answers == 1)
                                            {
                                                $answers = $answers + 1;
                                                $total = $total + 1;
                                            }
                                            else if ($gameansewervalue->answers > 1)
                                            {
                                                $game_question_answer = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                                ))
                                                    ->from('GamesQuestionAnswer')
                                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                                    $gameansewervalue->questions_no
                                                ))
                                                    ->getQuery()
                                                    ->execute();
                                                if (count($game_question_answer) > 0)
                                                {
                                                    foreach ($game_question_answer as $questionanswer)
                                                    {
                                                        if ($questionanswer->game_type_value == $gameansewervalue->answers)
                                                        {
                                                            $answers = $answers + 1;
                                                            $total = $total + 1;
                                                        }
                                                        else
                                                        {
                                                            $total = $total + 1;
                                                            $worng = $worng + 1;
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $total = $total + 1;
                                                $worng = $worng + 1;
                                            }

                                        }

                                    }

                                    if ($secondaryvalue->answer_show_type == '1')
                                    {
                                        $data2['qu_answer'] = $worng;
                                    }
                                    $gamedetailsarray2[] = $data2;

                                    $secondarydata['answers'] = $gamedetailsarray2;
                                    $secondarydata['question'] = $secondaryvalue->question;
                                }
                                else
                                {
                                    $game_question_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesQuestionAnswer.game_type_value as game_type_value',
                                        'GamesQuestionAnswer.question_id as question_id',
                                        'GamesQuestionAnswer.question as question',
                                        'GamesQuestionAnswer.answer as answer',
                                    ))
                                        ->from('GamesQuestionAnswer')
                                        ->inwhere('GamesQuestionAnswer.question_id', array(
                                        $secondaryvalue->question_id
                                    ))
                                        ->inwhere('GamesQuestionAnswer.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray = array();
                                    foreach ($game_question_answer as $questionvalue)
                                    {

                                        $showtype = "";

                                        $questioncount += 1;
                                        $game_answer = $this
                                            ->modelsManager
                                            ->createBuilder()
                                            ->columns(array(
                                            'GamesAnswers.id as id',
                                            'GamesAnswers.questions_no as questions_no',
                                            'GamesAnswers.answers as answers',
                                            'GamesAnswers.time as time',
                                            'GamesAnswers.slide_no as slide_no',
                                            'GamesAnswers.object_name',
                                        ))
                                            ->from("GamesAnswers")
                                            ->inwhere('GamesAnswers.created_at', array(
                                            date('Y-m-d')
                                        ))
                                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                            $input_data->nidara_kid_profile_id
                                        ))
                                            ->inwhere('GamesAnswers.game_id', array(
                                            $input_data->game_id
                                        ))
                                            ->inwhere('GamesAnswers.questions_no', array(
                                            $questionvalue->question_id
                                        ))
                                            ->inwhere('GamesAnswers.session_id ', array(
                                            $ses->session_id
                                        ))
                                            ->groupBy('GamesAnswers.id')
                                            ->getQuery()
                                            ->execute();

                                        if (count($game_answer) > 0)
                                        {
                                            foreach ($game_answer as $value6)
                                            {

                                                if ($secondaryvalue->answer_show_type == 3)
                                                {
                                                    $colorval = array();
                                                    $getcolorvalue = $this
                                                        ->modelsManager
                                                        ->createBuilder()
                                                        ->columns(array(
                                                        'GamesAnswersColor.click_count',
                                                    ))
                                                        ->from('GamesAnswersColor')
                                                        ->inwhere("GamesAnswersColor.game_id", array(
                                                        $input_data->game_id
                                                    ))
                                                        ->inwhere("GamesAnswersColor.child_id", array(
                                                        $input_data->nidara_kid_profile_id
                                                    ))
                                                        ->inwhere("GamesAnswersColor.question_id", array(
                                                        $questionvalue->question_id

                                                    ))
                                                        ->inwhere("GamesAnswersColor.slide_no", array(
                                                        $value6->slide_no
                                                    ))
                                                        ->inwhere("GamesAnswersColor.session_id", array(
                                                        $ses->session_id
                                                    ))

                                                        ->getQuery()
                                                        ->execute();

                                                    if (count($getcolorvalue) > 0)
                                                    {
                                                        foreach ($getcolorvalue as $getcolorval)
                                                        {
                                                            $colorgame = true;

                                                            $colorvalue['color'] = $getcolorval->click_count;
                                                            $colorval[] = $colorvalue;

                                                            //$data2['answer'] = $baseurl . $value6->object_name;
                                                            

                                                            
                                                        }

                                                    }
                                                    else
                                                    {
                                                        $colorgame = false;

                                                    }

                                                    $data2['colorgame'] = $colorgame;
                                                    $data2['clickcolor'] = $colorval;
                                                }
                                               
                                                else
                                                {
                                                    $colorgame = false;
                                                    $data2['colorgame'] = $colorgame;
                                                    $data2['clickcolor'] = [];

                                                }

                                                if ($value6->answers > 1)
                                                {

                                                    if ($questionvalue->game_type_value > 10)
                                                    {
                                                        if ($questionvalue->game_type_value == $value6->answers)
                                                        {
                                                            $data2['question'] = $questionvalue->question;
                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {
                                                                $data2['qu_answer'] = $value6->answers;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "Yes";
                                                            }
                                                            // $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                                            // $data2['time'] = $value6->time;
                                                            
                                                        }
                                                        else if ($questionvalue->game_type_value < $value6->answers)
                                                        {
                                                            $data2['question'] = $questionvalue->question;
                                                            if ($questionvalue->game_type_value > 20)
                                                            {

                                                                $qustionvalue = 'How many times did the child click on the monster';
                                                            }
                                                            else
                                                            {
                                                                $qustionvalue = 'How many objects did the child click on';
                                                            }

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {
                                                                $data2['qu_answer'] = $qustionvalue . ': ' . $value6->answers . ' <br> How many times did the child miss - hand eye coordination ' . ($value6->answers - $questionvalue->game_type_value);
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "No";
                                                            }
                                                            // $data2['time'] = $value6->time;
                                                            
                                                        }
                                                        else
                                                        {
                                                            $data2['question'] = $questionvalue->question;

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {

                                                                $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            //$data2['time'] = $value6->time;
                                                            
                                                        }
                                                    }
                                                    else
                                                    {
                                                        if ($questionvalue->game_type_value == $value6->answers)
                                                        {
                                                            $data2['question'] = $questionvalue->question;

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {

                                                                $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "Yes";
                                                            }

                                                            //$data2['time'] = $value6->time;
                                                            
                                                        }
                                                        else if ($questionvalue->game_type_value < $value6->answers)
                                                        {
                                                            $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                                            if (($numbercheck % $questionvalue->game_type_value) == 0)
                                                            {
                                                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';
                                                            }
                                                            else
                                                            {
                                                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                                            }
                                                            $data2['question'] = $questionvalue->question;

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {
                                                                $data2['qu_answer'] = $getAnsewer;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "No";
                                                            }
                                                            // $data2['time'] = $value6->time;
                                                            
                                                        }
                                                    }
                                                }
                                                else
                                                {

                                                    $testcontent = strtolower($questionvalue->question);
                                                    if ($value6->answers == 1)
                                                    {
                                                        $answercount += 1;
                                                        if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                            if($questionvalue->answer == "color")
                                                            {
                                                            $getanswer = 'Colored within the lines';
                                                            }
                                                            else
                                                            {

                                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        
                                                        $getanswer =$getimagevalue[0]->image_name;
                                                    }


                                                                
                                                            }
                                                        }
                                                        else if (strpos($testcontent, 'trace') !== false && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                            $getanswer = 'Traced on the lines';
                                                        }
                                                        else if ($secondaryvalue->answer_show_type == 4)
                                                        {
                                                            $getanswer = 'Yes';
                                                        }
                                                        else
                                                        {
                                                            $getanswer = 'Selected the correct answer';
                                                        }
                                                    }
                                                    else
                                                    {

                                                        if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                              if($questionvalue->answer == "color")
                                                            {
                                                            $getanswer = 'Colored out side the lines';
                                                            }
                                                            else
                                                            {

                                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        
                                                        $getanswer = $getimagevalue[0]->image_name;
                                                    }


                                                                
                                                            }
                                                            
                                                            $worngcount += 1;
                                                        }
                                                        else if ($secondaryvalue->answer_show_type == 4)
                                                        {
                                                            $getanswer = 'No';
                                                            $answercount += 1;
                                                        }
                                                        else if (strpos($testcontent, 'trace') !== false && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                            $getanswer = 'Traced on out side the lines';
                                                            $worngcount += 1;
                                                        }
                                                        else
                                                        {
                                                            $getanswer = 'Selected the Worng answer';
                                                            $worngcount += 1;
                                                        }
                                                    }
                                                    $data2['question'] = $questionvalue->question;
                                                    $data2['qu_answer'] = $getanswer;
                                                    $data2['time'] = $value6->time;
                                                }

                                            }
                                        }
                                        else
                                        {
                                            $data2['question'] = $questionvalue->question;
                                            $data2['qu_answer'] = 'Not Answered';
                                            $data2['time'] = '';
                                            $worngcount += 1;
                                        }
                                        $showtype = "";
                                        if ($secondaryvalue->answer_show_type == 0)
                                        {
                                            $showtype = "color";
                                        }

                                        //$data2 ['answer_show_type'] = $showtype;

                                        
                                        $gamedetailsarray[] = $data2;
                                        


                                    }
                                    $secondarydata['answers'] = $gamedetailsarray;
                                    foreach ($secondarydata['answers'] as $answervalue)
                                    {

                                    }
                                    $secondarydata['check'] = $answervalue;
                                    $secondarydata['question'] = $secondaryvalue->question . '' . $answervalue->qu_answer;
                                }

                                if ($secondaryvalue->answer_show_type == 5 && $secondaryvalue->standard_name == "Problem Solving")
                                                {
                                                    //$colorval = array();
                                        $gametrycount = $this
                                            ->modelsManager
                                            ->createBuilder()
                                            ->columns(array(
                                            'GamesAnswers.id as id',
                                        
                                        ))
                                            ->from("GamesAnswers")
                                            ->inwhere('GamesAnswers.created_at', array(
                                            date('Y-m-d')
                                        ))
                                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                            $input_data->nidara_kid_profile_id
                                        ))
                                            ->inwhere('GamesAnswers.game_id', array(
                                            $input_data->game_id
                                        ))
                                            ->inwhere('GamesAnswers.questions_no', array(
                                            $questionvalue->question_id
                                        ))
                                            ->inwhere('GamesAnswers.session_id ', array(
                                            $ses->session_id
                                        ))
                                            ->inwhere('GamesAnswers.slide_type ', array(
                                           "question"
                                        ))

                                            ->groupBy('GamesAnswers.id')
                                            ->getQuery()
                                            ->execute();

                                                    if (count($gametrycount) > 0)
                                                    {
                                                        
                                                        $secondarydata['gametrycount']=count($gametrycount);
                                                    }
                                                    else
                                                    {
                                                        $secondarydata['gametrycount']=false;
                                                    }
                                                    
                                                }
                                                else
                                                {
                                                    $secondarydata['gametrycount']=false;
                                                }
                                $secondarydata['standard_name'] = $secondaryvalue->standard_name;
                                $secondarydata['indicator_name'] = $secondaryvalue->indicator_name;

                                $gaemstatus = $gamedetails = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'KidsGamesStatus.id',

                                ))
                                    ->from('KidsGamesStatus')
                                    ->inwhere('KidsGamesStatus.game_id', array(
                                    $input_data->game_id
                                ))
                                    ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                                    $input_data->nidara_kid_profile_id
                                ))
                                    ->inwhere('KidsGamesStatus.session_id ', array(
                                    $ses->session_id
                                ))
                                    ->inwhere('KidsGamesStatus.current_status', array(
                                    1
                                ))
                                    ->getQuery()
                                    ->execute();

                                if (count($gaemstatus) > 0)
                                {
                                    $gamedata_arraystatus["gamestatus"] = true;
                                }
                                else
                                {
                                    $gamedata_arraystatus["gamestatus"] = false;
                                }

                                $gamevaluearray[] = $secondarydata;
                                $secondarydata['time_interval']=false;
                            }
                        }
                        else
                        {
                            continue;
                        }
                        $subjectdata['subject_id'] = $subjectvalue->subject_id;
                        $subjectdata['questionMap'] = $gamevaluearray;
                        $subjectdata['subject_name'] = $subjectvalue->subject_name;
                        $gamedata_array[] = $subjectdata;

                        $i = $i + 1;

                    }
                }
                if ($gamedata_array != [])
                {
                    $gamedata_arraystatus['subjectvalue'] = $gamedata_array;
                    $gamearrayval[] = $gamedata_arraystatus;
                }

            }
            return $this
                ->response
                ->setJsonContent(["status" => true, "data" => $gamearrayval]);
        }
    }

   public function getSecondaryDatatest()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $headers = $this
            ->request
            ->getHeaders();
        if (!empty($headers['Token']))
        {
            return $this
                ->response
                ->setJsonContent(["status" => false, "message" => "Please give the token"]);
        }
        else
        {

            $today_date = date('Y-m-d');
            $game_getses = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT KidsGamesStatus.session_id as session_id',
            ))
                ->from('KidsGamesStatus')
                ->inwhere('KidsGamesStatus.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('KidsGamesStatus.created_date', array(
                $today_date
            ))->getQuery()
                ->execute();
            $gamearrayval = array();

            foreach ($game_getses as $ses)
            {
                $i = 1;
                $gamedata_array = array();

                # code...
                

                $game_get = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'Subject.id as subject_id',
                    'Subject.subject_name as subject_name',
                ))
                    ->from('Subject')
                    ->inwhere('Subject.core_type', array(
                    2,
                    4
                ))
                    ->getQuery()
                    ->execute();
                if (count($game_get) > 0)
                {
                    foreach ($game_get as $subjectvalue)
                    {
                        $gamecoredetails = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'GameSecondaryQuestion.id as id',
                            'GameSecondaryQuestion.game_id as games_id',
                            'GameSecondaryQuestion.answer_show_type as answer_show_type',
                            'Standard.standard_name as standard_name',
                            'Indicators.indicator_name as indicator_name',
                            'GameSecondaryQuestion.question_type as question_type',
                            'GameSecondaryQuestion.question as question',
                            'GameSecondaryQuestion.question_id as question_id',
                            'GameSecondaryQuestion.standard as standard',
                            'GameSecondaryQuestion.indicators as indicators'
                        ))
                            ->from('GameSecondaryQuestion')
                            ->leftjoin('Subject', 'GameSecondaryQuestion.subject_id = Subject.id')
                            ->leftjoin('Standard', 'GameSecondaryQuestion.standard = Standard.id')
                            ->leftjoin('Indicators', 'GameSecondaryQuestion.indicators = Indicators.id')
                            ->inwhere('GameSecondaryQuestion.game_id', array(
                            $input_data->game_id
                        ))
                            ->inwhere('GameSecondaryQuestion.subject_id', array(
                            $subjectvalue->subject_id
                        ))
                            ->getQuery()
                            ->execute();
                        $gamevaluearray = array();
                        if (count($gamecoredetails) > 0)
                        {

                            foreach ($gamecoredetails as $secondaryvalue)
                            {
                                $answers = 0;
                                $total = 0;
                                $worng = 0;
                                $timecount = 0;

                                if ($secondaryvalue->question_type == '4')
                                {
                                   

                                    $game_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswers.id as id',
                                        'GamesAnswers.questions_no as questions_no',
                                        'GamesAnswers.answers as answers',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray1 = array();
                                    if (count($game_answer))
                                    {
                                        foreach ($game_answer as $gameansewervalue)
                                        {
                                            $timecount = $timecount + $gameansewervalue->time;
                                            if ($gameansewervalue->questions_no != 0)
                                            {

                                                if ($gameansewervalue->answers == 1)
                                                {
                                                    $answers = $answers + 1;
                                                    $total = $total + 1;
                                                }
                                                else if ($gameansewervalue->answers > 1)
                                                {
                                                    $game_question_answer = $this
                                                        ->modelsManager
                                                        ->createBuilder()
                                                        ->columns(array(
                                                        'GamesQuestionAnswer.game_type_value as game_type_value',
                                                    ))
                                                        ->from('GamesQuestionAnswer')
                                                        ->inwhere('GamesQuestionAnswer.game_id', array(
                                                        $input_data->game_id
                                                    ))
                                                        ->inwhere('GamesQuestionAnswer.question_id', array(
                                                        $gameansewervalue->questions_no
                                                    ))
                                                        ->getQuery()
                                                        ->execute();
                                                    foreach ($game_question_answer as $questionanswer)
                                                    {
                                                        if ($questionanswer->game_type_value == $gameansewervalue->answers)
                                                        {
                                                            $answers = $answers + 1;
                                                            $total = $total + 1;
                                                        }
                                                        else
                                                        {
                                                            $total = $total + 1;
                                                            $worng = $worng + 1;
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $total = $total + 1;
                                                    $worng = $worng + 1;
                                                }
                                            }
                                        }
                                    }

                                    if ($secondaryvalue->answer_show_type == '1')
                                    {
                                        $data1['qu_answer'] = $answers;
                                    }

                                    if($secondaryvalue->answer_show_type == '6')
                                    {
                                        $data1['qu_answer'] =0;
                                         $game_answer = $this
                                                ->modelsManager
                                                ->createBuilder()
                                                ->columns(array(
                                                'GameAnswersShootgame.id',
                                                'GameAnswersShootgame.child_id',
                                                'GameAnswersShootgame.game_id',
                                                'GameAnswersShootgame.session_id',
                                                'GameAnswersShootgame.click_count',
                                                'GameAnswersShootgame.time',
                                                'GameAnswersShootgame.create_at',

                                            ))
                                                ->from("GameAnswersShootgame")
                                                ->inwhere('GameAnswersShootgame.child_id', array(
                                                $input_data->nidara_kid_profile_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.game_id', array(
                                                $input_data->game_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.session_id', array(
                                                 $ses->session_id
                                            ))
                                                ->orderby('GameAnswersShootgame.id')
                                                ->getQuery()
                                                ->execute();

            

                                            if (count($game_answer) > 0)
                                            {

                                                $shootgamecheck = true;

                                                $totalcount = array();
                                                $countold = '00:00:00';
                                                $i = 0;
                                                foreach ($game_answer as $valueshoot)
                                                {
                                                    if ($i == 0)
                                                    {
                                                        $count['differentcount'] = "00";
                                                    }
                                                    else
                                                    {
                                                        $count['differentcount'] = date('s', strtotime($valueshoot->time) - strtotime($countold));
                                                    }

                                                    $count['child_id'] = $valueshoot->child_id;
                                                    $count['game_id'] = $valueshoot->game_id;
                                                    $count['click_count'] = $valueshoot->click_count;
                                                    $count['session_id'] = $valueshoot->session_id;
                                                    $countold = $valueshoot->time;

                                                    $i = i + 1;

                                                    $totalcount[] = $count;
                                                }

                                
                                                $secondarydata['time_interval'] = $totalcount;
                                                $data5['total_click_count'] = count($game_answer);
                                                $data5['shootgame'] = $shootgamecheck;

                                            }
                                    }

                                    if ($secondaryvalue->answer_show_type == '2')
                                    {
                                              $game_answer_value = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesQuestionAnswer.id as id',
                                        'GamesAnswers.questions_no',
                                        'GamesQuestionAnswer.answer',
                                        'GamesQuestionAnswer.game_type_value',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->leftjoin('GamesQuestionAnswer', 'GamesQuestionAnswer.question_id = GamesAnswers.questions_no')
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                           ->inwhere('GamesQuestionAnswer.game_id', array(
                                        $input_data->game_id
                                    ))
                                    ->inwhere('GamesAnswers.questions_no', array(
                                       $secondaryvalue->question_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();

                                        if(count($game_answer_value) > 0)
                                        {
                                        if((strpos($secondaryvalue->question,'activity')==true) && $secondaryvalue->question_id==0 && (str_replace(' ', '',$game_answer_value[0]->answer)!='color') && (str_replace(' ', '',$game_answer_value[0]->answer)!='trace'))
                                        {




                                             $data1['qu_answer'] = $timecount;
                                        }
                                        else
                                        {
                                        $data1['qu_answer'] = $game_answer_value[0]->time;
                                        }
                                    }
                                    else
                                    {

                                            $data1['qu_answer'] = $timecount;
                                        
                                    }


                                       
                                    }
                                    $gamedetailsarray1[] = $data1;
                                    $secondarydata['answers'] = $gamedetailsarray1;
                                    $secondarydata['question'] = $secondaryvalue->question;

                                }
                                else if ($secondaryvalue->question_type == '5')
                                {

                                    $game_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswers.id as id',
                                        'GamesAnswers.questions_no as questions_no',
                                        'GamesAnswers.answers as answers',
                                        'GamesAnswers.time as time',
                                        'GamesAnswers.slide_no as slide_no',
                                    ))
                                        ->from("GamesAnswers")
                                        ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere('GamesAnswers.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere('GamesAnswers.created_at', array(
                                        date('Y-m-d')
                                    ))
                                        ->inwhere('GamesAnswers.session_id ', array(
                                        $ses->session_id
                                    ))
                                        ->groupBy('GamesAnswers.id')
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray2 = array();
                                    foreach ($game_answer as $gameansewervalue)
                                    {
                                        if ($gameansewervalue->questions_no != 0)
                                        {
                                            if ($gameansewervalue->answers == 1)
                                            {
                                                $answers = $answers + 1;
                                                $total = $total + 1;
                                            }
                                            else if ($gameansewervalue->answers > 1)
                                            {
                                                $game_question_answer = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                                ))
                                                    ->from('GamesQuestionAnswer')
                                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                                    $gameansewervalue->questions_no
                                                ))
                                                    ->getQuery()
                                                    ->execute();
                                                if (count($game_question_answer) > 0)
                                                {
                                                    foreach ($game_question_answer as $questionanswer)
                                                    {
                                                        if ($questionanswer->game_type_value == $gameansewervalue->answers)
                                                        {
                                                            $answers = $answers + 1;
                                                            $total = $total + 1;
                                                        }
                                                        else
                                                        {
                                                            $total = $total + 1;
                                                            $worng = $worng + 1;
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $total = $total + 1;
                                                $worng = $worng + 1;
                                            }

                                        }

                                    }

                                    if ($secondaryvalue->answer_show_type == '1')
                                    {
                                        $data2['qu_answer'] = $worng;
                                    }
                                    $gamedetailsarray2[] = $data2;

                                    $secondarydata['answers'] = $gamedetailsarray2;
                                    $secondarydata['question'] = $secondaryvalue->question;
                                }
                                else
                                {
                                    $game_question_answer = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesQuestionAnswer.game_type_value as game_type_value',
                                        'GamesQuestionAnswer.question_id as question_id',
                                        'GamesQuestionAnswer.question as question',
                                        'GamesQuestionAnswer.answer as answer',
                                    ))
                                        ->from('GamesQuestionAnswer')
                                        ->inwhere('GamesQuestionAnswer.question_id', array(
                                        $secondaryvalue->question_id
                                    ))
                                        ->inwhere('GamesQuestionAnswer.game_id', array(
                                        $input_data->game_id
                                    ))
                                        ->getQuery()
                                        ->execute();
                                    $gamedetailsarray = array();
                                    foreach ($game_question_answer as $questionvalue)
                                    {

                                        $showtype = "";

                                        $questioncount += 1;
                                        $game_answer = $this
                                            ->modelsManager
                                            ->createBuilder()
                                            ->columns(array(
                                            'GamesAnswers.id as id',
                                            'GamesAnswers.questions_no as questions_no',
                                            'GamesAnswers.answers as answers',
                                            'GamesAnswers.time as time',
                                            'GamesAnswers.slide_no as slide_no',
                                            'GamesAnswers.object_name',
                                        ))
                                            ->from("GamesAnswers")
                                            ->inwhere('GamesAnswers.created_at', array(
                                            date('Y-m-d')
                                        ))
                                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                            $input_data->nidara_kid_profile_id
                                        ))
                                            ->inwhere('GamesAnswers.game_id', array(
                                            $input_data->game_id
                                        ))
                                            ->inwhere('GamesAnswers.questions_no', array(
                                            $questionvalue->question_id
                                        ))
                                            ->inwhere('GamesAnswers.session_id ', array(
                                            $ses->session_id
                                        ))
                                            ->groupBy('GamesAnswers.id')
                                            ->getQuery()
                                            ->execute();

                                        if (count($game_answer) > 0)
                                        {
                                            foreach ($game_answer as $value6)
                                            {

                                                if ($secondaryvalue->answer_show_type == 3)
                                                {
                                                    $colorval = array();
                                                    $getcolorvalue = $this
                                                        ->modelsManager
                                                        ->createBuilder()
                                                        ->columns(array(
                                                        'GamesAnswersColor.click_count',
                                                    ))
                                                        ->from('GamesAnswersColor')
                                                        ->inwhere("GamesAnswersColor.game_id", array(
                                                        $input_data->game_id
                                                    ))
                                                        ->inwhere("GamesAnswersColor.child_id", array(
                                                        $input_data->nidara_kid_profile_id
                                                    ))
                                                        ->inwhere("GamesAnswersColor.question_id", array(
                                                        $questionvalue->question_id

                                                    ))
                                                        ->inwhere("GamesAnswersColor.slide_no", array(
                                                        $value6->slide_no
                                                    ))
                                                        ->inwhere("GamesAnswersColor.session_id", array(
                                                        $ses->session_id
                                                    ))

                                                        ->getQuery()
                                                        ->execute();

                                                    if (count($getcolorvalue) > 0)
                                                    {
                                                        foreach ($getcolorvalue as $getcolorval)
                                                        {
                                                            $colorgame = true;

                                                            $colorvalue['color'] = $getcolorval->click_count;
                                                            $colorval[] = $colorvalue;

                                                            //$data2['answer'] = $baseurl . $value6->object_name;
                                                            

                                                            
                                                        }

                                                    }
                                                    else
                                                    {
                                                        $colorgame = false;

                                                    }

                                                    $data2['colorgame'] = $colorgame;
                                                    $data2['clickcolor'] = $colorval;
                                                }
                                               
                                                else
                                                {
                                                    $colorgame = false;
                                                    $data2['colorgame'] = $colorgame;
                                                    $data2['clickcolor'] = [];

                                                }

                                                if ($value6->answers > 1)
                                                {

                                                    if ($questionvalue->game_type_value > 10)
                                                    {
                                                        if ($questionvalue->game_type_value == $value6->answers)
                                                        {
                                                            $data2['question'] = $questionvalue->question;
                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {
                                                                $data2['qu_answer'] = $value6->answers;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "Yes";
                                                            }
                                                            // $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                                            // $data2['time'] = $value6->time;
                                                            
                                                        }
                                                        else if ($questionvalue->game_type_value < $value6->answers)
                                                        {
                                                            $data2['question'] = $questionvalue->question;
                                                            if ($questionvalue->game_type_value > 20)
                                                            {

                                                                $qustionvalue = 'How many times did the child click on the monster';
                                                            }
                                                            else
                                                            {
                                                                $qustionvalue = 'How many objects did the child click on';
                                                            }

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {
                                                                $data2['qu_answer'] = $qustionvalue . ': ' . $value6->answers . ' <br> How many times did the child miss - hand eye coordination ' . ($value6->answers - $questionvalue->game_type_value);
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "No";
                                                            }
                                                            // $data2['time'] = $value6->time;
                                                            
                                                        }
                                                        else
                                                        {
                                                            $data2['question'] = $questionvalue->question;

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {

                                                                $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            //$data2['time'] = $value6->time;
                                                            
                                                        }
                                                    }
                                                    else
                                                    {
                                                        if ($questionvalue->game_type_value == $value6->answers)
                                                        {
                                                            $data2['question'] = $questionvalue->question;

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {

                                                                $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "Yes";
                                                            }

                                                            //$data2['time'] = $value6->time;
                                                            
                                                        }
                                                        else if ($questionvalue->game_type_value < $value6->answers)
                                                        {
                                                            $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                                            if (($numbercheck % $questionvalue->game_type_value) == 0)
                                                            {
                                                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';
                                                            }
                                                            else
                                                            {
                                                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                                            }
                                                            $data2['question'] = $questionvalue->question;

                                                            if ($secondaryvalue->answer_show_type == 1)
                                                            {
                                                                $data2['qu_answer'] = $getAnsewer;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 2)
                                                            {
                                                                $data2['qu_answer'] = $value6->time;
                                                            }
                                                            else if ($secondaryvalue->answer_show_type == 4)
                                                            {
                                                                $data2['qu_answer'] = "No";
                                                            }
                                                            // $data2['time'] = $value6->time;
                                                            
                                                        }
                                                    }
                                                }
                                                else
                                                {


                                                    $testcontent = strtolower($questionvalue->question);
                                                    if ($value6->answers == 1)
                                                    {

                                                        $answercount += 1;
                                                      

                                                        if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $secondaryvalue->answer_show_type == 1)
                                                        {


                                                            if($questionvalue->answer == "color")
                                                            {
                                                            $getanswer = 'Colored within the lines';
                                                            }
                                                            else
                                                            {

                                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                    	
                                                        
                                                        $getanswer =$getimagevalue[0]->image_name;
                                                    }


                                                                
                                                            }
                                                        }
                                                        else if (strpos($testcontent, 'trace') !== false && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                            $getanswer = 'Traced on the lines';
                                                        }
                                                        else if ($secondaryvalue->answer_show_type == 4)
                                                        {
                                                            $getanswer = 'Yes';
                                                        }
                                                        else
                                                        {
                                                            $getanswer = 'Selected the correct answer';
                                                        }
                                                    }
                                                    else
                                                    {

                                                        if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                              if($questionvalue->answer == "color")
                                                            {
                                                            $getanswer = 'Colored out side the lines';
                                                            }
                                                            else
                                                            {

                                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        
                                                        $getanswer = $getimagevalue[0]->image_name;
                                                    }


                                                                
                                                            }
                                                            
                                                            $worngcount += 1;
                                                        }
                                                        else if ($secondaryvalue->answer_show_type == 4)
                                                        {
                                                            $getanswer = 'No';
                                                            $answercount += 1;
                                                        }
                                                        else if (strpos($testcontent, 'trace') !== false && $secondaryvalue->answer_show_type == 1)
                                                        {
                                                            $getanswer = 'Traced on out side the lines';
                                                            $worngcount += 1;
                                                        }
                                                        else
                                                        {
                                                            $getanswer = 'Selected the Worng answer';
                                                            $worngcount += 1;
                                                        }
                                                    }
                                                    $data2['question'] = $questionvalue->question;
                                                    $data2['qu_answer'] = $getanswer;
                                                    $data2['time'] = $value6->time;
                                                }

                                            }
                                        }
                                        else
                                        {
                                            $data2['question'] = $questionvalue->question;
                                            $data2['qu_answer'] = 'Not Answered';
                                            $data2['time'] = '';
                                            $worngcount += 1;
                                        }
                                        $showtype = "";
                                        if ($secondaryvalue->answer_show_type == 0)
                                        {
                                            $showtype = "color";
                                        }

                                        //$data2 ['answer_show_type'] = $showtype;

                                        
                                        $gamedetailsarray[] = $data2;
                                        


                                    }
                                    $secondarydata['answers'] = $gamedetailsarray;
                                    foreach ($secondarydata['answers'] as $answervalue)
                                    {

                                    }
                                    $secondarydata['check'] = $answervalue;
                                    $secondarydata['question'] = $secondaryvalue->question . '' . $answervalue->qu_answer;
                                }

                                if ($secondaryvalue->answer_show_type == 5 && $secondaryvalue->standard_name == "Problem Solving")
                                                {
                                                    //$colorval = array();
                                        $gametrycount = $this
                                            ->modelsManager
                                            ->createBuilder()
                                            ->columns(array(
                                            'GamesAnswers.id as id',
                                        
                                        ))
                                            ->from("GamesAnswers")
                                            ->inwhere('GamesAnswers.created_at', array(
                                            date('Y-m-d')
                                        ))
                                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                                            $input_data->nidara_kid_profile_id
                                        ))
                                            ->inwhere('GamesAnswers.game_id', array(
                                            $input_data->game_id
                                        ))
                                            ->inwhere('GamesAnswers.questions_no', array(
                                            $questionvalue->question_id
                                        ))
                                            ->inwhere('GamesAnswers.session_id ', array(
                                            $ses->session_id
                                        ))
                                            ->inwhere('GamesAnswers.slide_type ', array(
                                           "question"
                                        ))

                                            ->groupBy('GamesAnswers.id')
                                            ->getQuery()
                                            ->execute();

                                                    if (count($gametrycount) > 0)
                                                    {
                                                        
                                                        $secondarydata['gametrycount']=count($gametrycount);
                                                    }
                                                    else
                                                    {
                                                        $secondarydata['gametrycount']=false;
                                                    }
                                                    
                                                }
                                                else
                                                {
                                                    $secondarydata['gametrycount']=false;
                                                }
                                $secondarydata['standard_name'] = $secondaryvalue->standard_name;
                                $secondarydata['indicator_name'] = $secondaryvalue->indicator_name;

                                $gaemstatus = $gamedetails = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'KidsGamesStatus.id',

                                ))
                                    ->from('KidsGamesStatus')
                                    ->inwhere('KidsGamesStatus.game_id', array(
                                    $input_data->game_id
                                ))
                                    ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                                    $input_data->nidara_kid_profile_id
                                ))
                                    ->inwhere('KidsGamesStatus.session_id ', array(
                                    $ses->session_id
                                ))
                                    ->inwhere('KidsGamesStatus.current_status', array(
                                    1
                                ))
                                    ->getQuery()
                                    ->execute();

                                if (count($gaemstatus) > 0)
                                {
                                    $gamedata_arraystatus["gamestatus"] = true;
                                }
                                else
                                {
                                    $gamedata_arraystatus["gamestatus"] = false;
                                }

                                $gamevaluearray[] = $secondarydata;
                                $secondarydata['time_interval']=false;
                            }
                        }
                        else
                        {
                            continue;
                        }
                        $subjectdata['subject_id'] = $subjectvalue->subject_id;
                        $subjectdata['questionMap'] = $gamevaluearray;
                        $subjectdata['subject_name'] = $subjectvalue->subject_name;
                        $gamedata_array[] = $subjectdata;

                        $i = $i + 1;

                    }
                }
                if ($gamedata_array != [])
                {
                    $gamedata_arraystatus['subjectvalue'] = $gamedata_array;
                    $gamearrayval[] = $gamedata_arraystatus;
                }

            }
            return $this
                ->response
                ->setJsonContent(["status" => true, "data" => $gamearrayval]);
        }
    }
    

   /* public function getgameresultnewset()
    {
        $gamemultislide = false;
        $multiclick = false;

        $baseurl = $this
            ->config->colorurl;

        $input_data = $this
            ->request
            ->getJsonRawBody();
        //$headers = $this->request->getHeaders ();
        $headers = $this
            ->request
            ->getHeaders();
        if (!empty($headers['Token']))
        {
            return $this
                ->response
                ->setJsonContent(["status" => false, "message" => "Pleasesss give the token"]);
        }
        else
        {

            $today_date = date('Y-m-d');

            $shootgamecheck = false;
            $audiogame = false;
            $colorgame = false;

            $game_get = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT KidsGamesStatus.session_id as session_id',
                'KidsGamesStatus.game_id as game_ids',
                'CoreFrameworks.name as frameworkname',
                'GamesDatabase.games_name as games_name',
                'Standard.standard_name as standard_name',
                'GamesDatabase.daily_tips',
                'GuidedLearningDayGameMap.subject_id as subject_id',
                'GuidedLearningDayGameMap.framework_id as framework_id',
            ))
                ->from('KidsGamesStatus')
                ->leftjoin('GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id = KidsGamesStatus.game_id')

                ->leftjoin('Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id')
                ->leftjoin('CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id')
                ->leftjoin('GamesDatabase', 'KidsGamesStatus.game_id = GamesDatabase.id')
                ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')

                ->inwhere('KidsGamesStatus.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('KidsGamesStatus.created_date', array(
                $today_date
            ))->groupBy('KidsGamesStatus.session_id')
                ->getQuery()
                ->execute();
            $gamedata_array = array();

            foreach ($game_get as $value)
            {

                $gaemstatus = $gamedetails = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'KidsGamesStatus.id',

                ))
                    ->from('KidsGamesStatus')
                    ->inwhere('KidsGamesStatus.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('KidsGamesStatus.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('KidsGamesStatus.current_status', array(
                    1
                ))
                    ->getQuery()
                    ->execute();

                if (count($gaemstatus) > 0)
                {
                    $game_data2["gamestatus"] = true;
                }
                else
                {
                    $game_data2["gamestatus"] = false;
                }

                $gamedetails = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'DISTINCT GamesAnswers.id as id',
                    'GamesAnswers.game_id as game_ids',
                    'GamesAnswers.session_id as session_id',
                    'GamesAnswers.questions_no as questions_no',
                    'GamesAnswers.answers as answers',
                    'GamesAnswers.time as time',
                    'GamesAnswers.slide_type as slide_type',
                    'GamesAnswers.replaycount as answerreplaycount',
                    'GamesAnswers.slide_no as slide_no',
                    'GamesCoreframeMap.id as id',
                    'Standard.standard_name as standard_name',
                    'GamesCoreframeMap.gamecoretype as gamecoretype',
                    'GamesDatabase.games_name as games_name'
                ))
                    ->from('GamesAnswers')
                    ->leftjoin('GamesDatabase', 'GamesDatabase.id = GamesAnswers.game_id')
                    ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                    ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->groupBy('GamesAnswers.id')
                    ->orderBy('GamesAnswers.slide_no')
                    ->getQuery()
                    ->execute();

                $total_time = 0;
                $questioncount = 0;
                $answercount = 0;
                $worngcount = 0;
                $gamedetailsarray = array();
                $time = 0;

                foreach ($gamedetails as $gameanswer)
                {
                    if($gameanswer->slide_type != "learning")
                    {
                    $time += $gameanswer->time;
                    $game_data['time'] = $gameanswer->time;
                    $game_data['slide_type'] = $gameanswer->slide_type;
                    $game_data['slide_no'] = $gameanswer->slide_no;
                    $game_data['replaycount'] = $gameanswer->answerreplaycount;
                    }

                    if ($gameanswer->questions_no >= 1)
                    {
                        $questioncount += 1;
                        $game_answer = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'GamesAnswers.id as id',
                            'GamesAnswers.questions_no as questions_no',
                            'GamesAnswers.answers as answers',
                            'GamesAnswers.object_name',
                            'GamesAnswers.replaycount as answerreplaycount',
                            'GamesAnswers.time as time',
                            'GamesAnswers.slide_no as slide_no',
                        ))
                            ->from("GamesAnswers")
                            ->inwhere('GamesAnswers.session_id ', array(
                            $value->session_id
                        ))
                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                            $input_data->nidara_kid_profile_id
                        ))
                            ->inwhere('GamesAnswers.game_id', array(
                            $input_data->game_id
                        ))
                            ->inwhere('GamesAnswers.questions_no', array(
                            $gameanswer->questions_no
                        ))
                            ->groupBy('GamesAnswers.id')
                            ->getQuery()
                            ->execute();

                        if (count($game_answer) > 0)
                        {
                            foreach ($game_answer as $value6)
                            {

                                $colorval = array();

                                $game_question_answer = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                    'GamesQuestionAnswer.game_type',
                                    'GamesQuestionAnswer.question_id as question_id',
                                    'GamesQuestionAnswer.question as question',
                                    'GamesQuestionAnswer.answer as answer',
                                ))
                                    ->from('GamesQuestionAnswer')
                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                    $input_data->game_id
                                ))
                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                    $gameanswer->questions_no
                                ))
                                    ->getQuery()
                                    ->execute();

                                foreach ($game_question_answer as $questionvalue)
                                {

                                    if ($value6->answers > 1)
                                    {
                                        $data2['clickcolor'] = null;
                                        $data2['answer'] = null;
                                        $colorgame = false;
                                        $data2['colorgame'] = $colorgame;

                                        if ($questionvalue->game_type_value > 10)
                                        {

                                            $game_answer = $this
                                                ->modelsManager
                                                ->createBuilder()
                                                ->columns(array(
                                                'GameAnswersShootgame.id',
                                                'GameAnswersShootgame.child_id',
                                                'GameAnswersShootgame.game_id',
                                                'GameAnswersShootgame.session_id',
                                                'GameAnswersShootgame.click_count',
                                                'GameAnswersShootgame.time',
                                                'GameAnswersShootgame.create_at',

                                            ))
                                                ->from("GameAnswersShootgame")
                                                ->inwhere('GameAnswersShootgame.child_id', array(
                                                $input_data->nidara_kid_profile_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.game_id', array(
                                                $input_data->game_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.session_id', array(
                                                $value->session_id
                                            ))
                                                ->orderby('GameAnswersShootgame.id')
                                                ->getQuery()
                                                ->execute();

                                            if (count($game_answer) > 0)
                                            {

                                                $shootgamecheck = true;

                                                $totalcount = array();
                                                $countold = '00:00:00';
                                                $i = 0;
                                                foreach ($game_answer as $valueshoot)
                                                {
                                                    if ($i == 0)
                                                    {
                                                        $count['differentcount'] = "00";
                                                    }
                                                    else
                                                    {
                                                        $count['differentcount'] = date('s', strtotime($valueshoot->time) - strtotime($countold));
                                                    }

                                                    $count['child_id'] = $valueshoot->child_id;
                                                    $count['game_id'] = $valueshoot->game_id;
                                                    $count['click_count'] = $valueshoot->click_count;
                                                    $count['session_id'] = $valueshoot->session_id;
                                                    $countold = $valueshoot->time;

                                                    $i = i + 1;

                                                    $totalcount[] = $count;
                                                }

                                                $data2['time_interval'] = $totalcount;
                                                $data2['total_click_count'] = count($game_answer);
                                                $data2['shootgame'] = $shootgamecheck;

                                            }

                                            if ($questionvalue->game_type_value == $value6->answers)
                                            {
                                                // $data2['question'] = $questionvalue->question;
                                                if ($questionvalue->game_type_value > 20)
                                                {
                                                    $qustionvalue = 'How many times did the child click on the monster?';
                                                }
                                                else
                                                {
                                                    $qustionvalue = 'How many objects did the user correctly click on the object?';
                                                }
                                                $data2['qu_answer1'] = $qustionvalue;
                                                $data2['qu_answer1_count'] = ($questionvalue->game_type_value);

                                                $data2['qu_answer2'] = 'How many times did the child miss - hand eye coordination?';

                                                $data2['qu_answer2_count'] = ($value6->answers - $questionvalue->game_type_value);

                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                                $data2['time'] = $value6->time;
                                            }
                                            else if ($questionvalue->game_type_value < $value6->answers)
                                            {
                                                $data2['question'] = $questionvalue->question;
                                                if ($questionvalue->game_type_value > 20)
                                                {
                                                    $qustionvalue = 'How many times did the child click on the monster?';
                                                }
                                                else
                                                {
                                                    $qustionvalue = 'How many objects did the user correctly click on the object?';
                                                }
                                                $data2['qu_answer1'] = $qustionvalue;
                                                $data2['qu_answer1_count'] = ($questionvalue->game_type_value);

                                                $data2['qu_answer2'] = 'How many times did the child miss - hand eye coordination?';

                                                $data2['qu_answer2_count'] = ($value6->answers - $questionvalue->game_type_value);

                                                $data2['time'] = $value6->time;

                                                $worngcount += 1;

                                            }
                                            else
                                            {
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                                $data2['time'] = $value6->time;
                                            }
                                        }
                                        else
                                        {
                                            $multiclick = true;

                                            $ansimagedes = array();

                                            if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                            {

                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                if ($kidgender->gender == "female")
                                                {
                                                    $str_arr = explode(",", $value6->object_name);

                                                    foreach ($str_arr as $imgobj)
                                                    {

                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                        foreach ($getimagevalue as $getimagevalueval)
                                                        {
                                                            # code...
                                                            //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                            $ansimagedes[] = $getimagevalueval->image_name;

                                                        }

                                                        $getanswer = 'Selected the correct answer : ';
                                                    }

                                                }
                                                else
                                                {

                                                    $str_arr = explode(",", $value6->object_name);
                                                    foreach ($str_arr as $imgobj)
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                        foreach ($getimagevalue as $getimagevalueval)
                                                        {
                                                            # code...
                                                            //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                            $ansimagedes[] = $getimagevalueval->image_name;

                                                        }

                                                        $getanswer = 'Selected the correct answer : ';
                                                    }

                                                }

                                                $data2['qu_answer'] = $getAnsewer;
                                                $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                            }

                                            if ($questionvalue->game_type_value == $value6->answers)
                                            {
                                                $data2['multiclick'] = $multiclick;
                                                $data2['ansper'] = 100;

                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';

                                                $data2['time'] = $value6->time;

                                                $answercount += 1;
                                            }
                                            else if ($questionvalue->game_type_value < $value6->answers)
                                            {
                                                $data2['multiclick'] = $multiclick;
                                                $data2['ansper'] = 0;
                                                $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                                if (($numbercheck % $questionvalue->game_type_value) == 0)
                                                {
                                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';

                                                }
                                                else
                                                {
                                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                                }
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $getAnsewer;
                                                $data2['time'] = $value6->time;
                                                $worngcount += 1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $data2['shootgame'] = false;

                                        if (substr($value6->object_name, strlen($value6->object_name) - 3, strlen($value6->object_name)) == 'png')
                                        {

                                            if (substr($value6->object_name, 0, 1) == "/")
                                            {

                                                if ($value6->answers == 0)
                                                {
                                                    $worngcount += 1;
                                                }
                                                else
                                                {
                                                    $answercount += 1;
                                                }

                                                $slideone = $gameanswer->slide_no;
                                                $qnoone = $value6->questions_no;

                                                if (($slideone == $slidetwo) && $qnoone != $qnotwo)
                                                {
                                                    $gamemultislide = true;
                                                }
                                                else
                                                {
                                                    $gamemultislide = false;
                                                }
                                                $slidetwo = $slideone;
                                                $qnotwo = $qnoone;

                                                // $answercount += 1;
                                                $getcolorvalue = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesAnswersColor.click_count',
                                                ))
                                                    ->from('GamesAnswersColor')
                                                    ->inwhere("GamesAnswersColor.game_id", array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere("GamesAnswersColor.child_id", array(
                                                    $input_data->nidara_kid_profile_id
                                                ))
                                                    ->inwhere("GamesAnswersColor.question_id", array(
                                                    $value6->questions_no

                                                ))
                                                    ->inwhere("GamesAnswersColor.slide_no", array(
                                                    $gameanswer->slide_no
                                                ))
                                                    ->inwhere("GamesAnswersColor.session_id", array(
                                                    $value->session_id
                                                ))

                                                    ->getQuery()
                                                    ->execute();

                                                if (count($getcolorvalue) > 0)
                                                {
                                                    foreach ($getcolorvalue as $getcolorval)
                                                    {
                                                        $colorgame = true;

                                                        $colorvalue['color'] = $getcolorval->click_count;
                                                        $colorval[] = $colorvalue;
                                                        $data2['clickcolor'] = $colorval;
                                                        $data2['answer'] = $baseurl . $value6->object_name;
                                                        $data2['colorgame'] = $colorgame;

                                                    }
                                                }
                                                else
                                                {
                                                    $colorgame = false;

                                                    $data2['clickcolor'] = null;
                                                    $data2['answer'] = null;
                                                    $data2['colorgame'] = $colorgame;

                                                }

                                                $showtitle = 'Actual Work Created By Your Child';

                                            }
                                            else
                                            {

                                                $colorvalue['obj'] = $value6->object_name;
                                                //$worngcount += 1;
                                                $colorgame = false;

                                                $data2['clickcolor'] = null;
                                                $data2['answer'] = null;
                                                $data2['colorgame'] = $colorgame;

                                            }

                                        }
                                        else
                                        {
                                            $colorvalue['qno'] = $value6->questions_no;
                                            $colorvalue['qso'] = $gameanswer->slide_no;
                                            $colorvalue['obj'] = $value6->object_name;
                                            $data2['clickcolor'] = null;
                                            $data2['answer'] = null;
                                            $data2['colorgame'] = $colorgame;

                                        }

                                        $testcontent = strtolower($questionvalue->question);

                                        if ($value6->answers == 1 && substr($value6->object_name, 0, 1) != "/")
                                        {

                                            $answercount += 1;

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                                            {
                                                $getanswer = 'Colored within the lines';
                                                $showtitle = 'What your child colored';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'Actual Work Created By Your Child';
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Happy';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'Yes';
                                            }
                                            else
                                            {

                                                if ($questionvalue->game_type == 3 && substr($value6->object_name, 0, 1) != "/")
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        $data2['ansper'] = 100;
                                                        $data2['emotional'] = false;
                                                        $getanswer = 'Selected the correct answer : ' . $getimagevalue[0]->image_name;
                                                    }

                                                }
                                                else
                                                {
                                                    $data2['emotional'] = false;
                                                    $getanswer = 'Selected the correct answer';
                                                }
                                            }
                                        }
                                        else if ($value6->answers == 1 && substr($value6->object_name, 0, 1) == "/" && strpos($testcontent, 'trace') !== false || strpos($testcontent, 'draw your own') !== false)
                                        {

                                            $answercount += 1;

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                                            {
                                                $getanswer = 'Colored within the lines';
                                                $showtitle = 'What your child colored';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                                $data2['tracegame'] = true;
                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {

                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'Actual Work Created By Your Child';

                                                $data2['tracegame'] = false;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Happy';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'Yes';
                                            }
                                            else
                                            {
                                                $data2['emotional'] = false;
                                                $getanswer = 'Selected the correct answer';
                                            }
                                        }
                                        else
                                        {
                                            //guna
                                            

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                                            {

                                                if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $str_arr = explode(",", $value6->object_name);

                                                        foreach ($str_arr as $imgobj)
                                                        {

                                                            $getimagevalue = $this
                                                                ->modelsManager
                                                                ->createBuilder()
                                                                ->columns(array(
                                                                'GameQuestionImageMaster.image_name',
                                                            ))
                                                                ->from('GameQuestionImageMaster')
                                                                ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                $input_data->game_id
                                                            ))
                                                                ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                $imgobj
                                                            ))->inwhere("GameQuestionImageMaster.tina", array(
                                                                1
                                                            ))
                                                                ->getQuery()
                                                                ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                            {
                                                                # code...
                                                                //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                $ansimagedes[] = $getimagevalueval->image_name;

                                                            }

                                                            $getanswer = 'Selected the Wrong answer : ';
                                                        }

                                                    }
                                                    else
                                                    {

                                                        $str_arr = explode(",", $value6->object_name);
                                                        foreach ($str_arr as $imgobj)
                                                        {
                                                            $getimagevalue = $this
                                                                ->modelsManager
                                                                ->createBuilder()
                                                                ->columns(array(
                                                                'GameQuestionImageMaster.image_name',
                                                            ))
                                                                ->from('GameQuestionImageMaster')
                                                                ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                $input_data->game_id
                                                            ))
                                                                ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                $imgobj
                                                            ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                                1
                                                            ))
                                                                ->getQuery()
                                                                ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                            {
                                                                # code...
                                                                //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                $ansimagedes[] = $getimagevalueval->image_name;

                                                            }

                                                            $getanswer = 'Selected the Wrong answer : ';
                                                        }

                                                    }

                                                    $data2['qu_answer'] = $getAnsewer;
                                                    $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                                    $worngcount += 1;

                                                }
                                                else
                                                {
                                                    $getanswer = 'Colored out side the lines';
                                                    $showtitle = 'What your child colored';
                                                    $worngcount += 1;
                                                    $data2['ansper'] = 0;
                                                    $data2['colorshow'] = true;
                                                }
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'No';
                                                $worngcount += 1;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Sad';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on out side the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $worngcount += 1;
                                                $data2['ansper'] = 0;
                                                $data2['colorshow'] = true;
                                                $data2['tracegame'] = true;

                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {
                                                $getanswer = 'Traced on out side the lines';
                                                $worngcount += 1;
                                                $showtitle = 'Actual Work Created By Your Child';
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['tracegame'] = false;
                                            }
                                            else
                                            {
                                                if ($questionvalue->game_type == 3)
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }

                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        $data2['emotional'] = false;

                                                        $data2['ansper'] = 0;

                                                        $getanswer = 'Selected the Worng answer : ' . $getimagevalue[0]->image_name;

                                                        $worngcount += 1;
                                                    }
                                                }
                                                else
                                                {
                                                    //guna
                                                    if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                                    {

                                                        $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                        if ($kidgender->gender == "female")
                                                        {
                                                            $str_arr = explode(",", $value6->object_name);

                                                            foreach ($str_arr as $imgobj)
                                                            {

                                                                $getimagevalue = $this
                                                                    ->modelsManager
                                                                    ->createBuilder()
                                                                    ->columns(array(
                                                                    'GameQuestionImageMaster.image_name',
                                                                ))
                                                                    ->from('GameQuestionImageMaster')
                                                                    ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                    $input_data->game_id
                                                                ))
                                                                    ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                    $imgobj
                                                                ))->inwhere("GameQuestionImageMaster.tina", array(
                                                                    1
                                                                ))
                                                                    ->getQuery()
                                                                    ->execute();

                                                                foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                                $getanswer = 'Selected the Wrong answer : ';
                                                            }

                                                        }
                                                        else
                                                        {

                                                            $str_arr = explode(",", $value6->object_name);
                                                            foreach ($str_arr as $imgobj)
                                                            {
                                                                $getimagevalue = $this
                                                                    ->modelsManager
                                                                    ->createBuilder()
                                                                    ->columns(array(
                                                                    'GameQuestionImageMaster.image_name',
                                                                ))
                                                                    ->from('GameQuestionImageMaster')
                                                                    ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                    $input_data->game_id
                                                                ))
                                                                    ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                    $imgobj
                                                                ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                                    1
                                                                ))
                                                                    ->getQuery()
                                                                    ->execute();

                                                                foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                                $getanswer = 'Selected the Wrong answer : ';
                                                            }

                                                        }

                                                        $data2['qu_answer'] = $getAnsewer;
                                                        $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                                        $ansimagedes = [];
                                                        $data2['ansper'] = 0;

                                                        $worngcount += 1;

                                                    }
                                                    else
                                                    {
                                                        $data2['ansper'] = 0;
                                                        $getanswer = 'Selected the Worng answer';
                                                        $worngcount += 1;
                                                    }
                                                }

                                            }
                                        }

                                    }
                                }

                                $getaudiovalue = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'GamesAnswersAudioCount.click_count',
                                ))
                                    ->from('GamesAnswersAudioCount')
                                    ->inwhere("GamesAnswersAudioCount.game_id", array(
                                    $input_data->game_id
                                ))
                                    ->inwhere("GamesAnswersAudioCount.child_id", array(
                                    $input_data->nidara_kid_profile_id
                                ))
                                    ->inwhere("GamesAnswersAudioCount.question_id", array(
                                    $value6->questions_no
                                ))
                                    ->inwhere("GamesAnswersAudioCount.slide_no", array(
                                    $value6->slide_no
                                ))

                                    ->getQuery()
                                    ->execute();

                                if (count($getaudiovalue) > 0)
                                {

                                    $data2['audiocount'] = $getaudiovalue[0]->click_count;
                                    $audiogame = true;
                                }

                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = $getanswer;
                                $data2['showtitle'] = $showtitle;
                                $data2['questions_no'] = $value6->questions_no;
                                $data2['time'] = $value6->time;
                            }
                        }
                        else
                        {
                            $data2['question'] = $questionvalue->question;
                            $data2['qu_answer'] = 'Not Answered';
                            $data2['time'] = '';
                            $worngcount += 1;
                        }

                        $game_data['question_valye'] = $data2;

                        $data2['qa_imgdes'] = [];
                        $data2['tracegame'] = false;

                    }
                    else
                    {
                        $game_data['question_valye'] = '';
                    }

                    $game_data['multiclick'] = $multiclick;

                    $gamedetailsarray[] = $game_data;
                }
                $checkanswer = ($answercount + $worngcount);
                if ($questioncount == $checkanswer)
                {
                    $game_data2['questioncount'] = $questioncount;
                    $game_data2['answer'] = $answercount;
                    $game_data2['worngcount'] = $worngcount;
                }
                $game_data2['Primary_Tagging'] = $value->standard_name;
                $game_data2['over_all_time'] = $time;
                $game_data2['slide_info'] = $gamedetailsarray;
                $game_data2['game_id'] = $value->game_ids;
                $game_data2['game_time'] = $time;
                $game_data2['game_name'] = $value->games_name;
                $game_data2['frameworkname'] = $value->frameworkname;
                $game_data2['audiogame'] = $audiogame;
                $game_data2['multislide'] = $gamemultislide;

                $gamedata_array[] = $game_data2;
            }

            if ($questionvalue->game_type == 3 && substr($value6->object_name, 0, 1) != "/")

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $gamedata_array, 'daily_tips' => $value->daily_tips]);
        }
    }*/

    public function getgameresult()
    {
        $gamemultislide = false;
        $multiclick = false;

        $baseurl = $this
            ->config->colorurl;

        $input_data = $this
            ->request
            ->getJsonRawBody();
        //$headers = $this->request->getHeaders ();
        $headers = $this
            ->request
            ->getHeaders();
        if (empty($headers['Token']))
        {
            return $this
                ->response
                ->setJsonContent(["status" => false, "message" => "Pleasesss give the token"]);
        }
        else
        {

            $today_date = date('Y-m-d');

            $shootgamecheck = false;
            $audiogame = false;
            $colorgame = false;

            $game_get = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT KidsGamesStatus.session_id as session_id',
                'KidsGamesStatus.game_id as game_ids',
                'CoreFrameworks.name as frameworkname',
                'GamesDatabase.games_name as games_name',
                'Standard.standard_name as standard_name',
                'GamesDatabase.daily_tips',
                'GuidedLearningDayGameMap.subject_id as subject_id',
                'GuidedLearningDayGameMap.framework_id as framework_id',
            ))
                ->from('KidsGamesStatus')
                ->leftjoin('GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id = KidsGamesStatus.game_id')

                ->leftjoin('Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id')
                ->leftjoin('CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id')
                ->leftjoin('GamesDatabase', 'KidsGamesStatus.game_id = GamesDatabase.id')
                ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')

                ->inwhere('KidsGamesStatus.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('KidsGamesStatus.created_date', array(
                $today_date
            ))->groupBy('KidsGamesStatus.session_id')
                ->getQuery()
                ->execute();
            $gamedata_array = array();




            foreach ($game_get as $value)
            {

                $gaemstatus = $gamedetails = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'KidsGamesStatus.id',

                ))
                    ->from('KidsGamesStatus')
                    ->inwhere('KidsGamesStatus.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('KidsGamesStatus.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('KidsGamesStatus.current_status', array(
                    1
                ))
                    ->getQuery()
                    ->execute();

                    

                if (count($gaemstatus) > 0)
                {
                    $game_data2["gamestatus"] = true;
                }
                else
                {
                    $game_data2["gamestatus"] = false;
                }

                $gamedetails = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'DISTINCT GamesAnswers.id as id',
                    'GamesAnswers.game_id as game_ids',
                    'GamesAnswers.session_id as session_id',
                    'GamesAnswers.questions_no as questions_no',
                    'GamesAnswers.answers as answers',
                    'GamesAnswers.time as time',
                    'GamesAnswers.slide_type as slide_type',
                    'GamesAnswers.replaycount as answerreplaycount',
                    'GamesAnswers.slide_no as slide_no',
                    'GamesCoreframeMap.id as id',
                    'Standard.standard_name as standard_name',
                    'GamesCoreframeMap.gamecoretype as gamecoretype',
                    'GamesDatabase.games_name as games_name'
                ))
                    ->from('GamesAnswers')
                    ->leftjoin('GamesDatabase', 'GamesDatabase.id = GamesAnswers.game_id')
                    ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                    ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->groupBy('GamesAnswers.slide_no')
                    ->orderBy('GamesAnswers.slide_no')
                    ->getQuery()
                    ->execute();

                $total_time = 0;
                $questioncount = 0;
                $answercount = 0;
                $worngcount = 0;
                $gamedetailsarray = array();
                $time = 0;

                 $overalltime = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                   
                    'GamesAnswers.time as time',
                    
                ))
                    ->from('GamesAnswers')
                    
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->orderBy('GamesAnswers.slide_no')
                    ->getQuery()
                    ->execute();
                    foreach ($overalltime as $timevalueoverall) {

                        $time += $timevalueoverall->time;
                        # code...
                    }

                foreach ($gamedetails as $gameanswer)
                {

                   
                    /*$game_data['time'] = $gameanswer->time;
                    $game_data['slide_type'] = $gameanswer->slide_type;
                    $game_data['slide_no'] = $gameanswer->slide_no;

                    $game_data['replaycount'] = $gameanswer->answerreplaycount;*/

                    if($gameanswer->slide_type != "learning")
                   {
                    $game_data['time'] = $gameanswer->time;
                    $game_data['slide_type'] = $gameanswer->slide_type;
                    $game_data['slide_no'] = $gameanswer->slide_no;
                    $game_data['replaycount'] = $gameanswer->answerreplaycount;
                    }
                    else
                    {
                        $learning = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'DISTINCT GamesAnswers.id as id',
                    'GamesAnswers.game_id as game_ids',
                    'GamesAnswers.session_id as session_id',
                    'GamesAnswers.questions_no as questions_no',
                    'GamesAnswers.answers as answers',
                    'GamesAnswers.time as time',
                    'GamesAnswers.slide_type as slide_type',
                    'GamesAnswers.replaycount as answerreplaycount',
                    'GamesAnswers.slide_no as slide_no',
                ))
                    ->from('GamesAnswers')
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('GamesAnswers.slide_type ', array(
                    "learning"
                ))
                     ->inwhere('GamesAnswers.slide_no ', array(
                    $gameanswer->slide_no
                ))
                    ->orderBy('GamesAnswers.id')
                    ->getQuery()
                    ->execute();

                    foreach ($learning as $learningval) {
                        # code...
                  
                   $game_data['time'] = $learningval->time;
                    $game_data['slide_type'] = $learningval->slide_type;
                    $game_data['slide_no'] = $learningval->slide_no;
                    $game_data['replaycount'] = $learningval->answerreplaycount;
                        $gamedetailsarray[]=$game_data;
                        $game_data=false;
                    }



                    }

                    if ($gameanswer->questions_no >= 1)
                    {

                        $game_answer = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'GamesAnswers.id as id',
                            'GamesAnswers.questions_no as questions_no',
                            'GamesAnswers.answers as answers',
                            'GamesAnswers.object_name',
                            'GamesAnswers.replaycount as answerreplaycount',
                            'GamesAnswers.time as time',
                            'GamesAnswers.slide_no as slide_no',
                        ))
                            ->from("GamesAnswers")
                            ->inwhere('GamesAnswers.session_id ', array(
                            $value->session_id
                        ))
                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                            $input_data->nidara_kid_profile_id
                        ))
                            ->inwhere('GamesAnswers.game_id', array(
                            $input_data->game_id
                        ))
                            ->inwhere('GamesAnswers.slide_no', array(
                            $gameanswer->slide_no
                        ))
                            ->groupBy('GamesAnswers.id')
                            ->getQuery()
                            ->execute();

                        $getGameAnswerValueArray = array();
                        if (count($game_answer) > 0)
                        {

                            $wrongcountimg = 0;
                            foreach ($game_answer as $value6)
                            {

                                $data2['replaycount'] = $value6->answerreplaycount;

                                $questioncount += 1;
                                $anspersentage = 0;

                                $colorval = array();

                                $data2['qa_imgdes'] = [];

                                $game_question_answer = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                    'GamesQuestionAnswer.game_type',
                                    'GamesQuestionAnswer.question_id as question_id',
                                    'GamesQuestionAnswer.question as question',
                                    'GamesQuestionAnswer.answer as answer',
                                ))
                                    ->from('GamesQuestionAnswer')
                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                    $input_data->game_id
                                ))
                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                    $value6->questions_no
                                ))
                                    ->getQuery()
                                    ->execute();

                                foreach ($game_question_answer as $questionvalue)
                                {

                                    $getaudiovalue = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswersAudioCount.click_count',
                                    ))
                                        ->from('GamesAnswersAudioCount')
                                        ->inwhere("GamesAnswersAudioCount.game_id", array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.child_id", array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.question_id", array(
                                        $value6->questions_no
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.slide_no", array(
                                        $gameanswer->slide_no
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.session_id", array(
                                        $value->session_id
                                    ))

                                        ->getQuery()
                                        ->execute();

                                    if (count($getaudiovalue) > 0)
                                    {

                                        $audiocountval = $getaudiovalue[0]->click_count;

                                        $audiogame = true;
                                    }
                                    else
                                    {
                                        $audiocountval = 0;
                                    }
                                    $data2['audiocount'] = $audiocountval;

                                    if ($value6->answers > 1)
                                    {
                                        $data2['clickcolor'] = null;
                                        $data2['answer'] = null;
                                        $colorgame = false;
                                        $data2['colorgame'] = $colorgame;

                                        if ($questionvalue->game_type_value > 10)
                                        {

                                            $game_answer = $this
                                                ->modelsManager
                                                ->createBuilder()
                                                ->columns(array(
                                                'GameAnswersShootgame.id',
                                                'GameAnswersShootgame.child_id',
                                                'GameAnswersShootgame.game_id',
                                                'GameAnswersShootgame.session_id',
                                                'GameAnswersShootgame.click_count',
                                                'GameAnswersShootgame.time',
                                                'GameAnswersShootgame.create_at',

                                            ))
                                                ->from("GameAnswersShootgame")
                                                ->inwhere('GameAnswersShootgame.child_id', array(
                                                $input_data->nidara_kid_profile_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.game_id', array(
                                                $input_data->game_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.session_id', array(
                                                $value->session_id
                                            ))
                                                ->orderby('GameAnswersShootgame.id')
                                                ->getQuery()
                                                ->execute();

                                            if (count($game_answer) > 0)
                                            {

                                                $shootgamecheck = true;

                                                $totalcount = array();
                                                $countold = '00:00:00';
                                                $i = 0;
                                                foreach ($game_answer as $valueshoot)
                                                {
                                                    if ($i == 0)
                                                    {
                                                        $count['differentcount'] = "00";
                                                    }
                                                    else
                                                    {
                                                        $count['differentcount'] = date('s', strtotime($valueshoot->time) - strtotime($countold));
                                                    }

                                                    $count['child_id'] = $valueshoot->child_id;
                                                    $count['game_id'] = $valueshoot->game_id;
                                                    $count['click_count'] = $valueshoot->click_count;
                                                    $count['session_id'] = $valueshoot->session_id;
                                                    $countold = $valueshoot->time;

                                                    $i = i + 1;

                                                    $totalcount[] = $count;
                                                }

                                                $data2['time_interval'] = $totalcount;
                                                $data2['total_click_count'] = count($game_answer);
                                                $data2['shootgame'] = $shootgamecheck;

                                            }

                                            if ($questionvalue->game_type_value == $value6->answers)
                                            {
                                                // $data2['question'] = $questionvalue->question;
                                                if ($questionvalue->game_type_value > 20)
                                                {
                                                    $qustionvalue = 'How many times did the child click on the monster?';
                                                }
                                                else
                                                {
                                                    $qustionvalue = 'How many objects did the user correctly click on the object?';
                                                }
                                                $data2['qu_answer1'] = $qustionvalue;
                                                $data2['qu_answer1_count'] = ($questionvalue->game_type_value);

                                                $data2['qu_answer2'] = 'How many times did the child miss - hand eye coordination?';

                                                $data2['qu_answer2_count'] = ($value6->answers - $questionvalue->game_type_value);

                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                                $data2['time'] = $value6->time;
                                            }
                                            else if ($questionvalue->game_type_value < $value6->answers)
                                            {
                                                $data2['question'] = $questionvalue->question;
                                                if ($questionvalue->game_type_value > 20)
                                                {
                                                    $qustionvalue = 'How many times did the child click on the monster?';
                                                }
                                                else
                                                {
                                                    $qustionvalue = 'How many objects did the user correctly click on the object?';
                                                }
                                                $data2['qu_answer1'] = $qustionvalue;
                                                $data2['qu_answer1_count'] = ($questionvalue->game_type_value);

                                                $data2['qu_answer2'] = 'How many times did the child miss - hand eye coordination?';

                                                $data2['qu_answer2_count'] = ($value6->answers - $questionvalue->game_type_value);

                                                $data2['time'] = $value6->time;

                                                $worngcount += 1;

                                            }
                                            else
                                            {
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                                $data2['time'] = $value6->time;
                                            }
                                        }
                                        else
                                        {
                                            $multiclick = true;

                                            $ansimagedes = array();

                                            if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                            {

                                                //gunates
                                                

                                                $wrongcountimg += 100;

                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                if ($kidgender->gender == "female")
                                                {
                                                    $str_arr = explode(",", $value6->object_name);

                                                    foreach ($str_arr as $imgobj)
                                                    {

                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                        foreach ($getimagevalue as $getimagevalueval)
                                                        {
                                                            # code...
                                                            //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                            $ansimagedes[] = $getimagevalueval->image_name;

                                                        }

                                                        $getanswer = 'Selected the correct answer : ';
                                                    }

                                                }
                                                else
                                                {

                                                    $str_arr = explode(",", $value6->object_name);
                                                    foreach ($str_arr as $imgobj)
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                        foreach ($getimagevalue as $getimagevalueval)
                                                        {
                                                            # code...
                                                            //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                            $ansimagedes[] = $getimagevalueval->image_name;

                                                        }

                                                        $getanswer = 'Selected the correct answer : ';
                                                    }

                                                }

                                                $data2['qu_answer'] = $getanswer;
                                                $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                            }

                                            if ($questionvalue->game_type_value == $value6->answers)
                                            {
                                                $data2['multiclick'] = $multiclick;
                                                $data2['ansper'] = 100;

                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';

                                                $data2['time'] = $value6->time;

                                                $answercount += 1;
                                            }
                                            else if ($questionvalue->game_type_value < $value6->answers)
                                            {
                                                $data2['multiclick'] = $multiclick;
                                                $data2['ansper'] = 0;
                                                $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                                if (($numbercheck % $questionvalue->game_type_value) == 0)
                                                {
                                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';

                                                }
                                                else
                                                {
                                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                                }
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $getAnsewer;
                                                $data2['time'] = $value6->time;
                                                $worngcount += 1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $data2['shootgame'] = false;

                                        if (substr($value6->object_name, strlen($value6->object_name) - 3, strlen($value6->object_name)) == 'png')
                                        {

                                            if (substr($value6->object_name, 0, 1) == "/")
                                            {

                                                if ($value6->answers == 0)
                                                {
                                                    $worngcount += 1;
                                                }
                                                else
                                                {
                                                    $answercount += 1;
                                                }

                                                $slideone = $gameanswer->slide_no;
                                                $qnoone = $value6->questions_no;

                                                if (($slideone == $slidetwo) && $qnoone != $qnotwo)
                                                {
                                                    $gamemultislide = true;
                                                }
                                                else
                                                {
                                                    $gamemultislide = false;
                                                }
                                                $slidetwo = $slideone;
                                                $qnotwo = $qnoone;

                                                // $answercount += 1;
                                                $getcolorvalue = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesAnswersColor.click_count',
                                                ))
                                                    ->from('GamesAnswersColor')
                                                    ->inwhere("GamesAnswersColor.game_id", array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere("GamesAnswersColor.child_id", array(
                                                    $input_data->nidara_kid_profile_id
                                                ))
                                                    ->inwhere("GamesAnswersColor.question_id", array(
                                                    $value6->questions_no

                                                ))
                                                    ->inwhere("GamesAnswersColor.slide_no", array(
                                                    $gameanswer->slide_no
                                                ))
                                                    ->inwhere("GamesAnswersColor.session_id", array(
                                                    $value->session_id
                                                ))

                                                    ->getQuery()
                                                    ->execute();

                                                if (count($getcolorvalue) > 0)
                                                {
                                                    foreach ($getcolorvalue as $getcolorval)
                                                    {
                                                        $colorgame = true;

                                                        $colorvalue['color'] = $getcolorval->click_count;
                                                        $colorval[] = $colorvalue;
                                                        $data2['clickcolor'] = $colorval;
                                                        $data2['answer'] = $baseurl . $value6->object_name;
                                                        $data2['colorgame'] = $colorgame;

                                                    }
                                                }
                                                else
                                                {
                                                    $colorgame = false;

                                                    $data2['clickcolor'] = null;
                                                    $data2['answer'] = null;
                                                    $data2['colorgame'] = $colorgame;

                                                }

                                                $showtitle = 'Actual Work Created By Your Child';

                                            }
                                            else
                                            {

                                                $colorvalue['obj'] = $value6->object_name;
                                                //$worngcount += 1;
                                                $colorgame = false;

                                                $data2['clickcolor'] = null;
                                                $data2['answer'] = null;
                                                $data2['colorgame'] = $colorgame;

                                            }

                                        }
                                        else
                                        {
                                            $colorvalue['qno'] = $value6->questions_no;
                                            $colorvalue['qso'] = $gameanswer->slide_no;
                                            $colorvalue['obj'] = $value6->object_name;
                                            $data2['clickcolor'] = null;
                                            $data2['answer'] = null;
                                            $data2['colorgame'] = $colorgame;

                                        }

                                        $testcontent = strtolower($questionvalue->question);

                                        if ($value6->answers == 1 && substr($value6->object_name, 0, 1) != "/")
                                        {

                                            //a
                                            $answercount += 1;

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $questionvalue->answer == 'color')
                                            {
                                                $getanswer = 'Colored within the lines';
                                                $showtitle = 'What your child colored';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'Actual Work Created By Your Child';
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Happy';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'Yes';
                                            }
                                            else
                                            {

                                                if ($questionvalue->game_type == 3 && substr($value6->object_name, 0, 1) != "/")
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        $data2['ansper'] = 100;
                                                        $data2['emotional'] = false;
                                                        $getanswer = 'Selected the correct answer : ' . $getimagevalue[0]->image_name;
                                                    }

                                                }
                                                else
                                                {
                                                    $data2['emotional'] = false;
                                                    $getanswer = 'Selected the correct answer';
                                                }
                                            }
                                        }
                                        else if ($value6->answers == 1 && substr($value6->object_name, 0, 1) == "/" && strpos($testcontent, 'trace') !== false || strpos($testcontent, 'draw your own') !== false)
                                        {

                                            $answercount += 1;

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $questionvalue->answer == 'color')
                                            {
                                                $getanswer = 'Colored within the lines';
                                                $showtitle = 'What your child colored';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                                $data2['tracegame'] = true;
                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {

                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'Actual Work Created By Your Child';

                                                $data2['tracegame'] = false;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Happy';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'Yes';
                                            }
                                            else
                                            {
                                                $data2['emotional'] = false;
                                                $getanswer = 'Selected the correct answer';
                                            }
                                        }
                                        else
                                        {
                                            //guna
                                            

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $questionvalue->answer == 'color')
                                            {

                                                if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $str_arr = explode(",", $value6->object_name);

                                                        foreach ($str_arr as $imgobj)
                                                        {

                                                            $getimagevalue = $this
                                                                ->modelsManager
                                                                ->createBuilder()
                                                                ->columns(array(
                                                                'GameQuestionImageMaster.image_name',
                                                            ))
                                                                ->from('GameQuestionImageMaster')
                                                                ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                $input_data->game_id
                                                            ))
                                                                ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                $imgobj
                                                            ))->inwhere("GameQuestionImageMaster.tina", array(
                                                                1
                                                            ))
                                                                ->getQuery()
                                                                ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                            {
                                                                # code...
                                                                //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                $ansimagedes[] = $getimagevalueval->image_name;

                                                            }

                                                            $getanswer = 'Selected the Wrong answer : ';
                                                        }

                                                    }
                                                    else
                                                    {

                                                        $str_arr = explode(",", $value6->object_name);
                                                        foreach ($str_arr as $imgobj)
                                                        {
                                                            $getimagevalue = $this
                                                                ->modelsManager
                                                                ->createBuilder()
                                                                ->columns(array(
                                                                'GameQuestionImageMaster.image_name',
                                                            ))
                                                                ->from('GameQuestionImageMaster')
                                                                ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                $input_data->game_id
                                                            ))
                                                                ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                $imgobj
                                                            ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                                1
                                                            ))
                                                                ->getQuery()
                                                                ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                            {
                                                                # code...
                                                                //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                $ansimagedes[] = $getimagevalueval->image_name;

                                                            }

                                                            $getanswer = 'Selected the Wrong answer : ';

                                                        }

                                                    }

                                                    $data2['qu_answer'] = $getAnsewer;
                                                    $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                                    $worngcount += 1;
						    $data2['ansper'] = 0;

                                                }

                                                else
                                                {
                                                    $getanswer = 'Colored out side the lines';
                                                    $showtitle = 'What your child colored';
                                                    $worngcount += 1;
                                                    $data2['ansper'] = 0;
                                                    $data2['colorshow'] = true;
                                                }
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false && $questionvalue->game_type != 3) 
                                            {
                                            
                                                $getanswer = 'No';
                                                $worngcount += 1;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Sad';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                                $worngcount += 1;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on out side the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $worngcount += 1;
                                                $data2['ansper'] = 0;
                                                $data2['colorshow'] = true;
                                                $data2['tracegame'] = true;

                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {
                                                $getanswer = 'Traced on out side the lines';
                                                $worngcount += 1;
                                                $showtitle = 'Actual Work Created By Your Child';
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['tracegame'] = false;
                                            }
                                            else
                                            {
                                                if ($questionvalue->game_type == 3)
                                                {
                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {

                                                         $str_arr = explode(",", $value6->object_name);

                                                          foreach ($str_arr as $imgobj)
                                                            {

                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                             foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                        }

                                                      


                                                    }
                                                    else
                                                    {
                                                         $str_arr = explode(",", $value6->object_name);

                                                          foreach ($str_arr as $imgobj)
                                                            {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }



                                                        }

                                                    }



                                                    
                                                       

                                                        $getanswer = 'Selected the Worng answer : ' . implode(", ", $ansimagedes);

                                                        $worngcount += 1;
                                                        $data2['emotional'] = false;
							$data2['ansper'] = 0;
                                                    



                                                }
                                                else
                                                {
                                                    //guna
                                                    

                                                    if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                                    {

                                                        $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                        if ($kidgender->gender == "female")
                                                        {
                                                            $str_arr = explode(",", $value6->object_name);

                                                            foreach ($str_arr as $imgobj)
                                                            {

                                                                $getimagevalue = $this
                                                                    ->modelsManager
                                                                    ->createBuilder()
                                                                    ->columns(array(
                                                                    'GameQuestionImageMaster.image_name',
                                                                ))
                                                                    ->from('GameQuestionImageMaster')
                                                                    ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                    $input_data->game_id
                                                                ))
                                                                    ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                    $imgobj
                                                                ))->inwhere("GameQuestionImageMaster.tina", array(
                                                                    1
                                                                ))
                                                                    ->getQuery()
                                                                    ->execute();

                                                                foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                                $getanswer = 'Selected the Wrong answer : ';
                                                            }

                                                        }
                                                        else
                                                        {
                                                            $wrongcountimg += 0;

                                                            $str_arr = explode(",", $value6->object_name);
                                                            foreach ($str_arr as $imgobj)
                                                            {
                                                                $getimagevalue = $this
                                                                    ->modelsManager
                                                                    ->createBuilder()
                                                                    ->columns(array(
                                                                    'GameQuestionImageMaster.image_name',
                                                                ))
                                                                    ->from('GameQuestionImageMaster')
                                                                    ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                    $input_data->game_id
                                                                ))
                                                                    ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                    $imgobj
                                                                ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                                    1
                                                                ))
                                                                    ->getQuery()
                                                                    ->execute();

                                                                foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                                $getanswer = 'Selected the Wrong answer : ';
                                                            }

                                                        }

                                                        $data2['qu_answer'] = $getAnsewer;
                                                        $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                                        $ansimagedes = [];
                                                        $data2['ansper'] = 0;

                                                        $worngcount += 1;

                                                    }
                                                    else
                                                    {
                                                        $data2['ansper'] = 0;
                                                        $getanswer = 'Selected the Worng answer';
                                                        $worngcount += 1;
                                                    }
                                                }

                                            }
                                        }

                                    }
                                }

                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = $getanswer;
                                $data2['showtitle'] = $showtitle;
                                $data2['questions_no'] = $value6->questions_no;
                                $data2['time'] = $value6->time;

                                $getGameAnswerValueArray[] = $data2;

                                $ansimagedes = [];
                            }
                        }
                        else
                        {
                            $data2['question'] = $questionvalue->question;
                            $data2['qu_answer'] = 'Not Answered';
                            $data2['time'] = '';
                            $worngcount += 1;
                        }

                        // $game_data['question_valye'] = $data2;
                        $data2['qa_imgdes'] = [];
                        $data2['tracegame'] = false;

                    }
                    else
                    {
                        $game_data['question_valye'] = '';
                    }

                    $game_data['multiclick'] = $multiclick;
                    $game_data['question_valye'] = $getGameAnswerValueArray;

                    $anspersentage = ($wrongcountimg / (count($game_answer) * 100)) * 100;
                    $game_data['anerperval'] = $anspersentage;

                    $gamedetailsarray[] = $game_data;
                    $game_data['anerperval'] = 0;
                    $wrongcountimg = 0;
                }
                $checkanswer = ($answercount + $worngcount);
                if ($questioncount == $checkanswer)
                {
                    $game_data2['questioncount'] = $questioncount;
                    $game_data2['answer'] = $answercount;
                    $game_data2['worngcount'] = $worngcount;
                }
                $game_data2['Primary_Tagging'] = $value->standard_name;
                $game_data2['over_all_time'] = $time;
                $game_data2['slide_info'] = $gamedetailsarray;
                $game_data2['game_id'] = $value->game_ids;
                $game_data2['game_time'] = $time;
                $game_data2['game_name'] = $value->games_name;
                $game_data2['frameworkname'] = $value->frameworkname;
                $game_data2['audiogame'] = $audiogame;
                $game_data2['multislide'] = $gamemultislide;

                $gamedata_array[] = $game_data2;

                $data2['qa_imgdes'] = "";
            }

            /*return $this
                ->response
                ->setJsonContent(['rc' => $answercount, 'wc' => $worngcount,'qc' => $questioncount, 'ca' => $checkanswer]);*/

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $gamedata_array, 'daily_tips' => $value->daily_tips]);
        }
    }

    /* public function getgameresult(){
    $input_data = $this->request->getJsonRawBody ();
    $headers = $this->request->getHeaders ();
    if (empty ( $input_data )) {
    return $this->response->setJsonContent ( [
        "status" => false,
        "message" => "Please give the token" 
    ] );
    }
    else{
    $today_date = date('Y-m-d');
    $game_get = $this->modelsManager->createBuilder ()->columns ( array (
    'DISTINCT GamesAnswers.session_id as session_id',
    'GamesAnswers.game_id as game_ids',
    'GamesDatabase.games_name as games_name',
    'GuidedLearningDayGameMap.subject_id as subject_id',
    'GuidedLearningDayGameMap.framework_id as framework_id',
    ))->from('GamesAnswers')
    ->leftjoin('GuidedLearningDayGameMap','GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
    ->leftjoin ( 'Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id' )
    ->leftjoin ( 'CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id' )
    ->leftjoin ('GamesDatabase','GamesAnswers.game_id = GamesDatabase.id')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.created_at',array($today_date))
    ->getQuery ()->execute ();
    $gamedata_array = array();
    
    foreach($game_get as $value){
    $gamedetails = $this->modelsManager->createBuilder ()->columns ( array (
    'DISTINCT GamesAnswers.id as id',
    'GamesAnswers.game_id as game_ids',
    'GamesAnswers.session_id as session_id',
    'GamesAnswers.questions_no as questions_no',
    'GamesAnswers.answers as answers',
    'GamesAnswers.time as time',
    'GamesAnswers.replaycount as answerreplaycount',
    'GamesAnswers.slide_no as slide_no',
    'GamesCoreframeMap.id as id',
    'Standard.standard_name as standard_name',
    'GamesCoreframeMap.gamecoretype as gamecoretype',
    'GamesDatabase.games_name as games_name',
    'GamesAnswers.slide_type as slide_type',
    ))->from('GamesAnswers')
    ->leftjoin('GamesDatabase', 'GamesDatabase.id = GamesAnswers.game_id')
    ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
    ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')
    ->inwhere ('GamesAnswers.game_id',array($input_data->game_id))
    ->inwhere ('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
    ->inwhere ('GamesAnswers.session_id ',array($value->session_id))
    ->groupBy('GamesAnswers.id')
    ->orderBy ( 'GamesAnswers.slide_no' )
    ->getQuery ()->execute ();
    $total_time = 0;
    $questioncount = 0;
    $answercount = 0;
    $worngcount = 0;
    $gamedetailsarray = array();
    $time = 0;
    foreach($gamedetails as $gameanswer){
    
    $time += $gameanswer -> time;
    //$reseltarray[] = $value3;
    if($gameanswer -> slide_no == 2){
        $game_data['Intro_Slide'] = $gameanswer -> time;
        $replay_count = $this->modelsManager->createBuilder ()->columns ( array (
            'DISTINCT GameSlideReplayCount.id as id',
        ))->from('GameSlideReplayCount')
        ->inwhere ('GameSlideReplayCount.game_id',array($input_data->game_id))
        ->inwhere ('GameSlideReplayCount.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
        ->inwhere ('GameSlideReplayCount.session_id ',array($value->session_id))
        ->inwhere ('GameSlideReplayCount.slide_no ',array(2))
        ->getQuery ()->execute ();
        $game_data['intro_slide_replay_count'] = count($replay_count);
    }
    if($gameanswer -> slide_no == 3){
        $game_data['Learning_Slide'] = $gameanswer -> time;
        $replay_count = $this->modelsManager->createBuilder ()->columns ( array (
            'DISTINCT GameSlideReplayCount.id as id',
        ))->from('GameSlideReplayCount')
        ->inwhere ('GameSlideReplayCount.game_id',array($input_data->game_id))
        ->inwhere ('GameSlideReplayCount.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
        ->inwhere ('GameSlideReplayCount.session_id ',array($value->session_id))
        ->inwhere ('GameSlideReplayCount.slide_no ',array(3))
        ->getQuery ()->execute ();
        $game_data['learning_slide_replay_count'] = count($replay_count);
    }
    if($gameanswer -> slide_no == 4){
        $game_data['Game_Intro'] = $gameanswer -> time;
        $replay_count = $this->modelsManager->createBuilder ()->columns ( array (
            'DISTINCT GameSlideReplayCount.id as id',
        ))->from('GameSlideReplayCount')
        ->inwhere ('GameSlideReplayCount.game_id',array($input_data->game_id))
        ->inwhere ('GameSlideReplayCount.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
        ->inwhere ('GameSlideReplayCount.session_id ',array($value->session_id))
        ->inwhere ('GameSlideReplayCount.slide_no ',array(4))
        ->getQuery ()->execute ();
        $game_data['game_intro_slide_replay_count'] = count($replay_count);
    }
    if($gameanswer -> gamecoretype == 1){
        $game_data['Primary_Tagging'] = $gameanswer -> standard_name ;
    }
    if($gameanswer -> gamecoretype == 2){
        $game_data['Secondary_Tagging'] = $gameanswer -> standard_name;
    }
    //$game_result_array[] = $game_result_data;
    }
    
    $game_data['over_all_time'] = $time;
    $game_question_answer = $this->modelsManager->createBuilder ()->columns ( array (
    'GamesQuestionAnswer.game_type_value as game_type_value',
    'GamesQuestionAnswer.question_id as question_id',
    'GamesQuestionAnswer.question as question',
    'GamesQuestionAnswer.answer as answer',
    ))->from('GamesQuestionAnswer')
    ->inwhere('GamesQuestionAnswer.game_id',array($input_data -> game_id))
    ->getQuery ()->execute ();
    
    foreach($game_question_answer as $questionvalue){
    $questioncount += 1;
        $game_answer = $this->modelsManager->createBuilder ()->columns ( array (
            'GamesAnswers.id as id',
            'GamesAnswers.questions_no as questions_no',
            'GamesAnswers.answers as answers',
            'GamesAnswers.time as time',
            'GamesAnswers.slide_no as slide_no',
        ))->from("GamesAnswers")
        ->inwhere ('GamesAnswers.session_id ',array($value->session_id))
        ->inwhere('GamesAnswers.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
        ->inwhere('GamesAnswers.game_id',array($input_data -> game_id))
        ->inwhere('GamesAnswers.questions_no',array($questionvalue -> question_id))
        ->groupBy('GamesAnswers.id')
        ->getQuery()->execute ();
        if(count($game_answer) > 0){
            foreach($game_answer as $value6){
    
                }
                if($value6 -> answers > 1){
                    if($questionvalue -> game_type_value > 10){
                        if($questionvalue -> game_type_value == $value6 -> answers){
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = $questionvalue -> question . ': ' . $value6 -> answers;
                            $data2['time'] = $value6 -> time;
                        } else if($questionvalue -> game_type_value < $value6 -> answers){
                            $data2['question'] = $questionvalue -> question;
                            if($questionvalue -> game_type_value > 20){
                                $qustionvalue = 'How many times did the child click on the monster';
                            } else {
                                $qustionvalue = 'How many objects did the child click on';
                            }
                            $data2['qu_answer'] = $qustionvalue . ': ' . $value6 -> answers . ' <br> How many times did the child miss - hand eye coordination ' . ($value6 -> answers - $questionvalue -> game_type_value);
                            $data2['time'] = $value6 -> time;
                        } else {
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = 'child does not complete the activity' . $value6 -> answers;
                            $data2['time'] = $value6 -> time;
                        }
                    } else {
                        if($questionvalue -> game_type_value ==  $value6 -> answers){
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue -> game_type_value / $value6 -> answers) . ' try';
                            $data2['time'] = $value6 -> time;
                        } else if($questionvalue -> game_type_value < $value6 -> answers){
                             $numbercheck = ($value6 -> answers / $questionvalue -> game_type_value);
                            if(($numbercheck %  $questionvalue -> game_type_value) == 0){
                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';
                            } else {
                                $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                            }
                            $data2['question'] = $questionvalue -> question;
                            $data2['qu_answer'] = $getAnsewer;
                            $data2['time'] = $value6 -> time;
                        }
                    }
                } else {
                    
                    $testcontent = strtolower($questionvalue -> question );
                    if($value6 -> answers == 1){
                        $answercount += 1;
                        if((strpos ($testcontent , 'colour') !== false) || (strpos ($testcontent , 'color') !== false)){
                            $getanswer = 'Colored within the lines';
                        }
                        else if(strpos ($testcontent , 'trace') !== false){
                            $getanswer = 'Traced on the lines';
                        } else if(strpos ($testcontent , 'did you child') !== false){
                            $getanswer = 'Yes';
                        }
                        else{
                            $getanswer = 'Selected the correct answer';
                        }
                    } else {
                        
                        if((strpos ($testcontent , 'colour') !== false) || (strpos ($testcontent , 'color') !== false)){
                            $getanswer = 'Colored out side the lines';
                            $worngcount += 1;
                        }
                        else if(strpos ($testcontent , 'did you child') !== false){
                            $getanswer = 'No';
                            $answercount += 1;
                        }
                        else if(strpos ($testcontent , 'trace') !== false){
                            $getanswer = 'Traced on out side the lines';
                            $worngcount += 1;
                        }
                        else{
                            $getanswer = 'Selected the Worng answer';
                            $worngcount += 1;
                        }
                    }
                    $data2['question'] = $questionvalue -> question;
                    $data2['qu_answer'] = $getanswer;
                    $data2['time'] = $value6 -> time;
                }
            }
        else{
            $data2['question'] = $questionvalue -> question;
            $data2['qu_answer'] = 'Not Answered';
            $data2['time'] = '';
            $worngcount += 1;
        }
        $replay_count = $this->modelsManager->createBuilder ()->columns ( array (
            'DISTINCT GameSlideReplayCount.id as id',
        ))->from('GameSlideReplayCount')
        ->inwhere ('GameSlideReplayCount.game_id',array($input_data->game_id))
        ->inwhere ('GameSlideReplayCount.nidara_kid_profile_id',array($input_data->nidara_kid_profile_id))
        ->inwhere ('GameSlideReplayCount.session_id ',array($value->session_id))
        ->inwhere ('GameSlideReplayCount.question_id ',array($questionvalue -> question_id))
        ->getQuery ()->execute ();
        $data2['question_replay_count'] = count($replay_count);
        $gamedetailsarray[] = $data2;
    }
    $game_data['question'] = $gamedetailsarray;
    $checkanswer = ($answercount+$worngcount);
    if($questioncount == $checkanswer){
        $game_data['questioncount'] = $questioncount;
        $game_data['answer'] = $answercount;
        $game_data['worngcount'] = $worngcount;
    }
    $game_data['created_at'] = $value->created_ats;         
    $game_data['game_answers'] = $game_result_array;
    $game_data['Total'] = $total ;
    $game_data['answercount'] = $answer ;
    $game_data['wrong'] = $total - $answer;
    $game_data['game_id'] = $value->game_ids;
    $game_data['game_time'] = $time;
    $game_data['game_name'] = $value->games_name;
    $game_data['subject_id'] = $value->subject_id;
    $game_data['framework_id'] = $value->framework_id;
    $gamedata_array [] = $game_data;
    }
    return $this->response->setJsonContent ( [
    'status' => true,
    'data' => $gamedata_array
    ] );
    }
    } */

    /**
     * Save session id
     */
    public function getSessionMainId()
    {
        $gamesesstion->session_id = uniqid();
        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => $gamesesstion->session_id]);
    }
    /**
     * Save game history
     */
    public function getSessionId()
    {
        //$gamemapid = $this->getGuidedLearningId ( $gameid );
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $gamehistory = new GameHistory();
        $gamehistory->id = $this
            ->gamesidgen
            ->getNewId('gameshistory');
        $gamehistory->session_id = $input_data->session_id;
        $gamehistory->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
        $gamehistory->guided_learning_games_map_id = $input_data->guided_learning_games_map_id;
        $gamehistory->created_at = date('Y-m-d H:i:s');
        $gamehistory->created_by = 1;
        if ($gamehistory->save())
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'message' => 'Game saved successfully']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'message' => $gamehistory]);
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
    public function dummydata()
    {
        $json = file_get_contents(APP_PATH . "/library/gamesdata/games.json");
        return $this
            ->response
            ->setJsonContent(json_decode($json, true));
    }

    public function getgameresultbydates()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        /*$headers = $this->request->getHeaders ();
        if (empty ( $headers ['Token'] )) {
        return $this->response->setJsonContent ( [
        "status" => false,
        "message" => "Please give the token"
        ] );
        }
        else{*/
        $from = $input_data->from_date;
        $to = $input_data->to_date;
        $game_get = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'DISTINCT GamesAnswers.session_id as session_id',
            'GamesAnswers.game_id as game_ids',
            'GamesAnswers.created_at',
            'GamesDatabase.games_name as games_name',
            'GuidedLearningDayGameMap.subject_id as subject_id',
            'GuidedLearningDayGameMap.framework_id as framework_id',
        ))
            ->from('GamesAnswers')
            ->leftjoin('GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id = GamesAnswers.game_id')
            ->leftjoin('Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id')
            ->leftjoin('CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id')
            ->leftjoin('GamesDatabase', 'GamesAnswers.game_id = GamesDatabase.id')
            ->where("GamesAnswers.created_at >='" . $from . "' and GamesAnswers.created_at <='" . $to . "'")->inwhere('GamesAnswers.game_id', array(
            $input_data->game_id
        ))
            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
            $input_data->nidara_kid_profile_id
        ))

            ->getQuery()
            ->execute();
        $temp = "";
        foreach ($game_get as $value)
        {
            if ($temp != $value->created_at)
            {
                $gamedata_array = array();
            }
            $temp = $value->created_at;
            $gamedetails = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT GamesAnswers.id as id',
                'GamesAnswers.game_id as game_ids',
                'GamesAnswers.session_id as session_id',
                'GamesAnswers.questions_no as questions_no',
                'GamesAnswers.answers as answers',
                'GamesAnswers.time as time',
                'GamesAnswers.slide_no as slide_no',
                'GamesCoreframeMap.id as id',
                'Standard.standard_name as standard_name',
                'GamesCoreframeMap.gamecoretype as gamecoretype',
                'GamesDatabase.games_name as games_name'
            ))
                ->from('GamesAnswers')
                ->leftjoin('GamesDatabase', 'GamesDatabase.id = GamesAnswers.game_id')
                ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')
                ->inwhere('GamesAnswers.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('GamesAnswers.session_id ', array(
                $value->session_id
            ))
                ->groupBy('GamesAnswers.id')
                ->orderBy('GamesAnswers.slide_no')
                ->getQuery()
                ->execute();
            $total_time = 0;
            $questioncount = 0;
            $answercount = 0;
            $worngcount = 0;
            $gamedetailsarray = array();
            $time = 0;
            foreach ($gamedetails as $gameanswer)
            {

                $time += $gameanswer->time;
                //$reseltarray[] = $value3;
                if ($gameanswer->slide_no == 2)
                {
                    $game_data['Intro_Slide'] = $gameanswer->time;
                    $replay_count = $this
                        ->modelsManager
                        ->createBuilder()
                        ->columns(array(
                        'DISTINCT GameSlideReplayCount.id as id',
                    ))
                        ->from('GameSlideReplayCount')
                        ->inwhere('GameSlideReplayCount.game_id', array(
                        $input_data->game_id
                    ))
                        ->inwhere('GameSlideReplayCount.nidara_kid_profile_id', array(
                        $input_data->nidara_kid_profile_id
                    ))
                        ->inwhere('GameSlideReplayCount.session_id ', array(
                        $value->session_id
                    ))
                        ->inwhere('GameSlideReplayCount.slide_no ', array(
                        2
                    ))
                        ->getQuery()
                        ->execute();
                    $game_data['intro_slide_replay_count'] = count($replay_count);
                }
                if ($gameanswer->slide_no == 3)
                {
                    $game_data['Learning_Slide'] = $gameanswer->time;
                    $replay_count = $this
                        ->modelsManager
                        ->createBuilder()
                        ->columns(array(
                        'DISTINCT GameSlideReplayCount.id as id',
                    ))
                        ->from('GameSlideReplayCount')
                        ->inwhere('GameSlideReplayCount.game_id', array(
                        $input_data->game_id
                    ))
                        ->inwhere('GameSlideReplayCount.nidara_kid_profile_id', array(
                        $input_data->nidara_kid_profile_id
                    ))
                        ->inwhere('GameSlideReplayCount.session_id ', array(
                        $value->session_id
                    ))
                        ->inwhere('GameSlideReplayCount.slide_no ', array(
                        3
                    ))
                        ->getQuery()
                        ->execute();
                    $game_data['learning_slide_replay_count'] = count($replay_count);
                }
                if ($gameanswer->slide_no == 4)
                {
                    $game_data['Game_Intro'] = $gameanswer->time;
                    $replay_count = $this
                        ->modelsManager
                        ->createBuilder()
                        ->columns(array(
                        'DISTINCT GameSlideReplayCount.id as id',
                    ))
                        ->from('GameSlideReplayCount')
                        ->inwhere('GameSlideReplayCount.game_id', array(
                        $input_data->game_id
                    ))
                        ->inwhere('GameSlideReplayCount.nidara_kid_profile_id', array(
                        $input_data->nidara_kid_profile_id
                    ))
                        ->inwhere('GameSlideReplayCount.session_id ', array(
                        $value->session_id
                    ))
                        ->inwhere('GameSlideReplayCount.slide_no ', array(
                        4
                    ))
                        ->getQuery()
                        ->execute();
                    $game_data['game_intro_slide_replay_count'] = count($replay_count);
                }
                if ($gameanswer->gamecoretype == 1)
                {
                    $game_data['Primary_Tagging'] = $gameanswer->standard_name;
                }
                if ($gameanswer->gamecoretype == 2)
                {
                    $game_data['Secondary_Tagging'] = $gameanswer->standard_name;
                }
                //$game_result_array[] = $game_result_data;
                
            }

            $game_data['over_all_time'] = $time;
            $game_question_answer = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'GamesQuestionAnswer.game_type_value as game_type_value',
                'GamesQuestionAnswer.question_id as question_id',
                'GamesQuestionAnswer.question as question',
                'GamesQuestionAnswer.answer as answer',
            ))
                ->from('GamesQuestionAnswer')
                ->inwhere('GamesQuestionAnswer.game_id', array(
                $input_data->game_id
            ))
                ->getQuery()
                ->execute();

            foreach ($game_question_answer as $questionvalue)
            {
                $questioncount += 1;
                $game_answer = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesAnswers.id as id',
                    'GamesAnswers.questions_no as questions_no',
                    'GamesAnswers.answers as answers',
                    'GamesAnswers.time as time',
                    'GamesAnswers.slide_no as slide_no',
                ))
                    ->from("GamesAnswers")
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.questions_no', array(
                    $questionvalue->question_id
                ))
                    ->groupBy('GamesAnswers.id')
                    ->getQuery()
                    ->execute();
                if (count($game_answer) > 0)
                {
                    foreach ($game_answer as $value6)
                    {

                    }
                    if ($value6->answers > 1)
                    {
                        if ($questionvalue->game_type_value > 10)
                        {
                            if ($questionvalue->game_type_value == $value6->answers)
                            {
                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                $data2['time'] = $value6->time;
                            }
                            else if ($questionvalue->game_type_value < $value6->answers)
                            {
                                $data2['question'] = $questionvalue->question;
                                if ($questionvalue->game_type_value > 20)
                                {
                                    $qustionvalue = 'How many times did the child click on the monster';
                                }
                                else
                                {
                                    $qustionvalue = 'How many objects did the child click on';
                                }
                                $data2['qu_answer'] = $qustionvalue . ': ' . $value6->answers . ' <br> How many times did the child miss - hand eye coordination ' . ($value6->answers - $questionvalue->game_type_value);
                                $data2['time'] = $value6->time;
                            }
                            else
                            {
                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                $data2['time'] = $value6->time;
                            }
                        }
                        else
                        {
                            if ($questionvalue->game_type_value == $value6->answers)
                            {
                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';
                                $data2['time'] = $value6->time;
                            }
                            else if ($questionvalue->game_type_value < $value6->answers)
                            {
                                $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                if (($numbercheck % $questionvalue->game_type_value) == 0)
                                {
                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';
                                }
                                else
                                {
                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                }
                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = $getAnsewer;
                                $data2['time'] = $value6->time;
                            }
                        }
                    }
                    else
                    {

                        $testcontent = strtolower($questionvalue->question);
                        if ($value6->answers == 1)
                        {
                            $answercount += 1;
                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                            {
                                $getanswer = 'Colored within the lines';
                                $ansperval = 100;

                            }
                            else if (strpos($testcontent, 'trace') !== false)
                            {
                                $getanswer = 'Traced on the lines';
                                $ansperval = 100;
                            }
                            else if (strpos($testcontent, 'did you child') !== false)
                            {
                                $getanswer = 'Yes';
                            }
                            else
                            {
                                $getanswer = 'Selected the correct answer';
                            }
                        }
                        else
                        {

                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false))
                            {
                                $getanswer = 'Colored out side the lines';
                                $worngcount += 1;
                                $ansperval = 0;
                            }
                            else if (strpos($testcontent, 'did you child') !== false)
                            {
                                $answercount += 1;
                            }
                            else if (strpos($testcontent, 'trace') !== false)
                            {
                                $getanswer = 'Traced on out side the lines';
                                $worngcount += 1;
                                $ansperval = 0;
                            }
                            else
                            {
                                $getanswer = 'Selected the Worng answer';
                                $worngcount += 1;
                            }
                        }
                        $data2['question'] = $questionvalue->question;
                        $data2['qu_answer'] = $getanswer;
                        $data2['time'] = $value6->time;
                        $data2['ansper'] = $ansperval;
                    }
                }
                else
                {
                    $data2['question'] = $questionvalue->question;
                    $data2['qu_answer'] = 'Not Answered';
                    $data2['time'] = '';
                    $worngcount += 1;
                }
                $replay_count = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'DISTINCT GameSlideReplayCount.id as id',
                ))
                    ->from('GameSlideReplayCount')
                    ->inwhere('GameSlideReplayCount.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GameSlideReplayCount.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GameSlideReplayCount.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('GameSlideReplayCount.question_id ', array(
                    $questionvalue->question_id
                ))
                    ->getQuery()
                    ->execute();
                $data2['question_replay_count'] = count($replay_count);
                $gamedetailsarray[] = $data2;
            }
            $game_data['question'] = $gamedetailsarray;
            $checkanswer = ($answercount + $worngcount);
            if ($questioncount == $checkanswer)
            {
                $game_data['questioncount'] = $questioncount;
                $game_data['answer'] = $answercount;
                $game_data['worngcount'] = $worngcount;
            }
            $game_data['created_at'] = $value->created_ats;
            $game_data['game_answers'] = $game_result_array;
            $game_data['Total'] = $total;
            $game_data['answercount'] = $answer;
            $game_data['wrong'] = $total - $answer;
            $game_data['game_id'] = $value->game_ids;
            $game_data['game_time'] = $time;
            $game_data['game_name'] = $value->games_name;
            $game_data['subject_id'] = $value->subject_id;
            $game_data['framework_id'] = $value->framework_id;
            $gamedata_array[] = $game_data;
        }
        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => $gamedata_array]);
        /*}*/
    }

    public function shootgameanswersave()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameanswersshootgame = new GameAnswersShootgame();

        $gameanswersshootgame->child_id = $input_data->nidara_kid_profile_id;
        $gameanswersshootgame->game_id = $input_data->game_id;
        $gameanswersshootgame->session_id = $input_data->session_id;
        $gameanswersshootgame->click_count = $input_data->click_count;
        $gameanswersshootgame->time = date('H:i:s');
        $gameanswersshootgame->create_at = date('Y-m-d');

        if ($gameanswersshootgame->save())
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'Message' => 'Saved']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Not Saved']);
        }

    }

    public function normalgamecolorsave()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameanswercolor = new GamesAnswersColor();

        $gameanswercolor->child_id = $input_data->nidara_kid_profile_id;
        $gameanswercolor->game_id = $input_data->game_id;
        $gameanswercolor->session_id = $input_data->session_id;
        $gameanswercolor->click_count = $input_data->click_count;
        $gameanswercolor->time = date('H:i:s');
        $gameanswercolor->slide_no = $input_data->slide_no;
        $gameanswercolor->question_id = $input_data->question_id;
        $gameanswercolor->create_at = date('Y-m-d');

        if ($gameanswercolor->save())
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'Message' => 'Saved']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Not Saved']);
        }

    }

    public function gameaudiocountsave()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameanswercolor = new GamesAnswersAudioCount();

        $gameanswercolor->child_id = $input_data->nidara_kid_profile_id;
        $gameanswercolor->game_id = $input_data->game_id;
        $gameanswercolor->session_id = $input_data->session_id;
        $gameanswercolor->click_count = $input_data->click_count;
        $gameanswercolor->time = date('H:i:s');
        $gameanswercolor->slide_no = $input_data->slide_no;
        $gameanswercolor->question_id = $input_data->question_id;
        $gameanswercolor->create_at = date('Y-m-d');

        if ($gameanswercolor->save())
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'Message' => 'Saved']);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Not Saved']);
        }

    }
    
     public function getgameresulttest()
    {
        $gamemultislide = false;
        $multiclick = false;

        $baseurl = $this
            ->config->colorurl;

        $input_data = $this
            ->request
            ->getJsonRawBody();
        //$headers = $this->request->getHeaders ();
        $headers = $this
            ->request
            ->getHeaders();
        if (!empty($headers['Token']))
        {
            return $this
                ->response
                ->setJsonContent(["status" => false, "message" => "Pleasesss give the token"]);
        }
        else
        {

            $today_date = date('Y-m-d');

            $shootgamecheck = false;
            $audiogame = false;
            $colorgame = false;

            $game_get = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'DISTINCT KidsGamesStatus.session_id as session_id',
                'KidsGamesStatus.game_id as game_ids',
                'CoreFrameworks.name as frameworkname',
                'GamesDatabase.games_name as games_name',
                'Standard.standard_name as standard_name',
                'GamesDatabase.daily_tips',
                'GuidedLearningDayGameMap.subject_id as subject_id',
                'GuidedLearningDayGameMap.framework_id as framework_id',
            ))
                ->from('KidsGamesStatus')
                ->leftjoin('GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id = KidsGamesStatus.game_id')

                ->leftjoin('Subject', 'GuidedLearningDayGameMap.subject_id=Subject.id')
                ->leftjoin('CoreFrameworks', 'GuidedLearningDayGameMap.framework_id=CoreFrameworks.id')
                ->leftjoin('GamesDatabase', 'KidsGamesStatus.game_id = GamesDatabase.id')
                ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')

                ->inwhere('KidsGamesStatus.game_id', array(
                $input_data->game_id
            ))
                ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                $input_data->nidara_kid_profile_id
            ))
                ->inwhere('KidsGamesStatus.created_date', array(
                $today_date
            ))->groupBy('KidsGamesStatus.session_id')
                ->getQuery()
                ->execute();
            $gamedata_array = array();




            foreach ($game_get as $value)
            {

                $gaemstatus = $gamedetails = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'KidsGamesStatus.id',

                ))
                    ->from('KidsGamesStatus')
                    ->inwhere('KidsGamesStatus.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('KidsGamesStatus.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('KidsGamesStatus.current_status', array(
                    1
                ))
                    ->getQuery()
                    ->execute();

                    

                if (count($gaemstatus) > 0)
                {
                    $game_data2["gamestatus"] = true;
                }
                else
                {
                    $game_data2["gamestatus"] = false;
                }

                $gamedetails = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'DISTINCT GamesAnswers.id as id',
                    'GamesAnswers.game_id as game_ids',
                    'GamesAnswers.session_id as session_id',
                    'GamesAnswers.questions_no as questions_no',
                    'GamesAnswers.answers as answers',
                    'GamesAnswers.time as time',
                    'GamesAnswers.slide_type as slide_type',
                    'GamesAnswers.replaycount as answerreplaycount',
                    'GamesAnswers.slide_no as slide_no',
                    'GamesCoreframeMap.id as id',
                    'Standard.standard_name as standard_name',
                    'GamesCoreframeMap.gamecoretype as gamecoretype',
                    'GamesDatabase.games_name as games_name'
                ))
                    ->from('GamesAnswers')
                    ->leftjoin('GamesDatabase', 'GamesDatabase.id = GamesAnswers.game_id')
                    ->leftjoin('GamesCoreframeMap', 'GamesDatabase.id = GamesCoreframeMap.game_id')
                    ->leftjoin('Standard', 'GamesCoreframeMap.standard_id = Standard.id')
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->groupBy('GamesAnswers.slide_no')
                    ->orderBy('GamesAnswers.slide_no')
                    ->getQuery()
                    ->execute();

                $total_time = 0;
                $questioncount = 0;
                $answercount = 0;
                $worngcount = 0;
                $gamedetailsarray = array();
                $time = 0;

                 $overalltime = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                   
                    'GamesAnswers.time as time',
                    
                ))
                    ->from('GamesAnswers')
                    
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->orderBy('GamesAnswers.slide_no')
                    ->getQuery()
                    ->execute();
                    foreach ($overalltime as $timevalueoverall) {

                        $time += $timevalueoverall->time;
                        # code...
                    }

                foreach ($gamedetails as $gameanswer)
                {

                   
                    /*$game_data['time'] = $gameanswer->time;
                    $game_data['slide_type'] = $gameanswer->slide_type;
                    $game_data['slide_no'] = $gameanswer->slide_no;

                    $game_data['replaycount'] = $gameanswer->answerreplaycount;*/

                    if($gameanswer->slide_type != "learning")
                   {
                    $game_data['time'] = $gameanswer->time;
                    $game_data['slide_type'] = $gameanswer->slide_type;
                    $game_data['slide_no'] = $gameanswer->slide_no;
                    $game_data['replaycount'] = $gameanswer->answerreplaycount;
                    }
                    else
                    {
                        $learning = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'DISTINCT GamesAnswers.id as id',
                    'GamesAnswers.game_id as game_ids',
                    'GamesAnswers.session_id as session_id',
                    'GamesAnswers.questions_no as questions_no',
                    'GamesAnswers.answers as answers',
                    'GamesAnswers.time as time',
                    'GamesAnswers.slide_type as slide_type',
                    'GamesAnswers.replaycount as answerreplaycount',
                    'GamesAnswers.slide_no as slide_no',
                ))
                    ->from('GamesAnswers')
                    ->inwhere('GamesAnswers.game_id', array(
                    $input_data->game_id
                ))
                    ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                    ->inwhere('GamesAnswers.session_id ', array(
                    $value->session_id
                ))
                    ->inwhere('GamesAnswers.slide_type ', array(
                    "learning"
                ))
                     ->inwhere('GamesAnswers.slide_no ', array(
                    $gameanswer->slide_no
                ))
                    ->orderBy('GamesAnswers.id')
                    ->getQuery()
                    ->execute();

                    foreach ($learning as $learningval) {
                        # code...
                  
                   $game_data['time'] = $learningval->time;
                    $game_data['slide_type'] = $learningval->slide_type;
                    $game_data['slide_no'] = $learningval->slide_no;
                    $game_data['replaycount'] = $learningval->answerreplaycount;
                        $gamedetailsarray[]=$game_data;
                        $game_data=false;
                    }



                    }

                    if ($gameanswer->questions_no >= 1)
                    {

                        $game_answer = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'GamesAnswers.id as id',
                            'GamesAnswers.questions_no as questions_no',
                            'GamesAnswers.answers as answers',
                            'GamesAnswers.object_name',
                            'GamesAnswers.replaycount as answerreplaycount',
                            'GamesAnswers.time as time',
                            'GamesAnswers.slide_no as slide_no',
                        ))
                            ->from("GamesAnswers")
                            ->inwhere('GamesAnswers.session_id ', array(
                            $value->session_id
                        ))
                            ->inwhere('GamesAnswers.nidara_kid_profile_id', array(
                            $input_data->nidara_kid_profile_id
                        ))
                            ->inwhere('GamesAnswers.game_id', array(
                            $input_data->game_id
                        ))
                            ->inwhere('GamesAnswers.slide_no', array(
                            $gameanswer->slide_no
                        ))
                            ->groupBy('GamesAnswers.id')
                            ->getQuery()
                            ->execute();

                        $getGameAnswerValueArray = array();
                        if (count($game_answer) > 0)
                        {

                            $wrongcountimg = 0;
                            foreach ($game_answer as $value6)
                            {

                                $data2['replaycount'] = $value6->answerreplaycount;

                                $questioncount += 1;
                                $anspersentage = 0;

                                $colorval = array();

                                $data2['qa_imgdes'] = [];

                                $game_question_answer = $this
                                    ->modelsManager
                                    ->createBuilder()
                                    ->columns(array(
                                    'GamesQuestionAnswer.game_type_value as game_type_value',
                                    'GamesQuestionAnswer.game_type',
                                    'GamesQuestionAnswer.question_id as question_id',
                                    'GamesQuestionAnswer.question as question',
                                    'GamesQuestionAnswer.answer as answer',
                                ))
                                    ->from('GamesQuestionAnswer')
                                    ->inwhere('GamesQuestionAnswer.game_id', array(
                                    $input_data->game_id
                                ))
                                    ->inwhere('GamesQuestionAnswer.question_id', array(
                                    $value6->questions_no
                                ))
                                    ->getQuery()
                                    ->execute();

                                foreach ($game_question_answer as $questionvalue)
                                {

                                    $getaudiovalue = $this
                                        ->modelsManager
                                        ->createBuilder()
                                        ->columns(array(
                                        'GamesAnswersAudioCount.click_count',
                                    ))
                                        ->from('GamesAnswersAudioCount')
                                        ->inwhere("GamesAnswersAudioCount.game_id", array(
                                        $input_data->game_id
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.child_id", array(
                                        $input_data->nidara_kid_profile_id
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.question_id", array(
                                        $value6->questions_no
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.slide_no", array(
                                        $gameanswer->slide_no
                                    ))
                                        ->inwhere("GamesAnswersAudioCount.session_id", array(
                                        $value->session_id
                                    ))

                                        ->getQuery()
                                        ->execute();

                                    if (count($getaudiovalue) > 0)
                                    {

                                        $audiocountval = $getaudiovalue[0]->click_count;

                                        $audiogame = true;
                                    }
                                    else
                                    {
                                        $audiocountval = 0;
                                    }
                                    $data2['audiocount'] = $audiocountval;

                                    if ($value6->answers > 1)
                                    {
                                        $data2['clickcolor'] = null;
                                        $data2['answer'] = null;
                                        $colorgame = false;
                                        $data2['colorgame'] = $colorgame;

                                        if ($questionvalue->game_type_value > 10)
                                        {

                                            $game_answer = $this
                                                ->modelsManager
                                                ->createBuilder()
                                                ->columns(array(
                                                'GameAnswersShootgame.id',
                                                'GameAnswersShootgame.child_id',
                                                'GameAnswersShootgame.game_id',
                                                'GameAnswersShootgame.session_id',
                                                'GameAnswersShootgame.click_count',
                                                'GameAnswersShootgame.time',
                                                'GameAnswersShootgame.create_at',

                                            ))
                                                ->from("GameAnswersShootgame")
                                                ->inwhere('GameAnswersShootgame.child_id', array(
                                                $input_data->nidara_kid_profile_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.game_id', array(
                                                $input_data->game_id
                                            ))
                                                ->inwhere('GameAnswersShootgame.session_id', array(
                                                $value->session_id
                                            ))
                                                ->orderby('GameAnswersShootgame.id')
                                                ->getQuery()
                                                ->execute();

                                            if (count($game_answer) > 0)
                                            {

                                                $shootgamecheck = true;

                                                $totalcount = array();
                                                $countold = '00:00:00';
                                                $i = 0;
                                                foreach ($game_answer as $valueshoot)
                                                {
                                                    if ($i == 0)
                                                    {
                                                        $count['differentcount'] = "00";
                                                    }
                                                    else
                                                    {
                                                        $count['differentcount'] = date('s', strtotime($valueshoot->time) - strtotime($countold));
                                                    }

                                                    $count['child_id'] = $valueshoot->child_id;
                                                    $count['game_id'] = $valueshoot->game_id;
                                                    $count['click_count'] = $valueshoot->click_count;
                                                    $count['session_id'] = $valueshoot->session_id;
                                                    $countold = $valueshoot->time;

                                                    $i = i + 1;

                                                    $totalcount[] = $count;
                                                }

                                                $data2['time_interval'] = $totalcount;
                                                $data2['total_click_count'] = count($game_answer);
                                                $data2['shootgame'] = $shootgamecheck;

                                            }

                                            if ($questionvalue->game_type_value == $value6->answers)
                                            {
                                                // $data2['question'] = $questionvalue->question;
                                                if ($questionvalue->game_type_value > 20)
                                                {
                                                    $qustionvalue = 'How many times did the child click on the monster?';
                                                }
                                                else
                                                {
                                                    $qustionvalue = 'How many objects did the user correctly click on the object?';
                                                }
                                                $data2['qu_answer1'] = $qustionvalue;
                                                $data2['qu_answer1_count'] = ($questionvalue->game_type_value);

                                                $data2['qu_answer2'] = 'How many times did the child miss - hand eye coordination?';

                                                $data2['qu_answer2_count'] = ($value6->answers - $questionvalue->game_type_value);

                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $questionvalue->question . ': ' . $value6->answers;
                                                $data2['time'] = $value6->time;
                                            }
                                            else if ($questionvalue->game_type_value < $value6->answers)
                                            {
                                                $data2['question'] = $questionvalue->question;
                                                if ($questionvalue->game_type_value > 20)
                                                {
                                                    $qustionvalue = 'How many times did the child click on the monster?';
                                                }
                                                else
                                                {
                                                    $qustionvalue = 'How many objects did the user correctly click on the object?';
                                                }
                                                $data2['qu_answer1'] = $qustionvalue;
                                                $data2['qu_answer1_count'] = ($questionvalue->game_type_value);

                                                $data2['qu_answer2'] = 'How many times did the child miss - hand eye coordination?';

                                                $data2['qu_answer2_count'] = ($value6->answers - $questionvalue->game_type_value);

                                                $data2['time'] = $value6->time;

                                                $worngcount += 1;

                                            }
                                            else
                                            {
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = 'child does not complete the activity' . $value6->answers;
                                                $data2['time'] = $value6->time;
                                            }
                                        }
                                        else
                                        {
                                            $multiclick = true;

                                            $ansimagedes = array();

                                            if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                            {

                                                //gunates
                                                

                                                $wrongcountimg += 100;

                                                $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                if ($kidgender->gender == "female")
                                                {
                                                    $str_arr = explode(",", $value6->object_name);

                                                    foreach ($str_arr as $imgobj)
                                                    {

                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                        foreach ($getimagevalue as $getimagevalueval)
                                                        {
                                                            # code...
                                                            //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                            $ansimagedes[] = $getimagevalueval->image_name;

                                                        }

                                                        $getanswer = 'Selected the correct answer : ';
                                                    }

                                                }
                                                else
                                                {

                                                    $str_arr = explode(",", $value6->object_name);
                                                    foreach ($str_arr as $imgobj)
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                        foreach ($getimagevalue as $getimagevalueval)
                                                        {
                                                            # code...
                                                            //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                            $ansimagedes[] = $getimagevalueval->image_name;

                                                        }

                                                        $getanswer = 'Selected the correct answer : ';
                                                    }

                                                }

                                                $data2['qu_answer'] = $getanswer;
                                                $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                            }

                                            if ($questionvalue->game_type_value == $value6->answers)
                                            {
                                                $data2['multiclick'] = $multiclick;
                                                $data2['ansper'] = 100;

                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = 'How many tries did the child take to complete  this activity correctly: ' . ($questionvalue->game_type_value / $value6->answers) . ' try';

                                                $data2['time'] = $value6->time;

                                                $answercount += 1;
                                            }
                                            else if ($questionvalue->game_type_value < $value6->answers)
                                            {
                                                $data2['multiclick'] = $multiclick;
                                                $data2['ansper'] = 0;
                                                $numbercheck = ($value6->answers / $questionvalue->game_type_value);
                                                if (($numbercheck % $questionvalue->game_type_value) == 0)
                                                {
                                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . $numbercheck . ' tries';

                                                }
                                                else
                                                {
                                                    $getAnsewer = 'How many tries did the child take to complete  this activity correctly: ' . (round($numbercheck) + 1) . ' tries';
                                                }
                                                $data2['question'] = $questionvalue->question;
                                                $data2['qu_answer'] = $getAnsewer;
                                                $data2['time'] = $value6->time;
                                                $worngcount += 1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $data2['shootgame'] = false;

                                        if (substr($value6->object_name, strlen($value6->object_name) - 3, strlen($value6->object_name)) == 'png')
                                        {

                                            if (substr($value6->object_name, 0, 1) == "/")
                                            {

                                                if ($value6->answers == 0)
                                                {
                                                    $worngcount += 1;
                                                }
                                                else
                                                {
                                                    $answercount += 1;
                                                }

                                                $slideone = $gameanswer->slide_no;
                                                $qnoone = $value6->questions_no;

                                                if (($slideone == $slidetwo) && $qnoone != $qnotwo)
                                                {
                                                    $gamemultislide = true;
                                                }
                                                else
                                                {
                                                    $gamemultislide = false;
                                                }
                                                $slidetwo = $slideone;
                                                $qnotwo = $qnoone;

                                                // $answercount += 1;
                                                $getcolorvalue = $this
                                                    ->modelsManager
                                                    ->createBuilder()
                                                    ->columns(array(
                                                    'GamesAnswersColor.click_count',
                                                ))
                                                    ->from('GamesAnswersColor')
                                                    ->inwhere("GamesAnswersColor.game_id", array(
                                                    $input_data->game_id
                                                ))
                                                    ->inwhere("GamesAnswersColor.child_id", array(
                                                    $input_data->nidara_kid_profile_id
                                                ))
                                                    ->inwhere("GamesAnswersColor.question_id", array(
                                                    $value6->questions_no

                                                ))
                                                    ->inwhere("GamesAnswersColor.slide_no", array(
                                                    $gameanswer->slide_no
                                                ))
                                                    ->inwhere("GamesAnswersColor.session_id", array(
                                                    $value->session_id
                                                ))

                                                    ->getQuery()
                                                    ->execute();

                                                if (count($getcolorvalue) > 0)
                                                {
                                                    foreach ($getcolorvalue as $getcolorval)
                                                    {
                                                        $colorgame = true;

                                                        $colorvalue['color'] = $getcolorval->click_count;
                                                        $colorval[] = $colorvalue;
                                                        $data2['clickcolor'] = $colorval;
                                                        $data2['answer'] = $baseurl . $value6->object_name;
                                                        $data2['colorgame'] = $colorgame;

                                                    }
                                                }
                                                else
                                                {
                                                    $colorgame = false;

                                                    $data2['clickcolor'] = null;
                                                    $data2['answer'] = null;
                                                    $data2['colorgame'] = $colorgame;

                                                }

                                                $showtitle = 'Actual Work Created By Your Child';

                                            }
                                            else
                                            {

                                                $colorvalue['obj'] = $value6->object_name;
                                                //$worngcount += 1;
                                                $colorgame = false;

                                                $data2['clickcolor'] = null;
                                                $data2['answer'] = null;
                                                $data2['colorgame'] = $colorgame;

                                            }

                                        }
                                        else
                                        {
                                            $colorvalue['qno'] = $value6->questions_no;
                                            $colorvalue['qso'] = $gameanswer->slide_no;
                                            $colorvalue['obj'] = $value6->object_name;
                                            $data2['clickcolor'] = null;
                                            $data2['answer'] = null;
                                            $data2['colorgame'] = $colorgame;

                                        }

                                        $testcontent = strtolower($questionvalue->question);

                                        if ($value6->answers == 1 && substr($value6->object_name, 0, 1) != "/")
                                        {

                                            //a
                                            $answercount += 1;

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $questionvalue->answer == 'color')
                                            {
                                                $getanswer = 'Colored within the lines';
                                                $showtitle = 'What your child colored';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'Actual Work Created By Your Child';
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Happy';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'Yes';
                                            }
                                            else
                                            {

                                                if ($questionvalue->game_type == 3 && substr($value6->object_name, 0, 1) != "/")
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();
                                                    }
                                                    else
                                                    {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $value6->object_name
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                    }
                                                    if (count($getimagevalue) > 0)
                                                    {
                                                        $data2['ansper'] = 100;
                                                        $data2['emotional'] = false;
                                                        $getanswer = 'Selected the correct answer : ' . $getimagevalue[0]->image_name;
                                                    }

                                                }
                                                else
                                                {
                                                    $data2['emotional'] = false;
                                                    $getanswer = 'Selected the correct answer';
                                                }
                                            }
                                        }
                                        else if ($value6->answers == 1 && substr($value6->object_name, 0, 1) == "/" && strpos($testcontent, 'trace') !== false || strpos($testcontent, 'draw your own') !== false)
                                        {

                                            $answercount += 1;

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $questionvalue->answer == 'color')
                                            {
                                                $getanswer = 'Colored within the lines';
                                                $showtitle = 'What your child colored';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $data2['ansper'] = 100;
                                                $data2['colorshow'] = true;
                                                $data2['tracegame'] = true;
                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {

                                                $getanswer = 'Traced on the lines';
                                                $showtitle = 'Actual Work Created By Your Child';

                                                $data2['tracegame'] = false;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Happy';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false)
                                            {
                                                $getanswer = 'Yes';
                                            }
                                            else
                                            {
                                                $data2['emotional'] = false;
                                                $getanswer = 'Selected the correct answer';
                                            }
                                        }
                                        else
                                        {
                                            //guna
                                            

                                            if ((strpos($testcontent, 'colour') !== false) || (strpos($testcontent, 'color') !== false) && $questionvalue->answer == 'color')
                                            {

                                                if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                                {

                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {
                                                        $str_arr = explode(",", $value6->object_name);

                                                        foreach ($str_arr as $imgobj)
                                                        {

                                                            $getimagevalue = $this
                                                                ->modelsManager
                                                                ->createBuilder()
                                                                ->columns(array(
                                                                'GameQuestionImageMaster.image_name',
                                                            ))
                                                                ->from('GameQuestionImageMaster')
                                                                ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                $input_data->game_id
                                                            ))
                                                                ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                $imgobj
                                                            ))->inwhere("GameQuestionImageMaster.tina", array(
                                                                1
                                                            ))
                                                                ->getQuery()
                                                                ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                            {
                                                                # code...
                                                                //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                $ansimagedes[] = $getimagevalueval->image_name;

                                                            }

                                                            $getanswer = 'Selected the Wrong answer : ';
                                                        }

                                                    }
                                                    else
                                                    {

                                                        $str_arr = explode(",", $value6->object_name);
                                                        foreach ($str_arr as $imgobj)
                                                        {
                                                            $getimagevalue = $this
                                                                ->modelsManager
                                                                ->createBuilder()
                                                                ->columns(array(
                                                                'GameQuestionImageMaster.image_name',
                                                            ))
                                                                ->from('GameQuestionImageMaster')
                                                                ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                $input_data->game_id
                                                            ))
                                                                ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                $imgobj
                                                            ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                                1
                                                            ))
                                                                ->getQuery()
                                                                ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                            {
                                                                # code...
                                                                //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                $ansimagedes[] = $getimagevalueval->image_name;

                                                            }

                                                            $getanswer = 'Selected the Wrong answer : ';

                                                        }

                                                    }

                                                    $data2['qu_answer'] = $getAnsewer;
                                                    $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                                    $worngcount += 1;
                            $data2['ansper'] = 0;

                                                }

                                                else
                                                {
                                                    $getanswer = 'Colored out side the lines';
                                                    $showtitle = 'What your child colored';
                                                    $worngcount += 1;
                                                    $data2['ansper'] = 0;
                                                    $data2['colorshow'] = true;
                                                }
                                            }
                                            else if (strpos($testcontent, 'did you child') !== false && $questionvalue->game_type != 3) 
                                            {
                                                $getanswer = 'No';
                                                $worngcount += 1;
                                            }
                                            else if (strpos($testcontent, 'emotional state') !== false)
                                            {
                                                $getanswer = 'Sad';
                                                $showtitle = $questionvalue->question;
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['emotional'] = true;
                                                $worngcount += 1;
                                            }
                                            else if (strpos($testcontent, 'trace') !== false)
                                            {
                                                $getanswer = 'Traced on out side the lines';
                                                $showtitle = 'What your child traced on the dotted line';
                                                $worngcount += 1;
                                                $data2['ansper'] = 0;
                                                $data2['colorshow'] = true;
                                                $data2['tracegame'] = true;

                                            }
                                            else if (strpos($testcontent, 'draw your own') !== false)
                                            {
                                                $getanswer = 'Traced on out side the lines';
                                                $worngcount += 1;
                                                $showtitle = 'Actual Work Created By Your Child';
                                                $data2['ansper'] = false;
                                                $data2['colorshow'] = false;
                                                $data2['tracegame'] = false;
                                            }
                                            else
                                            {
                                                if ($questionvalue->game_type == 3)
                                                {
                                                    $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                    if ($kidgender->gender == "female")
                                                    {

                                                         $str_arr = explode(",", $value6->object_name);

                                                          foreach ($str_arr as $imgobj)
                                                            {

                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.tina", array(
                                                            1
                                                        ))
                                                            ->getQuery()
                                                            ->execute();

                                                             foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                        }

                                                      


                                                    }
                                                    else
                                                    {
                                                         $str_arr = explode(",", $value6->object_name);

                                                          foreach ($str_arr as $imgobj)
                                                            {
                                                        $getimagevalue = $this
                                                            ->modelsManager
                                                            ->createBuilder()
                                                            ->columns(array(
                                                            'GameQuestionImageMaster.image_name',
                                                        ))
                                                            ->from('GameQuestionImageMaster')
                                                            ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                            $input_data->game_id
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.object_name", array(
                                                            $imgobj
                                                        ))
                                                            ->inwhere("GameQuestionImageMaster.rahul", array(
                                                            1
                                                        ))

                                                            ->getQuery()
                                                            ->execute();

                                                            foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }



                                                        }

                                                    }



                                                    
                                                       

                                                        $getanswer = 'Selected the Worng answer : ' . implode(", ", $ansimagedes);

                                                        $worngcount += 1;
                                                        $data2['emotional'] = false;
                            $data2['ansper'] = 0;
                                                    



                                                }
                                                else
                                                {
                                                    //guna
                                                    

                                                    if ($questionvalue->game_type == 1 && $questionvalue->game_type_value >= 2)
                                                    {

                                                        $kidgender = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);

                                                        if ($kidgender->gender == "female")
                                                        {
                                                            $str_arr = explode(",", $value6->object_name);

                                                            foreach ($str_arr as $imgobj)
                                                            {

                                                                $getimagevalue = $this
                                                                    ->modelsManager
                                                                    ->createBuilder()
                                                                    ->columns(array(
                                                                    'GameQuestionImageMaster.image_name',
                                                                ))
                                                                    ->from('GameQuestionImageMaster')
                                                                    ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                    $input_data->game_id
                                                                ))
                                                                    ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                    $imgobj
                                                                ))->inwhere("GameQuestionImageMaster.tina", array(
                                                                    1
                                                                ))
                                                                    ->getQuery()
                                                                    ->execute();

                                                                foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                                $getanswer = 'Selected the Wrong answer : ';
                                                            }

                                                        }
                                                        else
                                                        {
                                                            $wrongcountimg += 0;

                                                            $str_arr = explode(",", $value6->object_name);
                                                            foreach ($str_arr as $imgobj)
                                                            {
                                                                $getimagevalue = $this
                                                                    ->modelsManager
                                                                    ->createBuilder()
                                                                    ->columns(array(
                                                                    'GameQuestionImageMaster.image_name',
                                                                ))
                                                                    ->from('GameQuestionImageMaster')
                                                                    ->inwhere("GameQuestionImageMaster.ref_game_id", array(
                                                                    $input_data->game_id
                                                                ))
                                                                    ->inwhere("GameQuestionImageMaster.object_name", array(
                                                                    $imgobj
                                                                ))->inwhere("GameQuestionImageMaster.rahul", array(
                                                                    1
                                                                ))
                                                                    ->getQuery()
                                                                    ->execute();

                                                                foreach ($getimagevalue as $getimagevalueval)
                                                                {
                                                                    # code...
                                                                    //$ansvalue['objname']=$getimagevalueval -> image_name;
                                                                    $ansimagedes[] = $getimagevalueval->image_name;

                                                                }

                                                                $getanswer = 'Selected the Wrong answer : ';
                                                            }

                                                        }

                                                        $data2['qu_answer'] = $getAnsewer;
                                                        $data2['qa_imgdes'] = implode(", ", $ansimagedes);

                                                        $ansimagedes = [];
                                                        $data2['ansper'] = 0;

                                                        $worngcount += 1;

                                                    }
                                                    else
                                                    {
                                                        $data2['ansper'] = 0;
                                                        $getanswer = 'Selected the Worng answer';
                                                        $worngcount += 1;
                                                    }
                                                }

                                            }
                                        }

                                    }
                                }

                                $data2['question'] = $questionvalue->question;
                                $data2['qu_answer'] = $getanswer;
                                $data2['showtitle'] = $showtitle;
                                $data2['questions_no'] = $value6->questions_no;
                                $data2['time'] = $value6->time;

                                $getGameAnswerValueArray[] = $data2;

                                $ansimagedes = [];
                            }
                        }
                        else
                        {
                            $data2['question'] = $questionvalue->question;
                            $data2['qu_answer'] = 'Not Answered';
                            $data2['time'] = '';
                            $worngcount += 1;
                        }

                        // $game_data['question_valye'] = $data2;
                        $data2['qa_imgdes'] = [];
                        $data2['tracegame'] = false;

                    }
                    else
                    {
                        $game_data['question_valye'] = '';
                    }

                    $game_data['multiclick'] = $multiclick;
                    $game_data['question_valye'] = $getGameAnswerValueArray;

                    $anspersentage = ($wrongcountimg / (count($game_answer) * 100)) * 100;
                    $game_data['anerperval'] = $anspersentage;

                    $gamedetailsarray[] = $game_data;
                    $game_data['anerperval'] = 0;
                    $wrongcountimg = 0;
                }
                $checkanswer = ($answercount + $worngcount);
                if ($questioncount == $checkanswer)
                {
                    $game_data2['questioncount'] = $questioncount;
                    $game_data2['answer'] = $answercount;
                    $game_data2['worngcount'] = $worngcount;
                }
                $game_data2['Primary_Tagging'] = $value->standard_name;
                $game_data2['over_all_time'] = $time;
                $game_data2['slide_info'] = $gamedetailsarray;
                $game_data2['game_id'] = $value->game_ids;
                $game_data2['game_time'] = $time;
                $game_data2['game_name'] = $value->games_name;
                $game_data2['frameworkname'] = $value->frameworkname;
                $game_data2['audiogame'] = $audiogame;
                $game_data2['multislide'] = $gamemultislide;

                $gamedata_array[] = $game_data2;

                $data2['qa_imgdes'] = "";
            }

            /*return $this
                ->response
                ->setJsonContent(['rc' => $answercount, 'wc' => $worngcount,'qc' => $questioncount, 'ca' => $checkanswer]);*/

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $gamedata_array, 'daily_tips' => $value->daily_tips]);
        }
    }

}

