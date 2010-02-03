<?php
abstract class GraphicContainer extends GraphicObject
{
	protected $children = array();
	
	function getNewChild($type)
	{
		$newChild = new $type($this);
		assert(is_a($newChild, 'GraphicObject'));
		$this->children[] = $newChild;
		return $newChild;
	}
	
	function isInline()
	{
		return false;
	}
	
	function getObjectTree($indentLevel = 0)
	{
		$tabs = '';
		for($i = 0; $i < $indentLevel; $i++)
			$tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		echo $tabs . '&lt;' . get_class($this) . '&gt;<br>';
		foreach($this->children as $thisChild)
		{
			$thisChild->getObjectTree($indentLevel + 1);
		}
		echo $tabs . '&lt;/' . get_class($this) . '&gt;<br>';
	}
}
