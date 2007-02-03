<?php
class DbConnection
{
	var $params;
	var $types;
	
	function query($sql, $params)
	{
		foreach($params as $key => $value)
		{
			$parts = explode(':', $key);
			$this->params[$parts[0]] = $value;
			if(isset($parts[1]))
				$this->types[$parts[0]] = $parts[1];
		}
		$sql = preg_replace_callback("/:([[:alpha:]]+):([[:alpha:]]+)|:([[:alpha:]]+)/", array($this, 'queryCallback'), $sql);
	}
	
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
	
}