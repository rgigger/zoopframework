<?php
class DbConnection
{
	var $params;
	var $types;
	var $conn;
	
	function query($sql, $params)
	{
		//	do all of the variable replacements
		foreach($params as $key => $value)
		{
			$parts = explode(':', $key);
			$this->params[$parts[0]] = $value;
			if(isset($parts[1]))
				$this->types[$parts[0]] = $parts[1];
		}
		$sql = preg_replace_callback("/:([[:alpha:]]+):([[:alpha:]]+)|:([[:alpha:]]+)/", array($this, 'queryCallback'), $sql);
		
		//	actually do the query
		return $this->_query($sql);
	}
	
	//	callback passed to preg_replace_callback for doing the variable substitutions
	function queryCallback($matches)
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
				$replaceString = $this->escapeString($this->params[$name]);
				break;
			case 'int':
				$replaceString = (int)$this->params[$name];
				break;
			default:
				trigger_error("unknown param type: " . $type);
				break;
		}
		
		return $replaceString;
	}
	
	function fetchRow($sql, $params)
	{
		$res = $this->query($sql, $params);
		if($num = $res->numRows() != 1)
			trigger_error("1 row expected: $num returned");
		
		return $res->current();
	}
	
	function fetchCell($sql, $params)
	{
		$row = $this->fetchRow($sql, $params);
		return current($row);
	}
	
	function fetchRows($sql, $params)
	{
		$rows = array();
		$res = $this->query($sql, $params);
		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			$rows[] = $row;
		}
		
		return $rows;
	}
	
	function fetchSimpleMap($sql, $params)
	{
		
	}
	
	function fetchMap($sql, $mapFields, $params)
	{
		
	}
	
	function fetchComplexMap($sql, $mapFields, $params)
	{
		
	}
	
	function insertRow()
	{
		
	}
	
	function insertRows()
	{
		
	}
	
	function updateRow()
	{
		
	}
	
	function updateRows()
	{
		
	}
}