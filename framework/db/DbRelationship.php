<?php
abstract class DbRelationship
{
	protected $name;
	protected $dbObject;
	
	function __construct($name, $params, $dbObject)
	{
		$this->name = $name;
		$this->dbObject = $dbObject;
	}
	
	abstract public function getInfo();
}