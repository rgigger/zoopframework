<?php
class CliErrorHandler
{
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
		array_shift($backtrace);
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
//			case E_RECOVERABLE_ERROR:
				$line .= 'Error type not yet handled: ' . $errno . '';
				break;
			case E_WARNING:
				$line .= 'Warning:';
				break;
			case E_NOTICE:
				$line .= 'Notice:';
				break;
			case E_USER_NOTICE:
				$line .= 'User Notice:';
				break;
			case E_USER_ERROR:
				$line .= 'User Error:';
				break;
			case E_USER_WARNING:
				$line .= 'User Warning:';
				break;
			case 0:
				$line .= 'Exception:';
				break;
			default:
				$line .= 'Undefined error type: ' . $errno . '';
			break;
		}
		
		$line .= ' ' . $errstr . "\n";
		$line .= ' in file ' . $errfile;
		$line .= ' ( on line  ' . $errline . ')';
		
		return $line;
	}
	
	function exceptionHandler($exception)
	{
		print_r($exception);
		self::handleError(0, $exception->getMessage(), $exception->getFile(), $exception->getLine(), NULL, $exception->getTrace());
	}
}
