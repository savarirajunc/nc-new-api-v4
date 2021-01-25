<?php declare(strict_types=1);use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use Firebase\JWT\JWT;
require BASE_PATH.'/vendor/autoload.php';

class AuthenticationController extends \Phalcon\Mvc\Controller {
	CONST UniqId = '598c2b9752462';
	CONST signer='nidara';
	private $key = 'nidarachildren';
	/**
	 * Generate the token
	 */
	public function tokengenerate($uid,$username) {
		$iat = time();
		$exp = $iat + 60 * 60 * 24;
		$payload = array(
			"iss" => $this->config->Issuer,
			"aud" => $this->config->Audience,
			"iat" => $iat,
			'exp' => $exp,
			'uid' => $uid,
			'username' => $username
		);
		$jwt = JWT::encode($payload, $this->key, 'HS512');

		return $jwt;
	}
	
	/**
	 * Token validation
	 */
	public function validatetoken($token, $ref = NULL) {
		// $token = (new Parser ())->parse ( ( string ) $token );
		// $data = new ValidationData ();
		try{
		$jwt = JWT::decode($token, $this->key, array('HS512'));
		return  ( $jwt );
		}

		catch (\Exception $e){
			
			// echo $e->getMessage() . '<br>';
			// return $e->getMessage();
			// echo '<pre>' . $e->getTraceAsString() . '</pre>';

			// die();

			if($e->getMessage() == "Expired token"){
				echo json_encode(array(
							"status" => false,
							"message" => "Access denied.",
							"error" => $e->getMessage()
				));
				
			
			} else {
			
			// 	// set response code
			// 	http_response_code(401);
			
			// 	// show error message
			// 	echo json_encode(array(
			// 		"message" => "Access denied.",
			// 		"error" => $e->getMessage()
			// 	));
			// 	
				}
		}
	}
	

	/**
	 * Get User Info
	 * @param string $token
	 * @return multitype:NULL \Lcobucci\JWT\mixed
	 */
	public function getuidtoken($token){
		//$token = (new Parser())->parse((string) $token);
		$jwt = JWT::decode($token, $this->key, array('HS512'));
		return array (
				"uid" => $jwt -> uid,
				"username" => $jwt -> username
		);
		
	}

}

