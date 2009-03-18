<?php
class CouchDocument
{
	private $conn;
	private $id;
	private $rev;
	public $data;
	
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
		$dbName = $this->getDb()->getName();
		$resp = $this->getDb()->getHttp()->send("GET", "/$dbName/$this->id");
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
		$dbName = $this->getDb()->getName();
		
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
}