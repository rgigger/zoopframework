<?php
class GraphicParser extends XmlParser
{
	private $context;
	private $pageSize;
	private $pageOrientation;
	private $documentSize = PdfContext::sizeA4;
	
	function __construct($contextType)
	{
		parent::__construct();
		
		switch($contextType)
		{
			case 'ZendPdf':
				$this->context = new ZendPdfContext();
				break;
			
			default:
				trigger_error("unhandled context type specified: $contextType");
				break;
		}
	}
	
	function parseText($xml)
	{
		$document = parent::parseText($xml);
		$document->getContext()->init($this->documentSize);
		$document->setMaxWidth($document->getContext()->getWidth());
		return $document;
	}
	
	protected function initRootContainer()
	{
		return new GraphicDocument($this->context);
	}
	
	protected function initExtra()
	{
		return new GraphicStyleStack();
	}
	
	function handleTextNode($node, $container, $styleStack)
	{
		$textRun = $container->getNewTextRun();
		echo_r($node->getTextContent());
		$textRun->setTextInfo($node->getTextContent());
		// echo $textRun;
		$textRun->setStyle($styleStack->getTopStyle());
		
		// echo 'handleTextNode<br>';
		// $this->container->drawRenderTree();
		
		// echo_r($container);
		// echo_r($node->getTextContent());
		// echo_r($extra);
		// die();
	}
	
	protected function preNodeHandler($node, $container, $styleStack)
	{
		$topStyle = $styleStack->cloneTop();
		$this->applyStyleInfo($node, $topStyle);
	}
	
	protected function postNodeHandler($node, $container, $styleStack)
	{
		$styleStack->pop();
	}
	
	private function applyStyleInfo($node, $style)
	{
		if($node->hasAttribute('style'))
		{
			$styleText = $node->getAttribute('style');
			$styleAttributes = explode(';', $style);

			$styleInfo = array();
			foreach($styleAttributes as $thisAttribute)
			{
				if(trim($thisAttribute))
				{
					$styleAttParts = explode(':', $thisAttribute);
					$styleAttName = strtolower(trim($styleAttParts[0]));
					$styleAttValue = strtolower(trim($styleAttParts[1]));
					$styleInfo[$styleAttName] = $styleAttValue;
				}
			}

			$style->add($styleInfo);
		}
	}
	
	protected function handleMeta($node, $container, $extra)
	{
		if(strtolower($node->getAttribute('name')) == 'pagesize')
		{
			if(strtolower($node->getAttribute('content')) == 'a4')
				$this->pageSize = 'a4';
			if(strtolower($node->getAttribute('content')) == 'letter')
				$this->pageSize = 'letter';
			else
				$this->documentSize = $node->getAttribute('content');
		}
		else if(strtolower($node->getAttribute('name')) == 'orientation')
		{
			if(strtolower($node->getAttribute('content')) == 'portrait')
				$this->pageOrientation = 'portrait';
			if(strtolower($node->getAttribute('content')) == 'landscape')
				$this->pageOrientation = 'landscape';
			else
				trigger_error("invalid orientation: " . $node->getAttribute('content'));
		}
		
		if($this->pageSize == 'a4' && $pageOrientation == 'portrait')
			$this->documentSize = PdfContext::sizeA4;
		else if($this->pageSize == 'a4' && $pageOrientation == 'landscape')
			$this->documentSize = PdfContext::sizeA4Landscape;
		else if($this->pageSize == 'letter' && $pageOrientation == 'portrait')
			$this->documentSize = PdfContext::sizeLetter;
		else if($this->pageSize == 'letter' && $pageOrientation == 'landscape')
			$this->documentSize = PdfContext::sizeLetterLandscape;
	}
	
	protected function handleDiv($node, $container, $extra)
	{
		$div = $container->getNewDiv();
		return $div;
	}
	
	protected function handleB($node, $container, $extra)
	{
		return $container;
	}
	
	/*
	private function __call($name, $arguments)
	{
		if(str_prefix($name, 'handle'))
		{
			
		}
		
		trigger_error("method call to $name not handled");
	}
	*/
}
