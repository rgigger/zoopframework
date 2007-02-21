<?php
class DbObject
{
	var $id;
	
	function DbObject($unique, $other = NULL)
	{
		switch(gettype($unique))
		{
			case 'integer':
				$this->id = $unique;
				break;
		}
	}
	
	function getTableName()
	{
		return strtolower(get_class($this));
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function getScalar($fieldName)
	{
		$tableName = $this->getTableName();
		return SqlFetchCell("select $fieldName from person where id = :id", array('id' => $this->id));
	}
}