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