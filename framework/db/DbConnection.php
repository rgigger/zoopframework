<?php
abstract class DbConnection
{
	protected $params;
	private $queryParams;
	private $types;
	/**
	 * Actual connection to the database
	 *
	 * @var unknown_type
	 */
	private $conn;
	private $echo;

	function __construct($params, $connectionName)
	{
		$this->params = $params;
		$this->validateParams($connectionName);
		$echo = false;
		$this->init();
	}

	//
	//	Begin configuration funtions
	//

	private function validateParams($connectionName)
	{
		//	handle the required fields
		$missing = array();
		foreach($this->getRequireds() as $thisRequired)
		{
			if(!isset($this->params[$thisRequired]))
				$missing[] = $thisRequired;
		}

		if(!empty($missing))
			throw new ConfigException('db', $missing, "for connection $connectionName");

		//	handle the defaults
		foreach($this->getDefaults() as $name => $value)
		{
			if(!isset($this->params[$name]))
				$this->params[$name] = $value;
		}
	}

	protected function init()
	{
	}

	protected function getRequireds()
	{
		return array();
	}

	protected function getDefaults()
	{
		return array();
	}

	//
	//	End configuration funtions
	//

	//
	//	Begin misc funtions
	//

	public function echoOn()
	{
		$this->echo = true;
	}

	public function echoOff()
	{
		$this->echo = false;
	}

	function escapeString($string)
	{
		trigger_error("escapeString must be defined in each individual database driver");
	}

	function escapeIdentifier($string)
	{
		return '"' . $string . '"';
	}

	//
	//	End misc funtions
	//

	//
	//	Begin Schema functions
	//

	public function alterSchema($sql)
	{
		return $this->_query($sql);
	}

	public function getSchema()
	{
		return new DbSchema($this);
	}

	abstract public function tableExists($name);
	abstract public function getTableNames();
	abstract public function getTableFieldInfo($tableName);

	/**
	 * Executes the passed in SQL statement on the database and returns a result set
	 *
	 * @param string $sql SQL statement to execute
	 * @return DbResultSet object
	 */
	abstract public function _query($sql);

	//
	//	End Schema functions
	//

	//
	//	Begin transaction funtions
	//

	public function beginTransaction()
	{
		$this->_query('begin');
	}

	public function commitTransaction()
	{
		$this->_query('commit');
	}

	public function rollbackTransaction()
	{
		$this->_query('rollback');
	}

	//
	//	End transaction funtions
	//

	//
	//	Begin query funtions
	//

	/**
	 * Escapes and inserts parameters into the the SQL statement and executes the statement.
	 *
	 * Syntax for parameters:
	 * - :<varname>:string 	-- the variable is escaped as a string and replaces this entry
	 * - :<varname>:int 		-- the variable is escaped as a number and replaces this entry
	 * - :<varname>:keyword	-- the variable replaces this entry with no change
	 * - :<varname>:identifier	-- the variable is escaped as a SQL identifier (table, field name, etc) and replaces this entry
	 * - :<varname>			-- if no type is specified, variable is treated as a string.
	 *
	 * @param string $sql SQL statement to execute
	 * @param assoc_array $params $variable => $value array of parameters to substitute into the SQL statement
	 * @return DbResultSet Resultset object
	 */
	public function query($sql, $params)
	{
		//	do all of the variable replacements
		$this->queryParams = array();
		foreach($params as $key => $value)
		{
			$parts = explode(':', $key);
			$this->queryParams[$parts[0]] = $value;
			if(isset($parts[1]))
				$this->types[$parts[0]] = $parts[1];
		}
		// TODO: indication here how to escape options in here
		$sql = preg_replace_callback("/:([[:alpha:]_\d]+):([[:alpha:]_]+)|[^:]:([[:alpha:]_\d]+)/", array($this, 'queryCallback'), $sql);

		if($this->echo)
		{
			if(php_sapi_name() == "cli")
				echo $sql . "\n";
			else
				echo $sql . '<br>';
		}
			

		//	actually do the query
		return $this->_query($sql);
	}

