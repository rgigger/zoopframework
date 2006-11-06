<?php
//	we rely on this all over the place.  there is no point in trying to do
//	anything until we've got this assigned
if(!defined('app_dir'))
	trigger_error('Please define the constant "app_dir" before continuing.  It should point to the base directory of your application code');

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
		$app->run();
	}
}

global $app;
$app = new ZoneApplication();
$app->loadZone('default');