<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Phalcon\Validation\Validator\Email;
use Aws\Credentials\CredentialProvider;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
require BASE_PATH.'/vendor/mailin.php';
require BASE_PATH.'/vendor/autoload.php';
require '../vendor/autoload.php';
require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
class SchoolsController extends \Phalcon\Mvc\Controller {

    // CONST ldaprdn = 'cn=admin,dc=nidarachildren,dc=in';
	// CONST ldappass = 'haselfre12';
	// CONST host = 'localhost';

	// // CONST host = 'apidev.nidarachildren.com';
	// public function connection() {
	// 	// using ldap bind
	// 	// return self::host;
	// 	$ldaprdn = 'cn=admin,dc=nidarachildren,dc=in'; // ldap rdn or dn
	// 	$ldappass = 'haselfre12'; // associated password
	// 	$binddn = "cn=admin,dc=nidarachildren,dc=in";
	// 	// connect to ldap server
	// 	$ldapconn = ldap_connect ( self::host ) or die ( "Could not connect to LDAP server." );
	// 	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	//     return $ldapconn;
    // }

	public function index() {
	}
    
    public function viewall(){
        $schooldata = Schools::find();
        if ($schooldata) :
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$schooldata
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed',
				        "data"=>array() 
			] );
		endif;
    }

    public function getbyid(){
        $input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
        }
        $schoolarray = array();
        $parentarray = array();
        $childarray = array();
        $getSchool =  Schools::findFirstByid($input_data -> school_id);
        if ($getSchool) :
            $data3['first_name'] = '';
            $childarray[] = $data3;
            $data2['first_name'] = '';
            $data2['childInfo'] = $childarray;
            $parentarray[] = $data2;
            // foreach($getSchool as $value){
                $data['id'] = $getSchool -> id;
                $data['school_name'] = $getSchool -> school_name;
                $data['phone_number'] = $getSchool -> phone_number;
                $data['principal_name'] = $getSchool -> principal_name;
                $data['principal_mobile'] = $getSchool -> principal_mobile;
                $data['parentInfo'] =  $parentarray;
                $schoolarray[] = $data;

            // }
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$schoolarray
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed'
			] );
		endif;
    }


    public function checkUserEmail(){
        $input_data = $this->request->getJsonRawBody ();
            $user = Users::findFirstByemail($input_data -> email);
            if($user){
                return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "This email address is already in use. Please enter a different one."
			    ] );
            } else {
                return $this->response->setJsonContent ( [
					"status" => true,
					"message" => ""
			    ] );
            }
    }

    public function checkUserMobile(){
        $input_data = $this->request->getJsonRawBody ();
            $user = Users::findFirstBymobile($input_data -> mobile);
            if($user){
                return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "This mobile number address is already in use. Please enter a different one."
			    ] );
            } else {
                return $this->response->setJsonContent ( [
					"status" => true,
					"message" => ""
			    ] );
            }
    }


    public function setregistationdate(){
        $input_data = $this->request->getJsonRawBody ();
        $headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
        } else {
            $date = SchoolRegistrationDate::findFirstByschool_id($input_data -> school_id);
            $school = Schools::findFirstByid($input_data -> school_id);
            if(!$date){
                $date = new SchoolRegistrationDate();
            }
            $date -> school_id = $input_data -> school_id;
            $date -> start_date = $input_data -> fromdate;
            $date -> end_date = $input_data -> todate;
            $date -> status = $input_data -> status;
            if($input_data -> fromdate === date('Y-m-d')){
            	$today = true;
            } else {
            	$today = false;
            }
            if(!$date -> save()){
                return $this->response->setJsonContent ( [
			"status" => false,
			"message" => "You need to set registration date for " . $school -> school_name . "",
			"today" => $today
		] );
            } else {
                if($input_data -> status == '2'){
                    return $this->response->setJsonContent ( [
                        "status" => false,
                        "message" => "You need to set registration date for " . $school -> school_name . "" ,
                        "today" => $today
                    ] );
                } else {
                    return $this->response->setJsonContent ( [
                        "status" => true,
                        "message" => "The registration date has been set successfully",
                        "today" => $today
                    ] );
                }
                
            }
        }
    }

    public function getrgisterDatebyschoolid(){
        $input_data = $this->request->getJsonRawBody ();
        $headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
        } else {
            $date = SchoolRegistrationDate::findFirstByschool_id($input_data -> school_id);
            $school = Schools::findFirstByid($input_data -> school_id);
            if($date){
                if($date -> status == '2'){
                    return $this->response->setJsonContent ( [
                        "status" => false,
                        "message" => "You need to set registration date for " . $school -> school_name
                    ] );
                } else {
                    return $this->response->setJsonContent ( [
                        "status" => true,
                        "message" => "The registration date has been set successfully"
                    ] );
                }
            }
        }
    }


    public function getInformationByid(){
        $input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
        }
        $schoolarray = array();
       
       // $childarray = array();
        $getSchool =  Schools::findFirstByid($input_data -> school_id);
        if ($getSchool) :
                $getuservalue = $this->modelsManager->createBuilder ()->columns ( array (
                    'Users.id as id',
                    'Users.first_name as first_name',
                    'Users.email as email',
                    'Users.status as status',
                 ))->from("SchoolParentMap")
                 ->leftjoin('Users','Users.id = SchoolParentMap.user_id')
                 ->inwhere('SchoolParentMap.school_id',array($input_data -> school_id))
                 ->getQuery()->execute ();
                 $parentarray = array();
                foreach($getuservalue as $value){
                    $data2['id'] = $value -> id;
                    $data2['first_name'] = $value -> first_name;
                    $data2['email'] = $value -> email;
                    if($value -> status <= 2){
                        $data2['status'] = 'Registered';
                    } else {
                        $data2['status'] = 'Not Registered';
                    }
                    $parentarray[] = $data2;
                }
                $data['id'] = $getSchool -> id;
                $data['school_name'] = $getSchool -> school_name;
                $data['phone_number'] = $getSchool -> phone_number;
                $data['principal_name'] = $getSchool -> principal_name;
                $data['principal_mobile'] = $getSchool -> principal_mobile;
                $data['parentInfo'] =  $parentarray;
                $schoolarray[] = $data;

            // }
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$schoolarray
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed'
			] );
		endif;
    }

    public function getSchoolUser(){
        $input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
        }
        $user_id =  $input_data -> user_id;
        $getSchool = $this->modelsManager->createBuilder ()->columns ( array (
           'Schools.id as id',
           'Schools.school_name as school_name',
        ))->from("SchoolUserMap")
        ->leftjoin('Schools','Schools.id = SchoolUserMap.schools_id')
        ->inwhere('SchoolUserMap.users_id',array($user_id))
        ->getQuery()->execute ();
        if ($getSchool) :
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$getSchool
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed'
			] );
		endif;
    }

    public function getSchoolFilter(){
        $input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					"status" => false,
					"message" => "Please give the token"
			] );
        }
        $user_id =  $input_data -> user_id;
        $getSchool = $this->modelsManager->createBuilder ()->columns ( array (
           'Schools.id as id',
           'Schools.school_name as school_name',
        ))->from("SchoolUserMap")
        ->leftjoin('Schools','Schools.id = SchoolUserMap.schools_id')
        ->inwhere('SchoolUserMap.users_id',array($user_id))
        ->getQuery()->execute ();
        if ($getSchool) :
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$getSchool
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed'
			] );
		endif;
    }

    function generate_random_letters() {
		$length = 3;
    		$random = '';
		$random .= 'NC';
    		for ($i = 0; $i < $length; $i++) {
        		$random .= chr(rand(ord('A'), ord('X')));
			$random .= chr(rand(ord(0), ord(9)));

    		}
    		return $random;
	}


  
	
}
