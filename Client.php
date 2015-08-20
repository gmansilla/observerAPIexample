<?php

class Client
{

	/**
	 * Sends a request to /vault/open
	 */
	public function openVault() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "http://medreleaf.dev/vault/open");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		$response = curl_exec($curl);
		$this->parseResponse($response);
	}

	/**
	 * Parses the response from api call
	 * @param $response
	 */
	private function parseResponse($response)
	{
		echo json_decode($response)->message;
	}

}

$client = new Client();
$client->openVault();