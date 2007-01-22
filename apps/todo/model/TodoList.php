<?php
class TodoList
{
	var $root;
	
	function TodoList(&$root)
	{
		$this->root = $root;
	}
	
	function &getRoot()
	{
		return $this->root;
	}
}