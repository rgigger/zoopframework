<?php
class GraphicStyle
{
	private $styleInfo;
	
	function __construct($styleInfo = array())
	{
		$this->styleInfo = $styleInfo;
	}
	
	public function add($styleInfo)
	{
		$this->styleInfo = array_merge($this->styleInfo, $styleInfo);
	}
}
