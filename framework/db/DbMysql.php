<?php
class DbMysql extends DbConnection
{
	private $connection;
	
	public function getRequireds()
	{
		return array('database', 'username');
	}
	
	public function getDefaults()
	{
		return array('host' => 'localhost', 'port' => 3306, 'password' => NULL);
	}
	
	function escapeString($string)
	{
		return "'" . mysql_real_escape_string($string, $this->connection) . "'";
	}
	
	function _query($sql)
	{
		//	lazy connection to the database
		if(!$this->connection)
		{
			$this->connection = mysql_connect($this->params['host'], $this->params['username'], $this->params['password']);
			mysql_select_db($this->params['database'], $this->connection)
				or trigger_error(mysql_error());;
		}
		
		$result = mysql_query($sql, $this->connection) 
			or trigger_error(mysql_error());
		
		return new DbMysqlResult($result);
	}
	
	function getLastInsertId()
	{
		return mysql_insert_id($this->connection);
	}
}
