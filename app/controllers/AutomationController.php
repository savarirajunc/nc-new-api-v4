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

class AutomationController extends \Phalcon\Mvc\Controller
{

    public function selectoneimageautomation()
    {

        $time = 0;

        


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();

        foreach ($gamesCount as $gamevalue)
        {

            if ($gamevalue->value == 'question')
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                    $j=0;

                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = 1;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $obj = $questionvalcount[$k]->answer_des;


                        }
                        else if ($answershow == 1)
                        {
                            $ansval = 1;
                            $obj = $gamequestion->answer_des;
                        }
                        else if ($answershow == 3)
                        {
                            if($j==1)
                            {
                                        $ansval = 0;

                                  $k=0;

                                    $a=array();

                                    for($b=1;$b<=count($questionvalcount);$b++)
                                    {
                                    $a[]=$b;
                                    }

                                    $array = array_diff($a, [$i]);

                                    $k = array_rand($array);

                                    $obj = $questionvalcount[$k]->answer_des;
                            }
                            else
                            {
                            $ansval = 1;
                            $obj = $gamequestion->answer_des;
                            }
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $obj = $gamequestion->answer_des;
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $obj = $questionvalcount[$k]->answer_des;

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $gamevalue->slide;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj.'.png';
                            $gameanswer->slide_type = $gamevalue->value;
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');


                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }

                            $i = $i + 1;
                            $j=$j+1;

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $gamevalue->slide;
                        $gameanswer->answers = 0;
                        $gameanswer->object_name = null;
                        if ((strpos($gamevalue->value, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue->value;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');

                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

    }

    public function morethanoneimageselectautomation()
    {

        $time = 0;

        


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();

        foreach ($gamesCount as $gamevalue)
        {

            if ((strpos($gamevalue->value, 'question') !== false))
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                    'GamesQuestionAnswer.game_type_value'
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                    $j=0;
                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = 1;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $val=array();
                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);

                           


                        }
                        else if ($answershow == 3)
                        {
                            if($j==1)
                            {
                                       $ansval = 0;

                                          $k=0;

                                            $a=array();

                                            for($b=1;$b<=count($questionvalcount);$b++)
                                            {
                                            $a[]=$b;
                                            }

                                            $array = array_diff($a, [$i]);

                                            $k = array_rand($array);

                                            $val=array();
                                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                                        foreach ($str_arr as $imgobj)
                                                        {
                                                        $val[]=$imgobj.'.png';
                                                        }
                                                        $obj =implode(",",$val);
                            }
                            else
                            {
                                 $ansval = $gamequestion->game_type_value;

                            $val=array();
                            $str_arr = explode(",", $gamequestion->answer_des);
                            foreach ($str_arr as $imgobj)
                            {
                             $val[]=$imgobj.'.png';
                            }
                             $obj =implode(",",$val);

                            }
                           } 
                        else if ($answershow == 1)
                        {
                            $ansval = $gamequestion->game_type_value;

                            $val=array();
                            $str_arr = explode(",", $gamequestion->answer_des);
                            foreach ($str_arr as $imgobj)
                            {
                             $val[]=$imgobj.'.png';
                            }
                             $obj =implode(",",$val);
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $ansval = $gamequestion->game_type_value;

                                        $val=array();
                                        $str_arr = explode(",", $gamequestion->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                                    for($b=1;$b<=count($questionvalcount);$b++)
                                    {
                                    $a[]=$b;
                                    }

                                        $array = array_diff($a, [$i]);

                                        $k = array_rand($array);

                                        //$obj = $questionvalcount[$k]->answer_des;

                                        $val=array();
                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $gamevalue->slide;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj;
                            $gameanswer->slide_type = 'question';
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');


                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }

                            $i = $i + 1;

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $gamevalue->slide;
                        $gameanswer->answers = 0;
                        
                        $gameanswer->object_name = null;
                          if ((strpos($gamevalue->value, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue->value;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');

                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

            }


 public function audioselectoneimageautomation()
    {

        $time = 0;

        


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();

        foreach ($gamesCount as $gamevalue)
        {

            if ($gamevalue->value == 'question')
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = 1;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $obj = $questionvalcount[$k]->answer_des;


                        }
                        else if ($answershow == 1)
                        {
                            $ansval = 1;
                            $obj = $gamequestion->answer_des;
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $obj = $gamequestion->answer_des;
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $obj = $questionvalcount[$k]->answer_des;

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $gamevalue->slide;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj.'.png';
                            $gameanswer->slide_type = $gamevalue->value;
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');





                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }
                            else
                            {
                                $getid=GamesAnswers::findFirstByid($gameanswer->id);

                                

                                $audiocount = rand(0, 5);

                                $aucount=new GamesAnswersAudioCount();

                                $aucount->session_id=$uniqeid;
                                $aucount->game_id=$gameid;
                                $aucount->child_id=$kidid;
                                $aucount->click_count=$audiocount;
                                $aucount->time=date('H:i:s',$getid->rec_data);
                                $aucount->slide_no =$gamevalue->slide;
                                $aucount->question_id=$gamequestion->question_id;
                                $aucount->create_at=date('Y-m-d');
                                if (!$aucount->save())
                                    {
                                return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $aucount]);
                                        
                                    }



                            }   


                            $i = $i + 1;

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $gamevalue->slide;
                        $gameanswer->answers = 0;
                        $gameanswer->object_name = null;
                          if ((strpos($gamevalue->value, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue->value;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');

                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

    }

     public function seccolormorethanoneimageselectautomation()
    {

        $time = 0;

        $q=1;


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();

        foreach ($gamesCount as $gamevalue)
        {


            if ((strpos($gamevalue->value, 'question') !== false))
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))
                    ->inwhere("GamesQuestionAnswer.question_id", array(
                    $q
                ))

                    ->getQuery()
                    ->execute();




                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                    'GamesQuestionAnswer.game_type_value'
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))
                   

                    ->getQuery()
                    ->execute();

                      

                    $q=$q+1;

                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = $gamequestion->question_id;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $val=array();
                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);

                           


                        }
                        else if ($answershow == 1)
                        {
                            $ansval = $gamequestion->game_type_value;

                            $val=array();
                            $str_arr = explode(",", $gamequestion->answer_des);
                            foreach ($str_arr as $imgobj)
                            {
                             $val[]=$imgobj.'.png';
                            }
                             $obj =implode(",",$val);
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $ansval = $gamequestion->game_type_value;

                                        $val=array();
                                        $str_arr = explode(",", $gamequestion->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                                    for($b=1;$b<=count($questionvalcount);$b++)
                                    {
                                    $a[]=$b;
                                    }

                                        $array = array_diff($a, [$i]);

                                        $k = array_rand($array);

                                        //$obj = $questionvalcount[$k]->answer_des;

                                        $val=array();
                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $gamevalue->slide;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj;
                            $gameanswer->slide_type = 'question';
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');


                             

                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }

                            

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $gamevalue->slide;
                        $gameanswer->answers = 0;
                        $gameanswer->object_name = null;
                         if ((strpos($gamevalue->value, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue->value;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');

                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

     }



public function soilselectoneimageautomation()
    {

        $sno=0;
        $time = 0;

        


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();



        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

      /*  $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();*/

            //$gamesCount = array("intro", "learning", "learning", "learning", "learning", "learning", "learning","game intro","question","last"); 

            foreach ($input_data->slideinfo as $slidevalue) {
                # code...
                $gamesCount[]=$slidevalue;
            }



        foreach ($gamesCount as $gamevalue)
        {
        $sno=$sno+1;

            if ($gamevalue == 'question')
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();




                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = 1;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);




                            $obj = $questionvalcount[$k]->answer_des.'.png,'.$questionvalcount[$i]->answer_des.'.png';

                          


                        }
                        else if ($answershow == 1)
                        {
                            $ansval = 1;
                            $obj = $gamequestion->answer_des.'.png';
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $obj = $gamequestion->answer_des.'.png';
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $obj = $questionvalcount[$k]->answer_des.'.png,'.$questionvalcount[$i]->answer_des.'.png';

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $sno;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj;
                            $gameanswer->slide_type = $gamevalue;
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');




                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }

                            $i = $i + 1;

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $sno;
                        $gameanswer->answers = 0;
                        $gameanswer->object_name = null;
                        if ((strpos($gamevalue, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');



                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

    }


    public function indujuvalselectoneimageautomation()
    {

        $sno=0;
        $time = 0;

        


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

            

        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

      /*  $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();*/

            //$gamesCount = array("intro", "learning", "learning", "learning", "learning", "learning", "learning","game intro","question","last"); 

            foreach ($input_data->slideinfo as $slidevalue) {
                # code...
                $gamesCount[]=$slidevalue;
            }



        foreach ($gamesCount as $gamevalue)
        {
        $sno=$sno+1;

            if ($gamevalue == 'question')
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();


      $j=0;

                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = 1;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);




                            $obj = $questionvalcount[$k]->answer_des;

                          


                        }
                        else if ($answershow == 1)
                        {
                            $ansval = 1;
                            $obj = $gamequestion->answer_des;
                        }
                        else if ($answershow == 3)
                        {
                            if($j==1)
                            {
                                        $ansval = 0;

                                  $k=0;

                                    $a=array();

                                    for($b=1;$b<=count($questionvalcount);$b++)
                                    {
                                    $a[]=$b;
                                    }

                                    $array = array_diff($a, [$i]);

                                    $k = array_rand($array);

                                    $obj = $questionvalcount[$k]->answer_des;
                            }
                            else
                            {
                            $ansval = 1;
                            $obj = $gamequestion->answer_des;
                            }
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $obj = $gamequestion->answer_des;
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $obj = $questionvalcount[$k]->answer_des;

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $sno;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj.'.png';
                            $gameanswer->slide_type = $gamevalue;
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');




                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }

                            $i = $i + 1;
                            $j = $j + 1;

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $sno;
                        $gameanswer->answers = 0;
                        $gameanswer->object_name = null;
                        if ((strpos($gamevalue, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');



                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

    }


    public function indujuvalselectmultiimageautomation()
    {

        $sno=0;
        $time = 0;

        


        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

            

        $gameid = $input_data->game_id;
        $kidid = $input_data->nidara_kid_profile_id;

                $answershow = $input_data->answers;


        $uniqeid = uniqid();

        for ($j = 0;$j < 2;$j++)
        {
            $kidstatus = new KidsGamesStatus();
            $kidstatus->session_id = $uniqeid;
            $kidstatus->game_id = $gameid;
            $kidstatus->nidara_kid_profile_id = $kidid;
            $kidstatus->current_status = $j;
            $kidstatus->created_date = date('Y-m-d');

            if (!$kidstatus->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => $kidstatus]);
            }
        }

      /*  $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'ApiApplicationComponentMaster.value',
            'ApiApplicationComponentMaster.slide',

        ))
            ->from('ApiApplicationComponentMaster')
            ->leftjoin('ApiApplicationGameDatabase', 'ApiApplicationGameDatabase.game_template = ApiApplicationComponentMaster.template_master_id')
            ->leftjoin('GamesDatabase', 'ApiApplicationGameDatabase.game_name = GamesDatabase.game_internal_name')
            ->inwhere("GamesDatabase.game_id", array(
            $input_data->game_id
        ))
            ->groupby('ApiApplicationComponentMaster.slide')
            ->getQuery()
            ->execute();*/

            //$gamesCount = array("intro", "learning", "learning", "learning", "learning", "learning", "learning","game intro","question","last"); 

            foreach ($input_data->slideinfo as $slidevalue) {
                # code...
                $gamesCount[]=$slidevalue;
            }



        foreach ($gamesCount as $gamevalue)
        {
        $sno=$sno+1;

            if ($gamevalue == 'question')
            {
                $questionval = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.*',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();

                $questionvalcount = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'GamesQuestionAnswer.answer_des',
                ))
                    ->from('GamesQuestionAnswer')

                    ->inwhere("GamesQuestionAnswer.game_id", array(
                    $input_data->game_id
                ))

                    ->getQuery()
                    ->execute();


     				 $j=0;

                foreach ($questionval as $gamequestion)
                {
                    # code...
                    $i = 1;

                    $time = $time + rand(1, 10);
                    $acttime = rand(0, 3);

                    	if ($answershow == 0)
                    {
                        $ansval = 0;

                          $k=0;

                            $a=array();

                            for($b=1;$b<=count($questionvalcount);$b++)
                            {
                            $a[]=$b;
                            }

                            $array = array_diff($a, [$i]);

                            $k = array_rand($array);

                            $val=array();
                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);

                           


                        }
                        else if ($answershow == 3)
                        {
                            if($j==1)
                            {
                                       $ansval = 0;

                                          $k=0;

                                            $a=array();

                                            for($b=1;$b<=count($questionvalcount);$b++)
                                            {
                                            $a[]=$b;
                                            }

                                            $array = array_diff($a, [$i]);

                                            $k = array_rand($array);

                                            $val=array();
                                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                                        foreach ($str_arr as $imgobj)
                                                        {
                                                        $val[]=$imgobj.'.png';
                                                        }
                                                        $obj =implode(",",$val);
                            }
                            else
                            {
                                 $ansval = $gamequestion->game_type_value;

                            $val=array();
                            $str_arr = explode(",", $gamequestion->answer_des);
                            foreach ($str_arr as $imgobj)
                            {
                             $val[]=$imgobj.'.png';
                            }
                             $obj =implode(",",$val);

                            }
                           } 
                        else if ($answershow == 1)
                        {
                            $ansval = $gamequestion->game_type_value;

                            $val=array();
                            $str_arr = explode(",", $gamequestion->answer_des);
                            foreach ($str_arr as $imgobj)
                            {
                             $val[]=$imgobj.'.png';
                            }
                             $obj =implode(",",$val);
                        }
                        else
                        {
                            $ansval = rand(0, 1);
                            if ($ansval == 1)
                            {
                                $ansval = $gamequestion->game_type_value;

                                        $val=array();
                                        $str_arr = explode(",", $gamequestion->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);
                            }
                            else
                            {
                                 $k=0;
                                $a=array();

                                    for($b=1;$b<=count($questionvalcount);$b++)
                                    {
                                    $a[]=$b;
                                    }

                                        $array = array_diff($a, [$i]);

                                        $k = array_rand($array);

                                        //$obj = $questionvalcount[$k]->answer_des;

                                        $val=array();
                                        $str_arr = explode(",", $questionvalcount[$k]->answer_des);
                                        foreach ($str_arr as $imgobj)
                                        {
                                        $val[]=$imgobj.'.png';
                                        }
                                        $obj =implode(",",$val);

                                  
                                }
                            }

                            $gameanswer = new GamesAnswers();

                            $gameanswer->session_id = $uniqeid;
                            $gameanswer->game_id = $gameid;
                            $gameanswer->nidara_kid_profile_id = $kidid;
                            $gameanswer->questions_no = $gamequestion->question_id;
                            $gameanswer->slide_no = $sno;
                            $gameanswer->answers = $ansval;
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->object_name = $obj;
                            $gameanswer->slide_type = $gamevalue;
                            $gameanswer->replaycount = 0;
                            $gameanswer->time = $time;
                            $gameanswer->created_at = date('Y-m-d');




                            if (!$gameanswer->save())
                            {
                                return $this
                                    ->response
                                    ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                            }

                            $i = $i + 1;
                            $j = $j + 1;

                        }

                    }
                    else
                    {
                        $time = $time + rand(1, 10);

                        $acttime = rand(0, 3);

                        $gameanswer = new GamesAnswers();

                        $gameanswer->session_id = $uniqeid;
                        $gameanswer->game_id = $gameid;
                        $gameanswer->nidara_kid_profile_id = $kidid;
                        $gameanswer->questions_no = 0;
                        $gameanswer->slide_no = $sno;
                        $gameanswer->answers = 0;
                        $gameanswer->object_name = null;
                        if ((strpos($gamevalue, 'last') !== false))
                         {
                        $gameanswer->slide_type = 'last';
                        $gameanswer->actual_time = 0;
                         }
                         else
                         {
                            $gameanswer->actual_time = $acttime;
                            $gameanswer->slide_type = $gamevalue;
                         }
                        $gameanswer->replaycount = 0;
                        $gameanswer->time = $time;
                        $gameanswer->created_at = date('Y-m-d');



                        if (!$gameanswer->save())
                        {
                            return $this
                                ->response
                                ->setJsonContent(['status' => false, 'data' => $gameanswer]);
                        }

                    }
                }

                return $this
                    ->response
                    ->setJsonContent(['status' => true, 'data' => "Saved"]);

    }


}
            
