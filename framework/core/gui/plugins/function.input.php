<?php
function smarty_function_input($params, &$smarty)
{
	$type = isset($params['type']) ? $params['type'] : 'text';
	$valueAtt = isset($params['value']) ? $params['value'] : '';
	
	// handle the data bindings
	if(isset($params['data_object']))
	{
		if(!isset($params['data_field']))
			trigger_error("gui:input: if you specifiy a data object you must also specify a data field");
		
		$object = $params['data_object'];
		$field = $params['data_field'];
		if(!isset($params['append']) || $params['append'] == false)
		{
			if(!isset($smarty->zoop->form))
				trigger_error("gui:input: if you specifiy a data object you must first use the 'init_form' tag");
			$name = $smarty->zoop->form->addBinding(get_class($object), $object->getId(), $field);
		}
		else
		{
			$binding = new FormBinding(get_class($object), $object->getId(), $field);
			Form::appendBindings(array($binding));
			$name = $binding->getName();
		}
		if(isset($params['default']) && ($object->$field === '' || $object->$field === NULL))
			$value = $params['default'];
		else
			$value = $object->$field;
		
		$namePart = ' name="' . $name . '"';
		if($type == 'radio')
			$valuePart = ' value="' . $valueAtt . '"';
		else
			$valuePart = ' value="' . $value . '"';
	}
	else
	{
		$value = $valueAtt;
		$namePart = isset($params['name']) ? ' name="' . $params['name'] . '"' : '';
		$valuePart = isset($params['value']) ? ' value="' . $params['value'] . '"' : '';
	}
	
	
	// pass on anything else they put in
	$extraFields = '';
	foreach($params as $paramName => $paramValue)
	{
		if(in_array($paramName, array('type', 'name', 'type', 'value', 'default', 'data_object', 'data_field', 'append')))
			continue;
		
		$extraFields .= ' ' . $paramName . '="' . $paramValue . '"';
	}
	
	switch($type)
	{
		case 'text':
		case 'password':
		case 'checkbox':
		case 'submit':
			return '<input type="' . $type . '"' . " $namePart $valuePart $extraFields>";
			break;
		case 'radio':
			// var_dump($value);
			// var_dump($valueAtt);
			$checked = $valueAtt == $value ? ' checked' : '';
			return '<input type="' . $type . '"' . " $namePart $valuePart $extraFields $checked>";
			break;
		case 'textarea':			
			$return = '<textarea name="' . $name . '" ' . $extraFields . '>';
			$return .= $value;
			$return .= '</textarea>';
			return $return;
			break;
		default:
			trigger_error("unknown input type: $type");
			break;
	}
}


