<?php
class DbMssql extends DbConnection
{	
	public function getRequireds()
	{
		return array('database', 'username');
	}
	
	function escapeString($string)
	{
		return str_replace(array("'", "\0"), array("''", "[NULL]"), $string);
	}
	
	function _query($sql)
	{
		self::connect();	
		$result = mssql_query($sql, $this->connection);
		return new DbPgResult($this->connection, $result);
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
		// http://www.lejalgenes.com/techtips/tips/Microsoft_SQL_Server/Information_Schema_View_Listing.php
		trigger_error("schema information not implemented for mssql");
	}
	
	public function getTableFieldInfo($tableName)
	{
		// http://www.lejalgenes.com/techtips/tips/Microsoft_SQL_Server/Information_Schema_View_Listing.php
		trigger_error("schema information not implemented for mssql");
	}
}
