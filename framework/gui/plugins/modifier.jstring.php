<?php
function smarty_modifier_jstring($string)
{
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '\n', $string);
	return $string;
}