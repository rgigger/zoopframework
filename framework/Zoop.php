<?php
// do some bootstrapping

function define_once($name, $value)
{
	if(!defined($name))
		define($name, $value);
}
//	now we load the default config for zoop
// include(zoop_dir . '/config.php');	//	this file is now obsolete and depricated, in favor of the new config module
include(zoop_dir . '/ZoopLibrary.php');
include(zoop_dir . '/ZoopModule.php');
include(zoop_dir . '/ZoopLoader.php');

//	we want to load this before we do anything else so that everything else is easier to debug
// include(zoop_dir . '/core/app/Error.php');
// include(zoop_dir . '/core/app/Globals.php');

/**
 * This object is for basic framework management tasks, such as:
 * * Configuring auto-loading of zones and modules
 * * Loading libraries and domains
 */
class Zoop
{
	// static private $loaded = array(), $registered = array();
	static private $libs = array();
	
	/**
	 * registers a library
	 */
	
	static public function registerLib($libName, $path = null)
	{
		if(!$path)
			$path = zoop_dir . '/' . $libName;
		
		if(isset(self::$libs[$libName]))
			return;
		
		$libClassName = ucfirst($libName) . 'Library';
		include("$path/$libClassName.php");
		self::$libs[$libName] = new $libClassName($path);
	}
	
	/**
	 * loads a library, which just means loading all of it's modules
	 */
	
	static public function loadLib($libName)
	{
		// for check is for backwards compatibility
		//	in the future we can depricate it and change the self::loadMod
		//	call to a trigger_error("lib '$libName' not found") call
		if(isset(self::$libs[$libName]))
			self::$libs[$libName]->loadMods();
		else
			self::loadMod($libName);
	}
	
	/**
	 * finds out what lib a module is in and then loads it
	 */
	
	static public function loadMod($modName)
	{
		foreach(self::$libs as $lib)
			if($lib->hasMod($modName))
				return $lib->loadMod($modName);
		
		trigger_error("mod '$modName' not found");
	}
	
	static public function expandPath($path)
	{
		if($path[0] == '/')
			return $path;
			
		return app_dir . '/' . $path;
	}
	
	static public function getTmpDir()
	{
		return Config::getFilePath('zoop.tmpDir');;
	}
	
	//	deprecated stuff
	
	/**
	 * static -- Register a class for auto-loading with the name of the class
	 * and the full path of the file that contains it.
	 *
	 * @param string $className
	 * @param string $fullPath
	 */
	static function registerClass($className, $fullPath)
	{
		ZoopLoader::addClass($className, $fullPath);
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
	
	
	// static $libList = array();
	// 
	// /**
	//  * Key => Value list of registered classes and the full path of the file that contains them
	//  *
	//  * @var array
	//  */
	// var $classList;
	// 
	// 
	// /**
	//  * Register a class for auto-loading with the name of the class
	//  * and the full path of the file that contains it.
	//  *
	//  * @param string $className
	//  * @param string $fullPath
	//  */
	// function _registerClass($className, $fullPath)
	// {
	// 	$this->classList[strtolower($className)] = $fullPath;
	// }
	// 
	// /**
	//  * Returns the full path and filename associated with the given registered class
	//  *
	//  * @param string $className
	//  * @return string - full path and filename of the class
	//  */
	// function _getClassPath($className)
	// {
	// 	$className = strtolower($className);
	// 	if(isset($this->classList[$className]))
	// 		return $this->classList[$className];
	// 	
	// 	return false;
	// }
	// 
	// /**
	//  * static -- Register a class for auto-loading with the name of the class
	//  * and the full path of the file that contains it.
	//  *
	//  * @param string $className
	//  * @param string $fullPath
	//  */
	// static function registerClass($className, $fullPath)
	// {
	// 	global $zoop;
	// 	$zoop->_registerClass($className, $fullPath);
	// }
	// 
	// /**
	//  * static -- Returns the full path and filename associated with the given registered class
	//  *
	//  * @param string $className
	//  * @return string - full path and filename of the class
	//  */
	// function getClassPath($className)
	// {
	// 	global $zoop;
	// 	return $zoop->_getClassPath($className);
	// }
	// 
	// /**
	//  * Loads a library of specified name.  Modules are located in the root
	//  * directory of the framework, using the following naming scheme:
	//  * 	<root>/<module>/<module>.php
	//  *
	//  * @param string $name
	//  */
	// static function loadLib($name, $isVendor = false)
	// {
	// 	echo "$name<br>";
	// 	var_dump($isVendor);
	// 	//	put some code in here to make sure we don't reload modules that have already been loaded
	// 	if(isset(self::$libList[$name]))
	// 		return;
	// 	self::$libList[$name] = 1;
	// 	
	// 	//	temporary measure so I can test without having to convert all of the modules over to the new format right away
	// 	if(file_exists(zoop_dir . "/$name/module.php"))
	// 	{
	// 		include(zoop_dir . "/$name/module.php");
	// 	}
	// 	else
	// 	{
	// 		if($isVendor)
	// 		{
	// 			$moduleName = ucfirst($name) . 'Module';
	// 			include(zoop_dir . "/vendor/$moduleName.php");
	// 			$module = new $moduleName();
	// 		}
	// 		else
	// 		{
	// 			$moduleName = ucfirst($name) . 'Module';
	// 			include(zoop_dir . "/$name/$moduleName.php");
	// 			$module = new $moduleName();
	// 		}
	// 	}
	// }
	// 
	
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

Zoop::registerLib('boot');
Zoop::registerLib('core');
Zoop::registerLib('experimental');
Zoop::registerLib('vendor');
Zoop::loadLib('boot');