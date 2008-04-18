<?php
class DbObject implements Iterator
{
	protected $tableName;
	protected $primaryKey;
	protected $keyAssignedBy;
	private $missingKeyFields;
	private $bound;
	private $persisted;
	private $scalars;
	private $relationships;
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
		$this->relationships = array();
		$this->persisted = NULL;
		
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
			$this->assignScalars(array($this->primaryKey[0] => $init));
		}
	}
	
	//	second stage constructor
	protected function init()
	{
		//	override this function to setup relationships without having to handle the constructor chaining
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
	
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}
	
	public function primaryKeyAssignedByDb()
	{
		return $this->keyAssignedBy == self::keyAssignedBy_db ? true : false;
	}
	
	public function getSchema()
	{
		return new DbTable(self::_getConnection(get_class($this)), $this->tableName);
	}
	
	public function forceFields()
	{
		if($this->bound)
			$this->loadScalars();
		else
		{
			foreach($this->getSchema()->fields as $thisField)
				$this->scalars[$thisField->name] = NULL;
		}
	}
	
	public function getString()
	{
		$s = '';
		$this->loadScalars();
		foreach($this->scalars as $field => $value)
			$s .= " $field => $value";
		return get_class($this) . ':' . $s;
	}
	
	public function getDb()
	{
		return self::_getConnection(get_class($this));
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
	
	public function getField($field)
	{
		return $this->getScalar($field);
	}
	
	private function getScalar($field)
	{
		if(!isset($this->scalars[$field]))
		{
			if(!$this->bound)
			{
				/*
				Different possibilities on how to handle this situation.  Maybe we could use some flags.
				1. check the metadata.  (alwaysCheckMeta)
					1. if its there then (useDummyDefaults requires alwaysCheckMeta)
						1. return the default value
						2. return NULL
					2. if its not there
						1. throw and error
				2.	dont check the metadata (useDummyNulls requires !alwaysCheckMeta)
					1.	return null
					2.	throw an error
				
				trigger_error("the field: $field is not present in memory and this object is not yet bound to a database row");
				*/
				
				return NULL;
			}
				
			$this->loadScalars();
		}
		
		if(!isset($this->scalars[$field]))
			trigger_error("the field $field is present neither in memory nor in the cooresponding database table");
		
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
	
	private function loadScalars()
	{
		assert($this->bound);
		$row = $this->fetchPersisted();
		$this->assignPersisted($row);
	}
	
	private function assignPersisted($row)
	{
		//	if they manually set a field don't write over it just because they loaded one scalar
		foreach($row as $field => $value)
		{
			if(!isset($this->scalars[$field]))
				$this->scalars[$field] = $value;
		}		
	}
	
	private function fetchPersisted()
	{
		$wheres = array();
		$whereValues = array();
		foreach($this->primaryKey as $keyField)
		{
			$wheres[] = "$keyField = :$keyField";
			$whereValues[$keyField] = $this->scalars[$keyField];
		}
		$whereClause = implode(' and ', $wheres);
		$row = self::_getConnection(get_class($this))->fetchRow("select * from $this->tableName where $whereClause", $whereValues);
		if($row)
			$this->persisted = true;
		else
			$this->persisted = false;
		return $row;
	}
	
	private function _persisted()
	{
		if(!$this->bound)
			return false;
		
		if($this->keyAssignedBy == self::keyAssignedBy_db)
			return true;
		else
		{
			$row = $this->fetchPersisted();
			if($row)
			{
				//	we might as well save the results
				$this->assignPersisted();
				return true;
			}
			
			return false;
		}
	}
	
	public function persisted()
	{
		if($this->persisted !== NULL)
			return $this->persisted;
		else
			return $this->persisted = $this->_persisted();
	}
	
	public function save()
	{
		if(!$this->bound)
		{
			if($this->keyAssignedBy == self::keyAssignedBy_db)
				$this->setScalar($this->primaryKey[0], self::_getConnection(get_class($this))->insertArray($this->tableName, $this->scalars));
			else
				trigger_error("you must define all foreign key fields in order by save this object");
		}
		else
		{
			if($this->keyAssignedBy == self::keyAssignedBy_db)
			{
				$updateInfo = DbConnection::generateUpdateInfo($this->tableName, $this->getKeyConditions(), $this->scalars);
				self::_getConnection(get_class($this))->updateRow($updateInfo['sql'], $updateInfo['params']);
			}
			else
			{
				if(!$this->persisted())
					self::_getConnection(get_class($this))->insertArray($this->tableName, $this->scalars, false);
				else
				{
					$updateInfo = DbConnection::generateUpdateInfo($this->tableName, $this->getKeyConditions(), $this->scalars);
					self::_getConnection(get_class($this))->updateRow($updateInfo['sql'], $updateInfo['params']);
				}
			}
		}
	}
	
	private function getKeyConditions()
	{
		assert($this->bound);
		return array_intersect_key($this->scalars, array_flip($this->primaryKey));
	}
	
	public function destroy()
	{
		//	have a way to destroy any existing vector fields or refuse to continue (destroy_r)
		$deleteInfo = DbConnection::generateDeleteInfo($this->tableName, $this->getKeyConditions());
		self::_getConnection(get_class($this))->deleteRow($deleteInfo['sql'], $deleteInfo['params']);
		$this->bound = false;
		$this->scalars = array();
		$this->persisted = false;
	}
	
	//
	//	end of scalar handlers
	//
	
	
	//
	//	vector handlers
	//
	
	private function addRelationship($name, $relationship)
	{
		$this->relationships[$name] = $relationship;
	}
	
	private function hasRelationship($name)
	{
		return isset($this->relationships[$name]) ? true : false;
	}
	
	private function getRelationshipInfo($name)
	{
		return $this->relationships[$name]->getInfo();
	}
	
	protected function hasMany($name, $params = array())
	{
		if(isset($params['through']) && $params['through'])
			$this->addRelationship($name, new DbRelationshipHasManyThrough($name, $params, $this));
		else
			$this->addRelationship($name, new DbRelationshipHasMany($name, $params, $this));
	}
	
	protected function hasOne($name, $params = array())
	{
		$this->addRelationship($name, new DbRelationshipHasOne($name, $params, $this));
	}
	
	protected function belongsTo($name, $params = array())
	{
		$this->addRelationship($name, new DbRelationshipBelongsTo($name, $params, $this));
	}
	
	protected function fieldOptions($name, $params = array())
	{
		$this->addRelationship($name, new DbRelationshipOptions($name, $params, $this));
	}
	
	//
	//	end vector handlers
	//
	
	//
	//	begin magic functions
	//
	
	function __get($varname)
	{
		if($this->hasRelationship($varname))
			return $this->getRelationshipInfo($varname);
		
		return $this->getScalar($varname);
	}

	function __set($varname, $value)
	{
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
	
	static public function _getTableName($className)
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
	
	static public function _find($className, $conditions = NULL, $params = NULL)
	{
		// echo_r($params);
		$tableName = DbObject::_getTableName($className);
		$selectInfo = self::_getConnection($className)->generateSelectInfo($tableName, '*', $conditions, $params);
		return self::_findBySql($className, $selectInfo['sql'], $selectInfo['params']);
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
