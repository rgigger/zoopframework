<?php
abstract class DbRelationshipBasic extends DbRelationship
{
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
			$this->remoteFieldName = $this->dbObject->getTableName() . '_id';
		
		if(isset($params['local_field']))
			$this->localFieldName = $params['local_field'];
		else
			$this->localFieldName = 'id';
	}
}
