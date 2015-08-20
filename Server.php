<?php

class Server
{

	/**
	 * @var Vault $vault object
	 */
	protected $vault;

	/**
	 * @var array statuses to be used in response
	 */
	protected $statuses = [
		200 => 'OK',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
	];

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->vault = new Vault();
		$this->createSupervisors();
	}


	/**
	 * Creates Supervisor objects and attach them as observers to the Vault
	 * Supervisor 1: 0:00 - 5:59
	 * Supervisor 2: 06:00 - 11:59
	 * Supervisor 3: 12:00 - 17:59
	 * Supervisor 4: 16:00 - 23:59
	 */
	protected function createSupervisors()
	{
		$startHour = 0;
		$endHour = 5;

		for ($i = 1; $i < 5; $i++) {
			$startTime = new DateTime('now');
			$endTime = new DateTime('now');
			$startTime->setTime($startHour, 0);
			$endTime->setTime($endHour, 59);
			$startHour += 6;
			$endHour += 6;
			$supervisor = new Supervisor('Supervisor ' . $i, $startTime, $endTime);
			$this->vault->attach($supervisor);
		}
	}

	/**
	 * Processes requests.
	 * For simplicity sakes only one method/uri is supported
	 */
	public function process()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$method = $_SERVER['REQUEST_METHOD'];
		if ($uri != '/vault/open') {
			$this->sendResponse("Resource not found", 404);
			return;
		}
		if ($method != 'PUT') {
			$this->sendResponse("Method not allowed", 405);
			return;
		}
		$response = $this->vault->enterVault();
		$this->sendResponse($response);
	}

	/**
	 * Sends the actual response
	 * @param string $response
	 * @param int $code
	 */
	public function sendResponse($response, $code = 200)
	{
		header("Content-Type: application/json");
		header("HTTP/1.1 " . $code . " " . $this->statuses[$code]);
		$result['message'] = $response;
		echo json_encode($result);
	}
}