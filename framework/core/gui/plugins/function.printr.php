<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     printr
 * Name:     reverse
 * Purpose:  print out a variable with print_r
 * -------------------------------------------------------------
 */

function smarty_function_printr($params, &$smarty)
{
    extract($params);
	
    if(!isset($var))
    {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }
	
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

/* vim: set expandtab: */

?>
