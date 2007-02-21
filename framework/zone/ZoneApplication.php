<?php
//	todo:	I think that this should extend Application.
class ZoneApplication
{
	function loadZone($name)
	{
		$name = ucfirst($name);
		include(app_dir . "/zones/Zone$name.php");
	}
	
	function run()
	{
		//	get the path parts
		$pathParts = explode('/', virtual_path);
		array_shift($pathParts);
		
		//	handle the request
		$zoneDefault = new ZoneDefault();
		$zoneDefault->handleRequest($pathParts);
	}
	
	//static
	function handleRequest()
	{
		global $app;
		$app->loadZone('default');
		$app->run();
	}
}

global $app;
$app = new ZoneApplication();