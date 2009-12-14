<?php
class CliErrorHandler
{
	static function throwException($errno, $errstr, $errfile, $errline, $context, $backtrace = NULL)
	{
		// maybe we should use this here: http://us3.php.net/manual/en/class.errorexception.php
		$e = new Exception($errstr, $errno);
		// print_r($e);
		// die();
		// $e->setFile($errfile);
		// $e->setLine($errline);
		throw $e;
	}
	
	static function handleError($errno, $errstr, $errfile, $errline, $context, $backtrace = NULL)
	{
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
		$errorLine = self::formatErrorLine($errno, $errstr, $errfile, $errline, $context, $backtrace);
		echo $errorLine . "\n";
		$backtrace = $backtrace ? $backtrace : debug_backtrace();
		// array_shift($backtrace);
		// FormatBacktraceCli($backtrace);
		$backtraceView = new BacktraceViewCli($backtrace);
		$backtraceView->display();
	}
	
	function formatErrorLine($errno, $errstr, $errfile, $errline, $context)
	{
		$line = "\n";
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
			case E_RECOVERABLE_ERROR:
				$line .= 'Error type not yet handled: ' . $errno . '';
				break;
			case E_WARNING:
				$line .= 'Warning:';
				break;
			case E_NOTICE:
				$line .= 'Notice:';
				break;
			case E_DEPRECATED:
				$line .= 'Deprecated:';
				break;
			case E_USER_ERROR:
				$line .= 'User Error:';
				break;
			case E_USER_WARNING:
				$line .= 'User Warning:';
				break;
			case E_USER_NOTICE:
				$line .= 'User Notice:';
				break;
			case E_USER_DEPRECATED:
				$line .= 'User Deprecated:';
				break;
			case 0:
				$line .= 'Exception:';
				break;
			default:
				$line .= 'Undefined error type: ' . $errno . '';
			break;
		}
		
		$line .= ' ' . $errstr . "";
		$line .= ' in file ' . $errfile;
		$line .= ' ( on line  ' . $errline . ')';
		
		return $line;
	}
	
	function exceptionHandler($exception)
	{
//		print_r($exception->getCode());die();
		$backtrace = $exception->getTrace();
		$file = $exception->getFile();
		$line = $exception->getLine();
		if(isset($backtrace[0]['args']) && is_array($backtrace[0]['args']))
			$backtrace[0]['args'] = array();
		if(isset($backtrace[0]['file']))
			$file = $backtrace[0]['file'];
		if(isset($backtrace[0]['line']))
			$line = $backtrace[0]['line'];
//		print_r($backtrace[0]);
		self::handleError($exception->getCode(), $exception->getMessage(), $file, $line, NULL, $backtrace);
	}
}
