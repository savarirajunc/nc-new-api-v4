<?php declare(strict_types=1);use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Aws\Credentials\CredentialProvider;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
use GuzzleHttp\Client;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// require BASE_PATH.'/vendor/Crypto.php';
require BASE_PATH.'/vendor/mailin.php';
require BASE_PATH.'/vendor/class.phpmailer.php';
require BASE_PATH.'/vendor/autoload.php';




use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


class LoginController extends \Phalcon\Mvc\Controller {
	CONST ldaprdn = 'cn=admin,dc=nidarachildren,dc=com';
	CONST ldappass = 'haselfre12';
	CONST host = 'localhost';

	// CONST host = 'apidev.nidarachildren.com';
	public function connection() {
		// using ldap bind
		// return self::host;
		$ldaprdn = 'cn=admin,dc=nidarachildren,dc=com'; // ldap rdn or dn
		$ldappass = 'haselfre12'; // associated password
		$binddn = "cn=admin,dc=nidarachildren,dc=com";
		// connect to ldap server
		$ldapconn = ldap_connect ( self::host ) or die ( "Could not connect to LDAP server." );
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	    return $ldapconn;
	}
	

	function azureAdToken(){
		$tenantId = $this->config->tenantId;
		$clientId = $this->config->clientId;
		$clientSecret = $this->config->clientSecret;
		$guzzle = new \GuzzleHttp\Client();
		$url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/token?api-version=1.0';
		$token = json_decode($guzzle->post($url, [
			'form_params' => [
				'client_id' => $clientId,
				'client_secret' => $clientSecret,
				'resource' => 'https://graph.microsoft.com/',
				'grant_type' => 'client_credentials',
			],
		])->getBody()->getContents());
		$accessToken = $token->access_token;
		return $accessToken;
	}


