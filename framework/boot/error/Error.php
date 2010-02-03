<?php
/**
 * class Error Handler
 *
 * @package app
 * @author Rick Gigger
 **/

error_reporting(E_ALL);

if(php_sapi_name() == "cli")
{
	include(zoop_dir . '/boot/error/CliErrorHandler.php');
	//set_error_handler(array("CliErrorHandler", "handleError"), E_ALL);
	set_error_handler(array("CliErrorHandler", "throwException"), E_ALL);
	set_exception_handler(array("CliErrorHandler", "exceptionHandler"));
}
else
{
	include(zoop_dir . '/boot/error/WebErrorHandler.php');
	// set_error_handler(array("WebErrorHandler", "throwException"), E_ALL);
	set_error_handler(array("WebErrorHandler", "handleError"), E_ALL);
	set_exception_handler(array("WebErrorHandler", "exceptionHandler"));
}
