<?php
/**
 * class Error Handler
 *
 * @package app
 * @author Rick Gigger
 **/

error_reporting(E_ALL);

class ErrorHandler
{
	//	static
	function handleError($errno, $errstr, $errfile, $errline, $context)
	{
		switch(app_status)
		{
			case 'dev':
				ErrorHandler::handleDevError($errno, $errstr, $errfile, $errline, $context);
				break;
			
			case 'test':
				trigger_error('status not handled:' . app_status);
				break;
			
			case 'live':
				trigger_error('status not handled:' . app_status);
				break;
			
			default:
				trigger_error('status not handled:' . app_status);
				break;
		}
	}
	
	function handleDevError($errno, $errstr, $errfile, $errline, $context)
	{
		echo '<pre>';
		debug_print_backtrace();
		echo '</pre>';
	}
}

if(version_compare(PHP_VERSION, '5.0', '<'))
{
	set_error_handler(array("ErrorHandler", "handleError"), E_ALL);
}
else
{
	include_once(dirname(__FILE__) . '/ZoopException.php');
}