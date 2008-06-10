<?php
class Person extends DbObject
{
	const rootId = 1;
	
	protected function init()
	{
		$this->hasMany('request', array('remote_field' => 'owner_id'));
		$this->addGetter('name');
	}
	
	public function getName()
	{
		return $this->firstname . ' ' . $this->lastname;
	}
		
	public function getPermittedRequests()
	{
		if($this->id == self::rootId)
			$requests = DbObject::_find('Request', NULL, array('orderby' => 'id'));
		else
			$requests = $this->request;
		return $requests;
	}
	
	public function RequestIsAllowed($request)
	{
		if(is_numeric($request))
			$request = new Request($request);
		else if(!($request instanceof Request))
			trigger_error("request not valid: $request");
		
		return $request->owner_id == $this->id;
	}
}
