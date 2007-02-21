<?php
function smarty_function_input($params, &$smarty)
{
	$type = $params['type'];
	$name = $params['name'];
	
	$value = isset($params['value']) ? $params['value'] : '';
	
	$id = $name;
	
	//	pass on anything else the put in
	$extraFields = '';
	foreach($params as $paramName => $paramValue)
	{
		if(in_array($paramName, array('type', 'name', 'type', 'value')))
			continue;
		
		$extraFields .= ' ' . $paramName . '="' . $paramValue . '"';
	}
	
	switch($type)
	{
		case 'text':
		case 'password':
		case 'radio':
			return '<input id="' . $id . '" type="' . $type . '" name="' . $name . '" value="' . $value . '">';
			break;
		case 'textarea':			
			$return = '<textarea id="' . $id . '" name="' . $name . '" ' . $extraFields. '>';
			$return .= $value;
			$return .= '</textarea>';
			return $return;
			break;
		default:
			trigger_error("unknown input type: $type");
			break;
	}
}
