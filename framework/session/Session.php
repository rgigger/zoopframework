<?php
class Session
{
	function start()
	{
		SessionModule::getEngine()->start();
	}
	
	function get($key = '__default__')
	{
		SessionModule::getEngine()->get($key);
	}
	
	function getWithLock($key = '__default__')
	{
		SessionModule::getEngine()->getWithLock($key);
	}
	
	function set($value, $key = '__default__')
	{
		SessionModule::getEngine()->set($value, $key);
	}
	
	function saveChangesUnsafe()
	{
		SessionModule::getEngine()->saveChangesUnsafe();
	}
	
	public function destroy()
	{
		// Unset all of the session variables.
		$_SESSION = array();

		// delete the session cookie
		if (isset($_COOKIE[session_name()]))
		    setcookie(session_name(), '', time()-42000, '/');

		// Finally, destroy the session.
		session_destroy();		
	}
}
