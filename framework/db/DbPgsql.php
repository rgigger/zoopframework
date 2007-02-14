<?php
class DbPgsql extends DbConnection
{
	function DbPgsql()
	{
		$this->conn = pg_connect("dbname=social user=postgres", PGSQL_CONNECT_FORCE_NEW);
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
	
}