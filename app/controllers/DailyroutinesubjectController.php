<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH . '/vendor/Crypto.php';
require BASE_PATH . '/vendor/mailin.php';
require BASE_PATH . '/vendor/class.phpmailer.php';

require BASE_PATH . '/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class DailyroutinesubjectController extends \Phalcon\Mvc\Controller
{
            public function getdailygame_newbase(){
        $input_data = $this->request->getJsonRawBody ();
        $headers = $this->request->getHeaders ();
        if (!empty ( $headers ['Token'] )) {
            return $this->response->setJsonContent ( [
                    "status" => false,
                    "message" => "Please give the token"
            ] );
        }
        /*  */
        $day_id = isset ( $input_data->day_id ) ? $input_data->day_id : '';
        $grade_id = isset ( $input_data->grade_id ) ? $input_data->grade_id : '';
        $kidprofile = NidaraKidProfile::findFirstByid ( $input_data->kid_id );
        if($day_id <= 0){
            $day_id = 1;
        }
        else{
            $day_id += 1;
        }
        if(empty($grade_id)){
            return $this->response->setJsonContent ( [ 
                    'status' => false,
                    'message' => 'Please give the grade_id' 
            ] );
        }
        else {

            $ctime=date("H:i:s");



            $getsubject=$this->modelsManager->createBuilder ()->columns ( array (
                        'DailyRoutineSubject.subject_id',
                    ))->from("DailyRoutineSubject")
                    ->where("play_date='".date('Y-m-d')."' and '".$ctime."' between start_time and end_time")
                    ->getQuery()->execute ();

                if(count($getsubject)>0)
                {
                $subject_id=$getsubject[0]->subject_id;
                }
                else
                {
                    $subject_id=0;
                }

                $gamestatus=$this->modelsManager->createBuilder ()->columns ( array (
                        'KidsGamesStatus.game_id as kid_games_id'
                    
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->leftjoin('KidsGamesStatus','KidsGamesStatus.game_id = GamesDatabase.game_id')
                    ->where("GuidedLearningDayGameMap.day_id <'".$day_id."'")
                    ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    
                    ->inwhere("KidsGamesStatus.nidara_kid_profile_id",array($input_data->kid_id))
                    ->inwhere("KidsGamesStatus.current_status",array(1))
                   
                   ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();

                    if(count($gamestatus)>0)
                    {
                        $kidgameids=array();
                        foreach ($gamestatus as $key) 
                        {
                            # code...
                            $kidgameids[]=$key->kid_games_id;
                        }

                       /* return $this->response->setJsonContent ([ 
                            'status' => true,
                            'data' =>implode(",",$kidgameids)
                    ]);*/

                  if($input_data->gender == 'famale'){
                    $guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
                        'GamesDatabase.game_id as games_id',
                        'GamesDatabase.games_name as games_name',
                        'GamesDatabase.games_folder as games_folder',
                        'GamesDatabase.daily_tips as daily_tips'
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->where("GuidedLearningDayGameMap.day_id <'".$day_id."' and GamesDatabase.game_id NOT IN('".implode(",",$kidgameids)."')")
                    ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    ->inwhere("GamesDatabase.tina",array(1))
                    ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();
                }
                else{
                    $guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
                    'GamesDatabase.game_id as games_id',
                    'GamesDatabase.games_name as games_name',
                    'GamesDatabase.games_folder as games_folder',
                    'GamesDatabase.daily_tips as daily_tips'
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->where("GuidedLearningDayGameMap.day_id <'".$day_id."' and GamesDatabase.game_id NOT IN('".implode(",",$kidgameids)."')")                   
                     ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    ->inwhere("GamesDatabase.rahul",array(1))
                    ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();

                }
                if(count($guidedlearning_id)<=0)
                {

                    if($input_data->gender == 'famale'){
                    $guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
                        'GamesDatabase.game_id as games_id',
                        'GamesDatabase.games_name as games_name',
                        'GamesDatabase.games_folder as games_folder',
                        'GamesDatabase.daily_tips as daily_tips'
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    ->inwhere("GuidedLearningDayGameMap.subject_id",array($subject_id))
                    ->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
                    ->inwhere("GamesDatabase.tina",array(1))
                    ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();
                }
                else{
                    $guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
                    'GamesDatabase.game_id as games_id',
                    'GamesDatabase.games_name as games_name',
                    'GamesDatabase.games_folder as games_folder',
                    'GamesDatabase.daily_tips as daily_tips'
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    ->inwhere("GuidedLearningDayGameMap.subject_id",array($subject_id))
                    ->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
                    ->inwhere("GamesDatabase.rahul",array(1))
                    ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();
                }

                }

                    }
                    else
                     {

                           if($input_data->gender == 'famale'){
                    $guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
                        'GamesDatabase.game_id as games_id',
                        'GamesDatabase.games_name as games_name',
                        'GamesDatabase.games_folder as games_folder',
                        'GamesDatabase.daily_tips as daily_tips'
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    ->inwhere("GuidedLearningDayGameMap.subject_id",array($subject_id))
                    ->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
                    ->inwhere("GamesDatabase.tina",array(1))
                    ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();
                }
                else{
                    $guidedlearning_id = $this->modelsManager->createBuilder ()->columns ( array (
                    'GamesDatabase.game_id as games_id',
                    'GamesDatabase.games_name as games_name',
                    'GamesDatabase.games_folder as games_folder',
                    'GamesDatabase.daily_tips as daily_tips'
                    ))->from("GuidedLearningDayGameMap")
                    ->leftjoin('GamesDatabase','GuidedLearningDayGameMap.game_id = GamesDatabase.id')
                    ->inwhere("GuidedLearningDayGameMap.day_guided_learning_id",array($grade_id))
                    ->inwhere("GuidedLearningDayGameMap.subject_id",array($subject_id))
                    ->inwhere("GuidedLearningDayGameMap.day_id",array($day_id))
                    ->inwhere("GamesDatabase.rahul",array(1))
                    ->groupBy('GuidedLearningDayGameMap.game_id')
                    ->getQuery()->execute ();
                }

                     }   

                    






             
        //  }
            
            if ($guidedlearning_id) {
                $gameplaycheckvalue = 1;
                $games = array ();
                $i=1;
                $gamecolor = GameColors::findFirstByday ( date('l') );
                $games ['background_image'] = $gamecolor->background_color;
                $games ['gif'] = $gamecolor->gif;
                $games ['img'] = $gamecolor->img;
                $games ['gender'] = $input_data->gender;
                foreach ( $guidedlearning_id as $games_data ) {
                    
                    if($kidprofile -> test_kid_status == 0){
                        $game_getses = $this->modelsManager->createBuilder()->columns(array(
                            'KidsGamesStatus.session_id as session_id',
                            'KidsGamesStatus.current_status as current_status',
                            'KidsGamesStatus.game_id as game_id',
                        ))->from('KidsGamesStatus')
                        ->where('KidsGamesStatus.created_date < "'. date ('Y-m-d') .'"')
                        ->inwhere('KidsGamesStatus.game_id', array(
                             $games_data->games_id
                        ))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                            $input_data->kid_id
                        ))->inwhere('KidsGamesStatus.current_status', array(
                            1
                        ))->getQuery()->execute();
                        if(count($game_getses) <= 0){
                            $game_value['status'] = true;
                        } else {
                            $game_value['status'] = false;
                        }
                        
                        $game_getses2 = $this->modelsManager->createBuilder()->columns(array(
                            'KidsGamesStatus.session_id as session_id',
                            'KidsGamesStatus.current_status as current_status',
                            'KidsGamesStatus.game_id as game_id',
                        ))->from('KidsGamesStatus')
                        ->inwhere('KidsGamesStatus.game_id', array(
                             $games_data->games_id
                        ))->inwhere('KidsGamesStatus.nidara_kid_profile_id', array(
                            $input_data->kid_id
                        ))->inwhere('KidsGamesStatus.current_status', array(
                            1
                        ))->getQuery()->execute();
                        if(count($game_getses2) <= 0){
                            $gameplaycheckvalue = 2;
                        }
                    } else {
                        $game_value['status'] = true;
                    }
                    $game_value['games_id'] = $games_data -> games_id;
                    $game_value['grade_id'] = $games_data -> grade_id;
                    $game_value['games_name'] = $games_data -> games_name;
                    $game_value['games_folder'] = $games_data -> games_folder;
                    $game_value['daily_tips'] = $games_data -> daily_tips;
                    $game_value['day_id'] = $day_id;
                    $games_data_array [] = $game_value;
                    $i++;
                }
                if(($grade_id) == 4 || ($grade_id) == 5 || ($grade_id) == 6 || ($grade_id) == 7 || ($grade_id) == 8){
                     $chunked_array = array_chunk ( $games_data_array, 1 );
                    array_replace ( $chunked_array, $chunked_array );
                    $keyed_array = array ();
                    foreach ( $chunked_array as $chunked_arrays ) {
                        $keyed_array [] ['page'] = $chunked_arrays;
                    }
                    if($kidprofile -> test_kid_status == 0){
                        if($gameplaycheckvalue == 2){
                                $games['palygamestatus'] = false;
                            } else {
                                $games['palygamestatus'] = true;
                            }
                    } else {
                        $games['palygamestatus'] = false;
                    }
                    $games ['games'] = $keyed_array;
                    return Json_encode ( $games );
                    return $this->response->setJsonContent ([ 
                            'status' => true,
                            'data' =>$games
                    ]);
                }
                else{
                    $chunked_array = array_chunk ( $games_data_array, 4 );
                    array_replace ( $chunked_array, $chunked_array );
                    $keyed_array = array ();
                    foreach ( $chunked_array as $chunked_arrays ) {
                        $keyed_array [] ['page'] = $chunked_arrays;
                    }
                    if($kidprofile -> test_kid_status == 0){
                        if($gameplaycheckvalue == 2){
                                $games['palygamestatus'] = false;
                            } else {
                                $games['palygamestatus'] = true;
                            }
                    } else {
                        $games['palygamestatus'] = false;
                    }
                    $games ['games'] = $keyed_array;
                    return Json_encode ( $games );
                    return $this->response->setJsonContent ([ 
                            'status' => true,
                            'data' =>$games
                    ]);
                }
            }
        }
    }

   


    public function createdailyroutinesubject()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $dailyroutinesubject=DailyRoutineSubject::findFirstByid($input_data-> id);

            if(!$dailyroutinesubject)
            {
             $dailyroutinesubject=new DailyRoutineSubject();
            }

            $dailyroutinesubject->kid_id = $input_data->kid_id;
            $dailyroutinesubject->subject_id= $input_data->subject_id;
            $dailyroutinesubject->start_time= $input_data->start_time;
            $dailyroutinesubject->end_time = $input_data->end_time;
            $dailyroutinesubject->play_date  = $input_data->play_date;
     


           if($dailyroutinesubject->save())
            {
             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $dailyroutinesubject]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  $dailyroutinesubject]);

            }



}         


    public function geydailyroutinesubjectbykid()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $dailyroutinesubject=$this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'DailyRoutineSubject.*',
            ))
            ->from('DailyRoutineSubject')
            ->inwhere("DailyRoutineSubject.kid_id", array(
            $input_data->kid_id
        ))
            ->getQuery()
            ->execute();


           if($dailyroutinesubject)
            {
             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $dailyroutinesubject]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  $dailyroutinesubject]);

            }



} 

    public function geydailyroutinesubjectbydate()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $dailyroutinesubject=$this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'DailyRoutineSubject.*',
            ))
            ->from('DailyRoutineSubject')
            ->inwhere("DailyRoutineSubject.kid_id", array(
            $input_data->kid_id
        ))
            ->inwhere("DailyRoutineSubject.play_date", array(
            $input_data->play_date
        ))
            ->getQuery()
            ->execute();


           if(count($dailyroutinesubject)>0)
            {
             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $dailyroutinesubject]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  "Data Not Found"]);

            }



}


}
            
