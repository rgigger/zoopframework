<?php
/**
 * Configuration class
 * 
 * Provides methods for retrieving configuration options from a YAML config file.
 *
 */
class Config
{
	private static $info = array();
	private static $file;
	
	static public function suggest($file, $prefix = NULL)
	{
		if($prefix)
			$root = &self::getReference($prefix);
		else
			$root = &self::$info;
		
		$root = self::mergeArray(Yaml::read($file), $root);
	}
	
	static public function insist($file, $prefix = NULL)
	{
		$root = $prefix ? self::getReference($prefix) : self::$info;
		self::$info = self::mergeArray($root, Yaml::read($file));
	}
	
	static public function mergeArray($suggested, $insisted)
	{
		return self::_mergeArray($suggested, $insisted);
	}
	
	static public function _mergeArray(&$suggested, &$insisted)
	{
		foreach($insisted as $key => $val)
		{
<<<<<<< HEAD
			if(is_array($val))
				self::_mergeArray($suggested[$key], $insisted[$key]);
			else
				$suggested[$key] = $val;
=======
			assert(is_string($key));
			if(is_array($val))
				self::_mergeArray($suggested[$key], $insisted[$key]);
			
			$suggested[$key] = $val;
>>>>>>> reorganize the modules into libraries
		}
		
		return $suggested;
	}
	
	/**
	 * Specify configuration file to use
	 *
	 * @param string $file Path and filename of the config file to use
	 */
	static function setConfigFile($file)
	{
		self::$file = $file;
	}
	
	/**
	 * Loads the config file specified by the $file member variable (or app_dir/config.yaml) 
	 *
	 */
	static function load()
	{
		self::suggest(zoop_dir . '/config.yaml', 'zoop');
		
		if(!self::$file)
			self::setConfigFile(app_dir . '/config.yaml');
		self::insist(self::$file);
		
		if(defined('instance_config') && instance_config)
			self::insist(instance_config);
	}
	
	/**
	 * Returns configuration options based on a path (i.e. zoop.db or zoop.application.info)
	 *
	 * @param string $path Path for which to fetch options
	 * @return array of configuration values
	 */
	static function get($path)
	{
		$parts = explode('.', $path);
		$cur = self::$info;
		
		foreach($parts as $thisPart)
			if(isset($cur[$thisPart]))
				$cur = $cur[$thisPart];
			else
				return false;
		
		return $cur;
	}	
	
	static function &getReference($path)
	{
		$parts = explode('.', $path);
		$cur = &self::$info;
		
		foreach($parts as $thisPart)
		{
			if(isset($cur[$thisPart]))
				$cur = &$cur[$thisPart];
			else
			{
				$cur[$thisPart] = array();
				$cur = &$cur[$thisPart];
			}
		}
		
		return $cur;
	}
	
	//	functions for getting scalar values and then formatting them
	static public function getFilePath($configPath)
	{
		$config = self::get($configPath);
		assert(is_string($config));
		return Zoop::expandPath($config);
	}
}
