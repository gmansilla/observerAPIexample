<?php

class Supervisor implements SplObserver
{

	/**
	 * id for this Supervisor Object
	 * @var string
	 */
	protected $name;

	/**
	 * Start time when this Supervisor should be notified about access to the vault
	 * @var DateTime
	 */
	protected $notificationStartTime;

	/**
	 * End time when this Supervisor should be notified about access to the vault
	 * @var DateTime
	 */
	protected $notificationEndTime;

	/**
	 * Constructor
	 * @param $name
	 * @param DateTime $notificationStartTime
	 * @param DateTime $notificationEndTime
	 */
	public function __construct($name, DateTime $notificationStartTime, DateTime $notificationEndTime)
	{
		$this->name = $name;
		$this->notificationStartTime = $notificationStartTime;
		$this->notificationEndTime = $notificationEndTime;
	}

	/**
	 * Receives update from subject (Vault)
	 * As of now, we only log the notification. We could've used a Logger or Mailer class for this.
	 * @param SplSubject $subject
	 * @return void
	 */
	public function update(SplSubject $subject)
	{
		$vaultAccessTime = $subject->getLastAccessToVaultDate();
		if ($vaultAccessTime >= $this->notificationStartTime && $vaultAccessTime <= $this->notificationEndTime) {
			error_log("$this->name is being notified about access to vault at " . $vaultAccessTime->format('H:i:s'));
		}
	}
}