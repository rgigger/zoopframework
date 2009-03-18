<?php
function smarty_function_input($params, &$smarty)
{
	$type = $params['type'];
	$name = $params['name'];
	
	$value = isset($params['value']) ? $params['value'] : '';
	
	$id = $name;
	
	// handle the data bindings
	if(isset($params['data_object']))
	{
		if(!isset($params['data_field']))
			trigger_error("gui:input: if you specifiy a data object you must also specify a data field");
		if(!isset($smarty->zoop->form))
			trigger_error("gui:input: if you specifiy a data object you must first use the 'openform' tag");
		
		$object = $params['data_object'];
		$field = $params['data_field'];
		$smarty->zoop->form->addBinding(get_class($object), $object->getId(), $field);
		
		$value = $object->$field;
	}
	else
	{
		$value = isset($params['value']) ? $params['value'] : '';
	}
	
	
	// pass on anything else they put in
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
			return '<input id="' . $id . '" type="' . $type . '" name="' . $name . '" value="' . $value . '" ' . $extraFields . '>';
			break;
		case 'textarea':			
			$return = '<textarea id="' . $id . '" name="' . $name . '" ' . $extraFields . '>';
			$return .= $value;
			$return .= '</textarea>';
			return $return;
			break;
		default:
			trigger_error("unknown input type: $type");
			break;
	}
}
