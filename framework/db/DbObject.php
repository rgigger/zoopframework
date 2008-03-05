<?php
class DbObject implements Iterator
{
	//	this should really be at least protected???
	public $id;	
	private $scalars;
	private $hasMany;
	private $belongsTo;
	private $autoSave;
	private $bound;
	
	//	constructor
	//
	//	How should the constructor really work?  Do we ever even want them calling it directly?
	//	from now we will have these cases for the constructor
	//	1. You pass nothing and get a totally in memory object.  You must call save in order to commit it to the db
	//	2. You pass in all fields including all primary key fields in which case it will assume it is bound to a row in the db
	//	3. You pass in some fields but not any primary key fields in which case it...
	//		3.1 commits itself to the db and is then bound?
	//		3.2 is in memory until you call save, then it binds itself to the db
	//		3.3 just don't do it, it's too confusing
	//
	//	inserts now only happen in save and (static)get
	//	constructor assumes either it already exists in the db or it is an in memory object
	//
	//	the other ways to create objects are:
	//	1. find(): give it conditions and it will return the objects you need - no longer handled by constructor
	//	2. create(): it will use the fields you give it to create a new row and return an object bound to it - no longer handled by 
	//	3. get(): will create the rows if not already in the db and return objects bound to the rows
	//
	function __construct($init = NULL, $defaults = NULL)
	{	
		$this->bound = false;

		if(is_numeric($init))
			$init = (int)$init;
		
		if($init === 0)
			return;
		
		if($defaults)
		{
			assert(is_array($defaults));
			if(!is_null($init))
				trigger_error("defaults can only be used when creating the row in the database.  use setscalars to set them after it is created");
		}
		
		switch(gettype($init))
		{
			case 'integer':
				$this->setId($init);
				$this->scalars = array();
				break;
			case 'array':
				if(isset($init['id']))
				{
					$this->setId($init['id']);
					$this->scalars = $init;
				}
				else
				{
					//	do a selsert on the lookup fields
					//	retrieve all of them
					//	if there is more than one throw an error
					trigger_error('not yet implemented');
				}
				break;
			case 'NULL':
				//	we just need to create a new blank object, bound to a new row in the database
				$tableName = $this->getTableName();
				if(!$defaults)
					$this->setId(SqlInsertRow("insert into $tableName default values", array()));
				else
					$this->createRow($defaults);
				
				break;
			default:
				trigger_error('object not initialized');
				break;
		}
		
		$this->hasMany = array();
		$this->autoSave = true;
		$this->init();
	}
	
	//	second stage constructor
	protected function init()
	{
		//	override this function to setup relationships without having to handle the constructor chaining
	}
	
