<?php
class DbRelationshipHasOne extends DbRelationshipBasic
{
	private $theOne;
	private $theOneIsSet;
	
	public function getInfo()
	{
		if(!$this->theOneIsSet)
		{
			$remoteTableName = DbObject::_getTableName($this->remoteClassName);
			$sql = "select * from $remoteTableName where {$this->remoteFieldName} = :id:int";
			$row = SqlFetchRow($sql, array('id' => $this->dbObject->getField($this->localFieldName)));
			if($row)
				$this->theOne = new $this->remoteClassName($row);
			
			$this->theOneIsSet = 1;
		}
			
		return $this->theOne;
	}
}