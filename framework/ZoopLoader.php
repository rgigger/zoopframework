<?php
class ZoopLoader
{
	static private $classes = array();
	
	static public function addClass($classname, $filename)
	{
		self::$classes[strtolower($classname)] = $filename;
	}
	
	static private function getClassPath($className)
	{
		$className = strtolower($className);
		if(isset(self::$classes[$className]))
			return self::$classes[$className];
		
		return false;
	}
	
	/**
	 * Automatic class loading handler.  This automatically loads a class using the path
	 * information that was registered using the ZoopLoader::class method 
	 *
	 * @param string $className Name of the class to load
	 */
	static function autoload($className)
	{
		$classPath = ZoopLoader::getClassPath($className);
		if($classPath)
			require_once($classPath);
	
		if(substr($className, 0, 5) == 'Zend_')
		{
			$parts = explode('_', $className);
			$modName = $parts[1];
			require_once(zoop_dir . "/vendor/Zend/$modName.php");
		}
	}
}

spl_autoload_register(array('ZoopLoader', 'autoload'));
