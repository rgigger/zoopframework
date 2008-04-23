<?php
class DbPdo extends DbConnection
{
	function init()
	{
		if($this->params['file'][0] != '/')
			$this->params['file'] = app_dir . '/var/' . $this->params['file'];

		$this->conn = new PDO('sqlite:' . $this->params['file']);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NAMED);
	}

	function escapeString($string)
	{
		return $this->conn->quote($string);
	}

		/**
	 * Checks if a given table exists in the database
	 *
	 * @param string $name Name of the table to look for
	 * @return boolean True if the table exists in this database
	 */
	public function tableExists($name)
	{
		trigger_error("tableExists method not yet implemented in DbPdo");
	}

	/**
	 * Returns an array of table names that exist in the database
	 *
	 * @return array Array of table names
	 */
	public function getTableNames()
	{
		trigger_error("getTableNames is not yet implemented in DbPdo");
	}

	/**
	 * Returns field information about the specified table
	 *
	 * @param string $tableName Name of the table to return information about
	 */
	public function getTableFieldInfo($tableName)
	{
		trigger_error("getTableFieldInfo is not yet implemented in DbPdo");
	}

	function _query($sql)
	{
		$result = $this->conn->query($sql);
		return new DbPdoResult($this->conn, $result);
	}

	function getLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
}
