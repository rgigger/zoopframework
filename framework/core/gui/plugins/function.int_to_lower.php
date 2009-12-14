<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Name:     int_to_lower
 * Purpose:  take a number and give the coorseponding letter 
 *				for the alphabet in lower case
 * -------------------------------------------------------------
 */

function smarty_function_int_to_lower($params, &$smarty)
{
    extract($params);
	
    if(!isset($int))
    {
        $smarty->trigger_error("assign: missing 'int' parameter");
        return;
    }
	
	$int = (int)$int;
	
	echo chr($int + 96);
}

/* vim: set expandtab: */

?>
