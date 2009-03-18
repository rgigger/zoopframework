<?php
class FormModule extends ZoopModule
{
	protected function init()
	{
	}
	
	protected function getDepends()
	{
		array('session', 'gui');
	}
	
	protected getClasses()
	{
		return array('Form', 'FormElement');
	}
}
