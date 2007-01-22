<?php
class TodoListCollection
{
	var $dir;
	
	function TodoListCollection($dir)
	{
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
				//$lists[] = $parser->parseFile($this->dir . '/' . $entry);
				$listNames[] = $entry;
		}
		$d->close();
		
		return $listNames;
	}
}