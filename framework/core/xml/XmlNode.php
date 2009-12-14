<?php
class XmlNode
{
	var $nodeData;
	
	function XmlNode($inNodeData)
	{
		$this->nodeData = $inNodeData;
	}
	
	function getChildren()
	{
		$nodeList = &new XmlNodeList($this->nodeData);
		$nodeList->rewind();
		return $nodeList;
	}
	
	function getName()
	{
		return $this->nodeData->nodeName;
	}
	
	function isText()
	{
		return $this->nodeData->nodeType === XML_TEXT_NODE ? true : false;
	}
	
	function hasContent()
	{
		if($this->nodeData->nodeType === XML_TEXT_NODE)
			return true;
		
		return false;
		
		return $content ? 1 : 0;
	}
	
	function getContent()
	{
		$results = array();
		
		if($this->nodeData->nodeType === XML_TEXT_NODE)
		{
			$originalContent = ereg_replace('&#([0-9]*);', '', $this->nodeData->textContent);
			$originalContent = html_entity_decode($originalContent);
		}
		else
		{
			trigger_error('only use content of text nodes.  Look at children for this info otherwise');
		}
		//$originalContent = str_replace('&#160;', '', $this->nodeData->content);
		
		$leftContent = ltrim($originalContent);
		if(strlen($originalContent) == strlen($leftContent))
			$results['leftTrim'] = 0;
		else
			$results['leftTrim'] = 1;
		
		$content = rtrim($leftContent);
		if(strlen($leftContent) == strlen($content))
			$results['rightTrim'] = 0;
		else
			$results['rightTrim'] = 1;
		
		$results['content'] = $content;
		
		return $results;
	}
	
	function getTextContent()
	{
		$results = array();
		
		if($this->nodeData->nodeType === XML_TEXT_NODE)
		{
			$originalContent = ereg_replace('&#([0-9]*);', '', $this->nodeData->textContent);
			$originalContent = html_entity_decode($originalContent);
		}
		else
		{
			//	if there is exactly one text node return the contents of that text node
			//	otherwise throw an error
			$children = $this->getChildren();
			$onlyChild = $children->current();
			assert($onlyChild);						//	throw an error if there is not child
			assert(!$children->next());				//	throw an error if there is more than one child
			$content = $onlyChild->getContent();	//	throw an error if the only is not a text node
			$originalContent = ereg_replace('&#([0-9]*);', '', $content['content']);	
			$originalContent = html_entity_decode($originalContent);
		}
		//$originalContent = str_replace('&#160;', '', $this->nodeData->content);
		
		$leftContent = ltrim($originalContent);
		if(strlen($originalContent) == strlen($leftContent))
			$results['leftTrim'] = 0;
		else
			$results['leftTrim'] = 1;
		
		$content = rtrim($leftContent);
		if(strlen($leftContent) == strlen($content))
			$results['rightTrim'] = 0;
		else
			$results['rightTrim'] = 1;
		
		$results['content'] = $content;
		
		return $results;
	}
	
	function hasAttribute($attributeName)
	{
			if( $this->nodeData->hasAttribute($attributeName) )
				return 1;
			else
				return 0;
	}
	
	function getAttribute($attributeName)
	{
		$att = $this->nodeData->attributes->getNamedItem($attributeName);
		if( !is_null($att) )
			return $att->value;
		else
			trigger_error('attribute does not exist: ' . $attributeName);
	}
	
	function getAttributes()
	{
		$atts = array();
		foreach($this->nodeData->attributes as $key => $val)
		{
			$atts[$key] = $val->value;
		}
		
		return $atts;
	}
	
	function __get($name)
	{
		if($name == 'children')
			return $this->getChildren();
	}
}
