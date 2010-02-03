<?php
//	this all needs to be thought out better and redone
class DbFactory
{
	private static $classMap = array('php_pgsql' => 'DbPgsql', 'php_mysql' => 'DbMysql', 
										'pdo' => 'DbPdo', 'php_mssql' => 'DbMssql');
	
	static function getConnection($params, $name)
	{
		if(!isset(self::$classMap[$params['driver']]))
			trigger_error("unknown driver type: " . $params['driver']);
		else
			$className = self::$classMap[$params['driver']];
		
		return new $className($params, $name);
	}

}
