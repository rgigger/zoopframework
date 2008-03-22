<?php
abstract class DbConnection
{
	protected $params;
	private $queryParams;
	private $types;
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
	
	public function getSchema($name = 'public')
	{
		return new DbSchema($this);
	}
	
	public function alterSchema($sql)
	{
		return $this->_query($sql);
	}
	
	//
	//	End misc funtions
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
		$sql = preg_replace_callback("/:([[:alpha:]_\d]+):([[:alpha:]_]+)|:([[:alpha:]_\d]+)/", array($this, 'queryCallback'), $sql);
		
		if($this->echo)
			echo $sql . '<br>';
		
		//	actually do the query
		return $this->_query($sql);
	}
	
	//	callback passed to preg_replace_callback for doing the variable substitutions
	private function queryCallback($matches)
	{
		if(isset($matches[3]))
			$matches[1] = $matches[3];
		
		$name = $matches[1];
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
				trigger_error("db::fetchSimpleMap : duplicate key in query: \n $inQuery \n");
			
			$cur = $row[ $valueField ];
		}
		
		return $map;
	}
	
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
	
	public function updateRow($sql, $params)
	{
		$affected = $this->updateRows($sql, $params);
		if($affected == 0 || $affected > 1)
			trigger_error("attempting to update one row, $affected altered");
	}
	
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
	
	static function generateSelectInfo($tableName, $fieldsNames, $conditions, $lock = 0)
	{
		$selectParams = array();
		
		//	create the field clause
		if($fieldsNames == '*')
			$fieldClause = '*';
		else
			$fieldClause = implode(', ', $fieldsNames);
		
		//	create the condition clause
		$conditionParts = array();
		foreach($conditions as $fieldName => $value)
		{
			$conditionParts[] = "$fieldName = :$fieldName";
			$selectParams[$fieldName] = $value;
		}
		$conditionClause = implode(' AND ', $conditionParts);
		
		//	create the lock clause
		if($lock)
			$lockClause = 'for update';
		else
			$lockClause = '';
		
		//	now put it all together
		$selectSql = "SELECT $fieldClause FROM :tableName:keyword WHERE $conditionClause $lockClause";
		$selectParams['tableName'] = $tableName;
		
		return array('sql' => $selectSql, 'params' => $selectParams);
	}
	
	static function generateUpdateInfo($tableName, $conditions, $values)
	{
		$updateParams = array();
		
		//	create the condition part
		$conditionParts = array();
		foreach($conditions as $fieldName => $value)
		{
			$conditionParts[] = "$fieldName = :$fieldName";
			$updateParams[$fieldName] = $value;
		}
		$conditionClause = implode(' AND ', $conditionParts);
		
		//	create the set part
		$setParts = array();
		foreach($values as $fieldName => $value)
		{
			$fieldNameParts = explode(':', $fieldName);
			$realFieldName = $fieldNameParts[0];
			if(isset($fieldType))
				$fieldType = $fieldNameParts[1];
			
			$setParts[] = "$realFieldName = :$fieldName";
			$updateParams[$realFieldName] = $value;
		}
		
		$setClause = implode(', ', $setParts);
		
		//	now put it all together
		$updateSql = "UPDATE :tableName:keyword SET $setClause WHERE $conditionClause";
		$updateParams['tableName'] = $tableName;
		
		return array('sql' => $updateSql, 'params' => $updateParams);
	}
	
	static function generateDeleteInfo($tableName, $conditions)
	{
		$deleteParams = array();
		
		//	create the condition part
		$conditionParts = array();
		foreach($conditions as $fieldName => $value)
		{
			$conditionParts[] = "$fieldName = :$fieldName";
			$deleteParams[$fieldName] = $value;
		}
		$conditionClause = implode(' AND ', $conditionParts);
		
		$updateSql = "DELETE FROM :tableName:keyword WHERE $conditionClause";
		$deleteParams['tableName'] = $tableName;
		
		return array('sql' => $updateSql, 'params' => $deleteParams);
	}
	
	static function generateInsertInfo($tableName, $values)
	{
		$insertParams = array();
		
		//	create the set part
		$fieldParts = array();
		$valuesParts = array();
		foreach($values as $fieldName => $value)
		{
			$fieldNameParts = explode(':', $fieldName);
			$realFieldName = $fieldNameParts[0];
			if(isset($fieldType))
				$fieldType = $fieldNameParts[1];
			
			$fieldParts[] = $realFieldName;
			$valuesParts[] = ':' . $fieldName;
			$insertParams[$realFieldName] = $value;
		}
		$fieldClause = implode(', ', $fieldParts);
		$valuesClause = implode(', ', $valuesParts);
		
		//	now put it all together
		$insertSql = "INSERT INTO :tableName:keyword ($fieldClause) VALUES ($valuesClause)";
		$insertParams['tableName'] = $tableName;
		
		return array('sql' => $insertSql, 'params' => $insertParams);
	}
	
	public function upsertRow($tableName, $conditions, $values)
	{
		//	generate the update query info
		$updateInfo = self::generateUpdateInfo($tableName, $conditions, $values);
		
		//	update the row if it's there
		$num = $this->updateRow($updateInfo['sql'], $updateInfo['params']);
		// var_dump($num);
		if(!$num)
		{
			//	generate the insert query info
			$insertInfo = self::generateInsertInfo($tableName, array_merge($conditions, $values));
			// echo_r($insertInfo);
			
			//	if it wasn't there insert it
			$id = $this->insertRow($insertInfo['sql'], $insertInfo['params']);
			
			if(!$id)
			{
				//	race condition
				//	if another thread inserted it first then we we should check again
				//	also we need to supress the error in PHP4.  we should probably try to use exceptions in PHP5
				$num = $this->updateRow($updateInfo['sql'], $updateInfo['params']);
				if(!$num)
				{
					//	I'm pretty sure that this should actually never happen
					trigger_error("error upsert: this shouldn't ever happen.  If it does figure out why and fix this function");
				}
			}
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
			$id = $this->insertRow($insertInfo['sql'], $insertInfo['params']);
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