	//	these two need to be updated to handle other single field and multi-field primary keys
	function setId($id)
	{
		$this->id = $id;
		$this->bound = true;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	//	I don't know that this function even needs to exist.  You should be using _create instead and we will remake this into a static method later
	function createRow($values)
	{
		$info = DbConnection::generateInsertInfo($this->getTableName(), $values);
		$this->setId(SqlInsertRow($info['sql'], $info['params']));
		return $this->id;
	}
	
	//	init should have a chance to set both the table name and the field name
	//	if it doesn't set them then we should calculate them once and store them in member vars
	//	these functions should just be acessors for those vars
	function getTableName()
	{
		$name = get_class($this);

		//      if there are any capitals after the firstone insert and underscore
		$name = $name[0] . preg_replace('/[A-Z]/', '_$0', substr($name, 1));

		//      lowercase everything and return it
		return strtolower($name);		
	}
	
	function getIdFieldName()
	{
		return 'id';
	}
	
	
	//
	//	the scalar handlers
	//
	//	rewrite them and make them handle primary keys with different names or more than one field
	//
	function getScalar($field)
	{
		if(!$this->bound)
			return NULL;
		
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
		
		//	
		//	most of this should be replaced by a call to DbConnection::generateUpdateInfo() or something
		//
		
		//	if they passed in an id we don't need to reset it just verify that it is the right one
		if(isset($data[$idFieldName]))
		{
			assert($data[$idFieldName] == $this->id);
			unset($data[$idFieldName]);
		}
		
		if($this->autoSave || $force)
		{
			//	this should probably all be abstracted into some 
			if($this->bound)
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
				
				$updateValues['id'] = $this->id;
				SqlUpdateRow("update $tableName set $updateFields where $idFieldName = :id", $updateValues);
			}
			else
			{
				$this->createRow($data);
			}
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
		$this->setScalars($this->scalars, true);
	}
	
	//
	//	end of scalar handlers
	//
	
	//
	//	vector handlers
	//
	
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
	
	function belongsTo($name, $params = NULL)
	{
		//	determine the name of the class we belong to
		$className = isset($params['class']) ? $params['class'] : $name;
		
		$tableName = DbObject::_getTableName($className);
		
		//	get the name of the foreign key in this table
		$localKey = isset($params['key']) ? $params['key'] : $tableName . '_id';
		
		$this->belongsTo[$name] = array('className' => $className, 'localKey' => $localKey);
	}
	
	function getOwner($name)
	{
		$className = $this->belongsTo[$name]['className'];
		$localKey = $this->belongsTo[$name]['localKey'];
		$tableName = DbObject::_getTableName($className);
		return new $className($this->getScalar($localKey));
	}
	
	//
	//	end vector handlers
	//
	
	function __get($varname)
	{
		if(isset($this->hasMany[$varname]))
			return $this->getMany($varname);
		
		if(isset($this->belongsTo[$varname]))
			return $this->getOwner($varname);
		
		return $this->getScalar($varname);
	}

	function __set($varname, $value)
	{
		$this->autoSave = false;
		$this->setScalar($varname, $value);
	}
	
	//
	//	iterator functions
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
	
	static public function _create($className, $values)
	{
		$tableName = DbObject::_getTableName($className);
		SqlModifyRowValues($tableName, $values);
		$object = new $className($values);
		return $object;
	}
	
	static function _getTableName($className)
	{
		//	work around lack of "late static binding"
		// EchoBacktrace();
		$dummy = new $className(0);
		return $dummy->getTableName();
	}
	
	static function _find($className, $conditions = NULL)
	{
		$tableName = self::_getTableName($className);
		
		if(is_numeric($conditions))
			$conditions = (int)$conditions;
		
		switch(gettype($conditions))
		{
			case 'integer':
				trigger_error('not yet implemented');
				break;
			case 'array':
				$sql = "select * from $tableName";
				
				if(count($conditions) > 0)
				{
					$sql .= ' where ';
					$parts = array();
					foreach($conditions as $fieldname => $value)
					{
						$parts[] = "$fieldname = '$value'";
					}
					$sql .= implode(' and ', $parts);
				}
				
				$rows = SqlFetchMap($sql, 'id', array());
				$objects = array();
				foreach($rows as $id => $row)
				{
					$objects[$id] = new $className($row);
				}
				return $objects;
				break;
			case 'NULL':
				$sql = "select * from $tableName";
				$rows = SqlFetchMap($sql, 'id', array());
				$objects = array();
				foreach($rows as $id => $row)
				{
					$objects[$id] = new $className($row);
				}
				return $objects;
				break;
			default:
				trigger_error('unhandled conditions type');
				break;
		}
	}
	
	
	/**
	 * Retrieve one object from the database and map it to an object
	 * @param string $className The name of the class corresponding to the table in the database 
	 * @param array $conditions Key value pair for the fields you want to look up
	 * @return DbObject
	 */	
	static function _findOne($className, $conditions = NULL)
	{
		$a = DbObject::_find($className, $conditions);
		if(!$a)
			return false;
		
		assert(is_array($a));
		assert(count($a) == 1);
		
		return current($a);
	}
	
	//
	//	end static methods
	//
}
