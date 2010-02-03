<?php
class GraphicStyleStack
{
	private $stack;
	
	function __construct()
	{
		$this->stack = array(new GraphicStyle());
	}
	
	public function cloneTop()
	{
		$this->push(clone $this->getTopStyle());
		return $this->getTopStyle();
	}
	
	private function push($style)
	{
		array_push($this->stack, $style);
	}
	
	public function pop()
	{
		array_pop($this->stack);
	}
	
	public function getTopStyle()
	{
		return $this->stack[ count($this->stack) - 1 ];
	}
}