	/**
	 * Private callback function passed to preg_replace_callback for doing the variable substitutions
	 * This method handles all escaping of strings and identifiers based on the matched list.
	 * Syntax:
	 * - :<varname>:string 	-- the variable is escaped as a string and replaces this entry
	 * - :<varname>:int 		-- the variable is escaped as a number and replaces this entry
	 * - :<varname>:keyword	-- the variable replaces this entry with no change
	 * - :<varname>:identifier	-- the variable is escaped as a SQL identifier (table, field name, etc) and replaces this entry
	 * - :<varname>			-- if no type is specified, variable is treated as a string.
	 *
	 * @param array $matches array of matches provided by preg_replace_callback
	 * @return string String to replace the matched string with
	 */
	private function queryCallback($matches)
	{
		if(isset($matches[3]))
			$matches[1] = $matches[3];

		$name = $matches[1];		
		if(is_null($this->queryParams[$name]))
			return 'NULL';
		if($matches[2])
			$type = $matches[2];

		if(isset($type) && isset($this->types[$name]))
			assert($type == $this->types[$name]);

		$type = isset($type) ? $type : (isset($this->types[$name]) ? $this->types[$name] : 'string');

		switch($type)
		{
			case 'string':
				$replaceString = $this->escapeString($this->queryParams[$name]);
				break;
			case 'int':
				$replaceString = (int)$this->queryParams[$name];
				break;
			case 'keyword':
				$replaceString = $this->queryParams[$name];
				break;
			case 'identifier':
				$replaceString = $this->escapeIdentifier($this->queryParams[$name]);
				break;
			default:
				trigger_error("unknown param type: " . $type);
				break;
		}

		return $replaceString;
	}

	//
	//	end query funtions
	//


	//
	//	Begin fetch funtions
	//

	public function fetchCell($sql, $params)
	{
		$row = $this->fetchRow($sql, $params);
		if(!$row)
			return NULL;
		return current($row);
	}

	public function fetchRow($sql, $params)
	{
		$res = $this->query($sql, $params);
		$num = $res->numRows();
		if($num > 1)
			trigger_error("1 row expected: $num returned");

		if($num == 0)
			return false;

		return $res->current();
	}

	public function fetchRows($sql, $params)
	{
		$rows = array();
		$res = $this->query($sql, $params);

		if(!$res->valid())
			return array();

		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			$rows[] = $row;
		}

