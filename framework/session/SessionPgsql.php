<?php
class SessionPgsql
{
	var $db;
	var $saveChanges;
	
	//	user functions
	function start()
	{
		$this->saveChanges = 0;
		session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
		session_start();
	}
	
	function get($key = '__default__')
	{
		$row = $this->db->selsertRow('session_data', array('data'), array('session_id' => $sessionId, 'key' => $key));
		
		if($row['data'])
			return unserialize($row['data']);
		else
			return NULL;
	}
	
	function getWithLock($key = '__default__')
	{
		
	}
	
	function set($value, $key = '__default__')
	{
		
	}
	
	function saveChanges()
	{
		if(session_style != 'serialize_manaul')
			trigger_error("this function is only used with session_style = 'serialize_manaual'");
		$this->saveChanges = 1;
	}
	
	function saveChangesUnsafe()
	{
		if(session_style != 'write_manaul')
			trigger_error("this function is only used with session_style = 'write_manaual'");
		$this->saveChanges = 1;
	}
	
	//	callbacks
	function open($savePath, $sessionId)
	{
		$this->db = new DbPgsql();
		return true;
	}
	
	function close()
	{
		return true;
	}
	
	function read($sessionId)
	{
		//	make sure the session_base record is in there
		$this->db->upsertRow('session_base', array('session_id' => $sessionId), array('last_active' => 'CURRENT_TIMESTAMP'));
		
		//	get the default row
		return $this->get();
	}
	
	function write($sessionId, $sessionData)
	{
		switch(session_style)
		{
			case 'serialize_manual':
			case 'write_manual':
				if($this->saveChanges)
					$this->set($sessionData);
				break;
			default:
				trigger_error("session style '" . session_style . "' not yet implemented");
				break;
		}
	}
	
	function destory($sessionId)
	{
		$this->db->beginTransaction();
		$this->db->deleteRows("delete from session_data where session_id = :session_id", array('session_id' => $sessionId));
		$this->db->deleteRow("delete from session_base where session_id = :session_id", array('session_id' => $sessionId));
		$this->db->commitTransaction();
	}
	
	function gc($maxLifetime)
	{
		$this->db->beginTransaction();
		$this->db->deleteRow("delete from session_base where CURRENT_TIMESTAMP - extract(epoch from timestamp with time zone last_active) < :maxLifetime", array('maxLifetime' => $maxLifetime));
		$this->db->deleteRows("delete from session_data left outer join session_base on session_date.session_id = session_base.session_id where session_base.session_id is null", array());
		$this->db->commitTransaction();		
	}
}