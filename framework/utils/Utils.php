<?php
function RequestIsPost()
{
	return $_SERVER['REQUEST_METHOD'] == 'POST' ? 1 : 0;
}

function echo_r($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function Redirect($url)
{
	header("location: $url");
}

if(version_compare(PHP_VERSION, '5.0', '<'))
{
	include_once(dirname(__FILE__) . '/Utils4.php');
}
else
{
	include_once(dirname(__FILE__) . '/Utils5.php');
}