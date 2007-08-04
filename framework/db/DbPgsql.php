<?php
class DbPgsql extends DbConnection
{
	private $connectionString;
	private $connection;
	
	function __construct($params, $name)
	{
		//	the parent contructor will validate all of the paramaters 
		//		inlcuding handling error messages to the user for missing config options
		//		and filling in default params
		parent::__construct($params, $name);
		
		//	create the connection string
		$connString = 'dbname=' . $params['database'];
		$connString .= ' user=' . $params['username'];
		if(isset($params['host']))
			$connString .= ' host=' . $params['host'];
		if(isset($params['port']))
			$connString .= ' port=' . $params['port'];
		
		$this->connectionString = $connString;
	}
	/*

	function DbPgsql($params)
	{
		$connString = 'dbname=' . $params['database'];
		$connString .= ' user=' . $params['username'];
		if(isset($params['host']))
			$connString .= ' host=' . $params['host'];
		if(isset($params['port']))
			$connString .= ' port=' . $params['port'];
		
		$this->conn = pg_connect($connString, PGSQL_CONNECT_FORCE_NEW);
	}
	*/
	
	function escapeString($string)
	{
		return "'$string'";
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