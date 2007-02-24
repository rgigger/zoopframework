<?php
function RequestIsPost()
{
	return $_SERVER['REQUEST_METHOD'] == 'POST' ? 1 : 0;
}

function echo_r($var)
{
	//EchoBacktrace();
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function Redirect($url)
{
	header("location: $url");
}

function EchoBacktrace($value='')
{
	echo '<pre>';
	debug_print_backtrace();
	echo '</pre>';
}

function EchoStaticFile($filename)
{
	$fp = fopen($filename, 'rb');
	
	//	send the headers
	//header("Content-Type: image/png");	//	figure out what should really be done here
	header("Content-Length: " . filesize($filename));	//	also we want to be able to properly set the cache headers here
	
	fpassthru($fp);
}

if(version_compare(PHP_VERSION, '5.0', '<'))
{
	include_once(dirname(__FILE__) . '/Utils4.php');
}
else
{
	include_once(dirname(__FILE__) . '/Utils5.php');
}