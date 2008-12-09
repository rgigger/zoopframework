<?php
class DbPgsql extends DbConnection
{
	private $connectionString;
	private $connection;
	
	public function init()
	{
		//	create the connection string
		$connString = 'dbname=' . $this->params['database'];
		$connString .= ' user=' . $this->params['username'];
		if(isset($this->params['host']))
			$connString .= ' host=' . $this->params['host'];
		if(isset($this->params['port']))
			$connString .= ' port=' . $this->params['port'];
		
		$this->connectionString = $connString;
	}
	
	public function getRequireds()
	{
		return array('database', 'username');
	}
	
	function escapeString($string)
	{
		self::connect();	
		if(version_compare(PHP_VERSION, "5.2", "<"))
			return "'" . pg_escape_string($string) . "'";
		else
			return "'" . pg_escape_string($this->connection, $string) . "'";
	}
	
	function _query($sql)
	{
		self::connect();	
		$result = pg_query($this->connection, $sql);
		return new DbPgResult($this->connection, $result);
	}
	
	function getLastInsertId()
	{
		return $this->fetchCell("select lastval()", array());
	}
	
	private function connect()
	{
		//	lazy connection to the database
		if(!$this->connection)
			$this->connection = pg_connect($this->connectionString, PGSQL_CONNECT_FORCE_NEW);
	}
	
	public function tableExists($name)
	{
		$sql = "SELECT table_name
				FROM information_schema.tables
				WHERE table_type = 'BASE TABLE'
					AND table_schema NOT IN ('pg_catalog', 'information_schema')
					AND table_name = :name";
		
		return $this->fetchCell($sql, array('name' => $name)) ? 1 : 0;
	}
	
	public function getTableNames()
	{
		$sql = "SELECT table_name
				FROM information_schema.tables
				WHERE table_type = 'BASE TABLE' and table_schema <> 'pg_catalog' and table_schema <> 'information_schema'";
		
		return $this->fetchColumn($sql, array());
	}
	
	public function getTableFieldInfo($tableName)
	{
		$sql = "SELECT 
					column_name as name, 
					data_type as type
					--, *
				from information_schema.columns where table_schema = 'public' and table_name = :name";
		return $this->fetchRows($sql, array('name' => $tableName));		
	}
}
