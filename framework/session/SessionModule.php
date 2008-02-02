<?php
class SessionModule extends ZoopModule
{
	private static $engine;
	
	static function getEngine()
	{
		return self::$engine;
	}
	
	protected function init()
	{
		$this->hasConfig = true;
	}
	
	protected function getIncludes()
	{
		return array('SessionFactory.php');
	}
	
	function getClasses()
	{
		return array('Session', 'SessionPgsql', 'SessionDb');
	}
	
	protected function configure()
	{
		$params = $this->getConfig();
		self::$engine = SessionFactory::getEngine($params);
	}
}
