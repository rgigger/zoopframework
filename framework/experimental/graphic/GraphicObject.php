<?php
abstract class GraphicObject
{
	protected $context;
	protected $parent;
	
	function __construct($init)
	{
		if(is_a($init, 'GraphicContainer'))
		{
			$this->parent = $init;
			$this->context = $init->getContext();
		}
		else
		{
			assert(is_a($init, 'GraphicContext'));
			$this->context = $init;
		}
	}
	
	//	property accessors
	function getContext()
	{
		return $this->context;
	}
	
	function isInline()
	{
		return true;
	}
	
	function getHeight($width)
	{
		$height = $this->draw(0, 0, $width, 0);
		return $height;
	}
	
	public function draw($x, $y, $width, $reallyDraw = true)
	{
		trigger_error("soft abstract function");
	}
	
	function getObjectTree($indentLevel = 0)
	{
		$tabs = '';
		for($i = 0; $i < $indentLevel; $i++)
			$tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		echo $tabs . '&lt;' . get_class($this) . '/&gt;<br>';
	}
	
	/*
	var $parent;
	var $width;
	var $position;
	var $left;
	var $top;
	var $repeatable;
	
	function GraphicObject(&$context)
	{
		$this->context = &$context;
		$this->position = 'static';
		$this->left = NULL;
		$this->top = NULL;
		$this->repeatable = 0;
	}
	
	function forcePageBreak()
	{
		return 0;
	}
	
	function makeRepeatable()
	{
		$this->repeatable = 1;
	}
	
	function isRepeatable()
	{
		return $this->repeatable;
	}
	
	function setParent(&$parent)
	{
		$this->parent = &$parent;
	}
	
	function &getParent()
	{
		return $this->parent;
	}
	
	function getAncestor($className)
	{
		$parent = &$this;
		
		while($parent)
		{
			if(is_a($parent, $className))
				return $parent;
			
			$parent = &$parent->parent;
		}
		
		return NULL;
	}
	
	function getDocument()
	{
		return $this->getAncestor('GraphicDocument');
	}
	
	function setWidth($width)
	{
		$this->width = $width;
	}
	
	function getWidth()
	{
		return $this->width;
	}
	
	function getContentWidth()
	{
		return $this->getWidth();
	}
	*/
	
	/*
	function getHeight($width)
	{
		if( !isset($this->heightCache[$width]) )
			$this->heightCache[$width] = $this->draw(0, 0, $width, 0);
		
		return $this->heightCache[$width];
	}
	*/
	
	/*
	function getHeight($width)
	{
		$height = $this->draw(0, 0, $width, 0);
//		echo get_class($this) . ' ' . $height . '<br>';
		return $height;
	}
	
	function isInline()
	{
		return 0;
	}
	
	function isBreakable()
	{
		return 0;
	}
	
	function setPosition($position)
	{
		$this->position = $position;
	}
	
	function getPosition()
	{
		return $this->position;
	}
	
	function setTop($top)
	{
		assert($this->getPosition() != 'static');
		$this->top = $top;
	}
	
	function getTop()
	{
		return $this->top;
	}
	
	function setLeft($left)
	{
		assert($this->getPosition() != 'static');
		$this->left = $left;
	}
	
	function getLeft()
	{
		return $this->left;
	}
	
	function doneDrawing()
	{
		return 1;
	}
	
	function draw()
	{
		trigger_error('abstract method GraphicObject::draw() called');
	}
	*/
}
