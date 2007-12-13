<?php
class ZonePerson extends AppZone
{
	function pageDefault()
	{
		$this->redirect('list');
	}
	
	function pageList($p, $z)
	{
		$people = DbObject::_find('Person');
		$this->assign('people', $people);
	}
	
	function pageEdit($p, $z)
	{
		$personId = $p[1];
		$person = new Person($personId);
	}
	
	function postEdit($p, $z)
	{
		$personId = $p[1];
		$person = new Person($personId);
		$person->firstname = $_POST['firstname'];
		$person->lastname = $_POST['lastname'];
		$person->save();
		
		if($z['personId'] == 0)
			$this->setParam('personId', $person->id);
		
		$this->redirect('view');
	}
	
	function pageView($p, $z)
	{
		$person = new Person($z['personId']);
		$this->assign('person', $person);
	}
}