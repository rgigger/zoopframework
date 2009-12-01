<?php
class Person extends DbObject
{
	protected function init()
	{
		$this->hasMany('Entry');
	}
	
	//	static
	function auth($username, $password)
	{
		$id = SqlFetchCell("select id from person where username = :username and password = :password", 
					array('username' => $username, 'password' => $password));
		
		if($id)
			Person::setLoggedInId($id);
		
		return $id ? 1 : 0;
	}
	
	//	static
	function getLoggedInUser()
	{
		$id = person::getLoggedInId();
		if($id)
			return new Person((int)$id);
		else
			return NULL;
	}
	
	//	static
	function getLoggedInId()
	{
		return isset($_SESSION['personId']) ? $_SESSION['personId'] : NULL;
	}
	
	//	static
	function setLoggedInId($id)
	{
		$_SESSION['personId'] = $id;
		session::saveChangesUnsafe();
	}
	
	function addEntry($startTime, $endTime)
	{
		$entry = new Entry(NULL, array('person_id' => $this->getId(), 'starttime' => $startTime, 'endtime' => $endTime, 'project_id' => 1));
	}
	
	function startEntry($startTime = NULL)
	{
		if(!$startTime)
			$startTime = "now()";
		$entry = new Entry(NULL, array('person_id' => $this->getId(), 'starttime' => $startTime, 'project_id' => 1));
	}
	
	function stopEntry()
	{
		$openEntry = $this->getOpenEntry();
		if(!$openEntry)
			trigger_error('trying to top the open entry when it doesn\'t exist');
		
		$openEntry->stop();
	}
	
	function getOpenEntry()
	{
		//	there should be a method in DbObject for doing lookups like this
		$entryInfo = SqlFetchCell("select * from entry where person_id = :id and starttime is not null and endtime is null", array('id' => $this->id));
		if(!$entryInfo)
			return NULL;
		
		return new Entry($entryInfo);
	}
}