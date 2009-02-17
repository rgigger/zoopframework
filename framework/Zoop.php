<?php
//	now we load the default config for zoop
include(zoop_dir . '/config.php');	//	this file is now obsolete and depricated, in favor of the new config module
include(zoop_dir . '/ZoopModule.php');

//	we want to load this before we do anything else so that everything else is easier to debug
include(zoop_dir . '/app/Error.php');

/**
 * This object is for basic framework management tasks, such as:
 * * Configuring auto-loading of zones and modules
 * * Loading libraries and domains
 */
class Zoop
{
	/**
	 * Key => Value list of registered classes and the full path of the file that contains them
	 *
	 * @var array
	 */
	var $classList;
	
	/**
	 * When a Zoop object is instantiated, the "utils" library is automatically
	 * imported and the app_tmp_dir constant is defined (to projectdir/tmp)
	 *
	 * @return Zoop
	 */
	function Zoop()
	{
		$this->classList = array();
		
		$this->loadLib('utils');
		DefineOnce('app_tmp_dir', app_dir . '/tmp');
	}
	
	/**
	 * Register a class for auto-loading with the name of the class
	 * and the full path of the file that contains it.
	 *
	 * @param string $className
	 * @param string $fullPath
	 */
	function _registerClass($className, $fullPath)
	{
		$this->classList[strtolower($className)] = $fullPath;
	}
	
	/**
	 * Returns the full path and filename associated with the given registered class
	 *
	 * @param string $className
	 * @return string - full path and filename of the class
	 */
	function _getClassPath($className)
	{
		$className = strtolower($className);
		if(isset($this->classList[$className]))
			return $this->classList[$className];
		
		return false;
	}
	
	/**
	 * static -- Register a class for auto-loading with the name of the class
	 * and the full path of the file that contains it.
	 *
	 * @param string $className
	 * @param string $fullPath
	 */
	static function registerClass($className, $fullPath)
	{
		global $zoop;
		$zoop->_registerClass($className, $fullPath);
	}
	
	/**
	 * Register a "domain" class for autoload (a domain class is a
	 * class that is located in the "domains" directory under
	 * the project root with the filename <classname>.php)
	 *
	 * @param unknown_type $className
	 */
	static public function registerDomain($className)
	{
		self::registerClass($className, app_dir . '/domain/' . $className . '.php');
	}
	
	/**
	 * static -- Returns the full path and filename associated with the given registered class
	 *
	 * @param string $className
	 * @return string - full path and filename of the class
	 */
	function getClassPath($className)
	{
		global $zoop;
		return $zoop->_getClassPath($className);
	}
	
	/**
	 * Loads a library of specified name.  Modules are located in the root
	 * directory of the framework, using the following naming scheme:
	 * 	<root>/<module>/<module>.php
	 *
	 * @param string $name
	 */
	function loadLib($name)
	{
		//	allow static calls to use the singleton object
		//	this is not the way to do this
		if(!isset($this))
		{
			global $zoop;
			$zoop->loadLib($name);
			return;
		}
		
		//	put some code in here to make sure we don't reload modules that have already been loaded
		
		//	temporary measure so I can test without having to convert all of the modules over to the new format right away
		if(file_exists(zoop_dir . "/$name/module.php"))
		{
			include(zoop_dir . "/$name/module.php");
		}
		else
		{
			$moduleName = ucfirst($name) . 'Module';
			include(zoop_dir . "/$name/$moduleName.php");
			$module = new $moduleName();
		}
	}
	
	/**
	 * Automatic class loading handler.  This automatically loads a class using the path
	 * information that was registered using the Zoop::registerClass or ::registerDomain
	 * method 
	 *
	 * @param string $className Name of the class to load
	 */
	static function autoload($className)
	{
		$classPath = Zoop::getClassPath($className);
		if($classPath)
		{
			require_once($classPath);
		}

		if(substr($className, 0, 5) == 'Zend_')
		{
			$parts = explode('_', $className);
			$modName = $parts[1];
			require_once(zoop_dir . "/Zend/$modName.php");
		}
	}
}

$zoop = new Zoop();
Zoop::loadLib('config');
Config::load();
spl_autoload_register(array('Zoop', 'autoload'));