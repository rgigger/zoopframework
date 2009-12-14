<?php
class DbRelationshipHasMany extends DbRelationshipBasic implements Iterator, ArrayAccess, Countable
{
	private $theMany;
	
	public function __construct($name, $params, $dbObject)
	{
		if(isset($params['order_by']))
			trigger_error("depricated paramater: user orderBy");
		if(isset($params['orderby']))
			trigger_error("depricated paramater: user orderBy");
		if(isset($params['map_field']))
			trigger_error("depricated paramater: user mapField");
		
		parent::__construct($name, $params, $dbObject);
		if(!isset($params['orderBy']))
			$this->params['orderBy'] = array();
			
		if(!isset($params['mapField']))
			$this->params['mapField'] = false;
			
		if(!isset($params['conditions']))
			$this->params['conditions'] = array();
			
		if(!isset($params['createOrderedRows']))
			$this->params['createOrderedRows'] = false;
		
		if(!isset($params['createDefaultRows']))
			$this->params['createDefaultRows'] = false;
	}
	
	public function add()
	{
		return new $this->remoteClassName(array($this->remoteFieldName => $this->dbObject->getField($this->localFieldName)));
	}
	
	public function push($object)
	{
		$this->theMany[] = $object;
	}
	
	public function getInfo()
	{
		if(!$this->theMany)
		{
			$params = array();
			$params['orderby'] = $this->params['orderBy'];
			$remoteTableName = DbObject::_getTableName($this->remoteClassName);
			$conditions = $this->params['conditions'];
			$conditions[$this->remoteFieldName] = $this->dbObject->getField($this->localFieldName);
			$selectInfo = DbConnection::generateSelectInfo($remoteTableName, '*', $conditions, $params);
			$rows = $this->dbObject->getDb()->fetchRows($selectInfo['sql'], $selectInfo['params']);
			$this->theMany = array();
			foreach($rows as $thisRow)
			{
				if($this->params['mapField'])
					$this->theMany[$thisRow[$this->params['mapField']]] = new $this->remoteClassName($thisRow);
				else
					$this->theMany[] = new $this->remoteClassName($thisRow);
			}
		}
		
		return $this;
	}
	
	//
	//	begin iterator functions
	//
	
	public function rewind()
	{
		reset($this->theMany);
	}

	public function current()
	{
		$var = current($this->theMany);
		return $var;
	}

	public function key()
	{
		$var = key($this->theMany);
		return $var;
	}

	public function next()
	{
		$var = next($this->theMany);
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
	//	begin array access functions
	//
	
	public function offsetExists($offset)
	{
		return isset($this->theMany[$offset]);
	}
	
	public function offsetGet($offset)
	{
		return isset($this->theMany[$offset]) ? $this->theMany[$offset] : null;
	}
	
	public function offsetSet($offset, $value)
	{
		$this->theMany[$offset] = $value;
	}
	
	public function offsetUnset($offset)
	{
		unset($this->theMany[$offset]);
	}
	
	//
	//	end array access functions
	//
	
	
	//
	//	begin countable functions
	//
	
	public function count()
	{
		return count($this->theMany);
	}
	
	//
	//	end countable functions
	//

}
