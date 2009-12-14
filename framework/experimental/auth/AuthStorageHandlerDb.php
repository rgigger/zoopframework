<?php
class AuthDbStorageDb implements AuthStorageHandler
{
	private $info;
	
	function __construct($dbInfo)
	{
		$this->info = $dbInfo;
	}
	
	public function getInfo($username)
	{
		return SqlFetchRow('select ' . $this->info['username'] . ' as username, ' . $this->info['password'] . ' as password, ' . 
						$this->info['nonce'] . ' as nonce, ' . $this->info['password_type'] . ' as password_type from ' . 
						$this->info['table'] . ' where ' . $this->info['username'] . ' = :username', 
						array('username' => $username));
	}
	
	public function setInfo($username, $info)
	{
		trigger_error('net yet implemented');
	}
}