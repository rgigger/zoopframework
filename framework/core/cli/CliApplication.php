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
				
		//	parse the flags
		$params = array();
		$inShortFlag = 0;
		$shortFlags = array();
		foreach($argv as $thisArg)
		{
			//	check for long flags
			if($thisArg[0] == '-' && $thisArg[1] == '-')
			{
			}
			//	check for short flags
			else if($thisArg[0] == '-')
			{
				$inShortFlag = 1;
				$shortFlagName = substr($thisArg, 1);
			}
			//	it's a paramater
			else
			{
				if($inShortFlag)
				{
					$inShortFlag = 0;
					$shortFlags[$shortFlagName] = $thisArg;
				}
				else
				{
					$params[] = $thisArg;
				}
			}
		}
		
		if(!isset($params[1]))
		{
			echo "usages:\n";
			echo "zap apply migrations:\n";
			die();
		}
		
		$zoneName = 'Zone' . $params[1];
		$pageName = 'sub' . $params[2];
		$zone = new $zoneName();
		$zone->$pageName($params, $shortFlags);
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