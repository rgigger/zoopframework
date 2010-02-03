<?php
class WebErrorHandler
{
	static function throwException($errno, $errstr, $errfile, $errline, $context, $backtrace = NULL)
	{
		// maybe we should use this here: http://us3.php.net/manual/en/class.errorexception.php
		$e = new Exception($errstr, $errno);
		// echo_r($e);
		// die();
		// $e->setFile($errfile);
		// $e->setLine($errline);
		throw $e;
	}
	
	static function handleError($errno, $errstr, $errfile, $errline, $context, $backtrace = NULL)
	{
		if(!defined('app_status'))
			define('app_status', 'dev');
			
		switch(app_status)
		{
			case 'dev':
				self::handleDevError($errno, $errstr, $errfile, $errline, $context, $backtrace);
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
	
	function handleDevError($errno, $errstr, $errfile, $errline, $context, $backtrace)
	{
		$errorLine = self::formatErrorLineHtml($errno, $errstr, $errfile, $errline, $context, $backtrace);
		echo '<p>' . $errorLine . '</p>';
		$backtrace = $backtrace ? $backtrace : debug_backtrace();
		// array_shift($backtrace);
		FormatBacktraceHtml($backtrace);
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
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			case E_STRICT:
//			case E_RECOVERABLE_ERROR:
				$line .= '<strong>Error type not yet handled: ' . $errno . '</strong>';
				break;
			case E_WARNING:
				$line .= '<strong>Warning:</strong>';
				break;
			case E_NOTICE:
				$line .= '<strong>Notice:</strong>';
				break;
			case E_USER_NOTICE:
				$line .= '<strong>User Notice:</strong>';
				break;
			case E_DEPRECATED:
				$line .= '<strong>Depricated:</strong>';
				break;
			case E_USER_ERROR:
				$line .= '<strong>User Error:</strong>';
				break;
			case E_USER_WARNING:
				$line .= '<strong>User Warning:</strong>';
				break;
			case 0:
				$line .= '<strong>Exception:</strong>';
				break;
			default:
				$line .= '<strong>Undefined error type: ' . $errno . '</strong>';
			break;
		}
		
		$line .= ' "' . $errstr . '"';
		$line .= ' in file ' . $errfile;
		$line .= ' ( on line  ' . $errline . ')';
		
		return $line;
	}
	
	function exceptionHandler($exception)
	{
		self::handleError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), NULL, $exception->getTrace());
	}
}
