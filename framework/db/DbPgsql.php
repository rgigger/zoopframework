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
		if(isset($params['host']))
			$connString .= ' host=' . $this->params['host'];
		if(isset($params['port']))
			$connString .= ' port=' . $this->params['port'];
		
		$this->connectionString = $connString;
	}
	
	public function getRequireds()
	{
		return array('database', 'username');
	}
	
	function escapeString($string)
	{
		return "'" . pg_escape_string($this->connection, $string) . "'";
	}
	
	function _query($sql)
	{
		//	lazy connection to the database
		if(!$this->connection)
			$this->connection = pg_connect($this->connectionString, PGSQL_CONNECT_FORCE_NEW);
		
		$result = pg_query($this->connection, $sql);
		return new DbPgResult($result);
	}
	
	function getLastInsertId()
	{
		return $this->fetchCell("select lastval()", array());
	}
}
