<?php
class ZoneDefault extends GuiZone
{
	public function closePages($p)
	{
		$this->display($p[0]);
	}
	
	function pageDefault()
	{
		$ps = new PersonStuff();
		$ps->firstname = 'Normal';
		$ps->lastname = 'Person';
		echo_r($ps);
		
		$values = array('id' => md5('disco'), 'firstname' => 'Unique', 'lastname' => 'Person');
		$gp = DbObject::_create('GuidPerson', $values);
		echo_r($gp);
	}	
}
