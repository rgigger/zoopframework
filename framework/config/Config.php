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
		$root = array_merge(Yaml::read($file), $root);
	}
	
	static public function insist($file, $prefix = NULL)
	{
		$root = $prefix ? self::getReference($prefix) : self::$info;
		self::$info = array_merge($root, Yaml::read($file));
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
		if(!self::$file)
			self::setConfigFile(app_dir . '/config.yaml');
		
		self::insist(self::$file);
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

}
