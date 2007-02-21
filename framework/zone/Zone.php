<?php
class Zone
{
	var	$requestInfo;
	var $params;
	
	function Zone($requestInfo = NULL, $params = array())
	{
		if(!$requestInfo)
		{
			$this->requestInfo = array();
			$this->requestInfo[] = array('zone' => '');
		}
		else
		{
			$this->requestInfo = $requestInfo;
			$this->requestInfo[] = array('zone' => strtolower(substr(get_class($this), 4)));
		}
		
		$this->params = array();
	}
	
	//
	//	methods to override
	//
	
	function getParamNames()
	{
		return array();
	}
	
	//	the main functionality of zone
	function handleRequest($pathParts)
	{
		$thisPart = ucfirst(array_shift($pathParts));
		
		$zoneName = "Zone$thisPart";
		$pageName = "page$thisPart";
		$postName = "post$thisPart";
		$defaultName = "pageDefault";
		
		//	first try to find a matching page
		if( RequestIsPost() && method_exists($this, $postName) )
			$foundPage = $postName;
		else
		{
			if( method_exists($this, $pageName) )
				$foundPage = $pageName;
		}
		
		//	next look for an existing zone that is not this class
		if( !isset($foundPage) )
		{
			if( $thisPart && class_exists($zoneName) )
			{
				$newZone = new $zoneName($this->requestInfo, $this->params);

				//	grab the zone params from $pathParts
				foreach($newZone->getParamNames() as $thisParamName)
				{
					assert(count($pathParts) > 0);
					$paramValue = array_shift($pathParts);
					$newZone->requestInfo[count($newZone->requestInfo) - 1]['params'][$thisParamName] = $paramValue;
					$newZone->params[$thisParamName] = $paramValue;
				}

				$newZone->handleRequest($pathParts);
			}
			else
			{
				//	finally look for a defaultpage
				if( method_exists($this, $defaultName) )
					$foundPage = $defaultName;
				else
					trigger_error("no valid page for $thisPart in " . get_class($this));
			}
		}
		
		//	if we found a page then run it
		if( isset($foundPage) )
		{
			$pageParams = array_merge(array(0 => $foundPage), $pathParts);
			
			if( method_exists($this, 'initPages') )
				$this->initPages($pageParams);
			
			$this->$foundPage($pageParams, $this->params);
			
			if( RequestIsPost() && method_exists($this, 'closePosts') )
				$this->closePosts($pageParams);
		}
		
	}
	
	
	//
	//	path manipulation functions
	//
	
	function getRequestPath($numLevels = 0)
	{
		$path = '';
		
		$maxLevel = count($this->requestInfo) - 1;
		if($numLevels < 0)
			$maxLevel += $numLevels;
		
		foreach($this->requestInfo as $index => $thisZoneInfo)
		{
				
			$path .= $thisZoneInfo['zone'];
			if(isset($thisZoneInfo['params']))
				foreach($thisZoneInfo['params'] as $paramValue)
					$path .= '/' . $paramValue;
			
			if($maxLevel == $index)
				break;
		}
		
		return $path;
	}
	
	function getUrl($extra = NULL, $numLevels = 0)
	{
		$url = script_url;
		if($this->getRequestPath($numLevels))
			$url .= '/' . $this->getRequestPath($numLevels);
		
		if($extra)
			$url .= '/' . $extra;
		
		return $url;
	}
	
	function redirect($extra)
	{
		$url = $this->getUrl($extra);
		Redirect($url);
	}
	
	function pushZone($zonePlusExtra)
	{
		$url = $this->getUrl($zonePlusExtra);
		Redirect($url);		
	}
	
	function popZone()
	{
		trigger_error('not done yet');
	}
	
	function switchZone($extra)
	{
		$url = $this->getUrl('', -1) . '/' . $extra;
		Redirect($url);
	}
}
