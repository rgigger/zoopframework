<?php
class DbRelationshipHasManyThrought extends DbRelationship
{
	protected $localField;
	protected $joinTable;
	protected $joinTableLocalField;
	protected $joinTableRemoteField;
	protected $remoteClass;
	protected $remoteField;
	
	function __construct($name, $params, $dbObject)
	{
		parent::__construct($name, $params, $dbObject);
		
		if(isset($params['local_field']))
			$this->localField = $params['local_field'];
		else
			$this->localField = 'id';
		
		if(isset($params['through']))
			$this->joinTable = $params['through'];
		else
			$this->joinTable = $name;
		
		if(isset($params['join_table_local_field']))
			$this->joinTableLocalField = $params['join_table_local_field'];
		else
			$this->joinTableLocalField = $this->getTableName() . '_id';
		
		if(isset($params['remote_class']))
			$this->remoteClass = $params['remote_class'];
		else
			$this->remoteClass = $name;
		
		if(isset($params['join_table_remote_field']))
			$this->joinTableRemoteField = $params['join_table_remote_field'];
		else
			$this->joinTableRemoteField = DbObject::_getTableName($this->remoteClass) . '_id';		
		
		if(isset($params['remote_field']))
			$this->remoteField = $params['remote_field'];
		else
			$this->remoteField = 'id';
	}
	
	private $theMany;
	
	public function getInfo()
	{
		if(!$this->theMany)
		{
			$remoteTable = DbObject::_getTableName($this->remoteClass);
			$sql = "select * from $remoteTable 
					inner join {$this->joinTable} on {$this->joinTable}.{$this->joinTableRemoteField} = {$remoteTable}.{$this->remoteField}
					where {$this->joinTable}.{$this->joinTableLocalField} = :id:int";
			$rows = SqlFetchRows($sql, array('id' => $this->dbObject->getScalar($this->localField)));
			
			$this->theMany = array();
			foreach($rows as $thisRow)
			{
				$this->theMany[] = new $className($thisRow);
			}
		}
				
		return $this->theMany;
	}
}
