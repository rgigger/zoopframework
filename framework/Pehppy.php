<?php
//	we rely on this all over the place.  there is no point in trying to do
//	anything until we've got this assigned
if(!defined('pehppy_dir'))
	trigger_error('Please define the constant "pehppy_dir" before continuing.  It should point to the base directory of the pehppy framwork');

class Pehppy
{
	function loadLibrary($name)
	{
		switch($name)
		{
			case 'app':
				$this->loadLib('utils');
				include(pehppy_dir . '/app/Globals.php');
				include(pehppy_dir . '/app/Application.php');
				break;
			case 'utils':
				include(pehppy_dir . '/utils/Utils.php');
				break;
			case 'zone':
				$this->loadLib('app');
				include(pehppy_dir . '/zone/Zone.php');
				include(pehppy_dir . '/zone/ZoneApplication.php');
				break;			
		}
	}
	
	//	static
	function loadLib($name)
	{
		global $pehppy;
		$pehppy->loadLibrary($name);
	}
}

$pehppy = new Pehppy();