<?php
class Config
{
	static $info;
	
	static function load()
	{
		self::$info = Yaml::read(app_dir . '/config.yaml');
		// echo_r(self::$info);
	}
	
	static function get($path)
	{
		$parts = explode('.', $path);
		$cur = self::$info;
		foreach($parts as $thisPart)
			$cur = $cur[$thisPart];
		
		return $cur;
	}
}