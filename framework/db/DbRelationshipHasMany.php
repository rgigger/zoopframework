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
			$remoteTableName = DbObject::_getTableName($this->remoteClassName);
			$sql = "select * from $remoteTableName where {$this->remoteFieldName} = :id:int";
			$rows = $this->dbObject->getDb()->fetchRows($sql, array('id' => $this->dbObject->getField($this->localFieldName)));
			
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