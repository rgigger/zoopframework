<?php
class DbRelationshipHasOne extends DbRelationshipBasic
{
	private $theOne;
	
	public function getInfo()
	{
		if(!$this->theOne)
		{
			$remoteTableName = DbObject::_getTableName($this->remoteClassName);
			$sql = "select * from $remoteTableName where {$this->remoteFieldName} = :id:int";
			$row = SqlFetchRow($sql, array('id' => $this->dbObject->getScalar($this->localFieldName)));
			$this->theOne = new $this->remoteClassName($row);
		}
				
		return $this->theOne;
	}
}