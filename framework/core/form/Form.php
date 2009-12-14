<?php
class Form
{
	private $id;
	private $bindings;
	private $sessionId;
	
	function __construct()
	{
		$this->bindings = array();
		$this->sessionId = session_id();
	}
	
	public function addBinding($class, $id, $field)
	{
		$newBinding = new FormBinding($class, $id, $field);
		$this->bindings[] = $newBinding;
		return $newBinding->getName();
	}
	
	public function saveBindings()
	{
		if(empty($this->bindings))
			return;
		$parts = array();
		foreach($this->bindings as $thisBinding)
			$parts[] = $thisBinding->getString();
		$listString = implode(',', $parts);
		
		$this->id = SqlInsertArray('session_form', array('session_id' => $this->sessionId, 'fields' => $listString));
	}
	
	static public function appendBindings($newBindings)
	{
		$formId = getPostInt('_zoop_form_id');
		$sessionId = session_id();
		//	IMPORTANT SECURITY NOTE:
		//		even though session.id is going to be a unique identifier we still need to check to make sure that it 
		//		has the correct session_id to prevent spoofing
		$fieldString = SqlFetchCell("select fields from session_form where session_id = :sessionId and id = :formId",
							array('sessionId' => $sessionId, 'formId' => $formId));
		
		if(!$fieldString)
			trigger_error("session_form row $formId not found.  Possible attempt to spoof session data.");
		
		$parts = array();
		foreach($newBindings as $thisBinding)
		{
			if(is_array($thisBinding))
				$bindingObject = new FormBinding($thisBinding['class'], $thisBinding['id'], $thisBinding['field']);
			else
				$bindingObject = $thisBinding;
			$parts[] = $bindingObject->getString();
		}
			
		$appendString = implode(',', $parts);
		
		SqlUpdateRow("update session_form set fields = :newFieldString where session_id = :sessionId and id = :formId",
							array('sessionId' => $sessionId, 'formId' => $formId, 'newFieldString' => $fieldString . ',' . $appendString));		
	}
	
	public function getTagInfo()
	{
		return array('_zoop_form_id', $this->id);
	}
	
	static public function save()
	{
		if(!isset($_POST['_zoop_form_id']) || !$_POST['_zoop_form_id'])
			return;
		
		$formId = $_POST['_zoop_form_id'];
		$sessionId = session_id();
		//	IMPORTANT SECURITY NOTE:
		//		even though session.id is going to be a unique identifier we still need to check to make sure that it 
		//		has the correct session_id to prevent spoofing
		$fieldString = SqlFetchCell("select fields from session_form where session_id = :sessionId and id = :formId",
							array('sessionId' => $sessionId, 'formId' => $formId));
		
		if(!$fieldString)
			trigger_error("session_form row $formId not found.  Possible attempt to spoof session data.");
		
		$objects = array();
		foreach(explode(',', $fieldString) as $thisFieldString)
		{
			list($class, $id, $field) = explode(':', $thisFieldString);
			if(!isset($_POST['_zoop_form_element'][$class][$id][$field]))
				continue;
			$objectId = "$class:$id";
			if(!isset($objects[$objectId]))
				$objects[$objectId] = new $class($id);
			
			$objects[$objectId]->$field = $_POST['_zoop_form_element'][$class][$id][$field];
		}
		
		foreach($objects as $thisObject)
			$thisObject->save();
	}
}
