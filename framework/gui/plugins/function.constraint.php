<?php
function smarty_function_constraint($params, &$smarty)
{
	$type = $params['type'];
	$name = $params['name'];
	$value = $params['value'];
	$inline = $params['inline'];
	
	$types = array('minlen' => 'minlen', 'sameas' => 'sameas');
	$type = $types[$type];
	
	return '<constraint type="' . $type . '" name="' . $name . '" value="' . $value . '" inline="' . $inline . '" />';
}
