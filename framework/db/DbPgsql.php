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
		return new DbPgResult($result);
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
	
}
