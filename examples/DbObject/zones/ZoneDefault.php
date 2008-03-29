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
	
	public function pageQuery()
	{
		$jane = DbObject::_getOne('PersonStuff', array('firstname' => 'Jane', 'lastname' => 'Doe'));
		
		$all = DbObject::_find('PersonStuff');
		$this->assign('all', $all);
		
		$odd = DbObject::_findByWhere('PersonStuff', "id % 2 == 0", array());
		$this->assign('odd', $odd);
		
		$sqled = DbObject::_findBySql('PersonStuff', "select id from person_stuff where firstname like 'J%'", array());
		$this->assign('sqled', $sqled);
		
		$one = DbObject::_findOne('PersonStuff', array('firstname' => 'Jane'));
		$this->assign('one', $one);
	}
	
	public function pageCreate()
	{
		//	just put some data in there without actually creating or returning any objects
		DbObject::_insert('PersonStuff', array('firstname' => 'Common', 'lastname' => 'Name'));
		
		//	get it if it's there, create and return it if it's not there
		$promised = DbObject::_getOne('PersonStuff', array('firstname' => 'Definitely', 'lastname' => 'There'));
		$this->assign('promised', $promised);
		
		$new = DbObject::_create('PersonStuff', array('firstname' => 'Unique', 'lastname' => 'Person'));
		$this->assign('new', $new);
		
		$all = DbObject::_find('PersonStuff');
		$this->assign('all', $all);
	}
	
	public function pageGuid($p, $z)
	{
		$gp = new GuidPerson();
		$gp->id = date("D M j G:i:s u T Y") . rand(0, 10000);
		$gp->firstname = "Guid";
		$gp->lastname = "Person";
		$gp->save();
		
		$all = DbObject::_find('GuidPerson');
		$this->assign('all', $all);
		
		$this->assign('gp', $gp);
	}
	
	public function pageMulti($p, $z)
	{
		$gp = new MultiPerson();
		$gp->one = rand(0, 10000);
		$gp->two = rand(0, 10000);
		$gp->three = rand(0, 10000);
		$gp->firstname = "Multi-field Primary Key";
		$gp->lastname = "Person";
		$gp->save();
		
		$all = DbObject::_find('MultiPerson');
		$this->assign('all', $all);
		
		$this->assign('gp', $gp);
	}
	
	public function pageDestroy()
	{
		//	you shouldn't really do stuff like this in a page function
		$all = DbObject::_find('PersonStuff');
		foreach($all as $thisObject)
		{
			$thisObject->destroy();
		}
		
		$all = DbObject::_find('GuidPerson');
		foreach($all as $thisObject)
		{
			$thisObject->destroy();
		}
	}
}
