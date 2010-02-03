<?php
abstract class Zone extends Object
{
	private	$requestInfo;
	private $params;
	
	function init($requestInfo = NULL, $params = array())
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
	
	public function initZone($p, $z) {}
	
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
		$originalPart = array_shift($pathParts);
		$thisPart = ucfirst($originalPart);
		
		$zoneName = "Zone$thisPart";
		$pageName = "page$thisPart";
		$postName = "post$thisPart";
		$defaultName = "pageDefault";
		$defaultPostName = "postDefault";
		
		//	first try to find a matching page
		if( RequestIsPost() && $this->_methodExists($postName) )
			$foundPage = $postName;
		else
		{
			if( $this->_methodExists($pageName) )
				$foundPage = $pageName;
		}
		
		//	next look for an existing zone that is not this class
		if( !isset($foundPage) )
		{
			
			if( $thisPart && class_exists($zoneName) )
			{
				$newZone = new $zoneName();
				$newZone->init($this->requestInfo, $this->params);

				//	grab the zone params from $pathParts
				foreach($newZone->getParamNames() as $thisParamName)
				{
					if(count($pathParts) == 0)
						trigger_error("$zoneName requires more paramaters than are available");
					$paramValue = array_shift($pathParts);
					$newZone->requestInfo[count($newZone->requestInfo) - 1]['params'][] = $thisParamName;
					$newZone->params[$thisParamName] = $paramValue;
				}
				
				$this->initZone($pathParts, $this->params);
				$newZone->handleRequest($pathParts);
			}
			else
			{
				//	now look for a default post page
				if( RequestIsPost() && $this->_methodExists($defaultPostName) )
				{
					$foundPage = $defaultPostName;
				}
				else
				{
					//	finally look for a defaultpage
					if( $this->_methodExists($defaultName) )
					{
						$foundPage = $defaultName;
					}
					else
					{
						trigger_error("no valid page for $thisPart in " . get_class($this));
					}
				}
			}
		}
		
		//	if we found a page then run it
		if( isset($foundPage) )
		{
			$postfix = $foundPage == 'pageDefault' || $foundPage == 'postDefault' ? array(0 => 'default', 1 => $originalPart) : array(0 => $originalPart);
			$pageParams = array_merge($postfix, $pathParts);
			
			$this->initZone($pageParams, $this->params);
			
			if( $this->_methodExists('initPages') )
				$this->initPages($pageParams, $this->params);
			
			$this->$foundPage($pageParams, $this->params);
			
			if( RequestIsGet() && $this->_methodExists('closePages') )
				$this->closePages($pageParams, $this->params);
			
			if( RequestIsPost() && $this->_methodExists('closePosts') )
				$this->closePosts($pageParams, $this->params);
		}
	}
	
	
	//
	//	path manipulation functions
	//
	
	function setParam($name, $value)
	{
		$this->params[$name] = $value;
	}
	
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
			{
				foreach($thisZoneInfo['params'] as $paramName)
					$path .= '/' . $this->params[$paramName];
			}
			
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
	
	function getBackUrl($extra = NULL, $numLevels = 0)
	{
		$url = back_script_url;
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
