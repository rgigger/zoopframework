<?php
class DbPdo extends DbConnection
{
	function init()
	{
		if($this->params['file'][0] != '/')
			$this->params['file'] = app_dir . '/var/' . $this->params['file'];
		
		$this->conn = new PDO('sqlite:' . $this->params['file']);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NAMED);
	}
	
	function escapeString($string)
	{
		return $this->conn->quote($string);
	}
	
	function _query($sql)
	{
		$result = $this->conn->query($sql);			
		return new DbPdoResult($result);
	}
	
	function getLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
}
