<?php
function smarty_function_status($params, &$smarty)
{
	$name = $params['name'];
	
	$id = 'gui_status_' . $name;
	
	return '<span id="' . $id . '"></span>';
}
