<?php
/**
 * This is the A  modified SDK for PHP that was used in a real domain.  Some end-points have been changed to protect the innocent
 * Include this file in your project to use it.
 * 
 */

require_once(dirname(__FILE__).'/lib/Request.php');
 
class REST {
	//TODO Add production URL
	const LIVE_ENDPOINT = '';
	const SANDBOX_ENDPOINT = https://some.end.point/api';

	private $request;
	private $accessId;
	private $accessKey;

	public function __construct($id, $key, $timeZone = null, $endpoint = null) {
		$this->id = $id;
		$this->key = $key;
		$this->request = new Request;
		$this->request->setAuthentication($this->id, $this->key);

		$this->request->setEndpoint($endpoint);
	}
	
	public function disableSecureConnection() {
		$this->request->disableSecureConnection();
	}
	
	public function getTransaction($id) {
	}

	public function listTransactions() {
	}
	
	public function listTransactionEvents() {
	}
	
	public function cancel($id) {
	}

	public function capture() {
	}

	public function refund() {
	}
	
}
