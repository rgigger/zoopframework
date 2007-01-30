<?php
//
//	sets all the default settings for config constants that are used across Zoop modules
//

//	some things we simple can't make defaults for.  This function is to help us let the user know that they need to define it themselves
function RequireDefined($name, $message = NULL)
{
	if(!defined($name))
	{
		if($message)
			trigger_error("Please define the constant '$name' before continuing. " . $message);
		else
			trigger_error("Please define the constant '$name' before continuing");
	}
}

//	for those things we can make sensible defaults for we use this function to set it if the user hasn't already
function DefineOnce($name, $value)
{
	if(!defined($name))
		define($name, $value);
}

DefineOnce('zoop_dir', dirname(__file__));
RequireDefined('app_dir', 'It should point to the base directory of your application.');
DefineOnce('app_status', 'dev');