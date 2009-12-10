<?php
class ZoneDefault extends AppZone
{
	function pageDefault($p)
	{
		$this->redirect('login');
	}
	
	function pageLogin($p)
	{
	}
	
	function postLogin($p)
	{
		$success = person::auth($_POST['username'], $_POST['password']);
		
		if(!$success)
			$this->redirect('login?auth=bad');
		
		$id = person::getLoggedInId();
		$this->redirect("main");
	}
	
	function pageMain($p)
	{
		$person = Person::getLoggedInUser();
		$this->assign('person', $person);
	}
	
	function postMain($p)
	{
		$person = Person::getLoggedInUser();
		
		switch(Time::getAction())
		{
			case 'start':
				$person->startEntry();
				break;
			case 'stop':
				$person->stopEntry();
				break;
			case 'create':
				$person->addEntry(GetPostDate('start'), GetPostDate('end'));
				break;
		}		
	}
}
