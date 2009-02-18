<?php
class DbRelationshipHasMany extends DbRelationshipBasic implements Iterator
{
	private $theMany;
	
	public function add()
	{
		return new $this->remoteClassName(array($this->remoteFieldName => $this->dbObject->getField($this->localFieldName)));
	}
	
	public function getInfo()
	{
		if(!$this->theMany)
		{
			$params = array();
			if(isset($this->params['orderby']))
				$params['orderby'] = $this->params['orderby'];
			$remoteTableName = DbObject::_getTableName($this->remoteClassName);
			$conditions = isset($this->params['conditions']) && $this->params['conditions'] ? $this->params['conditions'] : array();
			$conditions[$this->remoteFieldName] = $this->dbObject->getField($this->localFieldName);
			$selectInfo = DbConnection::generateSelectInfo($remoteTableName, '*', $conditions, $params);			
			$rows = $this->dbObject->getDb()->fetchRows($selectInfo['sql'], $selectInfo['params']);
			$this->theMany = array();
			foreach($rows as $thisRow)
			{
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
}