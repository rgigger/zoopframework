<?php
class DbModule extends ZoopModule
{
	private static $connections = array();
	
	function getConnection($name)
	{
		return self::$connections[$name];
	}
	
	function getDefaultConnection()
	{
		return self::getConnection('default');
	}
	
	function getIncludes()
	{
		return array('functions.php');
	}
	
	function getClasses()
	{
		return array('DbConnection', 'DbPgResult', 'DbPgsql', 'DbPdo',
						'DbPdoResult', 'DbObject', 'DbFactory', 'DbSchema');
	}
	
	function configure()
	{
		$connections = $this->getConfig();
		foreach($connections as $name => $params)
		{
			self::$connections[$name] = DbFactory::getConnection($params, $name);
		}
	}	
}
