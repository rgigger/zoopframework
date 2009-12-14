<?php
class CouchDocument
{
	private $id;
	private $rev;
	private $data;
	
	static function findAll()
	{
		return CouchModule::getConnection(self::getConnectionName())->getAllDocuments();
	}
	
	function __construct($id, $rev = null)
	{
		$this->id = $id;
		$this->rev = $rev;
	}
	
	static private function getConnectionName()
	{
		return 'default';
	}
	
	private function getDb()
	{
		return CouchModule::getConnection(self::getConnectionName());
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getRev()
	{
		return $this->rev;
	}
	
	public function load()
	{
		$dbName = $this->getDb()->getdbName();
		$resp = $this->getDb()->getHttp()->send("GET", "/$dbName/{$this->id}");
		foreach($resp as $key => $value)
		{
			if($key == '_id')
				$this->id = $value;
			else if($key == '_rev')
				$this->rev = $value;
			else
			{
				$this->data->$key = $value;
			}
		}
	}
	
	public function save()
	{
		$dbName = $this->getDb()->getDbName();
		
		$data->_id = $this->id;
		if($this->rev)
			$data->_rev = $this->rev;
		foreach($this->data as $key => $val)
		{
			$data->$key = $val;
		}
		
		$data = json_encode($data);
		
		$resp = $this->getDb()->getHttp()->send("PUT", "/$dbName/$this->id", $data);
		
		if($resp->ok != true)
			trigger_error("saving document '{$this->id}:{$this->rev}' failed");
		
		$this->rev = $resp->rev;
	}
	
	public function getData()
	{
		return $this->data();
	}
	
	function __get($varname)
	{
		if(!isset($this->data->$varname))
			trigger_error("field does not exist: $varname");
		return $this->data->$varname;
	}
	
	function __set($varname, $value)
	{
		$this->data->$varname = $value;
	}	
}