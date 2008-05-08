<?php
class DbTable extends Object implements Iterator
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
		
		//	get rid of fields and just use columns
		$this->addGetter('fields');
		$this->addGetter('columns');
	}
	
	//	change this to getColumns
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
	
	//	alias for getColumns (this is the prefered method to use)
	public function getColumns()
	{
		return $this->getFields();
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