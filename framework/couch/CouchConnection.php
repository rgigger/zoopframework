<?php
class CouchConnection
{
	private $http;
	private $dbName;
	
	function __construct($params)
	{
		if(!isset($params['host']))
			$params['host'] = 'localhost';
		if(!isset($params['port']))
			$params['port'] = 5984;
		
		$this->dbName = $params;
		$this->http = new CouchHttp($params['host'], $params['port']);
	}
	
	public function getAllDocuments()
	{
		$documentInfo = $this->http->send("GET", "/{$this->name}/_all_docs");
		$documents = array();
		foreach($documentInfo->rows as $thisRow)
		{
			$documents[] = new CouchDocument($this, $thisRow->id, $thisRow->value->rev);
		}
		return $documents;
	}
}
