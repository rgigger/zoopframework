<?php
class Config
{
	private static $info;
	private static $file;
	
	static function setConfigFile($file)
	{
		self::$file = $file;
		// print_r(self::$file . "\n");
	}
	
	static function load()
	{
		if(!self::$file)
			self::setConfigFile(app_dir . '/config.yaml');
		// echo_r(self::$file . "\n");
		self::$info = Yaml::read(self::$file);
		// echo_r(self::$info);
		// trigger_error('stuff');
	}
	
	static function get($path)
	{
		$parts = explode('.', $path);
		$cur = self::$info;
		// print_r($cur);
		foreach($parts as $thisPart)
		{
			if(isset($cur[$thisPart]))
				$cur = $cur[$thisPart];
			else
				return false;
		}
			
		
		return $cur;
	}
}