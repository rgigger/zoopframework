<?php
class DbObject implements Iterator
{
	protected $tableName;
	protected $primaryKey;
	protected $keyAssignedBy;
	private $missingKeyFields;
	private $bound;
	private $scalars;
	// private $krumoHack = array();
	
	const keyAssignedBy_db = 1;
	const keyAssignedBy_dev = 2;
	const keyAssignedBy_auto = 3;
	
	function __construct($init = NULL)
	{
		//	set up some sensible defaults
		$this->primaryKey = array('id');
		$this->tableName = $this->getDefaultTableName();
		$this->bound = false;
		$this->keyAssignedBy = self::keyAssignedBy_db;
		$this->scalars = array();
		
		$this->init($init);
		
		$this->missingKeyFields = count($this->primaryKey);
		if($this->keyAssignedBy == self::keyAssignedBy_db && count($this->primaryKey) != 1)
			trigger_error("in order for 'keyAssignedBy_db' to work you must have a single primary key field");
		
		if(is_array($init))
		{
			$this->assignScalars($init);
		}
		else if($init === NULL)
		{
			return;
		}
		else
		{
			assert(count($this->primaryKey) == 1);
			$this->assignScalars(array($this->primaryKey), $init);
		}
	}
	
	//	second stage constructor
	protected function init()
	{
		//	override this function to setup relationships without having to handle the constructor chaining
	}
	
	private function assignScalars($data)
	{
		foreach($data as $member => $value)
		{
			if(!isset($this->scalars[$member]) && in_array($member, $this->primaryKey))
			{
				$this->missingKeyFields--;
				if($this->missingKeyFields == 0)
					$this->bound = 1;
			}
			
			$this->scalars[$member] = $value;
		}
	}
	
	private function getDefaultTableName()
	{
		$name = get_class($this);

		//      if there are any capitals after the firstone insert and underscore
		$name = $name[0] . preg_replace('/[A-Z]/', '_$0', substr($name, 1));

		//      lowercase everything and return it
		return strtolower($name);		
	}
	
	public function getTableName()
	{
		return $this->tableName;
	}
	
	public function getId()
	{
		assert(count($this->primaryKey) == 1);
		return $this->scalars[$this->primaryKey[0]];
	}
	
	public function getString()
	{
		$s = '';
		$this->loadScalars();
		foreach($this->scalars as $field => $value)
			$s .= " $field => $value";
		return get_class($this) . ':' . $s;
	}
		
	//
	//	the scalar handlers
	//
	//	rewrite them and make them handle primary keys with different names or more than one field
	//
		
	public function getFields()
	{
		return $this->scalars;
	}
	
	public function setFields($data)
	{
		$this->assignScalars($data);
	}
	
	private function getScalar($field)
	{
		if(!isset($this->scalars[$field]))
		{
			if(!$this->bound)
				trigger_error("the field: $field is not present in memory and this object is not yet bound to a database row");
			$this->loadScalars();
		}
		
		if(!isset($this->scalars[$field]))
			trigger_error("the field $field present neither in memory nor in the cooresponding database table");
		
		return $this->scalars[$field];
	}
	
	private function setScalar($field, $value)
	{
		$data[$field] = $value;
		$this->assignScalars($data);
	}
	
	/*
	private function setScalars($data)
	{
		foreach($data as $field => $value)
		{
			$this->scalars[$field] = $value;
		}
	}
	*/
	
	private function loadScalars()
	{
		assert($this->bound);
		$wheres = array();
		$whereValues = array();
		foreach($this->primaryKey as $keyField)
		{
			$wheres[] = "$keyField = :$keyField";
			$whereValues[$keyField] = $this->scalars[$keyField];
		}
		$whereClause = implode(' and ', $wheres);
		$row = self::_getConnection(get_class($this))->fetchRow("select * from $this->tableName where $whereClause", $whereValues);
		
		//	if they manually set a field don't write over it just because they loaded one scalar
		foreach($row as $field => $value)
		{
			if(!isset($this->scalars[$field]))
				$this->scalars[$field] = $value;
		}
	}
	
	public function save()
	{
		if(!$this->bound)
		{
			if($this->keyAssignedBy == self::keyAssignedBy_db)
				$this->setScalar($this->primaryKey[0], self::_getConnection(get_class($this))->insertArray($this->tableName, $this->scalars));
			else
			{
				if(!$this->bound)
					trigger_error("you must supply _create with values for all of the primary key fields");

				self::_getConnection(get_class($this))->insertArray($this->tableName, $values, false);
			}
		}
		else
		{
			$updateInfo = DbConnection::generateUpdateInfo($this->tableName, $this->getKeyConditions(), $this->scalars);
			self::_getConnection(get_class($this))->updateRow($updateInfo['sql'], $updateInfo['params']);
		}
	}
	
	private function getKeyConditions()
	{
		assert($this->bound);
		return array_intersect_key($this->scalars, array_flip($this->primaryKey));
	}
	
	public function destroy()
	{
		$deleteInfo = DbConnection::generateDeleteInfo($this->tableName, $this->getKeyConditions());
		self::_getConnection(get_class($this))->deleteRow($deleteInfo['sql'], $deleteInfo['params']);
	}
	
