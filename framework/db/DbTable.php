<?php
class DbTable extends Object
{
	public $name;
	private $fields;
	private $conn;
	
	function __construct($conn, $name)
	{
		// var_dump($conn) . '<br>';
		$this->conn = $conn;
		// var_dump($this->conn);
		$this->name = $name;
		$this->addGetter('fields');
	}
	
	public function getFields()
	{
		if(!$this->fields)
		{
			$this->fields = array();
			foreach($this->conn->getTableFieldInfo($this->name) as $fieldInfo)
			{
				$this->fields[] = new DbField($fieldInfo);
			}
		}
		
		return $this->fields;
	}
	
	//
	//	begin iterator functions
	//
	
	public function rewind()
	{
		$this->getFields();
		reset($this->fields);
	}

	public function current()
	{
		$var = current($this->fields);
		return $var;
	}

	public function key()
	{
		$var = key($this->fields);
		return $var;
	}

	public function next()
	{
		$var = next($this->fields);
		return $var;
	}

	public function valid()
	{
		$var = $this->current() !== false;
		return $var;
	}
	
	//
	//	end iterator functions
	//
}