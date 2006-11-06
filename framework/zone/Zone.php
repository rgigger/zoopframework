<?php
/*
$gUrlVars = array();
$gPathParts = array();
$gZoneUrls = array();
$gGuiVars = array();
*/

class Zone
{
	/*
	var $error = "";		// The last error message recorded.
	var $errornum = 0;

	var $zonename = "";
	
	var $zonetype = "";		// this will always contain the name of this zone

	var $urlvar = array();
	var $urlzone = array();

	var $subZone = false;	// true if this instance was created from an URLVAR zone

	var $parent = null;		// Reference to the parent of this zone

	var $_zone = array();	// array of subclasses for this zone

	//	NOTHING IS ENFORCING THESE.  THIS IS VERY BAD.  THESE SHOULD BOTH BE ENFORCED
	//		AND EMPTY ARRAY SHOULD BLOCK EVERYTHING.  WE SHOULD DENY EVERYTHING AND
	//		FORCE YOU TO EXPLICITLY ALLOW WHAT SHOULD BE ALLOWED
	var $allowed_children = array();	// These are the zone names valid in this zone
	var $allowed_parents = array();		// These are the zones this zone can be a child of

	var $urlVarNames = array();

	var $returnPaths = array();

	var $origins = array();

	var $url = "";
	*/
	
	//////////////////////////////////////////////////////////////
	//  These are settings that will be overridden by subclass  //
	//////////////////////////////////////////////////////////////

	/**
	 *
	 * @access public
	 *
	 * If $wildcards == true then all path info
	 * past this class will be sent to pageDefault,
	 * or postDefault if the form was posted.
	 *
	 * http://localhost/index.php/class/these/are/parameters
	 *
	 * if $wildcards == true then the path /these/are/parameters
	 * will be sent to (page/post)Default w/out looking for a
	 * pagethese function.
	 *
	 **/
//	var $wildcards = false;

	/**
	 *
	 * @access public
	 *
	 * if $urlvars == <num of levels> then the fw will look for variables
	 * in the path immediately after the name of this class up to <num> levels
	 * and snag those variables and store them in the $this->urlvar array.
	 *
	 * http://localhost/index.php/user/12/add
	 *
	 * goes to pageadd() in class user, but stores a ["12"] in the
	 * $this->urlvar array.
	 *
	 * $urlzones is the same except it stores individual instances.
	 * The name is stored in $this->zonename.  The reason for individual
	 * instances is that each class instance exists in sessions.
	 * So $this->Var1 in /user/12 and $this->Var1 in /user/3
	 * are different.
	 *
	 **/
//	var $urlvars = false;
//	var $urlzones = false;

	/*
	* Set $this->Alias["alias"] = "Aliastarget"
	*/
//	var $Alias = array();
	
	function Zone()
	{
	}

