<?php
class DbObject
{
	var $id;
	var $scalars;
	var $hasMany;
	
	function DbObject($init = NULL)
	{
		if(!$init)
			return;
		
		switch(gettype($init))
		{
			case 'integer':
				$this->id = $init;
				$this->scalars = array();
				break;
			case 'array':
				if(isset($init['id']))
				{
					$this->id = $init['id'];
					$this->scalars = $init;
				}
				else
				{
					//	do a selsert on the lookup fields
					//	retrieve all of them
					trigger_error('not yet implemented');
				}
				break;
			default:
				trigger_error('object not initialized');
				break;
		}
		
		$this->hasMany = array();
	}
	
	function getTableName()
	{
		return strtolower(get_class($this));
	}
	
	function getIdFieldName()
	{
		return 'id';
	}
	
	function getId()
	{
		return $this->id;
	}
	
	//
	//	the scalar handlers
	//
	
	function getScalar($field)
	{
		if(!isset($this->scalars[$field]))
			$this->loadScalars();
		
		return $this->scalars[$field];
	}
	
	function loadScalars()
	{
		$tableName = $this->getTableName();
		$idFieldName = $this->getIdFieldName();
		$row = SqlFetchRow("select * from $tableName where $idFieldName = :id", array('id' => $this->id));
		
		//	if they manually set a field don't write over it just because they loaded one scalar
		foreach($row as $field => $value)
		{
			if(!isset($this->scalars[$field]))
				$this->scalars[$field] = $value;
		}
		
	}
	
	/*
	function assignScalars($data)
	{
		foreach($data as $member => $value)
		{
			$this->scalars[$member] = $value;
		}
	}
	*/
	
	/*
	function getScalar($fieldName)
	{
		$tableName = $this->getTableName();
		return SqlFetchCell("select $fieldName from person where id = :id", array('id' => $this->id));
	}
	*/
	
	//
	//	orm
	//
	
	function hasMany($className, $foreignKey = NULL)
	{
		if(!$foreignKey)
			$foreignKey = $this->getTableName() . '_id';
		$this->hasMany[$className] = $foreignKey;
	}
	
	function getMany($className)
	{
		//	hack to get around phps lack of decent static function handling
		$dummy = new $className();
		$tableName = $dummy->getTableName();
		$fieldName = $this->hasMany[$className];
		
		$sql = "select * from $tableName where $fieldName = :id";
		$rows = SqlFetchRows($sql, array('id' => $this->id));
		$objects = array();
		foreach($rows as $thisRow)
		{
			$objects[] = new $className($thisRow);
		}
		
		return $objects;
	}
}