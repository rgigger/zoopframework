<?php
class DbPgsql extends DbConnection
{
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
	
	function escapeString($string)
	{
		return "'$string'";
	}
	
	function _query($sql)
	{
		$result = pg_query($this->conn, $sql);
		return new DbPgResult($result);
	}
	
	function getLastInsertId()
	{
		return $this->fetchCell("select lastval()", array());
	}
}