<?php
//	now we load the default config for zoop
include(zoop_dir . '/config.php');	//	this file is now obsolete and depricated, in favor of the new config module
include(zoop_dir . '/ZoopModule.php');

//	we want to load this before we do anything else so that everything else is easier to debug
include(zoop_dir . '/app/Error.php');

class Zoop
{
	var $classList;
	
	function Zoop()
	{
		$this->classList = array();
		
		$this->loadLib('utils');
		DefineOnce('app_tmp_dir', app_dir . '/tmp');
	}
	
	function _registerClass($className, $fullPath)
	{
		$this->classList[strtolower($className)] = $fullPath;
	}
	
	function _getClassPath($className)
	{
		$className = strtolower($className);
		if(isset($this->classList[$className]))
			return $this->classList[$className];
		
		return false;
	}
	
	//	static - singleton
	function registerClass($className, $fullPath)
	{
		global $zoop;
		$zoop->_registerClass($className, $fullPath);
	}
	
	//	static - singleton
	function getClassPath($className)
	{
		global $zoop;
		return $zoop->_getClassPath($className);
	}
	
	function loadLib($name)
	{
		//	allow static calls to use the singleton object
		if(!isset($this))
		{
			global $zoop;
			$zoop->loadLib($name);
			return;
		}
		
		//	temporary meausre so I can test without having to convert all of the modules over to the new format right away
		if(file_exists(zoop_dir . "/$name/module.php"))
			include(zoop_dir . "/$name/module.php");
		else
		{
			$moduleName = ucfirst($name) . 'Module';
			include(zoop_dir . "/$name/$moduleName.php");
			$module = new $moduleName();
		}
	}
}

$zoop = new Zoop();

function __autoload($className)
{
	$classPath = Zoop::getClassPath($className);
	if($classPath)
	{
		require_once($classPath);
	}
}

Zoop::loadLib('config');
Config::load();