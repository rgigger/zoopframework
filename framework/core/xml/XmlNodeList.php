<?php
class XmlNodeList implements Iterator
{
	var $nodeList;
	
	function XmlNodeList($inNodeData)
	{
		$this->nodeList = $inNodeData->firstChild;
	}
	
	function rewind()
	{
		if($this->valid())
			$this->nodeList = $this->nodeList->parentNode->firstChild;
	}
	
	function current()
	{
		return is_null($this->nodeList) ? false : new XmlNode($this->nodeList);
	}
	
	function key()
	{
		trigger_error("I don't think that we actually use this");
		return key($this->nodeList);
	}
	
	function next()
	{
		$this->nodeList = $this->nodeList->nextSibling;
		return is_null($this->nodeList) ? false : new XmlNode($this->nodeList);
	}
	
	function valid()
	{
		return $this->current() !== false;
	}
}