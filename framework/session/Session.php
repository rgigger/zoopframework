<?php
class Session
{
	static function start()
	{
		SessionModule::getEngine()->start();
	}
	
	static function get($key = '__default__')
	{
		SessionModule::getEngine()->get($key);
	}
	
	static function getWithLock($key = '__default__')
	{
		SessionModule::getEngine()->getWithLock($key);
	}
	
	static function set($value, $key = '__default__')
	{
		SessionModule::getEngine()->set($value, $key);
	}
	
	static function saveChangesUnsafe()
	{
		SessionModule::getEngine()->saveChangesUnsafe();
	}
	
	static public function destroy()
	{
		// Unset all of the session variables.
		$_SESSION = array();

		// delete the session cookie
		if (isset($_COOKIE[session_name()]))
		    setcookie(session_name(), '', time()-42000, '/');

		// Finally, destroy the session.
		session_destroy();		
	}
	
	static public function getId()
	{
		return session_id();
	}
}
