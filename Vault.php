<?php

class Vault implements SplSubject
{

	/**
	 * Vault status. 0: closed. 1: open.
	 * Note: There is no method to set the vault as closed.
	 * @var int
	 */
	protected $status;

	/**
	 * Date of last access to the vault
	 * @var DateTime
	 */
	protected $lastAccessToVaultDate;

	/**
	 * Mapper for objects (observers)
	 * @var SplObjectStorage
	 */
	private $observers;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->status = 0; //Closed by default
		$this->observers = new SplObjectStorage();
	}

	/**
	 * Opens the vault (change its status) and notifies all observers attached to the vault object.
	 * @return string
	 */
	public function enterVault()
	{
		$this->status = 1;
		$this->lastAccessToVaultDate = new DateTime('now');
		$this->notify();
		$responseText = 'You have entered the vault at ' . $this->lastAccessToVaultDate->format('H:i:s');
		return $responseText;
	}

	/**
	 * @todo this method is not exposed yet to the api
	 */
	public function exitVault()
	{
		$this->status = 0;
	}

	/**
	 * @return mixed
	 */
	public function getLastAccessToVaultDate()
	{
		return $this->lastAccessToVaultDate;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Attach an SplObserver
	 * @link http://php.net/manual/en/splsubject.attach.php
	 * @param SplObserver $observer <p>
	 * The <b>SplObserver</b> to attach.
	 * </p>
	 * @return void
	 */
	public function attach(SplObserver $observer)
	{
		$this->observers->attach($observer);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Detach an observer
	 * @link http://php.net/manual/en/splsubject.detach.php
	 * @param SplObserver $observer <p>
	 * The <b>SplObserver</b> to detach.
	 * </p>
	 * @return void
	 */
	public function detach(SplObserver $observer)
	{
		$this->observers->detach($observer);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Notify an observer
	 * @link http://php.net/manual/en/splsubject.notify.php
	 * @return void
	 */
	public function notify()
	{
		foreach ($this->observers as $observer) {
			$observer->update($this);
		}
	}
}