	//
	//	end of scalar handlers
	//
	
	
	//
	//	vector handlers
	//
	/*
	protected function hasMany($name, $params = NULL)
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
	
	protected function getMany($name)
	{
		$className = $this->hasMany[$name]['className'];
		$foreignKey = $this->hasMany[$name]['foreignKey'];
		
		//	work around lack of "late static binding"
		$dummy = new $className(0);
		$tableName = $dummy->getTableName();
		
		$sql = "select * from $tableName where $foreignKey = :id";
		
		$rows = SqlFetchRows($sql, array('id' => $this->id));
		$objects = array();
		foreach($rows as $thisRow)
		{
			$objects[] = new $className($thisRow);
		}
		
		return $objects;
	}
	
	protected function belongsTo($name, $params = NULL)
	{
		//	determine the name of the class we belong to
		$className = isset($params['class']) ? $params['class'] : $name;
		
		$tableName = DbObject::_getTableName($className);
		
		//	get the name of the foreign key in this table
		$localKey = isset($params['key']) ? $params['key'] : $tableName . '_id';
		
		$this->belongsTo[$name] = array('className' => $className, 'localKey' => $localKey);
	}
	
	protected function getOwner($name)
	{
		$className = $this->belongsTo[$name]['className'];
		$localKey = $this->belongsTo[$name]['localKey'];
		$tableName = DbObject::_getTableName($className);
		return new $className($this->getScalar($localKey));
	}
	*/
	//
	//	end vector handlers
	//
	
	//
	//	begin magic functions
	//
	
	function __get($varname)
	{
		//	krumo hack
		// if(substr($varname, 0, 5) == 'krumo')
		// 	return isset($this->krumoHack[$varname]) ? $this->krumoHack[$varname] : NULL;
		
		/*
		if(isset($this->hasMany[$varname]))
			return $this->getMany($varname);
		
		if(isset($this->belongsTo[$varname]))
			return $this->getOwner($varname);
		*/
		return $this->getScalar($varname);
	}

	function __set($varname, $value)
	{
		//	krumo hack
		// if(substr($varname, 0, 5) == 'krumo')
		// 	$this->krumoHack[$varname] = $value;
		$this->setScalar($varname, $value);
	}
	
	//
	//	end magic functions
	//
	
	//
	//	begin iterator functions
	//
	
	public function rewind()
	{
		reset($this->scalars);
	}

	public function current()
	{
		$var = current($this->scalars);
		return $var;
	}

	public function key()
	{
		$var = key($this->scalars);
		return $var;
	}

	public function next()
	{
		$var = next($this->scalars);
		return $var;
	}

	public function valid()
	{
		$var = $this->current() !== false;
		return $var;
	}
	
	//
	//	end iterator functions
	//
	
	
	//
	//	static methods
	//
	
	static private function _getConnectionName($className)
	{
		return 'default';
	}
	
	static private function _getConnection($className)
	{
		return DbModule::getConnection(call_user_func(array($className, '_getConnectionName'), $className));
	}
	
	static private function _getTableName($className)
	{
		//	work around lack of "late static binding"
		$dummy = new $className();
		return $dummy->getTableName();
	}
	
	static public function _create($className, $values)
	{
		$object = new $className($values);
		$object->save();
		return $object;
	}
	
	static public function _insert($className, $values)
	{
		self::_getConnection($className)->insertArray(self::_getTableName($className), $values, false);			
	}
		
	static public function _findBySql($className, $sql, $params)
	{
		$res = self::_getConnection($className)->query($sql, $params);
		
		if(!$res->valid())
			return array();
		
		$objects = array();
		for($row = $res->current(); $res->valid(); $row = $res->next())
		{
			$objects[] = new $className($row);
		}
		
		return $objects;
	}
	
	static public function _findByWhere($className, $where, $params)
	{
		$tableName = DbObject::_getTableName($className);
		return self::_findBySql($className, "select * from $tableName where $where", $params);
	}
	
	static public function _find($className, $conditions = NULL)
	{
		$tableName = DbObject::_getTableName($className);
		if($conditions)
		{
			$selectInfo = self::_getConnection($className)->generateSelectInfo($tableName, '*', $conditions);
			$sql = $selectInfo['sql'];
			$params = $selectInfo['params'];
		}
		else
		{
			$sql = "select * from $tableName";
			$params = array();
		}
		
		return self::_findBySql($className, $sql, $params);
	}
	
	/**
	 * Retrieve one object from the database and map it to an object
	 * @param string $className The name of the class corresponding to the table in the database 
	 * @param array $conditions Key value pair for the fields you want to look up
	 * @return DbObject
	 */	
	
	static public function _findOne($className, $conditions = NULL)
	{
		$a = DbObject::_find($className, $conditions);
		if(!$a)
			return false;
		
		assert(is_array($a));
		assert(count($a) == 1);
		
		return current($a);
	}
	
	static public function _getOne($className, $conditions = NULL)
	{
		$tableName = DbObject::_getTableName($className);
		$row = self::_getConnection($className)->selsertRow($tableName, "*", $conditions);
		return new $className($row);
	}
	
	//
	//	end static methods
	//
}
