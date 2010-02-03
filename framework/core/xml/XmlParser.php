<?php
abstract class XmlParser
{
	private $dom;
	protected $container;
	
	function __construct()
	{
		$this->dom = new XmlDom();
	}
	
	function parseText($xml)
	{
		$xml = "<xml>$xml</xml>";
		$root = $this->dom->parseText($xml);
		return $this->handleRoot($root);
	}
	
	function parseFile($filename)
	{
		$root = $this->dom->parseFile($filename);
		return $this->handleRoot($root);
	}
	
	protected abstract function initExtra();
	protected abstract function handleTextNode($child, $container, $extra);
	
	private function handleRoot($root)
	{
		// echo 'handleRoot 1<br>';
		$this->container = $this->initRootContainer();
		// echo 'handleRoot 2<br>';
		
		$extra = $this->initExtra();
		$this->handleNode($root, $this->container, $extra);
		
		return $this->container;
	}
	
	protected function handleXml($node, $container, $extra)
	{
		return $container;
	}
	
	private function handleNode($node, $container, $extra)
	{
		$this->preNodeHandler($node, $container, $extra);
		
		$tagName = $node->getName();
		$handler = "handle$tagName";
		$container = $this->$handler($node, $container, $extra);
		
		foreach($node->children as $child)
		{
			if($child->isText())
				$this->handleTextNode($child, $container, $extra);
			else
				$this->handleNode($child, $container, $extra);
		}
		
		$this->postNodeHandler($node, $container, $extra);
	}
}
