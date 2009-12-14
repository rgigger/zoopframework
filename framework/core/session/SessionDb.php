<?php
class SessionDb
{
	var $db;
	var $style;
	var $saveChanges;
	var $sessionName;
	var $sessionId;
	
	public function __construct($params)
	{
		$this->db = DbModule::getConnection($params['db_connection']);
		$this->style = $params['style'];
	}
	
	//	user functions
	function start()
	{
		$this->saveChanges = 0;
		session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
		session_start();
	}
	
	function get($key = '__default__')
	{
		$row = $this->db->selsertRow('session_data', array('value'), array('session_id' => $this->sessionId, 'key' => $key));
		
		if($row['value'])
			return $row['value'];
		else
			return NULL;
	}
	
	function getWithLock($key = '__default__')
	{
		
	}
	
	function set($value, $key = '__default__')
	{
		$this->db->upsertRow('session_data', array('session_id' => $this->sessionId, 'key' => $key), array('value' => $value));
	}
	
	function saveChanges()
	{
		if(session_style != 'serialize_manaul')
			trigger_error("this function is only used with session_style = 'serialize_manaual'");
		$this->saveChanges = 1;
	}
	
	function saveChangesUnsafe()
	{
		if($this->style != 'write_manual')
			trigger_error("this function is only used with session_style = 'write_manaual'");
		$this->saveChanges = 1;
	}
	
	//	callbacks
	function open($savePath, $sessionName)
	{
		$this->sessionName = $sessionName;
		return true;
	}
	
	function close()
	{
		return true;
	}
	
	function read($sessionId)
	{
		$this->sessionId = $sessionId;
		//	make sure the session_base record is in there
		$this->db->upsertRow('session_base', array('session_id' => $sessionId), array('last_active:keyword' => 'CURRENT_TIMESTAMP'));
		
		$data = $this->get();
		
		//	get the default row
		return $data;
	}
	
	function write($sessionId, $sessionData)
	{
		switch($this->style)
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
		
		return true;
	}
	
	function destroy($sessionId)
	{
		$this->db->beginTransaction();
		$this->db->deleteRows("delete from session_data where session_id = :session_id", array('session_id' => $sessionId));
		$this->db->deleteRow("delete from session_base where session_id = :session_id", array('session_id' => $sessionId));
		$this->db->commitTransaction();
		return true;
	}
	
	function gc($maxLifetime)
	{
		$this->db->beginTransaction();
		$this->db->deleteRows("delete from session_base where extract(epoch from CURRENT_TIMESTAMP - last_active) > :maxLifetime", array('maxLifetime' => $maxLifetime));
		$this->db->deleteRows("delete from session_data where session_id not in (select session_id from session_base)", array());
		$this->db->commitTransaction();
		return true;
	}
}
