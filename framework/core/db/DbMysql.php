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

	/**
	 * Escapes the field name (to handle fields with special names, i.e. insert, select, update, now, date, etc)
	 *
	 * @param string $fieldName name of the field
	 * @return string escaped string (this method should be overridden by each db class to escape properly if needed)
	 */
	public function escapeIdentifier($fieldName)
	{
		return "`" . $fieldName . "`";
	}

	/**
	 * Checks if a given table exists in the database
	 *
	 * @param string $name Name of the table to look for
	 * @return boolean True if the table exists in this database
	 */
	public function tableExists($name)
	{
		trigger_error("tableExists method not yet implemented in DbMysql");
	}

	/**
	 * Returns an array of table names that exist in the database
	 *
	 * @return array Array of table names
	 */
	public function getTableNames()
	{
		trigger_error("getTableNames is not yet implemented in DbMysql");
	}

	/**
	 * Returns field information about the specified table
	 *
	 * @param string $tableName Name of the table to return information about
	 */
	public function getTableFieldInfo($tableName)
	{
		trigger_error("getTableFieldInfo is not yet implemented in DbMysql");
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

		return new DbMysqlResult($this->connection, $result);
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
