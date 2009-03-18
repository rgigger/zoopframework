<?php
class CouchHttp
{
	private $host;
	private $port;
	
	function __construct($host = 'localhost', $port = 5984)
	{
		$this->host = $host;
		$this->port = $port;
	}

	function send($method, $url, $postData = NULL)
	{
		$method = strtoupper($method);
		// var_dump($postData);
		$s = fsockopen($this->host, $this->port, $errno, $errstr);

		if(!$s)
			trigger_error("socket error: $errno, message: $errstr");
		
		$request = "$method $url HTTP/1.0\r\nHost: {$this->host}\r\n";

		if($postData)
		{
			$request .= "Content-Length: ".strlen($postData)."\r\n";
			// if(strtolower($method) == 'post')
			// 	$request .= 'Content-Type: text/javascript'."\r\n";
			// else
			// 	$request .= 'Content-Type: application/json'."\r\n";
			$request .= "\r\n";
			$request .= "$postData\r\n";
		}
		else
		{
			$request .= "\r\n";
		}
		
		// var_dump($request);
		
		fwrite($s, $request);

		$response = "";
		while(!feof($s))
		{
			$response .= fgets($s);
		}
		
		if(!$response)
		{
			// var_dump($response);
			trigger_error("request failed");
			return false;
		}
		
		list($headers, $body) = explode("\r\n\r\n", $response);
		
		$bodyObject = json_decode($body);
		
		if(isset($bodyObject->error) && $bodyObject->error)
			trigger_error("couchdb: {$bodyObject->error}: {$bodyObject->reason}");
		
		return $bodyObject;
	}
}
