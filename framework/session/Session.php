<?php
class Session
{
	function start()
	{
		global $SessionEngine;
		$SessionEngine->start();
	}
	
	function get($key = '__default__')
	{
		global $SessionEngine;
		return $SessionEngine->get($key);
	}
	
	function getWithLock($key = '__default__')
	{
		global $SessionEngine;
		return $SessionEngine->getWithLock($key);
	}
	
	function set($value, $key = '__default__')
	{
		global $SessionEngine;
		$SessionEngine->set($value, $key);
	}
}

$SessionEngine = SessionFactory('pgsql');