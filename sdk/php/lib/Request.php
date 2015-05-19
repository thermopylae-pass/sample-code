<?php
/**
 * cUrl wrapper class to hand API connections.  Some object references has been removed and/or changed to proctet the innocent
 */
class Request {
	private $username;
	private $password;
	private $secure = true;
	
	public function setAuthorization($username, $password) {
		if ($username === null || $password === null) {
			throw new InvalidArgumentException('Username and password are required.');
		}
		$this->username = $username;
		$this->password = $password;
	}

	public function disableSecureConnection() {
		$this->secure = false;
	}

	private function request($options) {
		$ch = curl_init();
		if (!$this->secure) {
			$options[CURLOPT_SSL_VERIFYHOST] = 0;
			$options[CURLOPT_SSL_VERIFYHOST] = false;
		}
		curl_setopt_array($ch, $options);
		$body = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($body === false) {
			$err = curl_errno($ch);
			$errmsg = curl_error($ch);
			handleError($err, $errmsg);
		}

		curl_close($ch);

		return handleResponse($code, $body);;
	}
	
	private function handleError($err, $errmsg) {
		$server = REST::getUrl();
		switch ($err) {
			case CURLE_COULDNT_CONNECT:
			case CURLE_COULDNT_RESOLVE_HOST:
			case CURLE_OPERATION_TIMEOUTED:
				$msg = "Could not connect to .... on $server. Please check your internet connection and try again.";
				break;
			case CURLE_SSL_CACERT:
			case CURLE_SSL_PEER_CERTIFICATE:
				$msg = "Could not verify ... SSL certificate. Please, try accessing $server on your browser to make sure that your network is not intercepting certificates.";
				break;
			default:
				$msg = "Unexpected error communicating with ....";
		}
		$msg .= "\n[($err) $errmsg]";
		throw new PWMB_ConnectionException($msg);
	}
	
	private function handleResponse($code, $body) {
		switch ($code) {
			case 200:
				// Everything is okay. Continue.
				break;
			default:
				throw new PWMB_Exception("Unexpected error.\n[HTTP Code: $code]");
				break;
		}
		
		$response = json_decode($body);
		if (is_null($response) || json_last_error() !== JSON_ERROR_NONE) {
			throw new PWMB_Exception("Error decoding response.");
		}
		
	}

	public function get($path) {
		$response = request(
			array(
				CURLOPT_URL => REST::getUrl().$path,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
				CURLOPT_USERPWD => "$this->username:$this->password",
				CURLOPT_HTTPHEADER => array('Accept: application/json')
			)
		);
		return $response;
	}

	public function post($path, $data) {
		$response = request(
			array(
				CURLOPT_URL => REST::getUrl().$path,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
				CURLOPT_USERPWD => "$this->username:$this->password",
				CURLOPT_HTTPHEADER => array('Accept: application/json')
			)
		);
		return $response;
	}
}
