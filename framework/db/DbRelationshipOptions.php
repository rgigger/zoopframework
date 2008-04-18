<?php
class DbRelationshipOptions extends DbRelationship
{
	protected $options;
	protected $localField;
	protected $remoteTable;
	protected $remoteKeyField;
	protected $remoteValueField;
	
	function __construct($name, $params, $dbObject)
	{
		parent::__construct($name, $params, $dbObject);
		
		if(isset($params['options']))
		{
			$this->options = $params['options'];
		}
		else
		{
			if(isset($params['local_field']))
				$this->localFieldName = $params['local_field'];
			else
				$this->localFieldName = $name . '_id';
			
			if(isset($params['option_table']))
				$this->remoteTable = $params['option_table'];
			else
				$this->remoteTable = $name;
			
			if(isset($params['option_key_field']))
				$this->remoteKeyField = $params['option_key_field'];
			else
				$this->remoteKeyField = 'id';

			if(isset($params['option_value_field']))
				$this->remoteValueField = $params['option_value_field'];
			else
				$this->remoteValueField = 'name';
		}
		
	}
	
	public function getInfo()
	{
		$key = $this->dbObject->getScalar($this->localFieldName);
		if(!$this->options[$key])
		{
			$sql = "select {$this->remoteValueField} from {$remoteTable} where {$this->remoteKeyField} = :id:int";
			$this->options[$key] = SqlFetchCell($sql, $key);
		}
				
		return $this->options[$key];
	}
}
