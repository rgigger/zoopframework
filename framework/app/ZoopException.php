<?php
class ZoopException extends Exception
{ 
	public static function errorHandlerCallback($code, $string, $file, $line, $context)
	{
		$e = new self($string, $code);
		$e->line = $line;
		$e->file = $file;
		throw $e;
	}
}

set_error_handler(array("ErrorHandler", "handleError"), E_ALL);