		return $rows;
	}

	public function fetchColumn($sql, $params)
	{
		$rows = array();
		$res = $this->query($sql, $params);

		if(!$res->valid())
			return array();

		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			$rows[] = current($row);
		}

		return $rows;
	}


	/**
	 * Creates a simple nested array structure grouping the values of the $valueField column by the values of the columns specified in the $keyFields array.
	 *
	 * For example, if your query returns a list of books and you'd like to group the titles by subject and isbn number, let $keyFields = array("subject", "isbn") and $valueField = "title".
	 * The format thus created will be $var[$subject][$isbn] = $title;
	 *
	 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
	 * @param array $keyFields array of fields to group the results by
	 * @param array $valueField name of the field containing the value to be grouped
	 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
	 * @return associative array structure grouped by the values in $mapFields
	 */
	public function fetchSimpleMap($sql, $keyFields, $valueField, $params)
	{
		$map = array();
		$res = $this->query($sql, $params);
		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			$cur = &$map;
			if(is_array($keyFields))
			{
				foreach($keyFields as $key)
				{
					$cur = &$cur[$row[$key]];
					$lastKey = $row[$key];
				}
			}
			else
			{
				$cur = &$cur[$row[$keyFields]];
				$lastKey = $row[$keyFields];
			}

			if(isset($cur) && !empty($lastKey))
				trigger_error("db::fetchSimpleMap : duplicate key in query: \n $sql \n");

			$cur = $row[ $valueField ];
		}

		return $map;
	}

	/**
	 * Returns a nested array, grouped by the fields (or field) listed in $mapFields
	 *
	 * For example, if mapFields = array("person_id", "book_id"), and the resultset returns
	 * a list of all the chapters of all the books of all the people, this will group the
	 * records by person and by book, keeping each row in an array under
	 * $var[$person_id][$book_id]
	 *
	 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
	 * @param array $mapFields array of fields to group the results by
	 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
	 * @return associative array structure grouped by the values in $mapFields
	 */
	public function fetchMap($sql, $mapFields, $params)
	{
		$map = array();
		$res = $this->query($sql, $params);
		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			if(is_array($mapFields))
			{
				$cur = &$map;

				foreach($mapFields as $val)
				{
					$curKey = $row[$val];

					if(!isset($cur[$curKey]))
						$cur[$curKey] = array();

					$cur = &$cur[$curKey];
				}

				if(count($cur))
					trigger_error("db::fetchSimpleMap : duplicate key $curKey (would silently destroy data) in query: \n $inQuery \n");

				$cur = $row;
			}
			else
			{
				$mapKey = $row[$mapFields];
				$map[$mapKey] = $row;
			}
		}

		return $map;
	}

	public function fetchComplexMap($sql, $mapFields, $params)
	{
		$map = array();
		$res = $this->query($sql, $params);
		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			if(is_array($mapFields))
			{
				$cur = &$map;

				foreach($mapFields as $val)
				{
					$curKey = $row[$val];

					if(!isset($cur[$curKey]))
						$cur[$curKey] = array();

					$cur = &$cur[$curKey];
				}

				$cur[] = $row;
			}
			else
			{
				$mapKey = $row[$mapFields];
				$map[$mapKey][] = $row;
			}
		}

		return $map;
	}

	//
	//	End fetch functions
	//

	//
	//	Begin insert functions
	//

	public function insertRow($sql, $params, $serial = true)
	{
		$this->query($sql, $params);
		if($serial)
			return $this->getLastInsertId();
		else
			return false;
	}

	public function insertRows($sql, $params, $serial = false)
	{
		$ids = array();
		foreach($params as $thisRow)
		{
			$id = $this->insertRow($sql, $thisRow, $serial);
			if($serial)
				$ids[] = $id;
		}
	}

	/**
	 * Automatically insert the values of an ($key=>$value) array into a database using the $key as the field name
	 *
	 * @param string $tableName Table into which the insert should be performed
	 * @param array $values ($key => $value) array in the format ($fieldName => $fieldValue)
	 * @param boolean $serial True if the last inserted ID should be returned
	 * @return mixed ID of the inserted row or false if $serial == false
	 */
	public function insertArray($tableName, $values, $serial = true)
	{
		$insertInfo = DbConnection::generateInsertInfo($tableName, $values);
		return $this->insertRow($insertInfo['sql'], $insertInfo['params'], $serial);
	}

	//
	//	End insert functions
	//

	//
	//	Begin update functions
	//

	/**
	 * Executes the given update SQL statement. This method throws an error if multiple rows are updated.
	 *
	 * @param string $sql SQL UPDATE statement
	 * @param assoc_array $params $param => $value array of parameters to escape and substitute into the query
	 */
	public function updateRow($sql, $params)
	{
		$affected = $this->updateRows($sql, $params);
		if($affected == 0 || $affected > 1)
			trigger_error("attempting to update one row, $affected altered");
	}

	/**
	 * Executes the given update SQL statement
	 *
	 * @param string $sql SQL UPDATE statement
	 * @param assoc_array $params $param => $value array of parameters to escape and substitute into the query
	 */
	public function updateRows($sql, $params)
	{
		$res = $this->query($sql, $params);
		return $res->affectedRows();
	}

	//
	//	End update functions
	//

	//
	//	Begin delete functions
	//

	public function deleteRow($sql, $params)
	{
		//	it just so happens that they actually need to do the exact same thing
		$this->updateRow($sql, $params);
	}

	public function deleteRows($sql, $params)
	{
		//	it just so happens that they actually need to do the exact same thing
		return $this->updateRows($sql, $params);
	}

	//
	//	End delete functions
	//

	//
	//	Begin combo functions
	//

	/**
	 * Automatically generates a select statement using the given information
	 *
	 * @param string $tableName name of the table to select from
	 * @param array $fieldsNames array of fields to retreive
	 * @param assoc_array $conditions $field => $value array of what to check (at this time only the "=" operator is supported)
	 * @param assoc_array $params $var => $value array of optional special parameters.  Currently "lock" and "orderby" (set to an array of field names) are supported.
	 * @return string SQL select statement
	 */
	static function generateSelectInfo($tableName, $fieldsNames, $conditions = NULL, $params = NULL)
	{
		// echo_r($params);
		$selectParams = array();

		//	create the field clause
		//	This first option will allow a "*" or a comma separated list of fields in case an array was not passed in
		if(is_string($fieldsNames))
			$fieldClause = $fieldsNames;
		else
		{
			$fieldList = array();
			foreach ($fieldsNames as $fieldName)
			{
				$fieldList[] = ":fld_$fieldName:identifier";
				$selectParams["fld_$fieldName"] = $fieldName;
			}
			$fieldClause = implode(', ', $fieldList);
		}

		//	create the condition clause
		if($conditions)
		{
			$conditionParts = array();
			foreach($conditions as $fieldName => $value)
			{
				if(is_array($value))
				{
					$valueParts = array();
					foreach($value as $thisKey => $thisValue)
					{
						$partName = "{$fieldName}_{$thisKey}";
						$valueParts[] = ":$partName";
						$selectParams[$partName] = $thisValue;
					}
					
					$valueString = implode(', ', $valueParts);
					$conditionParts[] = ":fld_$fieldName:identifier in ($valueString)";
				}
				else
					$conditionParts[] = ":fld_$fieldName:identifier = :$fieldName";
				$selectParams[$fieldName] = $value;
				$selectParams["fld_$fieldName"] = $fieldName;
			}
			$conditionClause = 'WHERE ' . implode(' AND ', $conditionParts);
		}
		else
			$conditionClause = '';

		//	create the lock clause
		if(isset($params['lock']) && $params['lock'])
			$lockClause = 'for update';
		else
			$lockClause = '';

		//	create the order by clause
		if(isset($params['orderby']) && $params['orderby'])
		{
			$orderByClause = 'order by ';
			if(is_array($params['orderby']))
				$orderByClause .= implode(', ', $params['orderby']);
			else
				$orderByClause .= $params['orderby'];
		}
		else
			$orderByClause = '';

		//	create the limit
		if(isset($params['limit']) && $params['limit'])
		{
			$limitClause = 'limit ' . $params['limit'];
		}
		else
			$limitClause = '';
		
		//	now put it all together
		$selectSql = "SELECT $fieldClause FROM :tableName:identifier $conditionClause $orderByClause $limitClause $lockClause";
		$selectParams['tableName'] = $tableName;

		return array('sql' => $selectSql, 'params' => $selectParams);
	}

	/**
	 * Automatically generate an UPDATE SQL statement for the given table based on the passed in conditions and values.  Currently only supports direct equalities for conditions.
	 *
	 * @param string $tableName Name of the table to update
	 * @param assoc_array $conditions $field => $value array of conditions.  Currently only supports the "=" operator.
	 * @param assoc_array $values $field => $value array of fields to update.  The update statement will be generated to update the fields supplied to the supplied values.
	 * @return string SQL statement to update the table
	 */
	static function generateUpdateInfo($tableName, $conditions, $values)
	{
		$updateParams = array();

		//	create the condition part
		$conditionParts = array();
		foreach($conditions as $fieldName => $value)
		{
			$conditionParts[] = ":fld_$fieldName:identifier = :$fieldName";
			$updateParams[$fieldName] = $value;
			$updateParams["fld_$fieldName"] = $fieldName;
		}
		$conditionClause = implode(' AND ', $conditionParts);

		//	create the set part
		$setParts = array();
		foreach($values as $fieldName => $value)
		{
			$fieldNameParts = explode(':', $fieldName);
			$realFieldName = $fieldNameParts[0];

			$setParts[] = ":fld_$realFieldName:identifier = :$fieldName";
			$updateParams[$realFieldName] = $value;
			$updateParams["fld_$realFieldName"] = $realFieldName;
		}

		$setClause = implode(', ', $setParts);

		//	now put it all together
		$updateSql = "UPDATE :tableName:keyword SET $setClause WHERE $conditionClause";
		$updateParams['tableName'] = $tableName;

		return array('sql' => $updateSql, 'params' => $updateParams);
	}

	/**
	 * Automatically generate a statement to delete records from a table based on certain conditions.  Currently only the "=" operator is supported for conditions.
	 *
	 * @param string $tableName Name of the table to delete from
	 * @param assoc_array $conditions $field => $value array of conditions to match for the delete statement
	 * @return string SQL statement to delete records from the table
	 */
	static function generateDeleteInfo($tableName, $conditions)
	{
		$deleteParams = array();

		//	create the condition part
		$conditionParts = array();
		foreach($conditions as $fieldName => $value)
		{
			$conditionParts[] = ":fld_$fieldName:identifier = :$fieldName";
			$deleteParams[$fieldName] = $value;
			$deleteParams["fld_$fieldName"] = $fieldName;
		}
		$conditionClause = implode(' AND ', $conditionParts);

		$updateSql = "DELETE FROM :tableName:keyword WHERE $conditionClause";
		$deleteParams['tableName'] = $tableName;

		return array('sql' => $updateSql, 'params' => $deleteParams);
	}

	/**
	 * Generate an insert statement from an associative ($key => $value) array
	 *
	 * @param string $tableName Table into which the insert should be performed
	 * @param array $values ($key => $value) array in the format ($fieldName => $fieldValue)
	 * @return string SQL statement to perform the insert
	 */
	static function generateInsertInfo($tableName, $values)
	{
		$insertParams = array();

		if($values)
		{
			//	create the set part
			$fieldParts = array();
			$valuesParts = array();
			foreach($values as $fieldName => $value)
			{
				$fieldNameParts = explode(':', $fieldName);
				$realFieldName = $fieldNameParts[0];
				$fieldParts[] = ':fld_' . $realFieldName . ":identifier" ;
				$valuesParts[] = ':' . $fieldName;
				$insertParams[$realFieldName] = $value;
				$insertParams["fld_" . $realFieldName] = $realFieldName;
			}
			$fieldClause = implode(', ', $fieldParts);
			$valuesClause = implode(', ', $valuesParts);

			//	now put it all together
			$insertSql = "INSERT INTO :tableName:keyword ($fieldClause) VALUES ($valuesClause)";
			$insertParams['tableName'] = $tableName;
		}
		else
		{
			$insertSql = "INSERT INTO :tableName:keyword DEFAULT VALUES";
			$insertParams['tableName'] = $tableName;
		}

		return array('sql' => $insertSql, 'params' => $insertParams);
	}

	public function upsertRow($tableName, $conditions, $values)
	{
		//	generate the update query info
		$updateInfo = self::generateUpdateInfo($tableName, $conditions, $values);

		//	update the row if it's there
		$num = $this->updateRows($updateInfo['sql'], $updateInfo['params']);

		if($num > 1)
			trigger_error("one row expected, $num rows updated");

		if(!$num)
		{
			//	generate the insert query info
			$insertInfo = self::generateInsertInfo($tableName, array_merge($conditions, $values));

			//	if it wasn't there insert it
			$this->insertRow($insertInfo['sql'], $insertInfo['params'], false);

			//
			//	I think the only thing that can go wrong here is we get a race condition where another process inserts the row
			//	after we do the update but before we do the insert.  In that case an error will be thrown here that needs to be handled.
			//	Also after catching the error we should still try to execute the update statement.  Of course if the thread causing the insert
			//	error then rolls back we might need to do an insert.  Hmmm... this could get tricky.
			//
		}
	}

	public function selsertRow($tableName, $fieldNames, $conditions, $defaults = NULL, $lock = 0)
	{
		//	generate the sqlect query info
		$selectInfo = self::generateSelectInfo($tableName, $fieldNames, $conditions, $lock);

		//	select the row if it's there
		$row = $this->fetchRow($selectInfo['sql'], $selectInfo['params']);
		if(!$row)
		{
			//	generate the insert query info
			$allInsertFields = $defaults ? array_merge($conditions, $defaults) : $conditions;
			$insertInfo = self::generateInsertInfo($tableName, array_merge($conditions, $allInsertFields));

			//	if it wasn't there insert it
			$this->insertRow($insertInfo['sql'], $insertInfo['params'], false);
			//	you may have a race condition here
			//	if another thread inserted it first then we need to supress the error in PHP4
			//	we should probably try to use exceptions in PHP5

			$row = $this->fetchRow($selectInfo['sql'], $selectInfo['params']);
			if(!$row)
			{
				//	I'm pretty sure that this should actually never happen
				trigger_error("error upsert: this shouldn't ever happen.  If it does figure out why and fix this function");
			}
		}

		return $row;
	}

	//
	//	End combo functions
	//
}
