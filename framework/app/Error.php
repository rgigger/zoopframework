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
		$errorLine = self::formatErrorLineHtml($errno, $errstr, $errfile, $errline, $context);
		echo '<div>' . $errorLine . '</div>';
		FormatBacktraceHtml(debug_backtrace());
	}
	
	function formatErrorLineHtml($errno, $errstr, $errfile, $errline, $context)
	{
		$line = '';
		switch ($errno)
		{
			case E_ERROR:
			case E_PARSE:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
				die('this should never happen');
				break;
			case E_USER_ERROR:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			case E_USER_ERROR:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			case E_STRICT:
			case E_RECOVERABLE_ERROR:
				die('not yet handled');
				break;
			case E_WARNING:
				$line .= '<strong>Warning:</strong>';
				break;
			case E_NOTICE:
				$line .= '<strong>Notice:</strong>';
				break;
		   case E_USER_ERROR:
		       break;
		   default:
				die("undefined error type");
				break;
		}
		
		$line .= ' "' . $errstr . '"';
		$line .= ' in file ' . $errfile;
		$line .= ' ( on line  ' . $errline . ')';
		
		return $line;
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