	public function deleteuser(){
		$accessToken = $this-> azureAdToken();
		try{
			$graph = new Graph();
			$graph->setAccessToken($accessToken);
			
			$user = $graph->createRequest("DELETE", "/users/ncpartnertesting13@nidarachildren.com")->setReturnType(Model\User::class)->execute();
			
			$getInfo = array();
			foreach($user as $value){
				$getInfo[] = $value;
			}
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $user
			] );
		}
		catch(\Exception $e){
			echo json_encode(array(
				"status" => false,
				"message" => "Access denied.",
				"error" => $e->getMessage()
			));
		}
		return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $user
			] );
	}


	public function getaccess(){
		$accessToken = $this-> azureAdToken();
		
        $graph = new Graph();
		$graph->setAccessToken($accessToken);
		
		$user = $graph->createRequest("GET", "/users/rsavari007@nidarachildren.com")->setReturnType(Model\User::class)->execute();
		
		$getInfo = array();
		foreach($user as $value){
			$getInfo[] = $value;
		}
		return $this->response->setJsonContent ( [ 
				'status' => true,
				'data' => $user
			] );
	}

	public function loginad(){
		$tenantId = $this->config->tenantId;
		$clientId = $this->config->clientId;
		$clientSecret = $this->config->clientSecret;
		$guzzle = new \GuzzleHttp\Client();
		$url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
		$token = json_decode($guzzle->post($url, [
			'form_params' => [
				'client_id' => $clientId,
				'client_secret' => $clientSecret,
				'scope' => 'user.read openid profile offline_access',
				'username' => 'ncpartnertesting12@nidarachildren.com',
				'password' => 'NCuyevvy76#',
				'grant_type' => 'password',
			],
		])->getBody()->getContents());
		$accessToken = $token->access_token;
		$graph = new Graph();
		$graph->setAccessToken($accessToken);
		
		$user = $graph->createRequest("GET", "/me")->setReturnType(Model\User::class)->execute();
		$getinfo = array();
		// echo $user;
		$getinfo[] = $user;
		$get  = json_decode(json_encode($user),true);
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'data' => $user
		] );
	}


	public function resetpasswordad(){
		$tenantId = 'a812087f-d0d0-4a13-b6a3-d92930d56005';
		$clientId = '6a00ce30-d34b-4d06-ad9e-59cfaa93ee7f';
		$clientSecret = 'X3s5ahEV-PW9GO_s__KObXARzmm7Glnf1z';
		$guzzle = new \GuzzleHttp\Client();
		$url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
		$token = json_decode($guzzle->post($url, [
			'form_params' => [
				'client_id' => $clientId,
				'client_secret' => $clientSecret,
				'scope' => 'user.read openid profile offline_access',
				'username' => 'savariraju@nidarachildren.com',
				'password' => 'Haselfre12#',
				'grant_type' => 'password',
			],
		])->getBody()->getContents());
		$accessToken = $token->access_token;
		$graph = new Graph();
		$graph->setAccessToken($accessToken);
		$password['currentPassword'] = 'Haselfre12#';
		$password['newPassword'] = 'Haselfre123#';
	
		$chanPassword = $graph
		->createRequest("POST", "/me/changePassword")
		->attachBody($password)
		->setReturnType(Model\User::class)
		->execute();

		return $this->response->setJsonContent ( [ 
			'status' => true,
			'data' => $chanPassword
		] );
	}


	public function careteazureaduser(){
		$accessToken = $this-> azureAdToken();
		
        $graph = new Graph();
		$graph->setAccessToken($accessToken);

		$input_data = $this->request->getJsonRawBody();
		// $data = array();
		$password['forceChangePasswordNextSignIn'] = false;
		$password['password'] = 'Haselfre123#';
		$uservala['accountEnabled'] = true;
		$uservala['displayName'] = "Savariraju";
		$uservala['mailNickname'] = "Savariraju";
		$uservala['userPrincipalName'] = "savariraju2@nidarachildren.com";
		$uservala['passwordProfile'] = $password;
		$data = $uservala;
		
		$user = $graph
		->createRequest("POST", "/users")
		->attachBody($data)
		->setReturnType(Model\User::class)
		->execute();
		$getvalau = json_encode($user);
		$getinfo = array();
		// echo $user;
		$getinfo[] = $user;
		$get  = json_decode(json_encode($user),true);
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'data' => $getvalau
		] );

		
	}


	public function azureAd(){
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'token' => $this-> azureAdToken()
		] );
	}
		
	/**
	 * Fetch Record from database based on ID :-
	 */
	public function getbyid($id = null) {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => 'Error',
					'message' => 'Invalid input parameter' 
			] );
		 else :
			$collection = Subject::findFirstByid ( $id );
			if ($collection) :
				return Json_encode ( $collection );
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => 'Error',
						'Message' => 'Data not found' 
				] );
			endif;
		endif;
	}
	public function getUnicId(){
		$code = $this -> generate_random_letters();
		return $this->response->setJsonContent([
			 'status' => true,
			'message' => $code		
		] );
	}

	function generate_random_letters() {
		$length = 6;
    		$random = '';
		$random .= 'NC';
    		for ($i = 0; $i < $length; $i++) {
        		$random .= chr(rand(ord('a'), ord('z')));
				//$random .= chr(rand(ord(0), ord(9)));
			} 
			for($z = 0; $z < 2; $z++){
				$random .= chr(rand(ord('0'), ord('9')));
			}
		$random .= '#';
    		return $random;
	}




	public function registerschooluser(){
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data)){
			return $this->response->setJsonContent([
			 	'status' => false,
				'message' => "Please give the details and then login"
			] );
		} else {
			// $ldapconn = $this->connection ();
			foreach($input_data->parentInfo as $parentvalue){
				$user = new Users ();
				$user->id = $this->usersid->getNewId("users");
				$user->parent_type = $parentvalue->parent_type;
				$user->first_name = $parentvalue->first_name;
				$user->last_name = $parentvalue->last_name;
				$user->user_type = 'parent';
				$user->email = $parentvalue->email;
				$user->photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/icon.png';
				$user->mobile = $parentvalue->mobile;
				$user->created_at = date ( 'Y-m-d H:i:s' );
				if(empty($input_data -> id)){
					$user->created_by = 100;
				}
				else{
					$user->created_by = $input_data->id;
				}
				$user->status = 5;
				$user->act_status = 1;
				$user->modified_at = date ( 'Y-m-d H:i:s' );
				if(!$user->save()){
					return $this->response->setJsonContent([
						'status' => false,
						'message' => "Parent not create"
					] );
				} else {
					$code = $this -> generate_random_letters();
					$email = $parentvalue->email;
					// $password = $code;
					$userpassword = new UserTemPassword();
					$userpassword -> user_id = $user-> id;
					$userpassword -> password = $code;
					if(!$userpassword -> save()){
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => "Password not save"
						] );

					}
					try{
						$accessToken = $this-> azureAdToken();
						
						$graph = new Graph();
						$graph->setAccessToken($accessToken);
						$parts=explode('@',$email);
						$userPrincipalName = $parts[0] . '@nidarachildren.com';
						
						$password['forceChangePasswordNextSignIn'] = false;
						$password['password'] = $code;
						$uservala['accountEnabled'] = true;
						$uservala['givenName'] = $user-> id;
						$uservala['displayName'] =  $parentvalue->first_name . ' ' .  $parentvalue->last_name ;
						$uservala['mailNickname'] = $parentvalue -> first_name;
						$uservala['userPrincipalName'] = $userPrincipalName;
						$uservala['passwordProfile'] = $password;
						$data = $uservala;
						$usercreate = $graph
						->createRequest("POST", "/users")
						->attachBody($data)
						->setReturnType(Model\User::class)
						->execute();
						if($usercreate){
							$user_infoval = Users::findFirstByemail($email);
							$get  = json_decode(json_encode($usercreate),true);
							$this -> adduseradinfo($user_infoval -> id, $get['id'], $get['userPrincipalName'] );
						}
					} 
					catch(\Exception $e) {
						echo json_encode(array(
							"status" => false,
							"message" => "Access denied.",
							"error" => $e->getMessage()
						));
					}
					foreach( $parentvalue->childInfo as $childvalue){
						$kidprofile = new NidaraKidProfile ();
						$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
						$kidprofile->first_name = $childvalue->first_name;
						if (! empty ( $childvalue->middle_name )) {
							$kidprofile->middle_name = $childvalue->middle_name;
						}
						$kidprofile->last_name = $childvalue->last_name;
						$kidprofile->date_of_birth = date('Y-m-d');
						if(!empty($input_data->age)){
							$kidprofile->age = $childvalue->age;
						}
						$kidprofile->expiry_date = date("Y-m-d",strtotime('+365days'));
						$kidprofile->gender = $childvalue->gender;
						$kidprofile->height = '0';
						$kidprofile->weight = '0';
						$kidprofile->grade = $childvalue->grade;
						$kidprofile->created_at = date ( 'Y-m-d H:i:s' );
						$kidprofile->created_by = $user -> id;
						$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
						$kidprofile->status = 5;
						$kidprofile->choose_time = 1;
						if(empty($input_data -> id)){
							$kidprofile->admission_status = 1;
							$kidschoolinfo = new NidaraKidSchoolInfo ();
						    	$kidschoolinfo->nidara_kid_profile_id = $kidprofile->id;
						    	$kidschoolinfo->school_name = $childvalue->school_name;
						    	$kidschoolinfo->school_type = $childvalue->school_type;
						    	$kidschoolinfo->address2 = $childvalue->address2;
						    	$kidschoolinfo->town_city = $childvalue->town_city;
						    	$kidschoolinfo->state = $childvalue->state;
						    	$kidschoolinfo->country = $childvalue->country;
						    	$kidschoolinfo->created_at =date('Y-m-d H:i:s');
						    	$kidschoolinfo->created_by = 1;
						    	if(!$kidschoolinfo-> save()){
						    		return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' => 'School infromation not saved' 
								] );
						    	}
						} else {
							$kidprofile->admission_status = $childvalue->admission_status;
						}
						
						$kidprofile->cancel_subscription = 1;
						if (empty ( $childvalue->child_photo )) {
							$gender = $childvalue->gender;
							if ($gender == 'male') {
								$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_male.png';
							} else {
								$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_female.png';
							}
						} 
						if (!$kidprofile->save ()) {
							return $this->response->setJsonContent([
								'status' => false,
								'message' => "Child not create",
								'data' => $kidprofile
							] );
						}
						else{
							$kid_guide = new KidGuidedLearningMap ();
							$kid_guide->id = $this->kididgen->getNewId ( "nidarakidlearningmap" );
							$kid_guide->nidara_kid_profile_id = $kidprofile->id;
							$kid_guide->guided_learning_id = $kidprofile->grade;
							$kid_guide->save ();
							$parentsmap = new KidParentsMap ();
							$parentsmap->id = $this->kididgen->getNewId ( "kidparentsmap" );
							$parentsmap->nidara_kid_profile_id = $kidprofile->id;
							$parentsmap->users_id = $user -> id;
							$parentsmap->save();
						}
					}

					if(empty($input_data -> id))
					{
						$parentmap = new NcSalesmanParentMap();
						$parentmap -> salesman_id = $input_data -> salesman_id;
						$parentmap -> user_id = $user -> id;
						$parentmap -> mail_status = 0;
					}
					else
					{
						$parentmap = new SchoolParentMap();
						$parentmap -> school_id = $input_data -> id;
						$parentmap -> user_id = $user -> id;
					}
					if(!$parentmap -> save()){
						return $this->response->setJsonContent([
							'status' => false,
							'message' => "Map is not create"
						] );
					}
				}
			}
			return $this->response->setJsonContent([
				'status' => true,
				'message' => "Data Save successfuly"
			] );
		}
	}


	// public function childregister($input_data){

	// }
	
	/**
	 * This function using to register the user
	 */
	public function register() {
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data)){
			return $this->response->setJsonContent([
					'status' => false,
					'message' => "Please give the details and then login"
			] );
		}
		$validation = new Validation ();
		$validation->add ( 'first_name', new PresenceOf ( [ 
				'message' => 'First name is required' 
		] ) );
		$validation->add ( 'last_name', new PresenceOf ( [ 
				'message' => 'Last name is required' 
		] ) );
		$validation->add ( 'email', new PresenceOf ( [
				'message' => 'Email is required'
		] ) );
		$validation->add ( 'email', new Email ( [
				'message' => 'Please give the valid email'
		] ) );
		
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent([ 
					'status' => false,
					'message' => $result
			] );
		}

		// $ldapconn = $this->connection ();
		
		$email = $input_data->email;
		$userexist=Users::findFirstByemail($email);
		if(!empty($userexist)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "This email address is already in use. Please enter a different one."
			] );
		}
		$code = $this -> generate_random_letters(); 
		$user_id = $this->usercreate ( $input_data );
		try{
			$accessToken = $this-> azureAdToken();
			
			$graph = new Graph();
			$graph->setAccessToken($accessToken);
			$parts=explode('@',$email);
			$userPrincipalName = $parts[0] . '@nidarachildren.com';
			// $input_data = $this->request->getJsonRawBody();
			// $data = array();
			$password['forceChangePasswordNextSignIn'] = false;
			$password['password'] = $code;
			$uservala['accountEnabled'] = true;
			$uservala['givenName'] = $user_id;
			$uservala['displayName'] = $input_data -> first_name . ' ' . $input_data -> last_name ;
			$uservala['mailNickname'] = $input_data -> first_name;
			$uservala['userPrincipalName'] = $userPrincipalName;
			$uservala['passwordProfile'] = $password;
			$data = $uservala;
			$user = $graph
			->createRequest("POST", "/users")
			->attachBody($data)
			->setReturnType(Model\User::class)
			->execute();
			if($user){
				$topset = file_get_contents('../public/email/topmail.html');
				$bottomset = file_get_contents('../public/email/bottom.html');
				$user_infoval = Users::findFirstByemail($input_data->email);
				$get  = json_decode(json_encode($user),true);
				$this -> adduseradinfo($user_infoval -> id, $get['id'], $get['userPrincipalName'] );
				$emailcontant = '<div class="page-title">
									<h3>WELCOME TO NIDARA-CHILDREN</h3>
									</div>
									<div class="page-content">
									
									<p>Dear ' . $user_infoval->first_name . ' ,</p> 

									<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

									<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

									<p> Step 1: Sign in and complete registration using the credentials below </p>

									<p> Email address: ' . $user_infoval->email . ' </p>
									<p> Temporary password: ' . $code . '  </p>

									<p> Step 2: Complete the Early Childhood Questionnaire 
									(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

									<p> Step 3: Start program </p>

									<p> We look forward to helping you give your child the best start in life.. </p>

									</div>
									<div class="click-but">
									<div class="but">
										<a href="' . $this
											->config->weburl . '/signin"> <span>SIGN IN</span> </a>
									</div>
									</div>';
				$mail = new PHPMailer(true);
				$mail->isSMTP(); // Set mailer to use SMTP
				$mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'contact@haselfre.com'; // SMTP username
				$mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587; // TCP port to connect to
				$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
				$mail->addAddress('savariraju@haselfre.com', ''); // Add a recipient
				// Name is optional
				$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				$mail->isHTML(true); // Set email format to HTML
				$mail->Subject = 'Welcome to Nidara Children ';
				$mail->Body = $topset . '' . $emailcontant . '' . $bottomset;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				if (!$mail->send())
				{
					return $this
						->response
						->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
				}
				else
				{
					return $this
						->response
						->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
				}
			} else {
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $user
				] );
			}
		}
		catch (\Exception $e){
			echo json_encode(array(
				"status" => false,
				"message" => "Access denied.",
				"error" => $e->getMessage()
			));
		}
		
	}

	/**
	 * Users create in wp site
	 */
	function wp_users_create($input_data){
		$parameters = array (
				'first_name' => $input_data->first_name,
				'last_name' => $input_data->last_name,
				'email' => $input_data->email,
				'password' => md5 ( $input_data->password ),
				'api_key' => $this->config->wpapi_key
		);
		$inputparams = json_encode ( $parameters );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $this->config->wpurl.'data-account.php');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
              'Content-Type: application/json',                    
              'Content-Length: ' . strlen($inputparams)
               ]
        );		
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS,$inputparams );
		$contents = curl_exec ( $ch );
		curl_close ( $ch );
	}

	/**
	 * Create user
	 * @param object $input_data
	 */
	function usercreate($input_data) {
		$user = new Users ();
		$user->id = $this->usersid->getNewId("users");
		$user->parent_type = $input_data->parent_type;
		$user->first_name = $input_data->first_name;
		$user->last_name = $input_data->last_name;
		if($input_data->parent_type == 'doctor'){
			$user->user_type = 'doctor';
		}
		else if(($input_data->parent_type == 'development_officer') || ($input_data->parent_type == 'dev_enroll_officer') || ($input_data->parent_type == 'coordinator'))
		{
			$user->user_type = $input_data->parent_type;
		}
		else{
			$user->user_type = 'parent';
		}
		$user->email = $input_data->email;
		if(empty($input_data->photo)){
			$user->photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/icon.png';
		} else {
			$user->photo = $input_data->photo;
		}
		
		$user->mobile = $input_data->mobile;
		$user->created_at = date ( 'Y-m-d H:i:s' );
		$user->created_by = $user->id;
		$user->status = $input_data->status;
		$user->act_status = $input_data->act_status;
		$user->modified_at = date ( 'Y-m-d H:i:s' );
		$user->save();
		if($input_data->parent_type == 'doctor'){
			$doctor_code = new DoctorCode();
			$doctor_code->user_id = $user->id;
			$doctor_code->doctor_code = 'NIDARA-DOCTOR-'.$user->id;
			$doctor_code-> save();
			
			$qualification = new DoctorInfo();
			$qualification -> register_no = $input_data->registration_number;
			$qualification -> qualification = $input_data->qualification;
			$qualification -> user_id = $user->id;
			$qualification -> save();
		}
		$parents_map = new ParentsMappingProfiles ();
		$parents_map->id = $this->parentsidgen->getNewId("parentsmap");
		$parents_map->primary_parents_id = $user->id;
		$parents_map->primary_parent_type = $input_data->parent_type;
		$parents_map->save ();

		if(($user->user_type == 'development_officer') || ($user->user_type == 'dev_enroll_officer') || ($user->user_type == 'coordinator'))
		{
		$collection = new SalesmanAddress();
		}
		else
		{
		$collection = new UserAddress();
		$collection->id = $this->parentsidgen->getNewId ( "address" );

		}

		$collection->id = $this->parentsidgen->getNewId ( "address" );
		$collection-> user_id = $user->id;
		$collection->address_1 = $input_data->address_1;
		$collection->address_2 = $input_data->address_2;
		$collection->city = $input_data->city;
		$collection->state = $input_data->state;
		$collection->country = $input_data->country;
		$collection->post_code = $input_data->postcode;
		$collection->created_at = date ( 'Y-m-d H:i:s' );
		$collection->created_by = $user->id;
		$collection->modified_at = date ( 'Y-m-d H:i:s' );
		$collection->save();


		$doctor_map = new DoctorParentMap();
		$doctor_map ->id = $this->parentsidgen->getNewId ( "doctor-map" );
		$doctor_map ->user_id = $user->id;
		$doctor_map ->doctor_code = $input_data->offer_code;
		$doctor_map ->created_at = date ( 'Y-m-d H:i:s' );
		$doctor_map ->save();	
			
		return $user->id;
	}
	
	public function uaseraddress(){
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data)){
			return $this->response->setJsonContent([
					'status' => false,
					'message' => "Please give the details and then login"
			] );
		}	
	}
	
	/**
	 * This function using to Subject information edit
	 */
	public function forgotpassword() {
		// $ldapconn = $this->connection ();
		$input_data = $this->request->getJsonRawBody ();
		if(empty($input_data->username)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the username' 
			] );
		}
		$userinfo = Users::findFirstByemail($input_data->username);
		$authentication = new AuthenticationController ();
		$token = ( string ) $authentication->tokengenerate ( $userinfo -> id, $input_data->username );
		
		// $baseurl = ''. $this->config->weburl .'/';
		$this->tokenadd ( $token, $userinfo->id );
		$changeurl = $this->config->weburl . '/resetpassword/?token=' . $token;
		$user_info = Users::findFirstByemail($input_data->username);
		
		$emailcontant = '<div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
          <h3 style="font-weight: 500;">RESET YOUR NIDARA-CHILDREN PASSWORD&hellip;</h3>
        </div>
        <div class="sub-mail-cont" style="width: 100%; float: left;">
          <span>Hi <span class="first-name" style="text-transform: capitalize;">'. $user_info->first_name .' '. $user_info->last_name .'</span>, </span>
          <br /><p style="line-height: 18px;">Someone recently requested a password change for your Nidara Children account. If this was you, you can set a new password here:
        </p>
      </div>
      <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
        <p style="line-height: 18px;">
        <a class="sub-but" href="'. $changeurl .'" style="text-decoration: none; color: #fff; padding: 10px 50px; background: #AFD2DB;"><b>RESET PASSWORD
          </b></a>
        </p>
      </div>
      
      <div class="sub-mail-cont" style="width: 100%; float: left;">
        
        <br /><p style="line-height: 18px;">If you don`t want to change your password or didn`t request this, just ignore and delete this message.
      </p>
      <p style="line-height: 18px;">
      To keep your account secure, please don`t forward this email to anyone
    </p>
  </div>';
		
		$topset = file_get_contents('../public/email/topmail.html');
		$bottomset = file_get_contents('../public/email/bottom.html');
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'contact@haselfre.com';                 // SMTP username
		$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
		$mail->addAddress($input_data->username, '');     // Add a recipient
																// Name is optional
		$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'RESET YOUR NIDARA-CHILDREN PASSWORD';
		$mail->Body    = $topset . '' . $emailcontant . '' . $bottomset;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		if(!$mail->send()) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
			] );
			
		} else {
		
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Please check your email for password reset instructions.' 
			] );
		}
		
	}


	
	
	public function tokenSend(){
		$input_data = $this->request->getJsonRawBody ();

		$topset = file_get_contents('../public/email/topmail.html');
		$bottomset = file_get_contents('../public/email/bottom.html');

		$emailcontant = '<div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
		<h3 style="font-weight: 500;">WELCOME TO NIDARA-CHILDREN</h3>
	  </div>
	  <div class="sub-mail-cont" style="width: 100%;">
	   <span>Hi <span class="first-name" style="text-transform: capitalize;">'. $input_data->user_first_name .' '. $input_data->user_last_name .'</span>, </span>
	   <br /><p style="line-height: 18px;">Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.
	   </p>
	   <p style="line-height: 18px;">Hope you find our premium system helpful in raising your child.</p>
	  </div>
	   <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
	   <p style="line-height: 18px;">
	   <a class="sub-but" href="'. $this->config->weburl .'/child-profileid-register?token='. $input_data->token .'" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>ACTIVATE FREE TRIAL</b></a>
		</p>
	  </div>';

		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'contact@haselfre.com';                 // SMTP username
		$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
		$mail->addAddress($input_data->email, '');     // Add a recipient
																// Name is optional
		$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Free Trial Activation';

		$mail->Body    = $topset . '' . $emailcontant . '' . $bottomset;

		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
			] );
			
		} else {
		
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message has been sent' 
			] );
		}
	}
	
	
		public function productmailsend(){
		$input_data = $this->request->getJsonRawBody ();
		$mail = new PHPMailer;

		$topset = file_get_contents('../public/email/topmail.html');
		$bottomset = file_get_contents('../public/email/bottom.html');

		$ncproduct = $this->modelsManager->createBuilder ()->columns ( array (
			'NCProductPricing.id as ids',
			'NCProductPricing.product_type as product_type',
			'NCProduct.product_name as product_name',
			'NCProduct.product_img as product_img',
			'NCProductPricing.product_price as product_price',
			'SUM (NCProductPricing.product_price) as subtotal',
			'COUNT (NCOrderProductList.product_id) as item',
			'COUNT (NCOrderProductList.product_id) as qty',
		))->from("NCOrderList")
		->leftjoin('NCOrderProductList','NCOrderProductList.order_id = NCOrderList.id')
		->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
		->leftjoin('NCProductPricing','NCOrderProductList.product_id = NCProductPricing.id')
		->leftjoin('NCProduct','NCProductPricing.product_id = NCProduct.id')
		->inwhere("NCOrderList.user_id",array($input_data->user_id))
		->inwhere("NCOrderList.order_id",array($input_data->order_id))
		->groupBy("NCOrderProductList.product_id")
		->getQuery ()->execute ();
		$product = '';
		foreach($ncproduct as $value){
			$product .= '<div class="product-details">';
			$product .= '<div class="product-img">';
			$product .= '<img src="';
			$product .= $value->product_img;
			$product .= '">';
			$product .= '</div>';
			$product .= '<div class="product-cont">';
			$product .= '<h4>';
			$product .= $value->product_name;
			$product .= '</h4>';
			$product .= '<p>Rs. ';
			$product .= $value->product_price;
			$product .= ' / month for 12 months</p>';
			$product .= '<p><span>Billing Cycle: Billed </span> <span style="float:right;padding-right:20px">Total:  Rs.';
			$product .= $value->product_price;
			$product .= '</span></p>';
			$product .= '</div>';
		}
		$product .= '';

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'contact@haselfre.com';                 // SMTP username
		$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
		$mail->addAddress($input_data->email, '');     // Add a recipient
																// Name is optional
		$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

		$mail->isHTML(true);                                  // Set email format to HTML
		
		$mail->Subject = 'THANK YOU FOR YOUR PURCHASE AT NIDARA-CHILDREN';
		
				
		$mail->Body    = $topset . '' . $product . '' . $bottomset;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
			] );
			
		} else {
		
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message has been sent' 
			] );
		}
	}
	
	
	
	
	public function posttokenSend(){
		$input_data = $this->request->getJsonRawBody ();

		$topset = file_get_contents('../public/email/topmail.html');
		$bottomset = file_get_contents('../public/email/bottom.html');

		$emailcontant = '<div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
		<h3 style="font-weight: 500;">WELCOME TO NIDARA-CHILDREN</h3>
	  </div>
	  <div class="sub-mail-cont" style="width: 100%;">
	   <span>Hi <span class="first-name" style="text-transform: capitalize;">'. $input_data->user_first_name .' '. $input_data->user_last_name .'</span>, </span>
	   <br /><p style="line-height: 18px;">Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.
	   </p>
	   <p style="line-height: 18px;">Hope you find our premium system helpful in raising your child.</p>
	  </div>
	   <div class="sub-mail-but" style="width: 100%; text-align: center; padding-top: 30px; float: left;">
	   <p style="line-height: 18px;">
	   <a class="sub-but" href="'. $this->config->weburl .'/child-profileid-register?token='. $input_data->token .'" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;"><b>START USING NIDARA-CHILDREN</b></a>
		</p>
	  </div>';

		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'contact@haselfre.com';                 // SMTP username
		$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
		$mail->addAddress($input_data->email, '');     // Add a recipient
																// Name is optional
		$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'THANK YOU FOR YOUR PURCHASE AT NIDARA-CHILDREN';
		$mail->Body    = $topset . '' . $emailcontant . '' . $bottomset;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
			] );
			
		} else {
		
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Message has been sent' 
			] );
		}
	}
	
	
	public function successtokensend(){
		$input_data = $this->request->getJsonRawBody ();
		$order_id = isset ($input_data->order_id) ? $input_data->order_id : '';
		$collection = $this->modelsManager->createBuilder ()->columns ( array (
			'NCOrderList.user_id as user_id',
			'NCOrderList.order_id as order_ids',
		))->from("NCOrderList")
		->leftjoin('NCOrderStatus','NCOrderStatus.order_id = NCOrderList.id')
		->inwhere("NCOrderList.order_id",array($order_id))
		->inwhere("NCOrderStatus.status",array('Success'))
		->getQuery ()->execute ();
		if(count($collection) === 0){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid User main' 
			] );
		}
		else{
			foreach($collection as $valid){
				
			}
			$userval = Users::findFirstByid($valid->user_id);
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $userval
			] );
		}
	}
	
	
	/**
	 * Change password
	 */
	function changepassword(){
		
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		$authentication = new AuthenticationController ();
		$validatetoken = $authentication->validatetoken ( $headers ['Token'] );
		$userdetail = $authentication->getuidtoken ( $headers ['Token'] );
		if (empty($validatetoken)) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid User' 
			] );
		}
		if(empty($input_data)){
			return $this->response->setJsonContent([
					'status' => false,
					'message' => "Please fill the form"
			] );
		}
		$validation = new Validation ();
		$validation->add ( 'oldpassword', new PresenceOf ( [
				'message' => 'Old password is required'
		] ) );
		$validation->add ( 'password', new PresenceOf ( [
				'message' => 'Password is required'
		] ) );
		$validation->add ( 'confirmpassword', new PresenceOf ( [
				'message' => 'Confirm Password is required'
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
				
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent([
					'status' => false,
					'message' => $result
			] );
		}
		if ($input_data->password != $input_data->confirmpassword) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => "Password doesnot match"
			] );
		}
		$ldapconn = $this->connection ();
		$ldapbind = ldap_bind($ldapconn, self::ldaprdn, self::ldappass);
		if ($ldapbind) {
			$search = "uid=" . $userdetail['username'];
			$user = ldap_search ( $ldapconn, self::ldaprdn, $search );
			$userinfo = ldap_get_entries ( $ldapconn, $user );
			if (! empty ( $userinfo ['count'] )) {
				$ldapBindUser = ldap_bind ( $ldapconn, $userinfo [0] ['dn'], md5 ( $input_data->oldpassword ) );
				if (empty($ldapBindUser)) {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Invalid old password' 
					] );
				}
				$userdata ['userpassword'] = md5 ( $input_data->password );
				$result = ldap_mod_replace ( $ldapconn, 'cn=' . $userdetail['username']. ',ou=users,' . self::ldaprdn, $userdata );
				
				if ($result) {
					$user_info = Users::findFirstByemail($userdetail['username']);
					if($user_info -> user_type == 'parent'){
						$user_info -> status = '4';
					} else {
						$user_info -> status = '1';
					}
					
					$user_info -> save();
						$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'contact@haselfre.com';                 // SMTP username
						$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
						$mail->addAddress($user_info->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Reset Your Nidara-Children Password';
						
						$mail->Body    = '
										<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
           <div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
             <h3 style="font-weight: 500;">YOUR PASSWORD HAS BEEN RESET&hellip;</h3>
           </div>
           <div class="sub-mail-cont" style="width: 100%;">
             <span>Hi <span class="first-name" style="text-transform: capitalize;">'. $user_info->first_name .' '. $user_info->last_name .'</span>, </span>
             <br /><p style="line-height: 18px;">The password for your Nidara-Children account ('. $user_info->email .') has been successfully reset.
             If you did not make this change or you believe an unauthorized person has accessed your account, go to www.nidarachildren.com to reset your password without delay.
           </p>
            </div>
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
           <br /><span>You are receiving this email because you opted in at our website.
            </span>
            
            <br /><span><a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a></span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>

						';
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						if(!$mail->send()) {
							return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
							
						} else {
						
							return $this->response->setJsonContent ( [ 
									'status' => true,
									'message' => 'Message has been sent' 
							] );
						}
					
					
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => 'Password changed successfully' 
					] );
				} else {
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Couldnot change the password' 
					] );
				}
			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid username or password' 
				] );
			}
		} else {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'LDAP bind failed' 
			] );
		}
	}
	/**
	 * Check the login credential and create token from the JWT
	 */
	public function logincheck() {
		// $ldapconn = $this->connection ();
		$input_data = $this->request->getJsonRawBody ();
		if(empty($input_data)){
			return $this->response->setJsonContent([
					'status' => false,
					'message' => "Please give the details and then login"
			] );
		}
		$validation = new Validation ();
		$validation->add ( 'username', new PresenceOf ( [ 
				'message' => 'Username is required' 
		] ) );
		$validation->add ( 'password', new PresenceOf ( [ 
				'message' => 'Password is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent([ 
					'status' => false,
					'message' => $result
			] );
		}
		$password = $input_data->password;
		$parts=explode('@',$input_data->username);
		$userPrincipalName = $parts[0] . '@nidarachildren.com';
		$tenantId = $this->config->tenantId;
		$clientId = $this->config->clientId;
		$clientSecret = $this->config->clientSecret;
		$guzzle = new \GuzzleHttp\Client();
		$url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
		try{
			$token = json_decode($guzzle->post($url, [
				'form_params' => [
					'client_id' => $clientId,
					'client_secret' => $clientSecret,
					'scope' => 'user.read openid profile offline_access',
					'username' => $userPrincipalName,
					'password' => $password,
					'grant_type' => 'password',
				],
			])->getBody()->getContents());
			$accessToken = $token->access_token;
			
			$graph = new Graph();
			$graph->setAccessToken($accessToken);
			
			$loginstatus = $graph->createRequest("GET", "/me")->setReturnType(Model\User::class)->execute();
			$getinfo = array();
			// echo $user;
			$getinfo[] = $loginstatus;
			$get  = json_decode(json_encode($loginstatus),true);

			// $password = md5 ( $input_data->password );
			// $search = "uid=" . $input_data->username;
			// $user = ldap_search ( $ldapconn, self::ldaprdn, $search );
			// $userinfo = ldap_get_entries ( $ldapconn, $user );
			if ($loginstatus) {
					$userinfo = Users::findFirstByid($get['givenName']);
					$authentication = new AuthenticationController ();
					$token = ( string ) $authentication->tokengenerate ( $userinfo->id, $input_data->username );
					$this->tokenadd ( $token, $userinfo->id );
					$user = Users::findFirstByid ( $userinfo->id);
					/*return $this->response->setJsonContent ( [
							'status' => true,
							'token' => $token,
							'is_active'=> $user->status,
							'message' => 'Login successful'
					] );*/
					if($user->status === '1' || $user->status === '2' || $user->status === '3'){
						return $this->response->setJsonContent ( [
							'status' => true,
							'token' => $token,
							'is_active'=> $user->status,
							'users_id'=> $user->id,
							'message' => 'Login successful'
						] );
					} 
					else if($user->status > 3){
						$userinfo = NcSalesmanParentMap::findFirstByuser_id($user->id );
						if(!$userinfo){
							$curdate=date('Y-m-d');
							$datecheck=$this->modelsManager->createBuilder ()->columns ( array (
									'SchoolRegistrationDate.id'
								) )->from ('SchoolRegistrationDate')
							->leftjoin('SchoolParentMap','SchoolParentMap.school_id=SchoolRegistrationDate.school_id')
							->where("SchoolRegistrationDate.status=1 AND SchoolParentMap.user_id =". $user->id ." AND SchoolRegistrationDate.start_date <='".$curdate."'AND SchoolRegistrationDate.end_date >='".$curdate."'")
							->getQuery ()->execute ();
							if(count($datecheck) <= 0 ){
								return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' =>"Please contact your school manager or please <a style='color: #a94442;' href='https://www.nidarachildren.com/contact-us'> contact us </a>"
								] );
							} else {
								return $this->response->setJsonContent ( [
									'status' => true,
									'data' => $datecheck,
									'token' => $token,
									'is_active'=> $user->status,
									'users_id'=> $user->id,
									'message' => 'Login successful'
								] );
							}
						} else {
							return $this->response->setJsonContent ( [
									'status' => true,
									'data' => $datecheck,
									'token' => $token,
									'is_active'=> $user->status,
									'users_id'=> $user->id,
									'message' => 'Login successful'
								] );
						}
					}
					else if($user->status === '6'){
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Your account has been deactivated. Please contact us for further assistance.' 
						] );
					}
					else{
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Invalid username or password and status' 
						] );
					}
			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid username and password' 
				] );
			}
		} 
		catch (\Exception $e){
			echo json_encode(array(
				'status' => false,
				"message" => "Invalid username and password",
				"error" => $e->getMessage()
			));
		}
	}


	public function adduseradinfo($user_id, $id, $username){
		$collection = new UserAzureAdInfo();
		$collection -> user_id = $user_id;
		$collection -> ad_id = $id;
		$collection -> user_name = $username;
		$collection -> status = 1;
		$collection -> save();
	}
	
	
	/**
	 * Add token info	
	 * @param string $token        	
	 * @param integer $users_id        	
	 */
	public function tokenadd($token, $users_id) {
		$token_data = TokenUsers::findFirstByusers_id ( $users_id );
		if (empty ( $token_data )) {
			$token_data = new TokenUsers ();
		//	$token_data->id = $this->usersid->getNewId ( "token" );
		}
		$token_data->token = $token;
		$token_data->users_id = $users_id;
		$token_data->last_modified_at = date("Y-m-d H:i:s");
		$token_data->save ();
	}
	/**
	 * Check token
	 * @param token
	 * @return array
	 */
	function tokencheck() {
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		$authentication = new AuthenticationController ();
		$validatetoken = $authentication->validatetoken ( $headers ['Token'] );
		$token_users = TokenUsers::findFirstBytoken ( $headers ['Token'] );
		// if (empty ( $validatetoken )) {
		// 	$useractive = $this->useractive ( $headers ['Token'] );
		// 	if (empty ( $useractive['success'] )) {
		// 		if (! empty ( $token_users )) {
		// 		  $token_users->delete ();
		// 		}
		// 		return $this->response->setJsonContent ( [ 
		// 				'status' => false,
		// 				'message' => 'Invalid user' 
		// 		] );
		// 	} else {
		// 		return $this->response->setJsonContent ( [ 
		// 				'status' => true,
		// 				'message' => 'Valid User',
		// 				"refresh_token" => $useractive['refresh_token'] 
		// 		] );
		// 	}
		// 	return $this->response->setJsonContent ( [ 
		// 			'status' => false,
		// 			'message' => 'Invalid user' 
		// 	] );
		// }
		// if (! empty ( $token_users )) {
		// 	$token_users->last_modified_at = date ( "Y-m-d H:i:s" );
		// 	$token_users->save ();
		// }
		if ($validatetoken && ! empty ( $token_users )) {
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Valid User' 
			] );
		} else {
			// return $this->response->setJsonContent ( [ 
			// 		'status' => false,
			// 		'message' => 'Invalid User' 
			// ] );
		}
	}
	public function useractive($token){
		date_default_timezone_set('Asia/Kolkata');
		$token_users = TokenUsers::findFirstBytoken ( $token );
		$seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime( $token_users->last_modified_at );
		$currentTime = new \DateTime ( 'now' );
		$last_login_time= new \DateTime ( $token_users->last_modified_at );
		$interval = $currentTime->diff ( $last_login_time );
		$hours = $interval->format ( '%h' );
		$minutes = $interval->format ( '%i' );
		if (empty($hours) && $mins < 60) {
			$authentication = new AuthenticationController ();
			$userinfo=$authentication->getuidtoken($token);
			$tokennew = ( string ) $authentication->tokengenerate ( $userinfo ['uid'], $userinfo ['username'] );
			$token_users->token = $tokennew;
			$token_users->last_modified_at = date ( "Y-m-d H:i:s" );
			$token_users->save ();
			return array (
					"success" => true,
					"refresh_token" => $tokennew 
			);
			
		} else {
			return array (
					"success" => false
			);
		}
	}
/*	public function sesConfiguration(){
		$profile = 'default';
		$path = APP_PATH . '/config/credentials.ini';
		
		$provider = CredentialProvider::ini ( $profile, $path );
		$provider = CredentialProvider::memoize ( $provider );
		// Instantiate an Amazon S3 client.
		$ses = SesClient::factory(array(
				'version' => 'latest',
				'region'  => 'us-east-1',
				'credentials' => $provider
		));
		return $ses;
	}*/
	/**
	 * Get profile info by token
	 */
	public function getuserinfobytoken() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Please give the token'
			] );
		}
		$authentication = new AuthenticationController ();
		$tokencheck=$authentication->validatetoken( $headers ['Token'] );
		$user = $authentication->getuidtoken ( $headers ['Token'] );
		// return $this->response->setJsonContent ( [
		// 		'status' => false,
		// 		'message' => $user
		// ] );
		$userdata = Users::findFirst ( $user ['uid'] )->toArray();
		$token_users = TokenUsers::findFirstBytoken($headers ['Token']);
		$userAddressarray = array();
		if ($userdata && ! empty ( $token_users )) {
			$getvalue = $this->modelsManager->createBuilder ()->columns ( array (
				'Users.id as id',
				'Users.parent_type as parent_type',
				'Users.first_name as first_name',
				'Users.last_name as last_name',
				'Users.email as email',
				'Users.mobile as mobile',
				'Users.occupation as occupation',
				'Users.company_name as company_name',
				'Users.created_by as created_by',
				'Users.modified_at as modified_at',
				'UserAddress.address_1 as address_1',
				'UserAddress.address_2 as address_2',
				'UserAddress.city as city',
				'UserAddress.state as state',
				'UserAddress.country as country',
				'UserAddress.post_code as post_code',
			))->from("Users")
			->leftjoin('UserAddress','UserAddress.user_id = Users.id')
			->inwhere('Users.id',array($userdata['id']))
			->getQuery()->execute ();
			return $this->response->setJsonContent ( [
					'status' => true,
					'user_info' => $userdata,
					'useraddress' => $getvalue,
			] );
		} else {
			return $this->response->setJsonContent ( [
					'status' => false,
					'message' => 'Invalid User'
			] );
		}
	}

	/**
	 * Check the password is to get into the parent dashboard
	 */
	public function parentvalidate() {
		// $ldapconn = $this->connection ();
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		if (empty ( $input_data->password )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the password' 
			] );
		}
		$authentication = new AuthenticationController ();
		$tokencheck=$authentication->validatetoken( $headers ['Token'] );
		if(empty($tokencheck)){
		return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid user' 
				] );
		}
		$user = $authentication->getuidtoken ( $headers ['Token'] );
		$userinfocheck = UserAzureAdInfo::findFirstByuser_id($user['uid']);
		$password = $input_data->password;
		$parts=explode('@',$input_data->username);
		$userPrincipalName = $parts[0] . '@nidarachildren.com';
		$tenantId = $this->config->tenantId;
		$clientId = $this->config->clientId;
		$clientSecret = $this->config->clientSecret;
		$guzzle = new \GuzzleHttp\Client();
		$url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
		try{
			$token = json_decode($guzzle->post($url, [
				'form_params' => [
					'client_id' => $clientId,
					'client_secret' => $clientSecret,
					'scope' => 'user.read openid profile offline_access',
					'username' => $userPrincipalName,
					'password' => $password,
					'grant_type' => 'password',
				],
			])->getBody()->getContents());
			$accessToken = $token->access_token;
			
			$graph = new Graph();
			$graph->setAccessToken($accessToken);
			
			$loginstatus = $graph->createRequest("GET", "/me")->setReturnType(Model\User::class)->execute();
			$getinfo = array();
			// echo $user;
			$getinfo[] = $loginstatus;
			$get  = json_decode(json_encode($loginstatus),true);

			// $password = md5 ( $input_data->password );
			// $search = "uid=" . $input_data->username;
			// $user = ldap_search ( $ldapconn, self::ldaprdn, $search );
			// $userinfo = ldap_get_entries ( $ldapconn, $user );
			if ($loginstatus) {
					$userinfo = Users::findFirstByid($get['givenName']);
					// $authentication = new AuthenticationController ();
					// $token = ( string ) $authentication->tokengenerate ( $userinfo->id, $input_data->username );
					// $this->tokenadd ( $token, $userinfo->id );
					$user = Users::findFirstByid ( $userinfo->id);
					/*return $this->response->setJsonContent ( [
							'status' => true,
							'token' => $token,
							'is_active'=> $user->status,
							'message' => 'Login successful'
					] );*/
					if($user->status === '1' || $user->status === '2' || $user->status === '3'){
						return $this->response->setJsonContent ( [
							'status' => true,
							'token' => $token,
							'is_active'=> $user->status,
							'users_id'=> $user->id,
							'message' => 'Login successful'
						] );
					} 
					else if($user->status > 3){
						$userinfo = NcSalesmanParentMap::findFirstByuser_id($user->id );
						if(!$userinfo){
							$curdate=date('Y-m-d');
							$datecheck=$this->modelsManager->createBuilder ()->columns ( array (
									'SchoolRegistrationDate.id'
								) )->from ('SchoolRegistrationDate')
							->leftjoin('SchoolParentMap','SchoolParentMap.school_id=SchoolRegistrationDate.school_id')
							->where("SchoolRegistrationDate.status=1 AND SchoolParentMap.user_id =". $user->id ." AND SchoolRegistrationDate.start_date <='".$curdate."'AND SchoolRegistrationDate.end_date >='".$curdate."'")
							->getQuery ()->execute ();
							if(count($datecheck) <= 0 ){
								return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' =>"Please contact your school manager or please <a style='color: #a94442;' href='https://www.nidarachildren.com/contact-us'> contact us </a>"
								] );
							} else {
								return $this->response->setJsonContent ( [
									'status' => true,
									'data' => $datecheck,
									'token' => $token,
									'is_active'=> $user->status,
									'users_id'=> $user->id,
									'message' => 'Login successful'
								] );
							}
						} else {
							return $this->response->setJsonContent ( [
									'status' => true,
									'data' => $datecheck,
									'token' => $token,
									'is_active'=> $user->status,
									'users_id'=> $user->id,
									'message' => 'Login successful'
								] );
						}
					}
					else if($user->status === '6'){
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Your account has been deactivated. Please contact us for further assistance.' 
						] );
					}
					else{
						return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Invalid username or password and status' 
						] );
					}
			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid username and password' 
				] );
			}
		} 
		catch (\Exception $e){
			echo json_encode(array(
				'status' => false,
				"message" => "Invalid username and password",
				"error" => $e->getMessage()
			));
		}
		
	}
	
	
	public function childdeactive(){
		$ldapconn = $this->connection ();
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		if (empty ( $input_data->password )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the password' 
			] );
		}
		$kid_id = isset ( $input_data->nidara_kid_profile_id ) ? $input_data->nidara_kid_profile_id : '';
		if (empty ( $kid_id )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Nidara kid profile id is empty' 
			] );
		}
		$authentication = new AuthenticationController ();
		$tokencheck=$authentication->validatetoken( $headers ['Token'] );
		if(empty($tokencheck)){
		return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid user' 
				] );
		}
		$user = $authentication->getuidtoken ( $headers ['Token'] );
		$search = "uid=" . $user['username'];
		$usersearch = ldap_search ( $ldapconn, self::ldaprdn, $search );
		$userinfo = ldap_get_entries ( $ldapconn, $usersearch );
		if (! empty ( $userinfo ['count'] )) {
			$ldapBindUser = ldap_bind ( $ldapconn, $userinfo [0] ['dn'], md5 ( $input_data->password ) );
			if ($ldapBindUser) {
				$user_id = $input_data->user_id;
				$account = new ChildDeactive ();
				$account->id = $this->parentsidgen->getNewId ( "child-deactivation" );
				$account->elaboration = $input_data->elaboration;
				$account->users_id = $user->id;
				$account->child_id = $kid_id;
				$account->why_are_you_leaving_id = $input_data->why_are_you_leaving_id;
				$account->save();
				$childinfo = NidaraKidProfile::findFirstByid($kid_id);
				/*$childinfo->first_name = md5($childinfo->first_name);
				$childinfo->middle_name = md5($childinfo->middle_name);
				$childinfo->last_name = md5($childinfo->last_name);
				$childinfo->grade = md5($childinfo->grade);
				$childinfo->child_photo = md5($childinfo->child_photo);
				$childinfo->child_avatar = md5($childinfo->child_avatar); */
				$childinfo->status = 6;
				$childinfo->save();
				if($childinfo->save()){
					return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Your account is requested for deactivation' 
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Deactivation Failed' 
					] );
				}

			} else {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Invalid password' 
				] );
			}
		} else {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid password' 
			] );
		}
	}

     	/**
	 * Reset password
	 */
	public function resetpassword() {
		$input_data = $this->request->getJsonRawBody ();
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "Please give the token" 
			] );
		}
		$authentication = new AuthenticationController ();
		$validatetoken = $authentication->validatetoken ( $headers ['Token'] );
		$userdetail = $authentication->getuidtoken ( $headers ['Token'] );
		if (empty ( $validatetoken )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid User' 
			] );
		}
		if (empty ( $input_data )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "Please fill the form" 
			] );
		}
		$validation = new Validation ();
		$validation->add ( 'password', new PresenceOf ( [ 
				'message' => 'Password is required' 
		] ) );
		$validation->add ( 'confirmpassword', new PresenceOf ( [ 
				'message' => 'Confirm Password is required' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'data' => $result 
			] );
		}
		if ($input_data->password != $input_data->confirmpassword) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "Password doesnot match" 
			] );
		}

		$getuserInfo = UserAzureAdInfo::findFirstByuser_id($userdetail['uid']);
		if($getuserInfo){
			$accessToken = $this-> azureAdToken();
		
			$graph = new Graph();
			$graph->setAccessToken($accessToken);

			
			// $url = 'https://graph.microsoft.com/beta/users/'. $getuserInfo -> user_name .'/authentication/passwordMethods/' . $getuserInfo -> ad_id . '/resetPassword';
			// $token = json_decode($guzzle->post($url, [
			// 	'form_params' => [
			// 		'newPassword' =>  $input_data->password
			// 	],
			// ])->getBody()->getContents());


			$password['forceChangePasswordNextSignIn'] = false;
			$password['password'] = $input_data->password;
			// $passwordchec['']
			$uservala['passwordProfile'] = $password;
			$uservala['passwordPolicies'] = "DisableStrongPassword";
			$data = $uservala;

			
			// return $this->response->setJsonContent ( [ 
			// 	'status' => true,
			// 	'message' => $data
			// ] );
			$user = $graph
			->createRequest("PATCH", "/users/". $getuserInfo -> user_name ."")
			->attachBody($data)
			->setReturnType(Model\User::class)
			->execute();
			if($user){
				$user_info = Users::findFirstByemail($userdetail['username']);
				$topset = file_get_contents('../public/email/topmail.html');
				$bottomset = file_get_contents('../public/email/bottom.html');
						$emailcontant = '<div class="main-title" style="text-align: center; color: #aed7d3; float: left; width: 100%;">
						<h3 style="font-weight: 500;">YOUR PASSWORD HAS BEEN RESET&hellip;</h3>
					  </div>
					  <div class="sub-mail-cont" style="width: 100%;">
						<span>Hi <span class="first-name" style="text-transform: capitalize;">'. $user_info->first_name .' '. $user_info->last_name .'</span>, </span>
						<br /><p style="line-height: 18px;">The password for your Nidara-Children account ('. $user_info->email .') has been successfully reset.
						If you did not make this change or you believe an unauthorized person has accessed your account, go to www.nidarachildren.com to reset your password without delay.
					  </p>
					   </div>';

						$mail = new PHPMailer;

						//$mail->SMTPDebug = 3;                               // Enable verbose debug output

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'contact@haselfre.com';                 // SMTP username
						$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
						$mail->addAddress($user_info->email, '');     // Add a recipient
																				// Name is optional
						$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

						//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'RESET YOUR NIDARA-CHILDREN PASSWORD\85';
						
						$mail->Body    = $topset . '' . $emailcontant . '' . $bottomset;
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						if(!$mail->send()) {
							return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
							] );
							
						} else {
						
							return $this->response->setJsonContent ( [ 
									'status' => true,
									'message' => 'Your password has been reset succesfully.' 
							] );
						}
				
				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'Password changed successfully' 
				] );
			} else {
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Password cout not changed successfully' 
			] );
			}
				
			} else {
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invelide user' 
			] );
			}
			
		
	}

	/**
	 * Logout
	 */
	public function logout() {
		$headers = $this->request->getHeaders ();
		if (empty ( $headers ['Token'] )) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give the token' 
			] );
		}
		$token = TokenUsers::findFirstBytoken($headers ['Token']);	
		if(empty($token)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Invalid token' 
			] );
		}
		$authentication = new AuthenticationController ();
		$expiretoken = $authentication->validatetoken ( $headers ['Token'],'logout' );	
		if ($token->delete ()) {
			return $this->response->setJsonContent ([ 
					'status' => true,
					'message' => 'User logout successfully' 
			]);
		} else {
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Cannot logout' 
			]);
		}
	}

	public function outofindiamail(){
		$input_data = $this->request->getJsonRawBody ();
		if($input_data->outofindia == 1){
			$title = 'Out of india notification';
		} else {
			$title = 'Customer Enquiry';
		}
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'contact@haselfre.com';                 // SMTP username
		$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		
		$mail->setFrom($input_data->email, '');     // Add a recipient
		$mail->addAddress('customersupport@nidarachildren.com', 'Nidara-Children');
																// Name is optional
		$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		// $mail->Subject = 'NIDARA-CHILDREN NOTIFY ME';
		$mail->Subject = 'Nidara-Children Notify Me';
		$mail->Body    = '
						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html>
							  <head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8">
								
							  <style type="text/css">
									body,td{
										font-family:verdana,geneva;
										font-size:12px;
									}
									body{
										background:#fff;
										padding:20px;
									}
									.top-img{
										width:100%;
										text-align:center;
										padding-bottom:0;
										font-size:10px;
									}
									.sub-mail-cont{
										width:100%;
									}
									.sub-mail-vr{
										width:580px;
										margin:auto;
										float:none;
									}
									.main-page-mail{
										width:100%;
										float:left;
										padding:20px;
										border:1px solid #999;
									}
									.sub-mail-but{
										width:100%;
										text-align:center;
										padding-top:30px;
										float:left;
									}
									a.sub-but{
										text-decoration:none;
										color:#333;
										padding:10px 50px;
										border:1px solid;
									}
									.sub-but-cont{
										width:100%;
										padding-top:20px;
										float:left;
									}
									.footer{
										width:100%;
										text-align:center;
										font-size:10px;
										padding-top:20px;
										float:left;
									}
									.footer ul{
										list-style:none;
										float:left;
										margin:15px 10px;
										width:100%;
										padding:0;
									}
									.footer ul li{
										display:inline-flex;
										padding-left:5px;
									}
									p{
										line-height:18px;
									}
									.small{
										font-size:11px;
									}
									.main-title{
										text-align:center;
										color:#aed7d3;
										float:left;
										width:100%;
									}
									.main-title h3{
										font-weight:500;
									}
									.first-name{
										text-transform:capitalize;
									}
									.product-img{
										width:20%;
										float:left;
										padding-right:20px;
									}
									.product-img img{
										width:100%;
									}
									.product-cont{
										width:75%;Out of india notification
										float:left;
									}
									.product-details{
										width:100%;
										float:left;
									}
							</style></head>
							  <body bgcolor="#fff">
								<p style="font-weight: bold;">'. $title .'</p>
								<p>Email: ' . $input_data->email . '</p>
							
							</body>
							</html>
		';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Message could not be sent.' ,
					'data' => $mail->ErrorInfo
			]);
			
		} 
		else {
				return $this->response->setJsonContent ([ 
					'status' => true,
					'message' => 'Message has been sent' 
			]);
		}	
	} 
	
	
	public function emailverify(){
		$input_data = $this->request->getJsonRawBody ();
		$collection = EmailVerify::findFirstByencrypt_code($input_data->encrypt_code);
		if(!empty($collection)){
			$collection->status = 1;
			if($collection->save()){
				$user_info = Users::findFirstByemail($collection->email_id);
				if($user_info->status === '3'){
					$user_info->status = 1;
					$user_info->save();
					$emailcontent = '<div class="sub-mail-cont">
												<span>Hi <span class="first-name">'. $user_info->first_name .' '. $user_info->last_name .'</span>, </span>
												<p>Your email address has been verified.  You can start using Nidara-Children now. </p>
											</div>
											<br>
											 <div class="sub-mail-but">
											 <p style="text-align:center;">
											   <a class="sub-but" style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" href="'. $this->config->weburl .'/signin"><b>SIGN IN</b>
											  </a>
											  </p>
											 </div>';
				}
				else if($user_info->status === '2'){
					$emailcontent = '<div class="sub-mail-cont">
												<span>Hi <span class="first-name">'. $user_info->first_name .' '. $user_info->last_name .'</span>, </span>
												<p>Your email address has been verified.  You can start using Nidara-Children Free Trial now. </p>
											</div>
											<br>
											 <div class="sub-mail-but">
											 <p style="text-align:center;">
											   <a style="text-decoration: none; color: #333; padding: 10px 50px; border: 1px solid;" class="sub-but" href="'. $this->config->weburl .'/signin"><b>SIGN IN</b>
											  </a>
											  </p>
											 </div>';
				}
				$mail = new PHPMailer;

					//$mail->SMTPDebug = 3;                               // Enable verbose debug output

				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp-relay.sendinblue.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'contact@haselfre.com';                 // SMTP username
				$mail->Password = 'DW6a42NFsPUCgcjA';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
				$mail->addAddress($user_info->email, '');     // Add a recipient
																			// Name is optional
				$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');

					//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
					//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				                                  // Set email format to HTML

				$mail->Subject = 'YOUR EMAIL ADDRESS HAS BEEN VERIFIED.';
				$mail->Body    = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Email</title>
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {}

         body,td{
         font-family:verdana,geneva;
         font-size:12px;
         }
         body{
         background:#fff;
         padding:20px;
         }
         .top-img{
         width:100%;
         text-align:center;
         padding-bottom:0;
         font-size:10px;
         }
         .sub-mail-cont{
         width:100%;
         }
         .sub-mail-vr{
         width:580px;
         margin:auto;
         float:none;
         }
         .main-page-mail{
         width:100%;
         float:left;
         padding:20px;
         border:1px solid #999;
         }
         .sub-mail-but{
         width:100%;
         text-align:center;
         padding-top:30px;
         float:left;
         }
         a.sub-but{
         text-decoration:none;
         color:#333;
         padding:10px 50px;
         border:1px solid;
         }
         .sub-but-cont{
         width:100%;
         padding-top:20px;
         float:left;
         }
         .footer{
         width:100%;
         text-align:center;
         font-size:10px;
         padding-top:20px;
         float:left;
         }
         .footer ul{
         list-style:none;
         float:left;
         margin:15px 10px;
         width:100%;
         padding:0;
         }
         .footer ul li{
         display:inline-flex;
         padding-left:5px;
         }
         p{
         line-height:18px;
         }
         .small{
         font-size:11px;
         }
         .main-title{
         text-align:center;
         color:#aed7d3;
         float:left;
         width:100%;
         }
         .main-title h3{
         font-weight:500;
         }
         .first-name{
         text-transform:capitalize;
         }
         .product-img{
         width:20%;
         float:left;
         padding-right:20px;
         }
         .product-img img{
         width:100%;
         }
         .product-cont{
         width:75%;
         float:left;
         }
         .product-details{
         width:100%;
         float:left;
         }
      
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body style="font-family: verdana,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style=""><tr><td>

      <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
         <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
            <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
               <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/07/170x150_logo.jpg" alt="170x150_logo.jpg" /><p style="line-height: 18px;">THE BEST START IN LIFE</p>
            </div>
            '. $emailcontent .'
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
               <p style="line-height: 18px;">Best regards,</p>
               <p style="line-height: 18px;">
               </p>
               <p style="line-height: 18px;">Nidara Children</p>
            </div>
            <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
               <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
<li style="display: inline-flex; padding-left: 5px;">
                     <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/facebook-mint.png" alt="facebook-mint.png" /></a>
                  </li>
                  <li style="display: inline-flex; padding-left: 5px;">
                     <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/twitter-mint.png" alt="twitter-mint.png" /></a>
                  </li>
                  <li style="display: inline-flex; padding-left: 5px;">
                     <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/instagram-mint.png" alt="instagram-mint.png" /></a>
                  </li>
                  <li style="display: inline-flex; padding-left: 5px;">
                     <a class="email" href="'. $this->config->weburl .'/contact-us/" target="_blank"> <img src="https://faq.nidarachildren.com/wp-content/uploads/2018/01/mail-mint.png" alt="mail-mint.png" /></a>
                  </li>
               </ul>
<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
               <br /><span>You are receiving this email because you opted in at our website.
               </span>
               <br /><span><a href="">Add us to your address book</a> | <a href="">Unsubscribe from this list</a></span>
            </div>
         </div>
      </div>
   
</td></tr></table>
</body>
</html>
';
					$mail->IsHTML(true);
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

					if(!$mail->send()) {
						return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo 
						] );
						
					} else {
						return $this->response->setJsonContent ( [ 
								'status' => true,
								'data' => $user_info
						] );
						
					}
			}
		}
		else{
			return $this->response->setJsonContent ( [ 
				'status' => false,
				'message' => 'The token has been expired' 
			] );
		}
		
	}
	public function updatetoken(){
		$input_data = $this->request->getJsonRawBody ();
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'message' => $input_data
		] );
		$coloection = TokenUsers::findFirstByusers_id($input_data -> users_id);
		if(!$coloection){
			$coloection = new TokenUsers();
			$coloection -> id = $this->usersid->getNewId("users-token");
		}
		$coloection -> token = $input_data -> token;
		if($coloection -> save()){
			return $this->response->setJsonContent ( [ 
				'status' => true,
				'message' => 'data saved'
			] );
		} else {
			return $this->response->setJsonContent ( [ 
				'status' => false,
				'message' => 'data not saved'
			] );
		}
	}



	public function saveExcel(){
        $input_data = $this->request->getJsonRawBody ();
        $tmpfname = $input_data -> file_name;
	$excelarray = array();
$fileHandle = fopen($tmpfname, "r");
//Loop through the CSV rows.
$i = 0;
$ldapconn = $this->connection ();
while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
    //Print out my column data.
   // echo 'Name: ' . $row[0] . '<br>';
   // echo 'Country: ' . $row[1] . '<br>';
  //  echo 'Age: ' . $row[2] . '<br>';
  //  echo '<br>';
  if($i >= 5){
	 $data['1'] = $row[0];
	 $data['2'] = $row[1];
	 $data['3'] = $row[2];
	 $data['4'] = $row[3];
	 $data['5'] = $row[4];
	 $data['6'] = $row[5];
	 $data['7'] = $row[6];
	 $data['8'] = $row[7];
	 $data['9'] = $row[8];
	 $data['10'] = $row[9];
	 $data['11'] = $row[10];
     	$data['12'] = $row[11];
     $excelarray[] = $data;
    $user = Users::findFirstByemail($row[4]);

                if(!$user){
                    $user = new Users ();
                    $user->id = $this->usersid->getNewId("users");
                    $user->parent_type = 'father';
                    $user->first_name = ($row[2]);
                    $user->last_name = ($row[3]);
                    $user->user_type = 'parent';
                    $user->email = ($row[4]);
                    $user->photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/icon.png';
                    $user->mobile = ($row[5]);
		   $user->created_at = date ( 'Y-m-d H:i:s' );
				$user->created_by = $input_data->school_id;
				$user->status = 5;
				$user->act_status = 2;
				$user->modified_at = date ( 'Y-m-d H:i:s' );
				if(!$user->save()){
					return $this->response->setJsonContent([
						'status' => false,
						'message' => "Please upload the excel format as per NC standard. "
					] );
				}else {
                        $code = $this -> generate_random_letters();
                        $email = $row[4];
                        $password = $code;
                        $userpassword = new UserTemPassword();
                        $userpassword -> user_id = $user-> id;
                        $userpassword -> password = $code;
                        if(!$userpassword -> save()){
                            return $this->response->setJsonContent ( [ 
                                'status' => false,
                                'message' => "Password not save"
                            ] );

                        }
                        $ldapbind = ldap_bind($ldapconn, self::ldaprdn, self::ldappass);
                        if ($ldapbind) {
                            /**
                             * This object using valitaion */
                             
                            $ldaprecord ['sn'] [0] = $row[2];
                            $ldaprecord ['objectclass'] [2] = "top";
                            $ldaprecord ['objectclass'] [1] = "posixAccount";
                            $ldaprecord ['objectclass'] [0] = "inetOrgPerson";
                            $ldaprecord ['uid'] [0] = $email;
                            $ldaprecord ['gidnumber'] [0] = '500';
                            $ldaprecord ['givenname'] [0] = $row[2];
                            $ldaprecord ['uidnumber'] [0] = $user-> id;
                            $ldaprecord ['userpassword'] [0] = md5 ($password);
                            $ldaprecord ['loginshell'] [0] = '/bin/sh';
                            $ldaprecord ['homedirectory'] [0] = "/home/users/$email";
                            $ldaprecord ['street'] [0] = $email;
                            
                            // add data to directory
                            $r = ldap_add ( $ldapconn, 'cn=' . $email . ',ou=users,' . self::ldaprdn, $ldaprecord );
                            
                            if (!$r) {
                                return $this->response->setJsonContent ( [ 
                                    'status' => false,
                                    'message' => 'couldnot save user' 
                                ] );
                            }
                            } else {
                                return $this->response->setJsonContent ( [ 
                                    'status' => false,
                                    'message' => 'LDAP bind failed' 
                                ] );
                        }
				}
                        			$kidprofile = new NidaraKidProfile ();
						$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
						$kidprofile->first_name = $row[6];
						if (! empty ( $childvalue->middle_name )) {
							$kidprofile->middle_name = $childvalue->middle_name;
						}
						$kidprofile->last_name = $row[7];
						$kidprofile->date_of_birth = date('Y-m-d');
						if(!empty($input_data->age)){
							$kidprofile->age = $childvalue->age;
						}
						if($row[8] === 'Boy'){
							$kidprofile->gender = 'male';
						} else if($row[8] === 'Girl'){
							$kidprofile->gender = 'female';
						}
						$kidprofile->height = '0';
						$kidprofile->weight = '0';
						if($row[9] === 'Preschool'){
							$kidprofile->grade = 1;
						} else if($row[9] === 'LKG'){
							$kidprofile->grade = 2;
						} else if($row[9] === 'UKG'){
							$kidprofile->grade = 3;
						}
						
						$kidprofile->created_at = date ( 'Y-m-d H:i:s' );
						$kidprofile->created_by = $user -> id;
						$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
						$kidprofile->status = 5;
						$kidprofile->choose_time = 1;
						if($row[12] === 'Already Admitted'){
							$kidprofile->admission_status = 1;
						} else if($row[12] === 'Admission Pending'){
							$kidprofile->admission_status = 2;
						} else if($row[12] === 'Admission Rejected'){
							$kidprofile->admission_status = 3;
						}
						
						$kidprofile->cancel_subscription = 1;
						if (empty ( $childvalue->child_photo )) {
							$gender = $childvalue->gender;
							if ($gender == 'male') {
								$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_male.png';
							} else {
								$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_female.png';
							}
						} 
						if (!$kidprofile->save ()) {
							return $this->response->setJsonContent([
								'status' => false,
								'message' => "Please upload the excel format as per NC standard. ",
								'data' => $kidprofile
							] );
						}
						else{
							$kid_guide = new KidGuidedLearningMap ();
							$kid_guide->id = $this->kididgen->getNewId ( "nidarakidlearningmap" );
							$kid_guide->nidara_kid_profile_id = $kidprofile->id;
							$kid_guide->guided_learning_id = $kidprofile->grade;
							if(!$kid_guide->save ()){
								return $this->response->setJsonContent([
									'status' => false,
									'message' => "Please upload the excel format as per NC standard. ",
									'data' => $kid_guide
								] );
							}
							$parentsmap = new KidParentsMap ();
							$parentsmap->id = $this->kididgen->getNewId ( "kidparentsmap" );
							$parentsmap->nidara_kid_profile_id = $kidprofile->id;
							$parentsmap->users_id = $user -> id;
							if(!$parentsmap->save()){
								return $this->response->setJsonContent([
									'status' => false,
									'message' => "Please upload the excel format as per NC standard. ",
									'data' => $parentsmap
								] );
							}
                        			}
				    $parentmap = SchoolParentMap::findFirstByuser_id($user -> id);
				    if(!$parentmap){
				        $parentmap = new SchoolParentMap();
				        $parentmap -> user_id = $user -> id;
				    }
					$parentmap -> school_id = $input_data -> school_id;
					if(!$parentmap -> save()){
						return $this->response->setJsonContent([
							'status' => false,
							'message' => "Please upload the excel format as per NC standard. "
						] );
					}
                } else {
                	$kidprofile = new NidaraKidProfile ();
						$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
						$kidprofile->first_name = $row[6];
						if (! empty ( $childvalue->middle_name )) {
							$kidprofile->middle_name = $childvalue->middle_name;
						}
						$kidprofile->last_name = $row[7];
						$kidprofile->date_of_birth = date('Y-m-d');
						if(!empty($input_data->age)){
							$kidprofile->age = $childvalue->age;
						}
						if($row[8] === 'Boy'){
							$kidprofile->gender = 'male';
						} else if($row[8] === 'Girl'){
							$kidprofile->gender = 'female';
						}
						$kidprofile->height = '0';
						$kidprofile->weight = '0';
						if($row[9] === 'Preschool'){
							$kidprofile->grade = 1;
						} else if($row[9] === 'LKG'){
							$kidprofile->grade = 2;
						} else if($row[9] === 'UKG'){
							$kidprofile->grade = 3;
						}
						$kidprofile->expiry_date = date("Y-m-d",strtotime('+365days'));
						$kidprofile->created_at = date ( 'Y-m-d H:i:s' );
						$kidprofile->created_by = $user -> id;
						$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
						$kidprofile->status = 5;
						$kidprofile->choose_time = 1;
						if($row[12] === 'Already Admitted'){
							$kidprofile->admission_status = 1;
						} else if($row[12] === 'Admission Pending'){
							$kidprofile->admission_status = 2;
						} else if($row[12] === 'Admission Rejected'){
							$kidprofile->admission_status = 3;
						}
						
						$kidprofile->cancel_subscription = 1;
						if (empty ( $childvalue->child_photo )) {
							$gender = $childvalue->gender;
							if ($gender == 'male') {
								$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_male.png';
							} else {
								$kidprofile->child_photo = 'https://stgncschoolapp.blob.core.windows.net/$web/afs/profile/no_image_female.png';
							}
						} 
						if (!$kidprofile->save ()) {
							return $this->response->setJsonContent([
								'status' => false,
								'message' => "Please upload the excel format as per NC standard. ",
								'data' => $kidprofile
							] );
						}
						else{
							$kid_guide = new KidGuidedLearningMap ();
							$kid_guide->id = $this->kididgen->getNewId ( "nidarakidlearningmap" );
							$kid_guide->nidara_kid_profile_id = $kidprofile->id;
							$kid_guide->guided_learning_id = $kidprofile->grade;
							if(!$kid_guide->save ()){
								return $this->response->setJsonContent([
									'status' => false,
									'message' => "Please upload the excel format as per NC standard. ",
									'data' => $kid_guide
								] );
							}
							$parentsmap = new KidParentsMap ();
							$parentsmap->id = $this->kididgen->getNewId ( "kidparentsmap" );
							$parentsmap->nidara_kid_profile_id = $kidprofile->id;
							$parentsmap->users_id = $user -> id;
							if(!$parentsmap->save()){
								return $this->response->setJsonContent([
									'status' => false,
									'message' => "Please upload the excel format as per NC standard. ",
									'data' => $parentsmap
								] );
							}
                        			}
				    $parentmap = SchoolParentMap::findFirstByuser_id($user -> id);
				    if(!$parentmap){
				        $parentmap = new SchoolParentMap();
				        $parentmap -> user_id = $user -> id;
				    }
					$parentmap -> school_id = $input_data -> school_id;
					if(!$parentmap -> save()){
						return $this->response->setJsonContent([
							'status' => false,
							'message' => "Please upload the excel format as per NC standard. "
						] );
					}
                }
            } 
        $i++;
    }
	// return $this->response->setJsonContent ( [
		// 			"status" => false,
		// 			"message" => $excelarray			
		// 	] );
        return $this->response->setJsonContent ( [
                "status" => true,
                "message" => "File uploaded successfully",
                "data" => $excelarray
        ] );
    } 
/*

    public function registersalesman() 
    {
        $input_data = $this->request->getJsonRawBody();
        if(empty($input_data)){
            return $this->response->setJsonContent([
                    'status' => false,
                    'message' => "Please give the details and then login"
            ] );
        }
        $validation = new Validation ();
        $validation->add ( 'first_name', new PresenceOf ( [ 
                'message' => 'First name is required' 
        ] ) );
        $validation->add ( 'last_name', new PresenceOf ( [ 
                'message' => 'Last name is required' 
        ] ) );
        $validation->add ( 'email', new PresenceOf ( [
                'message' => 'Email is required'
        ] ) );
        $validation->add ( 'email', new Email ( [
                'message' => 'Please give the valid email'
        ] ) );
        // $validation->add ( 'password', new PresenceOf ( [ 
        //         'message' => 'Password is required' 
        // ] ) );
        // $validation->add ( 'confirmpassword', new PresenceOf ( [
        //         'message' => 'Confirm Password is required'
        // ] ) );
        $validation->add ( 'parent_type', new PresenceOf ( [
                'message' => 'Parent Type is required'
        ] ) );
        $messages = $validation->validate ( $input_data );
        if (count ( $messages )) {
            
            foreach ( $messages as $message ) {
                $result [] = $message->getMessage ();
            }
            return $this->response->setJsonContent([ 
                    'status' => false,
                    'message' => $result
            ] );
        }
        if ($input_data->password != $input_data->confirmpassword) {
            return $this->response->setJsonContent ( [ 
                    'status' => false,
                    'message' => "Password does not match" 
            ] );
        }
        $ldapconn = $this->connection ();

        	$code = $this -> generate_random_letters();
			$password = $code;
        
        $email = $input_data->email;
        $userexist=Users::findFirstByemail($email);
        if(!empty($userexist)){
            return $this->response->setJsonContent ( [ 
                    'status' => false,
                    'message' => "This email address is already in use. Please enter a different one."
            ] );
        }
        $user_id = $this->usercreate ( $input_data );


        if(!empty($input_data->center_id))
        {
			$center =new SalesCenterMap();
        	$center->user_id =$user_id;
        	$center->center_id =$input_data->center_id;
	    	$center->save();
        }

        $ldapbind = ldap_bind($ldapconn, self::ldaprdn, self::ldappass);
        if ($ldapbind) {
           
            $ldaprecord ['sn'] [0] = $input_data->first_name;
            $ldaprecord ['objectclass'] [2] = "top";
            $ldaprecord ['objectclass'] [1] = "posixAccount";
            $ldaprecord ['objectclass'] [0] = "inetOrgPerson";
            $ldaprecord ['uid'] [0] = $email;
            $ldaprecord ['gidnumber'] [0] = '500';
            $ldaprecord ['givenname'] [0] = $input_data->first_name;
            $ldaprecord ['uidnumber'] [0] = $user_id;
        if(($user->user_type == 'development_officer') || ($user->user_type == 'dev_enroll_officer') || ($user->user_type == 'coordinator'))
        {
            $ldaprecord ['userpassword'] [0] = md5 ( $password );
        }
        else
        {
        	$ldaprecord ['userpassword'] [0] = md5 ( $input_data->password );
            
        }
            $ldaprecord ['loginshell'] [0] = '/bin/sh';
            $ldaprecord ['homedirectory'] [0] = "/home/users/$email";
            $ldaprecord ['street'] [0] = $email;
            
            // add data to directory
            $r = ldap_add ( $ldapconn, 'cn=' . $email . ',ou=users,' . self::ldaprdn, $ldaprecord );
            
            if ($r) {
                $this->wp_users_create($input_data);
                
                $user_info = Users::findFirstByemail($email);
                if($user_info->status == '1')
                {
                
              


                $mail = new PHPMailer;

		$user_infoval = Users::findFirstByemail($input_data->email);
		$topset = file_get_contents('../public/email/topmail.html');
		$bottomset = file_get_contents('../public/email/bottom.html');
		$emailcontant = ' <div class="page-title">
		<h3>WELCOME TO NIDARA-CHILDREN</h3>
	   </div>
		<div class="page-content">
			
			<p>Dear ' . $user_infoval->first_name . ' ,</p> 

			<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

			<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

			<p> Step 1: Sign in and complete registration using the credentials below </p>

			<p> Email address: ' . $user_infoval->email . ' </p>
			<p> Temporary password: ' . $password . '  </p>

			<p> Step 2: Complete the Early Childhood Questionnaire 
			(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

			<p> Step 3: Start program </p>

			<p> We look forward to helping you give your child the best start in life.. </p>

		</div>
		<div class="click-but">
			<div class="but">
				<a href="' . $this
			->config->weburl . '/signin"> <span>SIGN IN</span> </a>
			</div>
		</div>';

  	        // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
            $mail->addAddress($user_infoval->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Welcome to Nidara Children ';
            $mail->Body = $topset .'' . $emailcontant . '' . $bottomset;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }

                   
                }

                $code = $this -> childcreation($user_id);
                return $this->response->setJsonContent ( [ 
                        'status' => true,
                        'message' => 'User registered successfully',
                        'user_info' => $user_info
                ] );
            } else {
                return $this->response->setJsonContent ( [ 
                        'status' => false,
                        'message' => 'couldnot save user' 
                ] );
            }
        } 
        else {
            return $this->response->setJsonContent ( [ 
                    'status' => false,
                    'message' => 'LDAP bind failed' 
            ] );
        }
    }

  public function sendmail()
  {

  	  $input_data = $this->request->getJsonRawBody();
  	 $mail = new PHPMailer;

  	  $user_info = Users::findFirstByemail($input_data->email);

  	\

                $password="554212";
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'contact@haselfre.com'; // SMTP username
            $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
            $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
            $mail->addAddress($user_info->email, ''); // Add a recipient
            // Name is optional
            $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Welcome to Nidara Children ';
            $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html>
					<head>
					<meta http-equiv="content-type" content="text/html; charset=utf-8" />
					<style type="text/css">
					.ReadMsgBody {width: 100%;}
					.ExternalClass {width: 100%;}

					body {
						color: #666666;
					}
							.page-title {
								text-align: center;
								width: 100%;
								float: left;
								color: #8bbdcb;
							}
					           body,td{
					            font-family:verdana,geneva;
					            font-size:12px;
					           }
					           body{
					            background:#fff;
					            padding:20px;
					           }
					           .top-img{
					            width:100%;
					            text-align:center;
					            padding-bottom:0;
					            font-size:10px;
					           }
					           .sub-mail-cont{
					            width:100%;
					           }
					           .sub-mail-vr{
					            width:580px;
					            margin:auto;
					            float:none;
					           }
					           .main-page-mail{
					            width:100%;
					            float:left;
					            padding:20px;
					            border:1px solid #999;
					           }
					           .sub-mail-but{
					            width:100%;
					            text-align:center;
					            padding-top:30px;
					            float:left;
					           }
					           a.sub-but{
					            text-decoration:none;
					            color:#333;
					            padding:10px 50px;
					            border:1px solid;
					           }
					           .sub-but-cont{
					            width:100%;
					            padding-top:20px;
					            float:left;
					           }
					           .footer{
					            width:100%;
					            text-align:center;
					            font-size:10px;
					            padding-top:20px;
					            float:left;
					           }
					           .footer ul{
					            list-style:none;
					            float:left;
					            margin:15px 10px;
					            width:100%;
					            padding:0;
					           }
					           .footer ul li{
					            display:inline-flex;
					            padding-left:5px;
					           }
					           p{
					            line-height:18px;
					           }
					           .small{
					            font-size:11px;
					           }
					           .main-title{
					            text-align:center;
					            color:#aed7d3;
					            float:left;
					            width:100%;
					           }
					           .main-title h3{
					            font-weight:500;
					           }
					           .first-name{
					            text-transform:capitalize;
					           }
					           .product-img{
					            width:20%;
					            float:left;
					            padding-right:20px;
					           }
					           .product-img img{
					            width:100%;
					           }
					           .product-cont{
					            width:75%;
					            float:left;
					           }
					           .product-details{
					            width:100%;
					            float:left;
					           }
							   .page-content {
									width: 100%;
									float: left;
									color: #666666;
								}
								.click-but {
									width: 100%;
									float: left;
									text-align: center;
								}
								.click-but .but{
									width:250px;
									display:block;
									margin:auto;
								}
								.click-but .but a {
									text-decoration: none;
									padding: 10px;
									color: #fff;
								}
								.click-but .but {
									display: block;
									margin: auto;
									width: 200px;
									padding: 20px;
									background: #333333;
									font-size: 18px;
								}
								.click-but .but:hover{
									background: #8bbdcb;
								}
					         
					span.yshortcuts { color:#000; background-color:none; border:none;}
					span.yshortcuts:hover,
					span.yshortcuts:active,
					span.yshortcuts:focus {color:#000; background-color:none; border:none;}
					</style>
					</head>
					<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
					,geneva; font-size: 12px; background: #fff; padding: 20px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

					          
					          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
					            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
					           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
					             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
					           </div>
							   <div class="page-title">
								<h3>WELCOME TO NIDARA-CHILDREN</h3>
							   </div>
								<div class="page-content">
									
									<p>Dear ' . $user_info->first_name . ' ,</p> 

									<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

									<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

									<p> Step 1: Sign in and complete registration using the credentials below </p>

									<p> Email address: ' . $user_info->email . ' </p>
									<p> Temporary password: ' . $password . '  </p>

									<p> Step 2: Complete the Early Childhood Questionnaire 
									(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

									<p> Step 3: Start program </p>

									<p> We look forward to helping you give your child the best start in life.. </p>

								</div>
								<div class="click-but">
									<div class="but">
										<a href="' . $this
					                ->config->weburl . '/signin"> <span>SIGN IN</span> </a>
									</div>
								</div>
					            
					            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
					           <p style="line-height: 18px;">Best regards,</p>
					           <p style="line-height: 18px;">
					            </p>
					            <p style="line-height: 18px;">Nidara-Children</p>
					          </div>
					          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
					            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
									<li style="display: inline-flex; padding-left: 5px;">
									 <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
								   </li>
								   <li style="display: inline-flex; padding-left: 5px;">
									 <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
								   </li>
								   <li style="display: inline-flex; padding-left: 5px;">
									 <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
								   </li>
								   <li style="display: inline-flex; padding-left: 5px;">
									 <a class="email" href="' . $this
					                ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
								   </li>
					           </ul>
								<span>Copyright &copy; Nidara-Children. All rights reserved.</span>
									<br /><span>You are receiving this email because you opted in at our website.
					            </span>
					            <br /><span>
								<a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
								</span>
					          </div>
					           </div>
					         </div>
					         
					</td></tr></table>
					</body>
					</html>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if (!$mail->send())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
            else
            {
                return $this->response->setJsonContent ( [
                	'status' => true,
                	'message' => 'Message hase be sent.'
                ] );
                

                
            }
  }  
    */
/*
    public function registersalesmannew() {
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data -> centerInfo)){
			return $this->response->setJsonContent([ 
					'status' => false,
					'message' => 'plese check center info'
			] );
		}
		foreach($input_data -> centerInfo as $value){
			if(empty($value -> open_time)){
				return $this->response->setJsonContent([ 
					'status' => false,
					'message' => 'plese check center info'
				] );
			} else if(empty($value -> close_time)){
				return $this->response->setJsonContent([ 
					'status' => false,
					'message' => 'plese check center info'
				] );
			} else if(empty($value -> center_overview)){
				return $this->response->setJsonContent([ 
					'status' => false,
					'message' => 'plese check center info'
				] );
			}
		}
		
		$validation = new Validation ();
		$validation->add ( 'first_name', new PresenceOf ( [ 
				'message' => 'First name is required' 
		] ) );
		$validation->add ( 'last_name', new PresenceOf ( [ 
				'message' => 'Last name is required' 
		] ) );
		$validation->add ( 'email', new PresenceOf ( [
				'message' => 'Email is required'
		] ) );
		$validation->add ( 'email', new Email ( [
				'message' => 'Please give the valid email'
		] ) );
		
		if(($input_data->parent_type != 'development_officer') && ($input_data->parent_type != 'dev_enroll_officer') && ($input_data->parent_type != 'coordinator'))
		
					{

		$validation->add ( 'password', new PresenceOf ( [ 
				'message' => 'Password is required' 
		] ) );
		$validation->add ( 'confirmpassword', new PresenceOf ( [
				'message' => 'Confirm Password is required'
		] ) );
		
			}

		$validation->add ( 'parent_type', new PresenceOf ( [
				'message' => 'Parent Type is required'
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent([ 
					'status' => false,
					'message' => $result
			] );
		}
		if ($input_data->password != $input_data->confirmpassword) {
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "Password does not match" 
			] );
		}
		//$ldapconn = $this->connection ();
		
		$email = $input_data->email;
		$userexist=Users::findFirstByemail($email);
		if(!empty($userexist)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "This email address is already in use. Please enter a different one."
			] );
		}
		$code = $this -> generate_random_letters(); 
		$user_id = $this->usercreate ( $input_data );
		try{
			$accessToken = $this-> azureAdToken();
			
			$graph = new Graph();
			$graph->setAccessToken($accessToken);
			$parts=explode('@',$email);
			$userPrincipalName = $parts[0] . '@nidarachildren.com';
			// $input_data = $this->request->getJsonRawBody();
			// $data = array();
			$password['forceChangePasswordNextSignIn'] = false;
			$password['password'] = $code;
			$uservala['accountEnabled'] = true;
			$uservala['givenName'] = $user_id;
			$uservala['displayName'] = $input_data -> first_name . ' ' . $input_data -> last_name ;
			$uservala['mailNickname'] = $input_data -> first_name;
			$uservala['userPrincipalName'] = $userPrincipalName;
			$uservala['passwordProfile'] = $password;
			$data = $uservala;
			$user = $graph
			->createRequest("POST", "/users")
			->attachBody($data)
			->setReturnType(Model\User::class)
			->execute();
			if($user){
				$topset = file_get_contents('../public/email/topmail.html');
				$bottomset = file_get_contents('../public/email/bottom.html');
				$user_infoval = Users::findFirstByemail($input_data->email);
				$get  = json_decode(json_encode($user),true);
				$this -> adduseradinfo($user_infoval -> id, $get['id'], $get['userPrincipalName'] );
				$emailcontant = '<div class="page-title">
									<h3>WELCOME TO NIDARA-CHILDREN</h3>
									</div>
									<div class="page-content">
									
									<p>Dear ' . $user_infoval->first_name . ' ,</p> 

									<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

									<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

									<p> Step 1: Sign in and complete registration using the credentials below </p>

									<p> Email address: ' . $user_infoval->email . ' </p>
									<p> Temporary password: ' . $code . '  </p>

									<p> Step 2: Complete the Early Childhood Questionnaire 
									(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

									<p> Step 3: Start program </p>

									<p> We look forward to helping you give your child the best start in life.. </p>

									</div>
									<div class="click-but">
									<div class="but">
										<a href="' . $this
											->config->weburl . '/signin"> <span>SIGN IN</span> </a>
									</div>
									</div>';
				$mail = new PHPMailer(true);
				$mail->isSMTP(); // Set mailer to use SMTP
				$mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'contact@haselfre.com'; // SMTP username
				$mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587; // TCP port to connect to
				$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
				$mail->addAddress('savariraju@haselfre.com', ''); // Add a recipient
				// Name is optional
				$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				$mail->isHTML(true); // Set email format to HTML
				$mail->Subject = 'Welcome to Nidara Children ';
				$mail->Body = $topset . '' . $emailcontant . '' . $bottomset;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				if (!$mail->send())
				{
					return $this
						->response
						->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
				}
				else
				{
					return $this
						->response
						->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
				}
			} else {
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $user
				] );
			}
		}
		catch (\Exception $e){
			echo json_encode(array(
				"status" => false,
				"message" => "Access denied.",
				"error" => $e->getMessage()
			));
		}

				//sales center Info
				foreach ($input_data->centerInfo as $center) 
				{
					if(empty($center->id))
					{
						$salescenter=new SalesCenter();
					}
					else
					{
						$salescenter=SalesCenter::findFirstByid($center->id);
					}

					$salescenter->address_1=$input_data->address_1;
					$salescenter->address_2=$input_data->address_2;
					$salescenter->landmark=$center->landmark;
					$salescenter->city=$input_data->city;
					$salescenter->state=$input_data->state;
					$salescenter->country=$input_data->country;
					$salescenter->open_time=$center->open_time;
					$salescenter->close_time=$center->close_time;
					$salescenter->email=$input_data->email;
					$salescenter->mobile=$user_info->mobile;
					$salescenter->a_mobile=$center->a_mobile;
					$salescenter->center_overview=$center->center_overview;
					$salescenter->post_code=$input_data->postcode;
					$salescenter->center_type=$center->center_type;
					if(!$salescenter->save())
					{
					return $this->response->setJsonContent ( [ 
										'status' => false,
										'message' => $salescenter
								] );
					}

					$centerid=$salescenter->id;

					
						$time = strtotime('00:00:00');
							for($i=0;$i<32;$i++)
							{

							$startTime = date("H:i:s", strtotime('-0 minutes', $time));
							$endTime = date("H:i:s", strtotime('+45 minutes', $time));

							$salesmeetingavailable=new SalesMeetingAvailability();

							$salesmeetingavailable->center_id=$centerid;
							$salesmeetingavailable->start_time=$startTime;
							$salesmeetingavailable->end_time=$endTime;

							if(!$salesmeetingavailable->save())
								{
								return $this->response->setJsonContent ( [ 
														'status' => false,
														'message' => $salesmeetingavailable
												] );
								}

							$time=strtotime($endTime);

							}



							//sales_center_map

							$centermap =new SalesCenterMap();
				        	$centermap->user_id =$user_id;
				        	$centermap->center_id =$centerid;
					    	

					    if(!$centermap->save())
								{
								return $this->response->setJsonContent ( [ 
														'status' => false,
														'message' => $centermap
												] );
								}


							$centershowmap =new SalesCenterShowMap();
				        	$centershowmap->user_id =$user_id;
				        	$centershowmap->center_id =$centerid;
					    	

					    if(!$centershowmap->save())
								{
								return $this->response->setJsonContent ( [ 
														'status' => false,
														'message' => $centershowmap
												] );
								}

				}



				$this -> childcreation($user_id);


				return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'User registered successfully',
						'user_info' => $user_info
				] );
	}

*/

function childcreation($userid)
{
	for($i=0;$i<2;$i++)
	{
			if($i==0)
			{
				$first_name='Preschool Rahul';
				$last_name='Nidara';
				$gender='male';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/psr.png";
			}
			else
			{
				$first_name='Preschool Tina';
				$last_name='Nidara';
				$gender='female';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/pst.png";

			}


		
				$kidprofile = new NidaraKidProfile ();
				$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
			
			$kidprofile->first_name = $first_name;
			
				$kidprofile->middle_name ="";
			
			$kidprofile->last_name = $last_name;
			$kidprofile->date_of_birth = date("Y-m-d");
			
			$kidprofile->age = 5;
			
			$kidprofile->gender = $gender;
			$kidprofile->height = 100;
			$kidprofile->weight = 15;
			$kidprofile->grade = 1;
			$kidprofile->child_photo = $photo;
			// $kidprofile_create->child_avatar = $input_data->child_avatar;
			$kidprofile->created_at = date ( 'Y-m-d H:i:s' );
			
				$kidprofile->created_by = $userid;
			
			$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
			$kidprofile->status = 1;
			$kidprofile->cancel_subscription = 1;
			$kidprofile->choose_time = 1;
			$kidprofile->expiry_date = date("Y-m-d",strtotime('+365days'));
			$kidprofile->free_trial = 1;
			
			$kidprofile->test_kid_status = 1;
			$kidprofile->admission_status = 1;

			$kidprofile->relationship_to_child = 1;
		   	
		   	if ($kidprofile->save ()) 
			{
						$kid_guide = new KidGuidedLearningMap ();
							$kid_guide->id = $this->kididgen->getNewId("nidarakidlearningmap" );
							$kid_guide->nidara_kid_profile_id = $kidprofile->id;
							$kid_guide->guided_learning_id = $kidprofile->grade;
							$kid_guide->save ();
							$parentsmap = new KidParentsMap ();
							$parentsmap->id = $this->kididgen->getNewId ("kidparentsmap");
							$parentsmap->nidara_kid_profile_id = $kidprofile->id;
							$parentsmap->users_id = $userid;
							$parentsmap->save();

						$demo=new SalesmanDemoKid();
						$demo->kid_id=$kidprofile->id;
						$demo->save();

					$collactiondaily= $this->modelsManager->createBuilder ()->columns ( array(
						'DailyRoutine.task_name',
						'DailyRoutine.session_for',
						'DailyRoutine.repeatday',
						'DailyRoutine.reminder',
						'DailyRoutine.set_time',
						'DailyRoutine.end_time',
					))->from('DailyRoutine')
					->inwhere('DailyRoutine.nidara_kid_profile_id',array(2124))
					->getQuery ()->execute ();

					foreach ($collactiondaily as $value) 
					{
						
						$dailyroutine=new DailyRoutine();

						$dailyroutine->task_name=$value->task_name;
						$dailyroutine->session_for=$value->session_for;
						$dailyroutine->repeatday=$value->repeatday;
						$dailyroutine->reminder=$value->reminder;
						$dailyroutine->set_time=$value->set_time;
						$dailyroutine->end_time=$value->end_time;
						$dailyroutine->nidara_kid_profile_id=$kidprofile->id;

						$dailyroutine->save();

					}

			}
		
		}
}

public function childcreationnew()
{
	$input_data = $this->request->getJsonRawBody();
	for($i=0;$i<2;$i++)
	{
		if($input_data -> grade == 1){
			if($i==0)
			{
				$first_name='Preschool Rahul';
				$last_name='Nidara';
				$gender='male';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/psr.png";
			}
			else
			{
				$first_name='Preschool Tina';
				$last_name='Nidara';
				$gender='female';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/pst.png";

			}
		} else if($input_data -> grade == 2){
			if($i==0)
			{
				$first_name='Regular_Rahul Pre KG';
				$last_name='Nidara';
				$gender='male';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/bpkgr.png";
			}
			else
			{
				$first_name='Regular_Tina PreKG';
				$last_name='Nidara';
				$gender='female';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/pkgt.png";

			}
		} else if($input_data -> grade == 3){
			if($i==0)
			{
				$first_name='Regular_Rahul  KG';
				$last_name='Nidara';
				$gender='male';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/kgr.png";
			}
			else
			{
				$first_name='Regular_Tina KG';
				$last_name='Nidara';
				$gender='female';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/kgt.png";

			}
		} else if($input_data -> grade == 4){
			if($i==0)
			{
				$first_name='Regular_Rahul  Tows';
				$last_name='Nidara';
				$gender='male';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/twor.png";
			}
			else
			{
				$first_name='Regular_Tina Tows';
				$last_name='Nidara';
				$gender='female';
				$photo="https://cdnncschoolapplication.azureedge.net/assets/profile/twot.png";

			}
		}


		
				$kidprofile = new NidaraKidProfile ();
				$kidprofile->id = $this->kididgen->getNewId ( "nidarakidprofile" );
			
			$kidprofile->first_name = $first_name;
			
				$kidprofile->middle_name ="";
			
			$kidprofile->last_name = $last_name;
			$kidprofile->date_of_birth = date("Y-m-d");
			
			$kidprofile->age = 0;
			
			$kidprofile->gender = $gender;
			$kidprofile->height = 100;
			$kidprofile->weight = 15;
			$kidprofile->grade = $input_data -> grade;
			$kidprofile->child_photo = $photo;
			// $kidprofile_create->child_avatar = $input_data->child_avatar;
			$kidprofile->created_at = date ( 'Y-m-d H:i:s' );
			
				$kidprofile->created_by = $input_data -> user_id;
			
			$kidprofile->modified_at = date ( 'Y-m-d H:i:s' );
			$kidprofile->status = 1;
			$kidprofile->cancel_subscription = 1;
			$kidprofile->choose_time = 1;
			$kidprofile->expiry_date = date("Y-m-d",strtotime('+365days'));
			$kidprofile->free_trial = 1;
			
			$kidprofile->test_kid_status = 1;
			$kidprofile->admission_status = 1;

			$kidprofile->relationship_to_child = 1;
		   	
		   	if ($kidprofile->save ()) 
			{
						$kid_guide = new KidGuidedLearningMap ();
							$kid_guide->id = $this->kididgen->getNewId("nidarakidlearningmap" );
							$kid_guide->nidara_kid_profile_id = $kidprofile->id;
							$kid_guide->guided_learning_id = $kidprofile->grade;
							$kid_guide->save ();
							$parentsmap = new KidParentsMap ();
							$parentsmap->id = $this->kididgen->getNewId ("kidparentsmap");
							$parentsmap->nidara_kid_profile_id = $kidprofile->id;
							$parentsmap->users_id = $input_data -> user_id;
							$parentsmap->save();

						$demo=new SalesmanDemoKid();
						$demo->kid_id=$kidprofile->id;
						$demo->save();

					$collactiondaily= $this->modelsManager->createBuilder ()->columns ( array(
						'DailyRoutine.task_name',
						'DailyRoutine.session_for',
						'DailyRoutine.repeatday',
						'DailyRoutine.reminder',
						'DailyRoutine.set_time',
						'DailyRoutine.end_time',
					))->from('DailyRoutine')
					->inwhere('DailyRoutine.nidara_kid_profile_id',array(2124))
					->getQuery ()->execute ();

					foreach ($collactiondaily as $value) 
					{
						
						$dailyroutine=new DailyRoutine();

						$dailyroutine->task_name=$value->task_name;
						$dailyroutine->session_for=$value->session_for;
						$dailyroutine->repeatday=$value->repeatday;
						$dailyroutine->reminder=$value->reminder;
						$dailyroutine->set_time=$value->set_time;
						$dailyroutine->end_time=$value->end_time;
						$dailyroutine->nidara_kid_profile_id=$kidprofile->id;

						if(!$dailyroutine->save()){
							return $this->response->setJsonContent ( [ 
								'status' => false,
								'message' => 'User registered successfully',
							] );
						}

					}

			}
		
		}
		return $this->response->setJsonContent ( [ 
						'status' => true,
						'message' => 'User registered successfully',
				] );
		
}


	public function savenewbusiness(){
		$input_data = $this->request->getJsonRawBody();
	// 	return $this->response->setJsonContent ( [ 
	// 		'status' => false,
	// 		'message' => $input_data
	// ] );
		// $ldapconn = $this->connection ();
		$center_id = $this->addcenter ($input_data);
		if(!empty($input_data -> partner_type)){
			$code2 = $this -> adduserset($input_data, $center_id);
		}
		foreach($input_data -> userInfo as $userinfo){
			 
			$email = $userinfo->email;
			$userexist=Users::findFirstByemail($email);
			if(!empty($userexist)){
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => "This email address is already in use. Please enter a different one."
				] );
			}
			$code = $this -> generate_random_letters(); 
			$user_id = $this->usercreate ( $userinfo );
			try{
				$accessToken = $this-> azureAdToken();
				
				$graph = new Graph();
				$graph->setAccessToken($accessToken);
				$parts=explode('@',$email);
				$userPrincipalName = $parts[0] . '@nidarachildren.com';
				// $input_data = $this->request->getJsonRawBody();
				// $data = array();
				$password['forceChangePasswordNextSignIn'] = false;
				$password['password'] = $code;
				$uservala['accountEnabled'] = true;
				$uservala['givenName'] = $user_id;
				$uservala['displayName'] = $userinfo -> first_name . ' ' . $userinfo -> last_name ;
				$uservala['mailNickname'] = $userinfo -> first_name;
				$uservala['userPrincipalName'] = $userPrincipalName;
				$uservala['passwordProfile'] = $password;
				$data = $uservala;
				$user = $graph
				->createRequest("POST", "/users")
				->attachBody($data)
				->setReturnType(Model\User::class)
				->execute();
				if($user){
					$userpassword = new UserTemPassword();
								$userpassword -> user_id = $user_id;
								$userpassword -> password = $code;
								if(!$userpassword -> save()){
									return $this->response->setJsonContent ( [ 
										'status' => false,
										'message' => "Password not save"
									] );

								}
								if(!empty($center_id))
								{
									$center =new SalesCenterMap();
									$center->user_id =$user_id;
									$center->center_id =$center_id;
									$center->save();
								}

					$topset = file_get_contents('../public/email/topmail.html');
					$bottomset = file_get_contents('../public/email/bottom.html');
					$user_infoval = Users::findFirstByemail($userinfo->email);
					$get  = json_decode(json_encode($user),true);
					$this -> adduseradinfo($user_infoval -> id, $get['id'], $get['userPrincipalName'] );
					$emailcontant = '<div class="page-title">
										<h3>WELCOME TO NIDARA-CHILDREN</h3>
										</div>
										<div class="page-content">
										
										<p>Dear ' . $user_infoval->first_name . ' ,</p> 

										<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

										<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

										<p> Step 1: Sign in and complete registration using the credentials below </p>

										<p> Email address: ' . $user_infoval->email . ' </p>
										<p> Temporary password: ' . $code . '  </p>

										<p> Step 2: Complete the Early Childhood Questionnaire 
										(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

										<p> Step 3: Start program </p>

										<p> We look forward to helping you give your child the best start in life.. </p>

										</div>
										<div class="click-but">
										<div class="but">
											<a href="' . $this
												->config->weburl . '/signin"> <span>SIGN IN</span> </a>
										</div>
										</div>';
					$mail = new PHPMailer(true);
					$mail->isSMTP(); // Set mailer to use SMTP
					$mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
					$mail->SMTPAuth = true; // Enable SMTP authentication
					$mail->Username = 'contact@haselfre.com'; // SMTP username
					$mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587; // TCP port to connect to
					$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
					$mail->addAddress($user_infoval->email, ''); // Add a recipient
					// Name is optional
					$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
					//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
					//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
					$mail->isHTML(true); // Set email format to HTML
					$mail->Subject = 'Welcome to Nidara Children ';
					$mail->Body = $topset . '' . $emailcontant . '' . $bottomset;
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					if (!$mail->send())
					{
						return $this
							->response
							->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
					}
				} 
			}
			catch (\Exception $e){
				echo json_encode(array(
					"status" => false,
					"message" => "Access denied.",
					"error" => $e->getMessage()
				));
			}
		}
		return $this->response->setJsonContent ( [ 
			'status' => true,
			'message' => 'User registered successfully',
			'user_info' => $user_info
		] );
		
	}
	
	
	function adduserset($input_data, $center_id){
			if(empty($input_data -> first_name)){
				$input_data -> first_name = $input_data -> owner_name;
				$input_data -> last_name = 'Null';
			}
			
			if($input_data -> partner_type == 3){
				$input_data->parent_type = 'influential_user';
			} else if($input_data -> partner_type == 4){
				$input_data->parent_type = 'business_user';
			}
			
			// $ldapconn = $this->connection ();
			// $email = $input_data->email;
			// $userexist=Users::findFirstByemail($email);
			// if(!empty($userexist)){
			// 	return $this->response->setJsonContent ( [ 
			// 		'status' => false,
			// 		'message' => "This email address is already in use. Please enter a different one."
			// 	] );
			// }
			// $code = $this -> generate_random_letters();
			// $password = $code;

			// $user_id = $this->usercreate ($input_data);
			// $ldapbind = ldap_bind($ldapconn, self::ldaprdn, self::ldappass);
			// if ($ldapbind) {
				
			// 	// $email;
			// 	$ldaprecord ['sn'] [0] = $input_data->first_name;
			// 	$ldaprecord ['objectclass'] [2] = "top";
			// 	$ldaprecord ['objectclass'] [1] = "posixAccount";
			// 	$ldaprecord ['objectclass'] [0] = "inetOrgPerson";
			// 	$ldaprecord ['uid'] [0] = $email;
			// 	$ldaprecord ['gidnumber'] [0] = '500';
			// 	$ldaprecord ['givenname'] [0] = $input_data->first_name;
			// 	$ldaprecord ['uidnumber'] [0] = $user_id;
			// 	$ldaprecord ['userpassword'] [0] = md5 ( $password );
			// 	$ldaprecord ['loginshell'] [0] = '/bin/sh';
			// 	$ldaprecord ['homedirectory'] [0] = "/home/users/$email";
			// 	$ldaprecord ['street'] [0] = $email;
				
			// 	// add data to directory
			// 	$r = ldap_add ( $ldapconn, 'cn=' . $email . ',ou=users,' . self::ldaprdn, $ldaprecord );
				
			// 	if ($r) {

			// 		$this->wp_users_create($input_data);
			// 		$user_info = Users::findFirstByemail($email);

							
			// 				$topset = file_get_contents('../public/email/topmail.html');
			// 				$bottomset = file_get_contents('../public/email/bottom.html');
			// 				$user_infoval = Users::findFirstByemail($input_data->email);
			// 				$emailcontant = '<div class="page-title">
			// 					<h3>WELCOME TO NIDARA-CHILDREN</h3>
			// 					 </div>
			// 					<div class="page-content">
								  
			// 					  <p>Dear ' . $user_infoval->first_name . ' ,</p> 

			// 					  <p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

			// 					  <p>Start raising your child with Nidara-Children with 3 simple steps:</p>

			// 					  <p> Step 1: Sign in and complete registration using the credentials below </p>

			// 					  <p> Email address: ' . $user_infoval->email . ' </p>
			// 					  <p> Temporary password: ' . $password . '  </p>

			// 					  <p> Step 2: Complete the Early Childhood Questionnaire 
			// 					  (An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

			// 					  <p> Step 3: Start program </p>

			// 					  <p> We look forward to helping you give your child the best start in life.. </p>

			// 					</div>
			// 					<div class="click-but">
			// 					  <div class="but">
			// 						<a href="' . $this
			// 							  ->config->weburl . '/signin"> <span>SIGN IN</span> </a>
			// 					  </div>
			// 					</div>';
								
			// 			$mail = new PHPMailer;
			// 			$mail->isSMTP(); // Set mailer to use SMTP
			// 			$mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
			// 			$mail->SMTPAuth = true; // Enable SMTP authentication
			// 			$mail->Username = 'contact@haselfre.com'; // SMTP username
			// 			$mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
			// 			$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
			// 			$mail->Port = 587; // TCP port to connect to
			// 			$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
			// 			$mail->addAddress($user_infoval->email, ''); // Add a recipient
			// 			$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
			// 			$mail->isHTML(true); // Set email format to HTML
			// 			$mail->Subject = 'Welcome to Nidara Children ';
			// 			$mail->Body = $topset . '' . $emailcontant .'' . $bottomset;
			// 			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			// 			if (!$mail->send())
			// 			{
			// 				return $this
			// 					->response
			// 					->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
			// 			}
			// 		$code = $this -> childcreation($user_id);
			// 	} else {
			// 		return $this->response->setJsonContent ( [ 
			// 			'status' => false,
			// 			'message' => 'couldnot save user' 
			// 		] );
			// 	}
			// }


		$email = $input_data->email;
		$userexist=Users::findFirstByemail($email);
		if(!empty($userexist)){
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "This email address is already in use. Please enter a different one."
			] );
		}
		$code = $this -> generate_random_letters(); 
		$user_id = $this->usercreate ( $input_data );
		try{
			$accessToken = $this-> azureAdToken();
			
			$graph = new Graph();
			$graph->setAccessToken($accessToken);
			$parts=explode('@',$email);
			$userPrincipalName = $parts[0] . '@nidarachildren.com';
			// $input_data = $this->request->getJsonRawBody();
			// $data = array();
			$password['forceChangePasswordNextSignIn'] = false;
			$password['password'] = $code;
			$uservala['accountEnabled'] = true;
			$uservala['givenName'] = $user_id;
			$uservala['displayName'] = $input_data -> first_name . ' ' . $input_data -> last_name ;
			$uservala['mailNickname'] = $input_data -> first_name;
			$uservala['userPrincipalName'] = $userPrincipalName;
			$uservala['passwordProfile'] = $password;
			$data = $uservala;
			$user = $graph
			->createRequest("POST", "/users")
			->attachBody($data)
			->setReturnType(Model\User::class)
			->execute();
			if($user){
				$userpassword = new UserTemPassword();
							$userpassword -> user_id = $user_id;
							$userpassword -> password = $code;
							if(!$userpassword -> save()){
								return $this->response->setJsonContent ( [ 
									'status' => false,
									'message' => "Password not save"
								] );

							}
							if(!empty($center_id))
							{
								$center =new SalesCenterMap();
								$center->user_id =$user_id;
								$center->center_id =$center_id;
								$center->save();
							}

				$topset = file_get_contents('../public/email/topmail.html');
				$bottomset = file_get_contents('../public/email/bottom.html');
				$user_infoval = Users::findFirstByemail($input_data->email);
				$get  = json_decode(json_encode($user),true);
				$this -> adduseradinfo($user_infoval -> id, $get['id'], $get['userPrincipalName'] );
				$emailcontant = '<div class="page-title">
									<h3>WELCOME TO NIDARA-CHILDREN</h3>
									</div>
									<div class="page-content">
									
									<p>Dear ' . $user_infoval->first_name . ' ,</p> 

									<p>Welcome to Nidara-Children, a pioneering early child development system dedicated to providing children the best start in life.</p>

									<p>Start raising your child with Nidara-Children with 3 simple steps:</p>

									<p> Step 1: Sign in and complete registration using the credentials below </p>

									<p> Email address: ' . $user_infoval->email . ' </p>
									<p> Temporary password: ' . $code . '  </p>

									<p> Step 2: Complete the Early Childhood Questionnaire 
									(An NC Program Early Childhood Questionnaire Guide will be sent to you after completing Step 1) </p> 

									<p> Step 3: Start program </p>

									<p> We look forward to helping you give your child the best start in life.. </p>

									</div>
									<div class="click-but">
									<div class="but">
										<a href="' . $this
											->config->weburl . '/signin"> <span>SIGN IN</span> </a>
									</div>
									</div>';
				$mail = new PHPMailer(true);
				$mail->isSMTP(); // Set mailer to use SMTP
				$mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'contact@haselfre.com'; // SMTP username
				$mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587; // TCP port to connect to
				$mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
				$mail->addAddress($user_infoval->email, ''); // Add a recipient
				// Name is optional
				$mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				$mail->isHTML(true); // Set email format to HTML
				$mail->Subject = 'Welcome to Nidara Children ';
				$mail->Body = $topset . '' . $emailcontant . '' . $bottomset;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				if (!$mail->send())
				{
					return $this
						->response
						->setJsonContent(['status' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
				}
				else
				{
					return $this
						->response
						->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
				}
			} else {
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'data' => $user
				] );
			}
		}
		catch (\Exception $e){
			echo json_encode(array(
				"status" => false,
				"message" => "Access denied.",
				"error" => $e->getMessage()
			));
		}
	}
	
	
	public function addcenter($input_data){
		//sales center Info
				foreach ($input_data->centerInfo as $center) 
				{
					if(empty($center->id))
					{
						$salescenter=new SalesCenter();
					}
					else
					{
						$salescenter=SalesCenter::findFirstByid($center->id);
					}

					$salescenter->address_1=$center->address_1;
					$salescenter->address_2=$center->address . ' ' . $center->address_2;
					$salescenter->landmark=$center->landmark;
					$salescenter->city=$center->city;
					$salescenter->state=$center->state;
					$salescenter->country=$center->country;
					$salescenter->open_time='00:00';
					$salescenter->close_time='00:00';
					$salescenter->email=$input_data->email;
					$salescenter->mobile=$input_data->mobile;
					$salescenter->a_mobile=$center->a_mobile;
					$salescenter->center_overview=$center->center_overview;
					$salescenter->post_code=$center->post_code;
					$salescenter->center_type=$center->center_type;
					if(!$salescenter->save())
					{
					return $this->response->setJsonContent ( [ 
										'status' => false,
										'message' => $salescenter
								] );
					}
					foreach($center->dayInfoValue as $dayvlaue){
						$dayset = new SalesCenterOpenClose();
						$dayset-> day_id = $dayvlaue -> day_id;
						$dayset-> center_id = $salescenter-> id;
						$dayset-> open_time = $dayvlaue -> open_time;
						$dayset-> close_time = $dayvlaue -> close_time;
						$dayset-> day_status = $dayvlaue -> day_status;
						if(!$dayset->save()){
								return $this->response->setJsonContent ( [ 
										'status' => false,
										'message' => $dayset
								] );
						}
					}
					$centerid=$salescenter->id;

					
						$time = strtotime('00:00:00');
							for($i=0;$i<32;$i++)
							{

							$startTime = date("H:i:s", strtotime('-0 minutes', $time));
							$endTime = date("H:i:s", strtotime('+45 minutes', $time));

							$salesmeetingavailable=new SalesMeetingAvailability();

							$salesmeetingavailable->center_id=$centerid;
							$salesmeetingavailable->start_time=$startTime;
							$salesmeetingavailable->end_time=$endTime;

							if(!$salesmeetingavailable->save())
								{
								return $this->response->setJsonContent ( [ 
														'status' => false,
														'message' => $salesmeetingavailable
												] );
								}

							$time=strtotime($endTime);

							}
							//sales_center_map
					    	

					    /* if(!$centershowmap->save())
								{
								return $this->response->setJsonContent ( [ 
														'status' => false,
														'message' => $centershowmap
												] );
								} */

				}
				if(!empty($input_data -> company_name)){
				
					$businesscreate = SalesBusinessInfo::findFirstByemail($input_data -> email);
					if(!$businesscreate){
						$businesscreate = new SalesBusinessInfo();
					}
					$businesscreate -> company_name = $input_data -> company_name;
					$businesscreate -> owner_name = $input_data -> owner_name;
					$businesscreate -> email = $input_data -> email;
					$businesscreate -> mobile = $input_data -> mobile;
					$businesscreate -> pan_number = $input_data -> pan_number;
					$businesscreate -> gst_number = $input_data -> gst_number;
					$businesscreate -> address = $input_data -> address;
					$businesscreate -> address_1 = $input_data -> address_1;
					$businesscreate -> landmark = $input_data -> landmark;
					$businesscreate -> state = $input_data -> state;
					$businesscreate -> city = $input_data -> city;
					$businesscreate -> post_code = $input_data -> post_code;
					$businesscreate -> center_id = $input_data -> centerid;
					$businesscreate -> save();
				}
				
				return $centerid;
	}


}
