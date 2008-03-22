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
			tables with different id fields
			tables with multiple primary key fields
			tables with one id field that is auto assigned
			pass it into a template and access the properties
			get the vector things working and have it traverse a heirarchy
		*/		
	}
	
	public function pageActiveRecord()
	{
		$ps = new PersonStuff();
		$ps->firstname = 'Bob';
		$ps->lastname = 'Smith';
		$ps->save();
		
		$this->assign('ps', $ps);
	}
	
	public function pageStatic()
	{		
		$jane = DbObject::_getOne('PersonStuff', array('firstname' => 'Jane', 'lastname' => 'Doe'));
		$john = DbObject::_getOne('PersonStuff', array('firstname' => 'John', 'lastname' => 'Doe'));
		$john = DbObject::_getOne('PersonStuff', array('firstname' => 'Bill', 'lastname' => 'Johnson'));
		
		//	just put some data in there without actually creating or returning any objects
		DbObject::_insert('PersonStuff', array('firstname' => 'Common', 'lastname' => 'Name'));
		
		$special = DbObject::_create('PersonStuff', array('firstname' => 'Unique', 'lastname' => 'Person'));
		$this->assign('special', $special);
		
		$all = DbObject::_find('PersonStuff');
		$this->assign('all', $all);
		
		$odd = DbObject::_findByWhere('PersonStuff', "id % 2 == 0", array());
		$this->assign('odd', $odd);
		
		$sqled = DbObject::_findBySql('PersonStuff', "select id from person_stuff where firstname like 'J%'", array());
		$this->assign('sqled', $sqled);
		
		$one = DbObject::_findOne('PersonStuff', array('firstname' => 'Jane'));
		$this->assign('one', $one);
	}
	
	public function pageDestroy()
	{
		//	you really shouldn't do stuff like this in a page function
		$all = DbObject::_find('PersonStuff');
		foreach($all as $thisObject)
		{
			$thisObject->destroy();
		}
	}
	
	
}
