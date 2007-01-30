<?php
//	now we load the default config for zoop
include(zoop_dir . '/config.php');

//	we want to load this before we do anything else so that everything else is easier to debug
include(zoop_dir . '/app/Error.php');


class Zoop
{
	function Zoop()
	{
		$this->loadLib('utils');
		DefineOnce('app_tmp_dir', app_dir . '/tmp');
	}
	
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
				include(zoop_dir . '/app/Globals.php');
				include(zoop_dir . '/app/Application.php');
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
				$this->loadLib('gui');
				include(zoop_dir . '/zone/Zone.php');
				include(zoop_dir . '/zone/ZoneApplication.php');
				include(zoop_dir . '/zone/GuiZone.php');
				break;
			default:	
				trigger_error('unknown module: ' . $name);
		}
	}
}

$zoop = new Zoop();