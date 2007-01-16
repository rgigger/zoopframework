<?php
class TodoListItem
{
	var $parent;
	var $children;
	var $text;
	var $status;
	var $importance;
	var $urgency;
	
	function TodoListItem(&$parent, $status, $text, $params = NULL)
	{
		$this->children = array();
		$this->parent = &$parent;
		if($parent)
			$parent->addChild($this);
		
		$this->status = $status;
		$this->text = $text;
	}
	
	function addChild(&$child)
	{
		$this->children[] = &$child;
	}
}