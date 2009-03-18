<?php
class Form
{
	private $id;
	private $bindings;
	private $sessionId;
	
	function __construct()
	{
		$this->bindings = array();
		$this->sessionId = Session::getId();
	}
	
	public function addBinding($class, $id, $field)
	{
		$newBinding = new FormBinding($class, $id, $field);
		$this->bindings[] = $newBinding;
		return $newBinding->getName();
	}
	
	public function saveBindings()
	{
		$parts = array();
		foreach($this->bindings as $thisBinding)
			$parts[] = $thisBinding->getString();
		$listString = implode(',', $parts);
		
		$this->id = SqlInsertArray('session_form', array('session_id' => $this->sessionId, 'fields' => $listString));
	}
	
	public function getTagInfo()
	{
		return array('_zend_form_id', $this->id);
	}
	
	static public function save()
	{
		$formId = $_POST['_zoop_form_id'];
		$sessionId =  = Session::getId();
		$fieldString = SqlFetchCell("select fields from session_form where session_id = :sessionId and form_id = :formId",
							array('sessionId' => $sessionId, 'formId' => $formId));
		
		$objects = array();
		foreach(explode(',', $fieldString) as $thisFieldString)
		{
			list($class, $id, $field) = explode(':', $thisFieldString);
			$objectId = "$class:$id";
			if(!isset($objects[$objectId]))
				$objects[$objectId] = new $class($id);
			
			$objects[$objectId]->$field = $_POST['_zoop_form_element'][$class][$id][$field];
		}
		
		foreach($objects as $thisObject)
			$thisObject->save();
	}
}
