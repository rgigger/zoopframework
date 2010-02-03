<?php
class ZoneFilter extends AppZone
{
	public function pageDefault()
	{
		trigger_error('there is no page default');
	}
	
	public function pageEdit($p, $z)
	{
		// +1. get the config from the yaml file
		// +2. Update the real table with the row from the template table
		// +3. populate the list of fields
		// +4. populate the initial list of operators for the default field
		// +5. update the operators menu when they change the field type
		// +6. update the operand paramater fields to match the operator and field
		// -7. make the .. button work
		// -7. save the output somehow
		// -8. reload the output
		// -9. converit it to sql
		
		// echo_r(Config::get('app.filter'));
		
		$config = Config::get('app.filter');
		
		//	do any database lookups that need to be done to fill out the config data
		foreach($config['fields'] as $fieldName => $fieldInfo)
		{
			if($fieldInfo['type'] == 'discreet' && !isset($fieldInfo['list']))
				$config['fields'][$fieldName]['list'] = SqlFetchSimpleMap("SELECT id, :nameField:identifier FROM :tableName:identifier", 'id', $fieldInfo['display_field'],
					array('nameField' => $fieldInfo['display_field'], 'tableName' => $fieldInfo['references']));
		}
		
		$this->assign('config', $config);
	}
	
	public function postEdit($p, $z)
	{
		$data = json_decode($_POST['filterData'])->subs[0];
		echo_r($data);
		echo_r(sqlify($data));
		die();
	}
}

function sqlify($data)
{
	$parts = array();
	foreach($data->fields as $field)
		$parts[] = sqlifyField($field);
	foreach($data->subs as $sub)
		$parts[] = '(' . sqlify($sub) . ')';
	$boolOp = $data->anyall == 'any' ? ' OR ' : ' AND ';
	return implode($boolOp, $parts);
}

function sqlifyField($field)
{
	$config = Config::get('app.filter');
	$type = $config['fields'][$field->field]['type'];
	$operator = $field->operator;
	$widgit = $config['type_operator_map'][$type][$field->operator]['widgit'];
	
	echo_r(array($type, $operator, $widgit));
	
	$left = $field->field;
	
	if($operator == 'is')
	{
		$op = '=';
		$right = $field->operandValues->menu;
	}
	else if($operator == 'is_not')
	{
		$op = '<>';
		$right = $field->operandValues->menu;
	}
	else if($operator == 'in')
	{
		$op = 'IN';
		$right = '(' . implode(', ', $field->operandValues->multi) . ')';
	}
	else if($operator == 'not_in')
	{
		$op = 'NOT IN';
		$right = '(' . implode(', ', $field->operandValues->multi) . ')';
	}
	else if($operator == 'less_than')
	{
		$op = ' < ';
		$right = $field->operandValues->menu;
	}
	else if($operator == 'greater_than')
	{
		$op = ' < ';
		$right = $field->operandValues->menu;
	}
	else if($operator == 'less_than_equal')
	{
		$op = ' <= ';
		$right = $field->operandValues->menu;
	}
	else if($operator == 'greater_than_equal')
	{
		$op = ' <= ';
		$right = $field->operandValues->menu;
	}
	else if($operator == 'in_the_range')
	{
		$all = "($left >= {$field->operandValues->menu1} AND $left <= {$field->operandValues->menu2})";
		return $all;
	}
	else
	{
		trigger_error("unrecognized operator '$operator'");
	}
	
	return "($left $op $right)";
}
