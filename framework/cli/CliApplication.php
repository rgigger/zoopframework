<?php
//	todo:	I think that this should extend Application.
class CliApplication
{
	/*
	function loadZone($name)
	{
		$name = ucfirst($name);
		include(app_dir . "/zones/Zone$name.php");
	}
	*/
	
	function run()
	{
		global $argv;
		
		//	we really need do this right and to set up a yaml module that abstracts away the underlying yaml engine
		include_once(zoop_dir . '/spyc/spyc.php');

		$array = Spyc::YAMLLoad(app_dir . '/params.yaml');
		
		//	parse the flags
		$switches = array();
		$params = array();
		$inSwitch = 0;
		foreach($argv as $thisArg)
		{
			//	check for a double switch
			if($thisArg[0] == '-' && $thisArg[1] == '-')
			{
			}
			//	check for a single switch
			else if($thisArg[0] == '-')
			{
				$inSwitch = 1;
			}
			//	it's a paramater
			else
			{
				if($inSwitch)
				{
					$inSwitch = 0;
				}
				else
				{
					$params[] = $thisArg;
				}
			}
		}
		
		$zoneName = 'Zone' . $params[1];
		$pageName = 'page' . $params[2];
		$zone = new $zoneName();
		$zone->$pageName($params, $switches);
	}
	
	/*
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
			$zoneDefault->handleRequest($pathParts);
		}
	}
	*/
	
	//static
	function handleRequest()
	{
		global $app;
		//$app->loadZone('default');
		$app->run();
	}
	
	/*
	function handleStaticFile($pathParts)
	{
		array_shift($pathParts);
		$modName = str_replace('..', '', array_shift($pathParts));
		$staticPath = str_replace('..', '', implode('/', $pathParts));
		$filePath = zoop_dir . "/$modName/public/" . $staticPath;
		EchoStaticFile($filePath);
	}
	*/
}

global $app;
$app = new CliApplication();