<?php
class SessionModule extends ZoopModule
{
	private static $engine;
	
	protected function init()
	{
		$this->addClass('Session');
		$this->addClass('SessionPgsql');
		$this->addClass('SessionDb');
		$this->addClass('SessionFactory');
		$this->depend('db');
		$this->hasConfig = true;
	}
	
	static function getEngine()
	{
		return self::$engine;
	}
	
	protected function configure()
	{
		$params = $this->getConfig();
		self::$engine = SessionFactory::getEngine($params);
	}
}
