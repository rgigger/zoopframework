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
	//	private
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
	
	function fetchSimpleMap($sql, $keyFields, $valueField, $params)
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
			
			$cur = $row[ $inValueField ];
		}
		
		return $map;
	}
	
	function fetchMap($sql, $mapFields, $params)
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
	
	function fetchComplexMap($sql, $mapFields, $params)
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