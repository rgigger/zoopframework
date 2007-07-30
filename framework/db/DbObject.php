<?php
class DbObject
{
	var $id;
	var $scalars;
	var $hasMany;
	var $autoSave;
	
	function DbObject($init = NULL, $defaults = NULL)
	{
		//	terrible terrible hack.  Alas if only we had late static binding
		if($init == 'static')
			return;
		
		if(is_numeric($init))
			$init = (int)$init;
		
		if($defaults)
		{
			assert(is_array($defaults));
			if(!is_null($init))
				trigger_error("defaults can only be used when creating the row in the database.  use setscalars to set them after it is created");
		}
		
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
			case 'NULL':
				//	we just need to create a new blank object
				$tableName = $this->getTableName();
				if(!$defaults)
					$this->id = SqlInsertRow("insert into $tableName default values", array());
				else
				{
					$info = DbConnection::generateInsertInfo($tableName, $defaults);
					$this->id = SqlInsertRow($info['sql'], $info['params']);
				}
				break;
			default:
				trigger_error('object not initialized');
				break;
		}
		
		$this->hasMany = array();
		$this->autoSave = true;
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
	
	function setScalar($field, $value)
	{
		$data[$field] = $value;
		$this->setScalars($data);
	}
	
	function setScalars($data, $force = false)
	{
		$idFieldName = $this->getIdFieldName();
		
		//	if they passed in an id we don't need to reset it just verify that it is the right one
		if(isset($data[$idFieldName]))
		{
			assert($data[$idFieldName] == $this->id);
			unset($data[$idFieldName]);
		}
		
		if($this->autoSave || $force)
		{
			$tableName = $this->getTableName();
			
			$this->assignScalars($data);
			
			$updateFields = array();
			$updateValues = array();
			foreach($data as $member => $value)
			{
				if($value === null)
					$updateFields[] = "$member = NULL";
				else
				{
					$updateFields[] = "$member = :$member";
					$updateValues[$member] = $value;
				}
			}
			$updateFields = implode(", ", $updateFields);
			
			SqlUpdateRow("update $tableName set $updateFields where $idFieldName = $this->id", $updateValues);
		}
		else
		{
			$this->assignScalars($data);
		}
	}
	
	function assignScalars($data)
	{
		foreach($data as $member => $value)
		{
			$this->scalars[$member] = $value;
		}
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
	
	function save()
	{
		echo_r($this->scalars);
		$this->setScalars($this->scalars, true);
	}
	
	function hasMany($name, $params = NULL)
	{
		if(isset($params['class']))
			$className = $params['class'];
		else
			$className = $name;
		
		if(isset($params['field']))
			$foreignKey = $params['field'];
		else
			$foreignKey = $this->getTableName() . '_id';
		
		$this->hasMany[$name] = array('className' => $className, 'foreignKey' => $foreignKey);
	}
	
	function getMany($name)
	{
		$className = $this->hasMany[$name]['className'];
		$foreignKey = $this->hasMany[$name]['foreignKey'];
		
		//	hack to get around phps lack of decent static function handling
		$dummy = new $className('static');		
		$tableName = $dummy->getTableName();
		
		$sql = "select * from $tableName where $foreignKey = :id";
		
		// echo_r($sql);
		// echo_r($this->id);
		$rows = SqlFetchRows($sql, array('id' => $this->id));
		$objects = array();
		foreach($rows as $thisRow)
		{
			$objects[] = new $className($thisRow);
		}
		
		return $objects;
	}
	
	function __get($varname)
	{
//		if(isset($this->$value))
//			return $this->$value;
		
		if(isset($this->hasMany[$varname]))
			return $this->getMany($varname);
		
		return $this->getScalar($varname);
	}

	function __set($varname, $value)
	{
//		if(isset($this->$varname))
//			return;
		
		$this->autoSave = false;
		$this->setScalar($varname, $value);
	}
}