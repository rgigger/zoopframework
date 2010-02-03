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
			if(isset($params['localField']))
				$this->localFieldName = $params['localField'];
			else
				$this->localFieldName = $name . '_id';
			
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
	
	public function isTiedToField($fieldName)
	{
		return $this->localFieldName == $fieldName;
	}
	
	public function getOptions()
	{
		if($this->options)
			return $this->options;
		else
			return SqlFetchSimpleMap("select {$this->remoteKeyField}, {$this->remoteValueField} from {$this->remoteTable}", $this->remoteKeyField, $this->remoteValueField, array());
	}
	
	public function getInfo()
	{
		$options = $this->getOptions();
		$field = $this->localFieldName;
		return $options[$this->dbObject->$field];
	}
}
