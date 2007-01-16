<?php
//	we rely on this all over the place.  there is no point in trying to do
//	anything until we've got this assigned
if(!defined('zoop_dir'))
	trigger_error('Please define the constant "zoop_dir" before continuing.  It should point to the base directory of the zoop framwork');

class Zoop
{
	function loadLib($name)
	{
		//	allow static calls to use the singleton object
		if(!isset($this))
		{
			global $zoop;
			$zoop->loadLib($name);
			return;
		}
		
		switch($name)
		{
			case 'app':
				$this->loadLib('utils');
				include(zoop_dir . '/app/Globals.php');
				include(zoop_dir . '/app/Application.php');
				include(zoop_dir . '/app/Error.php');
				break;
			case 'utils':
				include(zoop_dir . '/utils/Utils.php');
				break;
			case 'gui':
				include(zoop_dir . "/smarty/Smarty.class.php");
				include(zoop_dir . '/gui/gui.php');
				break;
			case 'zone':
				$this->loadLib('app');
				include(zoop_dir . '/zone/Zone.php');
				include(zoop_dir . '/zone/ZoneApplication.php');
				break;
			default:	
				trigger_error('unknown module: ' . $name);
		}
	}
}

$zoop = new Zoop();