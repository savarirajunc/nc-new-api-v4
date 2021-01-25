<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class CoreWebsitePaymentGatewayController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    /**
    * Fetch all Record from database :-
    */

    public function viewall(){
        $core_web = CoreFrameworks::find ();
		if ($core_web) :
			return Json_encode ( $core_web );
		 else :
			return $this->response->setJsonContent ( [ 
					'status'  => false,
					'Message' => 'Failed' 
			] );
		endif;
    }

    /** 
     * Fetch the Record from database based on url :-
    */

    public function getvaluebyapiurl(){
        $input_data = $this->request->getJsonRawBody ();
		if (!$input_data) {
		    return $this->response->setJsonContent ( [ 
				"status"  => false,
				"message" => "Invalid User" 
			] );
        }
        else{
            $colloection = CoreWebsitePaymentGateway::findFirstByapiurl($input_data -> api);
            if($colloection){
                return $this->response->setJsonContent ( [ 
                    "status" => true,
                    "data"   => $colloection
                ] ); 
            }
            else {
                return $this->response->setJsonContent ( [ 
					'status'  => false,
					'Message' => 'Failed' 
			    ] );
            }
        }
    }

}

