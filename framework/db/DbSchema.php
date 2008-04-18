<?php
class DbSchema extends Object
{
	private $conn;
	private $tables;
	
	function __construct($conn)
	{
		$this->conn = $conn;
		$this->addGetter('tables');
	}
	
	public function tableExists($tableName)
	{
		return $this->conn->tableExists($tableName);
	}
	
	public function getTables()
	{
		if(!$this->tables)
		{
			$this->tables = array();
			foreach($this->conn->getTableNames() as $thisTableName)
			{
				$this->tables[$thisTableName] = new DbTable($this->conn, $thisTableName);
			}
		}
		
		return $this->tables;
	}
}
