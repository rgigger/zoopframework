<?php
class DbModule extends ZoopModule
{
	private static $connections = array();
	
	/**
	 * Returns a DbConnection object to the database called "$name"
	 *
	 * @param string $name
	 * @return DbConnection
	 */
	function getConnection($name)
	{
		if(!isset(self::$connections[$name]))
			trigger_error("connection '$name' does not exist");
		return self::$connections[$name];
	}
	
	/**
	 * Returns a DbConnection object for the default database connection
	 *
	 * @return DbConnection
	 */
	function getDefaultConnection()
	{
		return self::getConnection('default');
	}
	
	/**
	 * This method is overridden to tell zoop which files to include with this module
	 *
	 * @return array of filenames to include
	 */
	function getIncludes()
	{
		return array('functions.php');
	}
	
	/**
	 * This method is overridden to tell zoop which classes exist as part of this module
	 * so that they can be added to the autoloader
	 *
	 * @return unknown
	 */
	function getClasses()
	{
		return array('DbConnection', 'DbFactory', 'DbSchema', 'DbObject', 'DbZone', 'DbTable', 'DbField',
						'DbPdo', 'DbPdoResult', 'DbPgsql', 'DbPgResult', 'DbMysql', 'DbMysqlResult');
	}
	
	/**
	 * This method reads the configuration options (using the getConfig method)
	 * and initializes the database connections.
	 *
	 */
	function configure()
	{
		$connections = $this->getConfig();
		if($connections)
		{
			foreach($connections as $name => $params)
			{
				self::$connections[$name] = DbFactory::getConnection($params, $name);
			}
		}
	}	
}