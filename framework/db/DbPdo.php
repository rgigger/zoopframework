<?php
class DbPdo extends DbConnection
{
	function DbPdo($params)
	{
		$this->conn = new PDO('sqlite:' . $params['file']);
	}
	
	function escapeString($string)
	{
		return "'$string'";
	}
	
	function _query($sql)
	{
		$result = $this->conn->query($sql);
		return new DbPdoResult($result);
	}
	
	function getLastInsertId()
	{
		return $this->fetchCell("select lastval()", array());
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
