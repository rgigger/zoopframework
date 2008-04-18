<?php
class DbRelationshipBelongsTo extends DbRelationship
{
	private $owner;
	protected $localFieldName;
	protected $remoteClassName;
	protected $remoteFieldName;
	
	function __construct($name, $params, $dbObject)
	{
		parent::__construct($name, $params, $dbObject);
		if(isset($params['class']))
			$this->remoteClassName = $params['class'];
		else
			$this->remoteClassName = $name;
		
		if(isset($params['remote_field']))
			$this->remoteFieldName = $params['remote_field'];
		else
			$this->remoteFieldName = 'id';
		
		if(isset($params['local_field']))
			$this->localFieldName = $params['local_field'];
		else
			$this->localFieldName = DbObject::_getTableName($this->remoteClassName) . '_id';
	}
		
	public function getInfo()
	{
		if(!$this->owner)
		{
			$remoteTableName = DbObject::_getTableName($this->remoteClassName);
			$sql = "select * from $remoteTableName where {$this->remoteFieldName} = :id:int";
			$row = $this->dbObject->getDb()->fetchRow($sql, array($this->remoteFieldName => $this->dbObject->getField($this->localFieldName)));
			$this->owner = new $this->remoteClassName($row);
		}
		
		return $this->owner;
	}
}