<?php
//	todo:	I think that this should extend Application.
//	Application calls session_start, we need to figure out if that should be handled there or not before we extend it
class ZoneApplication
{
	static function loadZone($name)
	{
		$name = ucfirst($name);
		include(app_dir . "/zones/Zone$name.php");
	}
	
	function run()
	{
		//	get the path parts
		$pathParts = explode('/', virtual_path);
		array_shift($pathParts);
		
		//	special case: see if we need to dish out a static page from a zoop module
		if(isset($pathParts[0]) && $pathParts[0] == 'modpub')
		{
			$this->handleStaticFile($pathParts);
		}
		else
		{
			//	handle the request
			$zoneDefault = new ZoneDefault();
			$zoneDefault->init();
			$zoneDefault->handleRequest($pathParts);
		}
	}
	
	static function handleRequest()
	{
		global $app;
		self::loadZone('default');
		$app->run();
	}
	
	function handleStaticFile($pathParts)
	{
		array_shift($pathParts);
		$modName = str_replace('..', '', array_shift($pathParts));
		$staticPath = str_replace('..', '', implode('/', $pathParts));
		$filePath = zoop_dir . "/$modName/public/" . $staticPath;
		EchoStaticFile($filePath);
	}
}

global $app;
$app = new ZoneApplication();