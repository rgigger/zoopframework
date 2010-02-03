<?php
class DbMssql extends DbConnection
{	
	private $connection;
	
	public function getRequireds()
	{
		return array('database', 'username');
	}
	
	function escapeString($string)
	{
		return "'" . str_replace(array("'", "\0"), array("''", "[NULL]"), $string) . "'";
	}
	
	function _query($sql)
	{
		self::connect();	
		$result = mssql_query($sql, $this->connection);
		return new DbMssqlResult($this->connection, $result);
	}
	
	function getLastInsertId()
	{
		return $this->fetchCell("select SCOPE_IDENTITY()", array());
	}
	
	private function connect()
	{
		//	lazy connection to the database
		if(!$this->connection)
		{
			$host = isset($this->params['port']) && $this->params['port'] ? $this->params['host'] . ',' . $this->params['port'] : $this->params['host'];
			$this->connection = mssql_connect($host, $this->params['username'], $this->params['password'], true);
			mssql_select_db($this->params['database'], $this->connection);
		}
			
	}
	
	public function tableExists($name)
	{
		// http://www.lejalgenes.com/techtips/tips/Microsoft_SQL_Server/Information_Schema_View_Listing.php
		trigger_error("schema information not implemented for mssql");
	}
	
	public function getTableNames()
	{
		$sql = "SELECT table_name
				FROM information_schema.tables
				WHERE table_type = 'BASE TABLE'";
		
		return $this->fetchColumn($sql, array());
	}
	
	public function getTableFieldInfo($tableName)
	{
		$sql = "SELECT 
					column_name as name, 
					data_type as type
				from information_schema.columns where table_name = :name";
		return $this->fetchRows($sql, array('name' => $tableName));		
	}
}
