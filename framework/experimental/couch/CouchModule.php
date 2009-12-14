<?php
class CouchModule extends ZoopModule
{
	private static $connections = array();
	
	static function getConnection($name)
	{
		if(!isset(self::$connections[$name]))
			trigger_error("connection '$name' does not exist");
		return self::$connections[$name];
	}
	
	static function getDefaultConnection()
	{
		return self::getConnection('default');
	}
	
	function getClasses()
	{
		return array('CouchConnection', 'CouchDocument', 'CouchHttp');
	}
	
	function configure()
	{
		$connections = $this->getConfig();
		if($connections)
		{
			foreach($connections as $name => $params)
			{
				self::$connections[$name] = new CouchConnection($params);
			}
		}
	}	
}