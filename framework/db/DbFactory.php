<?php
//	this all needs to be thought out better and redone
class DbFactory
{
	//	static
	function getClassMap()
	{
		global $DbFactoryClassMap;
		$DbFactoryClassMap['pgsql_php'] = 'DbPgsql';
		$DbFactoryClassMap['pdo'] = 'DbPdo';
		return $DbFactoryClassMap;
	}
	
	//	static
	function getConnection($params)
	{
		$map = self::getClassMap();
		if(!isset($map[$params['driver']]))
			trigger_error("unknown driver type: " . $params['driver']);
		else
			$className = $map[$params['driver']];
		
		return new $className($params);
	}
	
	function getDefaultConnection()
	{
		global $DefaultDb;
		
		if(!$DefaultDb)
		{
			assert(db_use_default_connection);
			$params = array();
			$params['driver'] = db_driver;
			
			if(db_driver == 'pgsql_php')
			{
				if(defined('db_database'))
					$params['database'] = db_database;
				if(defined('db_username'))
					$params['username'] = db_username;
				if(defined('db_password'))
					$params['password'] = db_password;
				if(defined('db_host'))
					$params['host'] = db_host;
				if(defined('db_port'))
					$params['port'] = db_port;
			}
			else if(db_driver == 'pdo')
			{
				if(db_rdbms == 'sqlite')
				{
					$params['file'] = db_file;
				}
			}
			

			$DefaultDb = self::getConnection($params);
		}
		
		return $DefaultDb;
	}
}

global $DbFactoryClassMap;
$DbFactoryClassMap = array();


global $DefaultDb;
if(db_use_default_connection)
	$DefaultDb = DbFactory::getDefaultConnection();
else
	$DefaultDb = NULL;
	