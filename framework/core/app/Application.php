<?php
/**
 * class Application
 *
 * @package app
 * @author Rick Gigger
 **/
class Application
{
	function __construct()
	{
		//	should we always start the session here?  even if we aren't using the session module
		//	aren't we also calling it in the session module?
		//	oh, I don't think this even gets used cause nothing really extends it yet
		session_start();
	}
	
	function run()
	{
		trigger_error('run does nothing for Application');
	}
}
