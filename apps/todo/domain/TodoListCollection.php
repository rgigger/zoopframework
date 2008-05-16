<?php
class TodoListCollection extends Object
{
	var $dir;
	
	function __construct($dir)
	{
		$this->addGetter('todoListNames');
		$this->dir = $dir;
	}
	
	function getTodoListNames()
	{
		//$parser = new TodoListParser();
		//$lists = array();
		
		$listNames = array();
		
		$d = dir($this->dir);
		while(false !== ($entry = $d->read()))
		{
			$parts = explode('.', $entry);
			if(array_pop($parts) == 'todo')
				$listNames[] = $entry;
		}
		$d->close();
		
		return $listNames;
	}
}
