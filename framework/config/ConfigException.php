<?php
class ConfigException extends Exception
{
	function __construct($module, $missing, $extra = '')
	{
		//
		//	make the message here
		//
		
		$message = "Module $module is missing the following paramaters: " . implode(', ', $missing) . ' ' . $extra;
		
		parent::__construct($message);
	}
}
