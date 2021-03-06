<?php
/**
 * app::config.php
 *
 * @author Rick Gigger
 * @version $Id$
 * @copyright __MyCompanyName__, 16 February, 2007
 * @package app
 **/

//////////////////////////////////////////////
//
//	What we really want here is to define the following contants:
//
//	1) script_url		the url up to index.php or whatever the script name is
//	2) virtual_path		the virtual path.  this comes directly after scriptname.php
//	3) virtual_url		the url up through index.php and also the virtual path
//
//////////////////////////////////////////////

if(php_sapi_name() != "cli")
{
	if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' )
		$protocol = 'https://';
	else
		$protocol = 'http://';

	$host = $_SERVER['HTTP_HOST'];

	$realPath = $_SERVER['SCRIPT_NAME'];
	
	$virtualPath = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	
	define('root_url', $protocol . $host);
	if(defined('script_url'))
	{
		define('back_script_url', root_url . $realPath);
		define('back_virtual_url', back_script_url . $virtualPath);
		define('back_pub_url', dirname(back_script_url) . '/public');
	}
	else
	{
		define('script_url', root_url . $realPath);
	}
	
	define('virtual_path', $virtualPath);
	define('virtual_url', script_url . virtual_path);
}

if(!defined('E_DEPRECATED'))
	define('E_DEPRECATED', 8192);

define('version_53', version_compare(PHP_VERSION, '5.3.0') >= 0 ? true : false);
	
