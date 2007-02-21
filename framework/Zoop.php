<?php
//	now we load the default config for zoop
include(zoop_dir . '/config.php');

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
		
		if(version_compare(phpversion(), '5.0.0', '<'))
		{
			require_once($fullPath);
		}
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
		
		switch($name)
		{
			case 'app':
				include(zoop_dir . '/app/Globals.php');
				include(zoop_dir . '/app/Application.php');
				break;
			case 'utils':
				include(zoop_dir . '/utils/Utils.php');
				break;
			case 'gui':
				include(zoop_dir . "/smarty/Smarty.class.php");
				include(zoop_dir . '/gui/gui.php');
				break;
			case 'zone':
				$this->loadLib('app');
				$this->loadLib('gui');
				include(zoop_dir . '/zone/Zone.php');
				include(zoop_dir . '/zone/ZoneApplication.php');
				include(zoop_dir . '/zone/GuiZone.php');
				break;
			case 'db':
				include(zoop_dir . '/db/config.php');
				include(zoop_dir . '/db/DbConnection.php');
				include(zoop_dir . '/db/DbPgResult.php');
				include(zoop_dir . '/db/DbPgsql.php');
				include(zoop_dir . '/db/DbObject.php');
				include(zoop_dir . '/db/DbFactory.php');
				include(zoop_dir . '/db/functions.php');
				break;
			case 'session':
				include(zoop_dir . '/session/config.php');
				include(zoop_dir . '/session/SessionPgsql.php');
				include(zoop_dir . '/session/SessionFactory.php');
				include(zoop_dir . '/session/Session.php');
				break;
			default:	
				trigger_error('unknown module: ' . $name);
		}
	}
}

$zoop = new Zoop();

function __autoload($className)
{
	$classPath = Zoop::getClassPath($className);
	if($classPath)
		require_once($classPath);
}