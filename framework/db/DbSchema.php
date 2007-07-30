<?php
class DbSchema
{
	var $conn;
	
	function DbSchema($conn)
	{
		$this->conn = $conn;
	}
	
	function tableExists($name)
	{
		$sql = "SELECT table_name
				FROM information_schema.tables
				WHERE table_type = 'BASE TABLE'
					AND table_schema NOT IN ('pg_catalog', 'information_schema')
					AND table_name = :name";
		return SqlFetchCell($sql, array('name' => $name)) ? 1 : 0;
	}
}