	function handleRequest($pathParts)
	{
		$thisPart = array_shift($pathParts);
		$zoneName = "zone_$thisPart";
		$pageName = "page$thisPart";
		$postName = "post$thisPart";
		$defaultName = "pageDefault";
		
		//	first check for an existing zone
		if( class_exists($zoneName) )
		{
			$newZone = new $zoneName();
			$newZone->handleRequest($pathParts);
		}
		else
		{
			if( RequestIsPost() && method_exists($this, $postName) )
				$foundPage = $postName;
			else
			{
				if( method_exists($this, $pageName) )
					$foundPage = $pageName;
				else if( method_exists($this, $defaultName) )
					$foundPage = $defaultName;
			}
			
			if( isset($foundPage) )
			{
				$pageParams = array_merge(array(0 => $foundPage), $pathParts);
				
				if( method_exists($this, 'initPages') )
					$this->initPages($pageParams);
				
				$this->$foundPage($pageParams);
				
				if( RequestIsPost() && method_exists($this, 'closePosts') )
					$this->closePosts($pageParams);
			}
			else
				trigger_error("no valid page for $thisPart in " . get_class($this));			
		}	
	}

/*
	function handleRequest( $inPath )
	{
		global $gAlias;
		global $gUrlVars;
		global $gPathParts;
		global $gZoneUrls;

		$this->zonename = array_shift($inPath);

		$GLOBALS['current_zone'] = $this->zonename;

		if (isset($gUrlVars['userType']))
			$GLOBALS['current_usertype'] = $gUrlVars['userType'];

		$gPathParts[] = $this->zonename;


		if (!$this->zonename)
		{
			$this->zonename = "@ROOT";
		}

		if (!$this->zonetype || !$this->subZone)
		{
			$this->zonetype = ($this->zonename != "@ROOT") ? $this->zonename : "Default";
		}

		if ($this->subZone)
		{
			array_push($this->urlzone, $this->zonename);
		}
		else
		{
			$this->urlzone = array();
			$this->urlzone[] = $this->zonename;
		}

	//////////////////////////////////////////////////////////////
	//  $this->urlzones handling.  Allows variables in query    //
	//  path to be seperate zones in session memory.            //
	//////////////////////////////////////////////////////////////
		if ( $this->urlzones != false && $this->urlzones > 0)
		{
			//	we should NOT be using this
			assert(false);

			$nextpath = $this->zonetype;

			$urlvar = $inPath[0];
			$zname = "zone_" . $nextpath;

			if (!isset($this->_zone["_URLZONE_"][$urlvar]) || !is_a($this->_zone["_URLZONE_"][$urlvar], $zname))
			{
				$this->_zone["_URLZONE_"][$urlvar] = new $zname;
				$this->_zone["_URLZONE_"][$urlvar]->parent =& $this->parent;
			}
			$this->_zone["_URLZONE_"][$urlvar]->urlvar = $this->urlvar;
			$this->_zone["_URLZONE_"][$urlvar]->urlzone = $this->urlzone;

			$this->_zone["_URLZONE_"][$urlvar]->urlvars = $this->urlvars;
			$this->_zone["_URLZONE_"][$urlvar]->urlzones = $this->urlzones - 1;
			$this->_zone["_URLZONE_"][$urlvar]->subZone = true;

			$this->_zone["_URLZONE_"][$urlvar]->zonetype = $this->zonetype;

			return $this->_zone["_URLZONE_"][$urlvar]->handleRequest($inPath);
		}

		//////////////////////////////////////////////////////////////
		//  $this->urlvars handling.  Allows variables in query     //
		//  path.  These variables stored in $this->urlvar array	//
		//////////////////////////////////////////////////////////////
		elseif ( $this->urlvars != false && $this->urlvars > 0)
		{
			//	we should NOT be using this
			assert(false);

			$this->urlvar = array();

			for ($i = 0; $i < $this->urlvars; $i++)
			{
				$uvar = array_shift($inPath);
				$gPathParts[] = $uvar;
				array_push($this->urlvar, array_shift($inPath));
			}
		}

		// when wildcards are enabled, always execute the default function.
		if ($this->wildcards)
		{
			array_unshift($inPath, 'default');
			return( $this->_checkFuncs("Default", $inPath) );
		}

		if($urlVarNames = $this->getZoneParamNames())
		{
			foreach ($urlVarNames as $index => $varName)
			{
				if( count($inPath) > 0 )
				{
					$varValue = array_shift( $inPath );
					if(defined("strip_url_vars") && strip_url_vars)
						assert(strtok($varValue, " \t\r\n\0\x0B") === $varValue);
					$gUrlVars[ $varName ] = $varValue;
					$gPathParts[] = $varValue;
				}
				else
				{
					break;
				}
			}
		}

		$tmp = $gPathParts;
		global $sequenceStack;

		if(isset($sequenceStack))
		{
			$temp = array_shift($tmp);
			array_unshift($tmp, implode(":", $sequenceStack));
			array_unshift($tmp, $temp);
		}
		$this->url = implode("/", $tmp);
		$this->initZone($inPath);

		array_unshift($gZoneUrls, $this->url);

		//	if there is something at all in the path

		if ( isset($inPath[0]) && $inPath[0] !== '' )
		{
			$path2 = $inPath[0];
		}
		else
		{
			$path2 = "Default";
		}

		if ( isset( $this->Alias[$path2]) && !(($retval = $this->_checkFuncs($this->Alias[$path2], $inPath)) === false) )
		{
			return $retval;
		}

		elseif ( !($retval = $this->_checkFuncs($path2, $inPath) === false) )
		{
			return $retval;
		}

		elseif ( isset( $gAlias[$path2]) && !(($retval = $this->_checkFuncs($gAlias[$path2], $inPath )) === false) )
		{
			return $retval;
		}

		else
		{
			// Try to execute the correct funtion
			if ( isset( $this->Alias[$path2]) && !(($retval = $this->_checkZone($this->Alias[$path2], $inPath)) === false) )
			{
				return( $retval );
			}

			else if ( !(($retval = $this->_checkZone($path2, $inPath)) === false) )
			{
				return( $retval );
			}

			else if ( isset( $gAlias[$path2]) && !(($retval = $this->_checkZone($gAlias[$path2], $inPath)) === false) )
			{
				return( $retval );
			}

			else
			{
				$this->error = "The name found in the path ($path2) was not a valid function or class.  Perhaps this class should have wildcards enabled?  Executing pageDefault function.";

				if (REQUEST_TYPE == "XMLRPC")
				{
					$GLOBALS["pehppyXMLRPCServer"]->returnFault(1, "Invalid XMLRPC function, $path2");
					return true;
				}

				array_unshift($inPath, 'default');
				return( $this->_checkFuncs("Default", $inPath) );
			}
		}
	}

	function _checkFuncs($curPath, $inPath)
	{
		if (REQUEST_TYPE == "XMLRPC")
		{
			return $this->_xmlrpcDispatch($curPath, $inPath);
		}
		else
		{
			if ( $_SERVER["REQUEST_METHOD"] == "POST" )
			{
				if (method_exists($this, "post" . $curPath))
				{
					$funcName = "post" . $curPath;
					$GLOBALS['current_function'] = $funcName;						
					$this->initPages($inPath);
					$tmp = $this->$funcName($inPath);
					$this->closePages($inPath);
					$this->closePosts($inPath);
					return $tmp;
				}
				else if(method_exists($this, "page" . $curPath))
				{
					$this->initPages($inPath);
					$this->closePages($inPath);
					$this->closePosts($inPath);
					redirect($_SERVER["URL"]);
				}
			}
			
			if (method_exists($this, "page" . $curPath))
			{
				$funcName = "page" . $curPath;
				$GLOBALS['current_function'] = $funcName;
				$this->initPages($inPath);
				$tmp = $this->$funcName($inPath);
				$this->closePages($inPath);
				return( $tmp );
			}

			return false;
		}
	}

	function _checkZone($zoneName, $inPath)
	{
		$var2 = "zone_" . $zoneName;


		//	if the class exists and this zone has no allowed children or this is one of the allowed children
		//		not the easiest thing to follow but I guess it needs to be done this way since the object
		//		hasn't been instantiated yet.  Maybe a static method would be better here for getting the allowed
		//		parents

		if ( class_exists($var2) && (count($this->allowed_children) < 1 || in_array($zoneName, $this->allowed_children) ))
		{
			//	create the new zone object if it does not exist

			if ( !isset( $this->_zone[$zoneName] ) )
			{
				$this->_zone[$zoneName] = new $var2();
				$this->_zone[$zoneName]->parent =& $this;
			}


			// check to see if this is an allowed parent for the class we just created

			if (count($this->_zone[$zoneName]->allowed_parents) > 0 && !in_array($this->zonetype, $this->_zone[$zoneName]->allowed_parents))
			{
				return false;
			}
			$retval = $this->_zone[$zoneName]->handleRequest($inPath);				
			if ($retval === false)
			{
				$retval = "";
			}

			$this->closeZone($inPath);

			return( $retval );
		}
		else
		{
			return false;
		}
	}

	function _xmlrpcDispatch($curPath, $inPath)
	{
		//echo_r($this);
		//die($curPath);
		if (method_exists($this, "xmlrpc" . $curPath))
		{
			$funcname = "xmlrpc" . $curPath;

			$params = $GLOBALS["pehppyXMLRPCServer"]->getRequestVars();

			$methodname = $GLOBALS["pehppyXMLRPCServer"]->methodname;

			ob_start();
			$retval = $this->$funcname($inPath, $params, $methodname);
			$debug = ob_get_contents();
			ob_end_clean();

			if (is_object($retval) && isset($retval->code))
			{
				$GLOBALS["pehppyXMLRPCServer"]->returnFault($retval->code, $retval->string);
			}
			elseif (is_array($retval))
			{
				$GLOBALS["pehppyXMLRPCServer"]->returnValues($retval);
			}
			elseif ($retval === true || $retval === false)
			{
				$val["value"] = $retval ? 1 : 0;
				$val["type"] = "boolean";
				$GLOBALS["pehppyXMLRPCServer"]->returnValues($val);
			}
			elseif (is_numeric($retval) || strlen($retval) > 0)
			{
				$GLOBALS["pehppyXMLRPCServer"]->returnValues($retval);
			}
			else
			{
				$GLOBALS["pehppyXMLRPCServer"]->returnFault(2, "Function did not return a valid array");
			}

			return true;
		}
		else
		{

			//$GLOBALS["pehppyXMLRPCServer"]->returnFault(1, "Invalid XMLRPC function");
			return false;
		}
	}

	function pageDefault($inPath)
	{
		// This function should be overridden to be the default function (in case there is either A: no path info, or B: no matching function or class for the path";

		trigger_error("You haven't overridden pagedefault!");
	}

	function initZone($inPath)
	{
		// This function should be overridden in each zone if you would like code to execute each time it hits the zone's handleRequest function.
	}

	function closeZone($inPath)
	{
		// This function should be overridden in each zone if you would like code to execute each time before it leaves the zone's handleRequest function.
	}

	function initPages($inPath)
	{
		// This function is run before any page or post function in the zone.
	}

	function closePages($inPath)
	{
		// This function is run after any page or post function in the zone.
	}

	function closePosts($inPath)
	{
		// This function is run after any page or post function in the zone.
	}
	
	function getZoneParamNames()
	{
		return $this->urlVarNames;
	}

	function setZoneParamNames($inUrlVarNames)
	{
		return $this->urlVarNames = $inUrlVarNames;
	}

	function getZoneParams()
	{
		global $gUrlVars;
		return $gUrlVars;
	}

	function getZoneParam($name)
	{
		global $gUrlVars;
		return $gUrlVars[$name];
	}

	//should redirect us in the zone to the page $inUrl
	function zoneRedirect( $inUrl = '')
	{
		BaseRedirect( $this->url . "/" . $inUrl);
	}
	
	//	we should also have some functions here to get back the info organized by:
	//		zone -> zone params -> page -> page Params
	//	we could then display it to help with debugging
	//	we would need to build up this data structure as we build everything in handle request
	
	function getPath($depth = 0)
	{
		if($depth == 0)
		{
			return $this->url;
		}
		else
			return $this->parent->getPath($depth-1);
	}
	
	function getUrl($depth = 0)
	{
		
		return SCRIPT_URL . $this->getPath($depth);
	}
	
	//	Here we have some methods to help out with creating template (smarty, gui) objects 
	//		and also to help out with the management of assigning data to those objects
	//		whether or not this belongs in zone is questionble.  they may be more analagous
	//		to the sql_* functions that simply manage one default instance of a class

	function guiAssign($name, $value)
	{
		GuiAssign($name, $value);
	}
	
	function guiDisplay($templateName, $guiType = NULL)
	{
		global $gGuiVars;
		
		$gui = &$this->guiChoose($guiType);
		
		foreach($gGuiVars as $name => $value)
		{
			$gui->assign($name, $value);
		}
		
		$gui->assign('scriptUrl', $this->guiGetScriptUrl());
		
		$dirName = $this->guiGetTemplateBaseDir();
		
		$gui->display($dirName . '/'. $templateName);
	}
	
	function guiGetScriptUrl()
	{
		return SCRIPT_URL;
	}
	
	//	get the directory where the templates are stored for this zone
	//		override this to get the templates from a different directory
	function guiGetTemplateBaseDir()
	{
		$className = get_class($this);
		$parts = explode('_', $className);
		array_shift($parts);
		return implode('_', $parts);
	}
	
	function &guiChoose($guiType)
	{
		assert($guiType === NULL);
		$tmp = &new gui();
		return $tmp;
	}
	*/
}

/*
function GuiAssign($name, $value)
{
	global $gGuiVars;
	
	$gGuiVars[$name] = $value;
}

function GetGuiVars()
{
	global $gGuiVars;
	return $gGuiVars;
}
*/
?>