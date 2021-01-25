<?php
use Phalcon\Tag;
use Phalcon\Http\Client\Request;
class TokenValidate extends Tag
{
	
	/**
	 * 
	 * @param string $token
	 * @throws Exception
	 * @return mixed
	 */
	public function tokencheck($token,$baseurl) {
		try {
			$ch = curl_init ();
			$headers = [ 
					'Token:' . $token 
			];
			curl_setopt ( $ch, CURLOPT_URL, $baseurl . "/login/getuserinfobytoken/" );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			$contents = curl_exec ( $ch );
			curl_close ( $ch );
			return json_decode($contents);
		} catch ( \Exception $e ) {
			throw $e;
		}
	}
	public function usercheckbypassword($token,$baseurl,$password){
		try {
			$parameters = array(
					'password' => $password 
			);
			$inputparams = json_encode ( $parameters );
			$ch = curl_init ();
			$headers = [ 
					'Token:' . $token 
			];
			curl_setopt ( $ch, CURLOPT_URL, $baseurl . "/login/parentvalidate/" );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS,$inputparams );
			$contents = curl_exec ( $ch );
			curl_close ( $ch );
			return json_decode($contents);
		} catch ( \Exception $e ) {
			throw $e;
		}
	}
	public function getuserinfo($token,$baseurl){
		try {
			$ch = curl_init ();
			$headers = [
					'Token:' . $token
			];
			curl_setopt ( $ch, CURLOPT_URL, $baseurl . "/login/getuserinfobytoken/" );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			$contents = curl_exec ( $ch );
			curl_close ( $ch );
			return json_decode($contents);
		} catch ( \Exception $e ) {
			throw $e;
		}
	}
}
