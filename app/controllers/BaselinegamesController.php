<?php
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Aws\Credentials\CredentialProvider;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
require BASE_PATH . '/vendor/autoload.php';
//require 'IP2Location.php';
class BaselinegamesController extends \Phalcon\Mvc\Controller
{
    public function index()
    {
    }

    /*
    viewall : is used to view  all the BaseLineGames
    Table : baseline_games
    Input : none
    
    */

    public function viewall()
    {

        $gamesCount = BaselineGames::find();
        if ($gamesCount):
            return Json_encode($gamesCount);
        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faild']);
        endif;
    }

    /*
    viewallwithfilter : is used to view the BaseLineGames with grade_id,framework_id and subject_id filltering.
    
    Table : baseline_games,core_frameworks,Grade and Subject
    Input : grade_id,framework_id,subject_id
    
    */

    public function viewallwithfilter()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as name',
            'Subject.subject_name as subject_name',
        ))
            ->from('BaselineGames')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->inwhere("BaselineGames.grade_id", array(
            $input_data->grade_id
        ))
            ->inwhere("BaselineGames.framework_id", array(
            $input_data->framework_id
        ))
            ->inwhere("BaselineGames.subject_id", array(
            $input_data->subject_id
        ))
            ->getQuery()
            ->execute();

        if ($gamesCount):
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $gamesCount]);
        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    /*
    viewallquestion : is used to view the all base line question based on game id.
    Table : BaselineGamesQuestion
    Input : id -> baseline_games_id
    
    */

    public function viewallquestion()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $question = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGamesQuestion.id',
            'BaselineGamesQuestion.baseline_games_id',
            'BaselineGamesQuestion.question',
            'BaselineGamesQuestion.description',
            'BaselineGamesQuestion.question_type',
            'BaselineGamesQuestion.question_order_id',
            'BaselineGamesQuestion.create_at',

        ))
            ->from('BaselineGamesQuestion')
            ->inwhere("BaselineGamesQuestion.baseline_games_id", array(
            $input_data->id
        ))
            ->getQuery()
            ->execute();
        if ($question):

            return Json_encode($question);
        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    /*
    viewallquestionfilterbyid : is used to view the all base line question based on Question id.
    Table : BaselineGames,BaselineGamesQuestion
    Input : id -> baseline_games_id
    
    */

    public function viewallquestionfilterbyid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $games = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
        ))
            ->from('BaselineGames')
            ->inwhere("BaselineGames.id", array(
            $input_data->id
        ))
            ->getQuery()
            ->execute();
        $gamesarray = array();
        if (count($games) > 0)
        {
            foreach ($games as $gamesvalue)
            {
                $gamesInfo = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineGames.id',
                    'BaselineGames.game_name',
                    'BaselineGames.game_path',
                    'BaselineGames.created_at',
                ))
                    ->from('BaselineGames')
                    ->inwhere("BaselineGames.id", array(
                    $input_data->id
                ))
                    ->getQuery()
                    ->execute();
                $gameinfoarray = array();
                if (count($gamesInfo) > 0)
                {
                    foreach ($gamesInfo as $gamesInfovalue)
                    {
                        $questioninfo = $this
                            ->modelsManager
                            ->createBuilder()
                            ->columns(array(
                            'BaselineGamesQuestion.id as question_id',
                            'BaselineGamesQuestion.baseline_games_id',
                            'BaselineGamesQuestion.question as questionname',
                            'BaselineGamesQuestion.answer_value',
                            'BaselineGamesQuestion.description',
                            'BaselineGamesQuestion.question_type',
                            'BaselineGamesQuestion.question_order_id',
                            'BaselineGamesQuestion.create_at as creat'
                        ))
                            ->from('BaselineGames')
                            ->Leftjoin('BaselineGamesQuestion', 'BaselineGamesQuestion.baseline_games_id=BaselineGames.id')
                            ->inwhere("BaselineGames.id", array(
                            $input_data->id
                        ))
                            ->getQuery()
                            ->execute();
                        $questioninfoarray = array();
                        if (count($questioninfo) > 0)
                        {
                            foreach ($questioninfo as $questioninfovalue)
                            {
                                $question['id'] = $questioninfovalue->question_id;
                                $question['baseline_games_id'] = $questioninfovalue->baseline_games_id;
                                $question['question'] = $questioninfovalue->questionname;
                                $question['answer_value'] = $questioninfovalue->answer_value;
                                $question['description'] = $questioninfovalue->description;
                                $question['question_type'] = $questioninfovalue->question_type;
                                $question['question_order_id'] = $questioninfovalue->question_order_id;
                                $questioninfoarray[] = $question;
                            }
                        }
                        else
                        {
                            $question['id'] = '';
                            $question['baseline_games_id'] = '';
                            $question['question'] = '';
                            $question['answer_value'] = '';
                            $question['description'] = '';
                            $question['question_type'] = '';
                            $question['question_order_id'] = '';
                            $questioninfoarray[] = $question;
                        }
                        $gameinfodata['id'] = $gamesInfovalue->id;
                        $gameinfodata['game_name'] = $gamesInfovalue->game_name;
                        $gameinfodata['game_path'] = $gamesInfovalue->game_path;
                        $gameinfodata['questionInfo'] = $questioninfoarray;
                        $gameinfoarray[] = $gameinfodata;
                    }
                }
                else
                {
                    $gameinfodata['id'] = '';
                    $gameinfodata['game_name'] = '';
                    $gameinfodata['game_path'] = '';
                    $gameinfodata['questionInfo'] = '';
                    $gameinfoarray[] = $gameinfodata;
                }
                $data['grade_id'] = $gamesvalue->grade_id;
                $data['framework_id'] = $gamesvalue->framework_id;
                $data['subject_id'] = $gamesvalue->subject_id;
                $data['GameInfo'] = $gameinfoarray;
                $gamesarray[] = $data;
            }
        }
        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => $gamesarray, ]);
    }

    /*
    create : is used to save the baseline Question .
    Table : BaselineGames,BaselineGamesQuestion
    Input : GameInfo -> Array
    
    */

    /**
     * This function using to create Grade information
     */
    public function create()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        /* $headers = $this->request->getHeaders ();
        if (empty ( $headers ['Token'] )) {
        return $this->response->setJsonContent ( [
        "status" => false,
        "message" => "Please give the token"
        ] );
        } */
        /**
         * This object using valitaion
         */

        foreach ($input_data->GameInfo as $gameinfo)
        {

            $collection = BaselineGames::findFirstByid($gameinfo->id);
            if (!$collection)
            {
                $collection = new BaselineGames();
            }
            $collection->grade_id = $input_data->grade_id;
            $collection->framework_id = $input_data->framework_id;
            $collection->subject_id = $input_data->subject_id;
            $collection->game_name = $gameinfo->game_name;
            $collection->game_path = $gameinfo->game_path;
            if (!$collection->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'This game info not saved', "data" => $collection]);
            }
            else
            {
                $check = 0;
                foreach ($gameinfo->questionInfo as $questioninfo)
                {
                    $quesiondata = BaselineGamesQuestion::findFirstByid($questioninfo->id);
                    if (!$quesiondata)
                    {
                        $quesiondata = new BaselineGamesQuestion();
                    }
                    $quesiondata->baseline_games_id = $collection->id;
                    $quesiondata->question = $questioninfo->question;
                    $quesiondata->answer_value = $questioninfo->answer_value;
                    $quesiondata->description = $questioninfo->description;
                    $quesiondata->question_type = $questioninfo->question_type;
                    $quesiondata->question_order_id = $check + 1;
                    if (!$quesiondata->save())
                    {
                        return $this
                            ->response
                            ->setJsonContent(['status' => false, 'message' => 'This game question info not saved', "data" => $quesiondata]);
                    }
                    $check++;
                }
            }
        }
        return $this
            ->response
            ->setJsonContent(['status' => true, 'message' => 'Game save successfully', ]);
    }

    /*
    create : is used to save the baseline Question .
    Table : BaselineGames,BaselineGamesQuestion
    Input : GameInfo -> Array
    
    */

    public function viewallbaselineimgdiscription()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $baselineImageDescription = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGamesImageDescription.id',
            'BaselineGamesImageDescription.game_id',
            'BaselineGamesImageDescription.object_name',
            'BaselineGamesImageDescription.description',
            'BaselineGamesImageDescription.create_at'
        ))
            ->from('BaselineGamesImageDescription')

            ->inwhere("BaselineGamesImageDescription.game_id", array(
            $input_data->id
        ))
            ->inwhere("BaselineGamesImageDescription.object_name", array(
            $input_data->file
        ))

            ->getQuery()
            ->execute();

        if ($baselineImageDescription):

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $baselineImageDescription]);
        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faild']);
        endif;
    }

    /*
    createbaselineimgdiscription : is used to save the BaselineGamesImageDescription .
    Table : BaselineGamesImageDescription
    Input : gameid,file,object_des
    
    */

    public function createbaselineimgdiscription()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        foreach ($input_data->gameimageinfo as $gameimageinfo)
        {
            $collection = BaselineGamesImageDescription::findFirstByid($input_data->id);

            if (!$collection)
            {
                $collection = new BaselineGamesImageDescription();
            }
            $collection->game_id = $input_data->game_id;
            $collection->object_name = $gameimageinfo->file;
            $collection->description = $gameimageinfo->object_des;
            if (!$collection->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'This game question info not saved', "data" => $collection]);
            }
        }

        return $this
            ->response
            ->setJsonContent(['status' => true, 'message' => 'Game Image Description saved successfully', ]);

    }

    /*
    viewbaselinegamesfordb : is used to view the BaselineGames in Dashboard .
    Table : BaselineGames
    Input : grade_id
    
    */

    public function viewbaselinegamesfordb()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $game_data_val = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',

        ))
            ->from('BaselineGames')
            ->inwhere("BaselineGames.grade_id", array(
            $input_data->grade_id
        ))
            ->getQuery()
            ->execute();

        if ($game_data_val):
            $gamearray = array();
            $gamecolor = GameColors::findFirstByday(date('l'));
            $games['background_image'] = $gamecolor->background_color;
            $games['gif'] = $gamecolor->gif;
            $games['img'] = $gamecolor->img;

            $games['gender'] = $input_data->gender;

            foreach ($game_data_val as $gamesCount)
            {

                $game_val['games_id'] = $gamesCount->id;
                $game_val['grade_id'] = $gamesCount->grade_id;
                $game_val['framework_id'] = $gamesCount->framework_id;
                $game_val['subject_id'] = $gamesCount->subject_id;
                $game_val['game_name'] = $gamesCount->game_name;
                $game_val['games_folder'] = $gamesCount->game_path;
                $game_val['created_at'] = $gamesCount->created_at;
                $gamearray[] = $game_val;
            }
            $chunked_array = array_chunk($gamearray, 4);
            array_replace($chunked_array, $chunked_array);
            $keyed_array = array();

            foreach ($chunked_array as $chunked_arrays)
            {
                $keyed_array[]['page'] = $chunked_arrays;
            }
            $games['games'] = $keyed_array;
            return Json_encode($games);
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $games]);

        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    /* Get baseline game based on child and top split the game based on freamework */

    public function viewallbaselinesubjectbychildid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as core_framework_name',
            'Subject.id as subject_id',
            'Subject.subject_name as subject_name',
        ))
            ->from('BaselineGames')
            ->leftjoin('NidaraKidProfile', 'BaselineGames.grade_id = NidaraKidProfile.grade')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->inwhere("NidaraKidProfile.id", array(
            $input_data->child_id
        ))
            ->getQuery()
            ->execute();
        foreach ($gamesCount as $core_data)
        {
            $getgameid = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'BaselineGamesStatus.id',
            ))
                ->from('BaselineGamesStatus')
                ->inwhere("BaselineGamesStatus.game_id", array(
                $core_data->id
            ))
                ->inwhere("BaselineGamesStatus.kid_id", array(
                $input_data->child_id
            ))
                ->getQuery()
                ->execute();
            if (count($getgameid) > 0)
            {
                $core_data->show = false;
                $core_data->daily_tips = 'Game is Completed';
                $core_data->grade_color = '#008000';
            }
            else
            {
                $core_data->show = true;
                $core_data->daily_tips = 'Game not Completed';
            }
            $core_framework_name = strtolower(str_replace(' ', '_', $core_data->core_framework_name));
            $core_array[] = $core_data->core_framework_name;
            $core_frm_array[$core_framework_name][] = $core_data;
        }

        $core_frame = CoreFrameworks::find();
        foreach ($core_frame as $core)
        {
            if (!in_array($core->name, $core_array))
            {
                $core->name = strtolower(str_replace(' ', '_', $core->name));
                $core_frm_array[$core->name] = array();
            }
        }
        $kid = NidaraKidProfile::findFirstByid($input_data->child_id);
        if (!empty($kid))
        {
            $core_frm_array['kid_name'] = $kid->first_name;
            $core_frm_array['child_photo'] = $kid->child_photo;
        }
        $core_frm_array['today_date'] = date('l, F d, Y');
        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => $core_frm_array]);

    }

    /*
    viewallbaselinesubject : is used to view the viewallbaselinesubject in Dashboard .
    Table : BaselineGames,Grade,CoreFrameworks,Subject,NidaraKidProfile
    Input : child_id
    
    */

    public function viewallbaselinesubject()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        $freetrial = NcProductFreetrail::findFirstBykid_id($input_data->child_id);
        if (!$freetrial)
        {
            $gamesCount = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                //'BaselineGames.id',
                'BaselineGames.grade_id',
                'BaselineGames.framework_id',
                'BaselineGames.subject_id as game_subject_id',
                //'BaselineGames.game_name',
                //'BaselineGames.game_path',
                //'BaselineGames.created_at',
                'Grade.grade_name as grade_name',
                'CoreFrameworks.name as name',
                'Subject.id as subject_id',
                'Subject.subject_name as subject_name',
                'Subject.description',
                'Subject.status',
                'Subject.created_at',
                'Subject.created_by',
                'Subject.modified_at',
            ))
                ->from('BaselineGames')
                ->leftjoin('NidaraKidProfile', 'BaselineGames.grade_id = NidaraKidProfile.grade')
                ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
                ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
                ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
                ->inwhere("NidaraKidProfile.id", array(
                $input_data->child_id
            ))
                ->groupby("Subject.id")

                ->getQuery()
                ->execute();

            $responce = array();

            foreach ($gamesCount as $values)
            {
                # code...
                // $showingstatus=true;
                $getgameid = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineGames.id',
                ))
                    ->from('BaselineGames')
                    ->leftjoin('BaselineGamesStatus', 'BaselineGamesStatus.game_id = BaselineGames.id')
                    ->inwhere("BaselineGames.subject_id", array(
                    $values->subject_id
                ))
                    ->inwhere("BaselineGamesStatus.kid_id", array(
                    $input_data->child_id
                ))
                    ->inwhere("BaselineGamesStatus.create_date", array(
                    date('Y-m-d')
                ))
                    ->getQuery()
                    ->execute();

                $totalbasegame = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineGames.id',

                ))
                    ->from('BaselineGames')
                    ->leftjoin('NidaraKidProfile', 'BaselineGames.grade_id = NidaraKidProfile.grade')
                    ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
                    ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
                    ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
                    ->inwhere("BaselineGames.subject_id", array(
                    $values->subject_id
                ))
                    ->inwhere("BaselineGames.grade_id", array(
                    $values->grade_id
                ))
                    ->groupby("BaselineGames.id")
                    ->getQuery()
                    ->execute();

                if (count($getgameid) == count($totalbasegame))
                {
                    $showingstatus = false;
                }
                else
                {
                    $showingstatus = true;
                }

                $datavalue['grade_id'] = $values->grade_id;
                $datavalue['framework_id'] = $values->framework_id;
                $datavalue['grade_name'] = $values->grade_name;
                $datavalue['name'] = $values->name;
                $datavalue['game_subject_id'] = $values->game_subject_id;
                $datavalue['subject_id'] = $values->subject_id;
                $datavalue['subject_name'] = $values->subject_name;
                $datavalue['description'] = $values->description;
                $datavalue['status'] = $values->status;
                $datavalue['created_at'] = $values->created_at;
                $datavalue['created_by'] = $values->created_by;
                $datavalue['modified_at'] = $values->modified_at;
                $datavalue['show'] = $showingstatus;

                $responce[] = $datavalue;
            }

            if ($gamesCount):
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $responce]);
            else:
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'Message' => 'Faield']);
            endif;
        }
        else
        {
            $gamesCount = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                //'BaselineGames.id',
                'BaselineFreetrialGames.grade_id',
                'BaselineFreetrialGames.framework_id',
                'BaselineFreetrialGames.subject_id as game_subject_id',
                //'BaselineGames.game_name',
                //'BaselineGames.game_path',
                //'BaselineGames.created_at',
                'Grade.grade_name as grade_name',
                'CoreFrameworks.name as name',
                'Subject.id as subject_id',
                'Subject.subject_name as subject_name',
                'Subject.description',
                'Subject.status',
                'Subject.created_at',
                'Subject.created_by',
                'Subject.modified_at',
            ))
                ->from('BaselineFreetrialGames')
                ->leftjoin('NidaraKidProfile', 'BaselineFreetrialGames.grade_id = NidaraKidProfile.grade')
                ->leftjoin('CoreFrameworks', 'BaselineFreetrialGames.framework_id = CoreFrameworks.id')
                ->leftjoin('Grade', 'BaselineFreetrialGames.grade_id = Grade.id')
                ->leftjoin('Subject', 'BaselineFreetrialGames.subject_id = Subject.id')
                ->inwhere("NidaraKidProfile.id", array(
                $input_data->child_id
            ))
                ->groupby("Subject.id")

                ->getQuery()
                ->execute();

            $responce = array();

            foreach ($gamesCount as $values)
            {
                # code...
                

                $getgameid = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineFreetrialGames.id',
                ))
                    ->from('BaselineFreetrialGames')
                    ->leftjoin('BaselineGamesStatus', 'BaselineGamesStatus.game_id = BaselineFreetrialGames.id')
                    ->inwhere("BaselineFreetrialGames.subject_id", array(
                    $values->subject_id
                ))
                    ->inwhere("BaselineGamesStatus.kid_id", array(
                    $input_data->child_id
                ))
                    ->inwhere("BaselineGamesStatus.create_date", array(
                    date('Y-m-d')
                ))
                    ->getQuery()
                    ->execute();

                $totalbasegame = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineFreetrialGames.id',

                ))
                    ->from('BaselineFreetrialGames')
                    ->leftjoin('NidaraKidProfile', 'BaselineFreetrialGames.grade_id = NidaraKidProfile.grade')
                    ->leftjoin('CoreFrameworks', 'BaselineFreetrialGames.framework_id = CoreFrameworks.id')
                    ->leftjoin('Grade', 'BaselineFreetrialGames.grade_id = Grade.id')
                    ->leftjoin('Subject', 'BaselineFreetrialGames.subject_id = Subject.id')
                    ->inwhere("BaselineFreetrialGames.subject_id", array(
                    $values->subject_id
                ))
                    ->inwhere("BaselineFreetrialGames.grade_id", array(
                    $values->grade_id
                ))
                    ->groupby("BaselineFreetrialGames.id")
                    ->getQuery()
                    ->execute();

                if (count($getgameid) == count($totalbasegame))
                {
                    $showingstatus = false;
                }
                else
                {
                    $showingstatus = true;
                }

                $datavalue['grade_id'] = $values->grade_id;
                $datavalue['framework_id'] = $values->framework_id;
                $datavalue['grade_name'] = $values->grade_name;
                $datavalue['name'] = $values->name;
                $datavalue['game_subject_id'] = $values->game_subject_id;
                $datavalue['subject_id'] = $values->subject_id;
                $datavalue['subject_name'] = $values->subject_name;
                $datavalue['description'] = $values->description;
                $datavalue['status'] = $values->status;
                $datavalue['created_at'] = $values->created_at;
                $datavalue['created_by'] = $values->created_by;
                $datavalue['modified_at'] = $values->modified_at;
                $datavalue['show'] = $showingstatus;

                $responce[] = $datavalue;
            }

            if ($gamesCount):
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $responce]);
            else:
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'Message' => 'Faield']);
            endif;

        }
    }

    /*
    viewallbaselinesubject : is used to view the viewallbaselinesubject in Dashboard .
    Table : BaselineGames,Grade,CoreFrameworks,Subject,NidaraKidProfile
    Input : child_id
    
    */

    public function viewallwithgradesub()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();
        $freetrial = NcProductFreetrail::findFirstBykid_id($input_data->nidara_kid_profile_id);
        if (!$freetrial)
        {
            $game_data_val = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'BaselineGames.id',
                'BaselineGames.grade_id',
                'BaselineGames.framework_id',
                'BaselineGames.subject_id',
                'BaselineGames.game_name',
                'BaselineGames.game_path',
                'BaselineGames.created_at',

            ))
                ->from('BaselineGames')
                ->inwhere("BaselineGames.grade_id", array(
                $input_data->grade_id
            ))
                ->inwhere("BaselineGames.subject_id", array(
                $input_data->subject_id
            ))
                ->getQuery()
                ->execute();
        }
        else
        {
            $game_data_val = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'BaselineFreetrialGames.id',
                'BaselineFreetrialGames.grade_id',
                'BaselineFreetrialGames.framework_id',
                'BaselineFreetrialGames.subject_id',
                'BaselineFreetrialGames.game_name',
                'BaselineFreetrialGames.game_path',
                'BaselineFreetrialGames.created_at',

            ))
                ->from('BaselineFreetrialGames')
                ->inwhere("BaselineFreetrialGames.grade_id", array(
                $input_data->grade_id
            ))
                ->inwhere("BaselineFreetrialGames.subject_id", array(
                $input_data->subject_id
            ))
                ->getQuery()
                ->execute();
        }

        if ($game_data_val):
            $gamearray = array();
            $gamecolor = GameColors::findFirstByday(date('l'));
            $games['background_image'] = $gamecolor->background_color;
            $games['gif'] = $gamecolor->gif;
            $games['img'] = $gamecolor->img;

            $games['gender'] = $input_data->gender;

            foreach ($game_data_val as $gamesCount)
            {

                $getgameid = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineGamesStatus.id',
                ))
                    ->from('BaselineGamesStatus')
                    ->inwhere("BaselineGamesStatus.game_id", array(
                    $gamesCount->id
                ))
                    ->inwhere("BaselineGamesStatus.kid_id", array(
                    $input_data->nidara_kid_profile_id
                ))

                    ->getQuery()
                    ->execute();

                $game_val['games_id'] = $gamesCount->id;
                $game_val['grade_id'] = $gamesCount->grade_id;
                $game_val['framework_id'] = $gamesCount->framework_id;
                $game_val['subject_id'] = $gamesCount->subject_id;
                $game_val['game_name'] = $gamesCount->game_name;
                $game_val['games_folder'] = $gamesCount->game_path;
                $game_val['created_at'] = $gamesCount->created_at;

                if (count($getgameid) > 0)
                {
                    $game_val['show'] = false;

                }
                else
                {
                    $game_val['show'] = true;

                }
                $gamearray[] = $game_val;
            }
            $chunked_array = array_chunk($gamearray, 4);
            array_replace($chunked_array, $chunked_array);
            $keyed_array = array();

            foreach ($chunked_array as $chunked_arrays)
            {
                $keyed_array[]['page'] = $chunked_arrays;
            }
            $games['games'] = $keyed_array;
            return Json_encode($games);
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $games]);

        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    public function baselinestatussave()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $baselinegamestatus = new BaselineGamesStatus();

        $baselinegamestatus->kid_id = $input_data->nidara_kid_profile_id;
        $baselinegamestatus->game_id = $input_data->game_id;
        $baselinegamestatus->status = 1;
        $baselinegamestatus->create_date = date('Y-m-d');

        if ($baselinegamestatus->save())
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'Message' => 'Data Saved']);
        }
        else
        {

            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Data Not Saved']);

        }

    }

    public function baselineanswersave()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $baselineanswer = new BaselineGamesAnswer();

        $baselineanswer->kid_id = $input_data->nidara_kid_profile_id;
        $baselineanswer->game_id = $input_data->game_id;
        $baselineanswer->question_id = $input_data->question_id;

        if (empty($input_data->object_val))
        {
            if ($input_data->slide_type == "last")
            {
                $baselineanswer->answer = "No data to be collected";
            }
            else
            {
                $baselineanswer->answer = $input_data->options;
            }
        }
        else
        {
            $baselineanswer->answer = $input_data->object_val;
        }

        $baselineanswer->timeval = $input_data->time;
        $baselineanswer->replaycount = $input_data->replaycount;
        $baselineanswer->slide_no = $input_data->slide_no;
        $baselineanswer->slide_type = $input_data->slide_type;

        $baselineanswer->created_date = date('Y-m-d');
        if ($baselineanswer->save())
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'Message' => 'Saved']);
        }

    }

    public function viewreport()
    {
        $baseurl = $this
            ->config->colorurl;

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $answersval = array();

        $overalltime = 0;

        $getbaselinegameanswer = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGamesAnswer.id',
            'BaselineGamesAnswer.answer',
            'BaselineGamesAnswer.game_id',
            'BaselineGamesAnswer.replaycount',
            'BaselineGamesAnswer.slide_no',
            'BaselineGamesAnswer.slide_type',
            'BaselineGamesAnswer.created_date',
            'BaselineGamesAnswer.timeval',
            'BaselineGamesAnswer.question_id'
        ))
            ->from('BaselineGamesAnswer')
            ->inwhere("BaselineGamesAnswer.game_id", array(
            $input_data->game_id
        ))
            ->inwhere("BaselineGamesAnswer.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
            ->getQuery()
            ->execute();

            if(count($getbaselinegameanswer)>0)
            {
        foreach ($getbaselinegameanswer as $value)
        {
            $colorval = array();

            if ($value->question_id == 0)
            {

                $baselinegame = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineGames.game_name',
                ))
                    ->from('BaselineGames')
                    ->inwhere("BaselineGames.id", array(
                    $input_data->game_id
                ))
                    ->getQuery()
                    ->execute();

                $answers['question_id'] = $value->id;
                $answers['question'] = '';
                $answers['question_order_id'] = $value->question_id;
                $answers['game_id'] = $value->game_id;
                foreach ($baselinegame as $baselinegameval)
                {
                    $answers['game_name'] = $baselinegameval->game_name;
                }

                $answers['replaycount'] = $value->replaycount;
                $answers['slide_no'] = $value->slide_no;
                $answers['slide_type'] = $value->slide_type;
                $answers['created_date'] = $value->created_date;
                $answers['timeval'] = $value->timeval;

                if ($value->slide_type == "last")
                {
                    $answers['answer'] = $value->answer;
                    $answers['audiocount'] = null;
                    $answers['audiogame'] = false;
                    $answers['selectcheck'] = null;
                    $answers['selectgame'] = null;

                }
                else
                {
                    $answers['answer'] = '';
                    $answers['selectcheck'] = null;
                    $answers['selectgame'] = null;

                }
                $overalltime = $overalltime + (int)$value->timeval;
                $answersval[] = $answers;

            }

            $getbaselinegamegames = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'BaselineGamesQuestion.id',
                'BaselineGamesQuestion.question_order_id',
                'BaselineGamesQuestion.question',
                'BaselineGames.game_name',

            ))
                ->from('BaselineGamesQuestion')
                ->leftjoin('BaselineGames', 'BaselineGames.id = BaselineGamesQuestion.baseline_games_id')

                ->inwhere("BaselineGamesQuestion.baseline_games_id", array(
                $input_data->game_id
            ))
                ->inwhere("BaselineGamesQuestion.question_order_id", array(
                $value->question_id
            ))

                ->getQuery()
                ->execute();

            foreach ($getbaselinegamegames as $datas)
            {

                $answers['question_id'] = $value->id;
                $answers['question'] = $datas->question;
                $answers['question_order_id'] = $datas->question_order_id;
                $answers['game_id'] = $value->game_id;
                $answers['game_name'] = $datas->game_name;
                $answers['replaycount'] = $value->replaycount;
                $answers['slide_no'] = $value->slide_no;
                $answers['slide_type'] = $value->slide_type;
                $answers['created_date'] = $value->created_date;
                $answers['timeval'] = $value->timeval;

                $overalltime = $overalltime + (int)$value->timeval;

                if (strlen($value->answer) > 5)
                {
                    if (substr($value->answer, strlen($value->answer) - 3, strlen($value->answer)) == 'png')
                    {
                        if (substr($value->answer, 0, 1) == "/")
                        {

                            $getcolorvalue = $this
                                ->modelsManager
                                ->createBuilder()
                                ->columns(array(
                                'BaselineGamesAnswersColor.click_count',
                            ))
                                ->from('BaselineGamesAnswersColor')
                                ->inwhere("BaselineGamesAnswersColor.game_id", array(
                                $input_data->game_id
                            ))
                                ->inwhere("BaselineGamesAnswersColor.child_id", array(
                                $input_data->nidara_kid_profile_id
                            ))
                                ->inwhere("BaselineGamesAnswersColor.question_id", array(
                                $datas->question_order_id
                            ))
                                ->inwhere("BaselineGamesAnswersColor.slide_no", array(
                                $value->slide_no
                            ))

                                ->getQuery()
                                ->execute();

                            foreach ($getcolorvalue as $getcolorval)
                            {

                                $colorvalue['color'] = $getcolorval->click_count;
                                $colorval[] = $colorvalue;

                            }

                            $answers['clickcolor'] = $colorval;
                            $answers['answer'] = $baseurl . $value->answer;
                            $answers['colorgame'] = true;
                        }
                        else
                        {
                            $answers['clickcolor'] = null;
                            $answers['colorgame'] = false;
                            $answers['selectgame'] = false;

                            $getvalue = $this
                                ->modelsManager
                                ->createBuilder()
                                ->columns(array(
                                'BaselineGamesImageDescription.description',
                            ))
                                ->from('BaselineGamesImageDescription')
                                ->inwhere("BaselineGamesImageDescription.game_id", array(
                                $value->game_id
                            ))
                                ->inwhere("BaselineGamesImageDescription.object_name", array(
                                $value->answer
                            ))
                                ->getQuery()
                                ->execute();

                            foreach ($getvalue as $dataval)
                            {
                                # code...
                                $answers['answer'] = $dataval->description;
                            }


                            $getaudiovalue = $this
                                ->modelsManager
                                ->createBuilder()
                                ->columns(array(
                                'BaselineGamesAnswersAudioCount.click_count',
                            ))
                                ->from('BaselineGamesAnswersAudioCount')
                                ->inwhere("BaselineGamesAnswersAudioCount.game_id", array(
                                $input_data->game_id
                            ))
                                ->inwhere("BaselineGamesAnswersAudioCount.child_id", array(
                                $input_data->nidara_kid_profile_id
                            ))
                                ->inwhere("BaselineGamesAnswersAudioCount.question_id", array(
                                $datas->question_order_id
                            ))
                                ->inwhere("BaselineGamesAnswersAudioCount.slide_no", array(
                                $value->slide_no
                            ))

                                ->getQuery()
                                ->execute();

                            if (count($getaudiovalue) > 0)
                            {

                                $answers['audiocount'] = $getaudiovalue[0]->click_count;
                                $answers['audiogame'] = true;
                            }

                            
                        }

                    }
                    else
                    {

                        if (substr($value->answer, 0, 3) == "AUD")
                        {

                            $getaudiovalue = $this
                                ->modelsManager
                                ->createBuilder()
                                ->columns(array(
                                'BaselineGamesAnswersAudioCount.click_count',
                            ))
                                ->from('BaselineGamesAnswersAudioCount')
                                ->inwhere("BaselineGamesAnswersAudioCount.game_id", array(
                                $input_data->game_id
                            ))
                                ->inwhere("BaselineGamesAnswersAudioCount.child_id", array(
                                $input_data->nidara_kid_profile_id
                            ))
                                ->inwhere("BaselineGamesAnswersAudioCount.question_id", array(
                                $datas->question_order_id
                            ))
                                ->inwhere("BaselineGamesAnswersAudioCount.slide_no", array(
                                $value->slide_no
                            ))

                                ->getQuery()
                                ->execute();

                            if (count($getaudiovalue) > 0)
                            {

                                $answers['audiocount'] = $getaudiovalue[0]->click_count;
                                $answers['audiogame'] = true;
                            }
                            else
                            {
                                $answers['audiocount'] = null;
                                $answers['audiogame'] = false;

                            }

                            $answers['answer'] = substr($value->answer, 3, strlen($value->answer));
                        }
                        else if (substr($value->answer, 0, 6) == "SELECT")
                        {

                            $selectanswerval = substr($value->answer, strlen($value->answer) - 1, strlen($value->answer));

                            if ($selectanswerval == "2")
                            {
                                $answers['answer'] = $selectanswerval;
                                $answers['selectcheck'] = "Yes";

                            }
                            else
                            {
                                $answers['answer'] = $selectanswerval;
                                $answers['selectcheck'] = "No";

                            }
                            $answers['selectgame'] = true;

                        }
                        else
                        {
                            $answers['selectgame'] = false;

                            $answers['answer'] = $value->answer;
                        }

                    }
                }
                else
                {

                    $answers['answer'] = $value->answer;
                }

                $answersval[] = $answers;

            }

        }

        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => $answersval, 'overalltime' => $overalltime

        ]);
    }
    else
    {

        return $this
            ->response
            ->setJsonContent(['status' => false, 'data' => "Data Not Found"

        ]);

	}
    }

    public function viewallwithgradesubnosplit()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $game_data_val = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',

        ))
            ->from('BaselineGames')
            ->inwhere("BaselineGames.grade_id", array(
            $input_data->grade_id
        ))
            ->inwhere("BaselineGames.subject_id", array(
            $input_data->subject_id
        ))
            ->getQuery()
            ->execute();

        if ($game_data_val):
            $gamearray = array();
            $gamecolor = GameColors::findFirstByday(date('l'));
            $games['background_image'] = $gamecolor->background_color;
            $games['gif'] = $gamecolor->gif;
            $games['img'] = $gamecolor->img;

            $games['gender'] = $input_data->gender;

            foreach ($game_data_val as $gamesCount)
            {

                $getgameid = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'BaselineGamesStatus.id',
                ))
                    ->from('BaselineGamesStatus')
                    ->inwhere("BaselineGamesStatus.game_id", array(
                    $gamesCount->id
                ))
                    ->inwhere("BaselineGamesStatus.kid_id", array(
                    $input_data->nidara_kid_profile_id
                ))

                    ->getQuery()
                    ->execute();

                $game_val['games_id'] = $gamesCount->id;
                $game_val['grade_id'] = $gamesCount->grade_id;
                $game_val['framework_id'] = $gamesCount->framework_id;
                $game_val['subject_id'] = $gamesCount->subject_id;
                $game_val['game_name'] = $gamesCount->game_name;
                $game_val['games_folder'] = $gamesCount->game_path;
                $game_val['created_at'] = $gamesCount->created_at;

                if (count($getgameid) > 0)
                {
                    $game_val['show'] = false;

                }
                else
                {
                    $game_val['show'] = true;

                }
                $gamearray[] = $game_val;
            }

            $games['games'] = $gamearray;
            return Json_encode($games);
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $games]);

        else:
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

    public function shootgameanswerreport()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

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
            ->inwhere('GameAnswersShootgame.session_id ', array(
            $input_data->session_id
        ))
            ->inwhere('GameAnswersShootgame.child_id', array(
            $input_data->nidara_kid_profile_id
        ))
            ->inwhere('GameAnswersShootgame.game_id', array(
            $input_data->game_id
        ))
            ->orderby('GameAnswersShootgame.id')
            ->getQuery()
            ->execute();

        if (count($game_answer) > 0)
        {

            $totalcount = array();
            $countold = '00:00:00';
            $i = 0;
            foreach ($game_answer as $value)
            {

                if ($i == 0)
                {
                    $count['differentcount'] = "00";
                }
                else
                {
                    $count['differentcount'] = date('s', strtotime($value->time) - strtotime($countold));
                }

                $count['child_id'] = $value->child_id;
                $count['game_id'] = $value->game_id;
                $count['click_count'] = $value->click_count;
                $count['session_id'] = $value->session_id;
                $countold = $value->time;

                $i = i + 1;

                $totalcount[] = $count;
            }

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $totalcount]);

        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'Message' => "No Data Available"]);

        }

    }

    public function gamecolorsave()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameanswercolor = new BaselineGamesAnswersColor();

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

        $gameanswercolor = new BaselineGamesAnswersAudioCount();

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

}

