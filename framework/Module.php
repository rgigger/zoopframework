<?php
class AppModule
{
	function getClasses()
	{
		return array();
	}
	
	function registerClasses()
	{
		$classes = $this->getClasses();
		foreach($classes as $class => $path)
		{
			zoop::registerClass($class, $path);
		}
	}
}