<?php
class FormBinding
{
	private $class;
	private $id;
	private $field;
	
	function __construct($class, $id, $field)
	{
		$this->class = $class;
		$this->id = $id;
		$this->field = $field;
	}
	
	public function getName()
	{
		return "_zoop_form_element[{$this->class}][{$this->id}][{$this->field}]";
	}
	
	public function getString()
	{
		return "{$this->class}:{$this->id}:{$this->field}";
	}
}
