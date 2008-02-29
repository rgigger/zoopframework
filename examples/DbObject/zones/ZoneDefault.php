<?php
class ZoneDefault extends GuiZone
{
	public function closePages($p)
	{
		$this->display($p[0]);
	}
	
	function pageDefault()
	{
		/*
		$ps = new PersonStuff();
		$ps->firstname = 'Normal';
		$ps->lastname = 'Person';
		echo_r($ps);
		
		$values = array('id' => md5(time()), 'firstname' => 'Unique', 'lastname' => 'Person');
		$gp = DbObject::_create('GuidPerson', $values);
		echo_r($gp);
		
		$gp->firstname = 'changed';
		$gp->save();		
		echo_r($gp);
		*/
		
		$o = DbObject::_findOne('GuidPerson', array('id' => 'd78acd9cad41bf3b3a0aaa3b4b106c4e'));
		echo_r($o);
		$o->firstname = 'newname';
		echo_r($o);
		$o->save();
		echo_r($o);
	}	
}
