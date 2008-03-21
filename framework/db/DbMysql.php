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
		self::connect();	
		return "'" . mysql_real_escape_string($string, $this->connection) . "'";
	}
	
	function _query($sql)
	{	
		self::connect();	
		$result = mysql_query($sql, $this->connection) 
			or trigger_error(mysql_error());
		
		if(gettype($result) == 'boolean')
			return $result;
		
		return new DbMysqlResult($result);
	}
	
	function getLastInsertId()
	{
		self::connect();	
		return mysql_insert_id($this->connection);
	}
	
	private function connect()
	{
		//	lazy connection to the database
		if(!$this->connection)
		{
			$this->connection = mysql_connect($this->params['host'], $this->params['username'], $this->params['password'])
				or trigger_error(mysql_error());
			mysql_select_db($this->params['database'], $this->connection)
				or trigger_error(mysql_error());
		}
	}
}
