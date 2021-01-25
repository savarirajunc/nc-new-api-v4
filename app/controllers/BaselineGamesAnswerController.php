<?php
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Aws\Credentials\CredentialProvider;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
require BASE_PATH.'/vendor/autoload.php';
//require 'IP2Location.php';
class BaselineGamesAnswer extends \Phalcon\Mvc\Controller {
	
	
	/*
	baselineanswersave : is used to save the baseline game answer at the time of game playing by child
	Table : baseline_games_answer
	Input : 
    "kid_id"
	"game_id"
    "question_id"
	"answer":"test"
    "timeval":"12:12:00"
    "created_date":"2020-02-02"

	
	
	*/
	
	
	
	public function baselineanswersave()
	{
		$input_data = $this->request->getJsonRawBody ();
		


		$baselineanswer=new BaselineGamesAnswer();

		$baselineanswer -> kid_id =$input_data -> nidara_kid_profile_id;
		$baselineanswer -> game_id =$input_data -> game_id;
		$baselineanswer -> question_id =$input_data -> question_id;
		$baselineanswer -> answer =$input_data -> object_val;
		$baselineanswer -> timeval =$input_data -> time;
		$baselineanswer -> created_date =date('Y-m-d');
		
		if($baselineanswer->save())
		{
		return $this->response->setJsonContent ( [ 
					'status' => true,
					'Message' => 'Saved' 
			] );	
		}
		else
		{
return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Not Saved' 
			] );	


		}

			
	}

		public function view()
			{

			return $this->response->setJsonContent ( [ 
					'status' => false,
					'Message' => 'Not Saved' 
			] );	

			}
	
	
}
