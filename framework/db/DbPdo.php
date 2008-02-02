<?php
class DbPdo extends DbConnection
{
	function DbPdo($params)
	{
		if($params['file'][0] != '/')
			$params['file'] = app_dir . '/var/' . $params['file'];
		
		$this->conn = new PDO('sqlite:' . $params['file']);
		// try {
		// 	$this->conn = new PDO('sqlite:' . $params['file']);
		// }
		// catch(PDOException $e)
		// {
		// 		die('pdoexception');
		// }
	}
	
	function escapeString($string)
	{
		return $this->conn->quote($string);
		//return "'$string'";
	}
	
	function _query($sql)
	{
		// echo $sql . '<br>';
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$result = $this->conn->query($sql);			
		return new DbPdoResult($result);
	}
	
	function getLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
}

/*
try {
	$dbh = new PDO($params['file']);
	foreach($dbh->query('SELECT * from foo') as $row)
	{
		print_r($row);
	}
	$dbh = null;
}
catch (PDOException $e) {
   print "Error!: " . $e->getMessage() . "<br/>";
   die();
}

die();
*/

/*
$connString = 'dbname=' . $params['database'];
$connString .= ' user=' . $params['username'];
if(isset($params['host']))
	$connString .= ' host=' . $params['host'];
if(isset($params['port']))
	$connString .= ' port=' . $params['port'];

$this->conn = pg_connect($connString, PGSQL_CONNECT_FORCE_NEW);
*/
