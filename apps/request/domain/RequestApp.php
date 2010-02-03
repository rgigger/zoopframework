<?php
class RequestApp
{
	static public function auth($username, $password)
	{
		$id = SqlFetchCell("select id from person where username = :username and password = :password",
				array('username' => $username, 'password' => $password));
		
		if(!$id)
			return false;
		
		$_SESSION['personId'] = $id;
		session::saveChangesUnsafe();
		
		return true;
	}
	
	static public function getLoggedInUser()
	{
		return isset($_SESSION['personId']) && $_SESSION['personId'] ? new Person($_SESSION['personId']) : false;
	}

	static public function userIsLoggedIn()
	{
		return isset($_SESSION['personId']) && $_SESSION['personId'] ? true : false;
	}
}
