<?php
class DbField
{
	public $name;
	public $type;
	
	function __construct($info)
	{
		$this->name = $info['name'];
		$this->type = $info['type'];
	}
}