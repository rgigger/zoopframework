<?php
//////////////////////////////////////////////
//
//	What we really want here is to define the following contants:
//
//	1) script_url		the url up to index.php or whatever the script name is
//	2) virtual_path		the virtual path.  this comes directly after scriptname.php
//	3) virtual_url		the url up through index.php and also the virtual path
//
//////////////////////////////////////////////

if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' )
	$protocol = 'https://';
else
	$protocol = 'http://';

$host = $_SERVER['SERVER_NAME'];

$realPath = $_SERVER['SCRIPT_NAME'];

$virtualPath = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

define('script_url', $protocol . $host . $realPath);
define('virtual_path', $virtualPath);
define('virtual_url', script_url . $virtualPath);