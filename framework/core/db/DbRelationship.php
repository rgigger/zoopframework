<?php
abstract class DbRelationship
{
	protected $name;
	protected $dbObject;
	protected $params;
	
	function __construct($name, $params, $dbObject)
	{
		$this->name = $name;
		$this->dbObject = $dbObject;
		$this->params = $params;
	}
	
	abstract public function getInfo();
}